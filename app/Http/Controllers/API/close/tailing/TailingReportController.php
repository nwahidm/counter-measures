<?php

namespace App\Http\Controllers\API\close\tailing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CloseCase;
use App\Models\MasterSatker;
use App\Models\Tailing\TailingPemahamanPerilaku;
use App\Models\Tailing\TailingTargetOperasi;
use App\Models\Tailing\TailingResultAchievement;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Date;

class TailingReportController extends Controller
{
    public function downloadReport($caseid)
    {
        // Temukan CloseCase berdasarkan caseid dengan eager loading relasi
        $data = CloseCase::with(['satker', 'tailingPemahamanPerilaku.tailingTargetOperations.tailingResultAchievements'])
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

        foreach ($data->tailingPemahamanPerilaku as $pemahamanPerilaku) {
            $pemahamanPerilaku->data_type = "Tailing Pemahaman Perilaku";
        }
        $satker = $data->satker;
        $case = $data;
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        $mpdf->WriteHTML(view(
            "backoffice.close.tailing.report.pdf",
            compact(
                // 'case',
                'data',
                'case',
                // 'observationDirective',
                // 'observationCollectInfo',
                // 'observationThreat',
                // 'observationConnect',
                'satker'
                // 'images'
                
            )
        ));

        $filename = 'Close_Tailing_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');
    }
}
