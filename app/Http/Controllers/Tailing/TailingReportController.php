<?php

namespace App\Http\Controllers\Tailing;

use App\DataTables\Tailing\TailingReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MasterAgama;
use App\Models\CloseCase;
use App\Models\Tailing\TailingPemahamanPerilaku;
use App\Models\Tailing\TailingTargetOperasi;
use App\Models\Tailing\TailingResultAchievement;
use App\Models\Tailing\TailingReport;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Models\MasterSatker;
use Illuminate\Http\Request;

class TailingReportController extends Controller
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
    public function index(TailingReportDataTable $dataTable)
    {

        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.tailing.report.index', compact('satker', 'users'));
    }

    public function downloadFile($id)
    {

        // $data = CloseCase::find(decrypt($id));

        $tailing_pemahaman_perilaku_datas = TailingPemahamanPerilaku::where('case_id', decrypt($id))->get();
        $tailing_target_operasi_datas = TailingTargetOperasi::where('case_id', decrypt($id))->get();
        $tailing_result_achievement_datas = TailingResultAchievement::where('case_id', decrypt($id))->get();
       
        $case = CloseCase::findOrFail(decrypt($id));
        $case->target_photo = json_decode($case->target_photo, true);
        $satker = MasterSatker::find($case->satker_id);
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(
            view(
                
                "backoffice.close.tailing.report.pdf", 
                compact(
                    'case',
                    'satker',
                    'tailing_pemahaman_perilaku_datas',
                    'tailing_target_operasi_datas',
                    'tailing_result_achievement_datas'
                )));

        $filename = 'Tailing_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');

    }
}
