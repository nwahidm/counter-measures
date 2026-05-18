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
use App\Models\Tailing\TailingPemahamanPerilaku;
use App\Models\VideoAudioDocuments;
use App\Models\VideoAudioDocumentAnalytics;

class TailingPemahamanPerilakuController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = TailingPemahamanPerilaku::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('tailing_pemahaman_perilaku.satker_id', '=', $idSatker);
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

    public function show(Request $request, $id)
    {
        $data = TailingPemahamanPerilaku::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        $data->load(['case', 'satker']);

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
        $data = TailingPemahamanPerilaku::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        $data->delete();

        if($data->surat_perintah_path){
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
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'target_name' => 'required|string|max:128',
            // 'target_gender' => 'required|string|max:128',
            // 'target_religion' => 'required|string|max:128',
            // 'target_identity_number' => 'required|string|max:128',
            // 'target_identity_number_type' => 'required|string|max:128',
            // 'target_occupation' => 'required|string|max:128',
            // 'target_education' => 'required|string|max:128',
            'perilaku_tercatat' => 'required|string|max:1000000',
            // 'aktivitas_rutin' => 'required|string|max:1000000',
            // 'hubungan_sosial' => 'required|string|max:1000000',
            // 'prediksi_perilaku' => 'required|string|max:1000000',
            'target_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            // 'pemahaman_perilaku_video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();

        $data = new TailingPemahamanPerilaku;
        $data->kode_satker = $satker->kode_satker;
        $data->case_id = $request->id_case;
        $data->target_name = $request->target_name;
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_identity_number = $request->target_identity_number;
        $data->target_identity_number_type = $request->target_identity_number_type;
        $data->target_occupation = $request->target_occupation;
        $data->target_education = $request->target_education;
        $data->perilaku_tercatat = $request->perilaku_tercatat;
        $data->aktivitas_rutin = $request->aktivitas_rutin;
        $data->hubungan_sosial = $request->hubungan_sosial;
        $data->prediksi_perilaku = $request->prediksi_perilaku;

        if ($request->hasFile('target_photo')) {
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'close/tailing/pemahaman_perilaku',
                    Str::slug('foto', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            $data->target_photo = $target_photo;
        }


        $document_video = new VideoDocuments;
        $video_audio_data = new VideoAudioDocuments;
        // if ($request->hasFile('pemahaman_perilaku_video_upload')) {
        //     $ext_pemahaman_perilaku_video_upload = $request->file('pemahaman_perilaku_video_upload')->extension();
        //     $pemahaman_perilaku_video_upload = $request->file('pemahaman_perilaku_video_upload')
        //         ->storePubliclyAs(
        //             'close/tailing/pemahaman_perilaku',
        //             Str::slug('video', '_') . '_' . Str::random() . '.' . $ext_pemahaman_perilaku_video_upload,
        //             'public'
        //         );

        //     $data->pemahaman_perilaku_video_upload = $pemahaman_perilaku_video_upload;

        //     $document_video->doc_path = $pemahaman_perilaku_video_upload;
        //     $document_video->doc_type = "video";
        //     $document_video->doc_status = "0";
        //     $document_video->doc_status_remark = "Waiting Analysis";

        //     $video_audio_data->doc_path = $pemahaman_perilaku_video_upload;
        //     $video_audio_data->doc_type = "video_audio";
        //     $video_audio_data->doc_status = "0";
        //     $video_audio_data->doc_status_remark = "Waiting Analysis";
        //     $video_audio_data->created_by =  $user->id;
        // }

        if ($request->pemahaman_perilaku_video_upload) {
            // Mendekode base64 pemahaman_perilaku_video_upload
            $base64_video_upload = $request->pemahaman_perilaku_video_upload;
        
            // Pisahkan metadata base64 jika ada
            // if (strpos($base64_video_upload, ',') !== false) {
            //     list(, $base64_video_upload) = explode(',', $base64_video_upload);
            // }
        
            // Lakukan decoding base64
            $decoded_video = base64_decode($base64_video_upload);
        
            // Tentukan ekstensi file dari base64
            // $finfo = finfo_open(FILEINFO_MIME_TYPE);
            // $mimeType = finfo_buffer($finfo, $decoded_video);
            // $ext_video_upload = explode('/', $mimeType)[1];
        
            // Buat nama file dan simpan ke storage
            $pemahaman_perilaku_video_upload_path = 'close/tailing/pemahaman_perilaku/' .
                // Str::slug('video', '_') . '_' . Str::random() . '.' . $ext_video_upload;
                Str::slug('video', '_') . '_' . Str::random() . '.mp4';
        
            Storage::disk('public')->put($pemahaman_perilaku_video_upload_path, $decoded_video);
        
            // Menyimpan path file ke data
            $data->pemahaman_perilaku_video_upload = $pemahaman_perilaku_video_upload_path;
        
            // Mengisi data untuk tabel Documents
            $document_video->doc_path = $pemahaman_perilaku_video_upload_path;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";
        
            // Mengisi data untuk tabel VideoAudioDocuments
            $video_audio_data->doc_path = $pemahaman_perilaku_video_upload_path;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->created_by =  $user->id;
        }

        
        $data->created_by = $user->id;

        $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            
        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_pemahaman_perilaku', '0')
            ->update([
                'tailing_pemahaman_perilaku' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Pemahaman Perilaku",
                'percentage' => round((14/29)*100,2)
            ]);;
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_pemahaman_perilaku', '0')
            ->update([
                'tailing_pemahaman_perilaku' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Pemahaman Perilaku",
                'percentage' => round((29/29)*100,2)
            ]);;
        }

        if ($data->save()) {

            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update->action = "Penambahan Pemahaman Perilaku";
            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();
            
            if ($request->input('pemahaman_perilaku_video_upload')) {
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
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'target_name' => 'required|string|max:128',
            // 'target_gender' => 'required|string|max:128',
            // 'target_religion' => 'required|string|max:128',
            // 'target_identity_number' => 'required|string|max:128',
            // 'target_identity_number_type' => 'required|string|max:128',
            // 'target_occupation' => 'required|string|max:128',
            // 'target_education' => 'required|string|max:128',
            'perilaku_tercatat' => 'required|string|max:1000000',
            // 'aktivitas_rutin' => 'required|string|max:1000000',
            // 'hubungan_sosial' => 'required|string|max:1000000',
            // 'prediksi_perilaku' => 'required|string|max:1000000',
            // 'target_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'pemahaman_perilaku_video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = Auth::guard('api')->user();

        $data = TailingPemahamanPerilaku::find($request->id);
        $data->target_name = $request->target_name;
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_identity_number = $request->target_identity_number;
        $data->target_identity_number_type = $request->target_identity_number_type;
        $data->target_occupation = $request->target_occupation;
        $data->target_education = $request->target_education;
        $data->perilaku_tercatat = $request->perilaku_tercatat;
        $data->aktivitas_rutin = $request->aktivitas_rutin;
        $data->hubungan_sosial = $request->hubungan_sosial;
        $data->prediksi_perilaku = $request->prediksi_perilaku;

        if ($request->hasFile('target_photo')) {
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'close/tailing/pemahaman_perilaku',
                    Str::slug('foto', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            $data->target_photo = $target_photo;
        }

        

        if ($request->pemahaman_perilaku_video_upload) {
            $base64_video = $request->input('pemahaman_perilaku_video_upload');
            
            // Extract file extension from Base64 string (optional: depends on the structure of your Base64 string)
            // Assuming the Base64 string includes the file extension as part of a data URI (e.g., data:video/mp4;base64,...)
            // if (preg_match('/^data:video\/(\w+);base64,/', $base64_video, $type)) {
            //     $ext_pemahaman_perilaku_video_upload = strtolower($type[1]); // Get the file extension
            //     $base64_video = substr($base64_video, strpos($base64_video, ',') + 1); // Remove the mime type from the Base64 string
            // } else {
            //     // Handle error: invalid Base64 string format
            //     return response()->json(['error' => 'Invalid video format'], 400);
            // }
        
            // Decode the Base64 string
            $video_data = base64_decode($base64_video);
            
            // Generate a unique filename
            // $filename = Str::slug('video', '_') . '_' . Str::random() . '.' . $ext_pemahaman_perilaku_video_upload;
            $filename = Str::slug('video', '_') . '_' . Str::random() . '.mp4';
            
            // Store the decoded video file
            $file_path = 'close/tailing/pemahaman_perilaku/' . $filename;
            Storage::disk('public')->put($file_path, $video_data);
            
            // Save to database
            $data->pemahaman_perilaku_video_upload = $file_path;
            
            $document_video = new VideoDocuments;
            $document_video->relation_id = $request->id;
            $document_video->doc_path = $file_path;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->updated_by = $user->id;
            $document_video->save();
        
            $video_audio_data = new VideoAudioDocuments;
            $video_audio_data->relation_id = $request->id;
            $video_audio_data->doc_path = $file_path;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->updated_by = $user->id;
            $video_audio_data->save();
        }
        

        $data->updated_by = $user->id;
        
        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_pemahaman_perilaku', '0')
            ->update([
                'tailing_pemahaman_perilaku' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Pemahaman Perilaku",
                'percentage' => round((29/29)*100,2)
            ]);;
        
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
