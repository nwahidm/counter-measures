<?php

namespace App\Http\Controllers\API\close\delineation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CloseCase;
use App\Models\MasterSatker;
use App\Models\Delineation\DelineationInformationVerification;
use App\Models\Delineation\DelineationInformationValidation;
use App\Models\Delineation\DelineationScenarioRelation;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Date;

class DelineationReportController extends Controller
{
    public function downloadReport($caseid)
    {
        // Temukan CloseCase berdasarkan caseid dengan eager loading relasi
        $data = CloseCase::with([
            'satker', 
            'delineationInformationVerifications',
            'delineationInformationVerifications.delineationInformationValidations',
            'delineationInformationVerifications.delineationInformationValidations.delineationScenarioRelations'])
                                    ->findOrFail($caseid);

        // Tambahkan tipe data ke setiap Delineation Information Verification
        
        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data Not Found',
                'timestamp' => floor(microtime(true) * 1000),
                "data" => []
            ]);
        }

        foreach ($data->delineationInformationVerifications as $verification) {
            $verification->data_type = "Delineation Information Verification";
        }
        
        $case = $data;
    
        $satker = $data->satker;
        // $case->targetphoto = $case->target_photo ? json_decode($case->target_photo, true) : [];
        // dd($case->targetphoto[0]);
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        $mpdf->WriteHTML(view(
            "backoffice.close.delineation.report.pdf",
            compact(
                'case',
                'data',
                // 'observationDirective',
                // 'observationCollectInfo',
                // 'observationThreat',
                // 'observationConnect',
                'satker'
                // 'images'
                
            )
        ));

        $filename = 'Close_Delineation_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');
    }
}
