<?php

namespace App\Http\Controllers;


use App\DataTables\Interogation\InterogationTargetIdentificationVideoDataTable;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Mpdf\Mpdf;
use App\Models\Documents;
use App\Models\VideoDocuments;
use App\Models\MasterSatker;
use App\Models\VideoAudioDocuments;
use App\Models\VideoAudioDocumentAnalytics;
use App\Models\CaseProgresses;
use App\Models\InterogationRecord;
use App\Models\CaseEventHistoricalUpdates;
use App\Helpers\DataHelper;
use App\Helpers\BodycamDeviceDataHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\InterogationTargetIdentification;
use App\Models\InterogationResultAchievement;
use App\DataTables\Interogation\InterogationTargetIdentificationDataTable;

class InterogationTargetIdentificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InterogationTargetIdentificationDataTable $dataTable)
    {
        //
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();
        return $dataTable->render('backoffice.open.interogation-target-identification.index', compact('satker', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCaseValidInterogationRecord();
        // $interogrecord = DataHelper::getInterogrecord();
        return view('backoffice.open.interogation-target-identification.create', compact('case', 'satker'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            // 'id_interogation_record' => 'required',
            'satker_id' => 'required',
            'case_id' => 'required',
            'hasil_target_identification' => 'required',
            // 'upload_berita_acara' => 'required|mimes:pdf|max:30000'
        ]);
        $user = auth()->user();

        $data = new InterogationTargetIdentification();
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->interogation_record_id = $request->id_interogation_record;
        $data->hasil_target_identification = $request->hasil_target_identification;

        if ($request->hasFile('upload_berita_acara')) {
            $ext_upload_berita_acara = $request->file('upload_berita_acara')->extension();
            $upload_berita_acara = $request->file('upload_berita_acara')
                ->storePubliclyAs(
                    'open/data/interogation',
                    Str::slug('interogationtargetid', '_') . '_' . Str::random() . '.' . $ext_upload_berita_acara,
                    'public'
                );

            $data->hasil_target_identification_path = $upload_berita_acara;

        }

        if ($request->hasFile('upload_video_identifikasi_target')) {
            $ext_upload_berita_acara = $request->file('upload_video_identifikasi_target')->extension();
            $upload_video_identifikasi_target = $request->file('upload_video_identifikasi_target')
                ->storePubliclyAs(
                    'open/data/interogation',
                    Str::slug('interogationidentifikasitargetvideo', '_') . '_' . Str::random() . '.' . $ext_upload_berita_acara,
                    'public'
                );

            $data->hasil_video_target_identification_path = $upload_video_identifikasi_target;

        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {

            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->interogasi_identifikasi_target = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Indentifikasi Target';
            $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 64.68 ? $updateCaseProgresses->percentage : 64.68;
            $updateCaseProgresses->save();

            if ($data->save()) {
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->case_id;
                $cp->action = 'Penambahan Interogasi Indentifikasi Target';
                $cp->created_by = $user->id;
                $cp->save();

                $document_pdf = new Documents;
                $document_pdf->doc_path = $upload_berita_acara;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_target_identification;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();

                $video_data = new VideoDocuments;
                $video_data->relation_id = $data->id_interogation_target_identification;
                $video_data->doc_path = $upload_video_identifikasi_target;
                $video_data->doc_type = "video";
                $video_data->doc_status = "0";
                $video_data->doc_status_remark = "Waiting Analysis";
                $video_data->created_by = $user->id;
                $video_data->save();

                $video_audio_data = new VideoAudioDocuments;
                $video_audio_data->relation_id = $data->id_interogation_target_identification;
                $video_audio_data->doc_path = $upload_video_identifikasi_target;
                $video_audio_data->doc_type = "video_audio";
                $video_audio_data->doc_status = "0";
                $video_audio_data->doc_status_remark = "Waiting Analysis";
                $video_audio_data->created_by = $user->id;
                $video_audio_data->save();



                return redirect()->route('open.data.interogg-target-id.index')->with("success", "Data berhasil ditambah.");
            }
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        } else {

            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->interogasi_identifikasi_target = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Indentifikasi Target';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();



            if ($data->save()) {
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->case_id;
                $cp->action = 'Penambahan Interogasi Indentifikasi Target';
                $cp->created_by = $user->id;
                $cp->save();

                $document_pdf = new Documents;
                $document_pdf->doc_path = $upload_berita_acara;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_target_identification;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();

                $video_data = new VideoDocuments;
                $video_data->relation_id = $data->id_interogation_target_identification;
                $video_data->doc_path = $upload_video_identifikasi_target;
                $video_data->doc_type = "video";
                $video_data->doc_status = "0";
                $video_data->doc_status_remark = "Waiting Analysis";
                $video_data->created_by = $user->id;
                $video_data->save();


                $video_audio_data = new VideoAudioDocuments;
                $video_audio_data->relation_id = $data->id_interogation_target_identification;
                $video_audio_data->doc_path = $upload_video_identifikasi_target;
                $video_audio_data->doc_type = "video_audio";
                $video_audio_data->doc_status = "0";
                $video_audio_data->doc_status_remark = "Waiting Analysis";
                $video_audio_data->created_by = $user->id;
                $video_audio_data->save();

                return redirect()->route('open.data.interogg-target-id.index')->with("success", "Data berhasil ditambah.");
            }
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');

        }




    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id, InterogationTargetIdentificationVideoDataTable $dataTable)
    {
        $data = InterogationTargetIdentification::find($id);
        $summary = Documents::where('relation_id', $id)->first();
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();
        return $dataTable->render('backoffice.open.interogation-target-identification.show', compact(
            'data',
            'summary',
            'bodycam_devices'
        ));
        // return view('backoffice.open.interogation-target-identification.show', compact('data', 'summary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
        $data = InterogationTargetIdentification::find($id);
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCaseValidInterogationRecord($data->satker_id);
        $interogrecord = DataHelper::getInterogrecordByCase($data->case_id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        return view('backoffice.open.interogation-target-identification.edit', compact('data', 'satker', 'case', 'interogrecord'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            // 'id_interogation_record' => 'required',
            // 'satker_id' => 'required',
            'case_id' => 'required',
            'hasil_target_identification' => 'required',
            // 'upload_berita_acara' => 'nullable|mimes:pdf|max:30000'
        ]);

        $user = auth()->user();

        $data = InterogationTargetIdentification::find($id);

        // $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->interogation_record_id = $request->id_interogation_record;
        $data->hasil_target_identification = $request->hasil_target_identification;

        // DOKUMEN
        if ($request->hasFile('upload_berita_acara')) {
            $ext_upload_berita_acara = $request->file('upload_berita_acara')->extension();
            $upload_berita_acara = $request->file('upload_berita_acara')
                ->storePubliclyAs(
                    'open/data/interogation/',
                    Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_upload_berita_acara,
                    'public'
                );

            if (Storage::disk('public')->exists($request->temp_upload_berita_acara)) {
                Storage::disk('public')->delete($request->temp_upload_berita_acara);
            }
            $data->hasil_target_identification_path = $upload_berita_acara;
            $document_pdf = Documents::where('relation_id', $id)->first();
            if ($document_pdf) {
                $document_pdf->doc_path = $upload_berita_acara;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->updated_by = $user->id;
                $document_pdf->update();
            } else {
                $document_pdf = new Documents;
                $document_pdf->doc_path = $upload_berita_acara;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_result_achievement;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();
            }
        } else {
            $upload_berita_acara = $request->temp_upload_berita_acara;

            $data->hasil_target_identification_path = $upload_berita_acara;
        }

        // VIDEO
        if ($request->hasFile('upload_video_identifikasi_target')) {
            $ext_upload_berita_acara = $request->file('upload_video_identifikasi_target')->extension();
            $upload_video_identifikasi_target = $request->file('upload_video_identifikasi_target')
                ->storePubliclyAs(
                    'open/data/interogation/',
                    Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_upload_berita_acara,
                    'public'
                );

           
            $data->hasil_video_target_identification_path = $upload_video_identifikasi_target;
            
            $document_video = new VideoDocuments;
            $document_video->doc_path = $upload_video_identifikasi_target;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $data->id_interogation_target_identification;
            $document_video->created_by = $user->id;
            $document_video->updated_by = $user->id;
            $document_video->save();

            $document_video_audio = new VideoAudioDocuments;
            $document_video_audio->doc_path = $upload_video_identifikasi_target;
            $document_video_audio->doc_type = "video_audio";
            $document_video_audio->doc_status = "0";
            $document_video_audio->doc_status_remark = "Waiting Analysis";
            $document_video_audio->relation_id = $data->id_interogation_target_identification;
            $document_video_audio->created_by = $user->id;
            $document_video_audio->updated_by = $user->id;
            $document_video_audio->save();
            
        } else {
            $upload_berita_acara = $request->temp_upload_berita_acara;

            $data->hasil_target_identification_path = $upload_berita_acara;
        }

        $data->updated_by = $user->id;
        if ($request->submit_type === 'update_and_finish') {

            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->interogasi_identifikasi_target = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Indentifikasi Target';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();


        }


        if ($data->update()) {


            $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Data Interogasi Indentifikasi Target');

            return redirect()->route('open.data.interogg-target-id.index')->with(["success" => "Data berhasil diupdate."]);
        }
        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = InterogationTargetIdentification::find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $log = DataHelper::logUpdateCase($data->case_id, 'Penghapusan Data Interogasi Indentifikasi Target');

        $data->delete();
        InterogationResultAchievement::where('interogation_target_identification_id', $id)->delete();


        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }

    public function downloadFile($path)
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

            $data_interview_hasil = InterogationTargetIdentification::where('id_interogation_target_identification', $id)->first();
            $data_interview_hasil->hasil_video_target_identification_path = $path;
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


    public function downloadAudiotoTextFile($interrog_target_id)
    {

        $interrog_target_id = decrypt($interrog_target_id);
        // return $id_case;
        $data = InterogationTargetIdentification::where('id_interogation_target_identification', $interrog_target_id)->first();
        $satker = MasterSatker::where('kode_satker', $data->satker_id)->first();
        $video_audio_data = VideoAudioDocuments::where('video_audio_documents.relation_id', $data->id_interogation_target_identification)
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
        $mpdf->WriteHTML(view("backoffice.open.interogation-target-identification.pdf", compact(
            'data',
            'satker',
            'video_audio_analytics_data'
        )));


        $filename = 'Open_Interview_Result_Audio_to_Text_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');

       


    }
}
