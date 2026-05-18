<?php

namespace App\Http\Controllers\API\close\exploration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CloseCase;
use App\Models\MasterSatker;
use App\Models\ExplorationRencanaAksi;
use App\Models\ExplorationTargetIdentity;
use App\Models\ExplorationResultAchievment;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Date;
class ExplorationReportController extends Controller
{
    public function downloadReport($caseid)
    {
        // Temukan CloseCase berdasarkan caseid dengan eager loading relasi
        $data = CloseCase::with(['satker', 'explorationRencanaAksi.explorationTargetIdentities.explorationResultAchievements'])
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
        $satker = $data->satker;
        foreach ($data->explorationRencanaAksi as $rencanaAksi) {
            $rencanaAksi->data_type = "Exploration Action Plan";
        }

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        $mpdf->WriteHTML(view(
            "backoffice.close.exploration.report.pdf",
            compact(
                // 'case',
                'data',
                // 'observationDirective',
                // 'observationCollectInfo',
                // 'observationThreat',
                // 'observationConnect',
                'satker'
                // 'images'
                
            )
        ));

        $filename = 'Close_Exploration_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');
    }
}
