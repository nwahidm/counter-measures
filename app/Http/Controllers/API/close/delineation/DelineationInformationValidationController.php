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
use App\Models\Delineation\DelineationInformationValidation;

class DelineationInformationValidationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = DelineationInformationValidation::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                        $q->where('observation_information_validation.satker_id', '=', $idSatker);
                                    })
                                    ->with('case')
                                    ->with('satker')
                                    ->with('information_verification')
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
    
    public function show(Request $request, $id)
    {
        $data = DelineationInformationValidation::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        $data->load(['satker', 'case', 'information_verification','observation_information_collection']);


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
            // 'metode_validasi' => 'required|string|max:128',
            // 'validation_date' => 'required|date',
            // 'hasil_validasi' => 'required|string|max:1000000',
            // 'catatan_validasi' => 'required|string|max:1000000',
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();

        $data = new DelineationInformationValidation;
        $data->satker_id = $satker->id_satker;

        $data->case_id = $request->id_case;
        $data->information_collection_id = $request->id_information_collection;
        $data->information_verification_id = $request->id_information_verification;

        $data->metode_validasi = $request->metode_validasi;
        $data->tanggal_validasi = $request->validation_date;
        $data->catatan_validasi = $request->catatan_validasi;
        $data->hasil_validasi = $request->hasil_validasi;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update->case_id = $data->id;
        $data_case_close_historical_update->action = "Penambahan Informasi Validasi";

        $data_case_close_historical_update->created_by = $user->id;
        $data_case_close_historical_update->updated_by = $user->id;
        
        if ($request->submit_type === 'save') {

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
            ->where('delineation_informasi_validation', '0')
            ->update([
                'delineation_informasi_validation' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Informasi Validasi",
                'percentage' => round((7/29)*100,2)
            ]);;
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
            ->where('delineation_informasi_validation', '0')
            ->update([
                'delineation_informasi_validation' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Informasi Validasi",
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

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'id_case' => 'required|string|max:128',
            // 'id_satker' => 'required|string|max:128',
            // 'id_information_collection' => 'required|string|max:128',
            // 'id_information_verification' => 'required|string|max:128',
            // 'metode_validasi' => 'required|string|max:128',
            // 'validation_date' => 'required|date',
            // 'hasil_validasi' => 'required|string|max:1000000',
            // 'catatan_validasi' => 'required|string|max:1000000',
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();

        $data = DelineationInformationvalidation::find($id);
        $data->satker_id = $satker->id_satker;

        $data->case_id = $request->id_case;
        $data->information_collection_id = $request->id_information_collection;
        $data->information_verification_id = $request->id_information_verification;

        $data->metode_validasi = $request->metode_validasi;
        $data->tanggal_validasi = $request->validation_date;
        $data->catatan_validasi = $request->catatan_validasi;
        $data->hasil_validasi = $request->hasil_validasi;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
            ->where('delineation_informasi_validation', '0')
            ->update([
                'delineation_informasi_validation' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Informasi Validasi",
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
        $data = DelineationInformationValidation::find($id);

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
