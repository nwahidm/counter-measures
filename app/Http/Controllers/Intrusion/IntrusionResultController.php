<?php

namespace App\Http\Controllers\Intrusion;

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
use App\Models\Intrusion\IntrusionResult;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\DataTables\Intrusion\IntrusionResultDataTable;
use App\Helpers\IntrusionDataHelper;

class IntrusionResultController extends Controller
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
    public function index(IntrusionResultDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.intrusion.result.index', compact('satker', 'users'));
    }

    // API
    public function list(Request $request)
    {
        $user = Auth::user();
        $idSatker = $user->satker->id_satker;

        $data = IntrusionResult::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('intrusion_hasil_yang_dicapai.satker_id', '=', $idSatker);
                                })
                                ->with(['satker', 'case', 'location', 'environment'])
                                ->latest()
                                ->paginate(10);
        return response()->json($data);
    }
    public function individual($id)
    {

        $data = IntrusionResult::with(['satker', 'case', 'location', 'environment'])
                                ->findOrFail($id);
        return response()->json($data);
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();

        return view('backoffice.close.intrusion.result.create', compact('case', 'satker'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'satker_id' => 'required|string|max:128',
            'case_id' => 'required|string|max:128',
            // 'intrusion_target_location_id' => 'required|string|max:255',
            // 'intrusion_target_environment_id' => 'required|string|max:255',
            'hasil_yang_dicapai' => 'required|string',
            'upload_result' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = new IntrusionResult;
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->intrusion_target_location_id = $request->intrusion_target_location_id;
        $data->intrusion_target_environment_id = $request->intrusion_target_environment_id;

        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        if ($request->hasFile('upload_result')) {
            $ext_upload_result = $request->file('upload_result')->extension();
            $upload_result = $request->file('upload_result')
                ->storePubliclyAs(
                    'close/intrusion/result/upload_result',
                    Str::slug('intrusion result', '_') . '_' . Str::random() . '.' . $ext_upload_result,
                    'public'
                );

            $data->upload_hasil_yang_dicapai = $upload_result;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;


        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_hasil_yang_dicapai' => "1",
                'intrusion_laporan' => "1",
                'status' => $close_case_progress->percentage > 86 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 86 ? $close_case_progress->substatus : 'Input Hasil Penyurupan',
                'percentage' => $close_case_progress->percentage > 86 ? $close_case_progress->percentage : 86
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_hasil_yang_dicapai' => "1",
                'intrusion_laporan' => "1",
                'status' => $close_case_progress->percentage > 86 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 86 ? $close_case_progress->substatus : 'Input Hasil Penyurupan',
                'percentage' => 100
            ]);
        }

        if ($data->save()) {
            // save doc analysis
            if($data->upload_hasil_yang_dicapai){
                DataHelper::insertDocument($data->id, $data->upload_hasil_yang_dicapai);
            }
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Hasil Penyurupan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            // update progress
            
            return redirect()->route('close.intrusion.result.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = IntrusionResult::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $document_pdf_data = Documents::where('relation_id', $data->id)->first();

        return view('backoffice.close.intrusion.result.show', compact('data', 'document_pdf_data'));
    }

    public function edit(Request $request, $id)
    {
        $data = IntrusionResult::find($id);
        $satker = DataHelper::getSatker();

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $case = DataHelper::getCloseCase();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $location = DataHelper::getClosTargetLoc($data->case?->id);
        $environment = DataHelper::getClosTargetEnv($data->location?->id);

        return view('backoffice.close.intrusion.result.edit', compact('data', 'satker', 'case', 'location', 'environment'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'satker_id' => 'required|string|max:128',
            'case_id' => 'required|string|max:128',
            // 'intrusion_target_location_id' => 'required|string|max:255',
            // 'intrusion_target_environment_id' => 'required|string|max:255',
            'hasil_yang_dicapai' => 'required|string',
            'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:20480'
        ]);

        $user = auth()->user();

        $data = IntrusionResult::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        // $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->intrusion_target_location_id = $request->intrusion_target_location_id;
        $data->intrusion_target_environment_id = $request->intrusion_target_environment_id;

        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'close/intrusion/result/upload_result',
                    Str::slug('intrusion result', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
                    'public'
                );

            if($request->temp_upload_hasil_yang_dicapai){
                if (Storage::disk('public')->exists($request->temp_upload_hasil_yang_dicapai)) {
                    Storage::disk('public')->delete($request->temp_upload_hasil_yang_dicapai);
                }
            }

            // save doc analysis
            DataHelper::insertDocument($data->id, $upload_hasil_yang_dicapai, $request->temp_upload_hasil_yang_dicapai);
            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
        } else {
            $upload_hasil_yang_dicapai = $request->temp_upload_hasil_yang_dicapai;

            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_hasil_yang_dicapai' => "1",
                'intrusion_laporan' => "1",
                'status' => $close_case_progress->percentage > 86 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 86 ? $close_case_progress->substatus : 'Input Hasil Penyurupan',
                'percentage' => 100
            ]);
        }

        if ($data->update()) {
            return redirect()->route('close.intrusion.result.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = IntrusionResult::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if($data->upload_hasil_yang_dicapai){
            if (Storage::disk('public')->exists($data->upload_hasil_yang_dicapai)) {
                Storage::disk('public')->delete($data->upload_hasil_yang_dicapai);
            }
        }

        $data->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function listSatker(Request $request)
    {
        $satker = auth()->user()->satker;
        $tipeSatker = (int)$satker->tipe_satker;

        return optSatkerWithChild($satker->kode_satker, 1, range($tipeSatker, 4));
    }

    

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
