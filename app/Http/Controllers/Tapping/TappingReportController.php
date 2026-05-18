<?php

namespace App\Http\Controllers\Tapping;

use App\DataTables\Tapping\TappingReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Models\CloseCase;

class TappingReportController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TappingReportDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.tapping.report.index', compact('satker', 'users'));
    }

    public function downloadFile($id_case)
    {
        $data = CloseCase::with(
            'satker',
            'tappingElectronicDevice',
            'tappingElectronicDevice.tappingIntelligentSignal',
            'tappingElectronicDevice.tappingIntelligentSignal.tappingResultAchievement',
        )->where('id', decrypt($id_case))->first();

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.close.tapping.report.pdf", compact('data')));

        $filename = 'Close_Tapping_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
}
