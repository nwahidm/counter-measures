<?php

namespace App\Http\Controllers;

use App\DataTables\Interogation\InterogationReportDataTable;
use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\OpenCase;
use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;

class InterogationReportController extends Controller
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
    public function index(InterogationReportDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.open.interogation-report.index', compact('satker', 'users'));
    }

    public function downloadFile($id_case)
    {
        $data = OpenCase::find(decrypt($id_case));

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.open.interogation-report.pdf", compact('data')));

        $filename = 'Open_Interogation_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
}
