<?php

namespace App\Http\Controllers\Intrusion;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CloseCase;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Intrusion\IntrusionTargetLoc;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\DataTables\Intrusion\IntrusionTargetLocDataTable;
use App\DataTables\Intrusion\IntrusionTargetLocVideoShowDataTable;
use App\Helpers\IntrusionDataHelper;
use App\Models\VideoDocuments;
use App\Models\VideoAudioDocuments;
use App\Models\VideoAudioDocumentAnalytics;
use App\Helpers\BodycamDeviceDataHelper;
use App\Models\Intrusion\IntrusionResult;
use App\Models\Intrusion\IntrusionTargetEnv;
use Illuminate\Support\Facades\Date;

class IntrusionTargetLocController extends Controller
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
    public function index(IntrusionTargetLocDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.intrusion.target-loc.index', compact('satker', 'users'));
    }

    // API
    public function list(Request $request)
    {
        $user = Auth::user();
        $idSatker = $user->satker->id_satker;

        $data = IntrusionTargetLoc::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('intrusion_target_lokasi.satker_id', '=', $idSatker);
                                })
                                ->with(['satker', 'case'])
                                ->latest()
                                ->paginate(10);
        return response()->json($data);
    }
    public function individual($id)
    {

        $data = IntrusionTargetLoc::with(['satker', 'case'])
                                ->findOrFail($id);
        return response()->json($data);
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();
        $tipeIdentitas = tipeIndentitas();
        $agama = DataHelper::getAgama();
        $pendidikan = DataHelper::getPendidikan();
        $pekerjaan = DataHelper::getPekerjaan();

        return view('backoffice.close.intrusion.target-loc.create', compact('satker', 'case', 'tipeIdentitas', 'agama', 'pendidikan', 'pekerjaan'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'satker_id' => 'required|string|max:128',
            'case_id' => 'required|string|max:128',
            'target_name' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:128',
            // 'target_identity_number' => 'required|string|max:255',
            // 'target_gender' => 'required|string|max:100',
            // 'target_religion' => 'required|string|max:100',
            // 'target_education' => 'required|string|max:255',
            // 'target_occupation' => 'required|string|max:255',
            'lokasi_target' => 'required|string',
            'deskripsi_lokasi' => 'required|string',
            'upload_lokasi' => 'nullable|file|mimes:pdf|max:2048',
            'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            
        ]);

        $user = auth()->user();

        $data = new IntrusionTargetLoc;
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;

        $data->target_name = $request->target_name;
        $data->target_identity_number_type = 'NIK/KTP';
        $data->target_identity_number = $request->nik;
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_education = $request->pendidikan;
        $data->target_occupation = $request->pekerjaan;
        $data->lokasi_target = $request->lokasi_target;
        $data->deskripsi_lokasi = $request->deskripsi_lokasi;

        if ($request->hasFile('upload_lokasi')) {
            $ext_upload_lokasi = $request->file('upload_lokasi')->extension();
            $upload_lokasi = $request->file('upload_lokasi')
                ->storePubliclyAs(
                    'close/intrusion/target-loc/upload_lokasi',
                    Str::slug('intrusion target-loc', '_') . '_' . Str::random() . '.' . $ext_upload_lokasi,
                    'public'
                );

            $data->lokasi_target_upload = $upload_lokasi;
        }


        if ($request->hasFile('video_upload')) {
            $ext_video_upload = $request->file('video_upload')->extension();
            $video_upload = $request->file('video_upload')
                ->storePubliclyAs(
                    'close/intrusion/target_loc/video_upload',
                    Str::slug('tapping electronic device', '_') . '_' . Str::random() . '.' . $ext_video_upload,
                    'public'
                );

            $data->video_upload = $video_upload;


        }

        $filenames = [];
        $index = 1;
        if($request->file('image') != null){
            foreach ($request->file('image') as $image) {
                $filename = $image->storePubliclyAs(
                    'close/intrusion/target-loc/target-photo',
                    time(). ' - '. Str::random(). " - " . $request->target_name.' - '. $index . '.'. $image->getClientOriginalExtension(),
                    'public'
                );
                $filenames[] = $filename;
                $index++;
            }    
        }
        $data->target_photo = json_encode($filenames);

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lokasi_target' => "1",
                'status' => $close_case_progress->percentage > 77 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 77 ? $close_case_progress->substatus : 'Input Lokasi Target Penyurupan',
                'percentage' => $close_case_progress->percentage > 77 ? $close_case_progress->percentage : 77
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lokasi_target' => "1",
                'status' => $close_case_progress->percentage > 77 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 77 ? $close_case_progress->substatus : 'Input Lokasi Target Penyurupan',
                'percentage' => 100
            ]);

        }

        if ($data->save()) {
            // save doc analysis
            if($data->lokasi_target_upload){
                DataHelper::insertDocument($data->id, $data->lokasi_target_upload);
            }
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Lokasi Target Penyurupan";

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
            
            return redirect()->route('close.intrusion.target-loc.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id, IntrusionTargetLocVideoShowDataTable $dataTable)
    {
        $data = IntrusionTargetLoc::find($id);
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();



        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $images = [];

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);

            foreach ($imagePaths as $imagePath) {
                $images[] = asset('storage/' . $imagePath);
            }
        }

        return $dataTable->render('backoffice.close.intrusion.target-loc.show', compact(
            'data', 'images', 'bodycam_devices'));
    }

    public function edit(Request $request, $id)
    {
        $data = IntrusionTargetLoc::find($id);
        $satker = DataHelper::getSatker();

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $case = DataHelper::getCloseCase();
        $tipeIdentitas = tipeIndentitas();
        $agama = DataHelper::getAgama();
        $pendidikan = DataHelper::getPendidikan();
        $pekerjaan = DataHelper::getPekerjaan();
        $images = [];

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);

            foreach ($imagePaths as $imagePath) {
                $images[] = asset('storage/' . $imagePath);
            }
        }


        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.intrusion.target-loc.edit', compact('data', 'satker', 'case', 'tipeIdentitas', 'agama', 'images', 'pendidikan', 'pekerjaan'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'satker_id' => 'required|string|max:128',
            'case_id' => 'required|string|max:128',
            'target_name' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:128',
            // 'target_identity_number' => 'required|string|max:255',
            // 'target_gender' => 'required|string|max:100',
            // 'target_religion' => 'required|string|max:100',
            // 'target_education' => 'required|string|max:255',
            // 'target_occupation' => 'required|string|max:255',
            'lokasi_target' => 'required|string',
            'deskripsi_lokasi' => 'required|string',
            'lokasi_target_upload' => 'nullable|file|mimes:pdf|max:2048',
            'video_upload' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:2048000',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        $data = IntrusionTargetLoc::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $data->case_id = $request->case_id;

        // $data->satker_id = $request->satker_id;
        $data->target_name = $request->target_name;
        $data->target_identity_number_type = 'NIK/KTP';
        $data->target_identity_number = $request->target_identity_number;
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_education = $request->target_education;
        $data->target_occupation = $request->target_occupation;
        $data->lokasi_target = $request->lokasi_target;
        $data->deskripsi_lokasi = $request->deskripsi_lokasi;

        if ($request->hasFile('lokasi_target_upload')) {
            $ext_lokasi_target_upload = $request->file('lokasi_target_upload')->extension();
            $lokasi_target_upload = $request->file('lokasi_target_upload')
                ->storePubliclyAs(
                    'close/intrusion/target-loc/upload_lokasi',
                    Str::slug('intrusion target-loc lokasi', '_') . '_' . Str::random() . '.' . $ext_lokasi_target_upload,
                    'public'
                );

                if($request->temp_lokasi_target_upload){
                    if (Storage::disk('public')->exists($request->temp_lokasi_target_upload)) {
                        Storage::disk('public')->delete($request->temp_lokasi_target_upload);
                    }
                }

            // save doc analysis
            DataHelper::insertDocument($data->id, $lokasi_target_upload, $request->temp_lokasi_target_upload);
            $data->lokasi_target_upload = $lokasi_target_upload;
        } else {
            $lokasi_target_upload = $request->temp_lokasi_target_upload;

            $data->lokasi_target_upload = $lokasi_target_upload;
        }

        if ($request->hasFile('video_upload')) {
            $ext_video_upload = $request->file('video_upload')->extension();
            $video_upload = $request->file('video_upload')
                ->storePubliclyAs(
                    'close/intrusion/target_loc/video_upload',
                    Str::slug('intrusion target loc', '_') . '_' . Str::random() . '.' . $ext_video_upload,
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

        // photo
        $newImages = [];
        if ($request->file('image') != null) {
            // Remove existing images
            if ($data->target_photo) {
                $existingImagePaths = json_decode($data->target_photo);
    
                foreach ($existingImagePaths as $existingImagePath) {
                    if (Storage::disk('public')->exists($existingImagePath)) {
                        Storage::disk('public')->delete($existingImagePath);
                    }
                }
            }
            // Save new images
            $index = 1;
            foreach ($request->file('image') as $image) {
                $filename = $image->storePubliclyAs(
                    'close/intrusion/target-loc/target-photo',
                    time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension(),
                    'public'
                );
                $newImages[] = $filename;
                $index++;
            }    
        } else{
            $newImages = json_decode($data->target_photo);
        }
        $data->target_photo = json_encode($newImages);

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'intrusion_lokasi_target' => "1",
                'status' => $close_case_progress->percentage > 77 ? $close_case_progress->status : 'Penyurupan',
                'substatus' => $close_case_progress->percentage > 77 ? $close_case_progress->substatus : 'Input Lokasi Target Penyurupan',
                'percentage' => 100
            ]);
        }

        if ($data->update()) {
            return redirect()->route('close.intrusion.target-loc.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = IntrusionTargetLoc::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if($data->lokasi_target_upload){
            if (Storage::disk('public')->exists($data->lokasi_target_upload)) {
                Storage::disk('public')->delete($data->lokasi_target_upload);
            }
        }

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);

            foreach ($imagePaths as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        $data->delete();
        IntrusionTargetEnv::where('intrusion_target_location_id', $id)->delete();
        IntrusionResult::where('intrusion_target_location_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
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

            $data_interview_hasil = IntrusionTargetLoc::where('id', $id)->first();
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

        $data = IntrusionTargetLoc::where('id', $interview_result_id)->first();
        
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

        $image_target_locs = [];
        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);
            foreach ($imagePaths as $imagePath) {
                $image_target_locs[] = Storage::url( $imagePath);
            }
        }
       

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);


        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.close.intrusion.target-loc.pdf", compact(
            'data',
            'satker',
            'images',
            'image_target_locs',
            'video_audio_analytics_data'
        )));


        $filename = 'Open_Interview_Result_Audio_to_Text_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');



    }
}
