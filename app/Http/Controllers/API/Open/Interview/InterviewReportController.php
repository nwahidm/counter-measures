<?php

namespace App\Http\Controllers\API\Open\Interview;

use App\Http\Controllers\Controller;
use App\Models\OpenCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Date;
use Mpdf\Mpdf;

class InterviewReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function downloadReport($caseid)
    {

        // $data = OpenCase::find($case_id);
        $data = OpenCase::with(
                'satker', 
                'interviewJadwal',
                'interviewJadwal.interviewHasil',
                'interviewJadwal.interviewHasil.interviewSaranTL'
        )->findOrFail($caseid);
        if(!$data){
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data Not Found',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => []
            ]);
        }
        $data->foto = $data->foto ? json_decode($data->foto, true) : [];
        $interviewJadwal = $data->interviewJadwal;
        $interviewHasil = $data->interviewHasil;
        $interviewSaranTL = $data->interviewSaranTL;


        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.open.interview.report.pdf", 
        compact('data', 'interviewJadwal','interviewHasil','interviewSaranTL')));

        $filename = 'Open_Interview_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');

        //
        // $data = '';

        // try {
        //     $data = OpenCase::with(
        //         'satker', 
        //         'interviewJadwal',
        //         'interviewJadwal.interviewHasil',
        //         'interviewJadwal.interviewHasil.interviewSaranTL'
        //     )->findOrFail($case_id);
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     return response()->json([
        //         "status" => Response::HTTP_NOT_FOUND,
        //         "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
        //         "message" => $th->getMessage(),
        //         'timestamp' => floor(microtime(true) * 1000),
        //         "data" => $data
        //     ], Response::HTTP_NOT_FOUND);
        // }

        // return response()->json([
        //     "status" => Response::HTTP_OK,
        //     "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
        //     "message" => 'Get Data Success',
        //     'timestamp' => floor(microtime(true) * 1000),
        //     "data" => $data
        // ]);
    }
    
}
