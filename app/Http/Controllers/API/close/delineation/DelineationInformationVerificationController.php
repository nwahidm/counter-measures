<?php

namespace App\Http\Controllers\API\close\delineation;

use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\DelineationDataHelper;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\Models\Delineation\DelineationInformationVerification;

class DelineationInformationVerificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = DelineationInformationVerification::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('delineation_information_verification.satker_id', '=', $idSatker);
                                })
                                ->with([
                                    'case',                             
                                    'case.satker',                      
                                    'observation_information_collection' 
                                ])
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
            // 'id_case' => 'required|string|max:128',
            // 'id_satker' => 'required|string|max:128',
            // 'id_information_collection' => 'required|string|max:128',
            // 'metode_verifikasi' => 'required|string|max:128',
            // 'kredibilitas_sumber' => 'required|string|max:128',
            // 'verification_date' => 'required|date',
            // 'verified_by' => 'required|string|max:128',
            // 'detail_informasi_verifikasi' => 'required|string|max:1000000',
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();

        $data = new DelineationInformationVerification;
        $data->satker_id = $satker->id_satker;

        $data->case_id = $request->id_case;
        $data->information_collection_id = $request->id_information_collection;
        $data->kredibilitas_sumber = $request->kredibilitas_sumber;
        $data->metode_verifikasi = $request->metode_verifikasi;
        $data->detail_informasi_verifikasi = $request->detail_informasi_verifikasi;
        $data->verified_by = $request->verified_by;
        $data->verification_date = $request->verification_date;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        

        $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update->case_id = $data->id;
        $data_case_close_historical_update->action = "Penambahan Informasi Verifikasi";

        $data_case_close_historical_update->created_by = $user->id;
        $data_case_close_historical_update->updated_by = $user->id;
        
        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
            ->where('delineation_informasi_verifikasi', '0')
            ->update([
                'delineation_informasi_verifikasi' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Informasi Verifikasi",
                'percentage' => round((6/29)*100,2)
            ]);;
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
            ->where('delineation_informasi_verifikasi', '0')
            ->update([
                'delineation_informasi_verifikasi' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Informasi Verifikasi",
                'percentage' => round((29/29)*100,2)
            ]);;

        }

        if ($data->save()) {
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update->save();

          

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
        $data = DelineationInformationVerification::find($id)?->with([
            'case',                             
            'case.satker',                      
            'observation_information_collection' 
        ])->first();

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Load the related models
        $data->load(['case.satker', 'case', 'observation_information_collection']);

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
            // 'id_case' => 'required|string|max:128',
            // 'id_satker' => 'required|string|max:128',
            // 'id_information_collection' => 'required|string|max:128',
            // 'metode_verifikasi' => 'required|string|max:128',
            // 'kredibilitas_sumber' => 'required|string|max:128',
            // 'verification_date' => 'required|date',
            // 'verified_by' => 'required|string|max:128',
            // 'detail_informasi_verifikasi' => 'required|string|max:1000000',
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();

        $data = DelineationInformationVerification::find($id);
        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        $data->satker_id = $satker->id_satker;

        $data->case_id = $request->id_case;
        $data->information_collection_id = $request->id_information_collection;
        $data->kredibilitas_sumber = $request->kredibilitas_sumber;
        $data->metode_verifikasi = $request->metode_verifikasi;
        $data->detail_informasi_verifikasi = $request->detail_informasi_verifikasi;
        $data->verified_by = $request->verified_by;
        $data->verification_date = $request->verification_date;

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
            ->where('delineation_informasi_verifikasi', '0')
            ->update([
                'delineation_informasi_verifikasi' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Informasi Verifikasi",
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

    public function destroy($id, Request $request)
    {
        $data = DelineationInformationVerification::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

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
}
