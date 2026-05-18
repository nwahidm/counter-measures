<?php

namespace App\Http\Controllers\Tapping;

use App\Models\Tapping\TappingIntelligentSignal;
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
use App\Models\Tapping\TappingElectronicDevice;
use App\DataTables\Tapping\TappingElectronicDeviceDataTable;
use App\DataTables\Tapping\TappingElectronicDeviceShowDataTable;
use App\Models\Tapping\TappingResultAchievement;

class TappingElectronicDeviceController extends Controller
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
    public function index(TappingElectronicDeviceDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.tapping.electronic_device.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCloseCase();
        $agama = DataHelper::getListAgama();

        return view('backoffice.close.tapping.electronic_device.create', compact('satker', 'users', 'case', 'agama'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'tanggal_penyadapan' => 'required|date',
            'sumber_data' => 'required|string|max:128',
            'metode_penyadapan' => 'nullable|string|max:1280000',
            'deskripsi_hasil' => 'nullable|string|max:1280000',
            'dokumen_upload' => 'nullable|file|mimes:pdf|max:20480',
            'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = auth()->user();

        $data = new TappingElectronicDevice;
        $data->case_id = $request->id_case;
        $data->tanggal_penyadapan = $request->tanggal_penyadapan;
        $data->sumber_data = $request->sumber_data;
        $data->metode_penyadapan = $request->metode_penyadapan;
        $data->deskripsi_hasil = $request->deskripsi_hasil;

        if ($request->hasFile('dokumen_upload')) {
            $ext_dokumen_upload = $request->file('dokumen_upload')->extension();
            $dokumen_upload = $request->file('dokumen_upload')
                ->storePubliclyAs(
                    'close/tapping/electronic_device/dokumen_upload',
                    Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.' . $ext_dokumen_upload,
                    'public'
                );

            $data->dokumen_upload = $dokumen_upload;


        }

        if ($request->hasFile('video_upload')) {
            $ext_video_upload = $request->file('video_upload')->extension();
            $video_upload = $request->file('video_upload')
                ->storePubliclyAs(
                    'close/tapping/electronic_device/video_upload',
                    Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.' . $ext_video_upload,
                    'public'
                );

            $data->video_upload = $video_upload;


        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        $data->satker_id = $request->id_satker;

        if ($request->submit_type === 'save') {


            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'tapping_data_penyelidikan_komunikasi_elektronik' => "1",
                'status' => $close_case_progress->percentage > 90.5 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 90.5 ? $close_case_progress->substatus : 'Input Penyadapan Perangkat Elektronik',
                'percentage' => $close_case_progress->percentage > 90.5 ? $close_case_progress->percentage : 90.5,
                'updated_by' => $user->id
            ]);
        } else {
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'tapping_data_penyelidikan_komunikasi_elektronik' => "1",
                'status' => $close_case_progress->percentage > 90.5 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 90.5 ? $close_case_progress->substatus : 'Input Penyadapan Perangkat Elektronik',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);
        }

        if ($data->save()) {
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Penyadapan Perangkat Elektronik";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            // update progress

            if ($request->hasFile('dokumen_upload')) {
                $document_pdf = new Documents;
                $document_pdf->doc_path = $dokumen_upload;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_tapping_electronic_device;;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();

                // DataHelper::insertDocument($data->id_tapping_electronic_device, $data->dokumen_upload);
            }

            if ($request->hasFile('video_upload')) {

                // DataHelper::insertVideo($data->id_tapping_electronic_device, $data->video_upload);

                $video_data = new VideoDocuments;
                $video_data->relation_id = $data->id_tapping_electronic_device;
                $video_data->doc_path = $video_upload;
                $video_data->doc_type = "video";
                $video_data->doc_status = "0";
                $video_data->doc_status_remark = "Waiting Analysis";
                $video_data->updated_by = $user->id;
                $video_data->save();

                $video_audio_data = new VideoAudioDocuments;
                $video_audio_data->relation_id = $data->id_tapping_electronic_device;
                $video_audio_data->doc_path = $video_upload;
                $video_audio_data->doc_type = "video_audio";
                $video_audio_data->doc_status = "0";
                $video_audio_data->doc_status_remark = "Waiting Analysis";
                $video_audio_data->created_by = $user->id;
                $video_audio_data->save();
            }




            return redirect()->route('close.tapping.electronic_device.index')->with("success", "Data berhasil ditambah.");
        }

        if ($data->dokumen_upload && Storage::disk('public')->exists($data->dokumen_upload)) {
            Storage::disk('public')->delete($data->dokumen_upload);
        }

        if ($data->video_upload && Storage::disk('public')->exists($data->video_upload)) {
            Storage::disk('public')->delete($data->video_upload);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id, TappingElectronicDeviceShowDataTable $dataTable)
    {
        $data = TappingElectronicDevice::find($id);
        $document_pdf_data = Documents::where('relation_id', $data->id_tapping_electronic_device)->first();
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();


        return $dataTable->render(
            'backoffice.close.tapping.electronic_device.show',
            compact(
                'data',
                'document_pdf_data',
                'bodycam_devices'
            )
        );
        // return view('backoffice.close.tapping.electronic_device.show', compact('data', 'document_pdf_data'));
    }

    public function edit(Request $request, $id)
    {
        $data = TappingElectronicDevice::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.tapping.electronic_device.edit', compact('data', 'users', 'satker', 'case'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            // 'id_satker' => 'required|string|max:128',
            'tanggal_penyadapan' => 'required|date',
            'sumber_data' => 'required|string|max:128',
            'metode_penyadapan' => 'nullable|string|max:1280000',
            'deskripsi_hasil' => 'nullable|string|max:1280000',
            'dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = auth()->user();

        $data = TappingElectronicDevice::find($id);
        $data->case_id = $request->id_case;
        $data->tanggal_penyadapan = $request->tanggal_penyadapan;
        $data->sumber_data = $request->sumber_data;
        $data->metode_penyadapan = $request->metode_penyadapan;
        $data->deskripsi_hasil = $request->deskripsi_hasil;

        if ($request->hasFile('dokumen_upload')) {
            $ext_dokumen_upload = $request->file('dokumen_upload')->extension();
            $dokumen_upload = $request->file('dokumen_upload')
                ->storePubliclyAs(
                    'close/tapping/electronic_device/dokumen_upload',
                    Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.' . $ext_dokumen_upload,
                    'public'
                );

            if ($request->temp_dokumen_upload && Storage::disk('public')->exists($request->temp_dokumen_upload)) {
                Storage::disk('public')->delete($request->temp_dokumen_upload);
            }

            $data->dokumen_upload = $dokumen_upload;

            // $document_pdf = Documents::where('relation_id', $id)->first();
            // if ($document_pdf) {
            //     $document_pdf->doc_path = $dokumen_upload;
            //     $document_pdf->doc_type = "pdf";
            //     $document_pdf->doc_status = "0";
            //     $document_pdf->doc_status_remark = "Waiting Analysis";
            //     $document_pdf->updated_by = $user->id;
            //     $document_pdf->update();
            // } else {
            $document_pdf = new Documents;
            $document_pdf->doc_path = $dokumen_upload;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->relation_id = $id;
            $document_pdf->created_by = $user->id;
            $document_pdf->updated_by = $user->id;
            $document_pdf->save();
            // }
        } else {
            $dokumen_upload = $request->temp_dokumen_upload;

            $data->dokumen_upload = $dokumen_upload;
        }

        if ($request->hasFile('video_upload')) {
            $ext_video_upload = $request->file('video_upload')->extension();
            $video_upload = $request->file('video_upload')
                ->storePubliclyAs(
                    'close/tapping/electronic_device/video_upload',
                    Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.' . $ext_video_upload,
                    'public'
                );

            $data->video_upload = $video_upload;
            // DataHelper::insertVideo($data->id, $data->video_upload);
            // DataHelper::insertDocument($data->id_tapping_electronic_device, $data->dokumen_upload, $request->temp_dokumen_upload, $user->id);
            // DataHelper::insertVideo(
            //     $data->id_tapping_electronic_device,
            //     $data->video_upload,
            //     $request->temp_video_upload,
            //     $user->id
            // );

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
        // $data->satker_id = $request->id_satker;

        if ($request->submit_type === 'update_and_finish') {

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'tapping_data_penyelidikan_komunikasi_elektronik' => "1",
                'status' => $close_case_progress->percentage > 90.5 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 90.5 ? $close_case_progress->substatus : 'Input Penyadapan Perangkat Elektronik',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);
        }


        if ($data->update()) {

            $cp = CaseCloseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
            $cp->action = "Perubahan Penyadapan Perangkat Elektronik";
            $cp->updated_by = $user->id;
            $cp->update();

            return redirect()->route('close.tapping.electronic_device.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy(Request $request, $id)
    {
        $data = TappingElectronicDevice::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if ($data->dokumen_upload && Storage::disk('public')->exists($data->dokumen_upload)) {
            Storage::disk('public')->delete($data->dokumen_upload);

            Documents::where('relation_id', $data->id_tapping_electronic_device)
                ->where('doc_path', $data->dokumen_upload)
                ->delete();

            $data->dokumen_upload = null;
            $data->update();
        }

        if ($data->video_upload && Storage::disk('public')->exists($data->video_upload)) {
            Storage::disk('public')->delete($data->video_upload);

            VideoDocuments::where('relation_id', $data->id_tapping_electronic_device)
                ->where('doc_path', $data->video_upload)
                ->delete();

            $data->video_upload = null;
            $data->update();
        }

        $data->delete();
        TappingIntelligentSignal::where('tapping_electronic_device_data_id', $id)->delete();
        TappingResultAchievement::where('tapping_electronic_device_data_id', $id)->delete();

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
        $path = $request->file('path]'); // Mengambil file video dari FormData

        // Mendapatkan id dari request
        $id = $request->input('id');


        if ($path) {
            // $filename = 'electronic_device' . time() . '.mp4';
            // $path = 'close/tapping/electronic-device/electronic_device_video_upload/' . $filename;

            $data_interview_hasil = TappingElectronicDevice::where('id_tapping_electronic_device', $id)->first();
            $data_interview_hasil->video_upload = $path;
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

        $data = TappingElectronicDevice::where('id_tapping_electronic_device', $interview_result_id)->first();
        $satker = MasterSatker::where('kode_satker', $data->satker_id)->first();
        $video_audio_data = VideoAudioDocuments::where('video_audio_documents.relation_id', $data->id_tapping_electronic_device)
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
        $mpdf->WriteHTML(view("backoffice.close.tapping.electronic_device.pdf", compact(
            'data',
            'satker',
            'video_audio_analytics_data'
        )));


        $filename = 'Open_Interview_Result_Audio_to_Text_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');



    }

}
