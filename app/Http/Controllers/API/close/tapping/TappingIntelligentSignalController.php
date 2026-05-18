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
use App\Models\Tapping\TappingIntelligentSignal;
use App\Models\Tapping\TappingResultAchievement;

class TappingIntelligentSignalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = TappingIntelligentSignal::with(['case.satker', 'case'])
                                        ->when(!$user->hasRole(['superadmin']), function ($q) use ($idSatker) {
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
            'tapping_electronic_device_data_id' => 'required|string|max:128',
            'tanggal_penyadapan' => 'required|date',
            'jenis_sinyal' => 'required|string|max:128',
            'deskripsi_hasil' => 'required|string|max:1280000',
            // 'dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            // 'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = Auth::guard('api')->user();

        $data = new TappingIntelligentSignal;
        $data->satker_id = $request->satker_id;
        $data->tapping_electronic_device_data_id = $request->tapping_electronic_device_data_id;
        $data->tanggal_penyadapan = $request->tanggal_penyadapan;
        $data->jenis_sinyal = $request->jenis_sinyal;
        $data->deskripsi_hasil = $request->deskripsi_hasil;

        // if ($request->hasFile('dokumen_upload')) {
        //     $ext_dokumen_upload = $request->file('dokumen_upload')->extension();
        //     $dokumen_upload = $request->file('dokumen_upload')
        //         ->storePubliclyAs(
        //             'close/tapping/intelligent_signal/dokumen_upload',
        //             Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.' . $ext_dokumen_upload,
        //             'public'
        //         );

        //     $data->dokumen_upload = $dokumen_upload;
        // }

        if ($request->dokumen_upload) {
            $base64Document = $request->dokumen_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/tapping/intelligent_signal/dokumen_upload/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->dokumen_upload = $uploadPath;
        }

        // if ($request->hasFile('video_upload')) {
        //     $ext_video_upload = $request->file('video_upload')->extension();
        //     $video_upload = $request->file('video_upload')
        //         ->storePubliclyAs(
        //             'close/tapping/intelligent_signal/video_upload',
        //             Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.' . $ext_video_upload,
        //             'public'
        //         );

        //     $data->video_upload = $video_upload;
        // }

        if ($request->video_upload) {
            $base64Document = $request->video_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.mp4';
            $uploadPath = 'close/tapping/intelligent_signal/video_upload/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->video_upload = $uploadPath;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->tappingElectronicDevice->case->id)->first();
            $close_case_progress->update([
                'tapping_data_sinyal_intelijen' => "1",
                'status' => $close_case_progress->percentage > 95 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 95 ? $close_case_progress->substatus : 'Input Penyadapan Data Sinyal Intelijen',
                'percentage' => $close_case_progress->percentage > 95 ? $close_case_progress->percentage : 95,
                'updated_by' => $user->id
            ]);

        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->tappingElectronicDevice->case->id)->first();
            $close_case_progress->update([
                'tapping_data_sinyal_intelijen' => "1",
                'status' => $close_case_progress->percentage > 95 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 95 ? $close_case_progress->substatus : 'Input Penyadapan Data Sinyal Intelijen',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);

        }


        if ($data->save()) {
            $op = CaseCloseProgresses::where('case_id', $data->tappingElectronicDevice->case_id)->first();
            $op->tapping_data_sinyal_intelijen = 1;
            $op->status = $op->percentage > 95 ? $op->status : "Penyadapan";
            $op->substatus = $op->percentage > 95 ? $op->substatus : "Input Penyadapan Data Sinyal Intelijen";
            $op->percentage = $op->percentage > 95 ? $op->percentage : 95;
            $op->updated_by = $user->id;
            $op->save();

            $cp = new CaseCloseEventHistoricalUpdates;
            $cp->case_id = $data->tappingElectronicDevice->case_id;
            $cp->action = "Penambahan Penyadapan Data Sinyal Intelijen";
            $cp->created_by = $user->id;
            $cp->save();

            if ($request->dokumen_upload) {
                DataHelper::insertDocument($data->id_tapping_intelligent_signal, $data->dokumen_upload, null, $user->id);
            }

            if ($request->video_upload) {
                DataHelper::insertVideo($data->id_tapping_intelligent_signal, $data->video_upload, null, $user->id);
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
        //
        $data = TappingIntelligentSignal::where('id_tapping_intelligent_signal', $id)
            ->with([
                'tappingElectronicDevice',
                'tappingElectronicDevice.case',
                'tappingElectronicDevice.case.satker'
            ])
            ->first();

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data tidak ditemukan.',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'tapping_electronic_device_data_id' => 'required|string|max:128',
            'tanggal_penyadapan' => 'required|date',
            'jenis_sinyal' => 'required|string|max:128',
            'deskripsi_hasil' => 'required|string|max:1280000',
            // 'dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            // 'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = Auth::guard('api')->user();
        $data = TappingIntelligentSignal::find($id);
        $data->tapping_electronic_device_data_id = $request->tapping_electronic_device_data_id;
        $data->tanggal_penyadapan = $request->tanggal_penyadapan;
        $data->jenis_sinyal = $request->jenis_sinyal;
        $data->deskripsi_hasil = $request->deskripsi_hasil;

        // if ($request->hasFile('dokumen_upload')) {
        //     $ext_dokumen_upload = $request->file('dokumen_upload')->extension();
        //     $dokumen_upload = $request->file('dokumen_upload')
        //         ->storePubliclyAs(
        //             'close/tapping/intelligent_signal/dokumen_upload',
        //             Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.' . $ext_dokumen_upload,
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
            $fileName = Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/tapping/intelligent_signal/dokumen_upload/' . $fileName;

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
        //             'close/tapping/intelligent_signal/video_upload',
        //             Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.' . $ext_video_upload,
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
            $fileName = Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.mp4';
            $uploadPath = 'close/tapping/intelligent_signal/video_upload/' . $fileName;

            if ($data->video_upload && Storage::disk('public')->exists($data->video_upload)) {
                Storage::disk('public')->delete($data->video_upload);
            }

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->video_upload = $uploadPath;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->tappingElectronicDevice->case->id)->first();
            $close_case_progress->update([
                'tapping_data_sinyal_intelijen' => "1",
                'status' => $close_case_progress->percentage > 95 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 95 ? $close_case_progress->substatus : 'Input Penyadapan Data Sinyal Intelijen',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);
        }


        if ($data->update()) {
            if ($request->dokumen_upload) {
                DataHelper::insertDocument($data->id_tapping_intelligent_signal, $data->dokumen_upload, $request->old_dokumen_upload, $user->id);
            }

            if ($request->video_upload) {
                DataHelper::insertVideo($data->id_tapping_intelligent_signal, $data->video_upload, $request->old_video_upload, $user->id);
            }

            $cp = CaseCloseEventHistoricalUpdates::where('case_id', $data->tappingElectronicDevice->case_id)->first();
            $cp->action = "Perubahan Penyadapan Sinyal Intelijen";
            $cp->updated_by = $user->id;
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
        $data = TappingIntelligentSignal::find($id);

        if ($data) {
            if ($data->dokumen_upload && Storage::disk('public')->exists($data->dokumen_upload)) {
                Storage::disk('public')->delete($data->dokumen_upload);

                Documents::where('relation_id', $data->id_tapping_intelligent_signal)
                    ->where('doc_path', $data->dokumen_upload)
                    ->delete();

                $data->dokumen_upload = null;
                $data->update();
            }

            if ($data->video_upload && Storage::disk('public')->exists($data->video_upload)) {
                Storage::disk('public')->delete($data->video_upload);

                VideoDocuments::where('relation_id', $data->id_tapping_intelligent_signal)
                    ->where('doc_path', $data->video_upload)
                    ->delete();

                $data->video_upload = null;
                $data->update();
            }

            $cp = CaseCloseEventHistoricalUpdates::where('case_id', $data->tappingElectronicDevice->case_id)->first();
            $cp->action = "Penghapusan Penyadapan Sinyal Intelijen";
            $cp->updated_by = $data->created_by;
            $cp->update();

            $data->delete();
            TappingResultAchievement::where('tapping_intelligent_signal_data_id', $id)->delete();

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
