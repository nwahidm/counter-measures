<?php

namespace App\Http\Controllers\API\close\intrusion;

use Carbon\Carbon;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Intrusion\IntrusionResult;
use App\Models\Intrusion\IntrusionTargetEnv;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;

class IntrusionResultController extends Controller
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

        $data = IntrusionResult::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('intrusion_hasil_yang_dicapai.satker_id', '=', $idSatker);
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
            'intrusion_target_environment_id' => 'required|string|max:255',
            'hasil_yang_dicapai' => 'required|string',
            // 'upload_result' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = new IntrusionResult;
        $data->satker_id = $user->satker->id_satker;
        $data->case_id = $request->case_id;
        $data->intrusion_target_location_id = $request->intrusion_target_location_id;
        $data->intrusion_target_environment_id = $request->intrusion_target_environment_id;

        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        // if ($request->hasFile('upload_result')) {
        //     $ext_upload_result = $request->file('upload_result')->extension();
        //     $upload_result = $request->file('upload_result')
        //         ->storePubliclyAs(
        //             'close/intrusion/result/upload_result',
        //             Str::slug('intrusion result', '_') . '_' . Str::random() . '.' . $ext_upload_result,
        //             'public'
        //         );

        //     $data->upload_hasil_yang_dicapai = $upload_result;
        // }

        if ($request->upload_result) {
            $base64Document = $request->upload_result;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('intrusion result', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/intrusion/result/upload_result/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->upload_hasil_yang_dicapai = $uploadPath;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_hasil_yang_dicapai' => "1",
                'intrusion_laporan' => "1",
                'status' => $close_case_progress->percentage > 86 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 86 ? $close_case_progress->substatus : 'Input Hasil Penyurupan',
                'percentage' => $close_case_progress->percentage > 86 ? $close_case_progress->percentage : 86
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_hasil_yang_dicapai' => "1",
                'intrusion_laporan' => "1",
                'status' => $close_case_progress->percentage > 86 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 86 ? $close_case_progress->substatus : 'Input Hasil Penyurupan',
                'percentage' => 100
            ]);
        }


        if ($data->save()) {
            // save doc analysis
            if($data->upload_hasil_yang_dicapai){
                DataHelper::insertDocument($data->id, $data->upload_hasil_yang_dicapai);
            }
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Hasil Penyurupan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            // update progress
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_hasil_yang_dicapai' => "1",
                'intrusion_laporan' => "1",
                'status' => $close_case_progress->percentage > 86 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 86 ? $close_case_progress->substatus : 'Input Hasil Penyurupan',
                'percentage' => $close_case_progress->percentage > 86 ? $close_case_progress->percentage : 86
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
        $data = IntrusionResult::where('intrusion_hasil_yang_dicapai.id', $id)
                                ->with(['satker', 'case', 'location', 'environment'])
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
            'intrusion_target_environment_id' => 'required|string|max:255',
            'hasil_yang_dicapai' => 'required|string',
            // 'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = IntrusionResult::find($id);
        $data->case_id = $request->case_id;
        $data->intrusion_target_location_id = $request->intrusion_target_location_id;
        $data->intrusion_target_environment_id = $request->intrusion_target_environment_id;

        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        // if ($request->hasFile('upload_hasil_yang_dicapai')) {
        //     $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
        //     $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
        //         ->storePubliclyAs(
        //             'close/intrusion/result/upload_result',
        //             Str::slug('intrusion result', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
        //             'public'
        //         );

        //     if($data->upload_hasil_yang_dicapai){
        //         if (Storage::disk('public')->exists($data->upload_hasil_yang_dicapai)) {
        //             Storage::disk('public')->delete($data->upload_hasil_yang_dicapai);
        //         }
        //     }

        //     // save doc analysis
        //     DataHelper::insertDocument($data->id, $upload_hasil_yang_dicapai, $data->upload_hasil_yang_dicapai);
        //     $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
        // } 

        if ($request->upload_hasil_yang_dicapai) {
            $base64Document = $request->upload_hasil_yang_dicapai;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('intrusion result', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/intrusion/result/upload_result/' . $fileName;

            if($data->upload_hasil_yang_dicapai){
                if (Storage::disk('public')->exists($data->upload_hasil_yang_dicapai)) {
                    Storage::disk('public')->delete($data->upload_hasil_yang_dicapai);
                }
            }

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->upload_hasil_yang_dicapai = $uploadPath;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_hasil_yang_dicapai' => "1",
                'intrusion_laporan' => "1",
                'status' => $close_case_progress->percentage > 86 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 86 ? $close_case_progress->substatus : 'Input Hasil Penyurupan',
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
        $data = IntrusionResult::find($id);

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

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil dihapus',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }
}
