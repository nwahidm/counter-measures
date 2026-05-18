<?php

namespace App\Http\Controllers\API\Open\Elicitation;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\OpenCase;
use App\Models\ElicitationInterview;
use App\Models\ElicitationAdFoll;
use App\Models\ElicitationResult;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Date;

class ElicitationReportController extends Controller
{
    public function downloadReport($caseid)
    {
        // Tentukan jumlah item per halaman (misal 10)
        //$perPage = $request->input('per_page', 10);
        $originalHost = request()->getHttpHost();
        $urlPath = $originalHost . '/storage';

        // $validator = Validator::make($request->all(), [
        //     'case_id' => 'required|uuid',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         "status" => Response::HTTP_BAD_REQUEST,
        //         "statusMessage" => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
        //         "message" => 'Invalid Case ID format',
        //         'timestamp' => floor(microtime(true) * 1000),
        //         "data" => []
        //     ]);
        // }

        // if (!$request->has('case_id')) {
        //     return response()->json([
        //         "status" => Response::HTTP_BAD_REQUEST,
        //         "statusMessage" => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
        //         "message" => 'Case ID is required',
        //         'timestamp' => floor(microtime(true) * 1000),
        //         "data" => []
        //     ]);
        // }
        // elseif (empty($request->case_id)) {
        //     return response()->json([
        //         "status" => Response::HTTP_BAD_REQUEST,
        //         "statusMessage" => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
        //         "message" => 'Case ID is required',
        //         'timestamp' => floor(microtime(true) * 1000),
        //         "data" => []
        //     ]);
        // }



        $data = OpenCase::with(
            'satker',

            'caseProgress',
            'elicitationInterview',
            'elicitationInterview.eliciAdfoll',
            'elicitationInterview.eliciAdfoll.elresult',
            
            'CaseEventHistoricalUpdates'
        )->findOrFail($caseid);
        
        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data Not Found',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => []
            ]);
        }


        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view(
            "backoffice.open.elicitation-report.pdf",
            compact(
                'data',
                
            )
        ));

        $filename = 'Open_Elicitation_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');
        // $responseData = [
        //     "elicitaction" => $elicitaction,
        //     "elicitactionAdFl" => $elicitactionAdFl,
        //     "elicitactionResult" => $elicitactionResult
        // ];


        // // Buat response dengan data paginasi
        // if ($elicitaction->isEmpty()) {
        //     return response()->json([
        //         "status" => Response::HTTP_NOT_FOUND,
        //         "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
        //         "message" => 'Data Not Found',
        //         'timestamp' => floor(microtime(true) * 1000),
        //         "data" => []
        //     ]);
        // }
        // else{
        //     return response()->json([
        //         "status" => Response::HTTP_OK,
        //         "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
        //         "message" => 'Get Data Success',
        //         'timestamp' => floor(microtime(true) * 1000),
        //         "data" => $responseData
        //     ]);
        // }
    }

    public function getreport2(Request $request)
    {
        // Tentukan jumlah item per halaman (misal 10)
        $perPage = $request->input('per_page', 10);

        // Ambil data OpenCase dengan paginasi dan eager loading
        $case_open_datas = OpenCase::with(['satker', 'progress', 'CaseEventHistoricalUpdates'])->paginate($perPage);

        // $interrogation = InterogationRecord::where('case_id', $request->case_id)
        //                                 ->select('letter_number', 'letter_date', 'perihal', 'berita_acara_path','target_name',
        //                                 'target_identity_number', 'target_type_identity_number', 'target_gender', 'target_religion',
        //                                 'target_occupation', 'target_education', 'target_address', 'target_photo')
        //                                 ->get();

        // Buat response dengan data paginasi
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data Success',
            'timestamp' => floor(microtime(true) * 1000),
            "data" => $case_open_datas->items(),
            "pagination" => [
                "total" => $case_open_datas->total(),
                "count" => $case_open_datas->count(),
                "per_page" => $case_open_datas->perPage(),
                "current_page" => $case_open_datas->currentPage(),
                "total_pages" => $case_open_datas->lastPage(),
                "links" => [
                    "next" => $case_open_datas->nextPageUrl(),
                    "prev" => $case_open_datas->previousPageUrl()
                ]
            ]
        ]);
    }
}
