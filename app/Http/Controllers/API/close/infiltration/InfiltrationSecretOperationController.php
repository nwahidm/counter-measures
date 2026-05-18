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
use App\Models\Infiltration\InfiltrationSecretOperation;
use App\Models\Infiltration\InfiltrationResultAchievement;

class InfiltrationSecretOperationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = InfiltrationSecretOperation::when(!$user->hasRole(['superadmin',]), function ($q) use ($idSatker) {
            $q->where('infiltration_operasi_rahasia.satker_id', '=', $idSatker);
        })
            ->with('case')
            ->with('satker')
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
            'nama_operasi_rahasia' => 'required|string|max:1000000',
            // 'tanggal_operasi_rahasia' => 'required|date',
            // 'metode_eksekusi' => 'required|string|max:1000000',
            // 'operasi_rahasia_dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            // 'operasi_rahasia_video_upload' => 'nullable|file|mimes:mp4|max:200048'
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();


        $data = new InfiltrationSecretOperation;
        $data->satker_id = $satker->id_satker;
        $data->case_id = $request->id_case;

        $data->nama_operasi_rahasia = $request->nama_operasi_rahasia;
        $data->tanggal_operasi_rahasia = $request->tanggal_operasi_rahasia;
        $data->metode_eksekusi = $request->metode_eksekusi;



        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        $document_pdf = new Documents;
        // if ($request->hasFile('operasi_rahasia_dokumen_upload')) {
        //     $ext_upload_info = $request->file('operasi_rahasia_dokumen_upload')->extension();
        //     $upload_info = $request->file('operasi_rahasia_dokumen_upload')
        //         ->storePubliclyAs(
        //             'close/infiltration/secret-operation/operasi_rahasia_dokumen_upload',
        //             Str::slug('infiltration secret-operation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->operasi_rahasia_dokumen_upload = $upload_info;
        //     $document_pdf->doc_path = $upload_info;
        //     $document_pdf->doc_type = "pdf";
        //     $document_pdf->doc_status = "0";
        //     $document_pdf->doc_status_remark = "Waiting Analysis";
        // }

        if ($request->input('operasi_rahasia_dokumen_upload')) {
            $base64_pdf = $request->input('operasi_rahasia_dokumen_upload');

            // Extract file extension from Base64 string, assuming it's in a data URI format (e.g., data:application/pdf;base64,...)
            // if (preg_match('/^data:application\/(\w+);base64,/', $base64_pdf, $type)) {
            //     $ext_upload_info = strtolower($type[1]); // Get the file extension (e.g., pdf)
            //     $base64_pdf = substr($base64_pdf, strpos($base64_pdf, ',') + 1); // Remove the mime type from the Base64 string
            // } else {
            //     // Handle error: invalid Base64 string format
            //     return response()->json(['error' => 'Invalid PDF format'], 400);
            // }

            // Decode the Base64 string
            $pdf_data = base64_decode($base64_pdf);

            // Generate a unique filename
            // $filename = Str::slug('infiltration secret-operation document', '_') . '_' . Str::random() . '.' . $ext_upload_info;
            $filename = Str::slug('infiltration secret-operation document', '_') . '_' . Str::random() . '.pdf';

            // Store the decoded PDF file
            $file_path = 'close/infiltration/secret-operation/operasi_rahasia_dokumen_upload/' . $filename;
            Storage::disk('public')->put($file_path, $pdf_data);

            // Save the file path to the `operasi_rahasia_dokumen_upload` field in the database
            $data->operasi_rahasia_dokumen_upload = $file_path;

            // Save the document details in the `Documents` model
            $document_pdf = new Documents;
            $document_pdf->doc_path = $file_path;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";

        }


        $document_video = new VideoDocuments;
        $document_video_audio = new VideoAudioDocuments;
        // if ($request->hasFile('operasi_rahasia_video_upload')) {
        //     $ext_upload_info = $request->file('operasi_rahasia_video_upload')->extension();
        //     $upload_info = $request->file('operasi_rahasia_video_upload')
        //         ->storePubliclyAs(
        //             'close/infiltration/secret-operation/operasi_rahasia_video_upload',
        //             Str::slug('infiltration secret-operation video', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->operasi_rahasia_video_upload = $upload_info;

        //     $document_video->doc_path = $upload_info;
        //     $document_video->doc_type = "video";
        //     $document_video->doc_status = "0";
        //     $document_video->doc_status_remark = "Waiting Analysis";

        //     $document_video_audio->doc_path = $upload_info;
        //     $document_video_audio->doc_type = "video_audio";
        //     $document_video_audio->doc_status = "0";
        //     $document_video_audio->doc_status_remark = "Waiting Analysis";
        // }

        if ($request->input('operasi_rahasia_video_upload')) {
            $base64_video = $request->input('operasi_rahasia_video_upload');

            // Extract file extension from Base64 string, assuming it's in a data URI format (e.g., data:video/mp4;base64,...)
            // if (preg_match('/^data:video\/(\w+);base64,/', $base64_video, $type)) {
            //     $ext_upload_info = strtolower($type[1]); // Get the file extension (e.g., mp4)
            //     $base64_video = substr($base64_video, strpos($base64_video, ',') + 1); // Remove the mime type from the Base64 string
            // } else {
            //     // Handle error: invalid Base64 string format
            //     return response()->json(['error' => 'Invalid video format'], 400);
            // }

            // Decode the Base64 string
            $video_data = base64_decode($base64_video);

            // Generate a unique filename
            // $filename = Str::slug('infiltration secret-operation video', '_') . '_' . Str::random() . '.' . $ext_upload_info;
            $filename = Str::slug('infiltration secret-operation video', '_') . '_' . Str::random() . '.mp4';

            // Store the decoded video file
            $file_path = 'close/infiltration/secret-operation/operasi_rahasia_video_upload/' . $filename;
            Storage::disk('public')->put($file_path, $video_data);

            // Save the file path to the `operasi_rahasia_video_upload` field in the database
            $data->operasi_rahasia_video_upload = $file_path;

            // Save the video details in the `VideoDocuments` model
            // $document_video = new VideoDocuments;
            $document_video->doc_path = $file_path;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";
            // $document_video->relation_id = $data->id; // Assuming $data->id is available
            // $document_video->save();

            // Save the video-audio details in the `VideoAudioDocuments` model
            // $document_video_audio = new VideoAudioDocuments;
            $document_video_audio->doc_path = $file_path;
            $document_video_audio->doc_type = "video_audio";
            $document_video_audio->doc_status = "0";
            $document_video_audio->doc_status_remark = "Waiting Analysis";
            // $document_video_audio->relation_id = $data->id; // Assuming $data->id is available
            // $document_video_audio->save();
        }


        if ($request->submit_type === 'save') {


            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('infiltration_operasi_rahasia', '0')
                ->update([
                    'infiltration_operasi_rahasia' => "1",
                    'status' => "Penyusupan",
                    'substatus' => "Penambahan Operasi Rahasia",
                    'percentage' => round((18 / 29) * 100, 2)
                ]);
            ;
        } else {
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('infiltration_operasi_rahasia', '0')
                ->update([
                    'infiltration_operasi_rahasia' => "1",
                    'status' => "Penyusupan",
                    'substatus' => "Penambahan Operasi Rahasia",
                    'percentage' => round((29 / 29) * 100, 2)
                ]);
            ;

        }

        if ($data->save()) {


            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update->action = "Penambahan Operasi Rahasia";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            if ($request->input('operasi_rahasia_dokumen_upload')) {
                $document_pdf->relation_id = $data->id;
                $document_pdf->save();
            }

            if ($request->input('operasi_rahasia_video_upload')) {
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

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'nama_operasi_rahasia' => 'required|string|max:1000000',
            // 'tanggal_operasi_rahasia' => 'required|date',
            // 'metode_eksekusi' => 'required|string|max:1000000',
            // 'operasi_rahasia_dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            // 'operasi_rahasia_video_upload' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = InfiltrationSecretOperation::find($id);
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;

        $data->nama_operasi_rahasia = $request->nama_operasi_rahasia;
        $data->tanggal_operasi_rahasia = $request->tanggal_operasi_rahasia;
        $data->metode_eksekusi = $request->metode_eksekusi;



        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        // if ($request->hasFile('operasi_rahasia_dokumen_upload')) {
        //     $ext_upload_info = $request->file('operasi_rahasia_dokumen_upload')->extension();
        //     $upload_info = $request->file('operasi_rahasia_dokumen_upload')
        //         ->storePubliclyAs(
        //             'close/infiltration/secret-operation/operasi_rahasia_dokumen_upload',
        //             Str::slug('infiltration secret-operation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->operasi_rahasia_dokumen_upload = $upload_info;

        //     $document_pdf = new Documents;
        //     $document_pdf->doc_path = $upload_info;
        //     $document_pdf->doc_type = "pdf";
        //     $document_pdf->doc_status= "0";
        //     $document_pdf->doc_status_remark = "Waiting Analysis";
        //     $document_pdf->relation_id = $id;
        //     $document_pdf->save();
        // }

        if ($request->input('operasi_rahasia_dokumen_upload')) {
            $base64_document = $request->input('operasi_rahasia_dokumen_upload');

            // Extract file extension from Base64 string, assuming it's in a data URI format (e.g., data:application/pdf;base64,...)
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
            // $filename = Str::slug('infiltration secret-operation document', '_') . '_' . Str::random() . '.' . $ext_upload_info;
            $filename = Str::slug('infiltration secret-operation document', '_') . '_' . Str::random() . '.pdf';

            // Store the decoded document file
            $file_path = 'close/infiltration/secret-operation/operasi_rahasia_dokumen_upload/' . $filename;
            Storage::disk('public')->put($file_path, $document_data);

            // Save the file path to the `operasi_rahasia_dokumen_upload` field in the database
            $data->operasi_rahasia_dokumen_upload = $file_path;

            // Save the document details in the `Documents` model
            $document_pdf = new Documents;
            $document_pdf->doc_path = $file_path;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->relation_id = $id; // Assuming $id is available
            $document_pdf->save();
        }


        // if ($request->hasFile('operasi_rahasia_video_upload')) {
        //     $ext_upload_info = $request->file('operasi_rahasia_video_upload')->extension();
        //     $upload_info = $request->file('operasi_rahasia_video_upload')
        //         ->storePubliclyAs(
        //             'close/infiltration/secret-operation/operasi_rahasia_video_upload',
        //             Str::slug('infiltration secret-operation video', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->operasi_rahasia_video_upload = $upload_info;

        //     $document_video = new VideoDocuments;
        //     $document_video->doc_path = $upload_info;
        //     $document_video->doc_type = "video";
        //     $document_video->doc_status= "0";
        //     $document_video->doc_status_remark = "Waiting Analysis";
        //     $document_video->relation_id = $id;
        //     $document_video->save();

        //     $document_video_audio = new VideoAudioDocuments;
        //     $document_video_audio->doc_path = $upload_info;
        //     $document_video_audio->doc_type = "video_audio";
        //     $document_video_audio->doc_status= "0";
        //     $document_video_audio->doc_status_remark = "Waiting Analysis";
        //     $document_video_audio->relation_id = $id;
        //     $document_video_audio->save();
        // }

        if ($request->input('operasi_rahasia_video_upload')) {
            $base64_video = $request->input('operasi_rahasia_video_upload');

            // Extract file extension from Base64 string, assuming it's in a data URI format (e.g., data:video/mp4;base64,...)
            // if (preg_match('/^data:video\/(\w+);base64,/', $base64_video, $type)) {
            //     $ext_upload_info = strtolower($type[1]); // Get the file extension (e.g., mp4)
            //     $base64_video = substr($base64_video, strpos($base64_video, ',') + 1); // Remove the mime type from the Base64 string
            // } else {
            //     // Handle error: invalid Base64 string format
            //     return response()->json(['error' => 'Invalid video format'], 400);
            // }

            // Decode the Base64 string
            $video_data = base64_decode($base64_video);

            // Generate a unique filename
            // $filename = Str::slug('infiltration secret-operation video', '_') . '_' . Str::random() . '.' . $ext_upload_info;
            $filename = Str::slug('infiltration secret-operation video', '_') . '_' . Str::random() . '.mp4';

            // Store the decoded video file
            $file_path = 'close/infiltration/secret-operation/operasi_rahasia_video_upload/' . $filename;
            Storage::disk('public')->put($file_path, $video_data);

            // Save the file path to the `operasi_rahasia_video_upload` field in the database
            $data->operasi_rahasia_video_upload = $file_path;

            // Save the video details in the `VideoDocuments` model
            $document_video = new VideoDocuments;
            $document_video->doc_path = $file_path;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();

            // Save the video-audio details in the `VideoAudioDocuments` model
            $document_video_audio = new VideoAudioDocuments;
            $document_video_audio->doc_path = $file_path;
            $document_video_audio->doc_type = "video_audio";
            $document_video_audio->doc_status = "0";
            $document_video_audio->doc_status_remark = "Waiting Analysis";
            $document_video_audio->relation_id = $id;
            $document_video_audio->save();
        }


        $data->updated_by = $user->id;


        if ($request->submit_type === 'update_and_finish') {

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('infiltration_operasi_rahasia', '0')
                ->update([
                    'infiltration_operasi_rahasia' => "1",
                    'status' => "Penyusupan",
                    'substatus' => "Penambahan Operasi Rahasia",
                    'percentage' => round((18 / 29) * 100, 2)
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

    public function show(Request $request, $id)
    {
        

        $data = InfiltrationSecretOperation::with([
            'satker',
            'case',
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
        $data = InfiltrationSecretOperation::find($id);

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
        InfiltrationTargetDynamics::where('infiltration_operasi_rahasia_id', $id)->delete();
        InfiltrationResultAchievement::where('infiltration_operasi_rahasia_id', $id)->delete();

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil dihapus',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

}
