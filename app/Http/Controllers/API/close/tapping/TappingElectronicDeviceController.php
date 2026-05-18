<?php

namespace App\Http\Controllers\API\close\tapping;

use App\Models\Documents;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VideoDocuments;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\Models\Tapping\TappingElectronicDevice;
use App\Models\Tapping\TappingIntelligentSignal;
use App\Models\Tapping\TappingResultAchievement;

class TappingElectronicDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = TappingElectronicDevice::with(['case.satker', 'case'])
                                        ->when($user->hasRole(['superadmin']), function ($q) use ($idSatker) {
                                            $q->whereRelation('case', 'satker_id', '=', $idSatker);
                                        })
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'case_id' => 'required|string|max:128',
            'tanggal_penyadapan' => 'required|date',
            'sumber_data' => 'required|string|max:128',
            'metode_penyadapan' => 'required|string|max:1280000',
            'deskripsi_hasil' => 'required|string|max:1280000',
            // 'dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            // 'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = Auth::guard('api')->user();

        $data = new TappingElectronicDevice;
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->tanggal_penyadapan = $request->tanggal_penyadapan;
        $data->sumber_data = $request->sumber_data;
        $data->metode_penyadapan = $request->metode_penyadapan;
        $data->deskripsi_hasil = $request->deskripsi_hasil;

        // if ($request->hasFile('dokumen_upload')) {
        //     $ext_dokumen_upload = $request->file('dokumen_upload')->extension();
        //     $dokumen_upload = $request->file('dokumen_upload')
        //         ->storePubliclyAs(
        //             'close/tapping/electronic_device/dokumen_upload',
        //             Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.' . $ext_dokumen_upload,
        //             'public'
        //         );

        //     $data->dokumen_upload = $dokumen_upload;
        // }

        if ($request->dokumen_upload) {
            $base64Document = $request->dokumen_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/tapping/electronic_device/dokumen_upload/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->dokumen_upload = $uploadPath;
        }

        // if ($request->hasFile('video_upload')) {
        //     $ext_video_upload = $request->file('video_upload')->extension();
        //     $video_upload = $request->file('video_upload')
        //         ->storePubliclyAs(
        //             'close/tapping/electronic_device/video_upload',
        //             Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.' . $ext_video_upload,
        //             'public'
        //         );

        //     $data->video_upload = $video_upload;
        // }

        if ($request->video_upload) {
            $base64Document = $request->video_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.mp4';
            $uploadPath = 'close/tapping/electronic_device/video_upload/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->video_upload = $uploadPath;
        }

        $data->created_by = $request->user_id;
        $data->updated_by = $request->user_id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'tapping_data_penyelidikan_komunikasi_elektronik' => "1",
                'status' => $close_case_progress->percentage > 90.5 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 90.5 ? $close_case_progress->substatus : 'Input Penyadapan Perangkat Elektronik',
                'percentage' => $close_case_progress->percentage > 90.5 ? $close_case_progress->percentage : 90.5,
                'updated_by' => $user->id
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'tapping_data_penyelidikan_komunikasi_elektronik' => "1",
                'status' => $close_case_progress->percentage > 90.5 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 90.5 ? $close_case_progress->substatus : 'Input Penyadapan Perangkat Elektronik',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);
        }
        
        if ($data->save()) {
            $op = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $op->tapping_data_penyelidikan_komunikasi_elektronik = 1;
            $op->status = $op->percentage > 90.5 ? $op->status : "Penyadapan";
            $op->substatus = $op->percentage > 90.5 ? $op->substatus : "Input Penyadapan Perangakat Elektronik";
            $op->percentage = $op->percentage > 90.5 ? $op->percentage : 90.5;
            $op->updated_by = $request->user_id;
            $op->save();

            $cp = new CaseCloseEventHistoricalUpdates;
            $cp->case_id = $data->case_id;
            $cp->action = "Penambahan Penyadapan Perangkat Elektronik";
            $cp->created_by = $request->user_id;
            $cp->save();

            if ($request->dokumen_upload) {
                DataHelper::insertDocument($data->id_tapping_electronic_device, $data->dokumen_upload, null, $request->user_id);
            }

            if ($request->video_upload) {
                DataHelper::insertVideo($data->id_tapping_electronic_device, $data->video_upload, null, $request->user_id);
            }

            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Data berhasil disimpan',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if ($data->dokumen_upload && Storage::disk('public')->exists($data->dokumen_upload)) {
            Storage::disk('public')->delete($data->dokumen_upload);
        }

        if ($data->video_upload && Storage::disk('public')->exists($data->video_upload)) {
            Storage::disk('public')->delete($data->video_upload);
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
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $data = TappingElectronicDevice::with(['case.satker', 'case'])
                                        ->where('id_tapping_electronic_device', $id)
                                        ->first();

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'case_id' => 'required|string|max:128',
            'tanggal_penyadapan' => 'required|date',
            'sumber_data' => 'required|string|max:128',
            'metode_penyadapan' => 'required|string|max:1280000',
            'deskripsi_hasil' => 'required|string|max:1280000',
            // 'dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            // 'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = Auth::guard('api')->user();
        $data = TappingElectronicDevice::find($id);
        $data->case_id = $request->case_id;
        $data->tanggal_penyadapan = $request->tanggal_penyadapan;
        $data->sumber_data = $request->sumber_data;
        $data->metode_penyadapan = $request->metode_penyadapan;
        $data->deskripsi_hasil = $request->deskripsi_hasil;

        // if ($request->hasFile('dokumen_upload')) {
        //     $ext_dokumen_upload = $request->file('dokumen_upload')->extension();
        //     $dokumen_upload = $request->file('dokumen_upload')
        //         ->storePubliclyAs(
        //             'close/tapping/electronic_device/dokumen_upload',
        //             Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.' . $ext_dokumen_upload,
        //             'public'
        //         );

        //     if ($request->old_dokumen_upload && Storage::disk('public')->exists($request->old_dokumen_upload)) {
        //         Storage::disk('public')->delete($request->old_dokumen_upload);
        //     }

        //     $data->dokumen_upload = $dokumen_upload;
        // } else {
        //     $dokumen_upload = $request->old_dokumen_upload;

        //     $data->dokumen_upload = $dokumen_upload;
        // }

        if ($request->dokumen_upload) {
            $base64Document = $request->dokumen_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/tapping/electronic_device/dokumen_upload/' . $fileName;

            if ($data->dokumen_upload && Storage::disk('public')->exists($data->dokumen_upload)) {
                Storage::disk('public')->delete($data->dokumen_upload);
            }

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->dokumen_upload = $uploadPath;
        }

        // if ($request->hasFile('video_upload')) {
        //     $ext_video_upload = $request->file('video_upload')->extension();
        //     $video_upload = $request->file('video_upload')
        //         ->storePubliclyAs(
        //             'close/tapping/electronic_device/video_upload',
        //             Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.' . $ext_video_upload,
        //             'public'
        //         );

        //     if ($request->old_video_upload && Storage::disk('public')->exists($request->old_video_upload)) {
        //         Storage::disk('public')->delete($request->old_video_upload);
        //     }

        //     $data->video_upload = $video_upload;
        // } else {
        //     $video_upload = $request->old_video_upload;

        //     $data->video_upload = $video_upload;
        // }

        if ($request->video_upload) {
            $base64Document = $request->video_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.mp4';
            $uploadPath = 'close/tapping/electronic_device/video_upload/' . $fileName;

            if ($data->video_upload && Storage::disk('public')->exists($data->video_upload)) {
                Storage::disk('public')->delete($data->video_upload);
            }

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->video_upload = $uploadPath;
        }

        $data->updated_by = $request->user_id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'tapping_data_penyelidikan_komunikasi_elektronik' => "1",
                'status' => $close_case_progress->percentage > 90.5 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 90.5 ? $close_case_progress->substatus : 'Input Penyadapan Perangkat Elektronik',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);
        }

        if ($data->update()) {
            if ($request->dokumen_upload) {
                DataHelper::insertDocument($data->id_tapping_electronic_device, $data->dokumen_upload, $request->old_dokumen_upload, $request->user_id);
            }

            if ($request->video_upload) {
                DataHelper::insertVideo($data->id_tapping_electronic_device, $data->video_upload, $request->old_video_upload, $request->user_id);
            }

            $cp = CaseCloseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
            $cp->action = "Perubahan Penyadapan Perangkat Elektronik";
            $cp->updated_by = $request->user_id;
            $cp->update();

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = TappingElectronicDevice::find($id);

        if ($data) {
            if ($data->dokumen_upload && Storage::disk('public')->exists($data->dokumen_upload)) {
                Storage::disk('public')->delete($data->dokumen_upload);

                Documents::where('relation_id', $data->id_tapping_electronic_device)
                    ->where('doc_path', $data->dokumen_upload)
                    ->delete();

                $data->dokumen_upload = null;
                $data->update();
            }

            if ($data->video_upload && Storage::disk('public')->exists($data->video_upload)) {
                Storage::disk('public')->delete($data->video_upload);

                VideoDocuments::where('relation_id', $data->id_tapping_electronic_device)
                    ->where('doc_path', $data->video_upload)
                    ->delete();

                $data->video_upload = null;
                $data->update();
            }

            $cp = CaseCloseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
            $cp->action = "Penghapusan Penyadapan Perangkat Elektronik";
            $cp->updated_by = $data->created_by;
            $cp->update();

            $data->delete();
            TappingIntelligentSignal::where('tapping_electronic_device_data_id', $id)->delete();
            TappingResultAchievement::where('tapping_electronic_device_data_id', $id)->delete();

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
