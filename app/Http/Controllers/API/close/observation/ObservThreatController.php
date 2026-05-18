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
use App\Models\Observation\ObservDirective;
use App\Models\Observation\ObservCollectInfo;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;

class ObservThreatController extends Controller
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

        $data = ObservThreat::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('observation_potensi_aght.satker_id', '=', $idSatker);
                                })
                                ->with(['satker', 'case', 'sprint', 'collectInfo'])
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
            'aght_type' => 'required|string|max:255',
            // 'aght_place' => 'required|string|max:255',
            // 'aght_time' => 'required',
            // 'perihal' => 'required|string',
            'keterangan' => 'required|string',
            // 'upload_aght' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = new ObservThreat;
        $data->satker_id = $user->satker?->id_satker;
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_id = $request->information_collection_id;

        $data->aght_type = $request->aght_type;
        $data->aght_place = $request->aght_place;
        $data->aght_time = $request->aght_time;
        $data->perihal = $request->perihal;
        $data->keterangan = $request->keterangan;

        // if ($request->hasFile('upload_aght')) {
        //     $ext_upload_aght = $request->file('upload_aght')->extension();
        //     $upload_aght = $request->file('upload_aght')
        //         ->storePubliclyAs(
        //             'close/observation/threat/upload_aght',
        //             Str::slug('observation threat', '_') . '_' . Str::random() . '.' . $ext_upload_aght,
        //             'public'
        //         );

        //     $data->aght_path = $upload_aght;
        // }

        if ($request->upload_aght) {
            $base64Document = $request->upload_aght;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('observation threat', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/observation/threat/upload_aght/' . $fileName;

            // Simpan dokumen
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->aght_path = $uploadPath;
        }

        if ($request->submit_type === 'save') {
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_potensi_aght' => "1",
                'status' => $close_case_progress->percentage > 13.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 13.5 ? $close_case_progress->substatus :  'Input Analisis AGHT Pengamatan',
                'percentage' => $close_case_progress->percentage > 13.5 ? $close_case_progress->percentage : 13.5
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_potensi_aght' => "1",
                'status' => $close_case_progress->percentage > 13.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 13.5 ? $close_case_progress->substatus :  'Input Analisis AGHT Pengamatan',
                'percentage' => 100
            ]);
        }

        if ($data->save()) {
            // save doc analysis
            if($data->aght_path){
                DataHelper::insertDocument($data->id, $data->aght_path);
            }
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Analisis AGHT Pengamatan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            // update progress
            
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
        $data = ObservThreat::with(['satker', 'case', 'sprint', 'collectInfo'])
                            ->where('id', $id)
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
            'aght_type' => 'required|string|max:255',
            // 'aght_place' => 'required|string|max:255',
            // 'aght_time' => 'required',
            // 'perihal' => 'required|string',
            'keterangan' => 'required|string',
            // 'upload_aght' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = ObservThreat::find($id);
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_id = $request->information_collection_id;

        $data->aght_type = $request->aght_type;
        $data->aght_place = $request->aght_place;
        $data->aght_time = $request->aght_time;
        $data->perihal = $request->perihal;
        $data->keterangan = $request->keterangan;

        if ($request->hasFile('aght_path')) {
            $ext_upload_sprint = $request->file('aght_path')->extension();
            $upload_sprint = $request->file('aght_path')
                ->storePubliclyAs(
                    'close/observation/threat/upload_sprint',
                    Str::slug('observation threat', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );

            if($data->aght_path){
                if (Storage::disk('public')->exists($data->aght_path)) {
                    Storage::disk('public')->delete($data->aght_path);
                }
            }

            // save doc analysis
            DataHelper::insertDocument($data->id, $upload_sprint, $data->aght_path);
            $data->aght_path = $upload_sprint;
        } else {
            $aght_path = $data->aght_path;

            $data->aght_path = $aght_path;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_potensi_aght' => "1",
                'status' => $close_case_progress->percentage > 13.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 13.5 ? $close_case_progress->substatus :  'Input Analisis AGHT Pengamatan',
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
