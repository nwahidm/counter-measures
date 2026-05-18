<?php

namespace App\Http\Controllers\API\close\intrusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CloseCase;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Date;

class IntrusionReportController extends Controller
{
    public function downloadReport($caseid)
    {
        // Temukan CloseCase berdasarkan caseid dengan eager loading relasi
        $data = CloseCase::with(['satker', 'location.environment.result'])
            ->findOrFail($caseid);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data Not Found',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => []
            ]);
        }
        // Tambahkan tipe data ke setiap Intrusion Secret Operation
        foreach ($data->location as $operation) {
            $operation->data_type = "Intrusion Secret Operation";
        }

        $case = $data;
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        $mpdf->WriteHTML(view(
            "backoffice.close.intrusion.report.pdf",
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

        $filename = 'Close_Intrusion_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');
    }
}
