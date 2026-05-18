<?php

namespace App\Http\Controllers\API\close\intrusion;

use Carbon\Carbon;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VideoDocuments;
use App\Models\CaseCloseProgresses;
use App\Models\VideoAudioDocuments;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Intrusion\IntrusionResult;
use App\Models\Intrusion\IntrusionTargetEnv;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;

class IntrusionTargetEnvController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = IntrusionTargetEnv::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('intrusion_lingkungan_target.satker_id', '=', $idSatker);
                                })
                                ->with(['satker', 'case', 'location'])
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
            'case_id' => 'required|string|max:128',
            'intrusion_target_location_id' => 'required|string|max:255',
            'nama_lingkungan' => 'required|string|max:128',
            'tipe_lingkungan' => 'required|string|max:255',
            'deskripsi_lingkungan' => 'required|string',
            // 'informasi_terkumpul' => 'required|string',
            // 'aktivitas_teramati' => 'required|string',
            // 'upload_lingkungan' => 'nullable|file|mimes:pdf|max:2048'
        ]);


        $user = Auth::guard('api')->user();

        $data = new IntrusionTargetEnv;
        $data->satker_id = $user->satker->id_satker;
        $data->case_id = $request->case_id;
        $data->intrusion_target_location_id = $request->intrusion_target_location_id;

        $data->nama_lingkungan = $request->nama_lingkungan;
        $data->tipe_lingkungan = $request->tipe_lingkungan;
        $data->deskripsi_lingkungan = $request->deskripsi_lingkungan;
        $data->informasi_terkumpul = $request->informasi_terkumpul;
        $data->aktivitas_teramati = $request->aktivitas_teramati;

        // if ($request->hasFile('upload_lingkungan')) {
        //     $ext_upload_lingkungan = $request->file('upload_lingkungan')->extension();
        //     $upload_lingkungan = $request->file('upload_lingkungan')
        //         ->storePubliclyAs(
        //             'close/intrusion/target-env/upload_lingkungan',
        //             Str::slug('intrusion target-env', '_') . '_' . Str::random() . '.' . $ext_upload_lingkungan,
        //             'public'
        //         );

        //     $data->target_environment_upload = $upload_lingkungan;
        // }

        if ($request->upload_lingkungan) {
            $base64Document = $request->upload_lingkungan;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('intrusion target-env', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/intrusion/target-env/upload_lingkungan/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->target_environment_upload = $uploadPath;
        }

        // if ($request->hasFile('video_upload')) {
        //     $ext_video_upload = $request->file('video_upload')->extension();
        //     $video_upload = $request->file('video_upload')
        //         ->storePubliclyAs(
        //             'close/intrusion/target-env/video_upload',
        //             Str::slug('intrusion target environment', '_') . '_' . Str::random() . '.' . $ext_video_upload,
        //             'public'
        //         );

        //     $data->video_upload = $video_upload;
        // }

        if ($request->video_upload) {
            $base64Document = $request->video_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('intrusion target-env', '_') . '_' . Str::random() . '.mp4';
            $uploadPath = 'close/intrusion/target-env/video_upload/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->video_upload = $uploadPath;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lingkungan_target' => "1",
                'status' => $close_case_progress->percentage > 81.5 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 81.5 ? $close_case_progress->substatus : 'Input Lingkungan Target Penyurupan',
                'percentage' => $close_case_progress->percentage > 81.5 ? $close_case_progress->percentage : 81.5
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lingkungan_target' => "1",
                'status' => $close_case_progress->percentage > 81.5 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 81.5 ? $close_case_progress->substatus : 'Input Lingkungan Target Penyurupan',
                'percentage' => 100
            ]);

        }

        if ($data->save()) {
            // save doc analysis
            if($data->target_environment_upload){
                DataHelper::insertDocument($data->id, $data->target_environment_upload);
            }
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Lingkungan Target Penyurupan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            // update progress
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lingkungan_target' => "1",
                'status' => $close_case_progress->percentage > 81.5 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 81.5 ? $close_case_progress->substatus : 'Input Lingkungan Target Penyurupan',
                'percentage' => $close_case_progress->percentage > 81.5 ? $close_case_progress->percentage : 81.5
            ]);
            
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
        $data = IntrusionTargetEnv::where('intrusion_lingkungan_target.id', $id)
                                ->with(['satker', 'case', 'location'])
                                ->first();

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


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'case_id' => 'required|string|max:128',
            'intrusion_target_location_id' => 'required|string|max:255',
            'nama_lingkungan' => 'required|string|max:128',
            'tipe_lingkungan' => 'required|string|max:255',
            'deskripsi_lingkungan' => 'required|string',
            // 'informasi_terkumpul' => 'required|string',
            // 'aktivitas_teramati' => 'required|string',
            // 'target_environment_upload' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = IntrusionTargetEnv::find($id);
        $data->case_id = $request->case_id;
        $data->intrusion_target_location_id = $request->intrusion_target_location_id;

        $data->nama_lingkungan = $request->nama_lingkungan;
        $data->tipe_lingkungan = $request->tipe_lingkungan;
        $data->deskripsi_lingkungan = $request->deskripsi_lingkungan;
        $data->informasi_terkumpul = $request->informasi_terkumpul;
        $data->aktivitas_teramati = $request->aktivitas_teramati;

        // if ($request->hasFile('target_environment_upload')) {
        //     $ext_target_environment_upload = $request->file('target_environment_upload')->extension();
        //     $target_environment_upload = $request->file('target_environment_upload')
        //         ->storePubliclyAs(
        //             'close/intrusion/environment/upload_lingkungan',
        //             Str::slug('intrusion environment upload', '_') . '_' . Str::random() . '.' . $ext_target_environment_upload,
        //             'public'
        //         );

        //     if($data->target_environment_upload){
        //         if (Storage::disk('public')->exists($data->target_environment_upload)) {
        //             Storage::disk('public')->delete($data->target_environment_upload);
        //         }
        //     }

        //     // save doc analysis
        //     DataHelper::insertDocument($data->id, $target_environment_upload, $data->target_environment_upload);
        //     $data->target_environment_upload = $target_environment_upload;
        // } 

        if ($request->target_environment_upload) {
            $base64Document = $request->target_environment_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('intrusion target-env', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/intrusion/target-env/upload_lokasi/' . $fileName;

            if($data->target_environment_upload){
                if (Storage::disk('public')->exists($data->target_environment_upload)) {
                    Storage::disk('public')->delete($data->target_environment_upload);
                }
            }

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->target_environment_upload = $uploadPath;
        }

        if ($request->video_upload) {
            $base64Document = $request->video_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('intrusion target-env', '_') . '_' . Str::random() . '.mp4';
            $uploadPath = 'close/intrusion/target-env/video_upload/' . $fileName;

            if($data->video_upload){
                if (Storage::disk('public')->exists($data->video_upload)) {
                    Storage::disk('public')->delete($data->video_upload);
                }
            }

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->video_upload = $uploadPath;

            $video_data = new VideoDocuments;
            $video_data->relation_id = $id;
            $video_data->doc_path = $uploadPath;
            $video_data->doc_type = "video";
            $video_data->doc_status = "0";
            $video_data->doc_status_remark = "Waiting Analysis";
            $video_data->updated_by = $user->id;
            $video_data->save();

            $video_audio_data = new VideoAudioDocuments;
            $video_audio_data->relation_id = $id;
            $video_audio_data->doc_path = $uploadPath;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->updated_by = $user->id;
            $video_audio_data->save();
        } 

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lingkungan_target' => "1",
                'status' => $close_case_progress->percentage > 81.5 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 81.5 ? $close_case_progress->substatus : 'Input Lingkungan Target Penyurupan',
                'percentage' => 100
            ]);
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

    public function destroy($id, Request $request)
    {
        $data = IntrusionTargetEnv::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if($data->target_environment_upload){
            if (Storage::disk('public')->exists($data->target_environment_upload)) {
                Storage::disk('public')->delete($data->target_environment_upload);
            }
        }

        $data->delete();
        IntrusionResult::where('intrusion_target_environment_id', $id)->delete();

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil dihapus',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }
}
