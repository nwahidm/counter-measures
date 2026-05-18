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
use App\Models\Observation\ObservDirective;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\DataTables\Observation\ObservDirectiveDataTable;

class ObservDirectiveController extends Controller
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

        $data = ObservDirective::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('observation_surat_perintah.satker_id', '=', $idSatker);
                                })
                                ->with('case', 'satker')
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
            'surat_perintah_number' => 'required|string|max:128',
            'surat_perintah_perihal' => 'required|string|max:255'
            // 'surat_perintah_date' => 'required|string|max:128',
            // 'surat_perintah_date_started' => 'required|date',
            // 'upload_sprint' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = new ObservDirective;
        $data->satker_id = $user->satker?->id_satker;

        $data->case_id = $request->case_id;
        $data->surat_perintah_number = $request->surat_perintah_number;
        $data->surat_perintah_perihal = $request->surat_perintah_perihal;
        $data->surat_perintah_date = $request->surat_perintah_date;
        $data->surat_perintah_date_started = $request->surat_perintah_date_started;

        if ($request->has('upload_sprint')) {
            $ext_upload_sprint = 'pdf';
            $upload_sprint = $request->upload_sprint;
            $upload_sprint = base64_decode($upload_sprint);
            $upload_sprint = 'close/observation/directive/upload_sprint/' . Str::slug('observation directive sprint', '_') . '_' . Str::random() . '.' . $ext_upload_sprint;

            Storage::disk('public')->put($upload_sprint, $upload_sprint);
            $data->surat_perintah_path = $upload_sprint;
        }

        // if ($request->hasFile('upload_sprint')) {
        //     $ext_upload_sprint = $request->file('upload_sprint')->extension();
        //     $upload_sprint = $request->file('upload_sprint')
        //         ->storePubliclyAs(
        //             'close/observation/directive/upload_sprint',
        //             Str::slug('observation directive sprint', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
        //             'public'
        //         );

        //     $data->surat_perintah_path = $upload_sprint;
        // }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
             
            $close_case_progress->update([
                'observation_surat_perintah' => "1",
                'status' => $close_case_progress->percentage > 4.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 4.5 ? $close_case_progress->substatus :  'Input Surat Perintah Pengamatan',
                'percentage' => $close_case_progress?->percentage > 4.5 ? $close_case_progress?->percentage : 4.5
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
             
            $close_case_progress->update([
                'observation_surat_perintah' => "1",
                'status' => $close_case_progress->percentage > 4.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 4.5 ? $close_case_progress->substatus :  'Input Surat Perintah Pengamatan',
                'percentage' => 100
            ]);
        }

        if ($data->save()) {
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Surat Perintah Pengamatan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            // update progress
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            
            
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
        $data = ObservDirective::where('id', $id)
                                ->with('satker', 'case')
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
            'surat_perintah_number' => 'required|string|max:128',
            'surat_perintah_perihal' => 'required|string|max:255',
            // 'surat_perintah_date' => 'required|string|max:128',
            // 'surat_perintah_date_started' => 'required|date',
            'surat_perintah_path' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = ObservDirective::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        $data->case_id = $request->case_id;
        $data->surat_perintah_number = $request->surat_perintah_number;
        $data->surat_perintah_perihal = $request->surat_perintah_perihal;
        $data->surat_perintah_date = $request->surat_perintah_date;
        $data->surat_perintah_date_started = $request->surat_perintah_date_started;

        if ($request->hasFile('surat_perintah_path')) {
            $ext_upload_sprint = $request->file('surat_perintah_path')->extension();
            $upload_sprint = $request->file('surat_perintah_path')
                ->storePubliclyAs(
                    'close/observation/directive/upload_sprint',
                    Str::slug('observation directive sprint', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );
            if($data->surat_perintah_path){
                if (Storage::disk('public')->exists($data->surat_perintah_path)) {
                    Storage::disk('public')->delete($data->surat_perintah_path);
                }
            }
            
            $data->surat_perintah_path = $upload_sprint;
        } else {
            $surat_perintah_path = $data->surat_perintah_path;

            $data->surat_perintah_path = $surat_perintah_path;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
            
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
             
            $close_case_progress->update([
                'observation_surat_perintah' => "1",
                'status' => $close_case_progress->percentage > 4.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 4.5 ? $close_case_progress->substatus :  'Input Surat Perintah Pengamatan',
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
        $data = ObservDirective::find($id);

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
