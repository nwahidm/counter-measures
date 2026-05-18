<?php

namespace App\Http\Controllers\API\close\tapping;

use App\Http\Controllers\Controller;
use App\Models\CloseCase;
use Symfony\Component\HttpFoundation\Response;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Date;
class TappingReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function downloadReport($case_id)
    {
        //
        $data = '';

        $data = CloseCase::with([
            'satker',
            'tappingElectronicDevice',
            'tappingElectronicDevice.tappingIntelligentSignal',
            'tappingElectronicDevice.tappingIntelligentSignal.tappingResultAchievement'
        ])->findOrFail($case_id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data Not Found',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => []
            ]);
        }

        $case = $data;
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        $mpdf->WriteHTML(view(
            "backoffice.close.tapping.report.pdf",
            compact(
                // 'case',
                'data',
                'case',
                // 'observationDirective',
                // 'observationCollectInfo',
                // 'observationThreat',
                // 'observationConnect',
                // 'satker'
                // 'images'
                
            )
        ));

        $filename = 'Close_Tapping_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');
    }
    
}
