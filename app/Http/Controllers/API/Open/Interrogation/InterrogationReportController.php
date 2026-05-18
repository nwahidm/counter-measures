<?php

namespace App\Http\Controllers\API\Open\Interrogation;

use Mpdf\Mpdf;
use App\Models\OpenCase;
use Illuminate\Http\Request;
use App\Models\InterogationRecord;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use App\Models\InterogationResultAchievement;
use Symfony\Component\HttpFoundation\Response;
use App\Models\InterogationTargetIdentification;

class InterrogationReportController extends Controller
{
    public function downloadReport($id_case)
    {
        $data = OpenCase::find($id_case);
        if(!$data){
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
        $mpdf->WriteHTML(view("backoffice.open.interogation-report.pdf", compact('data')));

        $filename = 'Open_Interogation_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');
    }
    public function getreport(Request $request)
    {
        // Tentukan jumlah item per halaman (misal 10)
        //$perPage = $request->input('per_page', 10);
        $originalHost = request()->getHttpHost();
        $urlPath = $originalHost . '/storage';

        $validator = Validator::make($request->all(), [
            'case_id' => 'required|uuid',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                "status" => Response::HTTP_BAD_REQUEST,
                "statusMessage" => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                "message" => 'Invalid case_id format',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => []
            ]);
        }

        if (!$request->has('case_id')) {
            return response()->json([
                "status" => Response::HTTP_BAD_REQUEST,
                "statusMessage" => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                "message" => 'Case ID is required',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => []
            ]);
        }
        elseif (empty($request->case_id)) {
            return response()->json([
                "status" => Response::HTTP_BAD_REQUEST,
                "statusMessage" => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                "message" => 'Case ID is required',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => []
            ]);
        }

        // Ambil data OpenCase dengan paginasi dan eager loading
        //$case_open_datas = OpenCase::with(['satker', 'progress', 'CaseEventHistoricalUpdates'])->paginate($perPage);
        $interrogation = InterogationRecord::where('case_id', $request->case_id)
                                            ->select('letter_number', 'letter_date', 'perihal', 'berita_acara_path','target_name',
                                            'target_identity_number', 'target_type_identity_number', 'target_gender', 'target_religion',
                                            'target_occupation', 'target_education', 'target_address', 'target_photo')
                                            ->get()
                                            ->map(function ($item) use ($urlPath) {
                                                $item->berita_acara_path = $urlPath . '/' . $item->berita_acara_path;
                                                $item->target_photo = $urlPath . '/' . $item->target_photo;
                                                return $item;
                                            });
        
        $interrogationTarget = InterogationTargetIdentification::where('case_id', $request->case_id)
                                            ->select('hasil_target_identification', 'hasil_target_identification_path')
                                            ->get()
                                            ->map(function ($item) use ($urlPath) {
                                                $item->hasil_target_identification_path = $urlPath . '/' . $item->hasil_target_identification_path;
                                                return $item;
                                            });
        
        $interrogationResult = InterogationResultAchievement::where('case_id', $request->case_id)
                                            ->select('hasil_yang_dicapai', 'upload_hasil_yang_dicapai')
                                            ->get()
                                            ->map(function ($item) use ($urlPath) {
                                                $item->upload_hasil_yang_dicapai = $urlPath . '/' . $item->upload_hasil_yang_dicapai;
                                                return $item;
                                            });

        $responseData = [
            "interrogation" => $interrogation,
            "interrogationTarget" => $interrogationTarget,
            "interrogationResult" => $interrogationResult
        ];
        

        // Buat response dengan data paginasi
        if ($interrogation->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data Not Found',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => []
            ]);
        }
        else{
            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Get Data Success',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => $responseData
            ]);
        }
    }

    public function getreport2(Request $request)
    {
        // Tentukan jumlah item per halaman (misal 10)
        //$perPage = $request->input('per_page', 10);

        // Ambil data OpenCase dengan paginasi dan eager loading
        //$case_open_datas = OpenCase::with(['satker', 'progress', 'CaseEventHistoricalUpdates'])->paginate($perPage);

        $interrogation = InterogationRecord::where('case_id', $request->case_id)
                                        ->select('letter_number', 'letter_date', 'perihal', 'berita_acara_path','target_name',
                                        'target_identity_number', 'target_type_identity_number', 'target_gender', 'target_religion',
                                        'target_occupation', 'target_education', 'target_address', 'target_photo')
                                        ->get();

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
