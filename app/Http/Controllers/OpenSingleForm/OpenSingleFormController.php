<?php

namespace App\Http\Controllers\OpenSingleForm;

use Mpdf\Mpdf;
use Illuminate\Support\Facades\Date;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\DataTables\OpenSingleForm\OpenSingleFormDataTable;
use App\Helpers\DataHelper;
use App\Models\OpenCaseSingleForm;
use App\Models\Documents;
use App\Models\VideoDocuments;
use App\Models\VideoAudioDocuments;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Helpers\BodycamDeviceDataHelper;


class OpenSingleFormController extends Controller
{
    public function index(OpenSingleFormDataTable $dataTable)
    {
        return $dataTable->render('backoffice.open.single-form.index');
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $agama = DataHelper::getListAgama();
        $pendidikan = DataHelper::getPendidikan();
        $pekerjaan = DataHelper::getPekerjaan();
        $listPegawai = DataHelper::getPegawai();

        return view('backoffice.open.single-form.create', compact('satker', 'users', 'agama', 'pendidikan', 'pekerjaan', 'listPegawai'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'procedure_type' => 'required|string|max:128',
            'case_name' => 'required|string|max:128',
            'case_date' => 'required|string|max:128',
            'case_description'  => 'required|string',
            'satker_id' => 'required|string|max:128',

            'nik'  => 'required|string|max:128',
            'target_name'  => 'required|string|max:128',
            // 'target_religion'  => 'required|string|max:128',
            // 'target_education' => 'required|string|max:128',
            // 'target_occupation' => 'required|string|max:128',
            // 'target_gender' => 'required|string|max:128',

            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // 'research_dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            // 'research_video_upload' => 'nullable|file|mimes:mp4|max:200048',

            // 'interview_interviewer_name' => 'required|string|max:128',
            // 'interview_interviewer_schedule' => 'required|string|max:128',
            // 'interview_target_name' => 'required|string|max:128',
            // 'interview_nik' => 'required|string|max:128',
        ]);

        $user = auth()->user();
        

        $data = new OpenCaseSingleForm;
        $data->created_by = $user->id;
        $data->case_name = $request->case_name;
        $data->case_date = $request->case_date;
        $data->case_description = $request->case_description;

        $data->target_name = $request->target_name;
        $data->target_identity_number = $request->nik;
        $data->target_religion = $request->target_religion;
        $data->target_education = $request->target_education;
        $data->target_occupation = $request->target_occupation;
        $data->target_gender = $request->target_gender;
        $data->target_address = $request->target_address;

        // save the image first
        $filenames = [];
        $index = 1;
        if($request->file('target_image') != null){
            foreach ($request->file('target_image') as $image) {
                $filename = time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                
                
                // $image->move($folderPath, $filename);
                $filenames[] = $filename;
                $index++;

                $target_photo = $image
                ->storePubliclyAs(
                    'open/single-form',
                    $filename,
                    'public'
                );

                // $data->target_photo = $target_photo;
            }    
            $data->target_photo  = json_encode($filenames);
        }
        
        
        $data->satker_id = $request->satker_id;
        $data->open_procedure_type = $request->procedure_type;

        $document_research_upload_pdf = new Documents;
        $document_research_upload_video =new VideoDocuments;
        $document_research_upload_video_audio_data = new VideoAudioDocuments;
        if($request->procedure_type == "research" ||$request->procedure_type == "all"  ){
            $data->research_lapinsus_pendahuluan = $request->research_pendahuluan;
            $data->research_data_dan_fakta = $request->research_data_fakta;           
            $data->research_informasi_diperoleh = $request->research_informasi_diperoleh;
            $data->research_sumber_informasi = $request->research_sumber_informasi;
            $data->research_tren_perkembangan = $request->research_tren_perkembangan;
            $data->research_saran_tindak = $request->research_saran_tindak;
            $data->ancaman = $request->ancaman;
            $data->gangguan = $request->gangguan;
            $data->hambatan = $request->hambatan;
            $data->tantangan = $request->tantangan;

 
            if ($request->hasFile('research_dokumen_upload')) {
                $ext_upload_info = $request->file('research_dokumen_upload')->extension();
                $upload_info = $request->file('research_dokumen_upload')
                    ->storePubliclyAs(
                        'open/single-form/research_dokumen_upload',
                        Str::slug('single-form research document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                        'public'
                    );
    
                $data->research_file_document = $upload_info;
                $data->relation_id_research_document = Str::uuid()->toString();;
    
                $document_research_upload_pdf->doc_path = $upload_info;
                $document_research_upload_pdf->doc_type = "pdf";
                $document_research_upload_pdf->doc_status = "0";
                $document_research_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_research_upload_pdf->relation_id = $data->relation_id_research_document;
                $document_research_upload_pdf->save();
        
            }
    
            if ($request->hasFile('research_video_upload')) {
         
                $ext_upload_info1 = $request->file('research_video_upload')->extension();
                $upload_info1 = $request->file('research_video_upload')
                    ->storePubliclyAs(
                        'open/single-form/research_video_upload',
                        Str::slug('single-form research video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->research_file_video= $upload_info1;
                $data->relation_id_research_video = Str::uuid()->toString();;

                $document_research_upload_video->doc_path = $upload_info1;
                $document_research_upload_video->doc_type = "video";
                $document_research_upload_video->doc_status = "0";
                $document_research_upload_video->doc_status_remark = "Waiting Analysis";
                $document_research_upload_video->relation_id = $data->relation_id_research_video;
                $document_research_upload_video->save();

                $document_research_upload_video_audio_data->relation_id = $data->relation_id_research_video;
                $document_research_upload_video_audio_data->doc_path = $upload_info1;
                $document_research_upload_video_audio_data->doc_type = "video_audio";
                $document_research_upload_video_audio_data->doc_status = "0";
                $document_research_upload_video_audio_data->doc_status_remark = "Waiting Analysis";
                $document_research_upload_video_audio_data->created_by = $user->id;
                $document_research_upload_video_audio_data->save();
                
            }
    

        }

        $document_interview_upload_pdf = new Documents;
        $document_interview_upload_video =new VideoDocuments;
        $document_interview_upload_video_audio_data = new VideoAudioDocuments;
        if($request->procedure_type == "interview" ||$request->procedure_type == "all"  ){
            $data->interview_interviewer_name = $request->interview_interviewer_name;
            $data->interview_schedule = $request->interview_interviewer_schedule;
            $data->interview_target_name = $request->interview_target_name;
            $data->interview_target_identity_number = $request->interview_nik;
            $data->interview_target_religion = $request->interview_religion;
            $data->interview_target_education = $request->interview_education;
            $data->interview_target_occupation = $request->interview_occupation;
            $data->interview_target_gender = $request->interview_gender;
            $data->interview_target_address = $request->interview_address;
            $data->interview_keterangan = $request->interview_keterangan;

            $filenames = [];
            $index = 1;
            if($request->file('interview_target_photo') != null){
                foreach ($request->file('interview_target_photo') as $image) {
                    $filename = time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'open/single-form/interview',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                }  
                $data->interview_target_photo  = json_encode($filenames);  
            }
            

            if ($request->hasFile('interview_upload_dokumen_wawancara')) {
                $ext_upload_info = $request->file('interview_upload_dokumen_wawancara')->extension();
                $upload_info = $request->file('interview_upload_dokumen_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/interview_upload_dokumen_wawancara',
                        Str::slug('single-form interview document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                        'public'
                    );
    
                $data->interview_file_document = $upload_info;
                $data->relation_id_interview_document = Str::uuid()->toString();;
    
                $document_interview_upload_pdf->doc_path = $upload_info;
                $document_interview_upload_pdf->doc_type = "pdf";
                $document_interview_upload_pdf->doc_status = "0";
                $document_interview_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_interview_upload_pdf->relation_id = $data->relation_id_interview_document;
                $document_interview_upload_pdf->save();
        
            }
    
            if ($request->hasFile('interview_upload_video_wawancara')) {
         
                $ext_upload_info1 = $request->file('interview_upload_video_wawancara')->extension();
                $upload_info1 = $request->file('interview_upload_video_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/interview_upload_video_wawancara',
                        Str::slug('single-form interview video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->interview_file_video= $upload_info1;
                $data->relation_id_interview_video= Str::uuid()->toString();;;

                $document_interview_upload_video->doc_path = $upload_info1;
                $document_interview_upload_video->doc_type = "video";
                $document_interview_upload_video->doc_status = "0";
                $document_interview_upload_video->doc_status_remark = "Waiting Analysis";
                $document_interview_upload_video->relation_id = $data->relation_id_interview_video;
                $document_interview_upload_video->save();

                $document_interview_upload_video_audio_data->relation_id = $data->relation_id_interview_video;
                $document_interview_upload_video_audio_data->doc_path = $upload_info1;
                $document_interview_upload_video_audio_data->doc_type = "video_audio";
                $document_interview_upload_video_audio_data->doc_status = "0";
                $document_interview_upload_video_audio_data->doc_status_remark = "Waiting Analysis";
                $document_interview_upload_video_audio_data->created_by = $user->id;
                $document_interview_upload_video_audio_data->save();
            }
            $data->interview_saran_dan_tindak_lanjut = $request->interview_saran_dan_tindak_lanjut;
            
        }

        $document_interrogation_upload_pdf = new Documents;
        $document_interrogation_upload_video =new VideoDocuments;
        $document_interrogation_upload_video_audio_data = new VideoAudioDocuments;
        if($request->procedure_type == "interrogation" ||$request->procedure_type == "all"  ){
            $data->interrogation_target_name = $request->interrogation_target_name;
            $data->interrogation_target_identity_number = $request->interrogation_target_identity_number;
            $data->interrogation_target_religion = $request->interrogation_target_religion;
            $data->interrogation_target_education = $request->interrogation_education;
            $data->interrogation_target_gender = $request->interrogation_gender;
            $data->interrogation_target_occupation = $request->interrogation_occupation;
            $data->interrogation_target_address = $request->interrogation_address;
            $data->interrogation_jaksa = json_encode($request->interrogation_pegawai);

            $filenames = [];
            $index = 1;
            if($request->file('interrogation_target_photo') != null){
                foreach ($request->file('interrogation_target_photo') as $image) {
                    $filename = time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'open/single-form/interrogation',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                }    
                $data->interrogation_target_photo  = json_encode($filenames);
            }
            

            if ($request->hasFile('interrogation_upload_dokumen_wawancara')) {
                $ext_upload_info = $request->file('interrogation_upload_dokumen_wawancara')->extension();
                $upload_info = $request->file('interrogation_upload_dokumen_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/interrogation_upload_dokumen_wawancara',
                        Str::slug('single-form interrogation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                        'public'
                    );
    
                $data->interrogation_file_document = $upload_info;
                $data->relation_id_interrogation_document= Str::uuid()->toString();
    
                $document_interrogation_upload_pdf->doc_path = $upload_info;
                $document_interrogation_upload_pdf->doc_type = "pdf";
                $document_interrogation_upload_pdf->doc_status = "0";
                $document_interrogation_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_interrogation_upload_pdf->relation_id = $data->relation_id_interrogation_document;
                $document_interrogation_upload_pdf->save();
        
            }
    
            if ($request->hasFile('interrogation_upload_video_wawancara')) {
         
                $ext_upload_info1 = $request->file('interrogation_upload_video_wawancara')->extension();
                $upload_info1 = $request->file('interrogation_upload_video_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/interrogation_upload_video_wawancara',
                        Str::slug('single-form interrogation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->interrogation_file_video= $upload_info1;
                $data->relation_id_interrogation_video= Str::uuid()->toString();

                $document_interrogation_upload_video->doc_path = $upload_info1;
                $document_interrogation_upload_video->doc_type = "video";
                $document_interrogation_upload_video->doc_status = "0";
                $document_interrogation_upload_video->doc_status_remark = "Waiting Analysis";
                $document_interrogation_upload_video->relation_id = $data->relation_id_interrogation_video;
                $document_interrogation_upload_video->save();

                $document_interrogation_upload_video_audio_data->relation_id = $data->relation_id_interrogation_video;
                $document_interrogation_upload_video_audio_data->doc_path = $upload_info1;
                $document_interrogation_upload_video_audio_data->doc_type = "video_audio";
                $document_interrogation_upload_video_audio_data->doc_status = "0";
                $document_interrogation_upload_video_audio_data->doc_status_remark = "Waiting Analysis";
                $document_interrogation_upload_video_audio_data->created_by = $user->id;
                $document_interrogation_upload_video_audio_data->save();
                
            }
            
            $data->interrogation_target_identification = $request->interrogation_target_identification;
            $data->interrogation_result_achievement = $request->interrogation_result_achievement;

            
        }

        $document_elicitation_upload_pdf = new Documents;
        $document_elicitation_upload_video =new VideoDocuments;
        $document_elicitation_upload_video_audio_data = new VideoAudioDocuments;
        if($request->procedure_type == "elicitation" ||$request->procedure_type == "all"  ){

            $data->elicitation_interviewer_name = $request->elicitation_interviewer_name;
            $data->elicitation_interview_schedule = $request->elicitation_interview_schedule;
            $data->elicitation_interview_target_name = $request->elicitation_interview_target_name;

            $data->elicitation_interview_target_identity_number = $request->elicitation_interview_target_identity_number;
            $data->elicitation_target_religion = $request->elicitation_target_religion;
            $data->elicitation_target_education = $request->elicitation_target_education;

            $data->elicitation_target_occupation = $request->elicitation_target_occupation;

            $data->elicitation_target_gender = $request->elicitation_target_gender;
            $data->elicitation_target_address = $request->elicitation_target_address;

            $filenames = [];
            $index = 1;
            if($request->file('elicitation_target_photo') != null){
                foreach ($request->file('elicitation_target_photo') as $image) {
                    $filename = time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'open/single-form/elicitation',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                }   
                $data->elicitation_target_photo  = json_encode($filenames); 
            }
            

            if ($request->hasFile('elicitation_upload_dokumen_wawancara')) {
                $ext_upload_info = $request->file('elicitation_upload_dokumen_wawancara')->extension();
                $upload_info = $request->file('elicitation_upload_dokumen_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/elicitation_upload_dokumen_wawancara',
                        Str::slug('single-form elicitation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                        'public'
                    );
    
                $data->elicitation_file_document = $upload_info;
                $data->relation_id_elicitation_document= Str::uuid()->toString();
    
                $document_elicitation_upload_pdf->doc_path = $upload_info;
                $document_elicitation_upload_pdf->doc_type = "pdf";
                $document_elicitation_upload_pdf->doc_status = "0";
                $document_elicitation_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_elicitation_upload_pdf->relation_id = $data->relation_id_elicitation_document;
                $document_elicitation_upload_pdf->save();
        
            }
    
            if ($request->hasFile('elicitation_upload_video_wawancara')) {
         
                $ext_upload_info1 = $request->file('elicitation_upload_video_wawancara')->extension();
                $upload_info1 = $request->file('elicitation_upload_video_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/elicitation_upload_video_wawancara',
                        Str::slug('single-form elicitation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->elicitation_file_video= $upload_info1;
                $data->relation_id_elicitation_video= Str::uuid()->toString();

                $document_elicitation_upload_video->doc_path = $upload_info1;
                $document_elicitation_upload_video->doc_type = "video";
                $document_elicitation_upload_video->doc_status = "0";
                $document_elicitation_upload_video->doc_status_remark = "Waiting Analysis";
                $document_elicitation_upload_video->relation_id = $data->relation_id_elicitation_video;
                $document_elicitation_upload_video->save();

                $document_elicitation_upload_video_audio_data->relation_id = $data->relation_id_elicitation_video;
                $document_elicitation_upload_video_audio_data->doc_path = $upload_info1;
                $document_elicitation_upload_video_audio_data->doc_type = "video_audio";
                $document_elicitation_upload_video_audio_data->doc_status = "0";
                $document_elicitation_upload_video_audio_data->doc_status_remark = "Waiting Analysis";
                $document_elicitation_upload_video_audio_data->created_by = $user->id;
                $document_elicitation_upload_video_audio_data->save();
                
            }
            $data->elicitation_pendahuluan = $request->elicitation_pendahuluan;
            $data->elicitation_pelaksanaan_kegiatan = $request->elicitation_pelaksanaan_kegiatan;
            $data->elicitation_kendala = $request->elicitation_kendala;
            $data->elicitation_analisa = $request->elicitation_analisa;

          
            
        }

        if ($data->save()) {
            return redirect()->route('open.singleform.single-form.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function edit(Request $request, $id)
    {
        $data = OpenCaseSingleForm::find($id);
        $penelitianvideo = VideoDocuments::select('video_document_analytics.video_doc_analytic_2','video_document_analytics.video_doc_summary_2','video_document_analytics.video_doc_note')
                    ->join('open_case_single_form','video_documents.relation_id','open_case_single_form.relation_id_research_video')
                    ->join('video_document_analytics','video_documents.id','video_document_analytics.video_doc_id')
                    ->where('open_case_single_form.id',$id)
                    ->get();
        $penelitiandoc = Documents::select('documents.doc_analytics_2','documents.doc_summary_2')
                    ->join('open_case_single_form','documents.relation_id','open_case_single_form.relation_id_research_document')
                    ->where('open_case_single_form.id',$id)
                    ->get();
        
        $interviewvideo = VideoDocuments::select('video_document_analytics.video_doc_analytic_2','video_document_analytics.video_doc_summary_2','video_document_analytics.video_doc_note')
                    ->join('open_case_single_form','video_documents.relation_id','open_case_single_form.relation_id_interview_video')
                    ->join('video_document_analytics','video_documents.id','video_document_analytics.video_doc_id')
                    ->where('open_case_single_form.id',$id)
                    ->get();
        $interviewdoc = Documents::select('documents.doc_analytics_2','documents.doc_summary_2')
                    ->join('open_case_single_form','documents.relation_id','open_case_single_form.relation_id_interview_document')
                    ->where('open_case_single_form.id',$id)
                    ->get();
        
        $interrogationvideo = VideoDocuments::select('video_document_analytics.video_doc_analytic_2','video_document_analytics.video_doc_summary_2','video_document_analytics.video_doc_note')
                    ->join('open_case_single_form','video_documents.relation_id','open_case_single_form.relation_id_interrogation_video')
                    ->join('video_document_analytics','video_documents.id','video_document_analytics.video_doc_id')
                    ->where('open_case_single_form.id',$id)
                    ->get();
        $interrogationdoc = Documents::select('documents.doc_analytics_2','documents.doc_summary_2')
                    ->join('open_case_single_form','documents.relation_id','open_case_single_form.relation_id_interrogation_document')
                    ->where('open_case_single_form.id',$id)
                    ->get();
        
        $elicitationvideo = VideoDocuments::select('video_document_analytics.video_doc_analytic_2','video_document_analytics.video_doc_summary_2','video_document_analytics.video_doc_note')
                    ->join('open_case_single_form','video_documents.relation_id','open_case_single_form.relation_id_elicitation_video')
                    ->join('video_document_analytics','video_documents.id','video_document_analytics.video_doc_id')
                    ->where('open_case_single_form.id',$id)
                    ->get();
        $elicitationdoc = Documents::select('documents.doc_analytics_2','documents.doc_summary_2')
                    ->join('open_case_single_form','documents.relation_id','open_case_single_form.relation_id_elicitation_document')
                    ->where('open_case_single_form.id',$id)
                    ->get();
        $satker = DataHelper::getSatker();
        $interrogation_listPegawai = DataHelper::getPegawai();
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if(!json_decode($data->interrogation_jaksa)){
            $data->interrogation_jaksa = [];
        }

        return view('backoffice.open.single-form.edit',compact('data', 'satker', 'interrogation_listPegawai', 'penelitianvideo','penelitiandoc','interviewvideo','interviewdoc','interrogationvideo','interrogationdoc','elicitationvideo','elicitationdoc'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'procedure_type' => 'required|string|max:128',
            'case_name'  => 'required|string|max:128',
            'case_date'  => 'required|string|max:128',
            'case_description'  => 'required|string',
            // 'satker_id'  => 'required|string|max:128',

            'nik'  => 'nullable|string|max:128',
            'target_name'  => 'required|string|max:128',
            'target_religion'  => 'nullable|string|max:128',
            'target_education' => 'nullable|string|max:128',
            'target_occupation' => 'nullable|string|max:128',
            'target_gender' => 'nullable|string|max:128',

            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'research_dokumen_upload' => 'nullable|file|mimes:pdf|max:20480',
            'research_video_upload' => 'nullable|file|mimes:mp4|max:200048',

            // 'interview_interviewer_name' => 'required|string|max:128',
            // 'interview_interviewer_schedule' => 'required|string|max:128',
            // 'interview_target_name' => 'required|string|max:128',
            // 'interview_nik' => 'required|string|max:128',
        ]);

        $user = auth()->user();

        $data = OpenCaseSingleForm::find($id);
        $data->updated_by = $user->id;
        $data->case_name = $request->case_name;
        $data->case_date = $request->case_date;
        $data->case_description = $request->case_description;

        $data->target_name = $request->target_name;
        $data->target_identity_number = $request->nik;
        $data->target_religion = $request->target_religion;
        $data->target_education = $request->target_education;
        $data->target_occupation = $request->target_occupation;
        $data->target_gender = $request->target_gender;
        $data->target_address = $request->target_address;

        // save the image first
        $filenames = [];
        $index = 1;
        if($request->file('target_image') != null){
            foreach ($request->file('target_image') as $image) {
                $filename = time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                
                
                // $image->move($folderPath, $filename);
                $filenames[] = $filename;
                $index++;

                $target_photo = $image
                ->storePubliclyAs(
                    'open/single-form',
                    $filename,
                    'public'
                );

                // $data->target_photo = $target_photo;
            }  
            $data->target_photo  = json_encode($filenames);  
        }
        
        
        // $data->satker_id = $request->satker_id;
        $data->open_procedure_type = $request->procedure_type;

        $document_research_upload_pdf = new Documents;
        $document_research_upload_video =new VideoDocuments;
        $document_research_upload_video_audio_data = new VideoAudioDocuments;
        if($request->procedure_type == "research" ||$request->procedure_type == "all"  ){
            $data->research_lapinsus_pendahuluan = $request->research_pendahuluan;
            $data->research_data_dan_fakta = $request->research_data_fakta;
            $data->research_informasi_diperoleh = $request->research_informasi_diperoleh;
            $data->research_sumber_informasi = $request->research_sumber_informasi;
            $data->research_tren_perkembangan = $request->research_tren_perkembangan;
            $data->research_saran_tindak = $request->research_saran_tindak;
            $data->research_aght_type = $request->research_aght_type;
            $data->research_aght_description = $request->research_aght_description;
            $data->ancaman = $request->ancaman;
            $data->gangguan = $request->gangguan;
            $data->hambatan = $request->hambatan;
            $data->tantangan = $request->tantangan;

            
            if ($request->hasFile('research_dokumen_upload')) {
                $ext_upload_info = $request->file('research_dokumen_upload')->extension();
                $upload_info = $request->file('research_dokumen_upload')
                    ->storePubliclyAs(
                        'open/single-form/research_dokumen_upload',
                        Str::slug('single-form research document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                        'public'
                    );
    
                $data->research_file_document = $upload_info;
                $data->relation_id_research_document = Str::uuid()->toString();;
    
                $document_research_upload_pdf->doc_path = $upload_info;
                $document_research_upload_pdf->doc_type = "pdf";
                $document_research_upload_pdf->doc_status = "0";
                $document_research_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_research_upload_pdf->relation_id = $data->relation_id_research_document;
                $document_research_upload_pdf->save();
        
            }
    
            if ($request->hasFile('research_video_upload')) {
         
                $ext_upload_info1 = $request->file('research_video_upload')->extension();
                $upload_info1 = $request->file('research_video_upload')
                    ->storePubliclyAs(
                        'open/single-form/research_video_upload',
                        Str::slug('single-form research video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->research_file_video= $upload_info1;
                $data->relation_id_research_video = Str::uuid()->toString();;

                $document_research_upload_video->doc_path = $upload_info1;
                $document_research_upload_video->doc_type = "video";
                $document_research_upload_video->doc_status = "0";
                $document_research_upload_video->doc_status_remark = "Waiting Analysis";
                $document_research_upload_video->relation_id = $data->relation_id_research_video;
                $document_research_upload_video->save();

                $document_research_upload_video_audio_data->relation_id = $data->relation_id_research_video;
                $document_research_upload_video_audio_data->doc_path = $upload_info1;
                $document_research_upload_video_audio_data->doc_type = "video_audio";
                $document_research_upload_video_audio_data->doc_status = "0";
                $document_research_upload_video_audio_data->doc_status_remark = "Waiting Analysis";
                $document_research_upload_video_audio_data->created_by = $user->id;
                $document_research_upload_video_audio_data->save();
                
            }
        }

        $document_interview_upload_pdf = new Documents;
        $document_interview_upload_video =new VideoDocuments;
        $document_interview_upload_video_audio_data = new VideoAudioDocuments;
        if($request->procedure_type == "interview" ||$request->procedure_type == "all"  ){
            $data->interview_interviewer_name = $request->interview_interviewer_name;
            $data->interview_schedule = $request->interview_interviewer_schedule;
            $data->interview_target_name = $request->interview_target_name;
            $data->interview_target_identity_number = $request->interview_nik;
            $data->interview_target_religion = $request->interview_religion;
            $data->interview_target_education = $request->interview_education;
            $data->interview_occupation = $request->interview_occupation;
            $data->interview_target_gender = $request->interview_gender;
            $data->interview_target_address = $request->interview_address;

            $filenames = [];
            $index = 1;
            if($request->file('interview_target_photo') != null){
                foreach ($request->file('interview_target_photo') as $image) {
                    $filename = time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'open/single-form/interview',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                }    
                $data->interview_target_photo  = json_encode($filenames);
            }
            

            if ($request->hasFile('interview_upload_dokumen_wawancara')) {
                $ext_upload_info = $request->file('interview_upload_dokumen_wawancara')->extension();
                $upload_info = $request->file('interview_upload_dokumen_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/interview_upload_dokumen_wawancara',
                        Str::slug('single-form interview document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                        'public'
                    );
    
                $data->interview_file_document = $upload_info;
                $data->relation_id_interview_document = Str::uuid()->toString();;
    
                $document_interview_upload_pdf->doc_path = $upload_info;
                $document_interview_upload_pdf->doc_type = "pdf";
                $document_interview_upload_pdf->doc_status = "0";
                $document_interview_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_interview_upload_pdf->relation_id = $data->relation_id_interview_document;
                $document_interview_upload_pdf->save();
        
            }
    
            if ($request->hasFile('interview_upload_video_wawancara')) {
         
                $ext_upload_info1 = $request->file('interview_upload_video_wawancara')->extension();
                $upload_info1 = $request->file('interview_upload_video_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/interview_upload_video_wawancara',
                        Str::slug('single-form interview video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->interview_file_video= $upload_info1;
                $data->relation_id_interview_video= Str::uuid()->toString();;;

                $document_interview_upload_video->doc_path = $upload_info1;
                $document_interview_upload_video->doc_type = "video";
                $document_interview_upload_video->doc_status = "0";
                $document_interview_upload_video->doc_status_remark = "Waiting Analysis";
                $document_interview_upload_video->relation_id = $data->relation_id_interview_video;
                $document_interview_upload_video->save();

                $document_interview_upload_video_audio_data->relation_id = $data->relation_id_interview_video;
                $document_interview_upload_video_audio_data->doc_path = $upload_info1;
                $document_interview_upload_video_audio_data->doc_type = "video_audio";
                $document_interview_upload_video_audio_data->doc_status = "0";
                $document_interview_upload_video_audio_data->doc_status_remark = "Waiting Analysis";
                $document_interview_upload_video_audio_data->created_by = $user->id;
                $document_interview_upload_video_audio_data->save();
                
            }
            $data->interview_saran_dan_tindak_lanjut = $request->interview_saran_dan_tindak_lanjut;
            
        }

        $document_interrogation_upload_pdf = new Documents;
        $document_interrogation_upload_video =new VideoDocuments;
        $document_interrogation_upload_video_audio_data = new VideoAudioDocuments;
        if($request->procedure_type == "interrogation" ||$request->procedure_type == "all"  ){
            $data->interrogation_target_name = $request->interrogation_target_name;
            $data->interrogation_target_identity_number = $request->interrogation_target_identity_number;
            $data->interrogation_target_religion = $request->interrogation_target_religion;
            $data->interrogation_target_education = $request->interrogation_education;
            $data->interrogation_target_gender = $request->interrogation_gender;
            $data->interrogation_target_occupation = $request->interrogation_occupation;
            $data->interrogation_target_address = $request->interrogation_address;
            $data->interrogation_jaksa = json_encode($request->interrogation_pegawai);

            $filenames = [];
            $index = 1;
            if($request->file('interrogation_target_photo') != null){
                foreach ($request->file('interrogation_target_photo') as $image) {
                    $filename = time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'open/single-form/interrogation',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                }    
                $data->interrogation_target_photo  = json_encode($filenames);
            }
            

            if ($request->hasFile('interrogation_upload_dokumen_wawancara')) {
                $ext_upload_info = $request->file('interrogation_upload_dokumen_wawancara')->extension();
                $upload_info = $request->file('interrogation_upload_dokumen_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/interrogation_upload_dokumen_wawancara',
                        Str::slug('single-form interrogation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                        'public'
                    );
    
                $data->interrogation_file_document = $upload_info;
                $data->relation_id_interrogation_document= Str::uuid()->toString();
    
                $document_interrogation_upload_pdf->doc_path = $upload_info;
                $document_interrogation_upload_pdf->doc_type = "pdf";
                $document_interrogation_upload_pdf->doc_status = "0";
                $document_interrogation_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_interrogation_upload_pdf->relation_id = $data->relation_id_interrogation_document;
                $document_interrogation_upload_pdf->save();
        
            }
    
            if ($request->hasFile('interrogation_upload_video_wawancara')) {
         
                $ext_upload_info1 = $request->file('interrogation_upload_video_wawancara')->extension();
                $upload_info1 = $request->file('interrogation_upload_video_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/interrogation_upload_video_wawancara',
                        Str::slug('single-form interrogation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->interrogation_file_video= $upload_info1;
                $data->relation_id_interrogation_video= Str::uuid()->toString();

                $document_interrogation_upload_video->doc_path = $upload_info1;
                $document_interrogation_upload_video->doc_type = "video";
                $document_interrogation_upload_video->doc_status = "0";
                $document_interrogation_upload_video->doc_status_remark = "Waiting Analysis";
                $document_interrogation_upload_video->relation_id = $data->relation_id_interrogation_video;
                $document_interrogation_upload_video->save();

                $document_interrogation_upload_video_audio_data->relation_id = $data->relation_id_interrogation_video;
                $document_interrogation_upload_video_audio_data->doc_path = $upload_info1;
                $document_interrogation_upload_video_audio_data->doc_type = "video_audio";
                $document_interrogation_upload_video_audio_data->doc_status = "0";
                $document_interrogation_upload_video_audio_data->doc_status_remark = "Waiting Analysis";
                $document_interrogation_upload_video_audio_data->created_by = $user->id;
                $document_interrogation_upload_video_audio_data->save();
                
            }
            
            $data->interrogation_target_identification = $request->interrogation_target_identification;
            $data->interrogation_result_achievement = $request->interrogation_result_achievement;

            
        }

        $document_elicitation_upload_pdf = new Documents;
        $document_elicitation_upload_video =new VideoDocuments;
        $document_elicitation_upload_video_audio_data = new VideoAudioDocuments;
        if($request->procedure_type == "elicitation" ||$request->procedure_type == "all"  ){

            $data->elicitation_interviewer_name = $request->elicitation_interviewer_name;
            $data->elicitation_interview_schedule = $request->elicitation_interview_schedule;
            $data->elicitation_interview_target_name = $request->elicitation_interview_target_name;

            $data->elicitation_interview_target_identity_number = $request->elicitation_interview_target_identity_number;
            $data->elicitation_target_religion = $request->elicitation_target_religion;
            $data->elicitation_target_education = $request->elicitation_target_education;

            $data->elicitation_target_occupation = $request->elicitation_target_occupation;

            $data->elicitation_target_gender = $request->elicitation_target_gender;
            $data->elicitation_target_address = $request->elicitation_target_address;

            $filenames = [];
            $index = 1;
            if($request->file('elicitation_target_photo') != null){
                foreach ($request->file('elicitation_target_photo') as $image) {
                    $filename = time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'open/single-form/elicitation',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                }    
                $data->elicitation_target_photo  = json_encode($filenames);
            }
            

            if ($request->hasFile('elicitation_upload_dokumen_wawancara')) {
                $ext_upload_info = $request->file('elicitation_upload_dokumen_wawancara')->extension();
                $upload_info = $request->file('elicitation_upload_dokumen_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/elicitation_upload_dokumen_wawancara',
                        Str::slug('single-form elicitation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                        'public'
                    );
    
                $data->elicitation_file_document = $upload_info;
                $data->relation_id_elicitation_document= Str::uuid()->toString();
    
                $document_elicitation_upload_pdf->doc_path = $upload_info;
                $document_elicitation_upload_pdf->doc_type = "pdf";
                $document_elicitation_upload_pdf->doc_status = "0";
                $document_elicitation_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_elicitation_upload_pdf->relation_id = $data->relation_id_elicitation_document;
                $document_elicitation_upload_pdf->save();
        
            }
    
            if ($request->hasFile('elicitation_upload_video_wawancara')) {
         
                $ext_upload_info1 = $request->file('elicitation_upload_video_wawancara')->extension();
                $upload_info1 = $request->file('elicitation_upload_video_wawancara')
                    ->storePubliclyAs(
                        'open/single-form/elicitation_upload_video_wawancara',
                        Str::slug('single-form elicitation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->elicitation_file_video= $upload_info1;
                $data->relation_id_elicitation_video= Str::uuid()->toString();

                $document_elicitation_upload_video->doc_path = $upload_info1;
                $document_elicitation_upload_video->doc_type = "video";
                $document_elicitation_upload_video->doc_status = "0";
                $document_elicitation_upload_video->doc_status_remark = "Waiting Analysis";
                $document_elicitation_upload_video->relation_id = $data->relation_id_elicitation_video;
                $document_elicitation_upload_video->save();

                $document_elicitation_upload_video_audio_data->relation_id = $data->relation_id_elicitation_video;
                $document_elicitation_upload_video_audio_data->doc_path = $upload_info1;
                $document_elicitation_upload_video_audio_data->doc_type = "video_audio";
                $document_elicitation_upload_video_audio_data->doc_status = "0";
                $document_elicitation_upload_video_audio_data->doc_status_remark = "Waiting Analysis";
                $document_elicitation_upload_video_audio_data->created_by = $user->id;
                $document_elicitation_upload_video_audio_data->save();
                
            }
            $data->elicitation_pendahuluan = $request->elicitation_pendahuluan;
            $data->elicitation_pelaksanaan_kegiatan = $request->elicitation_pelaksanaan_kegiatan;
            $data->elicitation_kendala = $request->elicitation_kendala;
            $data->elicitation_analisa = $request->elicitation_analisa;
            

            
        }


        if ($data->update()) {
            
            
            
            return redirect()->route('open.singleform.single-form.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }



    public function show(Request $request, $id)
    {
        $data = OpenCaseSingleForm::find($id);

        $images = [];

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);
            foreach ($imagePaths as $imagePath) {
                $images[] =Storage::url('open/single-form/' . $imagePath);
            }
        }
        if($data->research_file_video  != null){
            $data->research_file_video = Storage::url( $data->research_file_video);
        }
        if($data->research_file_document != null){
            $data->research_file_document = Storage::url( $data->research_file_document);
        }
        
        $research_document_pdf_data = Documents::where('relation_id', $data->relation_id_research_document)->first();
        
        
        $interview_images = [];
        if ($data->interview_target_photo) {
            $imagePaths = json_decode($data->interview_target_photo);
            foreach ($imagePaths as $imagePath) {
                $interview_images[] =Storage::url('open/single-form/interview/' . $imagePath);
            }
        }
        if($data->interview_file_video  != null){
            $data->interview_file_video = Storage::url( $data->interview_file_video);
        }
        if($data->interview_file_document  != null){
            $data->interview_file_document = Storage::url( $data->interview_file_document);
        }
        $interview_document_pdf_data = Documents::where('relation_id', $data->relation_id_interview_document)->first();
        
        
        
        $interrogation_images = [];
        if ($data->interrogation_target_photo) {
            $imagePaths = json_decode($data->interrogation_target_photo);
            foreach ($imagePaths as $imagePath) {
                $interrogation_images[] =Storage::url('open/single-form/interrogation/' . $imagePath);
            }
        }
        if($data->interrogation_file_video != null){
            $data->interrogation_file_video = Storage::url( $data->interrogation_file_video);
        }
        if($data->interrogation_file_document != null){
            $data->interrogation_file_document = Storage::url( $data->interrogation_file_document);
        }
        $interrogation_document_pdf_data = Documents::where('relation_id', $data->relation_id_interrogation_document)->first();
        
        
        $elicitation_images = [];
        if ($data->elicitation_target_photo) {
            $imagePaths = json_decode($data->elicitation_target_photo);
            foreach ($imagePaths as $imagePath) {
                $elicitation_images[] =Storage::url('open/single-form/elicitation/' . $imagePath);
            }
        }

        if($data->elicitation_file_video != null){
            $data->elicitation_file_video = Storage::url( $data->elicitation_file_video);
        }
        if($data->elicitation_file_document != null){
            $data->elicitation_file_document = Storage::url( $data->elicitation_file_document);
        }
        
        $elicitation_document_pdf_data = Documents::where('relation_id', $data->relation_id_elicitation_document)->first();
        
       

        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();

        // jaksa interrogation
        $listPegawai = DataHelper::getPegawai();
        $interrogation_listJaksa = [];
        
        if (json_decode($data->interrogation_jaksa)) {
            foreach (json_decode($data->interrogation_jaksa) as $jaksa) {
                $foundJaksa = $listPegawai->whereIn('nip', $jaksa)->first();
                if($foundJaksa){
                    $interrogation_listJaksa[] = $foundJaksa['text'];
                }
            }
        }

        $penelitianvideo = VideoDocuments::select('video_document_analytics.video_doc_analytic_2','video_document_analytics.video_doc_summary_2','video_document_analytics.video_doc_note')
                            ->join('open_case_single_form','video_documents.relation_id','open_case_single_form.relation_id_research_video')
                            ->join('video_document_analytics','video_documents.id','video_document_analytics.video_doc_id')
                            ->where('open_case_single_form.id',$id)
                            ->get();
        $interviewvideo = VideoDocuments::select('video_document_analytics.video_doc_analytic_2','video_document_analytics.video_doc_summary_2','video_document_analytics.video_doc_note')
                            ->join('open_case_single_form','video_documents.relation_id','open_case_single_form.relation_id_interview_video')
                            ->join('video_document_analytics','video_documents.id','video_document_analytics.video_doc_id')
                            ->where('open_case_single_form.id',$id)
                            ->get();
        $interrogationvideo = VideoDocuments::select('video_document_analytics.video_doc_analytic_2','video_document_analytics.video_doc_summary_2','video_document_analytics.video_doc_note')
                            ->join('open_case_single_form','video_documents.relation_id','open_case_single_form.relation_id_interrogation_video')
                            ->join('video_document_analytics','video_documents.id','video_document_analytics.video_doc_id')
                            ->where('open_case_single_form.id',$id)
                            ->get();
        $elicitationvideo = VideoDocuments::select('video_document_analytics.video_doc_analytic_2','video_document_analytics.video_doc_summary_2','video_document_analytics.video_doc_note')
                            ->join('open_case_single_form','video_documents.relation_id','open_case_single_form.relation_id_elicitation_video')
                            ->join('video_document_analytics','video_documents.id','video_document_analytics.video_doc_id')
                            ->where('open_case_single_form.id',$id)
                            ->get();

  
        return view('backoffice.open.single-form.show', compact(
            'data', 
            'images',
            'research_document_pdf_data','penelitianvideo',

            'interview_images',
            'interview_document_pdf_data','interviewvideo',

            'interrogation_images',
            'interrogation_document_pdf_data','interrogationvideo',
            'interrogation_listJaksa',

            'elicitation_images',
            'elicitation_document_pdf_data','elicitationvideo',
            'bodycam_devices'));
    }

    public function destroy(Request $request, $id)
    {
        $data = OpenCaseSingleForm::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        
        $data->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($id_case)
    {
        $data = OpenCaseSingleForm::find(decrypt($id_case));
        $images = [];

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);
            foreach ($imagePaths as $imagePath) {
                $images[] =Storage::url('open/single-form/' . $imagePath);
            }
        }

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);
        $research_document_pdf_data = Documents::where('relation_id', $data->relation_id_research_document)->first();
        $interview_document_pdf_data = Documents::where('relation_id', $data->relation_id_interview_document)->first();
        $interrogation_document_pdf_data = Documents::where('relation_id', $data->relation_id_interrogation_document)->first();
        $elicitation_document_pdf_data = Documents::where('relation_id', $data->relation_id_elicitation_document)->first();


        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.open.single-form.pdf", compact(
            'data', 
            
            'research_document_pdf_data',
            'interview_document_pdf_data',
        'interrogation_document_pdf_data',
        'elicitation_document_pdf_data')));

        $filename = 'Open_Single_Form_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    public function uploadVideo1(Request $request)
    {
        // $video = $request->file('video'); // Mengambil file video dari FormData

        // Mendapatkan id dari request
        $id = $request->input('id');
        $type = $request->input('type');
        $path = $request->input('path');

        if($type == "research"){
            if ($path) {
                // $filename = 'research_' . time() . '.mp4';
                // $path = 'open/single-form/research-single-form-video_upload/' . $filename;
    
                $data_interview_hasil = OpenCaseSingleForm::where('id', $id)->first();
                $data_interview_hasil->relation_id_research_video = Str::uuid()->toString();
                $data_interview_hasil->research_file_video = $path;
                $data_interview_hasil->update();

                $document_video = new VideoDocuments;
                $document_video->doc_path = $path;
                $document_video->doc_status = "0";
                $document_video->doc_type = "video";
                $document_video->doc_status_remark = "Waiting Analysis";
                $document_video->relation_id = $data_interview_hasil->relation_id_research_video;
                $document_video->save();
        
                // Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

                return response()->json(['success' => true, 'path' => $path]);
            }

            return response()->json(['success' => false, 'message' => 'No video data uploaded']);

        }
        if($type == "interview"){
            if ($path) {
                // $filename = 'interview_' . time() . '.mp4';
                // $path = 'open/single-form/interview-single-form-video_upload/' . $filename;
    
                $data_interview_hasil = OpenCaseSingleForm::where('id', $id)->first();
                $data_interview_hasil->relation_id_interview_video = Str::uuid()->toString();
                $data_interview_hasil->interview_file_video = $path;
                $data_interview_hasil->update();

                $document_video = new VideoDocuments;
                $document_video->doc_path = $path;
                $document_video->doc_status = "0";
                $document_video->doc_type = "video";
                $document_video->doc_status_remark = "Waiting Analysis";
                $document_video->relation_id = $data_interview_hasil->relation_id_interview_video;
                $document_video->save();
        
                // Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

                return response()->json(['success' => true, 'path' => $path]);
            }

            return response()->json(['success' => false, 'message' => 'No video data uploaded']);

            
            
        }
        if($type == "interrogation"){
            if ($path) {
                // $filename = 'interrogation_' . time() . '.mp4';
                // $path = 'open/single-form/interrogation-single-form-video_upload/' . $filename;
    
                $data_interview_hasil = OpenCaseSingleForm::where('id', $id)->first();
                $data_interview_hasil->relation_id_interrogation_video = Str::uuid()->toString();
                $data_interview_hasil->interrogation_file_video = $path;
                $data_interview_hasil->update();

                $document_video = new VideoDocuments;
                $document_video->doc_path = $path;
                $document_video->doc_status = "0";
                $document_video->doc_type = "video";
                $document_video->doc_status_remark = "Waiting Analysis";
                $document_video->relation_id = $data_interview_hasil->relation_id_interrogation_video;
                $document_video->save();
        
                // Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

                return response()->json(['success' => true, 'path' => $path]);
            }

            return response()->json(['success' => false, 'message' => 'No video data uploaded']);

            
        }
        if($type == "elicitation"){
            if ($path) {
                // $filename = 'elicitation_' . time() . '.mp4';
                // $path = 'open/single-form/elicitation-single-form-video_upload/' . $filename;
    
                $data_interview_hasil = OpenCaseSingleForm::where('id', $id)->first();
                $data_interview_hasil->relation_id_elicitation_video = Str::uuid()->toString();
                $data_interview_hasil->elicitation_file_video = $path;
                $data_interview_hasil->update();

                $document_video = new VideoDocuments;
                $document_video->doc_path = $path;
                $document_video->doc_status = "0";
                $document_video->doc_type = "video";
                $document_video->doc_status_remark = "Waiting Analysis";
                $document_video->relation_id = $data_interview_hasil->relation_id_elicitation_video;
                $document_video->save();
        
                // Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

                return response()->json(['success' => true, 'path' => $path]);
            }

            return response()->json(['success' => false, 'message' => 'No video data uploaded']);

            
        }
        
    }

}
