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
use App\Models\Observation\ObservCollectInfo;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;

class ObservCollectInfoController extends Controller
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

        $data = ObservCollectInfo::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('observation_information_collection.satker_id', '=', $idSatker);
                                })
                                ->with(['satker', 'case', 'sprint'])
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
            'information_collection_source' => 'required|string|max:255',
            'information_collection_perihal' => 'required|string|max:255',
            // 'information_collection_date' => 'required|date',
            'information_collection_detail' => 'required',
            // 'upload_info' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = new ObservCollectInfo;
        $data->satker_id = $user->satker?->id_satker;

        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_source = $request->information_collection_source;
        $data->information_collection_perihal = $request->information_collection_perihal;
        $data->information_collection_date = $request->information_collection_date;
        $data->information_collection_detail = $request->information_collection_detail;

        if ($request->upload_info) {
            $base64Document = $request->upload_info;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('observation collect-info sprint', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/observation/collect-info/upload_info/' . $fileName;

            // Simpan dokumen
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->information_collection_upload = $uploadPath;
        }

        // if ($request->hasFile('upload_info')) {
        //     $ext_upload_info = $request->file('upload_info')->extension();
        //     $upload_info = $request->file('upload_info')
        //         ->storePubliclyAs(
        //             'close/observation/collect-info/upload_info',
        //             Str::slug('observation collect-info sprint', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->information_collection_upload = $upload_info;
        // }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_information_collection' => "1",
                'status' => 'Pengamatan',
                'substatus' => 'Input Pengumpulan Informasi Pengamatan',
                'percentage' => $close_case_progress->percentage > 9.0 ? $close_case_progress->percentage : 9.0
            ]);
            
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_information_collection' => "1",
                'status' => 'Pengamatan',
                'substatus' => 'Input Pengumpulan Informasi Pengamatan',
                'percentage' => 100
            ]);
            
        }

        if ($data->save()) {
            // dd($data->id, $data->information_collection_upload);
            // save doc analysis
            if($data->information_collection_upload){
                DataHelper::insertDocument($data->id, $data->information_collection_upload);
            }
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Pengumpulan Informasi Pengamatan";

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
        $data = ObservCollectInfo::where('observation_information_collection.id',$id)
                                ?->with(['satker', 'case', 'sprint'])
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
            'information_collection_source' => 'required|string|max:255',
            'information_collection_perihal' => 'required|string|max:255',
            // 'information_collection_date' => 'required|date',
            'information_collection_detail' => 'required',
            'information_collection_upload' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = ObservCollectInfo::find($id);
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_source = $request->information_collection_source;
        $data->information_collection_perihal = $request->information_collection_perihal;
        $data->information_collection_date = $request->information_collection_date;
        $data->information_collection_detail = $request->information_collection_detail;

        if ($request->hasFile('information_collection_upload')) {
            $ext_upload_sprint = $request->file('information_collection_upload')->extension();
            $upload_sprint = $request->file('information_collection_upload')
                ->storePubliclyAs(
                    'close/observation/collect-info/upload_sprint',
                    Str::slug('observation collect-info', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );

            if($data->information_collection_upload){
                if (Storage::disk('public')->exists($data->information_collection_upload)) {
                    Storage::disk('public')->delete($data->information_collection_upload);
                }
            }

            // save doc analysis
            DataHelper::insertDocument($data->id, $upload_sprint, $data->information_collection_upload);
            $data->information_collection_upload = $upload_sprint;
        } else {
            $information_collection_upload = $data->information_collection_upload;

            $data->information_collection_upload = $information_collection_upload;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_information_collection' => "1",
                'status' => 'Pengamatan',
                'substatus' => 'Input Pengumpulan Informasi Pengamatan',
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
        $data = ObservCollectInfo::find($id)?->with(['satker', 'case', 'sprint']);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if($data->information_collection_upload){
            if (Storage::disk('public')->exists($data->information_collection_upload)) {
                Storage::disk('public')->delete($data->information_collection_upload);
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
