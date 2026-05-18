<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\OpenCase;
use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use App\DataTables\Elicitation\ElicitationReportDataTable;
use App\Models\ElicitationResult;

class ElicitationReportController extends Controller
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
    public function index(ElicitationReportDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.open.elicitation-report.index', compact('satker', 'users'));
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
        $mpdf->WriteHTML(view("backoffice.open.elicitation-report.pdf", compact('data')));

        $filename = 'Open_Elicitation_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    public function downloadHasilPelaksanaanTugas($id){
        
        $elicitationResult = ElicitationResult::join('elicitation_hasil_wawancara','elicitation_hasil_yang_dicapai.elicitation_interview_result_id','=','elicitation_hasil_wawancara.id_elicitation_interview_result')
        ->where('id_elicitation_result', $id)->first();
        
        
        $data = OpenCase::where('id', $elicitationResult->case_id)->first();
        $status = "preview";
        if($data->foto){
            $data->foto = json_decode($data->foto);
        }
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        $headerHTML = '<div style="text-align: center; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">RAHASIA</div>';
        $mpdf->SetHTMLHeader($headerHTML);

        $footerHTML = '<div style="text-align: center; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">RAHASIA</div>';
        $mpdf->SetHTMLFooter($footerHTML);


        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->AddPage();
        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.template_laporan.l-in-4", compact(
            'data',
            'status',
            'elicitationResult')));
        
            
        $filename = 'Open_Elicitation_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
}
