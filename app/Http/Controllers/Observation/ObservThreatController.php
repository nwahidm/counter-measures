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
use Illuminate\Support\Facades\Storage;
use App\Models\Observation\ObservDirective;
use App\Models\Observation\ObservCollectInfo;
use App\Models\Observation\ObservThreat;
use App\Models\Observation\ObservConnect;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\DataTables\Observation\ObservThreatDataTable;
use App\Helpers\ObservationDataHelper;

class ObservThreatController extends Controller
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
    public function index(ObservThreatDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.observation.threat.index', compact('satker', 'users'));
    }

    // API
    public function list(Request $request)
    {
        $user = Auth::user();
        $idSatker = $user->satker->id_satker;

        $data = ObservThreat::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('observation_surat_perintah.satker_id', '=', $idSatker);
                                })
                                ->with(['satker', 'case', 'sprint', 'collectInfo'])
                                ->latest()
                                ->paginate(10);
        return response()->json($data);
    }
    public function individual($id)
    {

        $data = ObservThreat::with(['satker', 'case', 'sprint', 'collectInfo'])
                                ->findOrFail($id);
        return response()->json($data);
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $case = ObservationDataHelper::getCloseCaseByObservCollectInfo();

        return view('backoffice.close.observation.threat.create', compact('satker', 'case'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'satker_id' => 'required|string|max:255',
            'case_id' => 'required|string|max:255',
            // 'surat_perintah_id' => 'required|string|max:255',
            // 'information_collection_id' => 'required|string|max:255',
            'aght_type' => 'required|string|max:255',
            // 'aght_place' => 'required|string|max:255',
            // 'aght_time' => 'required',
            // 'perihal' => 'required|string',
            'keterangan' => 'required|string',
            // 'upload_aght' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = new ObservThreat;
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_id = $request->information_collection_id;

        $data->aght_type = $request->aght_type;
        $data->aght_place = $request->aght_place;
        $data->aght_time = $request->aght_time;
        $data->perihal = $request->perihal;
        $data->keterangan = $request->keterangan;

        if ($request->hasFile('upload_aght')) {
            $ext_upload_aght = $request->file('upload_aght')->extension();
            $upload_aght = $request->file('upload_aght')
                ->storePubliclyAs(
                    'close/observation/threat/upload_aght',
                    Str::slug('observation threat', '_') . '_' . Str::random() . '.' . $ext_upload_aght,
                    'public'
                );

            $data->aght_path = $upload_aght;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_potensi_aght' => "1",
                'status' => $close_case_progress->percentage > 13.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 13.5 ? $close_case_progress->substatus :  'Input Analisis AGHT Pengamatan',
                'percentage' => $close_case_progress->percentage > 13.5 ? $close_case_progress->percentage : 13.5
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_potensi_aght' => "1",
                'status' => $close_case_progress->percentage > 13.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 13.5 ? $close_case_progress->substatus :  'Input Analisis AGHT Pengamatan',
                'percentage' => 100
            ]);
        }

        if ($data->save()) {
            // save doc analysis
            if($data->aght_path){
                DataHelper::insertDocument($data->id, $data->aght_path);
            }
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Analisis AGHT Pengamatan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            
            return redirect()->route('close.observation.threat.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = ObservThreat::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $document_pdf_data = Documents::where('relation_id', $data->id)->first();

        return view('backoffice.close.observation.threat.show', compact('data', 'document_pdf_data'));
    }

    public function edit(Request $request, $id)
    {
        $data = ObservThreat::find($id);
        $satker = DataHelper::getSatker();

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCloseCase();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $surat_perintah = DataHelper::getCloseSprint($data->case?->id);
        $collect_info = DataHelper::getCollectInfo($data->sprint?->id);

        return view('backoffice.close.observation.threat.edit', compact('data', 'satker', 'case', 'surat_perintah', 'collect_info'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'satker_id' => 'required|string|max:255',
            'case_id' => 'required|string|max:255',
            // 'surat_perintah_id' => 'required|string|max:255',
            // 'information_collection_id' => 'required|string|max:255',
            'aght_type' => 'required|string|max:255',
            // 'aght_place' => 'required|string|max:255',
            // 'aght_time' => 'required',
            // 'perihal' => 'required|string',
            'keterangan' => 'required|string',
            // 'upload_aght' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = ObservThreat::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_id = $request->information_collection_id;

        $data->aght_type = $request->aght_type;
        $data->aght_place = $request->aght_place;
        $data->aght_time = $request->aght_time;
        $data->perihal = $request->perihal;
        $data->keterangan = $request->keterangan;

        if ($request->hasFile('aght_path')) {
            $ext_upload_sprint = $request->file('aght_path')->extension();
            $upload_sprint = $request->file('aght_path')
                ->storePubliclyAs(
                    'close/observation/threat/upload_sprint',
                    Str::slug('observation threat', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );

            if($request->temp_aght_path){
                if (Storage::disk('public')->exists($request->temp_aght_path)) {
                    Storage::disk('public')->delete($request->temp_aght_path);
                }
            }

            // save doc analysis
            DataHelper::insertDocument($data->id, $upload_sprint, $request->temp_aght_path);
            $data->aght_path = $upload_sprint;
        } else {
            $aght_path = $request->temp_aght_path;

            $data->aght_path = $aght_path;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_potensi_aght' => "1",
                'status' => $close_case_progress->percentage > 13.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 13.5 ? $close_case_progress->substatus :  'Input Analisis AGHT Pengamatan',
                'percentage' => 100
            ]);
        }
        if ($data->update()) {
            return redirect()->route('close.observation.threat.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = ObservThreat::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if($data->aght_path){
            if (Storage::disk('public')->exists($data->aght_path)) {
                Storage::disk('public')->delete($data->aght_path);
            }
        }

        $data->delete();
        ObservConnect::where('potensi_aght_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }


    

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
