<?php

namespace App\Http\Controllers\Interview;

use App\DataTables\Interview\InterviewResultVideoShowDataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Mpdf\Mpdf;
use App\Models\User;
use App\Models\Documents;
use App\Models\VideoAudioDocuments;
use App\Models\VideoAudioDocumentAnalytics;
use App\Helpers\DataHelper;
use App\Helpers\InterviewDataHelper;
use App\Helpers\BodycamDeviceDataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Interview\InterviewHasil;
use App\DataTables\Interview\InterviewHasilDataTable;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\VideoDocuments;
use App\Models\Interview\InterviewSaranTL;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use DOMDocument;

class InterviewHasilController extends Controller
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
    public function index(InterviewHasilDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.open.interview.hasil.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = InterviewDataHelper::getCloseCaseByResearchReport();
        $jadwal = InterviewDataHelper::getInterviewSchedule();

        return view('backoffice.open.interview.hasil.create', compact('satker', 'users', 'case', 'jadwal'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            // 'interview_scheduler_id' => 'nullable|string|max:128',
            //'hasil_interview' => 'required|date',
            //'video_interview' => 'required|string|max:128',
            'upload_dokumen_wawancara' => 'required|file|mimes:pdf|max:2048',
            'upload_video_wawancara' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = auth()->user();

        $data = new InterviewHasil;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->interview_scheduler_id = $request->interview_scheduler_id;
        $data->keterangan = $request->keterangan;
        $data->upload_dokumen_wawancara = $request->upload_dokumen_wawancara;
        $data->upload_video_wawancara = $request->upload_video_wawancara;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->hasFile('upload_dokumen_wawancara')) {
            $ext_upload_dokumen_wawancara = $request->file('upload_dokumen_wawancara')->extension();
            $upload_dokumen_wawancara1 = $request->file('upload_dokumen_wawancara')
                ->storePubliclyAs(
                    'open/interview/hasil/upload_dokumen_wawancara',
                    Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $ext_upload_dokumen_wawancara,
                    'public'
                );

            $data->upload_dokumen_wawancara = $upload_dokumen_wawancara1;


        }

        if ($request->hasFile('upload_video_wawancara')) {
            $ext_upload_video_wawancara = $request->file('upload_video_wawancara')->extension();
            $upload_video_wawancara = $request->file('upload_video_wawancara')
                ->storePubliclyAs(
                    'open/interview/hasil/upload_video_wawancara',
                    Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $ext_upload_video_wawancara,
                    'public'
                );

            $data->upload_video_wawancara = $upload_video_wawancara;

        }



        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $request->id_case)->first();
                $op->wawancara_hasil = 1;
                $op->status = $op->percentage > 52.92 ? $op->status : "Wawancara";
                $op->substatus = $op->percentage > 52.92 ? $op->substatus : "Input Hasil Wawancara";
                $op->percentage = $op->percentage > 52.92 ? $op->percentage : 52.92;
                $op->updated_by = $user->id;
                $op->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Wawancara Hasil';
                $cp->created_by = $user->id;
                $cp->save();

                if ($request->hasFile('upload_dokumen_wawancara')) {

                    DataHelper::insertDocument($data->id_interview_result, $data->upload_dokumen_wawancara);
                }

                if ($request->hasFile('upload_video_wawancara')) {

                    DataHelper::insertVideo($data->id_interview_result, $data->upload_video_wawancara);

                    $video_audio_data = new VideoAudioDocuments;
                    $video_audio_data->relation_id = $data->id_interview_result;
                    $video_audio_data->doc_path = $upload_video_wawancara;
                    $video_audio_data->doc_type = "video_audio";
                    $video_audio_data->doc_status = "0";
                    $video_audio_data->doc_status_remark = "Waiting Analysis";
                    $video_audio_data->created_by = $user->id;
                    $video_audio_data->save();
                }


                return redirect()->route('open.interview.hasil.index')->with("success", "Data berhasil ditambah.");
            }

            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        } else {
            if ($data->save()) {

                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->wawancara_hasil = 1;
                $updateCaseProgresses->status = 'Wawancara';
                $updateCaseProgresses->substatus = 'Penambahan Hasil Wawancara';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->interviewJadwal->case_id;
                $cp->action = 'Penambahan Wawancara Hasil';
                $cp->created_by = $user->id;
                $cp->save();

                if ($request->hasFile('upload_dokumen_wawancara')) {

                    DataHelper::insertDocument($data->id_interview_result, $data->upload_dokumen_wawancara);
                }

                if ($request->hasFile('upload_video_wawancara')) {

                    DataHelper::insertVideo($data->id_interview_result, $data->upload_video_wawancara);

                    $video_audio_data = new VideoAudioDocuments;
                    $video_audio_data->relation_id = $data->id_interview_result;
                    $video_audio_data->doc_path = $upload_video_wawancara;
                    $video_audio_data->doc_type = "video_audio";
                    $video_audio_data->doc_status = "0";
                    $video_audio_data->doc_status_remark = "Waiting Analysis";
                    $video_audio_data->created_by = $user->id;
                    $video_audio_data->save();
                }


                return redirect()->route('open.interview.hasil.index')->with("success", "Data berhasil ditambah.");
            }

            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }

    }

    public function show(Request $request, $id, InterviewResultVideoShowDataTable $dataTable)
    {
        $data = InterviewHasil::find($id);
        $document_pdf_data = Documents::where('relation_id', $data->id_interview_result)->first();
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();
        return $dataTable->render('backoffice.open.interview.hasil.show', compact(
            'data',
            'document_pdf_data',
            'bodycam_devices'
        ));
        // return view('backoffice.open.interview.hasil.show', compact('data', 'document_pdf_data'));
    }

    public function edit(Request $request, $id)
    {
        $satker = DataHelper::getSatker();
        $data = InterviewHasil::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = InterviewDataHelper::getCloseCaseByResearchReport();
        $jadwal = InterviewDataHelper::getInterviewScheduleByCase($data->case_id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.open.interview.hasil.edit', compact('data', 'users', 'case', 'jadwal', 'satker'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            // 'interview_scheduler_id' => 'nullable|string|max:128',
            // 'keterangan' => 'required',
            //'video_interview' => 'required|string|max:128',
            'upload_dokumen_wawancara' => 'nullable|file|mimes:pdf|max:2048',
            'upload_video_wawancara' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = auth()->user();

        $data = InterviewHasil::find($id);
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->interview_scheduler_id = $request->interview_scheduler_id;
        $data->keterangan = $request->keterangan;
        $data->upload_dokumen_wawancara = $request->upload_dokumen_wawancara;
        $data->upload_video_wawancara = $request->upload_video_wawancara;

        if ($request->hasFile('upload_dokumen_wawancara')) {
            $ext_upload_dokumen_wawancara = $request->file('upload_dokumen_wawancara')->extension();
            $upload_dokumen_wawancara = $request->file('upload_dokumen_wawancara')
                ->storePubliclyAs(
                    'open/interview/hasil/upload_dokumen_wawancara',
                    Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $ext_upload_dokumen_wawancara,
                    'public'
                );

            if ($request->temp_upload_dokumen_wawancara && Storage::disk('public')->exists($request->temp_upload_dokumen_wawancara)) {
                Storage::disk('public')->delete($request->temp_upload_dokumen_wawancara);
            }

            $data->upload_dokumen_wawancara = $upload_dokumen_wawancara;

            $document_pdf = new Documents;
            $document_pdf->doc_path = $upload_dokumen_wawancara;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->relation_id = $data->id_interogation_result_achievement;
            $document_pdf->created_by = $user->id;
            $document_pdf->updated_by = $user->id;
            $document_pdf->save();
            // $document_pdf = Documents::where('relation_id', $id)->first();
            // if ($document_pdf) {
            //     $document_pdf->doc_path = $upload_dokumen_wawancara;
            //     $document_pdf->doc_type = "pdf";
            //     $document_pdf->doc_status = "0";
            //     $document_pdf->doc_status_remark = "Waiting Analysis";
            //     $document_pdf->updated_by = $user->id;
            //     $document_pdf->update();
            // } else {

            // }


            // DataHelper::insertDocument($data->id, $data->upload_dokumen_wawancara);
        } else {
            $upload_dokumen_wawancara = $request->temp_upload_dokumen_wawancara;

            $data->upload_dokumen_wawancara = $upload_dokumen_wawancara;
        }

        if ($request->hasFile('upload_video_wawancara')) {
            $ext_upload_video_wawancara = $request->file('upload_video_wawancara')->extension();
            $upload_video_wawancara = $request->file('upload_video_wawancara')
                ->storePubliclyAs(
                    'open/interview/hasil/upload_video_wawancara',
                    Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $ext_upload_video_wawancara,
                    'public'
                );

            if ($request->temp_upload_video_wawancara && Storage::disk('public')->exists($request->temp_upload_video_wawancara)) {
                Storage::disk('public')->delete($request->temp_upload_video_wawancara);
            }

            $data->upload_video_wawancara = $upload_video_wawancara;

            DataHelper::insertVideo($data->id, $data->upload_video_wawancara);

            $video_audio_data = new VideoAudioDocuments;
            $video_audio_data->relation_id = $data->id_interview_result;
            $video_audio_data->doc_path = $upload_video_wawancara;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->updated_by = $user->id;
            $video_audio_data->save();
        } else {
            $upload_video_wawancara = $request->temp_upload_video_wawancara;

            $data->upload_video_wawancara = $upload_video_wawancara;

        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->wawancara_hasil = 1;
            $updateCaseProgresses->status = 'Wawancara';
            $updateCaseProgresses->substatus = 'Penambahan Hasil Wawancara';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }

        if ($data->update()) {
            $cp = CaseEventHistoricalUpdates::where('case_id', $request->id_case)->first();
            $cp->action = 'Perubahan Wawancara Hasil';
            $cp->updated_by = $user->id;
            $cp->update();

            return redirect()->route('open.interview.hasil.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy(Request $request, $id)
    {
        $data = InterviewHasil::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if ($data->upload_dokumen_wawancara && Storage::disk('public')->exists($data->upload_dokumen_wawancara)) {
            $data->upload_dokumen_wawancara = null;
            $data->update();

            Storage::disk('public')->delete($data->upload_dokumen_wawancara);
        }

        if ($data->upload_video_wawancara && Storage::disk('public')->exists($data->upload_video_wawancara)) {
            $data->upload_video_wawancara = null;
            $data->update();

            Storage::disk('public')->delete($data->upload_video_wawancara);
        }

        $cp = CaseEventHistoricalUpdates::where('case_id', $data->interviewJadwal->case_id)->first();
        $cp->action = 'Penghapusan Wawancara Hasil';
        $cp->updated_by = auth()->user()->id;
        $cp->update();
        
        $data->delete();
        InterviewSaranTL::where('interview_result_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadDokumen($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

    public function downloadVideo($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

    public function uploadVideo(Request $request)
    {
        // $video = $request->file('video'); // Mengambil file video dari FormData

        // Mendapatkan id dari request
        $id = $request->input('id');
        $path = $request->input('path');

        if ($path) {
            // $filename = 'interview_hasil_' . time() . '.mp4';
            // $path = 'open/interview/hasil/upload_video_wawancara/' . $filename;

            $data_interview_hasil = InterviewHasil::where('id_interview_result', $id)->first();
            $data_interview_hasil->upload_video_wawancara = $path;
            $data_interview_hasil->update();


            $document_video = new VideoDocuments;
            $document_video->doc_path = $path;
            $document_video->doc_status = "0";
            $document_video->doc_type = "video";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();


            $document_video = new VideoAudioDocuments;
            $document_video->doc_path = $path;
            $document_video->doc_status = "0";
            $document_video->doc_type = "video_audio";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();

            // Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

            return response()->json(['success' => true, 'path' => $path]);
        }

        return response()->json(['success' => false, 'message' => 'No video data uploaded']);
    }

    public function downloadAudiotoTextFile($interview_result_id)
    {

        $interview_result_id = decrypt($interview_result_id);
        // return $id_case;
        $data = InterviewHasil::where('interview_hasil.id_interview_result', $interview_result_id)->first();
        $satker = MasterSatker::where('kode_satker', $data->satker_id)->first();
        $video_audio_data = VideoAudioDocuments::where('video_audio_documents.relation_id', $data->id_interview_result)
            ->orderBy('created_at', 'desc')
            ->first();
        $video_audio_analytics_data = VideoAudioDocumentAnalytics::where('video_audio_document_analytics.video_audio_doc_id', $video_audio_data->id)->get();

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);


        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.open.interview.hasil.pdf", compact(
            'data',
            'satker',
            'video_audio_analytics_data'
        )));


        $filename = 'Open_Interview_Result_Audio_to_Text_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');

        // Decrypt the interview result ID
        // $interview_result_id = decrypt($interview_result_id);

        // // Fetch the interview result data
        // $data = InterviewHasil::where('interview_hasil.id_interview_result', $interview_result_id)->first();
        // $satker = MasterSatker::where('kode_satker', $data->satker_id)->first();
        // $video_audio_data = VideoAudioDocuments::where('video_audio_documents.relation_id', $data->id_interview_result)
        //     ->orderBy('created_at', 'desc')
        //     ->first();
        // $video_audio_analytics_data = VideoAudioDocumentAnalytics::where('video_audio_document_analytics.video_audio_doc_id', $video_audio_data->id)->get();

        // // Generate HTML content from a view
        // $html = view("backoffice.open.interview.hasil.docx", compact('data', 'satker', 'video_audio_analytics_data'))->render();

        // // Initialize PHPWord
        // $phpWord = new PhpWord();
        // $section = $phpWord->addSection();
        // // Strip problematic tags (except basic ones that PHPWord supports)
        // $html = strip_tags($html, '<p><b><i><u><strong><em><h1><h2><h3><ul><ol><li><table><tr><td>');
        // $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);

        // // Add HTML content to Word document
        // Html::addHtml($section, $html, false, false);

        // // Define the filename
        // $filename = 'Open_Interview_Result_Audio_to_Text_Report-' . Date::now('Asia/Jakarta')->timestamp . '.docx';

        // // Set headers to download the Word document
        // header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        // header("Content-Disposition: attachment; filename={$filename}");
        // header('Cache-Control: max-age=0');

        // // Save the Word document and output it to the browser
        // $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        // $objWriter->save("php://output");

        // // Stop further script execution
        // exit;


    }

}
