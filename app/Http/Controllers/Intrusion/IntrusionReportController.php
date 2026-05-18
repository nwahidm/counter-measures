<?php

namespace App\Http\Controllers\Intrusion;

use App\DataTables\Intrusion\IntrusionReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Models\CloseCase;
use App\Models\Intrusion\IntrusionResult;
use App\Models\Intrusion\IntrusionTargetEnv;
use App\Models\Intrusion\IntrusionTargetLoc;
use Illuminate\Http\Request;

class IntrusionReportController extends Controller
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
    public function index(IntrusionReportDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.intrusion.report.index', compact('satker', 'users'));
    }

    public function downloadFile($id_case)
    {
        $data = CloseCase::find(decrypt($id_case));
        $intrusionLocation = IntrusionTargetLoc::where('case_id', decrypt($id_case))->get();
        $intrusionEnv = IntrusionTargetEnv::where('case_id', decrypt($id_case))->get();
        $intrusionResult = IntrusionResult::where('case_id', decrypt($id_case))->get();

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.close.intrusion.report.pdf", 
        compact('data', 'intrusionLocation', 'intrusionEnv', 'intrusionResult')));

        $filename = 'Open_Intrusion_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
}
