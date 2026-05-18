<?php

namespace App\Http\Controllers\Intrusion;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CloseCase;
use App\Models\Documents;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\VideoDocuments;
use Illuminate\Support\Facades\DB;
use App\Models\CaseCloseProgresses;
use App\Models\VideoAudioDocuments;
use App\Helpers\IntrusionDataHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Helpers\BodycamDeviceDataHelper;
use App\Models\Intrusion\IntrusionResult;
use App\Models\VideoAudioDocumentAnalytics;
use App\Models\Intrusion\IntrusionTargetEnv;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\DataTables\Intrusion\IntrusionTargetEnvDataTable;
use App\DataTables\Intrusion\IntrusionTargetEnvVideoShowDataTable;


class IntrusionTargetEnvController extends Controller
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
    public function index(IntrusionTargetEnvDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.intrusion.target-env.index', compact('satker', 'users'));
    }

    // API
    public function list(Request $request)
    {
        $user = Auth::user();
        $idSatker = $user->satker->id_satker;

        $data = IntrusionTargetEnv::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('intrusion_lingkungan_target.satker_id', '=', $idSatker);
                                })
                                ->with(['satker', 'case', 'location'])
                                ->latest()
                                ->paginate(10);
        return response()->json($data);
    }
    public function individual($id)
    {

        $data = IntrusionTargetEnv::with(['satker', 'case', 'location'])
                                ->findOrFail($id);
        return response()->json($data);
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();
        return view('backoffice.close.intrusion.target-env.create', compact('case', 'satker'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'satker_id' => 'required|string|max:128',
            'case_id' => 'required|string|max:128',
            // 'intrusion_target_location_id' => 'required|string|max:255',
            'nama_lingkungan' => 'required|string|max:128',
            'tipe_lingkungan' => 'required|string|max:255',
            'deskripsi_lingkungan' => 'required|string',
            // 'informasi_terkumpul' => 'required|string',
            // 'aktivitas_teramati' => 'required|string',
            'upload_lingkungan' => 'nullable|file|mimes:pdf|max:20480',
            'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = auth()->user();

        $data = new IntrusionTargetEnv;
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->intrusion_target_location_id = $request->intrusion_target_location_id;

        $data->nama_lingkungan = $request->nama_lingkungan;
        $data->tipe_lingkungan = $request->tipe_lingkungan;
        $data->deskripsi_lingkungan = $request->deskripsi_lingkungan;
        $data->informasi_terkumpul = $request->informasi_terkumpul;
        $data->aktivitas_teramati = $request->aktivitas_teramati;

        if ($request->hasFile('upload_lingkungan')) {
            $ext_upload_lingkungan = $request->file('upload_lingkungan')->extension();
            $upload_lingkungan = $request->file('upload_lingkungan')
                ->storePubliclyAs(
                    'close/intrusion/target-env/upload_lingkungan',
                    Str::slug('intrusion target-env', '_') . '_' . Str::random() . '.' . $ext_upload_lingkungan,
                    'public'
                );

            $data->target_environment_upload = $upload_lingkungan;
        }

        if ($request->hasFile('video_upload')) {
            $ext_video_upload = $request->file('video_upload')->extension();
            $video_upload = $request->file('video_upload')
                ->storePubliclyAs(
                    'close/intrusion/target_env/video_upload',
                    Str::slug('intrusion target environment', '_') . '_' . Str::random() . '.' . $ext_video_upload,
                    'public'
                );

            $data->video_upload = $video_upload;


        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lingkungan_target' => "1",
                'status' => $close_case_progress->percentage > 81.5 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 81.5 ? $close_case_progress->substatus : 'Input Lingkungan Target Penyurupan',
                'percentage' => $close_case_progress->percentage > 81.5 ? $close_case_progress->percentage : 81.5
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lingkungan_target' => "1",
                'status' => $close_case_progress->percentage > 81.5 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 81.5 ? $close_case_progress->substatus : 'Input Lingkungan Target Penyurupan',
                'percentage' => 100
            ]);

        }


        if ($data->save()) {
            // save doc analysis
            if($data->target_environment_upload){
                DataHelper::insertDocument($data->id, $data->target_environment_upload);
            }
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Lingkungan Target Penyurupan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            if ($request->hasFile('video_upload')) {

                // DataHelper::insertVideo($data->id_tapping_electronic_device, $data->video_upload);

                $video_data = new VideoDocuments;
                $video_data->relation_id = $data->id;
                $video_data->doc_path = $video_upload;
                $video_data->doc_type = "video";
                $video_data->doc_status = "0";
                $video_data->doc_status_remark = "Waiting Analysis";
                $video_data->updated_by = $user->id;
                $video_data->save();

                $video_audio_data = new VideoAudioDocuments;
                $video_audio_data->relation_id = $data->id;
                $video_audio_data->doc_path = $video_upload;
                $video_audio_data->doc_type = "video_audio";
                $video_audio_data->doc_status = "0";
                $video_audio_data->doc_status_remark = "Waiting Analysis";
                $video_audio_data->created_by = $user->id;
                $video_audio_data->save();
            }

            // update progress
            
            return redirect()->route('close.intrusion.target-env.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id, IntrusionTargetEnvVideoShowDataTable $dataTable)
    {
        $data = IntrusionTargetEnv::find($id);
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $document_pdf_data = Documents::where('relation_id', $data->id)->first();

        return $dataTable->render('backoffice.close.intrusion.target-env.show', compact(
            'data', 'document_pdf_data', 'bodycam_devices'));

        // return $dataTable->render('backoffice.close.tailing.pemahaman-perilaku.show', compact(
        //         'data', 'images', 'bodycam_devices'));
    }

    public function edit(Request $request, $id)
    {
        $data = IntrusionTargetEnv::find($id);
        $satker = DataHelper::getSatker();

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $case = DataHelper::getCloseCase();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $location = DataHelper::getClosTargetLoc($data->case->id);

        return view('backoffice.close.intrusion.target-env.edit', compact('data', 'satker', 'case', 'location'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'satker_id' => 'required|string|max:128',
            'case_id' => 'required|string|max:128',
            // 'intrusion_target_location_id' => 'required|string|max:255',
            // 'aktivitas_teramati' => 'required|string',
            // 'informasi_terkumpul' => 'required|string',
            'deskripsi_lingkungan' => 'required|string',
            // 'informasi_terkumpul' => 'required|string',
            // 'aktivitas_teramati' => 'required|string',
            'target_environment_upload' => 'nullable|file|mimes:pdf|max:20480',
            'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = auth()->user();

        $data = IntrusionTargetEnv::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        // $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->intrusion_target_location_id = $request->intrusion_target_location_id;

        $data->nama_lingkungan = $request->nama_lingkungan;
        $data->tipe_lingkungan = $request->tipe_lingkungan;
        $data->deskripsi_lingkungan = $request->deskripsi_lingkungan;
        $data->informasi_terkumpul = $request->informasi_terkumpul;
        $data->aktivitas_teramati = $request->aktivitas_teramati;

        if ($request->hasFile('target_environment_upload')) {
            $ext_target_environment_upload = $request->file('target_environment_upload')->extension();
            $target_environment_upload = $request->file('target_environment_upload')
                ->storePubliclyAs(
                    'close/intrusion/environment/upload_lingkungan',
                    Str::slug('intrusion environment upload', '_') . '_' . Str::random() . '.' . $ext_target_environment_upload,
                    'public'
                );

            if ($request->temp_target_environment_upload && Storage::disk('public')->exists($request->temp_target_environment_upload)) {
                Storage::disk('public')->delete($request->temp_target_environment_upload);
            }

            // save doc analysis
            DataHelper::insertDocument($data->id, $target_environment_upload, $request->temp_target_environment_upload);
            $data->target_environment_upload = $target_environment_upload;
        } else {
            $target_environment_upload = $request->temp_target_environment_upload;

            $data->target_environment_upload = $target_environment_upload;
        }

        if ($request->hasFile('video_upload')) {
            $ext_video_upload = $request->file('video_upload')->extension();
            $video_upload = $request->file('video_upload')
                ->storePubliclyAs(
                    'close/intrusion/target_env/video_upload',
                    Str::slug('intrusion target env', '_') . '_' . Str::random() . '.' . $ext_video_upload,
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

            if ($request->temp_video_upload && Storage::disk('public')->exists($request->temp_video_upload)) {
                Storage::disk('public')->delete($request->temp_video_upload);
            }

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

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lingkungan_target' => "1",
                'status' => $close_case_progress->percentage > 81.5 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 81.5 ? $close_case_progress->substatus : 'Input Lingkungan Target Penyurupan',
                'percentage' => 100
            ]);
        }

        if ($data->update()) {
            return redirect()->route('close.intrusion.target-env.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = IntrusionTargetEnv::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if($data->target_environment_upload){
            if (Storage::disk('public')->exists($data->target_environment_upload)) {
                Storage::disk('public')->delete($data->target_environment_upload);
            }
        }

        $data->delete();
        IntrusionResult::where('intrusion_target_environment_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function listSatker(Request $request)
    {
        $satker = auth()->user()->satker;
        $tipeSatker = (int)$satker->tipe_satker;

        return optSatkerWithChild($satker->kode_satker, 1, range($tipeSatker, 4));
    }

    

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

    public function uploadVideo(Request $request)
    {
        $path = $request->file('path]'); // Mengambil file video dari FormData

        // Mendapatkan id dari request
        $id = $request->input('id');


        if ($path) {
            // $filename = 'electronic_device' . time() . '.mp4';
            // $path = 'close/tapping/electronic-device/electronic_device_video_upload/' . $filename;

            $data_interview_hasil = IntrusionTargetEnv::where('id', $id)->first();
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

        $data = IntrusionTargetEnv::where('id', $interview_result_id)->first();
        
        $satker = MasterSatker::where('kode_satker', $data->satker_id)->first();

        
        $video_audio_data = VideoAudioDocuments::where('video_audio_documents.relation_id', $data->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $video_audio_analytics_data = VideoAudioDocumentAnalytics::where('video_audio_document_analytics.video_audio_doc_id', $video_audio_data->id)->get();

        $images = [];
        if ($data->case->target_photo) {
            $imagePaths = json_decode($data->case->target_photo);
            foreach ($imagePaths as $imagePath) {
                $images[] = Storage::url('close/case/' . $imagePath);
            }
        }

      
       

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);


        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.close.intrusion.target-env.pdf", compact(
            'data',
            'satker',
            'images',
            'video_audio_analytics_data'
        )));


        $filename = 'Open_Interview_Result_Audio_to_Text_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');



    }
}
