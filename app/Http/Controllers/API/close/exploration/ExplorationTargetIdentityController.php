<?php

namespace App\Http\Controllers\API\close\exploration;

use Illuminate\Http\Request;
use App\Models\CaseCloseProgresses;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ExplorationTargetIdentity;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;

class ExplorationTargetIdentityController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = ExplorationTargetIdentity::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('exploration_target_identitas.satker_id', '=', $idSatker);
                                })
                                ->with('case', 'case.satker', 'explorationRencanaAksi')
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
        $data = ExplorationTargetIdentity::find($id);

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
        $data->load(['satker', 'case', 'explorationRencanaAksi']);

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
        $data = ExplorationTargetIdentity::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if($data->target_photo){
            if (Storage::disk('public')->exists($data->target_photo)) {
                Storage::disk('public')->delete($data->target_photo);
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
        //
        $this->validate($request, [
            'id_satker' => 'required',
            'case_id' => 'required|string|max:255',
            'exploration_rencana_aksi_id' => 'required',
            'target_name' => 'required|string|max:255',
            // 'target_identity_number' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:255',
            // 'target_gender' => 'required|string|max:255',
            // 'target_religion' => 'required|string|max:255',
            // 'target_education' => 'required|string|max:255',
            // 'target_occupation' => 'required|string|max:255',
            // 'target_photo' => 'nullable|file|mimes:jpeg,jpg|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = new ExplorationTargetIdentity;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->exploration_rencana_aksi_id = $request->exploration_rencana_aksi_id;
        $data->target_name = $request->target_name;
        $data->target_identity_number = $request->nik;
        $data->target_identity_number_type = 'KTP';
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;


        if ($request->target_photo) {
            $base64Document = $request->target_photo;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('exploration-identitas-target', '_') . '_' . Str::random() . '.jpeg';
            $uploadPath = 'close/exploration/identitastarget/upload/' . $fileName;

            // Simpan dokumen
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->target_photo = $uploadPath;
        }

        // if ($request->hasFile('target_photo')) {
        //     $ext_upload_info = $request->file('target_photo')->extension();
        //     $upload_info = $request->file('target_photo')
        //         ->storePubliclyAs(
        //             'close/exploration/identitastarget/upload',
        //             Str::slug('exploration-identitas-target', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->target_photo = $upload_info;
        // }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_identitas_target' => "1",
                'status' => "Identitas Target",
                'substatus' => "Penambahan Identitas Targer",
                'percentage' => round((11/29)*100,2)
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_identitas_target' => "1",
                'status' => "Identitas Target",
                'substatus' => "Penambahan Identitas Targer",
                'percentage' => round((29/29)*100,2)
            ]);

        }


        if ($data->save()) {
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $request->case_id;
            $data_case_close_historical_update->action = "Penambahan Identitas Target";
    
            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_identitas_target' => "1",
                'status' => "Identitas Target",
                'substatus' => "Penambahan Identitas Targer",
                'percentage' => round((11/29)*100,2)
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

    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'id_satker' => 'required',
            'case_id' => 'required|string|max:255',
            'target_name' => 'required|string|max:255',
            'exploration_rencana_aksi_id' => 'required',
            // 'target_identity_number' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:255',
            // 'target_gender' => 'required|string|max:255',
            // 'target_religion' => 'required|string|max:255',
            // 'target_occupation' => 'required|string|max:255',
            // 'target_education' => 'required|string|max:255',
            // 'target_photo' => 'nullable|file|mimes:jpeg,jpg|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = ExplorationTargetIdentity::find($id);
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->exploration_rencana_aksi_id = $request->exploration_rencana_aksi_id;
        $data->target_name = $request->target_name;
        $data->target_identity_number = $request->target_identity_number;
        $data->target_identity_number_type = $request->target_identity_number_type;
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_occupation = $request->target_occupation;
        $data->target_education = $request->target_education;

        if ($request->hasFile('target_photo')) {
            $ext_upload_sprint = $request->file('target_photo')->extension();
            $upload_sprint = $request->file('target_photo')
                ->storePubliclyAs(
                    'close/exploration/identitastarget/upload',
                    Str::slug('exploration-identitas-target', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );


            $data->target_photo = $upload_sprint;
        } else {
            $information_collection_upload = $request->temp_target_photo;

            $data->target_photo = $information_collection_upload;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_identitas_target' => "1",
                'status' => "Identitas Target",
                'substatus' => "Penambahan Identitas Targer",
                'percentage' => round((29/29)*100,2)
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

}
