<?php

namespace App\Http\Controllers\Tailing;

use App\DataTables\Tailing\TailingPemahamanPerilakuDataTable;
use App\DataTables\Tailing\TailingPemahamanPerilakuVideoShowDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use App\Models\MasterAgama;
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
use App\Models\MasterPendidikan;
use App\Models\MasterPekerjaan;
use App\Models\VideoAudioDocuments;
use App\Models\VideoAudioDocumentAnalytics;

class TailingPemahamanPerilakuController extends Controller
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
    public function index(TailingPemahamanPerilakuDataTable $dataTable)
    {

        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.tailing.pemahaman-perilaku.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $agama = MasterAgama::get();
        $case = DataHelper::getCloseCase();
        $pendidikan = MasterPendidikan::select('kode', 'nama')->get();
        $pekerjaan = MasterPekerjaan::select('kode', 'nama')->get();

        return view(
            'backoffice.close.tailing.pemahaman-perilaku.create', 
            compact(
                'satker', 
                'users', 
                'case', 
                'agama',
                'pendidikan',
                'pekerjaan'));
    }

    public function store(Request $request)
    {


        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'target_name' => 'required|string|max:128',
            // 'target_gender' => 'required|string|max:128',
            // 'target_religion' => 'required|string|max:128',
            'nik' => 'required|string|max:128',
            // 'target_identity_number_type' => 'required|string|max:128',
            // 'target_occupation' => 'required|string|max:128',
            // 'target_education' => 'required|string|max:128',
            'perilaku_tercatat' => 'required|string|max:1000000',
            // 'aktivitas_rutin' => 'required|string|max:1000000',
            // 'hubungan_sosial' => 'required|string|max:1000000',
            // 'prediksi_perilaku' => 'required|string|max:1000000',
            'target_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pemahaman_perilaku_video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = auth()->user();

        $data = new TailingPemahamanPerilaku;
        $data->kode_satker = $satker->kode_satker;
        $data->id_satker = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->target_name = $request->target_name;
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_identity_number = $request->nik;
        $data->target_identity_number_type = 'NIK/KTP';
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;
        $data->perilaku_tercatat = $request->perilaku_tercatat;
        $data->aktivitas_rutin = $request->aktivitas_rutin;
        $data->hubungan_sosial = $request->hubungan_sosial;
        $data->prediksi_perilaku = $request->prediksi_perilaku;

        if ($request->hasFile('target_photo')) {
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'close/tailing/pemahaman_perilaku',
                    Str::slug('foto', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            $data->target_photo = $target_photo;
        }

        $document_video = new VideoDocuments;
        $video_audio_data = new VideoAudioDocuments;
        if ($request->hasFile('pemahaman_perilaku_video_upload')) {
            $ext_pemahaman_perilaku_video_upload = $request->file('pemahaman_perilaku_video_upload')->extension();
            $pemahaman_perilaku_video_upload = $request->file('pemahaman_perilaku_video_upload')
                ->storePubliclyAs(
                    'close/tailing/pemahaman_perilaku',
                    Str::slug('video', '_') . '_' . Str::random() . '.' . $ext_pemahaman_perilaku_video_upload,
                    'public'
                );

            $data->pemahaman_perilaku_video_upload = $pemahaman_perilaku_video_upload;

            $document_video->doc_path = $pemahaman_perilaku_video_upload;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";


            
           
            $video_audio_data->doc_path = $pemahaman_perilaku_video_upload;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->created_by =  $user->id;
           
        }

        $data->created_by = $user->id;
        $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
        
        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_pemahaman_perilaku', '0')
            ->update([
                'tailing_pemahaman_perilaku' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Pemahaman Perilaku",
                'percentage' => round((14/29)*100,2)
            ]);;
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_pemahaman_perilaku', '0')
            ->update([
                'tailing_pemahaman_perilaku' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Pemahaman Perilaku",
                'percentage' => round((29/29)*100,2)
            ]);;
        }
        
        if ($data->save()) {

            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update->action = "Penambahan Pemahaman Perilaku";
            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();
            

            $document_video->relation_id = $data->id;
            $document_video->save();

            $video_audio_data->relation_id = $data->id;
            $video_audio_data->save();
            


            return redirect()->route('close.tailing.pemahaman-perilaku.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $pemahaman_perilaku, TailingPemahamanPerilakuVideoShowDataTable $dataTable)
    {
        $data = TailingPemahamanPerilaku::find($pemahaman_perilaku);

        if (!$data) {
            return redirect()->back()->with('error', 'Data not found');
        }

        $images = [];
        if ($data->case->target_photo) {
            $imagePaths = json_decode($data->case->target_photo);
            foreach ($imagePaths as $imagePath) {
                $images[] = Storage::url('close/case/' . $imagePath);
            }
        }

        $data->pemahaman_perilaku_video_upload = Storage::url($data->pemahaman_perilaku_video_upload);
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();
        
        return $dataTable->render('backoffice.close.tailing.pemahaman-perilaku.show', compact(
            'data', 'images', 'bodycam_devices'));
    }


    public function edit(Request $request, $id)
    {
        $data = TailingPemahamanPerilaku::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();
         $agama = MasterAgama::get();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.tailing.pemahaman-perilaku.edit', compact('data', 'users', 'satker', 'case', 'agama'));
    }

    public function update(Request $request)
    {

         $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'target_name' => 'required|string|max:128',
            // 'target_gender' => 'required|string|max:128',
            // 'target_religion' => 'required|string|max:128',
            'nik' => 'required|string|max:128',
            // 'target_identity_number_type' => 'required|string|max:128',
            // 'target_occupation' => 'required|string|max:128',
            // 'target_education' => 'required|string|max:128',
            'perilaku_tercatat' => 'required|string|max:1000000',
            // 'aktivitas_rutin' => 'required|string|max:1000000',
            // 'hubungan_sosial' => 'required|string|max:1000000',
            // 'prediksi_perilaku' => 'required|string|max:1000000',
            // 'target_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'pemahaman_perilaku_video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
        ]);

        $user = auth()->user();

        $data = TailingPemahamanPerilaku::find($request->id);
        $data->target_name = $request->target_name;
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_identity_number = $request->nik;
        $data->target_identity_number_type = 'NIK/KTP';
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;
        $data->perilaku_tercatat = $request->perilaku_tercatat;
        $data->aktivitas_rutin = $request->aktivitas_rutin;
        $data->hubungan_sosial = $request->hubungan_sosial;
        $data->prediksi_perilaku = $request->prediksi_perilaku;

        if ($request->hasFile('target_photo')) {
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'close/tailing/pemahaman_perilaku',
                    Str::slug('foto', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            $data->target_photo = $target_photo;
        }


        if ($request->hasFile('pemahaman_perilaku_video_upload')) {
            $ext_pemahaman_perilaku_video_upload = $request->file('pemahaman_perilaku_video_upload')->extension();
            $pemahaman_perilaku_video_upload = $request->file('pemahaman_perilaku_video_upload')
                ->storePubliclyAs(
                    'close/tailing/pemahaman_perilaku',
                    Str::slug('video', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            $data->pemahaman_perilaku_video_upload = $pemahaman_perilaku_video_upload;
            
            $document_video = new VideoDocuments;
            $document_video->relation_id = $request->id;
            $document_video->doc_path = $pemahaman_perilaku_video_upload;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->updated_by =   $user->id;
            $document_video->save();

            $video_audio_data = new VideoAudioDocuments;
            $video_audio_data->relation_id = $request->id;
            $video_audio_data->doc_path = $pemahaman_perilaku_video_upload;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->updated_by =   $user->id;
            $video_audio_data->save();
        }

        $data->updated_by = $user->id;
        $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
        
        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_pemahaman_perilaku', '0')
            ->update([
                'tailing_pemahaman_perilaku' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Pemahaman Perilaku",
                'percentage' => round((29/29)*100,2)
            ]);;
        
        }
        
        if ($data->update()) {
            return redirect()->route('close.tailing.pemahaman-perilaku.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = TailingPemahamanPerilaku::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $data->delete();
        TailingTargetOperasi::where('tailing_pemahaman_perilaku_id', $id)->delete();
        TailingResultAchievement::where('tailing_pemahaman_perilaku_id', $id)->delete();

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
            // $filename = 'pemahaman_perilaku_' . time() . '.mp4';
            // $path = 'close/tailing/pemahaman_perilaku/' . $filename;

            $data_interview_hasil = TailingPemahamanPerilaku::where('id', $id)->first();
            $data_interview_hasil->pemahaman_perilaku_video_upload = $path;
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
        $data = TailingPemahamanPerilaku::where('id', $id)->first();
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
        
      
        $data->target_photo = Storage::url( $data->target_photo);
        $mpdf->WriteHTML(view("backoffice.close.tailing.pemahaman-perilaku.pdf", compact(
            'data',
            'images',
            'satker',
            'video_audio_analytics_data')));
        

        $filename = 'Open_Tailing_PemahamanPerilaku_Audio_to_Text_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
}
