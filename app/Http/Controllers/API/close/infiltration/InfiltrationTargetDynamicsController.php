<?php

namespace App\Http\Controllers\API\close\infiltration;

use App\Models\Documents;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\VideoDocuments;
use App\Models\CaseCloseProgresses;
use App\Models\VideoAudioDocuments;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\Models\Infiltration\InfiltrationTargetDynamics;
use App\Models\Infiltration\InfiltrationResultAchievement;

class InfiltrationTargetDynamicsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = InfiltrationTargetDynamics::when(!$user->hasRole(['superadmin',]), function ($q) use ($idSatker) {
            $q->where('infiltration_dinamika_target.satker_id', '=', $idSatker);
        })
            ->with('case')
            ->with('satker')
            ->with('secret_operation')
            ->latest()
            ->paginate(10);

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
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'id_infiltration_operasi_rahasia' => 'required|string|max:128',
            'dinamika_teramati' => 'required|string|max:1000000',
            // 'tanggal_dinamika_teramati' => 'required|date',
            // 'deskripsi_dinamika_teramati' => 'required|string|max:1000000',
            // 'dinamika_target_dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            // 'dinamika_target_video_upload' => 'nullable|file|mimes:mp4|max:200048'
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();

        $data = new InfiltrationTargetDynamics;
        $data->satker_id = $satker->id_satker;
        $data->case_id = $request->id_case;
        $data->infiltration_operasi_rahasia_id = $request->id_infiltration_operasi_rahasia;

        $data->dinamika_teramati = $request->dinamika_teramati;
        $data->tanggal_dinamika_teramati = $request->tanggal_dinamika_teramati;
        $data->deskripsi_dinamika_teramati = $request->deskripsi_dinamika_teramati;



        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        $document_pdf = new Documents;
        // if ($request->hasFile('dinamika_target_dokumen_upload')) {
        //     $ext_upload_info = $request->file('dinamika_target_dokumen_upload')->extension();
        //     $upload_info = $request->file('dinamika_target_dokumen_upload')
        //         ->storePubliclyAs(
        //             'close/infiltration/target-dynamics/dinamika_target_dokumen_upload',
        //             Str::slug('infiltration target-dynamics document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->dinamika_target_dokumen_upload = $upload_info;


        //     $document_pdf->doc_path = $upload_info;
        //     $document_pdf->doc_type = "pdf";
        //     $document_pdf->doc_status = "0";
        //     $document_pdf->doc_status_remark = "Waiting Analysis";

        // }

        if ($request->input('dinamika_target_dokumen_upload')) {
            $base64_document = $request->input('dinamika_target_dokumen_upload');

            // Extract the file extension from Base64 string, assuming it's in a data URI format (e.g., data:application/pdf;base64,...)
            // if (preg_match('/^data:application\/(\w+);base64,/', $base64_document, $type)) {
            //     $ext_upload_info = strtolower($type[1]); // Get the file extension (e.g., pdf)
            //     $base64_document = substr($base64_document, strpos($base64_document, ',') + 1); // Remove the mime type from the Base64 string
            // } else {
            //     // Handle error: invalid Base64 string format
            //     return response()->json(['error' => 'Invalid document format'], 400);
            // }

            // Decode the Base64 string
            $document_data = base64_decode($base64_document);

            // Generate a unique filename
            // $filename = Str::slug('infiltration target-dynamics document', '_') . '_' . Str::random() . '.' . $ext_upload_info;
            $filename = Str::slug('infiltration target-dynamics document', '_') . '_' . Str::random() . '.pdf';


            // Store the decoded document file
            $file_path = 'close/infiltration/target-dynamics/dinamika_target_dokumen_upload/' . $filename;
            Storage::disk('public')->put($file_path, $document_data);

            // Save the file path to the `dinamika_target_dokumen_upload` field in the database
            $data->dinamika_target_dokumen_upload = $file_path;

            // Save the document details in the `Documents` model
            $document_pdf->doc_path = $file_path;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
        }


        // $document_video = new VideoDocuments;
        // $document_video_audio = new VideoDocuments;
        // if ($request->hasFile('dinamika_target_video_upload')) {
        //     $ext_upload_info = $request->file('dinamika_target_video_upload')->extension();
        //     $upload_info = $request->file('dinamika_target_video_upload')
        //         ->storePubliclyAs(
        //             'close/infiltration/secret-operation/dinamika_target_video_upload',
        //             Str::slug('infiltration target-dynamics video', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->dinamika_target_video_upload = $upload_info;


        //     $document_video->doc_path = $upload_info;
        //     $document_video->doc_type = "video";
        //     $document_video->doc_status = "0";
        //     $document_video->doc_status_remark = "Waiting Analysis";

        //     $document_video_audio->doc_path = $upload_info;
        //     $document_video_audio->doc_type = "video_audio";
        //     $document_video_audio->doc_status = "0";
        //     $document_video_audio->doc_status_remark = "Waiting Analysis";
        // }
        $document_video = new VideoDocuments;
        $document_video_audio = new VideoDocuments;

        if ($request->has('dinamika_target_video_upload')) {
            // Get the base64 string
            $base64String = $request->input('dinamika_target_video_upload');

            // Decode base64 string
            $decodedData = base64_decode($base64String);

            // Define the file extension (e.g., mp4)
            $ext_upload_info = 'mp4'; // Assuming mp4, change based on actual type if necessary

            // Create a unique file name
            $fileName = Str::slug('infiltration target-dynamics video', '_') . '_' . Str::random() . '.' . $ext_upload_info;

            // Store the file in the desired path
            $filePath = 'close/infiltration/secret-operation/dinamika_target_video_upload/' . $fileName;
            Storage::disk('public')->put($filePath, $decodedData);

            // Save the file path in the database
            $data->dinamika_target_video_upload = $filePath;

            // Assign the file path to the VideoDocuments objects
            $document_video->doc_path = $filePath;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";

            $document_video_audio->doc_path = $filePath;
            $document_video_audio->doc_type = "video_audio";
            $document_video_audio->doc_status = "0";
            $document_video_audio->doc_status_remark = "Waiting Analysis";
        }


        $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update->case_id = $data->id;
        $data_case_close_historical_update->action = "Penambahan Dinamika Target";

        $data_case_close_historical_update->created_by = $user->id;
        $data_case_close_historical_update->updated_by = $user->id;


        $data_case_close_historical_update->created_by = $user->id;
        $data_case_close_historical_update->updated_by = $user->id;

        if ($request->submit_type === 'save') {


            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('infiltration_dinamika_target', '0')
                ->update([
                    'infiltration_dinamika_target' => "1",
                    'status' => "Penyusupan",
                    'substatus' => "Penambahan Dinamika Target",
                    'percentage' => round((19 / 29) * 100, 2)
                ]);
            ;
        } else {
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('infiltration_dinamika_target', '0')
                ->update([
                    'infiltration_dinamika_target' => "1",
                    'status' => "Penyusupan",
                    'substatus' => "Penambahan Dinamika Target",
                    'percentage' => round((29 / 29) * 100, 2)
                ]);
            ;

        }

        if ($data->save()) {
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update->save();

            if ($request->input('dinamika_target_dokumen_upload')) {
                $document_pdf->relation_id = $data->id;
                $document_pdf->save();
            }

            if ($request->input('dinamika_target_video_upload')) {

                $document_video->relation_id = $data->id;
                $document_video->save();

                $document_video_audio->relation_id = $data->id;
                $document_video_audio->save();
            }

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
            "message" => 'Data Gagal Disimpan',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function show(Request $request, $id)
    {
       


        $data = InfiltrationTargetDynamics::with([
            'satker',
            'case',
            'secret_operation'
        ])->where('id', $id)->first();

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function destroy($id, Request $request)
    {
        $data = InfiltrationTargetDynamics::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if ($data->surat_perintah_path) {
            if (Storage::disk('public')->exists($data->surat_perintah_path)) {
                Storage::disk('public')->delete($data->surat_perintah_path);
            }
        }

        $data->delete();
        InfiltrationResultAchievement::where('infiltration_dinamika_target_id', $id)->delete();

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil dihapus',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'id_infiltration_operasi_rahasia' => 'required|string|max:128',
            'dinamika_teramati' => 'required|string|max:1000000',
            // 'tanggal_dinamika_teramati' => 'required|date',
            // 'deskripsi_dinamika_teramati' => 'required|string|max:1000000',
            // 'dinamika_target_dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            // 'dinamika_target_video_upload' => 'nullable|file|mimes:pdf|max:2048'
        ]);


        $user = Auth::guard('api')->user();

        $data = InfiltrationTargetDynamics::find($id);
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->infiltration_operasi_rahasia_id = $request->id_infiltration_operasi_rahasia;

        $data->dinamika_teramati = $request->dinamika_teramati;
        $data->tanggal_dinamika_teramati = $request->tanggal_dinamika_teramati;
        $data->deskripsi_dinamika_teramati = $request->deskripsi_dinamika_teramati;

        $data->updated_by = $user->id;

        // if ($request->hasFile('dinamika_target_dokumen_upload')) {
        //     $ext_upload_info = $request->file('dinamika_target_dokumen_upload')->extension();
        //     $upload_info = $request->file('dinamika_target_dokumen_upload')
        //         ->storePubliclyAs(
        //             'close/infiltration/target-dynamics/dinamika_target_dokumen_upload',
        //             Str::slug('infiltration target-dynamics document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->dinamika_target_dokumen_upload = $upload_info;

        //     $document_pdf = new Documents;
        //     $document_pdf->doc_path = $upload_info;
        //     $document_pdf->doc_video = "pdf";
        //     $document_pdf->doc_status_remark = "Waiting Analysis";
        //     $document_pdf->relation_id = $id;
        //     $document_pdf->save();
        // }

        if ($request->has('dinamika_target_dokumen_upload')) {
            // Get the base64 string
            $base64String = $request->input('dinamika_target_dokumen_upload');
        
            // Decode base64 string
            $decodedData = base64_decode($base64String);
        
            // Define the file extension (e.g., pdf)
            $ext_upload_info = 'pdf'; // Assuming PDF, change if necessary
        
            // Create a unique file name
            $fileName = Str::slug('infiltration target-dynamics document', '_') . '_' . Str::random() . '.' . $ext_upload_info;
        
            // Store the file in the desired path
            $filePath = 'close/infiltration/target-dynamics/dinamika_target_dokumen_upload/' . $fileName;
            Storage::disk('public')->put($filePath, $decodedData);
        
            // Save the file path in the database
            $data->dinamika_target_dokumen_upload = $filePath;
        
            // Create a new document record
            $document_pdf = new Documents;
            $document_pdf->doc_path = $filePath;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->relation_id = $id;
            $document_pdf->save();
        }
        

        // if ($request->hasFile('dinamika_target_video_upload')) {
        //     $ext_upload_info = $request->file('dinamika_target_video_upload')->extension();
        //     $upload_info = $request->file('dinamika_target_video_upload')
        //         ->storePubliclyAs(
        //             'close/infiltration/secret-operation/dinamika_target_video_upload',
        //             Str::slug('infiltration target-dynamics video', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->dinamika_target_video_upload = $upload_info;

        //     $document_video = new VideoDocuments;
        //     $document_video->doc_path = $upload_info;
        //     $document_video->doc_type = "video";
        //     $document_video->doc_status_remark = "Waiting Analysis";
        //     $document_video->relation_id = $id;
        //     $document_video->save();

        //     $document_video_audio = new VideoAudioDocuments;
        //     $document_video_audio->doc_path = $upload_info;
        //     $document_video_audio->doc_type = "video_audio";
        //     $document_video_audio->doc_status_remark = "Waiting Analysis";
        //     $document_video_audio->relation_id = $id;
        //     $document_video_audio->save();
        // }

        if ($request->has('dinamika_target_video_upload')) {
            // Get the base64 string from the request input
            $base64String = $request->input('dinamika_target_video_upload');
        
            // Decode base64 string into binary data
            $decodedData = base64_decode($base64String);
        
            // Define the file extension (assuming mp4 for video, adjust if necessary)
            $ext_upload_info = 'mp4'; // You can determine the file extension dynamically if needed
        
            // Create a unique file name for the video
            $fileName = Str::slug('infiltration target-dynamics video', '_') . '_' . Str::random() . '.' . $ext_upload_info;
        
            // Store the decoded data as a file
            $filePath = 'close/infiltration/secret-operation/dinamika_target_video_upload/' . $fileName;
            Storage::disk('public')->put($filePath, $decodedData);
        
            // Save the file path in the data object
            $data->dinamika_target_video_upload = $filePath;
        
            // Create a new VideoDocuments record
            $document_video = new VideoDocuments;
            $document_video->doc_path = $filePath;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();
        
            // Create a new VideoAudioDocuments record
            $document_video_audio = new VideoAudioDocuments;
            $document_video_audio->doc_path = $filePath;
            $document_video_audio->doc_type = "video_audio";
            $document_video_audio->doc_status = "0";
            $document_video_audio->doc_status_remark = "Waiting Analysis";
            $document_video_audio->relation_id = $id;
            $document_video_audio->save();
        }
        

        if ($request->submit_type === 'update_and_finish') {

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('infiltration_dinamika_target', '0')
                ->update([
                    'infiltration_dinamika_target' => "1",
                    'status' => "Penyusupan",
                    'substatus' => "Penambahan Dinamika Target",
                    'percentage' => round((29 / 29) * 100, 2)
                ]);
            ;

        }

        if ($data->update()) {
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
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

}
