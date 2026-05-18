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
use App\Models\Intrusion\IntrusionTargetLoc;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;

class IntrusionTargetLocController extends Controller
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

        $data = IntrusionTargetLoc::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('intrusion_target_lokasi.satker_id', '=', $idSatker);
                                })
                                ->with(['satker', 'case'])
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
            'target_name' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:128',
            // 'target_identity_number' => 'required|string|max:255',
            // 'target_gender' => 'required|string|max:100',
            // 'target_religion' => 'required|string|max:100',
            // 'target_education' => 'required|string|max:255',
            // 'target_occupation' => 'required|string|max:255',
            'lokasi_target' => 'required|string',
            'deskripsi_lokasi' => 'required|string',
            // 'upload_lokasi' => 'nullable|file|mimes:pdf|max:2048',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        $user = Auth::guard('api')->user();

        $data = new IntrusionTargetLoc;
        $data->satker_id = $user->satker?->id_satker;
        $data->case_id = $request->case_id;

        $data->target_name = $request->target_name;
        $data->target_identity_number_type = $request->target_identity_number_type;
        $data->target_identity_number = $request->target_identity_number;
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_education = $request->target_education;
        $data->target_occupation = $request->target_occupation;
        $data->lokasi_target = $request->lokasi_target;
        $data->deskripsi_lokasi = $request->deskripsi_lokasi;

        // if ($request->hasFile('upload_lokasi')) {
        //     $ext_upload_lokasi = $request->file('upload_lokasi')->extension();
        //     $upload_lokasi = $request->file('upload_lokasi')
        //         ->storePubliclyAs(
        //             'close/intrusion/target-loc/upload_lokasi',
        //             Str::slug('intrusion target-loc', '_') . '_' . Str::random() . '.' . $ext_upload_lokasi,
        //             'public'
        //         );

        //     $data->lokasi_target_upload = $upload_lokasi;
        // }

        if ($request->upload_lokasi) {
            $base64Document = $request->upload_lokasi;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('intrusion target-loc', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/intrusion/target-loc/upload_lokasi/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->lokasi_target_upload = $uploadPath;
        }

        // if ($request->hasFile('video_upload')) {
        //     $ext_video_upload = $request->file('video_upload')->extension();
        //     $video_upload = $request->file('video_upload')
        //         ->storePubliclyAs(
        //             'close/intrusion/target-loc/video_upload',
        //             Str::slug('intrusion target-loc', '_') . '_' . Str::random() . '.' . $ext_video_upload,
        //             'public'
        //         );

        //     $data->video_upload = $video_upload;
        // }

        if ($request->video_upload) {
            $base64Document = $request->video_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('intrusion target-loc', '_') . '_' . Str::random() . '.mp4';
            $uploadPath = 'close/intrusion/target-loc/video_upload/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->video_upload = $uploadPath;
        }

        $filenames = [];
        $index = 1;
        if($request->file('image') != null){
            foreach ($request->file('image') as $image) {
                $filename = $image->storePubliclyAs(
                    'close/intrusion/target-loc/target-photo',
                    time(). ' - '. Str::random(). " - " . $request->target_name.' - '. $index . '.'. $image->getClientOriginalExtension(),
                    'public'
                );
                $filenames[] = $filename;
                $index++;
            }    
        }
        $data->target_photo = json_encode($filenames);

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lokasi_target' => "1",
                'status' => $close_case_progress->percentage > 77 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 77 ? $close_case_progress->substatus : 'Input Lokasi Target Penyurupan',
                'percentage' => $close_case_progress->percentage > 77 ? $close_case_progress->percentage : 77
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lokasi_target' => "1",
                'status' => $close_case_progress->percentage > 77 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 77 ? $close_case_progress->substatus : 'Input Lokasi Target Penyurupan',
                'percentage' => 100
            ]);

        }

        if ($data->save()) {
            // save doc analysis
            if($data->lokasi_target_upload){
                DataHelper::insertDocument($data->id, $data->lokasi_target_upload);
            }
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Lokasi Target Penyurupan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            // update progress
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lokasi_target' => "1",
                'status' => $close_case_progress->percentage > 77 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 77 ? $close_case_progress->substatus : 'Input Lokasi Target Penyurupan',
                'percentage' => $close_case_progress->percentage > 77 ? $close_case_progress->percentage : 77
            ]);

            if ($request->video_upload) {
                $video_data = new VideoDocuments;
                $video_data->relation_id = $data->id;
                $video_data->doc_path = $data->video_upload;
                $video_data->doc_type = "video";
                $video_data->doc_status = "0";
                $video_data->doc_status_remark = "Waiting Analysis";
                $video_data->updated_by = $user->id;
                $video_data->save();

                $video_audio_data = new VideoAudioDocuments;
                $video_audio_data->relation_id = $data->id;
                $video_audio_data->doc_path = $data->video_upload;
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
            "message" => 'Data Gagal Disimpan',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function show(Request $request, $id)
    {
        $data = IntrusionTargetLoc::where('intrusion_target_lokasi.id', $id)
                                ->with(['satker', 'case'])
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
            'target_name' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:128',
            // 'target_identity_number' => 'required|string|max:255',
            // 'target_gender' => 'required|string|max:100',
            // 'target_religion' => 'required|string|max:100',
            // 'target_education' => 'required|string|max:255',
            // 'target_occupation' => 'required|string|max:255',
            'lokasi_target' => 'required|string',
            'deskripsi_lokasi' => 'required|string',
            // 'lokasi_target_upload' => 'nullable|file|mimes:pdf|max:2048',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('api')->user();

        $data = IntrusionTargetLoc::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        $data->case_id = $request->case_id;
        $data->target_name = $request->target_name;
        $data->target_identity_number_type = $request->target_identity_number_type;
        $data->target_identity_number = $request->target_identity_number;
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_education = $request->target_education;
        $data->target_occupation = $request->target_occupation;
        $data->lokasi_target = $request->lokasi_target;
        $data->deskripsi_lokasi = $request->deskripsi_lokasi;

        // if ($request->hasFile('lokasi_target_upload')) {
        //     $ext_lokasi_target_upload = $request->file('lokasi_target_upload')->extension();
        //     $lokasi_target_upload = $request->file('lokasi_target_upload')
        //         ->storePubliclyAs(
        //             'close/intrusion/target-loc/upload_lokasi',
        //             Str::slug('intrusion target-loc lokasi', '_') . '_' . Str::random() . '.' . $ext_lokasi_target_upload,
        //             'public'
        //         );

        //     if($data->lokasi_target_upload){
        //         if (Storage::disk('public')->exists($data->lokasi_target_upload)) {
        //             Storage::disk('public')->delete($data->lokasi_target_upload);
        //         }
        //     }

        //     // save doc analysis
        //     DataHelper::insertDocument($data->id, $lokasi_target_upload, $data->lokasi_target_upload);
        //     $data->lokasi_target_upload = $lokasi_target_upload;
        // } else {
        //     $lokasi_target_upload = $data->lokasi_target_upload;

        //     $data->lokasi_target_upload = $lokasi_target_upload;
        // }

        if ($request->lokasi_target_upload) {
            $base64Document = $request->lokasi_target_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('intrusion target-loc', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/intrusion/target-loc/upload_lokasi/' . $fileName;

            if($data->lokasi_target_upload){
                if (Storage::disk('public')->exists($data->lokasi_target_upload)) {
                    Storage::disk('public')->delete($data->lokasi_target_upload);
                }
            }

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->lokasi_target_upload = $uploadPath;
        }

        // photo
        $newImages = [];
        if ($request->file('image') != null) {
            // Remove existing images
            if ($data->target_photo) {
                $existingImagePaths = json_decode($data->target_photo);
    
                foreach ($existingImagePaths as $existingImagePath) {
                    if (Storage::disk('public')->exists($existingImagePath)) {
                        Storage::disk('public')->delete($existingImagePath);
                    }
                }
            }
            // Save new images
            $index = 1;
            foreach ($request->file('image') as $image) {
                $filename = $image->storePubliclyAs(
                    'close/intrusion/target-loc/target-photo',
                    time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension(),
                    'public'
                );
                $newImages[] = $filename;
                $index++;
            }    
        } else{
            $newImages = json_decode($data->target_photo);
        }
        $data->target_photo = json_encode($newImages);

        if ($request->video_upload) {
            // $ext_video_upload = $request->file('video_upload')->extension();
            // $video_upload = $request->file('video_upload')
            //     ->storePubliclyAs(
            //         'close/intrusion/target_loc/video_upload',
            //         Str::slug('intrusion target loc', '_') . '_' . Str::random() . '.' . $ext_video_upload,
            //         'public'
            //     );
            $base64Document = $request->video_upload;
            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('intrusion target-loc', '_') . '_' . Str::random() . '.mp4';
            $uploadPath = 'close/intrusion/target-loc/video_upload/' . $fileName;

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
                'intrusion_lokasi_target' => "1",
                'status' => $close_case_progress->percentage > 77 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 77 ? $close_case_progress->substatus : 'Input Lokasi Target Penyurupan',
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
        $data = IntrusionTargetLoc::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if($data->lokasi_target_upload){
            if (Storage::disk('public')->exists($data->lokasi_target_upload)) {
                Storage::disk('public')->delete($data->lokasi_target_upload);
            }
        }

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);

            foreach ($imagePaths as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        $data->delete();
        IntrusionTargetEnv::where('intrusion_target_location_id', $id)->delete();
        IntrusionResult::where('intrusion_target_location_id', $id)->delete();

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil dihapus',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }
}
