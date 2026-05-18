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
use App\Models\Tapping\TappingResultAchievement;

class TappingResultAchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = TappingResultAchievement::with(['case', 'case.satker'])
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
            'tapping_intelligent_signal_data_id' => 'required|string|max:128',
            'hasil_yang_dicapai' => 'required|string|max:1280000',
            // 'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $user = Auth::guard('api')->user();
        $data = new TappingResultAchievement;
        $data->case_id = $request->case_id;
        $data->case_id = $request->case_id;
        $data->tapping_electronic_device_data_id = $request->tapping_electronic_device_data_id;
        $data->tapping_intelligent_signal_data_id = $request->tapping_intelligent_signal_data_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        // if ($request->hasFile('upload_hasil_yang_dicapai')) {
        //     $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
        //     $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
        //         ->storePubliclyAs(
        //             'close/tapping/result_achievement/upload_hasil_yang_dicapai',
        //             Str::slug('tapping result achievement', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
        //             'public'
        //         );

        //     $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
        // }

        if ($request->upload_hasil_yang_dicapai) {
            $base64Document = $request->upload_hasil_yang_dicapai;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('tapping result achievement', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/tapping/result_achievement/upload_hasil_yang_dicapai/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->upload_hasil_yang_dicapai = $uploadPath;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->tappingIntelligentSignal->tappingElectronicDevice->case->id)->first();
            $close_case_progress->update([
                'tapping_hasil_penyadapan' => "1",
                'status' => $close_case_progress->percentage > 100 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 100 ? $close_case_progress->substatus : 'Input Hasil Penyadapan',
                'percentage' => $close_case_progress->percentage > 100 ? $close_case_progress->percentage : 100,
                'updated_by' => $user->id
            ]);

        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->tappingIntelligentSignal->tappingElectronicDevice->case->id)->first();
            $close_case_progress->update([
                'tapping_hasil_penyadapan' => "1",
                'status' => $close_case_progress->percentage > 100 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 100 ? $close_case_progress->substatus : 'Input Hasil Penyadapan',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);

        }

        if ($data->save()) {
            $op = CaseCloseProgresses::where('case_id', $data->tappingIntelligentSignal->tappingElectronicDevice->case_id)->first();
            $op->tapping_hasil_penyadapan = 1;
            $op->status = $op->percentage > 100 ? $op->status : "Penyadapan";
            $op->substatus = $op->percentage > 100 ? $op->substatus : "Input Hasil Penyadapan";
            $op->percentage = $op->percentage > 100 ? $op->percentage : 100;
            $op->updated_by = $user->id;
            $op->save();

            $cp = new CaseCloseEventHistoricalUpdates;
            $cp->case_id = $data->tappingIntelligentSignal->tappingElectronicDevice->case_id;
            $cp->action = "Penambahan Hasil Penyadapan";
            $cp->created_by = $user->id;
            $cp->save();

           
            $cp = new CaseCloseEventHistoricalUpdates;
            $cp->case_id = $data->tappingIntelligentSignal->tappingElectronicDevice->case_id;
            $cp->action = 'Penambahan Penyadapan Laporan';
            $cp->created_by = $user->id;
            $cp->save();

            if ($request->upload_hasil_yang_dicapai) {
                DataHelper::insertDocument($data->id_tapping_result_achievement, $data->upload_hasil_yang_dicapai, null, $user->id);
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = TappingResultAchievement::with(['case', 'case.satker'])
                                        ->where('id_tapping_result_achievement', $id)
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
            'tapping_intelligent_signal_data_id' => 'required|string|max:128',
            'hasil_yang_dicapai' => 'required|string|max:1280000',
            // 'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $user = Auth::guard('api')->user();
        $data = TappingResultAchievement::find($id);
        
        $data->case_id = $request->case_id;
        $data->tapping_electronic_device_data_id = $request->tapping_electronic_device_data_id;
        $data->tapping_intelligent_signal_data_id = $request->tapping_intelligent_signal_data_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        // if ($request->hasFile('upload_hasil_yang_dicapai')) {
        //     $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
        //     $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
        //         ->storePubliclyAs(
        //             'close/tapping/result_achievement/upload_hasil_yang_dicapai',
        //             Str::slug('tapping result achievement', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
        //             'public'
        //         );

        //     if ($request->old_upload_hasil_yang_dicapai && Storage::disk('public')->exists($request->old_upload_hasil_yang_dicapai)) {
        //         Storage::disk('public')->delete($request->old_upload_hasil_yang_dicapai);
        //     }

        //     $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
        // } else {
        //     $upload_hasil_yang_dicapai = $request->old_upload_hasil_yang_dicapai;

        //     $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
        // }

        if ($request->upload_hasil_yang_dicapai) {
            $base64Document = $request->upload_hasil_yang_dicapai;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('tapping result achievement', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/tapping/result_achievement/upload_hasil_yang_dicapai/' . $fileName;

            if ($request->upload_hasil_yang_dicapai && Storage::disk('public')->exists($request->upload_hasil_yang_dicapai)) {
                Storage::disk('public')->delete($request->upload_hasil_yang_dicapai);
            }

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->upload_hasil_yang_dicapai = $uploadPath;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->tappingIntelligentSignal->tappingElectronicDevice->case->id)->first();
            $close_case_progress->update([
                'tapping_hasil_penyadapan' => "1",
                'status' => $close_case_progress->percentage > 100 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 100 ? $close_case_progress->substatus : 'Input Hasil Penyadapan',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);
        }

        if ($data->update()) {
            if ($request->upload_hasil_yang_dicapai) {
                DataHelper::insertDocument($data->id_tapping_result_achievement, $data->upload_hasil_yang_dicapai, $request->old_upload_hasil_yang_dicapai, $user->id);
            }

            $cp = CaseCloseEventHistoricalUpdates::where('case_id', $data->tappingIntelligentSignal->tappingElectronicDevice->case_id)->first();
            $cp->action = "Perubahan Hasil Penyadapan";
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
        $data = TappingResultAchievement::find($id);

        if ($data) {
            if ($data->upload_hasil_yang_dicapai && Storage::disk('public')->exists($data->upload_hasil_yang_dicapai)) {
                Storage::disk('public')->delete($data->upload_hasil_yang_dicapai);

                Documents::where('relation_id', $data->id_tapping_result_achievement)
                    ->where('doc_path', $data->upload_hasil_yang_dicapai)
                    ->delete();

                $data->upload_hasil_yang_dicapai = null;
                $data->update();
            }

            $cp = CaseCloseEventHistoricalUpdates::where('case_id', $data->tappingIntelligentSignal->tappingElectronicDevice->case_id)->first();
            $cp->action = "Penghapusan Hasil Penyadapan";
            $cp->updated_by = $data->created_by;
            $cp->update();

            TappingResultAchievement::where('tapping_intelligent_signal_data_id', $data->id)->delete();
            $data->delete();

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
