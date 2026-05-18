<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\CaseProgresses;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ElicitationInterview;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\ElicitationAdFoll;
use App\Models\ElicitationResult;
use App\Models\Documents;
use App\Models\VideoDocuments;
use App\Models\VideoDocumentAnalytics;
use App\Models\VideoAudioDocuments;
use App\Models\VideoAudioDocumentAnalytics;
use Illuminate\Support\Facades\Storage;
use App\DataTables\Elicitation\ElicitationInterviewDataTable;
use Illuminate\Support\Facades\Date;
use Mpdf\Mpdf;
use App\Helpers\BodycamDeviceDataHelper;
use App\DataTables\Elicitation\ElicitationInterviewResultVideoShowDataTable;

class ElicitationInterviewController extends Controller
{
    //
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ElicitationInterviewDataTable $dataTable)
    {
        //
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();
        return $dataTable->render('backoffice.open.elicitation-interview.index', compact('satker', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCaseValidElicitationRecord();
        $satker = DataHelper::getSatker();
        $agama = DataHelper::getAgama();
        $pekerjaan = DataHelper::getPekerjaan();
        $pendidikan = DataHelper::getPendidikan();
        return view('backoffice.open.elicitation-interview.create', compact('case','users','satker','agama','pekerjaan','pendidikan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'id_case' => 'required',
            'id_satker' => 'required',
            'interviewer_name' => 'required',
            'interviewer_schedule' => 'required',
            'source_person_name' => 'required',
            'interview_result_path' => 'required|mimes:pdf|max:30000',
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = auth()->user();

        $data = new ElicitationInterview;
        // $data->kode_satker = $satker->kode_satker;
        $data->satker_id = $request->id_satker;

        $data->case_id = $request->id_case;
        $data->interviewer_name = $request->interviewer_name;
        $data->interviewer_schedule = $request->interviewer_schedule;
        $data->source_person_name = $request->source_person_name;
        $data->target_identity_number = $request->nik;
        $data->target_identity_number_type = 'NIK/KTP';
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;
        $data->target_address = $request->alamat;
        $data->nip = $request->nip;
        $data->pangkat = $request->pangkat;

        if ($request->hasFile('interview_result_path')) {
            $ext_interview_result = $request->file('interview_result_path')->extension();
            $interview_result = $request->file('interview_result_path')
                ->storePubliclyAs(
                    'open/data/elicitation/interview',
                    Str::slug('elicitation interview', '_') . '_' . Str::random() . '.' . $ext_interview_result,
                    'public'
                );

            $data->interview_result_path = $interview_result;
        }


        if ($request->hasFile('upload_video_elicitation')) {
            $ext_interview_result = $request->file('upload_video_elicitation')->extension();
            $interview_video_result = $request->file('upload_video_elicitation')
                ->storePubliclyAs(
                    'open/data/elicitation/interview_video',
                    Str::slug('elicitation interview video', '_') . '_' . Str::random() . '.' . $ext_interview_result,
                    'public'
                );

            $data->interview_video_path = $interview_video_result;
        }

        if ($request->hasFile('target_photo')) {
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'open/data/elicitation/interview',
                    Str::slug('interview foto', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            $data->target_photo = $target_photo;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        $data->satker_id = $user->id_satker;

        if ($request->submit_type === 'save') {

            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->elisitasi_hasil_wawancara = 1;
            $updateCaseProgresses->status = 'Elicitation';
            $updateCaseProgresses->substatus = 'Penambahan Elisitasi Hasil Wawancara';
            $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 82.32 ? $updateCaseProgresses->percentage : 82.32;
            $updateCaseProgresses->save();

            if ($data->save()) {
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Elisitasi Hasil Wawancara';
                $cp->created_by = $user->id;
                $cp->save();

                $document_pdf = new Documents;
                $document_pdf->doc_path = $data->interview_result_path;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_elicitation_interview_result;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();


                $video_document_pdf = new VideoDocuments;
                $video_document_pdf->doc_path = $data->interview_video_path;
                $video_document_pdf->doc_type = "video";
                $video_document_pdf->doc_status = "0";
                $video_document_pdf->doc_status_remark = "Waiting Analysis";
                $video_document_pdf->relation_id = $data->id_elicitation_interview_result;
                $video_document_pdf->created_by = $user->id;
                $video_document_pdf->updated_by = $user->id;
                $video_document_pdf->save();

                $video_document_pdf = new VideoAudioDocuments;
                $video_document_pdf->doc_path = $data->interview_video_path;
                $video_document_pdf->doc_type = "video_audio";
                $video_document_pdf->doc_status = "0";
                $video_document_pdf->doc_status_remark = "Waiting Analysis";
                $video_document_pdf->relation_id = $data->id_elicitation_interview_result;
                $video_document_pdf->created_by = $user->id;
                $video_document_pdf->updated_by = $user->id;
                $video_document_pdf->save();
               
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->update([
                    'elisitasi_hasil_wawancara' => '1',
                    'status' => 'Elicitation',
                    'substatus' => 'Penambahan Elisitasi Hasil Wawancara',
                    'updated_at' => Carbon::now(),
                    'updated_by' => $user->id  
                ]);
        
                // $log = DataHelper::logUpdateCase($data->case_id, 'Penambahan Elisitasi Hasil Wawancara');

                return redirect()->route('open.data.elicit-interview.index')->with("success", "Data berhasil ditambah.");
            }

            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }else{
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->elisitasi_hasil_wawancara = 1;
            $updateCaseProgresses->status = 'Elicitation';
            $updateCaseProgresses->substatus = 'Penambahan Elisitasi Hasil Wawancara';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();


            if ($data->save()) {

                $document_pdf = new Documents;
                $document_pdf->doc_path = $data->interview_result_path;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_elicitation_interview_result;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();

                $video_document_pdf = new VideoDocuments;
                $video_document_pdf->doc_path = $data->interview_video_path;
                $video_document_pdf->doc_type = "video";
                $video_document_pdf->doc_status = "0";
                $video_document_pdf->doc_status_remark = "Waiting Analysis";
                $video_document_pdf->relation_id = $data->id_elicitation_interview_result;
                $video_document_pdf->created_by = $user->id;
                $video_document_pdf->updated_by = $user->id;
                $video_document_pdf->save();

                $video_document_pdf = new VideoAudioDocuments;
                $video_document_pdf->doc_path = $data->interview_video_path;
                $video_document_pdf->doc_type = "video_audio";
                $video_document_pdf->doc_status = "0";
                $video_document_pdf->doc_status_remark = "Waiting Analysis";
                $video_document_pdf->relation_id = $data->id_elicitation_interview_result;
                $video_document_pdf->created_by = $user->id;
                $video_document_pdf->updated_by = $user->id;
                $video_document_pdf->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Elisitasi Hasil Wawancara';
                $cp->created_by = $user->id;
                $cp->save();
                
              
                return redirect()->route('open.data.elicit-interview.index')->with("success", "Data berhasil ditambah.");
            }

            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $elicit_interview, ElicitationInterviewResultVideoShowDataTable $dataTable)
    {
        //
        $data = ElicitationInterview::find($elicit_interview);
        $document_pdf_data = Documents::where('relation_id', $data->id_elicitation_interview_result)->first();
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();
        return $dataTable->render(
            'backoffice.open.elicitation-interview.show', compact(
            'data',
            'document_pdf_data',
            'bodycam_devices'));
        
        // return view('backoffice.open.elicitation-interview.show', compact(
        //     'data',
        //     'document_pdf_data',
        //     'bodycam_devices'));
    }

    public function uploadVideo(Request $request)
    {
        $id = $request->input('id');
        $path = $request->input('path');


        if ($path) {
            // $filename = 'elicitaiton_interview_hasil_video_' . time() . '.mp4';
            // $path = 'open/data/elicitation/interivew/upload_video_wawancara/' . $filename;

            $data_interview_hasil = ElicitationInterview::where('id_elicitation_interview_result', $id)->first();
            $data_interview_hasil->interview_video_path = $path;
            $data_interview_hasil->update();


            $document_video = new VideoDocuments;
            $document_video->doc_path = $path;
            $document_video->doc_status = "0";
            $document_video->doc_type = "video";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();


            $document_video = new VideoAudioDocuments;
            $document_video->doc_path = $path;
            $document_video->doc_status = "0";
            $document_video->doc_type = "video_audio";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();
       
            // Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

            return response()->json(['success' => true, 'path' => $path]);
        }

        return response()->json(['success' => false, 'message' => 'No video data uploaded']);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
        $data = ElicitationInterview::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCaseValidElicitationRecord();
        $agama = DataHelper::getAgama();
        $pekerjaan = DataHelper::getPekerjaan();
        $pendidikan = DataHelper::getPendidikan();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.open.elicitation-interview.edit', compact('data', 'users', 'satker', 'case', 'agama', 'pekerjaan', 'pendidikan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'id_case' => 'required',
            'interviewer_name' => 'required',
            'interviewer_schedule' => 'required',
            'source_person_name' => 'required',
            // 'target_identity_number' => 'required',
            // 'target_identity_number_type' => 'required',
            // 'target_gender' => 'required',
            // 'target_religion' => 'required',
            // 'target_occupation' => 'required',
            // 'target_education' => 'required',
            // 'target_photo' => 'mimes:jpg,jpeg,png,bmp,tiff |max:10000',
            // 'target_address' => 'required',
        ]);
        
        $user = auth()->user();

        $data = ElicitationInterview::find($id);
        $data->case_id = $request->id_case;
        $data->interviewer_name = $request->interviewer_name;
        $data->interviewer_schedule = $request->interviewer_schedule;
        $data->source_person_name = $request->source_person_name;
        $data->target_identity_number = $request->nik;
        // $data->target_identity_number_type = $request->target_identity_number_type;
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;
        $data->target_address = $request->alamat;
        $data->nip = $request->nip;
        $data->pangkat = $request->pangkat;

        // DOKUMEN 
        if ($request->hasFile('interview_result_path')) {
            $ext_interview_result = $request->file('interview_result_path')->extension();
            $interview_result = $request->file('interview_result_path')
                ->storePubliclyAs(
                    'open/data/elicitation/interview',
                    Str::slug('elicitationInterview', '_') . '_' . Str::random() . '.' . $ext_interview_result,
                    'public'
                );

            if (Storage::disk('public')->exists($request->temp_interview_result)) {
                Storage::disk('public')->delete($request->temp_interview_result);
            }

            $data->interview_result_path = $interview_result;

            $document_pdf = Documents::where('relation_id',$id)->first();
            if($document_pdf){
                $document_pdf->doc_path = $interview_result;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->updated_by = $user->id;
                $document_pdf->update();
            }else{
                $document_pdf = new Documents;
                $document_pdf->doc_path = $interview_result;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_result_achievement;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();
            }
        } else {
            $interview_result = $request->temp_interview_result;

            $data->interview_result_path = $interview_result;
        }

        if ($request->hasFile('upload_video_elicitation')) {
            $ext_interview_result = $request->file('upload_video_elicitation')->extension();
            $interview_video_result = $request->file('upload_video_elicitation')
                ->storePubliclyAs(
                    'open/data/elicitation/interview_video',
                    Str::slug('elicitation interview video', '_') . '_' . Str::random() . '.' . $ext_interview_result,
                    'public'
                );

            $data->interview_video_path = $interview_video_result;

            $document_video = new VideoDocuments;
            $document_video->doc_path = $interview_video_result;
            $document_video->doc_status = "0";
            $document_video->doc_type = "video";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();

            $video_document_pdf = new VideoAudioDocuments;
            $video_document_pdf->doc_path = $data->interview_video_path;
            $video_document_pdf->doc_type = "video_audio";
            $video_document_pdf->doc_status = "0";
            $video_document_pdf->doc_status_remark = "Waiting Analysis";
            $video_document_pdf->relation_id = $data->id_elicitation_interview_result;
            $video_document_pdf->created_by = $user->id;
            $video_document_pdf->updated_by = $user->id;
            $video_document_pdf->save();

        }
        else {
            $interview_result = $request->temp_upload_video_elicitation;

            $data->interview_result_path = $interview_result;
        }

        // FOTO
        if ($request->hasFile('target_photo')) {
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'open/data/elicitation/interview',
                    Str::slug('elicitationInterview', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            if (Storage::disk('public')->exists($request->target_photo)) {
                Storage::disk('public')->delete($request->target_photo);
            }
            $data->target_photo = $target_photo;
        } 

        $data->updated_by = $user->id;

        if ($data->update()) {

            if ($request->submit_type === 'update_and_finish') {
       
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->elisitasi_hasil_wawancara = 1;
                $updateCaseProgresses->status = 'Elicitation';
                $updateCaseProgresses->substatus = 'Penambahan Elisitasi Hasil Wawancara';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();
    
    
            }

            $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Elisitasi Hasil Wawancara');

            return redirect()->route('open.data.elicit-interview.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        //
        $data = ElicitationInterview::find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $log = DataHelper::logUpdateCase($data->case_id, 'Penghapusan Elisitasi Hasil Wawancara');

        $data->delete();
        ElicitationAdFoll::where('elicitation_hasil_wawancara_id', $id)->delete();
        ElicitationResult::where('elicitation_interview_result_id', $id)->delete();



        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

    public function getElicitationRecord($case_id)
    {
        $elicitrecord = ElicitationInterview::where('case_id', $case_id)->get();

        return $elicitrecord;
    }

    public function getAdFl($elicit_id)
    {
        $elicitAdFl = ElicitationAdFoll::where('elicitation_hasil_wawancara_id', $elicit_id)->get();

        return $elicitAdFl;
    }

    public function downloadAudiotoTextFile($elicitation_interview_resukt_id)
    {

        $elicitation_interview_resukt_id = decrypt($elicitation_interview_resukt_id);
        // return $id_case;
        $data = ElicitationInterview::where('id_elicitation_interview_result', $elicitation_interview_resukt_id)->first();
        $satker = MasterSatker::where('kode_satker', $data->satker_id)->first();
        $video_audio_data = VideoAudioDocuments::where('video_audio_documents.relation_id', $data->id_elicitation_interview_result)
                                       ->orderBy('created_at', 'desc')
                                       ->first();
        $video_audio_analytics_data = VideoAudioDocumentAnalytics::where('video_audio_document_analytics.video_audio_doc_id', $video_audio_data->id)->get();

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);
        
      
        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.open.elicitation-interview.pdf", compact(
            'data',
            'satker',
            'video_audio_analytics_data')));
        

        $filename = 'Open_Interview_Result_Audio_to_Text_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
}
