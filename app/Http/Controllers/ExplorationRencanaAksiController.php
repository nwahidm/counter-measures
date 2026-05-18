<?php

namespace App\Http\Controllers;

use App\Models\ExplorationRencanaAksi;
use App\Models\ExplorationTargetIdentity;
use App\Models\ExplorationResultAchievment;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\Models\Documents;
use Illuminate\Http\Request;
use App\DataTables\ExplorationRencanaAksiDataTable;
use App\Http\Controllers\Controller;
use App\Models\CloseCase;
use App\Models\Observation\ObservCollectInfo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Models\MasterSatker;

class ExplorationRencanaAksiController extends Controller
{

    public function __construct()
    {
        Carbon::setLocale('id');
    }
    /**
     * Display a listing of the resource.
     */
    
    public function index(ExplorationRencanaAksiDataTable $dataTable)
    {
        //
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.exploration.rencanaaksi.index', compact('satker', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $user = auth()->user();
        $idSatker = $user->satker?->id_satker;
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();
        // $cek = CaseCloseProgresses
        $case = CloseCase::join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            ->when(
                !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                function ($q) use ($idSatker) {
                    $q->where('satker_id', $idSatker);
                }
            )
            // ->where('case_close_progresses.delineation_laporan', '1')
            ->get();

        return view('backoffice.close.exploration.rencanaaksi.create', compact('satker', 'case'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_satker' => 'required',
            'case_id' => 'required|string|max:255',
            'rencana_aksi_data' => 'required|string|max:255',
            // 'rencana_aksi_detail' => 'required',
            'rencana_aksi_upload' => 'required|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = new ExplorationRencanaAksi;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->rencana_aksi_data = $request->rencana_aksi_data;
        $data->rencana_aksi_detail = $request->rencana_aksi_detail;

        $document_pdf = new Documents;
        if ($request->hasFile('rencana_aksi_upload')) {
            $ext_upload_info = $request->file('rencana_aksi_upload')->extension();
            $upload_info = $request->file('rencana_aksi_upload')
                ->storePubliclyAs(
                    'close/exploration/rencanaaksi/upload',
                    Str::slug('exploration-rencana-aksi', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->rencana_aksi_upload = $upload_info;
            $document_pdf->doc_path = $upload_info;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_rencana_aksi' => "1",
                'status' => "Rencana Aksi",
                'substatus' => "Penambahan Rencana Aksi",
                'percentage' => round((10/29)*100,2)
                // 'percentage' => 25
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_rencana_aksi' => "1",
                'status' => "Rencana Aksi",
                'substatus' => "Penambahan Rencana Aksi",
                'percentage' => round((29/29)*100,2)
                // 'percentage' => 25
            ]);

        }
        
        if ($data->save()) {
            $data1 = ExplorationRencanaAksi::where('satker_id', auth()->user()->satker->id_satker)
            ->orderby('created_at','DESC')->first();

            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $request->case_id;
            $data_case_close_historical_update->action = "Penambahan Rencana Aksi";
    
            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();
    

            $document_pdf->relation_id = $data1->id_exploration_rencana_aksi;
            
            $document_pdf->save();
            
            
            return redirect()->route('close.exploration.rencana-aksi.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $data = ExplorationRencanaAksi::find($id);
        $summary = DB::table('documents')->where('relation_id',$id)->first();

        return view('backoffice.close.exploration.rencanaaksi.show', compact('data','summary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $data = ExplorationRencanaAksi::where('id_exploration_rencana_aksi', $id)->first();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCloseCase();
        $satker = DataHelper::getSatker();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $case_id = $data->case->id;
        $surat_perintah = DataHelper::getCloseSprint($case_id);

        return view('backoffice.close.exploration.rencanaaksi.edit', compact('data', 'users', 'case', 'satker','surat_perintah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            // 'id_satker' => 'required',
            'case_id' => 'required|string|max:255',
            'rencana_aksi_data' => 'required|string|max:255',
            // 'rencana_aksi_detail' => 'required',
            'rencana_aksi_upload' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = ExplorationRencanaAksi::where('id_exploration_rencana_aksi', $id)->first();
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->rencana_aksi_data = $request->rencana_aksi_data;
        $data->rencana_aksi_detail = $request->rencana_aksi_detail;

        if ($request->hasFile('rencana_aksi_upload')) {
            $ext_upload_sprint = $request->file('rencana_aksi_upload')->extension();
            $upload_sprint = $request->file('rencana_aksi_upload')
                ->storePubliclyAs(
                    'close/exploration/rencanaaksi/upload',
                    Str::slug('exploration-rencana-aksi', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );
            $data->rencana_aksi_upload = $upload_sprint;

            $document_pdf = Documents::where('relation_id',$id)->first();
            $document_pdf->doc_path = $upload_sprint;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->relation_id = $id;            
            $document_pdf->save();
        } else {
            $information_collection_upload = $request->temp_rencana_aksi_upload;

            $data->rencana_aksi_upload = $information_collection_upload;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_rencana_aksi' => "1",
                'status' => "Rencana Aksi",
                'substatus' => "Penambahan Rencana Aksi",
                'percentage' => round((29/29)*100,2)
                // 'percentage' => 25
            ]);
        }

        if ($data->update()) {

            return redirect()->route('close.exploration.rencana-aksi.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = ExplorationRencanaAksi::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $data->delete();
        ExplorationTargetIdentity::where('exploration_rencana_aksi_id', $id)->delete();
        ExplorationResultAchievment::where('exploration_rencana_aksi_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
