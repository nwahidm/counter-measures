<?php

namespace App\Http\Controllers\API\Open\Research;

use App\Http\Controllers\Controller;
use App\Models\OpenCase;
use Symfony\Component\HttpFoundation\Response;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Date;
class ResearchReportController extends Controller
{
    /**
     * Handle the incoming request.
     */

    public function downloadReport($caseid)
    {
        // $data = OpenCase::find($case_id);
        $data = OpenCase::with(
            'satker',

            'caseProgress',
            'researchSuratPerintah',
            'researchSuratPerintah.researchLaporanInformasiKhusus',
            'researchSuratPerintah.researchLaporanInformasiKhusus.researchSaranTindakLanjut',
            'researchSuratPerintah.researchLaporanInformasiKhusus.researchSaranTindakLanjut.researchPotensiAght',
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

        $data->foto = $data->foto ? json_decode($data->foto, true) : [];
        $researchSuratPerintah = $data->researchSuratPerintah;
        $researchLaporanInformasiKhusus = $data->researchLaporanInformasiKhusus;
        $researchSaranTindakLanjut = $data->researchSaranTindakLanjut;
        $researchPotensiAght = $data->researchPotensiAght;


        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view(
            "backoffice.open.research.report.pdf",
            compact('data', 
            'researchSuratPerintah', 
            'researchLaporanInformasiKhusus',
            'researchSaranTindakLanjut',
            'researchPotensiAght' )
        ));

        $filename = 'Open_Research_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');
    }
    // public function __invoke($case_id)
    // {
    //     //
    //     $data = '';

    //     try {
    //         $data = OpenCase::with(
    //             'satker', 

    //             'caseProgress',
    //             'researchSuratPerintah',
    //             'researchSuratPerintah.researchLaporanInformasiKhusus',
    //             'researchSuratPerintah.researchLaporanInformasiKhusus.researchSaranTindakLanjut',
    //             'researchSuratPerintah.researchLaporanInformasiKhusus.researchSaranTindakLanjut.researchPotensiAght',
    //             'CaseEventHistoricalUpdates'
    //         )->findOrFail($case_id);

    //         if ($data->foto) { // Use $item instead of $data
    //             $imagePaths = json_decode($data->foto); // Decode the JSON string containing image paths

    //             foreach ($imagePaths as $imagePath) { // Loop through each image path
    //                 $images[] = asset('storage/' . $imagePath); // Add the full image URL to the $images array
    //             }
    //             $data->foto = $imagePaths;
    //         }
    //         foreach ($data as $key => $value) {
    //             if (is_array($value) || is_object($value)) {
    //                 // Recursively apply strip_tags to arrays or objects
    //                 $data->$key = applyStripTags($value);
    //             } else if (is_string($value)) {
    //                 // Apply strip_tags to string values
    //                 $data->$key = strip_tags($value);
    //             }
    //         }


    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         return response()->json([
    //             "status" => Response::HTTP_NOT_FOUND,
    //             "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
    //             "message" => $th->getMessage(),
    //             'timestamp' => floor(microtime(true) * 1000),
    //             "data" => $data
    //         ], Response::HTTP_NOT_FOUND);
    //     }

    //     return response()->json([
    //         "status" => Response::HTTP_OK,
    //         "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
    //         "message" => 'Get Data Success',
    //         'timestamp' => floor(microtime(true) * 1000),
    //         "data" => $data
    //     ]);
    // }


}
