<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Documents;
use App\Models\CaseProgresses;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ElicitationResult;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseEventHistoricalUpdates;
use App\DataTables\Elicitation\ElicitationResultDataTable;

class ElicitationResultController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ElicitationResultDataTable $dataTable)
    {
        //
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();
        return $dataTable->render('backoffice.open.elicitation-result.index', compact('satker', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCaseValidElicitationRecord();
        $satker = DataHelper::getSatker();
        $elinterview = DataHelper::getElicitationInterview();
        $elintadfoll = DataHelper::getElicitationAdfoll();
        return view('backoffice.open.elicitation-result.create', compact('case','users','satker','elinterview','elintadfoll'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'id_case' => 'required',
            'id_satker' => 'required',
            'pendahuluan' => 'required',
            'pelaksanaan_kegiatan' => 'required',
            'kendala' => 'required',
            'analisa' => 'required',
            'kesimpulan' => 'required',
            'saran' => 'required',
            'petunjuk_pimpinan' => 'required',
            'upload_hasil_yang_dicapai' => 'required|mimes:pdf|max:30000',
        ]);

        if($request->id_elicitation_interview_result=="---Pilih Hasil Wawancara Elisitasi---"){
            $request->id_elicitation_interview_result = "";
        }
        if($request->id_elicitation_advice_and_followup=="------Pilih Saran Tindak Elisitasi---t---"){
            $request->id_elicitation_advice_and_followup = "";
        }

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = auth()->user();

        $data = new ElicitationResult();
        $data->satker_id = $request->id_satker;

        $data->case_id = $request->id_case;
        $data->petunjuk_pimpinan = $request->petunjuk_pimpinan;
        $data->elicitation_interview_result_id = $request->id_elicitation_interview_result;
        $data->elicitation_advice_and_follow_up_id = $request->id_elicitation_advice_and_followup;
        $data->pendahuluan = $request->pendahuluan;
        $data->pelaksanaan_kegiatan = $request->pelaksanaan_kegiatan;
        $data->kendala = $request->kendala;
        $data->analisa = $request->analisa;
        $data->kesimpulan = $request->kesimpulan;
        $data->saran = $request->saran;
        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'open/data/elicitation/result',
                    Str::slug('Elicitation result', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
                    'public'
                );

            $data->hasil_yang_dicapai_path = $upload_hasil_yang_dicapai;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        $data->satker_id = $user->id_satker;
        

        

        if ($data->save()) {

            $document_pdf = new Documents;
            $document_pdf->doc_path = $upload_hasil_yang_dicapai;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->relation_id = $data->id_elicitation_result;
            $document_pdf->created_by = $user->id;
            $document_pdf->updated_by = $user->id;
            $document_pdf->save();

            // $log = DataHelper::logUpdateCase($data->case_id, 'Penambahan Elisitasi Hasil yang Dicapai');
            if ($request->submit_type === 'save') {
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->elisitasi_hasil_yang_dicapai = 1;
                $updateCaseProgresses->elisitasi_laporan = 1;
                $updateCaseProgresses->status = 'Elicitation';
                $updateCaseProgresses->substatus = 'Penambahan Elisitasi Hasil yang Dicapai';
                // $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 100 ? $updateCaseProgresses->percentage : 100;
                $updateCaseProgresses->save();
            }else{
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->elisitasi_hasil_yang_dicapai = 1;
                $updateCaseProgresses->elisitasi_laporan = 1;
                $updateCaseProgresses->status = 'Elicitation';
                $updateCaseProgresses->substatus = 'Penambahan Elisitasi Hasil yang Dicapai';
                $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 100 ? $updateCaseProgresses->percentage : 100;
                $updateCaseProgresses->save();

            }
            

            $cp = CaseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
            $cp->action = 'Penambahan Elisitasi Hasil yang Dicapai';
            $cp->created_by = $user->id;
            $cp->save();

            return redirect()->route('open.data.elicit-result.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        //
        $data = ElicitationResult::find($id);
        $summary = Documents::where('relation_id',$id)->first();
        return view('backoffice.open.elicitation-result.show', compact('data','summary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
        $data = ElicitationResult::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCaseValidElicitationRecord();
        $elinterview = DataHelper::getElicitationInterview();
        $elintadfoll = DataHelper::getElicitationAdfoll();
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.open.elicitation-result.edit', compact('data', 'users', 'satker', 'case','elintadfoll','elinterview'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //

        $this->validate($request, [
            'id_case' => 'required',
            'pendahuluan' => 'required',
            'pelaksanaan_kegiatan' => 'required',
            'kendala' => 'required',
            'analisa' => 'required',
            'kesimpulan' => 'required',
            'saran' => 'required',
            'petunjuk_pimpinan' => 'required',
            'upload_hasil_yang_dicapai' => 'mimes:pdf|max:30000',
        ]);
        
        if($request->id_elicitation_interview_result=="---Pilih Hasil Wawancara Elisitasi---"){
            $request->id_elicitation_interview_result = "";
        }
        if($request->id_elicitation_advice_and_followup=="------Pilih Saran Tindak Elisitasi---t---"){
            $request->id_elicitation_advice_and_followup = "";
        }

        $user = auth()->user();
        
        $data = ElicitationResult::find($id);
        $data->petunjuk_pimpinan = $request->petunjuk_pimpinan;
        $data->case_id = $request->id_case;
        $data->elicitation_interview_result_id = $request->id_elicitation_interview_result;
        $data->elicitation_advice_and_follow_up_id = $request->id_elicitation_advice_and_followup;
        $data->pendahuluan = $request->pendahuluan;
        $data->pelaksanaan_kegiatan = $request->pelaksanaan_kegiatan;
        $data->kendala = $request->kendala;
        $data->analisa = $request->analisa;

        $data->kesimpulan = $request->kesimpulan;
        $data->saran = $request->saran;
        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'open/data/elicitation/result',
                    Str::slug('elicitationresult', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
                    'public'
                );

            if (Storage::disk('public')->exists($request->temp_upload_hasil_yang_dicapai)) {
                Storage::disk('public')->delete($request->temp_upload_hasil_yang_dicapai);
            }

            $data->hasil_yang_dicapai_path = $upload_hasil_yang_dicapai;

            $document_pdf = Documents::where('relation_id',$id)->first();
            $document_pdf->doc_path = $upload_hasil_yang_dicapai;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->updated_by = $user->id;
            $document_pdf->save();
        } else {
            $hasil_yang_dicapai_path = $request->temp_upload_hasil_yang_dicapai;
            $data->hasil_yang_dicapai_path = $hasil_yang_dicapai_path;
        }

        $data->updated_by = $user->id;

        if ($data->update()) {

            if ($request->submit_type === 'update_and_finish') {
       
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->elisitasi_hasil_yang_dicapai = 1;
                $updateCaseProgresses->status = 'Elicitation';
                $updateCaseProgresses->substatus = 'Perubahan Elisitasi Hasil yang Dicapai';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();
    
    
            }

            
            $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Elisitasi Hasil yang Dicapai');
            return redirect()->route('open.data.elicit-result.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        //
        $data = ElicitationResult::find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $log = DataHelper::logUpdateCase($data->case_id, 'Penghapusan Elisitasi Hasil yang Dicapai');

        $data->delete();
        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

}
