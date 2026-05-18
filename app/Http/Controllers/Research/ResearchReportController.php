<?php

namespace App\Http\Controllers\Research;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\OpenCase;
use App\Helpers\DataHelper;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\MasterPegawai;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use App\DataTables\Research\ResearchReportDataTable;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;

class ResearchReportController extends Controller
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
    public function index(ResearchReportDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.open.research.report.index', compact('satker', 'users'));
    }

    public function show(Request $request, $id)
    {
        $data = ResearchLaporanInformasiKhusus::find($id);

        return view('backoffice.open.research.report.show', compact('data'));
    }

    public function downloadFile($id_case)
    {
        // $data = OpenCase::find(decrypt($id_case));
        $data = OpenCase::with(
            'researchSuratPerintah',
            'researchSuratPerintah.researchLaporanInformasiKhusus',
            'researchSuratPerintah.researchLaporanInformasiKhusus.researchSaranTindakLanjut',
            'researchSuratPerintah.researchLaporanInformasiKhusus.researchSaranTindakLanjut.researchPotensiAght',
        )->where('id', decrypt($id_case))->first();
        if($data->foto){
            $data->foto = json_decode($data->foto);
        }
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.open.research.report.pdf", compact('data')));

        $filename = 'Open_Research_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    public function downloadFilelapinsusus($id)
    {
        // $data = OpenCase::find(decrypt($id_case));
        // return $id_case;
    
        $data = ResearchLaporanInformasiKhusus::where('id',$id)->first();
        $satker = MasterSatker::where('kode_satker', $data->satker_id)->first();
        $penandatangan = MasterPegawai::where('nip', $data->nip)->first();

        if($data->file_laporan_informasi_khusus){
            return Storage::disk('public')->download($data->file_laporan_informasi_khusus);
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
        $mpdf->AddPage();
        $mpdf->WriteHTML(view("backoffice.template_laporan.l-in-2", compact('data', 'satker', 'penandatangan')));
        $filename = 'Open_Research_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
}
