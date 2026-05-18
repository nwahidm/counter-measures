<?php

namespace App\Http\Controllers;

use App\Models\ExplorationResultAchievment;
use App\Models\ExplorationRencanaAksi;
use App\Models\ExplorationTargetIdentity;
use App\Models\CaseCloseProgresses;
use App\Models\Documents;
use App\Models\CaseCloseEventHistoricalUpdates;
use Illuminate\Http\Request;
use App\DataTables\ExplorationResultAchievmentDataTable;
use App\DataTables\ExplorationReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\CloseCase;
use App\Models\Observation\ObservCollectInfo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Models\MasterSatker;

class ExplorationResultAchievmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    public function index(ExplorationResultAchievmentDataTable $dataTable)
    {
        //
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.exploration.hasilcapaian.index', compact('satker', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $satker = DataHelper::getSatker();
        // $case = DataHelper::getCloseCase();
        $case = DataHelper::getCloseCase();
        $target = ExplorationTargetIdentity::select('target_name','id_exploration_target_identity')->get();
        $rencana = ExplorationRencanaAksi::select('rencana_aksi_data','id_exploration_rencana_aksi')->get();
        return view('backoffice.close.exploration.hasilcapaian.create', compact('satker', 'case','target','rencana'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'id_satker' => 'required',
            'case_id' => 'required|string|max:255',
            'exploration_rencana_aksi_id' => 'nullable|string',
            'exploration_target_identity_id' => 'nullable|string',
            'hasil_yang_dicapai' => 'required|string',
            'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();
        
        $data = new ExplorationResultAchievment;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->exploration_rencana_aksi_id = $request->exploration_rencana_aksi_id;
        $data->exploration_target_identity_id = $request->exploration_target_identity_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        $document_pdf = new Documents;
        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_info = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_info = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'close/exploration/hasildicapai/upload',
                    Str::slug('exploration-hasil-dicapai', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->upload_hasil_yang_dicapai = $upload_info;
            $document_pdf->doc_path = $upload_info;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_hasil_yang_dicapai' => "1",
                'exploration_laporan' => "1",
                'status' => "Hasil Dicapai",
                'substatus' => "Penambahan Hasil Dicapai",
                'percentage' => round((10/29)*100,2)
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_hasil_yang_dicapai' => "1",
                'exploration_laporan' => "1",
                'status' => "Hasil Dicapai",
                'substatus' => "Penambahan Hasil Dicapai",
                'percentage' => round((29/29)*100,2)
            ]);

        }

        if ($data->save()) {
            $data1 = ExplorationResultAchievment::where('satker_id', auth()->user()->satker->id_satker)
            ->orderby('created_at','DESC')->first();

            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $request->case_id;
            $data_case_close_historical_update->action = "Penambahan Hasil Dicapai";
    
            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();
    

            $document_pdf->relation_id = $data1->id_exploration_result_achievement;
            
            $document_pdf->save();

            
            return redirect()->route('close.exploration.hasil-pencapaian.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $data = ExplorationResultAchievment::
        with(['explorationTargetIdentitas', 'explorationRencanaAksi', 'case', 'satker'])
        ->find($id);
        
        $summary = DB::table('documents')->where('relation_id',$id)->first();

        return view('backoffice.close.exploration.hasilcapaian.show', compact('data','summary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $data = ExplorationResultAchievment::find($id);
        $case = DataHelper::getCloseCase();
        $satker = DataHelper::getSatker();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $case_id = $data->case?->id;
        
        $target = ExplorationTargetIdentity::select('target_name','id_exploration_target_identity')->where('exploration_rencana_aksi_id', $data->exploration_rencana_aksi_id)->whereNotNull('exploration_rencana_aksi_id')->get();
        $rencana = ExplorationRencanaAksi::select('rencana_aksi_data','id_exploration_rencana_aksi')->where('case_id', $data->case_id)->get();

        return view('backoffice.close.exploration.hasilcapaian.edit', compact('data', 'case', 'satker','rencana','target'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            // 'id_satker' => 'required',
            'exploration_rencana_aksi_id' => 'nullable|string',
            'exploration_target_identity_id' => 'nullable|string',
            'case_id' => 'required|string|max:255',
            'hasil_yang_dicapai' => 'required|string',
            'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:20480'
        ]);

        $user = auth()->user();

        $data = ExplorationResultAchievment::find($id);
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->exploration_rencana_aksi_id = $request->exploration_rencana_aksi_id;
        $data->exploration_target_identity_id = $request->exploration_target_identity_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_sprint = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_sprint = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'close/exploration/hasildicapai/upload',
                    Str::slug('exploration-hasil-dicapai', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );
                $data->upload_hasil_yang_dicapai = $upload_sprint;

                $document_pdf = Documents::where('relation_id',$id)->first();
                $document_pdf->doc_path = $upload_sprint;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $id;            
                $document_pdf->save();
        } else {
            $information_collection_upload = $request->temp_upload_hasil_yang_dicapai;

            $data->upload_hasil_yang_dicapai = $information_collection_upload;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_hasil_yang_dicapai' => "1",
                'exploration_laporan' => "1",
                'status' => "Hasil Dicapai",
                'substatus' => "Penambahan Hasil Dicapai",
                'percentage' => round((29/29)*100,2)
            ]);;

        }

        if ($data->update()) {
            
            return redirect()->route('close.exploration.hasil-pencapaian.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $data = ExplorationResultAchievment::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $data->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

    public function report(ExplorationReportDataTable $dataTable)
    {
        //
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.exploration.report.index', compact('satker', 'users'));
    }

    public function downloadReport($id)
    {
        // $data = OpenCase::find(decrypt($id_case));
        $data = CloseCase::join('master_satker', DB::raw("CAST(master_satker.id_satker AS bigint)"), DB::raw("CAST(close_case.satker_id AS bigint)"))
                            // ->join('master_agama', 'master_agama.kode', 'close_case.target_religion')
                            // ->selectRaw('close_case.*, master_satker.*, master_agama.nama as nama_agama')
                            ->selectRaw('close_case.*, master_satker.*')
                            ->where('id', $id)
                            ->first();
        $satker = MasterSatker::findOrFail($data->satker_id);
        $explorationrencanaaksi = ExplorationRencanaAksi::where('case_id', $data->id)
                            ->first();
        $explorationresult = ExplorationResultAchievment::where('case_id', $data->id)
                            ->first();
        $explorationtarget = ExplorationTargetIdentity::where('case_id', $data->id)
                            ->first();
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        $data = CloseCase::findOrFail($id);
        $data->target_photo = json_decode($data->target_photo, true);
        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.close.exploration.report.pdf", compact(
            'data',
            'explorationrencanaaksi',
            'satker',
            'explorationresult',
            'explorationtarget')));

        $filename = 'Close_Exploration_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
}
