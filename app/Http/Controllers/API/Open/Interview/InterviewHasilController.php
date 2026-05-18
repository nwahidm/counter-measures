<?php

namespace App\Http\Controllers\API\Open\Interview;

use Illuminate\Support\Facades\Auth;
use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\Documents;
use App\Models\Interview\InterviewHasil;
use App\Models\Interview\InterviewSaranTL;
use App\Models\VideoDocuments;
use App\Models\VideoAudioDocuments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class InterviewHasilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $caseId = $request->get('interview_scheduler_id');

        if ($caseId) {
            return response()->json(InterviewHasil::where('interview_scheduler_id', $caseId)->paginate(10));
        }
        
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get data berhasil',
            "data" => InterviewHasil::paginate(10),
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_OK);


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;
        //
        $request->validate([
            // 'interview_scheduler_id' => 'required|string|max:128',
            //'hasil_interview' => 'required|date',
            //'video_interview' => 'required|string|max:128',
            // 'upload_dokumen_wawancara' => 'required|file|mimes:pdf|max:2048',
            // 'upload_video_wawancara' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $data = new InterviewHasil;
        $data->interview_scheduler_id = $request->interview_scheduler_id;
        $data->upload_dokumen_wawancara = $request->upload_dokumen_wawancara;
        $data->upload_video_wawancara = $request->upload_video_wawancara;


        if ($request->upload_dokumen_wawancara) {
            $base64Document = $request->upload_dokumen_wawancara;

            // Extract base64 data
            // list($fileType, $base64Data) = explode(';', $base64Document);
            // list(, $extension) = explode('/', $fileType);
            // list(, $base64Data) = explode(',', $base64Data);

            $decodedDocument = base64_decode($base64Document);
            // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $extension;
            $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'open/interview/hasil/upload_dokumen_wawancara/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->upload_dokumen_wawancara = $uploadPath;
        }

        if ($request->upload_video_wawancara) {
            $base64Video = $request->upload_video_wawancara;

            // Extract base64 data
            // list($fileType, $base64Data) = explode(';', $base64Video);
            // list(, $extension) = explode('/', $fileType);
            // list(, $base64Data) = explode(',', $base64Data);

            $decodedVideo = base64_decode($base64Video);
            // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $extension;
            $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.mp4';
            $uploadPath = 'open/interview/hasil/upload_video_wawancara/' . $fileName;

            // Store the video
            Storage::disk('public')->put($uploadPath, $decodedVideo);
            $data->upload_video_wawancara = $uploadPath;
        }

        $data->created_by = $request->user_id;
        $data->updated_by = $request->user_id;

        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $request->id_case)->first();
                $op->wawancara_hasil = 1;
                $op->status = $op->percentage > 52.92 ? $op->status : "Wawancara";
                $op->substatus = $op->percentage > 52.92 ? $op->substatus : "Input Hasil Wawancara";
                $op->percentage = $op->percentage > 52.92 ? $op->percentage : 52.92;
                $op->updated_by = $user->id;
                $op->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Wawancara Hasil';
                $cp->created_by = $user->id;
                $cp->save();

                if ($request->upload_dokumen_wawancara) {
                    // $base64Document = $request->upload_dokumen_wawancara;

                    // $decodedDocument = base64_decode($base64Document);
                    // // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $extension;
                    // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.pdf';
                    // $uploadPath = 'open/interview/hasil/upload_dokumen_wawancara/' . $fileName;

                    // // Store the document
                    // Storage::disk('public')->put($uploadPath, $decodedDocument);
                    // $data->upload_dokumen_wawancara = $uploadPath;

                    DataHelper::insertDocument($data->id_interview_result, $data->upload_dokumen_wawancara);
                }

                if ($request->upload_video_wawancara) {
                    // $base64Video = $request->upload_video_wawancara;
                    // // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $extension;
                    // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.mp4';
                    // $uploadPath = 'open/interview/hasil/upload_video_wawancara/' . $fileName;

                    // $decodedVideo = base64_decode($base64Video);
                    // // Store the video
                    // Storage::disk('public')->put($uploadPath, $decodedVideo);
                    // $data->upload_video_wawancara = $uploadPath;
                    DataHelper::insertVideo($data->id_interview_result, $data->upload_video_wawancara);
                    $video_audio_data = new VideoAudioDocuments;
                    $video_audio_data->relation_id = $data->id_interview_result;
                    $video_audio_data->doc_path = $data->upload_video_wawancara;
                    $video_audio_data->doc_type = "video_audio";
                    $video_audio_data->doc_status = "0";
                    $video_audio_data->doc_status_remark = "Waiting Analysis";
                    $video_audio_data->created_by = $user->id;
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
                "message" => 'Data gagal disimpan',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            if ($data->save()) {

                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->wawancara_hasil = 1;
                $updateCaseProgresses->status = 'Wawancara';
                $updateCaseProgresses->substatus = 'Penambahan Hasil Wawancara';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->interviewJadwal->case_id;
                $cp->action = 'Penambahan Wawancara Hasil';
                $cp->created_by = $user->id;
                $cp->save();

                if ($request->upload_dokumen_wawancara) {
                    // $base64Document = $request->upload_dokumen_wawancara;

                    // $decodedDocument = base64_decode($base64Document);
                    // // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $extension;
                    // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.pdf';
                    // $uploadPath = 'open/interview/hasil/upload_dokumen_wawancara/' . $fileName;

                    // // Store the document
                    // Storage::disk('public')->put($uploadPath, $decodedDocument);
                    // $data->upload_dokumen_wawancara = $uploadPath;

                    DataHelper::insertDocument($data->id_interview_result, $data->upload_dokumen_wawancara);
                }

                if ($request->upload_video_wawancara) {
                    // $base64Video = $request->upload_video_wawancara;
                    // // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $extension;
                    // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.mp4';
                    // $uploadPath = 'open/interview/hasil/upload_video_wawancara/' . $fileName;

                    // $decodedVideo = base64_decode($base64Video);
                    // // Store the video
                    // Storage::disk('public')->put($uploadPath, $decodedVideo);
                    // $data->upload_video_wawancara = $uploadPath;

                    DataHelper::insertVideo($data->id_interview_result, $data->upload_video_wawancara);

                    $video_audio_data = new VideoAudioDocuments;
                    $video_audio_data->relation_id = $data->id_interview_result;
                    $video_audio_data->doc_path = $data->upload_video_wawancara;
                    $video_audio_data->doc_type = "video_audio";
                    $video_audio_data->doc_status = "0";
                    $video_audio_data->doc_status_remark = "Waiting Analysis";
                    $video_audio_data->created_by = $user->id;
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
                "message" => 'Data gagal disimpan',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        // if ($data->save()) {
        //     $op = CaseProgresses::where('case_id', $data->interviewJadwal->case_id)->first();
        //     $op->wawancara_hasil = 1;
        //     $op->status = $op->percentage > 52.92 ? $op->status : "Wawancara";
        //     $op->substatus = $op->percentage > 52.92 ? $op->substatus : "Input Hasil Wawancara";
        //     $op->percentage = $op->percentage > 52.92 ? $op->percentage : 52.92;
        //     $op->updated_by = $request->user_id;
        //     $op->save();

        //     $cp = new CaseEventHistoricalUpdates;
        //     $cp->case_id = $data->interviewJadwal->case_id;
        //     $cp->action = 'Penambahan Wawancara Hasil';
        //     $cp->created_by = $request->user_id;
        //     $cp->save();

        //     if ($request->hasFile('upload_dokumen_wawancara')) {
        //         DataHelper::insertDocument($data->id_interview_result, $data->upload_dokumen_wawancara, null, $request->user_id);
        //     }

        //     if ($request->hasFile('upload_video_wawancara')) {
        //         DataHelper::insertVideo($data->id_interview_result, $data->upload_video_wawancara, null, $request->user_id);
        //     }

        //     return response()->json([
        //         "status" => Response::HTTP_OK,
        //         "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
        //         "message" => 'Data berhasil disimpan',
        //         "data" => $data,
        //         'timestamp' => floor(microtime(true) * 1000)
        //     ]);
        // }

        // if ($data->upload_dokumen_wawancara && Storage::disk('public')->exists($data->upload_dokumen_wawancara)) {
        //     Storage::disk('public')->delete($data->upload_dokumen_wawancara);
        // }

        // if ($data->upload_video_wawancara && Storage::disk('public')->exists($data->upload_video_wawancara)) {
        //     Storage::disk('public')->delete($data->upload_video_wawancara);
        // }

        // return response()->json([
        //     "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
        //     "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
        //     "message" => 'Data gagal disimpan',
        //     "data" => $data,
        //     'timestamp' => floor(microtime(true) * 1000)
        // ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = InterviewHasil::with([
            'interviewJadwal',
            'interviewJadwal.case'
        ])->first();

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data tidak ditemukan.',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil disimpan',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;
        //
        $request->validate([
            'interview_scheduler_id' => 'required|string|max:128',
            //'hasil_interview' => 'required|date',
            //'video_interview' => 'required|string|max:128',
            'upload_dokumen_wawancara' => 'nullable|file|mimes:pdf|max:2048',
            'upload_video_wawancara' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $data = InterviewHasil::find($id);
        $data->interview_scheduler_id = $request->interview_scheduler_id;
        $data->upload_dokumen_wawancara = $request->upload_dokumen_wawancara;
        $data->upload_video_wawancara = $request->upload_video_wawancara;

        if ($request->upload_dokumen_wawancara) {
            $base64Document = $request->upload_dokumen_wawancara;

            $decodedDocument = base64_decode($base64Document);
            // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $extension;
            $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'open/interview/hasil/upload_dokumen_wawancara/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->upload_dokumen_wawancara = $uploadPath;


        } else {
            $upload_dokumen_wawancara = $request->old_upload_dokumen_wawancara;

            $data->upload_dokumen_wawancara = $upload_dokumen_wawancara;
        }

        if ($request->upload_video_wawancara) {
            $base64Video = $request->upload_video_wawancara;
            // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $extension;
            $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.mp4';
            $uploadPath = 'open/interview/hasil/upload_video_wawancara/' . $fileName;

            $decodedVideo = base64_decode($base64Video);
            // Store the video
            Storage::disk('public')->put($uploadPath, $decodedVideo);
            $data->upload_video_wawancara = $uploadPath;


        } else {
            $upload_video_wawancara = $request->old_upload_video_wawancara;

            $data->upload_video_wawancara = $upload_video_wawancara;
        }

        $data->updated_by = $request->user_id;

        if ($request->submit_type === 'update_and_finish') {
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->wawancara_hasil = 1;
            $updateCaseProgresses->status = 'Wawancara';
            $updateCaseProgresses->substatus = 'Penambahan Hasil Wawancara';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }

        if ($data->update()) {
            $cp = CaseEventHistoricalUpdates::where('case_id', $data->interviewJadwal->case_id)->first();
            $cp->action = 'Perubahan Wawancara Hasil';
            $cp->updated_by = $request->user_id;
            $cp->update();

            if ($request->upload_dokumen_wawancara) {
                DataHelper::insertDocument($data->id_interview_result, $data->upload_dokumen_wawancara, $request->old_upload_dokumen_wawancara, $request->user_id);
            }

            if ($request->upload_video_wawancara) {
                DataHelper::insertVideo($data->id_interview_result, $data->upload_video_wawancara, $request->old_upload_video_wawancara, $request->user_id);
                $video_audio_data = new VideoAudioDocuments;
                $video_audio_data->relation_id = $data->id_interview_result;
                $video_audio_data->doc_path = $upload_video_wawancara;
                $video_audio_data->doc_type = "video_audio";
                $video_audio_data->doc_status = "0";
                $video_audio_data->doc_status_remark = "Waiting Analysis";
                $video_audio_data->updated_by = $user->id;
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

        if ($data->upload_dokumen_wawancara && Storage::disk('public')->exists($data->upload_dokumen_wawancara)) {
            Storage::disk('public')->delete($data->upload_dokumen_wawancara);
        }

        if ($data->upload_video_wawancara && Storage::disk('public')->exists($data->upload_video_wawancara)) {
            Storage::disk('public')->delete($data->upload_video_wawancara);
        }

        return response()->json([
            "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            "message" => 'Data gagal disimpan',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = InterviewHasil::find($id);

        if ($data) {
            if ($data->upload_dokumen_wawancara && Storage::disk('public')->exists($data->upload_dokumen_wawancara)) {
                Storage::disk('public')->delete($data->upload_dokumen_wawancara);

                Documents::where('relation_id', $data->id_interview_result)
                    ->where('doc_path', $data->upload_dokumen_wawancara)
                    ->delete();

                $data->upload_dokumen_wawancara = null;
                $data->update();
            }

            if ($data->upload_video_wawancara && Storage::disk('public')->exists($data->upload_video_wawancara)) {
                Storage::disk('public')->delete($data->upload_video_wawancara);

                VideoDocuments::where('relation_id', $data->id_interview_result)
                    ->where('doc_path', $data->upload_video_wawancara)
                    ->delete();

                $data->upload_video_wawancara = null;
                $data->update();
            }

            $cp = CaseEventHistoricalUpdates::where('case_id', $data->interviewJadwal->case_id)->first();
            $cp->action = 'Penghapusan Wawancara Hasil';
            $cp->updated_by = $data->created_by;
            $cp->update();

            $data->delete();
            InterviewSaranTL::where('interview_result_id', $id)->delete();

            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Data berhasil dihapus',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        return response()->json([
            "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            "message" => 'Data gagal dihapus',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
