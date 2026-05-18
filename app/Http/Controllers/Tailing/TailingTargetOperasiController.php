<?php

namespace App\Http\Controllers\Tailing;

use App\DataTables\Tailing\TailingTargetOperasiDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Satker;
use App\Models\VideoDocuments;
use App\Models\Tailing\TailingPemahamanPerilaku;
use App\Models\Tailing\TailingTargetOperasi;
use App\Models\Tailing\TailingResultAchievement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Helpers\TailingDataHelper;
use App\Helpers\BodycamDeviceDataHelper;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;
use Illuminate\Support\Facades\Date;
use App\Models\VideoAudioDocuments;
use App\Models\VideoAudioDocumentAnalytics;
class TailingTargetOperasiController extends Controller
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
    public function index(TailingTargetOperasiDataTable $dataTable)
    {

        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.tailing.target-operasi.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCloseCase();
        $pemahaman_perilaku = DB::table('tailing_pemahaman_perilaku')->get();


        return view('backoffice.close.tailing.target-operasi.create', compact('satker', 'users', 'case','pemahaman_perilaku'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            // 'tailing_pemahaman_perilaku_id' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'rencana_target_operasi' => 'required|string|max:1000000',
            'target_operasi' => 'required|string|max:1000000',
            'skenario_target_operasi' => 'nullable|string|max:1000000',
            'target_operasi_video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);


        $satker = MasterSatker::find($request->id_satker);
        $user = auth()->user();

        $data = new TailingTargetOperasi;
        $data->kode_satker  = $satker->kode_satker;
        $data->case_id = $request->id_case;
        $data->tailing_pemahaman_perilaku_id = $request->tailing_pemahaman_perilaku_id;
        $data->rencana_target_operasi = $request->rencana_target_operasi;
        $data->target_operasi = $request->target_operasi;
        $data->skenario_target_operasi = $request->skenario_target_operasi;
        $data->created_by = $user->id;

        $document_video = new VideoDocuments;
        $video_audio_data = new VideoAudioDocuments;
        if ($request->hasFile('target_operasi_video_upload')) {
            $ext_target_operasi_video_upload = $request->file('target_operasi_video_upload')->extension();
            $target_operasi_video_upload = $request->file('target_operasi_video_upload')
                ->storePubliclyAs(
                    'close/tailing/target_operasi',
                    Str::slug('video', '_') . '_' . Str::random() . '.' . $ext_target_operasi_video_upload,
                    'public'
                );

            $data->target_operasi_video_upload = $target_operasi_video_upload;

            $document_video->doc_path = $target_operasi_video_upload;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";

            
            $video_audio_data->doc_path = $target_operasi_video_upload;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->created_by =   $user->id;
            
        }

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_target_operasi', '0')
            ->update([
                'tailing_target_operasi' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Target Operasi",
                'percentage' => round((15/29) *100, 2)
            ]);;

        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_target_operasi', '0')
            ->update([
                'tailing_target_operasi' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Target Operasi",
                'percentage' => round((29/29) *100, 2)
            ]);;

        }
        

        if ($data->save()) {

            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update->action = "Penambahan Target Operasi";
            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            $document_video->relation_id = $data->id;
            $document_video->save();

            $video_audio_data->relation_id = $data->id;
            $video_audio_data->save();


            


            return redirect()->route('close.tailing.target-operasi.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = TailingTargetOperasi::with(['tailingpemahamanperilaku', 'case', 'satker'])->find($id);

        $data->target_operasi_video_upload = Storage::url( $data->target_operasi_video_upload);
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();
        

        return view('backoffice.close.tailing.target-operasi.show', compact(
            'data',
            'bodycam_devices'));
    }

    public function edit(Request $request, $id)
    {
        $data = TailingTargetOperasi::find($id);
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();

        $pemahaman_perilaku = DB::table('tailing_pemahaman_perilaku')->where('case_id', $data->case_id)->get();


        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.tailing.target-operasi.edit', compact('data', 'satker', 'case', 'pemahaman_perilaku'));
    }

    public function update(Request $request)
    {
         $this->validate($request, [
            // 'tailing_pemahaman_perilaku_id' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            // 'id_satker' => 'required|string|max:128',
            'rencana_target_operasi' => 'required|string|max:1000000',
            'target_operasi' => 'required|string|max:1000000',
            // 'skenario_target_operasi' => 'required|string|max:1000000',
            'target_operasi_video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000'
        ]);

        $user = auth()->user();

        $data = TailingTargetOperasi::find($request->id);
        $data->rencana_target_operasi = $request->rencana_target_operasi;
        $data->target_operasi = $request->target_operasi;
        $data->skenario_target_operasi = $request->skenario_target_operasi;
        $data->created_by = $user->id;

         if ($request->hasFile('target_operasi_video_upload')) {
            $ext_target_operasi_video_upload = $request->file('target_operasi_video_upload')->extension();
            $target_operasi_video_upload = $request->file('target_operasi_video_upload')
                ->storePubliclyAs(
                    'close/tailing/target_operasi',
                    Str::slug('video', '_') . '_' . Str::random() . '.' . $ext_target_operasi_video_upload,
                    'public'
                );

            $data->target_operasi_video_upload = $target_operasi_video_upload;

            $document_video = new VideoDocuments;
            $document_video->relation_id = $request->id;
            $document_video->doc_path = $target_operasi_video_upload;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->updated_by =   $user->id;
            $document_video->save();

            $video_audio_data = new VideoAudioDocuments;
            $video_audio_data->relation_id = $request->id;
            $video_audio_data->doc_path = $target_operasi_video_upload;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->updated_by =   $user->id;
            $video_audio_data->save();
        }

         $data->updated_by = $user->id;

         if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_target_operasi', '0')
            ->update([
                'tailing_target_operasi' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Target Operasi",
                'percentage' => round((20/29) *100, 2)
            ]);;

        
        }

        if ($data->update()) {
            return redirect()->route('close.tailing.target-operasi.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = TailingTargetOperasi::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $data->delete();
        TailingResultAchievement::where('tailing_target_operasi_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

    public function uploadVideo(Request $request)
    {
        $id = $request->input('id');
        $path = $request->input('path');
        
        if ($path) {
            // $filename = 'target_operasi_' . time() . '.mp4';
            // $path = 'close/tailing/target_operasi/' . $filename;

            $data_interview_hasil = TailingTargetOperasi::where('id', $id)->first();
            $data_interview_hasil->target_operasi_video_upload = $path;
            $data_interview_hasil->update();


            $document_video = new VideoDocuments;
            $document_video->doc_path = $path;
            $document_video->doc_status = "0";
            $document_video->doc_type = "video";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();

            $video_audio_data = new VideoAudioDocuments;
            $video_audio_data->relation_id = $id;
            $video_audio_data->doc_path = $path;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->save();
       
            // Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

            return response()->json(['success' => true, 'path' => $path]);
        }

        return response()->json(['success' => false, 'message' => 'No video data uploaded']);
    }

    public function downloadAudiotoTextFile($id)
    {

        $id = decrypt($id);
        // return $id_case;
        $data = TailingTargetOperasi::where('id', $id)->first();
        $satker = MasterSatker::where('kode_satker', $data->satker_id)->first();
        $video_audio_data = VideoAudioDocuments::where('video_audio_documents.relation_id', $data->id)
                                       ->orderBy('created_at', 'desc')
                                       ->first();
        $video_audio_analytics_data = VideoAudioDocumentAnalytics::where('video_audio_document_analytics.video_audio_doc_id', $video_audio_data->id)->get();

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        $images = [];
        if ($data->case->target_photo) {
            $imagePaths = json_decode($data->case->target_photo);
            foreach ($imagePaths as $imagePath) {
                $images[] = Storage::url('close/case/' . $imagePath);
            }
        }
        
      
        $mpdf->WriteHTML(view("backoffice.close.tailing.target-operasi.pdf", compact(
            'data',
            'images',
            'satker',
            'video_audio_analytics_data')));
        

        $filename = 'Open_Tailing_Target_Operasi_Audio_to_Text_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
}
