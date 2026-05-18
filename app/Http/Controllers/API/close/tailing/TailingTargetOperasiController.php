<?php

namespace App\Http\Controllers\API\close\tailing;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterSatker;
use Illuminate\Support\Str;
use App\Models\VideoDocuments;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tailing\TailingTargetOperasi;
use App\Models\VideoAudioDocuments;

class TailingTargetOperasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = TailingTargetOperasi::when(!$user->hasRole(['superadmin',]), function ($q) use ($idSatker) {
            $q->where('tailing_target_operasi.satker_id', '=', $idSatker);
        })
            ->with('case')
            ->with('satker')
            ->with('tailingPemahamanPerilaku')
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

    public function show(Request $request, $id)
    {
        $data = TailingTargetOperasi::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        $data->load(['case', 'satker', 'TailingPemahamanPerilaku']);
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
        $data = TailingTargetOperasi::find($id);

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

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil dihapus',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'tailing_pemahaman_perilaku_id' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'rencana_target_operasi' => 'required|string|max:1000000',
            'target_operasi' => 'required|string|max:1000000',
            // 'skenario_target_operasi' => 'required|string|max:1000000',
            // 'target_operasi_video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);


        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();

        $data = new TailingTargetOperasi;
        $data->kode_satker = $satker->kode_satker;
        $data->case_id = $request->id_case;
        $data->tailing_pemahaman_perilaku_id = $request->tailing_pemahaman_perilaku_id;
        $data->rencana_target_operasi = $request->rencana_target_operasi;
        $data->target_operasi = $request->target_operasi;
        $data->skenario_target_operasi = $request->skenario_target_operasi;
        $data->created_by = $user->id;

        // $document_video = new VideoDocuments;
        // if ($request->hasFile('target_operasi_video_upload')) {
        //     $ext_target_operasi_video_upload = $request->file('target_operasi_video_upload')->extension();
        //     $target_operasi_video_upload = $request->file('target_operasi_video_upload')
        //         ->storePubliclyAs(
        //             'close/tailing/target_operasi',
        //             Str::slug('video', '_') . '_' . Str::random() . '.' . $ext_target_operasi_video_upload,
        //             'public'
        //         );

        //     $data->target_operasi_video_upload = $target_operasi_video_upload;

        //     $document_video->doc_path = $target_operasi_video_upload;
        //     $document_video->doc_type = "video";
        //     $document_video->doc_status = "0";
        //     $document_video->doc_status_remark = "Waiting Analysis";
        // }

        $document_video = new VideoDocuments;
        $video_audio_data = new VideoAudioDocuments;
        if ($request->input('target_operasi_video_upload')) {
            $base64_video = $request->input('target_operasi_video_upload');

            // Extract file extension from Base64 string if it's part of a data URI (e.g., data:video/mp4;base64,...)
            // if (preg_match('/^data:video\/(\w+);base64,/', $base64_video, $type)) {
            //     $ext_target_operasi_video_upload = strtolower($type[1]); // Get the file extension (e.g., mp4)
            //     $base64_video = substr($base64_video, strpos($base64_video, ',') + 1); // Remove the mime type from the Base64 string
            // } else {
            //     // Handle error: invalid Base64 string format
            //     return response()->json(['error' => 'Invalid video format'], 400);
            // }

            // Decode the Base64 string
            $video_data = base64_decode($base64_video);

            // Generate a unique filename
            // $filename = Str::slug('video', '_') . '_' . Str::random() . '.' . $ext_target_operasi_video_upload;
            $filename = Str::slug('video', '_') . '_' . Str::random() . '.mp4';

            // Store the decoded video file
            $file_path = 'close/tailing/target_operasi/' . $filename;
            Storage::disk('public')->put($file_path, $video_data);

            // Save the file path to the `target_operasi_video_upload` field in the database
            $data->target_operasi_video_upload = $file_path;

            // Save to the `VideoDocuments` model
            $document_video->doc_path = $file_path;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";

            $video_audio_data->doc_path = $file_path;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->created_by = $user->id;

        }


        if ($request->submit_type === 'save') {


            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('tailing_target_operasi', '0')
                ->update([
                    'tailing_target_operasi' => "1",
                    'status' => "Pembuntutan",
                    'substatus' => "Penambahan Target Operasi",
                    'percentage' => round((15 / 29) * 100, 2)
                ]);
            ;

        } else {
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('tailing_target_operasi', '0')
                ->update([
                    'tailing_target_operasi' => "1",
                    'status' => "Pembuntutan",
                    'substatus' => "Penambahan Target Operasi",
                    'percentage' => round((29 / 29) * 100, 2)
                ]);
            ;

        }

        if ($data->save()) {

            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update->action = "Penambahan Target Operasi";
            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            if ($request->input('target_operasi_video_upload')) {
                $document_video->relation_id = $data->id;
                $document_video->save();

                $video_audio_data->relation_id = $data->id;
                $video_audio_data->save();
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

    public function update(Request $request)
    {
        $this->validate($request, [
            'tailing_pemahaman_perilaku_id' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'rencana_target_operasi' => 'required|string|max:1000000',
            'target_operasi' => 'required|string|max:1000000',
            // 'skenario_target_operasi' => 'required|string|max:1000000',
            // 'target_operasi_video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000'
        ]);

        $user = Auth::guard('api')->user();

        $data = TailingTargetOperasi::find($request->id);
        $data->rencana_target_operasi = $request->rencana_target_operasi;
        $data->target_operasi = $request->target_operasi;
        $data->skenario_target_operasi = $request->skenario_target_operasi;
        $data->created_by = $user->id;

        // if ($request->hasFile('target_operasi_video_upload')) {
        //     $ext_target_operasi_video_upload = $request->file('target_operasi_video_upload')->extension();
        //     $target_operasi_video_upload = $request->file('target_operasi_video_upload')
        //         ->storePubliclyAs(
        //             'close/tailing/target_operasi',
        //             Str::slug('video', '_') . '_' . Str::random() . '.' . $ext_target_operasi_video_upload,
        //             'public'
        //         );

        //     $data->target_operasi_video_upload = $target_operasi_video_upload;


        //     $document_video = new VideoDocuments;
        //     $document_video->relation_id = $request->id;
        //     $document_video->doc_path = $data->target_operasi_video_upload ;
        //     $document_video->doc_type = "video";
        //     $document_video->doc_status = "0";
        //     $document_video->doc_status_remark = "Waiting Analysis";
        //     $document_video->updated_by = $user->id;
        //     $document_video->save();

        //     $video_audio_data = new VideoAudioDocuments;
        //     $video_audio_data->relation_id = $request->id;
        //     $video_audio_data->doc_path = $data->target_operasi_video_upload ;
        //     $video_audio_data->doc_type = "video_audio";
        //     $video_audio_data->doc_status = "0";
        //     $video_audio_data->doc_status_remark = "Waiting Analysis";
        //     $video_audio_data->updated_by = $user->id;
        //     $video_audio_data->save();
        // }

        if ($request->input('target_operasi_video_upload')) {
            $base64_video = $request->input('target_operasi_video_upload');

            // Extract the file extension from Base64 string, assuming it's in a data URI format (e.g., data:video/mp4;base64,...)
            // if (preg_match('/^data:video\/(\w+);base64,/', $base64_video, $type)) {
            //     $ext_target_operasi_video_upload = strtolower($type[1]); // Get the file extension (e.g., mp4)
            //     $base64_video = substr($base64_video, strpos($base64_video, ',') + 1); // Remove the mime type from the Base64 string
            // } else {
            //     // Handle error: invalid Base64 string format
            //     return response()->json(['error' => 'Invalid video format'], 400);
            // }

            // Decode the Base64 string
            $video_data = base64_decode($base64_video);

            // Generate a unique filename
            // $filename = Str::slug('video', '_') . '_' . Str::random() . '.' . $ext_target_operasi_video_upload;
            $filename = Str::slug('video', '_') . '_' . Str::random() . '.mp4';

            // Store the decoded video file
            $file_path = 'close/tailing/target_operasi/' . $filename;
            Storage::disk('public')->put($file_path, $video_data);

            // Save the file path to the `target_operasi_video_upload` field in the database
            $data->target_operasi_video_upload = $file_path;

            // Save the video information in the `VideoDocuments` model
            $document_video = new VideoDocuments;
            $document_video->relation_id = $request->id;
            $document_video->doc_path = $data->target_operasi_video_upload;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->updated_by = $user->id;
            $document_video->save();

            // Save the video information in the `VideoAudioDocuments` model
            $video_audio_data = new VideoAudioDocuments;
            $video_audio_data->relation_id = $request->id;
            $video_audio_data->doc_path = $data->target_operasi_video_upload;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->updated_by = $user->id;
            $video_audio_data->save();
        }


        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('tailing_target_operasi', '0')
                ->update([
                    'tailing_target_operasi' => "1",
                    'status' => "Pembuntutan",
                    'substatus' => "Penambahan Target Operasi",
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
