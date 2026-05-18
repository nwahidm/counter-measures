<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\InterogationRecord;
use App\Models\OpenCase;
use App\Models\Documents;
use App\Models\CaseProgresses;
use App\Models\CaseEventHistoricalUpdates;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\InterogationResultAchievement;
use App\DataTables\Interogation\InterogationResultAchievementDataTable;

class InterogationResultAchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InterogationResultAchievementDataTable $dataTable)
    {
        //
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();
        return $dataTable->render('backoffice.open.interogation-result-achievement.index', compact('satker', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCaseValidInterogationRecord();
        // $interogrecord = DataHelper::getInterogrecord();
        // $interogtarget = DataHelper::getInterogTarget();
        return view('backoffice.open.interogation-result-achievement.create', compact('case','satker'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            // 'id_interogation_target_identification' => 'required',
            // 'id_interogation_record' => 'required',
            'satker_id' => 'required',
            'case_id' => 'required',
            'hasil_yang_dicapai' => 'required',
            'upload_hasil_yang_dicapai' => 'required|mimes:pdf|max:30000'
        ]);

        $user = auth()->user();

        $data = new InterogationResultAchievement();
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->interogation_record_id = $request->id_interogation_record;
        $data->interogation_target_identification_id = $request->id_interogation_target_identification;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'open/data/interogation',
                    Str::slug('interogationtargetid', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
                    'public'
                );

            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        
        if ($request->submit_type === 'save') {

            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->interogasi_hasil_yang_dicapai = 1;
            $updateCaseProgresses->interogasi_laporan = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Hasil Yang Dicapai';
            $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 76.44 ? $updateCaseProgresses->percentage : 76.44;
            $updateCaseProgresses->save();

            if ($data->save()) {

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->case_id;
                $cp->action = 'Penambahan Interogasi Hasil Yang Dicapai';
                $cp->created_by = $user->id;
                $cp->save();

                $document_pdf = new Documents;
                $document_pdf->doc_path = $upload_hasil_yang_dicapai;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_result_achievement;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();

                // $log = DataHelper::logUpdateCase($data->case_id, 'Penambahan Interogasi Hasil Yang Dicapi');

                return redirect()->route('open.data.interogg-achieve.index')->with("success", "Data berhasil ditambah.");
            }
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }else{

            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->interogasi_identifikasi_target = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Hasil Capaian';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();

            

            if ($data->save()) {
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->case_id;
                $cp->action = 'Penambahan Interogasi Hasil Capaian';
                $cp->created_by = $user->id;
                $cp->save();
                
                $document_pdf = new Documents;
                $document_pdf->doc_path = $upload_hasil_yang_dicapai;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_result_achievement;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();

               
                return redirect()->route('open.data.interogg-achieve.index')->with("success", "Data berhasil ditambah.");
            }
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');

        }

        
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $data = InterogationResultAchievement::find($id);
        $summary = Documents::where('relation_id',$id)->first();
        return view('backoffice.open.interogation-result-achievement.show', compact('data','summary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
        $data = InterogationResultAchievement::find($id);
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCaseValidInterogationRecord($data->satker_id);
        $interogrecord = DataHelper::getInterogrecordByCase($data->case_id);
        $interogtarget = DataHelper::getInterogTarget();
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        return view('backoffice.open.interogation-result-achievement.edit', compact('data', 'satker', 'case','interogrecord','interogtarget'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            // 'id_interogation_target_identification' => 'required',
            // 'id_interogation_record' => 'required',
            // 'satker_id' => 'required',
            'case_id' => 'required',
            'hasil_yang_dicapai' => 'required',
            'upload_hasil_yang_dicapai' => 'nullable|mimes:pdf|max:30000'
        ]);
        
        $user = auth()->user();

        $data = InterogationResultAchievement::find($id);

        // $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->interogation_record_id = $request->id_interogation_record;
        $data->interogation_target_identification_id = $request->id_interogation_target_identification;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        // DOKUMEN
        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'open/data/interogation/',
                    Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
                    'public'
                );

            if ($request->temp_upload_hasil_yang_dicapai && Storage::disk('public')->exists($request->temp_upload_hasil_yang_dicapai)) {
                Storage::disk('public')->delete($request->temp_upload_hasil_yang_dicapai);
            }
            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;

            $document_pdf = Documents::where('relation_id',$id)->first();
            if($document_pdf){
                $document_pdf->doc_path = $upload_hasil_yang_dicapai;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->updated_by = $user->id;
                $document_pdf->update();
            }else{
                $document_pdf = new Documents;
                $document_pdf->doc_path = $upload_hasil_yang_dicapai;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_result_achievement;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();
            }
            
        } else {
            $upload_hasil_yang_dicapai = $request->temp_upload_hasil_yang_dicapai;

            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
        }

        $data->updated_by = $user->id;
        // dd($request->submit_type);
        if ($request->submit_type === 'update_and_finish') {
       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->interogasi_identifikasi_target = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Hasil Capaian';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();


        }

        if ($data->update()) {

            
            $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Interogasi Hasil Yang Dicapi');

            return redirect()->route('open.data.interogg-achieve.index')->with(["success" => "Data berhasil diupdate."]);
        }
        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = InterogationResultAchievement::find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $log = DataHelper::logUpdateCase($data->case_id, 'Penghapusan Interogasi Hasil Yang Dicapi');

        $data->delete();
        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

    public function getHasilIdentifikasiTarget(Request $request){
       

        $case_id = $request->input('case_id'); // 'value1'
        $interogation_record_id = $request->input('interogation_record_id'); // 'value2'

        $case_data= OpenCase::find($case_id);
        $interogation_record_data = InterogationRecord::find($interogation_record_id);



        $response = Http::withBasicAuth(
            'rode_interogasi_identifikasi_target', 
            'rode_interogasi_identifikasi_target-45412312@1234!!908')
        ->post('https://rovi.atlasdev.cloud/api/gateway1/api/v1/applicationprompt/user/login', [
            
        ]);
        $statusCode = $response->status();

        if ($statusCode === 200) {
            // Request berhasil
            $body = $response->body();
            $login_data = json_decode($body, true);

            $text_to_llm = "";
            if($case_data){
                $text_to_llm = "Berikut adalah informasi terkait dengan kasus ";
                $text_to_llm = $text_to_llm." Kasus ".$case_data->nama_kasus." dengan target ".$case_data->nama_target;
                $text_to_llm = $text_to_llm. " Tanggal Kasus ".$case_data->tanggal_kasus." dengan deskripsi kasus ".$case_data->deskripsi_kasus;
                $text_to_llm = $text_to_llm. " Agama ".$case_data->agama." dengan pendidikan ".$case_data->pendidikan;
                $text_to_llm = $text_to_llm. " Pekerjaan ".$case_data->pekerjaan." dengan alamat ".$case_data->alamat;
                $text_to_llm = $text_to_llm. " No Identitas ".$case_data->no_identitas;
            }

            if($case_data){
                $text_to_llm = "Berikut adalah informasi terkait dengan kasus ";
                $text_to_llm = $text_to_llm." Kasus ".$case_data->nama_kasus." dengan target ".$case_data->nama_target;
            }

            if($interogation_record_data){
                $text_to_llm = $text_to_llm. "\n\nBerikut adalah informasi terkait dengan interogasi berita acara ";
                $text_to_llm = $text_to_llm. "Interogasi dilakukan dengan ". $interogation_record_data->target_name ." dengan nomor berita acara ".$interogation_record_data->letter_number ;
                $text_to_llm = $text_to_llm. "hasil yang diperoleh adalah ". strip_tags($interogation_record_data->hasil); 
            }
            
            // Melakukan request kedua dengan Bearer Token yang didapatkan
            $response2 = Http::withToken($login_data["data"]["access_token"])
                ->post('https://rovi.atlasdev.cloud/api/gateway1/api/v1/applicationprompt/prompt/resultllm', [
                    'application_name' => 'rode_interogasi_identifikasi_target',
                    'text' => $text_to_llm
                ]);
            $body2 = $response2->body();
            $llm_data = json_decode($body2, true)["data"];
            // Mengembalikan hasil sebagai JSON ke JavaScript
            return response()->json([
                'status' => 'success',
                'data' => $llm_data
            ]);
        } elseif ($statusCode === 401) {
            // Unauthorized, mungkin salah username/password
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Please check your credentials.'
            ], 401);
        } else {
            // Menangani kode respons lainnya
            return response()->json([
                'status' => 'error',
                'message' => 'Error: Received status code ' . $statusCode
            ], $statusCode);
        }
        

    }

}
