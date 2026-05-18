<?php

namespace App\Http\Controllers\API\close\observation;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CloseCase;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Observation\ObservThreat;
use App\Models\Observation\ObservConnect;
use App\Models\Observation\ObservDirective;
use App\Models\Observation\ObservCollectInfo;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;

class ObservConnectController extends Controller
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

        $data = ObservConnect::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('observation_surat_perintah.satker_id', '=', $idSatker);
                                })
                                ->with(['satker', 'case', 'sprint', 'collectInfo', 'threat'])
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
            'case_id' => 'required|string|max:255',
            'surat_perintah_id' => 'required|string|max:255',
            'information_collection_id' => 'required|string|max:255',
            'potensi_aght_id' => 'required|string|max:255',
            'target_name' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:255',
            // 'target_identity_number' => 'required|string|max:100',
            // 'target_gender' => 'required|string|max:50',
            // 'target_religion' => 'required|string|max:100',
            // 'target_education' => 'required|string|max:100',
            // 'target_occupation' => 'required|string',
            'image' => 'array',
            // 'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('api')->user();

        $data = new ObservConnect;
        $data->satker_id = $user->satker?->id_satker;
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_id = $request->information_collection_id;
        $data->potensi_aght_id = $request->potensi_aght_id;

        $data->target_name = $request->target_name;
        $data->target_identity_number_type = $request->target_identity_number_type;
        $data->target_identity_number = $request->target_identity_number;
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_education = $request->target_education;
        $data->target_occupation = $request->target_occupation;

        // save the image first
        // $filenames = [];
        // $index = 1;
        // if($request->file('image') != null){
        //     foreach ($request->file('image') as $image) {
        //         $filename = $image->storePubliclyAs(
        //             'close/observation/connected-identity/foto-target',
        //             time(). ' - '. $request->target_name.' - '. $index . ' - ' . Str::random() . '.'. $image->getClientOriginalExtension(),
        //             'public'
        //         );
        //         $filenames[] = $filename;
        //         $index++;
        //     }    
        // }

        $filenames = [];
        $index = 1;
        if($request->image != null){
            foreach ($request->image as $base64Image) {
                $decodedImage = base64_decode($base64Image);
                $filename = 'close/observation/connected-identity/foto-target/' . time(). ' - '. $request->target_name.' - '. $index . ' - ' . Str::random() . '.jpg';
                Storage::disk('public')->put($filename, $decodedImage);
                $filenames[] = $filename;
                $index++;
            }    
        }

        $data->target_photo = json_encode($filenames);
        $data->created_by = $user->id;
        $data->updated_by = $user->id;  

        if ($request->submit_type === 'save') {
             // update progress
             $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
             $close_case_progress->update([
                 'observation_identitas_terhubung' => "1",
                 'observation_laporan' => "1",
                 'status' => $close_case_progress->percentage > 18 ? $close_case_progress->status :  'Pengamatan',
                 'substatus' => $close_case_progress->percentage > 18 ? $close_case_progress->substatus : 'Input Pihak Lain Yang Terhubung Pengamatan',
                 'percentage' => $close_case_progress->percentage > 18 ? $close_case_progress->percentage : 18
             ]);
        }else{
             // update progress
             $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
             $close_case_progress->update([
                 'observation_identitas_terhubung' => "1",
                 'observation_laporan' => "1",
                 'status' => $close_case_progress->percentage > 18 ? $close_case_progress->status :  'Pengamatan',
                 'substatus' => $close_case_progress->percentage > 18 ? $close_case_progress->substatus : 'Input Pihak Lain Yang Terhubung Pengamatan',
                 'percentage' => 100
             ]);
        }

        if ($data->save()) {
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Pihak Lain Yang Terhubung Pengamatan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
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
        $data = ObservConnect::where('observation_connected_identity.id', $id)
                            ?->with(['satker', 'case', 'sprint', 'collectInfo'])
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
            'case_id' => 'required|string|max:255',
            'surat_perintah_id' => 'required|string|max:255',
            'information_collection_id' => 'required|string|max:255',
            'potensi_aght_id' => 'required|string|max:255',
            'target_name' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:255',
            // 'target_identity_number' => 'required|string|max:100',
            // 'target_gender' => 'required|string|max:50',
            // 'target_religion' => 'required|string|max:100',
            // 'target_education' => 'required|string|max:100',
            // 'target_occupation' => 'required|string',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('api')->user();

        $data = ObservConnect::find($id);
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_id = $request->information_collection_id;
        $data->potensi_aght_id = $request->potensi_aght_id;

        $data->target_name = $request->target_name;
        $data->target_identity_number_type = "NIK/KTP";
        $data->target_identity_number = $request->target_identity_number;
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_education = $request->target_education;
        $data->target_occupation = $request->target_occupation;

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
                    'close/observation/connected-identity/foto-target',
                    time(). ' - '. $request->target_name.' - '. $index . ' - ' . Str::random() . '.'. $image->getClientOriginalExtension(),
                    'public'
                );
                $newImages[] = $filename;
                $index++;
            }    
        } else{
            $newImages = json_decode($data->target_photo);
        }
        $data->target_photo = json_encode($newImages);

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                 'observation_identitas_terhubung' => "1",
                 'observation_laporan' => "1",
                 'status' => $close_case_progress->percentage > 18 ? $close_case_progress->status :  'Pengamatan',
                 'substatus' => $close_case_progress->percentage > 18 ? $close_case_progress->substatus : 'Input Pihak Lain Yang Terhubung Pengamatan',
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
        $data = ObservThreat::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if($data->aght_path){
            if (Storage::disk('public')->exists($data->aght_path)) {
                Storage::disk('public')->delete($data->aght_path);
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
