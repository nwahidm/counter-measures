<?php

namespace App\Http\Controllers\Tapping;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Documents;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\VideoDocuments;
use App\Models\CaseCloseProgresses;
use App\Models\VideoAudioDocuments;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use App\Helpers\BodycamDeviceDataHelper;
use App\Models\VideoAudioDocumentAnalytics;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\Models\Tapping\TappingIntelligentSignal;
use App\Models\Tapping\TappingResultAchievement;
use App\DataTables\Tapping\TappingIntelligentSignalDataTable;
use App\DataTables\Tapping\TappingIntelligentSignalShowDataTable;

class TappingIntelligentSignalController extends Controller
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
    public function index(TappingIntelligentSignalDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.tapping.intelligent_signal.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCloseCase();
        $eldev = DataHelper::getTappingElectronicDevice();

        return view('backoffice.close.tapping.intelligent_signal.create', compact('satker', 'users', 'case', 'eldev'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'tapping_electronic_device_data_id' => 'nullable|string|max:128',
            // 'tanggal_penyadapan' => 'required|date',
            'jenis_sinyal' => 'required|string|max:128',
            'deskripsi_hasil' => 'required|string|max:1280000',
            'dokumen_upload' => 'nullable|file|mimes:pdf|max:2048000',
            'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = auth()->user();

        $data = new TappingIntelligentSignal;
        $data->case_id = $request->id_case;
        $data->tapping_electronic_device_data_id = $request->tapping_electronic_device_data_id;
        $data->tanggal_penyadapan = $request->tanggal_penyadapan;
        $data->jenis_sinyal = $request->jenis_sinyal;
        $data->deskripsi_hasil = $request->deskripsi_hasil;

        if ($request->hasFile('dokumen_upload')) {
            $ext_dokumen_upload = $request->file('dokumen_upload')->extension();
            $dokumen_upload = $request->file('dokumen_upload')
                ->storePubliclyAs(
                    'close/tapping/intelligent_signal/dokumen_upload',
                    Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.' . $ext_dokumen_upload,
                    'public'
                );

            $data->dokumen_upload = $dokumen_upload;
        }

        if ($request->hasFile('video_upload')) {
            $ext_video_upload = $request->file('video_upload')->extension();
            $video_upload = $request->file('video_upload')
                ->storePubliclyAs(
                    'close/tapping/intelligent_signal/video_upload',
                    Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.' . $ext_video_upload,
                    'public'
                );

            $data->video_upload = $video_upload;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        $data->satker_id = $user->id_satker;

        if ($request->submit_type == 'save') {


            $close_case_progress = CaseCloseProgresses::where('case_id', $data->tappingElectronicDevice->case->id)->first();
            $close_case_progress->update([
                'tapping_data_sinyal_intelijen' => "1",
                'status' => $close_case_progress->percentage > 95 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 95 ? $close_case_progress->substatus : 'Input Penyadapan Data Sinyal Intelijen',
                'percentage' => $close_case_progress->percentage > 95 ? $close_case_progress->percentage : 95,
                'updated_by' => $user->id
            ]);

        } else {
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->tappingElectronicDevice->case->id)->first();
            $close_case_progress->update([
                'tapping_data_sinyal_intelijen' => "1",
                'status' => $close_case_progress->percentage > 95 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 95 ? $close_case_progress->substatus : 'Input Penyadapan Data Sinyal Intelijen',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);

        }

        if ($data->save()) {
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->tappingElectronicDevice->case->id;
            $data_case_close_historical_update->action = "Penambahan Penyadapan Data Sinyal Intelijen";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            if ($request->hasFile('dokumen_upload')) {
                $document_pdf = new Documents;
                $document_pdf->doc_path = $dokumen_upload;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_tapping_intelligent_signal;
                ;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();

                // DataHelper::insertDocument($data->id_tapping_electronic_device, $data->dokumen_upload);
            }

            if ($request->hasFile('video_upload')) {

                // DataHelper::insertVideo($data->id_tapping_electronic_device, $data->video_upload);

                $video_data = new VideoDocuments;
                $video_data->relation_id = $data->id_tapping_intelligent_signal;
                $video_data->doc_path = $video_upload;
                $video_data->doc_type = "video";
                $video_data->doc_status = "0";
                $video_data->doc_status_remark = "Waiting Analysis";
                $video_data->updated_by = $user->id;
                $video_data->save();

                $video_audio_data = new VideoAudioDocuments;
                $video_audio_data->relation_id = $data->id_tapping_intelligent_signal;
                $video_audio_data->doc_path = $video_upload;
                $video_audio_data->doc_type = "video_audio";
                $video_audio_data->doc_status = "0";
                $video_audio_data->doc_status_remark = "Waiting Analysis";
                $video_audio_data->created_by = $user->id;
                $video_audio_data->save();
            }




            // update progress

            // DataHelper::insertDocument($data->id_tapping_intelligent_signal, $data->dokumen_upload, null, $user->id);
            // DataHelper::insertVideo($data->id_tapping_intelligent_signal, $data->video_upload, null, $user->id);

            return redirect()->route('close.tapping.intelligent_signal.index')->with("success", "Data berhasil ditambah.");
        }

        // if ($data->dokumen_upload && Storage::disk('public')->exists($data->dokumen_upload)) {
        //     Storage::disk('public')->delete($data->dokumen_upload);
        // }

        // if ($data->video_upload && Storage::disk('public')->exists($data->video_upload)) {
        //     Storage::disk('public')->delete($data->video_upload);
        // }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id, TappingIntelligentSignalShowDataTable $dataTable)
    {
        $data = TappingIntelligentSignal::find($id);
        $document_pdf_data = Documents::where('relation_id', $data->id_tapping_intelligent_signal)->first();
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();

        return $dataTable->render(
            'backoffice.close.tapping.intelligent_signal.show',
            compact(
                'data',
                'document_pdf_data',
                'bodycam_devices'
            )
        );

        // return view(
        //     'backoffice.close.tapping.intelligent_signal.show',
        //     compact('data', 'document_pdf_data', 'bodycam_devices')
        // );
    }

    public function edit(Request $request, $id)
    {
        $data = TappingIntelligentSignal::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();


        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        $eldev = DataHelper::getTappingElectronicDeviceByCase($data->case_id);

        return view('backoffice.close.tapping.intelligent_signal.edit', compact('data', 'users', 'satker', 'case', 'eldev'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'tapping_electronic_device_data_id' => 'nullable|string|max:128',
            // 'tanggal_penyadapan' => 'required|date',
            'jenis_sinyal' => 'required|string|max:128',
            'deskripsi_hasil' => 'required|string|max:1280000',
            // 'dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = auth()->user();

        $data = TappingIntelligentSignal::find($id);
        $data->case_id = $request->id_case;
        $data->tapping_electronic_device_data_id = $request->tapping_electronic_device_data_id;
        $data->tanggal_penyadapan = $request->tanggal_penyadapan;
        $data->jenis_sinyal = $request->jenis_sinyal;
        $data->deskripsi_hasil = $request->deskripsi_hasil;

        if ($request->hasFile('dokumen_upload')) {
            $ext_dokumen_upload = $request->file('dokumen_upload')->extension();
            $dokumen_upload = $request->file('dokumen_upload')
                ->storePubliclyAs(
                    'close/tapping/intelligent_signal/dokumen_upload',
                    Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.' . $ext_dokumen_upload,
                    'public'
                );

            // if ($request->temp_dokumen_upload && Storage::disk('public')->exists($request->temp_dokumen_upload)) {
            //     Storage::disk('public')->delete($request->temp_dokumen_upload);
            // }

            $data->dokumen_upload = $dokumen_upload;

            $document_pdf = new Documents;
            $document_pdf->doc_path = $dokumen_upload;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->relation_id = $id;
            $document_pdf->created_by = $user->id;
            $document_pdf->updated_by = $user->id;
            $document_pdf->save();
        } else {
            $dokumen_upload = $request->temp_dokumen_upload;

            $data->dokumen_upload = $dokumen_upload;
        }

        if ($request->hasFile('video_upload')) {
            $ext_video_upload = $request->file('video_upload')->extension();
            $video_upload = $request->file('video_upload')
                ->storePubliclyAs(
                    'close/tapping/intelligent_signal/video_upload',
                    Str::slug('tapping intelligent signal', '_') . '_' . Str::random() . '.' . $ext_video_upload,
                    'public'
                );

            // if ($request->temp_video_upload && Storage::disk('public')->exists($request->temp_video_upload)) {
            //     Storage::disk('public')->delete($request->temp_video_upload);
            // }

            $data->video_upload = $video_upload;

            $video_data = new VideoDocuments;
            $video_data->relation_id = $id;
            $video_data->doc_path = $video_upload;
            $video_data->doc_type = "video";
            $video_data->doc_status = "0";
            $video_data->doc_status_remark = "Waiting Analysis";
            $video_data->updated_by = $user->id;
            $video_data->save();

            $video_audio_data = new VideoAudioDocuments;
            $video_audio_data->relation_id = $id;
            $video_audio_data->doc_path = $video_upload;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->updated_by = $user->id;
            $video_audio_data->save();
        } else {
            $video_upload = $request->temp_video_upload;

            $data->video_upload = $video_upload;
        }

        $data->updated_by = $user->id;
        $data->satker_id = $user->id_satker;

        if ($request->submit_type === 'update_and_finish') {

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->tappingElectronicDevice->case->id)->first();
            $close_case_progress->update([
                'tapping_data_sinyal_intelijen' => "1",
                'status' => $close_case_progress->percentage > 95 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 95 ? $close_case_progress->substatus : 'Input Penyadapan Data Sinyal Intelijen',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);
        }


        if ($data->update()) {
            // DataHelper::insertDocument($data->id_tapping_intelligent_signal, $data->dokumen_upload, $request->old_dokumen_upload, $user->id);
            // DataHelper::insertVideo($data->id_tapping_intelligent_signal, $data->video_upload, $request->old_video_upload, $user->id);

            $cp = CaseCloseEventHistoricalUpdates::where('case_id', $data->tappingElectronicDevice->case_id)->first();
            $cp->action = "Perubahan Penyadapan Sinyal Intelijen";
            $cp->updated_by = $user->id;
            $cp->update();

            return redirect()->route('close.tapping.intelligent_signal.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy(Request $request, $id)
    {
        $data = TappingIntelligentSignal::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if ($data->dokumen_upload && Storage::disk('public')->exists($data->dokumen_upload)) {
            Storage::disk('public')->delete($data->dokumen_upload);

            Documents::where('relation_id', $data->id_tapping_intelligent_signal)
                ->where('doc_path', $data->dokumen_upload)
                ->delete();

            $data->dokumen_upload = null;
            $data->update();
        }

        if ($data->video_upload && Storage::disk('public')->exists($data->video_upload)) {
            Storage::disk('public')->delete($data->video_upload);

            VideoDocuments::where('relation_id', $data->id_tapping_intelligent_signal)
                ->where('doc_path', $data->video_upload)
                ->delete();

            $data->video_upload = null;
            $data->update();
        }

        $cp = CaseCloseEventHistoricalUpdates::where('case_id', $data->tappingElectronicDevice->case_id)->first();
        $cp->action = "Penghapusan Penyadapan Sinyal Intelijen";
        $cp->updated_by = $data->created_by;
        $cp->update();

        $data->delete();
        TappingResultAchievement::where('tapping_intelligent_signal_data_id', $id)->delete();

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

    public function uploadVideo1(Request $request)
    {
        $path = $request->file('path'); // Mengambil file video dari FormData

        // Mendapatkan id dari request
        $id = $request->input('id');


        if ($path) {
            $timestamp = time();
            // $filename = 'intelligent_signal_' . $timestamp . '.mp4';
            // $path = 'close/tapping/intelligent-signal/intelligent_video_upload/' . $filename;
            // Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

            $data_interview_hasil = TappingIntelligentSignal::where('id_tapping_intelligent_signal', $id)->first();
            $data_interview_hasil->video_upload = $path;
            $data_interview_hasil->update();

            $document_video = new VideoDocuments;
            $document_video->doc_path = $path;
            $document_video->doc_status = "0";
            $document_video->doc_type = "video";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();


            $document_video = new VideoDocuments;
            $document_video->doc_path = $path;
            $document_video->doc_status = "0";
            $document_video->doc_type = "video";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();



            return response()->json(['success' => true, 'path' => $path]);
        }

        return response()->json(['success' => false, 'message' => 'No video data uploaded']);
    }

    public function downloadAudiotoTextFile($interview_result_id)
    {

        $interview_result_id = decrypt($interview_result_id);
        // return $id_case;
        
        $data = TappingIntelligentSignal::where('id_tapping_intelligent_signal', $interview_result_id)->first();
        $satker = MasterSatker::where('kode_satker', $data->satker_id)->first();
        
        $video_audio_data = VideoAudioDocuments::where('video_audio_documents.relation_id', $data->id_tapping_intelligent_signal)
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
        $mpdf->WriteHTML(view("backoffice.close.tapping.intelligent_signal.pdf", compact(
            'data',
            'satker',
            'video_audio_analytics_data'
        )));


        $filename = 'Open_Interview_Result_Audio_to_Text_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');



    }
}
