<?php

namespace App\Http\Controllers\API\Open\Interview;

use App\Http\Controllers\Controller;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\Interview\InterviewSaranTL;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class InterviewSaranTindakLanjutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $caseId = $request->get('interview_result_id');

        if ($caseId) {
            return response()->json(InterviewSaranTL::where('interview_result_id', $caseId)->paginate(10));
        }

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get data berhasil',
            "data" => InterviewSaranTL::paginate(10),
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_OK);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            // 'interview_result_id' => 'required|string|max:128',
            // 'saran_dan_tindak_lanjut_date' => 'required|date',
            // 'saran_dan_tindak_lanjut' => 'required|string|max:1280000',
        ]);

        $data = new InterviewSaranTL;
        $data->interview_result_id = $request->interview_result_id;
        $data->interview_schedule_id = $request->interview_schedule_id;
        $data->saran_dan_tindak_lanjut_date = $request->saran_dan_tindak_lanjut_date;
        $data->saran_dan_tindak_lanjut = $request->saran_dan_tindak_lanjut;
        $data->case_id = $request->id_case;

        $data->created_by = $request->user_id;
        $data->updated_by = $request->user_id;

        $user = Auth::guard('api')->user();

        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $request->id_case)->first();
                $op->wawancara_saran_dan_tindak_lanjut = 1;
                $op->status = $op->percentage > 58.8 ? $op->status : "Wawancara";
                $op->substatus = $op->percentage > 58.8 ? $op->substatus : "Input Saran dan Tindak Lanjut";
                $op->percentage = $op->percentage > 58.8 ? $op->percentage : 58.8;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Wawancara Saran dan Tindak Lanjut';
                $cp->created_by = $user->id;
                $cp->save();
    
                // Laporan
                $op = CaseProgresses::where('case_id', $request->id_case)->first();
                $op->wawancara_laporan = 1;
                $op->status = $op->percentage > 64.68 ? $op->status : "Wawancara";
                $op->substatus = $op->percentage > 64.68 ? $op->substatus : "Input Laporan";
                $op->percentage = $op->percentage > 64.68 ? $op->percentage : 64.68;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Wawancara Laporan';
                $cp->created_by = $user->id;
                $cp->save();
                
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
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }else{
            if ($data->save()) {

                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->wawancara_saran_dan_tindak_lanjut = 1;
                $updateCaseProgresses->status = 'Wawancara';
                $updateCaseProgresses->substatus = 'Penambahan Saran dan Tindak Lanjut"';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Wawancara Saran dan Tindak Lanjut';
                $cp->created_by = $user->id;
                $cp->save();
                
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
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // if ($data->save()) {
        //     $op = CaseProgresses::where('case_id', $data->interviewHasil->interviewJadwal->case_id)->first();
        //     $op->wawancara_saran_dan_tindak_lanjut = 1;
        //     $op->status = $op->percentage > 58.8 ? $op->status : "Wawancara";
        //     $op->substatus = $op->percentage > 58.8 ? $op->substatus : "Input Saran dan Tindak Lanjut";
        //     $op->percentage = $op->percentage > 58.8 ? $op->percentage : 58.8;
        //     $op->updated_by = $request->user_id;
        //     $op->save();

        //     $cp = new CaseEventHistoricalUpdates;
        //     $cp->case_id = $data->interviewHasil->interviewJadwal->case_id;
        //     $cp->action = 'Penambahan Wawancara Saran dan Tindak Lanjut';
        //     $cp->created_by = $request->user_id;
        //     $cp->save();

        //     // Laporan
        //     $op = CaseProgresses::where('case_id', $data->interviewHasil->interviewJadwal->case_id)->first();
        //     $op->wawancara_laporan = 1;
        //     $op->status = $op->percentage > 64.68 ? $op->status : "Wawancara";
        //     $op->substatus = $op->percentage > 64.68 ? $op->substatus : "Input Laporan";
        //     $op->percentage = $op->percentage > 64.68 ? $op->percentage : 64.68;
        //     $op->updated_by = $request->user_id;
        //     $op->save();

        //     $cp = new CaseEventHistoricalUpdates;
        //     $cp->case_id = $data->interviewHasil->interviewJadwal->case_id;
        //     $cp->action = 'Penambahan Wawancara Laporan';
        //     $cp->created_by = $request->user_id;
        //     $cp->save();

        //     return response()->json([
        //         "status" => Response::HTTP_OK,
        //         "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
        //         "message" => 'Data berhasil disimpan',
        //         "data" => $data,
        //         'timestamp' => floor(microtime(true) * 1000)
        //     ]);
        // }

        // return response()->json([
        //     "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
        //     "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
        //     "message" => 'Data gagal disimpan',
        //     "data" => $data,
        //     'timestamp' => floor(microtime(true) * 1000)
        // ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = InterviewSaranTL::with([
            'interviewHasil',
            'interviewHasil.interviewJadwal',
            'interviewHasil.interviewJadwal.case'
        ])->first();

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data tidak ditemukan.',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get data berhasil',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_OK);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            // 'interview_result_id' => 'required|string|max:128',
            'saran_dan_tindak_lanjut_date' => 'required|date',
            'saran_dan_tindak_lanjut' => 'required|string|max:1280000',
        ]);

        $data = InterviewSaranTL::find($id);
        $data->case_id = $request->id_case;
        $data->interview_result_id = $request->interview_result_id;
        $data->interview_schedule_id = $request->interview_schedule_id;
        $data->saran_dan_tindak_lanjut_date = $request->saran_dan_tindak_lanjut_date;
        $data->saran_dan_tindak_lanjut = $request->saran_dan_tindak_lanjut;

        $data->updated_by = $request->user_id;

        if ($request->submit_type === 'update_and_finish') {                 
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->wawancara_saran_dan_tindak_lanjut = 1;
            $updateCaseProgresses->status = 'Wawancara';
            $updateCaseProgresses->substatus = 'Penambahan Saran dan Tindak Lanjut"';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }

        if ($data->update()) {
            $cp = CaseEventHistoricalUpdates::where('case_id', $request->id_case)->first();
            $cp->action = 'Perubahan Wawancara Saran dan Tindak Lanjut';
            $cp->updated_by = $request->user_id;
            $cp->update();

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
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = InterviewSaranTL::find($id);

        if ($data) {
            $cp = CaseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
            $cp->action = 'Penghapusan Wawancara Saran dan Tindak Lanjut';
            $cp->updated_by = $data->updated_by;
            $cp->update();

            $data->delete();

            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Data berhasil dihapus',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        return response()->json([
            "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            "message" => 'Data gagal dihapus',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
