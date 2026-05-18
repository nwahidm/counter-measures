<?php

namespace App\Http\Controllers\API\Open\Elicitation;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Helpers\DataHelper;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ElicitationAdFoll;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\ElicitationResult;

class ElicitationAdFollController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = ElicitationAdFoll::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('elicitation_hasil_wawancara.satker_id', '=', $idSatker);
                                })
                                ->with('case')
                                ->with('elinterview')
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
        $data = ElicitationAdFoll::find($id);

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
        $data->load(['satker', 'case', 'elinterview']);

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
        $data = ElicitationAdFoll::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        $data->delete();
        ElicitationResult::where('elicitation_advice_and_follow_up_id', $id)->delete();


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
            'id_case' => 'required',
            'id_satker' => 'required',
            // 'id_elicitation_interview_result' => 'required',
            'tanggal_tinjut' => 'required',
            'saran_tinjut' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $user = Auth::guard('api')->user();

            $data = ElicitationAdFoll::create([
                'case_id' => $request->id_case,
                'satker_id' => $request->id_satker,
                'elicitation_hasil_wawancara_id' => $request->id_elicitation_interview_result,
                'saran_dan_tindak_lanjut_date' => $request->tanggal_tinjut,
                'saran_dan_tindak_lanjut' => $request->saran_tinjut,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            if ($request->submit_type === 'save') {

                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->elisitasi_saran_dan_tindak_lanjut = 1;
                $updateCaseProgresses->status = 'Elicitation';
                $updateCaseProgresses->substatus = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 88.2 ? $updateCaseProgresses->percentage : 88.2;
                $updateCaseProgresses->save();

                // $cp = CaseEventHistoricalUpdates::where('case_id',$request->id_case)->first();
                // $cp->action = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                // $cp->created_by = $user->id;
                // $cp->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                $cp->created_by = $user->id;
                $cp->save();


            }else{
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->elisitasi_saran_dan_tindak_lanjut = 1;
                $updateCaseProgresses->status = 'Elicitation';
                $updateCaseProgresses->substatus = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                // $cp = CaseEventHistoricalUpdates::where('case_id',$request->id_case)->first();
                // $cp->action = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                // $cp->created_by = $user->id;
                // $cp->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                $cp->created_by = $user->id;
                $cp->save();


            }

            // $log = DataHelper::logUpdateCase($request->id_case, 'Penambahan Elisitasi Saran dan Tindak Lanjut');

            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Data berhasil disimpan',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data Gagal Disimpan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        //

        $this->validate($request, [
            'id_case' => 'required',
            // 'id_elicitation_interview_result' => 'required',
            'tanggal_tinjut' => 'required',
            'saran_tinjut' => 'required',
        ]);
        
        $user = auth()->user();

        $data = ElicitationAdFoll::find($id);
        $data->case_id = $request->id_case;
        $data->elicitation_hasil_wawancara_id = $request->id_elicitation_interview_result;
        $data->saran_dan_tindak_lanjut_date = $request->tanggal_tinjut;
        $data->saran_dan_tindak_lanjut = $request->saran_tinjut;

        $data->updated_by = $user->id;

        if ($data->update()) {
            if ($request->submit_type === 'update_and_finish') {
       
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->elisitasi_saran_dan_tindak_lanjut = 1;
                $updateCaseProgresses->status = 'Elicitation';
                $updateCaseProgresses->substatus = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();
    

            }

            // $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Elisitasi Saran dan Tindak Lanjut');

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
