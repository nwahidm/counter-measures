<?php

namespace App\Http\Controllers\Interview;

use App\DataTables\Interview\InterviewReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\OpenCase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Models\Interview\InterviewJadwal;
use App\Models\Interview\InterviewHasil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\View;
use DOMDocument;

class InterviewReportController extends Controller
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
    public function index(InterviewReportDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.open.interview.report.index', compact('satker', 'users'));
    }

    public function show(Request $request, $id)
    {
        $data = InterviewJadwal::find($id);

        return view('backoffice.open.interview.report.show', compact('data'));
    }

    public function downloadReport($id_case)
    {
        $data = OpenCase::find(decrypt($id_case));
        if($data->foto){
            $data->foto = json_decode($data->foto);
        }

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        $imgBase64 = $data->target_photo ? DataHelper::imgToBase64($data->target_photo) : null;

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.open.interview.report.pdf", compact('data'), ['targetPhotoBase64' => $imgBase64]));

        $filename = 'Open_Interview_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    public function downloadReportwawancara($id_case)
    {
        // return $id_case;
        $data = InterviewHasil::join('open_case','open_case.id','interview_hasil.case_id')
        ->join('interview_jadwal','open_case.id','interview_jadwal.case_id')
        // ->join('interview_saran_dan_tindak_lanjut','interview_hasil.id_interview_result','interview_saran_dan_tindak_lanjut.interview_result_id')
        ->join('master_satker','open_case.id_satker','master_satker.id_satker')
        ->where('interview_hasil.id_interview_result', $id_case)
        ->first();

        // return $data = OpenCase::join('interview_jadwal','open_case.id','interview_jadwal.case_id')
        //             ->join('interview_hasil','interview_jadwal.id_interview_scheduler','interview_hasil.interview_scheduler_id')
        //             ->join('interview_saran_dan_tindak_lanjut','interview_hasil.id_interview_result','interview_saran_dan_tindak_lanjut.interview_result_id')
        //             ->join('master_satker','open_case.id_satker','master_satker.id_satker')
        //             ->where('interview_hasil.id_interview_result', $id_case)
        //             ->first();
        if($data->foto){
            $data->foto = json_decode($data->foto);
        }

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);
        
        $imgBase64 = $data->target_photo ? DataHelper::imgToBase64($data->target_photo) : null;

        $headerHTML = '<div style="text-align: center; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">RAHASIA</div>';
        $mpdf->SetHTMLHeader($headerHTML);

        $footerHTML = '<div style="text-align: center; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">RAHASIA</div>';
        $mpdf->SetHTMLFooter($footerHTML);


        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->AddPage();
        $mpdf->WriteHTML(view("backoffice.template_laporan.in-11", compact('data'), ['targetPhotoBase64' => $imgBase64]));
        

        $filename = 'Open_Interview_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    public function downloadReportwawancaraword($id_case)
    {
        // // return $id_case;
        //  // Ambil data dari database
        //     $data = InterviewHasil::join('open_case', 'open_case.id', 'interview_hasil.case_id')
        //     ->join('interview_jadwal', 'open_case.id', 'interview_jadwal.case_id')
        //     ->join('master_satker', 'open_case.id_satker', 'master_satker.id_satker')
        //     ->where('interview_hasil.id_interview_result', $id_case)
        //     ->first();

        // // Render template Blade ke dalam HTML
        // $html = View::make('backoffice.template_laporan.in-11-word', compact('data'))->render();

        // // Bersihkan dan validasi HTML menggunakan DOMDocument
        // $doc = new DOMDocument();

        // // Mengatasi masalah dengan menambahkan doctype dan pengaturan encoding yang benar
        // @$doc->loadHTML(mb_convert_encoding('<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>' . $html . '</body></html>', 'HTML-ENTITIES', 'UTF-8'));

        // // Konversi kembali ke HTML String yang valid
        // $cleanHtml = $doc->saveHTML();

        // // Buat instance PhpWord
        // $phpWord = new PhpWord();
        // $section = $phpWord->addSection();

        // // Tambahkan konten dari HTML yang sudah dibersihkan
        // \PhpOffice\PhpWord\Shared\Html::addHtml($section, $cleanHtml, false, false);

        // // Simpan ke file Word
        // $fileName = 'Laporan.docx';
        // $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        // $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        // $objWriter->save($tempFile);

        // // Unduh file
        // return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);

       // Ambil data dari database
            $data = InterviewHasil::join('open_case', 'open_case.id', 'interview_hasil.case_id')
            ->join('interview_jadwal', 'open_case.id', 'interview_jadwal.case_id')
            ->join('master_satker', 'open_case.id_satker', 'master_satker.id_satker')
            ->where('interview_hasil.id_interview_result', $id_case)
            ->first();

        // Render template Blade ke dalam HTML
        $html = View::make('backoffice.template_laporan.in-11-word', compact('data'))->render();

        // Periksa dan konversi HTML menjadi entitas yang valid
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        // Inisialisasi DOMDocument
        $doc = new DOMDocument();

        // Atur untuk mengabaikan kesalahan parsing HTML
        libxml_use_internal_errors(true);

        // Load HTML dan periksa apakah berhasil
        if (!@$doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
            // Jika gagal, kembalikan pesan kesalahan
            return response()->json(['error' => 'Failed to load HTML. Check for invalid characters or markup issues.'], 500);
        }

        // Konversi kembali ke HTML String yang valid
        $cleanHtml = $doc->saveHTML();

        // Buat instance PhpWord
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Tambahkan konten dari HTML yang sudah dibersihkan
        try {
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $cleanHtml);
        } catch (\Exception $e) {
            // Jika gagal menambahkan konten HTML, tangani kesalahan
            return response()->json(['error' => 'Failed to add HTML content to Word document: ' . $e->getMessage()], 500);
        }

        // Simpan ke file Word
        $fileName = 'Laporan.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        // Unduh file
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
