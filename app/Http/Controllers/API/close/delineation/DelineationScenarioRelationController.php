<?php

namespace App\Http\Controllers\API\close\delineation;

use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\Models\Delineation\DelineationScenarioRelation;

class DelineationScenarioRelationController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = DelineationScenarioRelation::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('delineation_scenario_relation.satker_id', '=', $idSatker);
                                })
                                ->with('information_verification.case')
                                ->with('information_verification.case.satker')
                                ->with('information_verification')
                                ->with('information_validation')
                                ->with('observation_information_collection')
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
            // 'id_information_verification' => 'required|string|max:128',
            // 'id_information_validation' => 'required|string|max:128',
            // 'tanggal_pencatatan' => 'required|date',
            // 'subjek_utama' => 'required|string|max:1000000',
            // 'subjek_terkait' => 'required|string|max:1000000',
            // 'jenis_relasi' => 'required|string|max:1000000',
            // 'kekuatan_relasi' => 'required|string|max:1000000',
            // 'dampak_potensial' => 'required|string|max:1000000',
            // 'detail_relasi' => 'required|string|max:1000000',
            // 'catatan_analisa' => 'required|string|max:1000000',
        ]);
        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();

        $data = new DelineationScenarioRelation;
        $data->satker_id = $satker->id_satker;

        $data->case_id = $request->id_case;
        $data->information_collection_id = $request->id_information_collection;
        $data->information_verification_id = $request->id_information_verification;
        $data->information_validation_id = $request->id_information_validation;

        $data->subjek_utama = $request->subjek_utama;
        $data->subjek_terkait = $request->subjek_terkait;
        $data->jenis_relasi = $request->jenis_relasi;
        $data->kekuatan_relasi = $request->kekuatan_relasi;

        $data->tanggal_pencatatan= $request->tanggal_pencatatan;
        $data->detail_relasi = $request->detail_relasi;
        $data->dampak_potensial = $request->dampak_potensial;
        $data->catatan_analisa = $request->catatan_analisa;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update->case_id = $data->id;
        $data_case_close_historical_update->action = "Penambahan Identitas Terhubung";

        $data_case_close_historical_update->created_by = $user->id;
        $data_case_close_historical_update->updated_by = $user->id;

        $data_case_close_historical_update2 = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update2->case_id = $data->id;
        $data_case_close_historical_update2->action = "Penambahan Delineation Report";

        $data_case_close_historical_update2->created_by = $user->id;
        $data_case_close_historical_update2->updated_by = $user->id;
        
        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('delineation_skenario_relasi', '0')
            ->update([
                'delineation_skenario_relasi' => "1",
                'delineation_laporan' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Skenario Relasi",
                'percentage' => round((9/29) * 100,2)
            ]);;
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('delineation_skenario_relasi', '0')
            ->update([
                'delineation_skenario_relasi' => "1",
                'delineation_laporan' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Skenario Relasi",
                'percentage' => round((29/29) * 100,2)
            ]);;

        }

        if ($data->save()) {
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update2->case_id = $request->id_case;
            $data_case_close_historical_update->save();
            $data_case_close_historical_update2->save();

           
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

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'id_case' => 'required|string|max:128',
            // 'id_satker' => 'required|string|max:128',
            // 'id_information_collection' => 'required|string|max:128',
            // 'id_information_verification' => 'required|string|max:128',
            // 'id_information_validation' => 'required|string|max:128',
            // 'tanggal_pencatatan' => 'required|date',
            // 'subjek_utama' => 'required|string|max:1000000',
            // 'subjek_terkait' => 'required|string|max:1000000',
            // 'jenis_relasi' => 'required|string|max:1000000',
            // 'kekuatan_relasi' => 'required|string|max:1000000',
            // 'dampak_potensial' => 'required|string|max:1000000',
            // 'detail_relasi' => 'required|string|max:1000000',
            // 'catatan_analisa' => 'required|string|max:1000000',
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();

        $data = DelineationScenarioRelation::find($id);
        $data->satker_id = $satker->id_satker;

        $data->case_id = $request->id_case;
        $data->information_collection_id = $request->id_information_collection;
        $data->information_verification_id = $request->id_information_verification;
        $data->information_validation_id = $request->id_information_validation;

        $data->subjek_utama = $request->subjek_utama;
        $data->subjek_terkait = $request->subjek_terkait;
        $data->jenis_relasi = $request->jenis_relasi;
        $data->kekuatan_relasi = $request->kekuatan_relasi;

        $data->tanggal_pencatatan= $request->tanggal_pencatatan;
        $data->detail_relasi = $request->detail_relasi;
        $data->dampak_potensial = $request->dampak_potensial;
        $data->catatan_analisa = $request->catatan_analisa;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('delineation_skenario_relasi', '0')
            ->update([
                'delineation_skenario_relasi' => "1",
                'delineation_laporan' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Skenario Relasi",
                'percentage' => round((29/29) * 100,2)
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


    public function show(Request $request, $id)
    {
        $data = DelineationScenarioRelation::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        $data->load(['information_verification.case.satker', 'information_verification.case', 'information_verification', 'information_validation','observation_information_collection']);


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
        $data = DelineationScenarioRelation::find($id);

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
