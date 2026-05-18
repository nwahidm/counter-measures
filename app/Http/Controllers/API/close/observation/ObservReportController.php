<?php

namespace App\Http\Controllers\API\close\observation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CloseCase;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Date;

class ObservReportController extends Controller
{
    public function downloadReport($caseid)
    {
        // Temukan CloseCase berdasarkan caseid dengan eager loading relasi
        $data = CloseCase::with(['satker', 
        'observationDirective',
        'observationDirective.collectionInfo',
        'observationDirective.collectionInfo.threat',
        'observationDirective.collectionInfo.threat.connected'])
                                    ->findOrFail($caseid);

        // Tambahkan tipe data ke setiap Observ Secret Operation
        foreach ($data->observationDirective as $operation) {
            $operation->data_type = "Observation Directive";
        }
        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data Not Found',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => []
            ]);
        }
        // dd($data->observationDirective);
        $satker = $data->satker;
        $observationDirective = $data->observationDirective;
        $observationCollectInfo = $data->observationCollectInfo;

        $observationThreat = $data->observationThreat;
        $observationConnect = $data->observationConnect;


        // $observationDirective = $data->observationDirective;
        // $observationDirective = $data->observationDirective;
        $images = $data->foto ? json_decode($data->foto, true) : [];
        
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view(
            "backoffice.close.observation.report.pdf",
            compact(
                'data',
                'observationDirective',
                'observationCollectInfo',
                'observationThreat',
                'observationConnect',
                'satker',
                'images'
                
            )
        ));

        $filename = 'Close_Observation_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');
    }
}
