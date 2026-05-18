<?php

namespace App\Http\Controllers\API\Open;

use Mpdf\Mpdf;
use App\Models\User;
use App\Models\Documents;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VideoDocuments;
use App\Models\VideoAudioDocuments;
use App\Models\OpenCaseSingleForm;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use App\Helpers\BodycamDeviceDataHelper;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\DataTables\OpenSingleForm\OpenSingleFormDataTable;


class OpenSingleFormController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $satker = $user->satker;
        $kodeSatker = $satker->kode_satker;
        $data = OpenCaseSingleForm::when(!$user->hasRole(['superadmin']), function($q) use ($user, $kodeSatker) {
            $q->where('satker_id', 'like', "$kodeSatker%");
        })->with('satker')->paginate(10);

        foreach ($data as $value) {
            $images = [];
            $imagesInterview = [];
            $imagesElicitation = [];
            if ($value->target_photo) {
                $imagePaths = json_decode($value->target_photo);
                foreach ($imagePaths as $imagePath) {
                    $images[] =Storage::url('open/single-form/' . $imagePath);
                }
            }
            if ($value->interview_target_photo) {
                $imagePaths = json_decode($value->interview_target_photo);
                foreach ($imagePaths as $imagePath) {
                    $imagesInterview[] =Storage::url('open/single-form/interview/' . $imagePath);
                }
            }
            if ($value->elicitation_target_photo) {
                $imagePaths = json_decode($value->elicitation_target_photo);
                foreach ($imagePaths as $imagePath) {
                    $imagesElicitation[] =Storage::url('open/single-form/elicitation/' . $imagePath);
                }
            }
            $value->target_photo = $images;
            $value->interview_target_photo = $imagesInterview;
            $value->elicitation_target_photo = $imagesElicitation;
        }

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }


    public function store(Request $request)
    {
        $rules = [
            'procedure_type' => 'required|string|max:128',
            'case_name' => 'required|string|max:128',
            'case_date' => 'required|date',
            'case_description' => 'required|string|max:128',
            'satker_id' => 'required|string|max:128',

            'nik' => 'required|string|max:128',
            'target_name' => 'nullable|string|max:128',
            'target_religion' => 'nullable|string|max:128',
            'target_education' => 'nullable|string|max:128',
            'target_occupation' => 'nullable|string|max:128',
            'target_gender' => 'nullable|string|max:128',

            'target_image' => 'array',
            'target_image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // custom validator
        switch ($request->input('procedure_type')) {
            case 'research':
                $rules['research_pendahuluan'] = 'required|string';
                $rules['research_data_fakta'] = 'required|string';
                $rules['research_informasi_diperoleh'] = 'required|string';
                $rules['research_sumber_informasi'] = 'required|string';
                $rules['research_tren_perkembangan'] = 'required|string';
                $rules['research_saran_tindak'] = 'required|string';
                $rules['research_aght_type'] = 'required|string';
                $rules['research_aght_description'] = 'required|string';
                // $rules['research_dokumen_upload'] = 'nullable|mimes:pdf|max:2048';
                // $rules['research_video_upload'] = 'nullable|mimes:mp4|max:2048';
                break;
            case 'interview':
                $rules['interview_nik'] = 'required|string|max:256';
                $rules['interview_interviewer_name'] = 'required|string|max:256';
                $rules['interview_interviewer_schedule'] = 'required|string|max:256';
                $rules['interview_target_name'] = 'required|string|max:256';
                $rules['interview_gender'] = 'required|string|max:100';
                $rules['interview_occupation'] = 'required|string|max:256';
                $rules['interview_education'] = 'required|string|max:256';
                $rules['interview_religion'] = 'required|string|max:256';
                $rules['interview_target_photo'] = 'array';
                $rules['interview_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['interview_address'] = 'required|string';
                // $rules['interview_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['interview_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                $rules['interview_saran_dan_tindak_lanjut'] = 'required|string';
                break;
            case 'interrogation':
                $rules['interrogation_target_identity_number'] = 'required|string|max:100';
                $rules['interrogation_target_name'] = 'required|string|max:256';
                $rules['interrogation_gender'] = 'required|string|max:100';
                $rules['interrogation_occupation'] = 'required|string|max:256';
                $rules['interrogation_education'] = 'required|string|max:256';
                $rules['interrogation_target_religion'] = 'required|string|max:256';
                $rules['interrogation_target_photo'] = 'array';
                $rules['interrogation_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['interrogation_address'] = 'required|string|max:256';
                // $rules['interrogation_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['interrogation_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                $rules['interrogation_target_identification'] = 'required|string';
                $rules['interrogation_result_achievement'] = 'required|string';
                break;
            case 'elicitation':
                $rules['elicitation_interviewer_name'] = 'required|string|max:256';
                $rules['elicitation_interview_target_identity_number'] = 'required|string|max:100';
                $rules['elicitation_interview_target_name'] = 'required|string|max:256';
                $rules['elicitation_target_gender'] = 'required|string|max:100';
                $rules['elicitation_target_occupation'] = 'required|string|max:256';
                $rules['elicitation_target_education'] = 'required|string|max:256';
                $rules['elicitation_target_address'] = 'required|string|max:256';
                $rules['elicitation_target_photo'] = 'array';
                $rules['elicitation_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['elicitation_target_religion'] = 'required|string|max:100';
                // $rules['elicitation_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['elicitation_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                $rules['elicitation_saran_dan_tindak_lanjut'] = 'required|string';
                $rules['elicitation_hasil_yang_dicapai'] = 'required|string';
                break;
            default:
                $rules['research_pendahuluan'] = 'required|string';
                $rules['research_data_fakta'] = 'required|string';
                $rules['research_informasi_diperoleh'] = 'required|string';
                $rules['research_sumber_informasi'] = 'required|string';
                $rules['research_tren_perkembangan'] = 'required|string';
                $rules['research_saran_tindak'] = 'required|string';
                $rules['research_aght_type'] = 'required|string';
                $rules['research_aght_description'] = 'required|string';
                // $rules['research_dokumen_upload'] = 'nullable|mimes:pdf|max:2048';
                // $rules['research_video_upload'] = 'nullable|mimes:mp4|max:2048';

                $rules['interrogation_target_identity_number'] = 'required|string|max:100';
                $rules['interrogation_target_name'] = 'required|string|max:256';
                $rules['interrogation_gender'] = 'required|string|max:100';
                $rules['interrogation_occupation'] = 'required|string|max:256';
                $rules['interrogation_education'] = 'required|string|max:256';
                $rules['interrogation_target_religion'] = 'required|string|max:256';
                $rules['interrogation_target_photo'] = 'array';
                $rules['interrogation_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['interrogation_address'] = 'required|string|max:256';
                // $rules['interrogation_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['interrogation_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                $rules['interrogation_target_identification'] = 'required|string';
                $rules['interrogation_result_achievement'] = 'required|string';

                $rules['interrogation_target_identity_number'] = 'required|string|max:100';
                $rules['interrogation_target_name'] = 'required|string|max:256';
                $rules['interrogation_gender'] = 'required|string|max:100';
                $rules['interrogation_occupation'] = 'required|string|max:256';
                $rules['interrogation_education'] = 'required|string|max:256';
                $rules['interrogation_target_religion'] = 'required|string|max:256';
                $rules['interrogation_target_photo'] = 'array';
                $rules['interrogation_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['interrogation_address'] = 'required|string|max:256';
                // $rules['interrogation_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['interrogation_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                $rules['interrogation_target_identification'] = 'required|string';
                $rules['interrogation_result_achievement'] = 'required|string';

                $rules['elicitation_interviewer_name'] = 'required|string|max:256';
                $rules['elicitation_interview_target_identity_number'] = 'required|string|max:100';
                $rules['elicitation_interview_target_name'] = 'required|string|max:256';
                $rules['elicitation_target_gender'] = 'required|string|max:100';
                $rules['elicitation_target_occupation'] = 'required|string|max:256';
                $rules['elicitation_target_education'] = 'required|string|max:256';
                $rules['elicitation_target_address'] = 'required|string|max:256';
                $rules['elicitation_target_photo'] = 'array';
                $rules['elicitation_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['elicitation_target_religion'] = 'required|string|max:100';
                // $rules['elicitation_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['elicitation_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                $rules['elicitation_saran_dan_tindak_lanjut'] = 'required|string';
                $rules['elicitation_hasil_yang_dicapai'] = 'required|string';
        }

        $this->validate($request, $rules);

        $user = Auth::guard('api')->user();
        

        $data = new OpenCaseSingleForm;
        $data->created_by = $user->id;
        $data->case_name = $request->case_name;
        $data->case_date = $request->case_date;
        $data->case_description = $request->case_description;

        $data->target_name = $request->target_name;
        $data->target_identity_number_type = 'NIK/KTP';
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
                $filename = time(). ' - '. $request->target_name.' - '. $index . '.'. $image->getClientOriginalExtension();
                
                
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
        }
        $data->target_photo  = json_encode($filenames);
        
        $data->satker_id = $request->satker_id;
        $data->open_procedure_type = $request->procedure_type;

        $document_research_upload_pdf = new Documents;
        $document_research_upload_video =new VideoDocuments;
        if($request->procedure_type == "research" ||$request->procedure_type == "all"  ){
            $data->research_lapinsus_pendahuluan = $request->research_pendahuluan;
            $data->research_data_dan_fakta = $request->research_data_fakta;           
            $data->research_informasi_diperoleh = $request->research_informasi_diperoleh;
            $data->research_sumber_informasi = $request->research_sumber_informasi;
            $data->research_tren_perkembangan = $request->research_tren_perkembangan;
            $data->research_saran_tindak = $request->research_saran_tindak;
            $data->research_aght_type = $request->research_aght_type;
            $data->research_aght_description = $request->research_aght_description;

            
            // if ($request->hasFile('research_dokumen_upload')) {
            //     $ext_upload_info = $request->file('research_dokumen_upload')->extension();
            //     $upload_info = $request->file('research_dokumen_upload')
            //         ->storePubliclyAs(
            //             'open/single-form/research_dokumen_upload',
            //             Str::slug('single-form research document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
            //             'public'
            //         );
    
            //     $data->research_file_document = $upload_info;
            //     $data->relation_id_research_document = Str::uuid()->toString();;
    
            //     $document_research_upload_pdf->doc_path = $upload_info;
            //     $document_research_upload_pdf->doc_type = "pdf";
            //     $document_research_upload_pdf->doc_status = "0";
            //     $document_research_upload_pdf->doc_status_remark = "Waiting Analysis";
            //     $document_research_upload_pdf->relation_id = $data->relation_id_research_document;
            //     $document_research_upload_pdf->save();
        
            // }
    
            // if ($request->hasFile('research_video_upload')) {
         
            //     $ext_upload_info1 = $request->file('research_video_upload')->extension();
            //     $upload_info1 = $request->file('research_video_upload')
            //         ->storePubliclyAs(
            //             'open/single-form/research_video_upload',
            //             Str::slug('single-form research video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
            //             'public'
            //         );
                    
            //     $data->research_file_video= $upload_info1;
            //     $data->relation_id_research_video = Str::uuid()->toString();;

            //     $document_research_upload_video->doc_path = $upload_info1;
            //     $document_research_upload_video->doc_type = "video";
            //     $document_research_upload_video->doc_status = "0";
            //     $document_research_upload_video->doc_status_remark = "Waiting Analysis";
            //     $document_research_upload_video->relation_id = $data->relation_id_research_video;
            //     $document_research_upload_video->save();
                
            // }
            
            // Decode and handle the base64 encoded research document upload
            if ($request->input('research_dokumen_upload')) {
                $base64Doc = $request->input('research_dokumen_upload');
                $decodedDoc = base64_decode($base64Doc);
                
                $ext_upload_info = 'pdf'; // Assuming the file extension is known or provided
                $fileName = Str::slug('single-form research document', '_') . '_' . Str::random() . '.' . $ext_upload_info;
                $filePath = 'open/single-form/research_dokumen_upload/' . $fileName;
                
                // Store the decoded file
                Storage::disk('public')->put($filePath, $decodedDoc);

                $data->research_file_document = $filePath;
                $data->relation_id_research_document = Str::uuid()->toString();

                $document_research_upload_pdf->doc_path = $filePath;
                $document_research_upload_pdf->doc_type = "pdf";
                $document_research_upload_pdf->doc_status = "0";
                $document_research_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_research_upload_pdf->relation_id = $data->relation_id_research_document;
                $document_research_upload_pdf->save();
            }

            // Decode and handle the base64 encoded research video upload
            if ($request->input('research_video_upload')) {
                $base64Video = $request->input('research_video_upload');
                $decodedVideo = base64_decode($base64Video);

                $ext_upload_info1 = 'mp4'; // Assuming the file extension is known or provided
                $fileName1 = Str::slug('single-form research video', '_') . '_' . Str::random() . '.' . $ext_upload_info1;
                $filePath1 = 'open/single-form/research_video_upload/' . $fileName1;

                // Store the decoded file
                Storage::disk('public')->put($filePath1, $decodedVideo);

                $data->research_file_video = $filePath1;
                $data->relation_id_research_video = Str::uuid()->toString();

                $document_research_upload_video->doc_path = $filePath1;
                $document_research_upload_video->doc_type = "video";
                $document_research_upload_video->doc_status = "0";
                $document_research_upload_video->doc_status_remark = "Waiting Analysis";
                $document_research_upload_video->relation_id = $data->relation_id_research_video;
                $document_research_upload_video->save();

                $document_video_audio = new VideoAudioDocuments;
                $document_video_audio->doc_path = $filePath1;
                $document_video_audio->doc_type = "video_audio";
                $document_video_audio->doc_status = "0";
                $document_video_audio->doc_status_remark = "Waiting Analysis";
                $document_video_audio->relation_id = $data->relation_id_research_video;
                $document_video_audio->save();
            }



        }

        $document_interview_upload_pdf = new Documents;
        $document_interview_upload_video =new VideoDocuments;
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

            $filenames = [];
            $index = 1;
            if($request->file('interview_target_photo') != null){
                foreach ($request->file('interview_target_photo') as $image) {
                    $filename = time(). ' - '. $request->interview_target_name.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
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
            }
            $data->interview_target_photo  = json_encode($filenames);

            // if ($request->hasFile('interview_upload_dokumen_wawancara')) {
            //     $ext_upload_info = $request->file('interview_upload_dokumen_wawancara')->extension();
            //     $upload_info = $request->file('interview_upload_dokumen_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/interview_upload_dokumen_wawancara',
            //             Str::slug('single-form interview document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
            //             'public'
            //         );
    
            //     $data->interview_file_document = $upload_info;
            //     $data->relation_id_interview_document = Str::uuid()->toString();;
    
            //     $document_interview_upload_pdf->doc_path = $upload_info;
            //     $document_interview_upload_pdf->doc_type = "pdf";
            //     $document_interview_upload_pdf->doc_status = "0";
            //     $document_interview_upload_pdf->doc_status_remark = "Waiting Analysis";
            //     $document_interview_upload_pdf->relation_id = $data->relation_id_interview_document;
            //     $document_interview_upload_pdf->save();
        
            // }
    
            // if ($request->hasFile('interview_upload_video_wawancara')) {
         
            //     $ext_upload_info1 = $request->file('interview_upload_video_wawancara')->extension();
            //     $upload_info1 = $request->file('interview_upload_video_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/interview_upload_video_wawancara',
            //             Str::slug('single-form interview video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
            //             'public'
            //         );
                    
            //     $data->interview_file_video= $upload_info1;
            //     $data->relation_id_interview_video= Str::uuid()->toString();;;

            //     $document_interview_upload_video->doc_path = $upload_info1;
            //     $document_interview_upload_video->doc_type = "video";
            //     $document_interview_upload_video->doc_status = "0";
            //     $document_interview_upload_video->doc_status_remark = "Waiting Analysis";
            //     $document_interview_upload_video->relation_id = $data->relation_id_interview_video;
            //     $document_interview_upload_video->save();
                
            // }

            if ($request->input('interview_upload_dokumen_wawancara')) {
                // Decode base64 to file content
                $base64_document = $request->input('interview_upload_dokumen_wawancara');
                $decoded_file_content = base64_decode($base64_document);
                
                // Determine file extension from the base64 header
                $extension = 'pdf'; // You could parse this dynamically if needed
            
                // Create a unique file name
                $file_name = Str::slug('single-form interview document', '_') . '_' . Str::random() . '.' . $extension;
            
                // Define the file path
                $file_path = 'open/single-form/interview_upload_dokumen_wawancara/' . $file_name;
            
                // Store the decoded content as a file
                Storage::disk('public')->put($file_path, $decoded_file_content);
            
                // Assign the file path to data model
                $data->interview_file_document = $file_path;
                $data->relation_id_interview_document = Str::uuid()->toString();
            
                // Save document metadata to the database
                $document_interview_upload_pdf->doc_path = $file_path;
                $document_interview_upload_pdf->doc_type = "pdf";
                $document_interview_upload_pdf->doc_status = "0";
                $document_interview_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_interview_upload_pdf->relation_id = $data->relation_id_interview_document;
                $document_interview_upload_pdf->save();
            }
            
            if ($request->input('interview_upload_video_wawancara')) {
                // Decode base64 to file content
                $base64_video = $request->input('interview_upload_video_wawancara');
                $decoded_video_content = base64_decode($base64_video);
                
                // Determine file extension from the base64 header
                $extension = 'mp4'; // You could parse this dynamically if needed
            
                // Create a unique file name
                $file_name = Str::slug('single-form interview video', '_') . '_' . Str::random() . '.' . $extension;
            
                // Define the file path
                $file_path = 'open/single-form/interview_upload_video_wawancara/' . $file_name;
            
                // Store the decoded content as a file
                Storage::disk('public')->put($file_path, $decoded_video_content);
            
                // Assign the file path to data model
                $data->interview_file_video = $file_path;
                $data->relation_id_interview_video = Str::uuid()->toString();
            
                // Save video metadata to the database
                $document_interview_upload_video->doc_path = $file_path;
                $document_interview_upload_video->doc_type = "video";
                $document_interview_upload_video->doc_status = "0";
                $document_interview_upload_video->doc_status_remark = "Waiting Analysis";
                $document_interview_upload_video->relation_id = $data->relation_id_interview_video;
                $document_interview_upload_video->save();

                $document_video_audio = new VideoAudioDocuments;
                $document_video_audio->doc_path = $filePath1;
                $document_video_audio->doc_type = "video_audio";
                $document_video_audio->doc_status = "0";
                $document_video_audio->doc_status_remark = "Waiting Analysis";
                $document_video_audio->relation_id = $data->relation_id_interview_video;
                $document_video_audio->save();
            }
            
            $data->interview_saran_dan_tindak_lanjut = $request->interview_saran_dan_tindak_lanjut;
            
        }

        $document_interrogation_upload_pdf = new Documents;
        $document_interrogation_upload_video =new VideoDocuments;

        if($request->procedure_type == "interrogation" ||$request->procedure_type == "all"  ){
            $data->interrogation_target_name = $request->interrogation_target_name;
            $data->interrogation_target_identity_number = $request->interrogation_target_identity_number;
            $data->interrogation_target_religion = $request->interrogation_target_religion;
            $data->interrogation_target_education = $request->interrogation_education;
            $data->interrogation_target_gender = $request->interrogation_gender;
            $data->interrogation_target_occupation = $request->interrogation_occupation;
            $data->interrogation_target_address = $request->interrogation_address;

            $filenames = [];
            $index = 1;
            if($request->file('interrogation_target_photo') != null){
                foreach ($request->file('interrogation_target_photo') as $image) {
                    $filename = time(). ' - '. $request->interrogation_target_name.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
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
            }
            $data->interrogation_target_photo  = json_encode($filenames);

            // if ($request->hasFile('interrogation_upload_dokumen_wawancara')) {
            //     $ext_upload_info = $request->file('interrogation_upload_dokumen_wawancara')->extension();
            //     $upload_info = $request->file('interrogation_upload_dokumen_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/interrogation_upload_dokumen_wawancara',
            //             Str::slug('single-form interrogation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
            //             'public'
            //         );
    
            //     $data->interrogation_file_document = $upload_info;
            //     $data->relation_id_interrogation_document= Str::uuid()->toString();
    
            //     $document_interrogation_upload_pdf->doc_path = $upload_info;
            //     $document_interrogation_upload_pdf->doc_type = "pdf";
            //     $document_interrogation_upload_pdf->doc_status = "0";
            //     $document_interrogation_upload_pdf->doc_status_remark = "Waiting Analysis";
            //     $document_interrogation_upload_pdf->relation_id = $data->relation_id_interrogation_document;
            //     $document_interrogation_upload_pdf->save();
        
            // }
    
            // if ($request->hasFile('interrogation_upload_video_wawancara')) {
         
            //     $ext_upload_info1 = $request->file('interrogation_upload_video_wawancara')->extension();
            //     $upload_info1 = $request->file('interrogation_upload_video_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/interrogation_upload_video_wawancara',
            //             Str::slug('single-form interrogation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
            //             'public'
            //         );
                    
            //     $data->interrogation_file_video= $upload_info1;
            //     $data->relation_id_interrogation_video= Str::uuid()->toString();

            //     $document_interrogation_upload_video->doc_path = $upload_info1;
            //     $document_interrogation_upload_video->doc_type = "video";
            //     $document_interrogation_upload_video->doc_status = "0";
            //     $document_interrogation_upload_video->doc_status_remark = "Waiting Analysis";
            //     $document_interrogation_upload_video->relation_id = $data->relation_id_interrogation_video;
            //     $document_interrogation_upload_video->save();
                
            // }

            if ($request->has('interrogation_upload_dokumen_wawancara')) {
                $base64Doc = $request->input('interrogation_upload_dokumen_wawancara');
                $decodedDoc = base64_decode($base64Doc);
                
                $ext_upload_info = 'pdf'; // Atur format yang diharapkan, atau tentukan sesuai metadata Base64
                $filename = Str::slug('single-form interrogation document', '_') . '_' . Str::random() . '.' . $ext_upload_info;
                $uploadPath = 'open/single-form/interrogation_upload_dokumen_wawancara/' . $filename;
            
                Storage::disk('public')->put($uploadPath, $decodedDoc);
            
                $data->interrogation_file_document = $uploadPath;
                $data->relation_id_interrogation_document = Str::uuid()->toString();
            
                $document_interrogation_upload_pdf->doc_path = $uploadPath;
                $document_interrogation_upload_pdf->doc_type = "pdf";
                $document_interrogation_upload_pdf->doc_status = "0";
                $document_interrogation_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_interrogation_upload_pdf->relation_id = $data->relation_id_interrogation_document;
                $document_interrogation_upload_pdf->save();
            }
            
            if ($request->has('interrogation_upload_video_wawancara')) {
                $base64Video = $request->input('interrogation_upload_video_wawancara');
                $decodedVideo = base64_decode($base64Video);
                
                $ext_upload_info1 = 'mp4'; // Atur format yang diharapkan, atau tentukan sesuai metadata Base64
                $filename1 = Str::slug('single-form interrogation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1;
                $uploadPath1 = 'open/single-form/interrogation_upload_video_wawancara/' . $filename1;
            
                Storage::disk('public')->put($uploadPath1, $decodedVideo);
            
                $data->interrogation_file_video = $uploadPath1;
                $data->relation_id_interrogation_video = Str::uuid()->toString();
            
                $document_interrogation_upload_video->doc_path = $uploadPath1;
                $document_interrogation_upload_video->doc_type = "video";
                $document_interrogation_upload_video->doc_status = "0";
                $document_interrogation_upload_video->doc_status_remark = "Waiting Analysis";
                $document_interrogation_upload_video->relation_id = $data->relation_id_interrogation_video;
                $document_interrogation_upload_video->save();

                $document_video_audio = new VideoAudioDocuments;
                $document_video_audio->doc_path = $uploadPath1;
                $document_video_audio->doc_type = "video_audio";
                $document_video_audio->doc_status = "0";
                $document_video_audio->doc_status_remark = "Waiting Analysis";
                $document_video_audio->relation_id = $data->relation_id_interrogation_video;
                $document_video_audio->save();
            }
            
            $data->interrogation_target_identification = $request->interrogation_target_identification;
            $data->interrogation_result_achievement = $request->interrogation_result_achievement;

            
        }

        $document_elicitation_upload_pdf = new Documents;
        $document_elicitation_upload_video =new VideoDocuments;
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
                    $filename = time(). ' - '. $request->elicitation_interview_target_name.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
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
            }
            $data->elicitation_target_photo  = json_encode($filenames);

            // if ($request->hasFile('elicitation_upload_dokumen_wawancara')) {
            //     $ext_upload_info = $request->file('elicitation_upload_dokumen_wawancara')->extension();
            //     $upload_info = $request->file('elicitation_upload_dokumen_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/elicitation_upload_dokumen_wawancara',
            //             Str::slug('single-form elicitation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
            //             'public'
            //         );
    
            //     $data->elicitation_file_document = $upload_info;
            //     $data->relation_id_elicitation_document= Str::uuid()->toString();
    
            //     $document_elicitation_upload_pdf->doc_path = $upload_info;
            //     $document_elicitation_upload_pdf->doc_type = "pdf";
            //     $document_elicitation_upload_pdf->doc_status = "0";
            //     $document_elicitation_upload_pdf->doc_status_remark = "Waiting Analysis";
            //     $document_elicitation_upload_pdf->relation_id = $data->relation_id_elicitation_document;
            //     $document_elicitation_upload_pdf->save();
        
            // }
    
            // if ($request->hasFile('elicitation_upload_video_wawancara')) {
         
            //     $ext_upload_info1 = $request->file('elicitation_upload_video_wawancara')->extension();
            //     $upload_info1 = $request->file('elicitation_upload_video_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/elicitation_upload_video_wawancara',
            //             Str::slug('single-form elicitation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
            //             'public'
            //         );
                    
            //     $data->elicitation_file_video= $upload_info1;
            //     $data->relation_id_elicitation_video= Str::uuid()->toString();

            //     $document_elicitation_upload_video->doc_path = $upload_info1;
            //     $document_elicitation_upload_video->doc_type = "video";
            //     $document_elicitation_upload_video->doc_status = "0";
            //     $document_elicitation_upload_video->doc_status_remark = "Waiting Analysis";
            //     $document_elicitation_upload_video->relation_id = $data->relation_id_elicitation_video;
            //     $document_elicitation_upload_video->save();
                
            // }

            if ($request->has('elicitation_upload_dokumen_wawancara')) {
                $base64Doc = $request->input('elicitation_upload_dokumen_wawancara');
                $decodedDoc = base64_decode($base64Doc);
                
                $ext_upload_info = 'pdf'; // Sesuaikan dengan format file yang diharapkan
                $filename = Str::slug('single-form elicitation document', '_') . '_' . Str::random() . '.' . $ext_upload_info;
                $uploadPath = 'open/single-form/elicitation_upload_dokumen_wawancara/' . $filename;
            
                Storage::disk('public')->put($uploadPath, $decodedDoc);
            
                $data->elicitation_file_document = $uploadPath;
                $data->relation_id_elicitation_document = Str::uuid()->toString();
            
                $document_elicitation_upload_pdf->doc_path = $uploadPath;
                $document_elicitation_upload_pdf->doc_type = "pdf";
                $document_elicitation_upload_pdf->doc_status = "0";
                $document_elicitation_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_elicitation_upload_pdf->relation_id = $data->relation_id_elicitation_document;
                $document_elicitation_upload_pdf->save();
            }
            
            if ($request->has('elicitation_upload_video_wawancara')) {
                $base64Video = $request->input('elicitation_upload_video_wawancara');
                $decodedVideo = base64_decode($base64Video);
                
                $ext_upload_info1 = 'mp4'; // Sesuaikan dengan format file yang diharapkan
                $filename1 = Str::slug('single-form elicitation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1;
                $uploadPath1 = 'open/single-form/elicitation_upload_video_wawancara/' . $filename1;
            
                Storage::disk('public')->put($uploadPath1, $decodedVideo);
            
                $data->elicitation_file_video = $uploadPath1;
                $data->relation_id_elicitation_video = Str::uuid()->toString();
            
                $document_elicitation_upload_video->doc_path = $uploadPath1;
                $document_elicitation_upload_video->doc_type = "video";
                $document_elicitation_upload_video->doc_status = "0";
                $document_elicitation_upload_video->doc_status_remark = "Waiting Analysis";
                $document_elicitation_upload_video->relation_id = $data->relation_id_elicitation_video;
                $document_elicitation_upload_video->save();

                $document_video_audio = new VideoAudioDocuments;
                $document_video_audio->doc_path = $uploadPath1;
                $document_video_audio->doc_type = "video_audio";
                $document_video_audio->doc_status = "0";
                $document_video_audio->doc_status_remark = "Waiting Analysis";
                $document_video_audio->relation_id = $data->relation_id_elicitation_video;
                $document_video_audio->save();
            }

            $data->elicitation_saran_dan_tindak_lanjut = $request->elicitation_saran_dan_tindak_lanjut;
            $data->elicitation_hasil_yang_dicapai = $request->elicitation_hasil_yang_dicapai;
            

            
        }

        if ($data->save()) {
            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Data berhasil disimpan',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        return response()->json([
            "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            "message" => 'Data gagal disimpan',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'procedure_type' => 'required|string|max:128',
            'case_name' => 'required|string|max:128',
            'case_date' => 'required|date',
            'case_description' => 'required|string|max:128',
            'satker_id' => 'required|string|max:128',

            'nik' => 'required|string|max:128',
            'target_name' => 'nullable|string|max:128',
            'target_religion' => 'nullable|string|max:128',
            'target_education' => 'nullable|string|max:128',
            'target_occupation' => 'nullable|string|max:128',
            'target_gender' => 'nullable|string|max:128',

            'target_image' => 'array',
            'target_image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // custom validator
        switch ($request->input('procedure_type')) {
            case 'research':
                $rules['research_pendahuluan'] = 'required|string';
                $rules['research_data_fakta'] = 'required|string';
                $rules['research_informasi_diperoleh'] = 'required|string';
                $rules['research_sumber_informasi'] = 'required|string';
                $rules['research_tren_perkembangan'] = 'required|string';
                $rules['research_saran_tindak'] = 'required|string';
                $rules['research_aght_type'] = 'required|string';
                $rules['research_aght_description'] = 'required|string';
                // $rules['research_dokumen_upload'] = 'nullable|mimes:pdf|max:2048';
                // $rules['research_video_upload'] = 'nullable|mimes:mp4|max:2048';
                break;
            case 'interview':
                $rules['interview_nik'] = 'required|string|max:256';
                $rules['interview_interviewer_name'] = 'required|string|max:256';
                $rules['interview_interviewer_schedule'] = 'required|string|max:256';
                $rules['interview_target_name'] = 'required|string|max:256';
                $rules['interview_gender'] = 'required|string|max:100';
                $rules['interview_occupation'] = 'required|string|max:256';
                $rules['interview_education'] = 'required|string|max:256';
                $rules['interview_religion'] = 'required|string|max:256';
                $rules['interview_target_photo'] = 'array';
                $rules['interview_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['interview_address'] = 'required|string';
                // $rules['interview_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['interview_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                $rules['interview_saran_dan_tindak_lanjut'] = 'required|string';
                break;
            case 'interrogation':
                $rules['interrogation_target_identity_number'] = 'required|string|max:100';
                $rules['interrogation_target_name'] = 'required|string|max:256';
                $rules['interrogation_gender'] = 'required|string|max:100';
                $rules['interrogation_occupation'] = 'required|string|max:256';
                $rules['interrogation_education'] = 'required|string|max:256';
                $rules['interrogation_target_religion'] = 'required|string|max:256';
                $rules['interrogation_target_photo'] = 'array';
                $rules['interrogation_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['interrogation_address'] = 'required|string|max:256';
                // $rules['interrogation_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['interrogation_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                $rules['interrogation_target_identification'] = 'required|string';
                $rules['interrogation_result_achievement'] = 'required|string';
                break;
            case 'elicitation':
                $rules['elicitation_interviewer_name'] = 'required|string|max:256';
                $rules['elicitation_interview_target_identity_number'] = 'required|string|max:100';
                $rules['elicitation_interview_target_name'] = 'required|string|max:256';
                $rules['elicitation_target_gender'] = 'required|string|max:100';
                $rules['elicitation_target_occupation'] = 'required|string|max:256';
                $rules['elicitation_target_education'] = 'required|string|max:256';
                $rules['elicitation_target_address'] = 'required|string|max:256';
                $rules['elicitation_target_photo'] = 'array';
                $rules['elicitation_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['elicitation_target_religion'] = 'required|string|max:100';
                // $rules['elicitation_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['elicitation_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                $rules['elicitation_saran_dan_tindak_lanjut'] = 'required|string';
                $rules['elicitation_hasil_yang_dicapai'] = 'required|string';
                break;
            default:
                $rules['research_pendahuluan'] = 'required|string';
                $rules['research_data_fakta'] = 'required|string';
                $rules['research_informasi_diperoleh'] = 'required|string';
                $rules['research_sumber_informasi'] = 'required|string';
                $rules['research_tren_perkembangan'] = 'required|string';
                $rules['research_saran_tindak'] = 'required|string';
                $rules['research_aght_type'] = 'required|string';
                $rules['research_aght_description'] = 'required|string';
                // $rules['research_dokumen_upload'] = 'nullable|mimes:pdf|max:2048';
                // $rules['research_video_upload'] = 'nullable|mimes:mp4|max:2048';

                $rules['interrogation_target_identity_number'] = 'required|string|max:100';
                $rules['interrogation_target_name'] = 'required|string|max:256';
                $rules['interrogation_gender'] = 'required|string|max:100';
                $rules['interrogation_occupation'] = 'required|string|max:256';
                $rules['interrogation_education'] = 'required|string|max:256';
                $rules['interrogation_target_religion'] = 'required|string|max:256';
                $rules['interrogation_target_photo'] = 'array';
                $rules['interrogation_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['interrogation_address'] = 'required|string|max:256';
                // $rules['interrogation_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['interrogation_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                // $rules['interrogation_target_identification'] = 'required|string';
                $rules['interrogation_result_achievement'] = 'required|string';

                $rules['interrogation_target_identity_number'] = 'required|string|max:100';
                $rules['interrogation_target_name'] = 'required|string|max:256';
                $rules['interrogation_gender'] = 'required|string|max:100';
                $rules['interrogation_occupation'] = 'required|string|max:256';
                $rules['interrogation_education'] = 'required|string|max:256';
                $rules['interrogation_target_religion'] = 'required|string|max:256';
                $rules['interrogation_target_photo'] = 'array';
                $rules['interrogation_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['interrogation_address'] = 'required|string|max:256';
                // $rules['interrogation_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['interrogation_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                // $rules['interrogation_target_identification'] = 'required|string';
                $rules['interrogation_result_achievement'] = 'required|string';

                $rules['elicitation_interviewer_name'] = 'required|string|max:256';
                $rules['elicitation_interview_target_identity_number'] = 'required|string|max:100';
                $rules['elicitation_interview_target_name'] = 'required|string|max:256';
                $rules['elicitation_target_gender'] = 'required|string|max:100';
                $rules['elicitation_target_occupation'] = 'required|string|max:256';
                $rules['elicitation_target_education'] = 'required|string|max:256';
                $rules['elicitation_target_address'] = 'required|string|max:256';
                $rules['elicitation_target_photo'] = 'array';
                $rules['elicitation_target_photo.*'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
                $rules['elicitation_target_religion'] = 'required|string|max:100';
                // $rules['elicitation_upload_dokumen_wawancara'] = 'nullable|mimes:pdf|max:2048';
                // $rules['elicitation_upload_video_wawancara'] = 'nullable|mimes:mp4|max:2048';
                $rules['elicitation_saran_dan_tindak_lanjut'] = 'required|string';
                $rules['elicitation_hasil_yang_dicapai'] = 'required|string';
        }

        $this->validate($request, $rules);

        $user = Auth::guard('api')->user();

        $data = OpenCaseSingleForm::find($id);
        $data->updated_by = $user->id;
        $data->case_name = $request->case_name;
        $data->case_date = $request->case_date;
        $data->case_description = $request->case_description;

        $data->target_name = $request->target_name;
        $data->target_identity_number_type = 'NIK/KTP';
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
                $filename = time(). ' - '. $request->target_name.' - '. $index . '.'. $image->getClientOriginalExtension();
                
                
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
        }
        $data->target_photo  = json_encode($filenames);
        
        $data->satker_id = $request->satker_id;
        $data->open_procedure_type = $request->procedure_type;

        $document_research_upload_pdf = new Documents;
        $document_research_upload_video =new VideoDocuments;
        if($request->procedure_type == "research" ||$request->procedure_type == "all"  ){
            $data->research_lapinsus_pendahuluan = $request->research_pendahuluan;
            $data->research_data_dan_fakta = $request->research_data_fakta;
            $data->research_informasi_diperoleh = $request->research_informasi_diperoleh;
            $data->research_sumber_informasi = $request->research_sumber_informasi;
            $data->research_tren_perkembangan = $request->research_tren_perkembangan;
            $data->research_saran_tindak = $request->research_saran_tindak;
            $data->research_aght_type = $request->research_aght_type;
            $data->research_aght_description = $request->research_aght_description;

            
            // if ($request->hasFile('research_dokumen_upload')) {
            //     $ext_upload_info = $request->file('research_dokumen_upload')->extension();
            //     $upload_info = $request->file('research_dokumen_upload')
            //         ->storePubliclyAs(
            //             'open/single-form/research_dokumen_upload',
            //             Str::slug('single-form research document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
            //             'public'
            //         );
    
            //     $data->research_file_document = $upload_info;
            //     $data->relation_id_research_document = Str::uuid()->toString();;
    
            //     $document_research_upload_pdf->doc_path = $upload_info;
            //     $document_research_upload_pdf->doc_type = "pdf";
            //     $document_research_upload_pdf->doc_status = "0";
            //     $document_research_upload_pdf->doc_status_remark = "Waiting Analysis";
            //     $document_research_upload_pdf->relation_id = $data->relation_id_research_document;
            //     $document_research_upload_pdf->save();
        
            // }
    
            // if ($request->hasFile('research_video_upload')) {
         
            //     $ext_upload_info1 = $request->file('research_video_upload')->extension();
            //     $upload_info1 = $request->file('research_video_upload')
            //         ->storePubliclyAs(
            //             'open/single-form/research_video_upload',
            //             Str::slug('single-form research video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
            //             'public'
            //         );
                    
            //     $data->research_file_video= $upload_info1;
            //     $data->relation_id_research_video = Str::uuid()->toString();;

            //     $document_research_upload_video->doc_path = $upload_info1;
            //     $document_research_upload_video->doc_type = "video";
            //     $document_research_upload_video->doc_status = "0";
            //     $document_research_upload_video->doc_status_remark = "Waiting Analysis";
            //     $document_research_upload_video->relation_id = $data->relation_id_research_video;
            //     $document_research_upload_video->save();
                
            // }
            if ($request->has('research_dokumen_upload')) {
                $base64Doc = $request->input('research_dokumen_upload');
                $decodedDoc = base64_decode($base64Doc);
                
                $ext_upload_info = 'pdf'; // Sesuaikan dengan format file yang diharapkan
                $filename = Str::slug('single-form research document', '_') . '_' . Str::random() . '.' . $ext_upload_info;
                $uploadPath = 'open/single-form/research_dokumen_upload/' . $filename;
            
                Storage::disk('public')->put($uploadPath, $decodedDoc);
            
                $data->research_file_document = $uploadPath;
                $data->relation_id_research_document = Str::uuid()->toString();
            
                $document_research_upload_pdf->doc_path = $uploadPath;
                $document_research_upload_pdf->doc_type = "pdf";
                $document_research_upload_pdf->doc_status = "0";
                $document_research_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_research_upload_pdf->relation_id = $data->relation_id_research_document;
                $document_research_upload_pdf->save();
            }
            
            if ($request->has('research_video_upload')) {
                $base64Video = $request->input('research_video_upload');
                $decodedVideo = base64_decode($base64Video);
                
                $ext_upload_info1 = 'mp4'; // Sesuaikan dengan format file yang diharapkan
                $filename1 = Str::slug('single-form research video', '_') . '_' . Str::random() . '.' . $ext_upload_info1;
                $uploadPath1 = 'open/single-form/research_video_upload/' . $filename1;
            
                Storage::disk('public')->put($uploadPath1, $decodedVideo);
            
                $data->research_file_video = $uploadPath1;
                $data->relation_id_research_video = Str::uuid()->toString();
            
                $document_research_upload_video->doc_path = $uploadPath1;
                $document_research_upload_video->doc_type = "video";
                $document_research_upload_video->doc_status = "0";
                $document_research_upload_video->doc_status_remark = "Waiting Analysis";
                $document_research_upload_video->relation_id = $data->relation_id_research_video;
                $document_research_upload_video->save();

                $document_video_audio = new VideoAudioDocuments;
                $document_video_audio->doc_path = $uploadPath1;
                $document_video_audio->doc_type = "video_audio";
                $document_video_audio->doc_status = "0";
                $document_video_audio->doc_status_remark = "Waiting Analysis";
                $document_video_audio->relation_id = $data->relation_id_research_video;
                $document_video_audio->save();
            }

        }

        $document_interview_upload_pdf = new Documents;
        $document_interview_upload_video =new VideoDocuments;
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

            $filenames = [];
            $index = 1;
            if($request->file('interview_target_photo') != null){
                foreach ($request->file('interview_target_photo') as $image) {
                    $filename = time(). ' - '. $request->interview_target_name.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
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
            }
            $data->interview_target_photo  = json_encode($filenames);

            // if ($request->hasFile('interview_upload_dokumen_wawancara')) {
            //     $ext_upload_info = $request->file('interview_upload_dokumen_wawancara')->extension();
            //     $upload_info = $request->file('interview_upload_dokumen_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/interview_upload_dokumen_wawancara',
            //             Str::slug('single-form interview document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
            //             'public'
            //         );
    
            //     $data->interview_file_document = $upload_info;
            //     $data->relation_id_interview_document = Str::uuid()->toString();;
    
            //     $document_interview_upload_pdf->doc_path = $upload_info;
            //     $document_interview_upload_pdf->doc_type = "pdf";
            //     $document_interview_upload_pdf->doc_status = "0";
            //     $document_interview_upload_pdf->doc_status_remark = "Waiting Analysis";
            //     $document_interview_upload_pdf->relation_id = $data->relation_id_interview_document;
            //     $document_interview_upload_pdf->save();
        
            // }
    
            // if ($request->hasFile('interview_upload_video_wawancara')) {
         
            //     $ext_upload_info1 = $request->file('interview_upload_video_wawancara')->extension();
            //     $upload_info1 = $request->file('interview_upload_video_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/interview_upload_video_wawancara',
            //             Str::slug('single-form interview video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
            //             'public'
            //         );
                    
            //     $data->interview_file_video= $upload_info1;
            //     $data->relation_id_interview_video= Str::uuid()->toString();;;

            //     $document_interview_upload_video->doc_path = $upload_info1;
            //     $document_interview_upload_video->doc_type = "video";
            //     $document_interview_upload_video->doc_status = "0";
            //     $document_interview_upload_video->doc_status_remark = "Waiting Analysis";
            //     $document_interview_upload_video->relation_id = $data->relation_id_interview_video;
            //     $document_interview_upload_video->save();
                
            // }

            if ($request->has('interview_upload_dokumen_wawancara')) {
                $base64Doc = $request->input('interview_upload_dokumen_wawancara');
                $decodedDoc = base64_decode($base64Doc);
                
                $ext_upload_info = 'pdf'; // Tentukan format file yang diharapkan, misalnya 'pdf'
                $filename = Str::slug('single-form interview document', '_') . '_' . Str::random() . '.' . $ext_upload_info;
                $uploadPath = 'open/single-form/interview_upload_dokumen_wawancara/' . $filename;
            
                Storage::disk('public')->put($uploadPath, $decodedDoc);
            
                $data->interview_file_document = $uploadPath;
                $data->relation_id_interview_document = Str::uuid()->toString();
            
                $document_interview_upload_pdf->doc_path = $uploadPath;
                $document_interview_upload_pdf->doc_type = "pdf";
                $document_interview_upload_pdf->doc_status = "0";
                $document_interview_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_interview_upload_pdf->relation_id = $data->relation_id_interview_document;
                $document_interview_upload_pdf->save();
            }
            
            if ($request->has('interview_upload_video_wawancara')) {
                $base64Video = $request->input('interview_upload_video_wawancara');
                $decodedVideo = base64_decode($base64Video);
                
                $ext_upload_info1 = 'mp4'; // Tentukan format file yang diharapkan, misalnya 'mp4'
                $filename1 = Str::slug('single-form interview video', '_') . '_' . Str::random() . '.' . $ext_upload_info1;
                $uploadPath1 = 'open/single-form/interview_upload_video_wawancara/' . $filename1;
            
                Storage::disk('public')->put($uploadPath1, $decodedVideo);
            
                $data->interview_file_video = $uploadPath1;
                $data->relation_id_interview_video = Str::uuid()->toString();
            
                $document_interview_upload_video->doc_path = $uploadPath1;
                $document_interview_upload_video->doc_type = "video";
                $document_interview_upload_video->doc_status = "0";
                $document_interview_upload_video->doc_status_remark = "Waiting Analysis";
                $document_interview_upload_video->relation_id = $data->relation_id_interview_video;
                $document_interview_upload_video->save();

                $document_video_audio = new VideoAudioDocuments;
                $document_video_audio->doc_path = $uploadPath1;
                $document_video_audio->doc_type = "video_audio";
                $document_video_audio->doc_status = "0";
                $document_video_audio->doc_status_remark = "Waiting Analysis";
                $document_video_audio->relation_id = $data->relation_id_interview_video;
                $document_video_audio->save();
            }
            $data->interview_saran_dan_tindak_lanjut = $request->interview_saran_dan_tindak_lanjut;
            
        }

        $document_interrogation_upload_pdf = new Documents;
        $document_interrogation_upload_video =new VideoDocuments;

        if($request->procedure_type == "interrogation" ||$request->procedure_type == "all"  ){
            $data->interrogation_target_name = $request->interrogation_target_name;
            $data->interrogation_target_identity_number = $request->interrogation_target_identity_number;
            $data->interrogation_target_religion = $request->interrogation_target_religion;
            $data->interrogation_target_education = $request->interrogation_education;
            $data->interrogation_target_gender = $request->interrogation_gender;
            $data->interrogation_target_occupation = $request->interrogation_occupation;
            $data->interrogation_target_address = $request->interrogation_address;

            $filenames = [];
            $index = 1;
            if($request->file('interrogation_target_photo') != null){
                foreach ($request->file('interrogation_target_photo') as $image) {
                    $filename = time(). ' - '. $request->interrogation_target_name.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
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
            }
            $data->interrogation_target_photo  = json_encode($filenames);

            // if ($request->hasFile('interrogation_upload_dokumen_wawancara')) {
            //     $ext_upload_info = $request->file('interrogation_upload_dokumen_wawancara')->extension();
            //     $upload_info = $request->file('interrogation_upload_dokumen_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/interrogation_upload_dokumen_wawancara',
            //             Str::slug('single-form interrogation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
            //             'public'
            //         );
    
            //     $data->interrogation_file_document = $upload_info;
            //     $data->relation_id_interrogation_document= Str::uuid()->toString();
    
            //     $document_interrogation_upload_pdf->doc_path = $upload_info;
            //     $document_interrogation_upload_pdf->doc_type = "pdf";
            //     $document_interrogation_upload_pdf->doc_status = "0";
            //     $document_interrogation_upload_pdf->doc_status_remark = "Waiting Analysis";
            //     $document_interrogation_upload_pdf->relation_id = $data->relation_id_interrogation_document;
            //     $document_interrogation_upload_pdf->save();
        
            // }
    
            // if ($request->hasFile('interrogation_upload_video_wawancara')) {
         
            //     $ext_upload_info1 = $request->file('interrogation_upload_video_wawancara')->extension();
            //     $upload_info1 = $request->file('interrogation_upload_video_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/interrogation_upload_video_wawancara',
            //             Str::slug('single-form interrogation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
            //             'public'
            //         );
                    
            //     $data->interrogation_file_video= $upload_info1;
            //     $data->relation_id_interrogation_video= Str::uuid()->toString();

            //     $document_interrogation_upload_video->doc_path = $upload_info1;
            //     $document_interrogation_upload_video->doc_type = "video";
            //     $document_interrogation_upload_video->doc_status = "0";
            //     $document_interrogation_upload_video->doc_status_remark = "Waiting Analysis";
            //     $document_interrogation_upload_video->relation_id = $data->relation_id_interrogation_video;
            //     $document_interrogation_upload_video->save();
                
            // }
            if ($request->has('interrogation_upload_dokumen_wawancara')) {
                $base64Doc = $request->input('interrogation_upload_dokumen_wawancara');
                $decodedDoc = base64_decode($base64Doc);
                
                $ext_upload_info = 'pdf'; // Tentukan format file yang diharapkan, misalnya 'pdf'
                $filename = Str::slug('single-form interrogation document', '_') . '_' . Str::random() . '.' . $ext_upload_info;
                $uploadPath = 'open/single-form/interrogation_upload_dokumen_wawancara/' . $filename;
            
                Storage::disk('public')->put($uploadPath, $decodedDoc);
            
                $data->interrogation_file_document = $uploadPath;
                $data->relation_id_interrogation_document = Str::uuid()->toString();
            
                $document_interrogation_upload_pdf->doc_path = $uploadPath;
                $document_interrogation_upload_pdf->doc_type = "pdf";
                $document_interrogation_upload_pdf->doc_status = "0";
                $document_interrogation_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_interrogation_upload_pdf->relation_id = $data->relation_id_interrogation_document;
                $document_interrogation_upload_pdf->save();
            }
            
            if ($request->has('interrogation_upload_video_wawancara')) {
                $base64Video = $request->input('interrogation_upload_video_wawancara');
                $decodedVideo = base64_decode($base64Video);
                
                $ext_upload_info1 = 'mp4'; // Tentukan format file yang diharapkan, misalnya 'mp4'
                $filename1 = Str::slug('single-form interrogation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1;
                $uploadPath1 = 'open/single-form/interrogation_upload_video_wawancara/' . $filename1;
            
                Storage::disk('public')->put($uploadPath1, $decodedVideo);
            
                $data->interrogation_file_video = $uploadPath1;
                $data->relation_id_interrogation_video = Str::uuid()->toString();
            
                $document_interrogation_upload_video->doc_path = $uploadPath1;
                $document_interrogation_upload_video->doc_type = "video";
                $document_interrogation_upload_video->doc_status = "0";
                $document_interrogation_upload_video->doc_status_remark = "Waiting Analysis";
                $document_interrogation_upload_video->relation_id = $data->relation_id_interrogation_video;
                $document_interrogation_upload_video->save();

                $document_video_audio = new VideoAudioDocuments;
                $document_video_audio->doc_path = $uploadPath1;
                $document_video_audio->doc_type = "video_audio";
                $document_video_audio->doc_status = "0";
                $document_video_audio->doc_status_remark = "Waiting Analysis";
                $document_video_audio->relation_id = $data->relation_id_interrogation_video;
                $document_video_audio->save();
            }
            
            $data->interrogation_target_identification = $request->interrogation_target_identification;
            $data->interrogation_result_achievement = $request->interrogation_result_achievement;

            
        }

        $document_elicitation_upload_pdf = new Documents;
        $document_elicitation_upload_video =new VideoDocuments;
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
                    $filename = time(). ' - '. $request->elicitation_interview_target_name.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
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
            }
            $data->elicitation_target_photo  = json_encode($filenames);

            // if ($request->hasFile('elicitation_upload_dokumen_wawancara')) {
            //     $ext_upload_info = $request->file('elicitation_upload_dokumen_wawancara')->extension();
            //     $upload_info = $request->file('elicitation_upload_dokumen_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/elicitation_upload_dokumen_wawancara',
            //             Str::slug('single-form elicitation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
            //             'public'
            //         );
    
            //     $data->elicitation_file_document = $upload_info;
            //     $data->relation_id_elicitation_document= Str::uuid()->toString();
    
            //     $document_elicitation_upload_pdf->doc_path = $upload_info;
            //     $document_elicitation_upload_pdf->doc_type = "pdf";
            //     $document_elicitation_upload_pdf->doc_status = "0";
            //     $document_elicitation_upload_pdf->doc_status_remark = "Waiting Analysis";
            //     $document_elicitation_upload_pdf->relation_id = $data->relation_id_elicitation_document;
            //     $document_elicitation_upload_pdf->save();
        
            // }
    
            // if ($request->hasFile('elicitation_upload_video_wawancara')) {
         
            //     $ext_upload_info1 = $request->file('elicitation_upload_video_wawancara')->extension();
            //     $upload_info1 = $request->file('elicitation_upload_video_wawancara')
            //         ->storePubliclyAs(
            //             'open/single-form/elicitation_upload_video_wawancara',
            //             Str::slug('single-form elicitation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
            //             'public'
            //         );
                    
            //     $data->elicitation_file_video= $upload_info1;
            //     $data->relation_id_elicitation_video= Str::uuid()->toString();

            //     $document_elicitation_upload_video->doc_path = $upload_info1;
            //     $document_elicitation_upload_video->doc_type = "video";
            //     $document_elicitation_upload_video->doc_status = "0";
            //     $document_elicitation_upload_video->doc_status_remark = "Waiting Analysis";
            //     $document_elicitation_upload_video->relation_id = $data->relation_id_elicitation_video;
            //     $document_elicitation_upload_video->save();
                
            // }
            if ($request->has('elicitation_upload_dokumen_wawancara')) {
                $base64Doc = $request->input('elicitation_upload_dokumen_wawancara');
                $decodedDoc = base64_decode($base64Doc);
                
                $ext_upload_info = 'pdf'; // Tentukan format file yang diharapkan, misalnya 'pdf'
                $filename = Str::slug('single-form elicitation document', '_') . '_' . Str::random() . '.' . $ext_upload_info;
                $uploadPath = 'open/single-form/elicitation_upload_dokumen_wawancara/' . $filename;
            
                Storage::disk('public')->put($uploadPath, $decodedDoc);
            
                $data->elicitation_file_document = $uploadPath;
                $data->relation_id_elicitation_document = Str::uuid()->toString();
            
                $document_elicitation_upload_pdf->doc_path = $uploadPath;
                $document_elicitation_upload_pdf->doc_type = "pdf";
                $document_elicitation_upload_pdf->doc_status = "0";
                $document_elicitation_upload_pdf->doc_status_remark = "Waiting Analysis";
                $document_elicitation_upload_pdf->relation_id = $data->relation_id_elicitation_document;
                $document_elicitation_upload_pdf->save();
            }
            
            if ($request->has('elicitation_upload_video_wawancara')) {
                $base64Video = $request->input('elicitation_upload_video_wawancara');
                $decodedVideo = base64_decode($base64Video);
                
                $ext_upload_info1 = 'mp4'; // Tentukan format file yang diharapkan, misalnya 'mp4'
                $filename1 = Str::slug('single-form elicitation video', '_') . '_' . Str::random() . '.' . $ext_upload_info1;
                $uploadPath1 = 'open/single-form/elicitation_upload_video_wawancara/' . $filename1;
            
                Storage::disk('public')->put($uploadPath1, $decodedVideo);
            
                $data->elicitation_file_video = $uploadPath1;
                $data->relation_id_elicitation_video = Str::uuid()->toString();
            
                $document_elicitation_upload_video->doc_path = $uploadPath1;
                $document_elicitation_upload_video->doc_type = "video";
                $document_elicitation_upload_video->doc_status = "0";
                $document_elicitation_upload_video->doc_status_remark = "Waiting Analysis";
                $document_elicitation_upload_video->relation_id = $data->relation_id_elicitation_video;
                $document_elicitation_upload_video->save();

                $document_video_audio = new VideoAudioDocuments;
                $document_video_audio->doc_path = $uploadPath1;
                $document_video_audio->doc_type = "video_audio";
                $document_video_audio->doc_status = "0";
                $document_video_audio->doc_status_remark = "Waiting Analysis";
                $document_video_audio->relation_id = $data->relation_id_elicitation_video;
                $document_video_audio->save();
            }
            $data->elicitation_saran_dan_tindak_lanjut = $request->elicitation_saran_dan_tindak_lanjut;
            $data->elicitation_hasil_yang_dicapai = $request->elicitation_hasil_yang_dicapai;
            

            
        }


        if ($data->update()) {
            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Data berhasil diubah',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        return response()->json([
            "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            "message" => 'Data gagal disimpan',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function show(Request $request, $id)
    {
        // $data = OpenCaseSingleForm::find($id)?->with('satker')->first();
        $data = OpenCaseSingleForm::with('satker')->find($id);

        $images = [];
        $imagesInterview = [];
        $imagesElicitation = [];
        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);
            foreach ($imagePaths as $imagePath) {
                $images[] =Storage::url('open/single-form/' . $imagePath);
            }
        }
        if ($data->interview_target_photo) {
            $imagePaths = json_decode($data->interview_target_photo);
            foreach ($imagePaths as $imagePath) {
                $imagesInterview[] =Storage::url('open/single-form/interview/' . $imagePath);
            }
        }
        if ($data->elicitation_target_photo) {
            $imagePaths = json_decode($data->elicitation_target_photo);
            foreach ($imagePaths as $imagePath) {
                $imagesElicitation[] =Storage::url('open/single-form/elicitation/' . $imagePath);
            }
        }
        $data->target_photo = $images;
        $data->interview_target_photo = $imagesInterview;
        $data->elicitation_target_photo = $imagesElicitation;

        $data->research_file_video = Storage::url( $data->research_file_video);
        $data->research_file_document = Storage::url( $data->research_file_document);

        $research_document_pdf_data = Documents::where('relation_id', $data->relation_id_research_document)->first();

        $interview_images = [];
        if ($data->interview_target_photo) {
            $imagePaths = json_decode($data->interview_target_photo);
            foreach ($imagePaths as $imagePath) {
                $interview_images[] =Storage::url('open/single-form/interview/' . $imagePath);
            }
        }
        $data->interview_file_video = Storage::url( $data->interview_file_video);
        $data->interview_file_document = Storage::url( $data->interview_file_document);
        $interview_document_pdf_data = Documents::where('relation_id', $data->relation_id_interview_document)->first();

        $interrogation_images = [];
        if ($data->interrogation_target_photo) {
            $imagePaths = json_decode($data->interrogation_target_photo);
            foreach ($imagePaths as $imagePath) {
                $interrogation_images[] =Storage::url('open/single-form/interrogation/' . $imagePath);
            }
        }
        $data->interrogation_file_video = Storage::url( $data->interrogation_file_video);
        $data->interrogation_file_document = Storage::url( $data->interrogation_file_document);
        $interrogation_document_pdf_data = Documents::where('relation_id', $data->relation_id_interrogation_document)->first();

        $elicitation_images = [];
        if ($data->elicitation_target_photo) {
            $imagePaths = json_decode($data->elicitation_target_photo);
            foreach ($imagePaths as $imagePath) {
                $elicitation_images[] =Storage::url('open/single-form/elicitation/' . $imagePath);
            }
        }
        $data->elicitation_file_video = Storage::url( $data->elicitation_file_video);
        $data->elicitation_file_document = Storage::url( $data->elicitation_file_document);
        $elicitation_document_pdf_data = Documents::where('relation_id', $data->relation_id_elicitation_document)->first();


        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => [
                'data' => $data, 
                'images' => $images,
                'research_document_pdf_data' => $research_document_pdf_data,

                'interview_images' => $interview_images,
                'interview_document_pdf_data' => $interview_document_pdf_data,

                'interrogation_images' => $interrogation_images,
                'interrogation_document_pdf_data' => $interrogation_document_pdf_data,

                'elicitation_images' => $elicitation_images,
                'elicitation_document_pdf_data' => $elicitation_document_pdf_data,
                'bodycam_devices' => $bodycam_devices
            ],
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $data = OpenCaseSingleForm::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data Tidak Ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        
        $data->delete();

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil dihapus',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function downloadFile($id_case)
    {
        $data = OpenCaseSingleForm::find($id_case);
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
        $mpdf->Output($filename, 'D');
    }

}
