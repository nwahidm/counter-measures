<?php

namespace App\Http\Controllers\API\close\infiltration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CloseCase;
use App\Models\MasterSatker;
use App\Models\Infiltration\InfiltrationSecretOperation;
use App\Models\Infiltration\InfiltrationTargetDynamics;
use App\Models\Infiltration\InfiltrationResultAchievement;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Date;

class InfiltrationReportController extends Controller
{
    public function downloadReport($caseid)
    {
        // Temukan CloseCase berdasarkan caseid dengan eager loading relasi
        $data = CloseCase::with([
            'satker',
            'infiltrationSecretOperations.infiltrationTargetDynamics.infiltrationResultAchievements'
        ])
            ->find($caseid);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data Not Found',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => []
            ]);
        }
        foreach ($data->infiltrationSecretOperations as $operation) {
            $operation->data_type = "Infiltration Secret Operation";
        }
        $case = $data;
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        $mpdf->WriteHTML(view(
            "backoffice.close.infiltration.report.pdf",
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

        $filename = 'Close_Infiltration_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');
    }
}
