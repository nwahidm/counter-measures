<?php

namespace App\Http\Controllers\Observation;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CloseCase;
use App\Models\Documents;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Helpers\ObservationDataHelper;
use Illuminate\Support\Facades\Storage;
use App\Models\Observation\ObservDirective;
use App\Models\Observation\ObservCollectInfo;
use App\Models\Observation\ObservThreat;
use App\Models\Observation\ObservConnect;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\DataTables\Observation\ObservCollectInfoDataTable;

class ObservCollectInfoController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ObservCollectInfoDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.observation.collect-info.index', compact('satker', 'users'));
    }

    // API
    public function list(Request $request)
    {
        $user = Auth::user();
        $idSatker = $user->satker->id_satker;

        $data = ObservCollectInfo::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('observation_surat_perintah.satker_id', '=', $idSatker);
                                })
                                ->with(['satker', 'case', 'sprint'])
                                ->latest()
                                ->paginate(10);
        return response()->json($data);
    }
    public function individual($id)
    {

        $data = ObservCollectInfo::with(['satker', 'case', 'sprint'])
                                ->findOrFail($id);
        return response()->json($data);
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();

        return view('backoffice.close.observation.collect-info.create', compact('satker', 'case'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'case_id' => 'required|string|max:255',
            'id_satker' => 'required|string|max:255',
            // 'surat_perintah_id' => 'required|string|max:255',
            'information_collection_source' => 'required|string|max:255',
            'information_collection_perihal' => 'required|string|max:255',
            // 'information_collection_date' => 'required|date',
            'information_collection_detail' => 'required',
            'upload_info' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = new ObservCollectInfo;
        $data->satker_id = $request->id_satker;

        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_source = $request->information_collection_source;
        $data->information_collection_perihal = $request->information_collection_perihal;
        $data->information_collection_date = $request->information_collection_date;
        $data->information_collection_detail = $request->information_collection_detail;

        if ($request->hasFile('upload_info')) {
            $ext_upload_info = $request->file('upload_info')->extension();
            $upload_info = $request->file('upload_info')
                ->storePubliclyAs(
                    'close/observation/collect-info/upload_info',
                    Str::slug('observation collect-info sprint', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->information_collection_upload = $upload_info;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_information_collection' => "1",
                'status' => 'Pengamatan',
                'substatus' => 'Input Pengumpulan Informasi Pengamatan',
                'percentage' => $close_case_progress->percentage > 9.0 ? $close_case_progress->percentage : 9.0
            ]);
            
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_information_collection' => "1",
                'status' => 'Pengamatan',
                'substatus' => 'Input Pengumpulan Informasi Pengamatan',
                'percentage' => 100
            ]);
            
        }


        if ($data->save()) {
            // dd($data->id, $data->information_collection_upload);
            // save doc analysis
            if($data->information_collection_upload){
                DataHelper::insertDocument($data->id, $data->information_collection_upload);
            }
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Pengumpulan Informasi Pengamatan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

           
            return redirect()->route('close.observation.collect-info.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = ObservCollectInfo::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $document_pdf_data = Documents::where('relation_id', $data->id)->first();

        return view('backoffice.close.observation.collect-info.show', compact('data', 'document_pdf_data'));
    }

    public function edit(Request $request, $id)
    {
        $data = ObservCollectInfo::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $case_id = $data->case->id;
        $surat_perintah = DataHelper::getCloseSprint($case_id);

        return view('backoffice.close.observation.collect-info.edit', compact('data', 'case', 'surat_perintah', 'satker'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'case_id' => 'required|string|max:255',
            'id_satker' => 'required|string|max:255',
            // 'surat_perintah_id' => 'required|string|max:255',
            'information_collection_source' => 'required|string|max:255',
            'information_collection_perihal' => 'required|string|max:255',
            // 'information_collection_date' => 'required|date',
            'information_collection_detail' => 'required',
            'information_collection_upload' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = ObservCollectInfo::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_source = $request->information_collection_source;
        $data->information_collection_perihal = $request->information_collection_perihal;
        $data->information_collection_date = $request->information_collection_date;
        $data->information_collection_detail = $request->information_collection_detail;

        if ($request->hasFile('information_collection_upload')) {
            $ext_upload_sprint = $request->file('information_collection_upload')->extension();
            $upload_sprint = $request->file('information_collection_upload')
                ->storePubliclyAs(
                    'close/observation/collect-info/upload_sprint',
                    Str::slug('observation collect-info', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );

            if($request->temp_information_collection_upload){
                if (Storage::disk('public')->exists($request->temp_information_collection_upload)) {
                    Storage::disk('public')->delete($request->temp_information_collection_upload);
                }
            }

            // save doc analysis
            DataHelper::insertDocument($data->id, $upload_sprint, $request->temp_information_collection_upload);
            $data->information_collection_upload = $upload_sprint;
        } else {
            $information_collection_upload = $request->temp_information_collection_upload;

            $data->information_collection_upload = $information_collection_upload;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_information_collection' => "1",
                'status' => 'Pengamatan',
                'substatus' => 'Input Pengumpulan Informasi Pengamatan',
                'percentage' => 100
            ]);
        }

        if ($data->update()) {
            return redirect()->route('close.observation.collect-info.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = ObservCollectInfo::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if($data->information_collection_upload){
            if (Storage::disk('public')->exists($data->information_collection_upload)) {
                Storage::disk('public')->delete($data->information_collection_upload);
            }
        }

        $data->delete();
        ObservThreat::where('information_collection_id', $id)->delete();
        ObservConnect::where('information_collection_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
