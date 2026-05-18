<?php

namespace App\Http\Controllers\Infiltration;

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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use App\Helpers\InfiltrationDataHelper;
use Illuminate\Support\Facades\Storage;
use App\Helpers\BodycamDeviceDataHelper;
use App\Models\VideoAudioDocumentAnalytics;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\Models\Infiltration\InfiltrationTargetDynamics;
use App\Models\Infiltration\InfiltrationSecretOperation;
use App\Models\Infiltration\InfiltrationResultAchievement;
use App\DataTables\Infiltration\InfiltrationTargetDynamicsDataTable;
use App\DataTables\Infiltration\InfiltrationTargetDynamicsVideoShowDataTable;

class InfiltrationTargetDynamicsController extends Controller
{
    public function index(InfiltrationTargetDynamicsDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.infiltration.target-dynamics.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        
        $case = DataHelper::getCloseCase();
        $infiltration_operasi_rahasia = DataHelper::getInfiltrationOperasiRahasia();

        return view('backoffice.close.infiltration.target-dynamics.create', 
        compact('satker', 'users', 'case', 'infiltration_operasi_rahasia'));
    }

    public function edit(Request $request, $id)
    {
        $data = InfiltrationTargetDynamics::find($id);
        $infiltration_secret_operation = InfiltrationDataHelper::getInfiltrationOperasiRahasiabyCaseId($data->case_id);
        
        $case = DataHelper::getCloseCase();
        $satker = DataHelper::getSatker();
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.infiltration.target-dynamics.edit', 
        compact('data', 'case','satker', 'infiltration_secret_operation' ));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            // 'id_satker' => 'required|string|max:128',
            // 'id_infiltration_operasi_rahasia' => 'required|string|max:128',
            'dinamika_teramati' => 'required|string|max:1000000',
            // 'tanggal_dinamika_teramati' => 'required|date',
            // 'deskripsi_dinamika_teramati' => 'required|string|max:1000000',
            'dinamika_target_dokumen_upload' => 'nullable|file|mimes:pdf|max:20480',
            'dinamika_target_video_upload' => 'nullable|file|mimes:pdf|max:204800'
        ]);


        $user = auth()->user();

        $data = InfiltrationTargetDynamics::find($id);
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->infiltration_operasi_rahasia_id = $request->id_infiltration_operasi_rahasia;

        $data->dinamika_teramati = $request->dinamika_teramati;
        $data->tanggal_dinamika_teramati = $request->tanggal_dinamika_teramati;
        $data->deskripsi_dinamika_teramati = $request->deskripsi_dinamika_teramati;
        
        $data->updated_by = $user->id;

        if ($request->hasFile('dinamika_target_dokumen_upload')) {
            $ext_upload_info = $request->file('dinamika_target_dokumen_upload')->extension();
            $upload_info = $request->file('dinamika_target_dokumen_upload')
                ->storePubliclyAs(
                    'close/infiltration/target-dynamics/dinamika_target_dokumen_upload',
                    Str::slug('infiltration target-dynamics document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->dinamika_target_dokumen_upload = $upload_info;

            $document_pdf = new Documents;
            $document_pdf->doc_path = $upload_info;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->relation_id = $id;
            $document_pdf->save();
        }

        if ($request->hasFile('dinamika_target_video_upload')) {
            $ext_upload_info = $request->file('dinamika_target_video_upload')->extension();
            $upload_info = $request->file('dinamika_target_video_upload')
                ->storePubliclyAs(
                    'close/infiltration/target-dynamics/dinamika_target_video_upload',
                    Str::slug('infiltration target-dynamics video', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->dinamika_target_video_upload = $upload_info;

            $document_video = new VideoDocuments;
            $document_video->doc_path = $upload_info;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();

            $video_audio_data = new VideoAudioDocuments;
            $video_audio_data->relation_id = $id;
            $video_audio_data->doc_path = $upload_info;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->updated_by =   $user->id;
            $video_audio_data->save();
        }

        if ($request->submit_type === 'update_and_finish') {
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)->first();
            
       
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('infiltration_dinamika_target', '0')
            ->update([
                'infiltration_dinamika_target' => "1",
                'status' => "Penyusupan",
                'substatus' => "Penambahan Dinamika Target",
                'percentage' => round((29/29)*100,2)
            ]);;

        }

        if ($data->update()) {
            return redirect()->route('close.infiltration.target-dynamics.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            // 'id_infiltration_operasi_rahasia' => 'required|string|max:128',
            'dinamika_teramati' => 'required|string|max:1000000',
            // 'tanggal_dinamika_teramati' => 'required|date',
            // 'deskripsi_dinamika_teramati' => 'required|string|max:1000000',
            'dinamika_target_dokumen_upload' => 'nullable|file|mimes:pdf|max:20480',
            'dinamika_target_video_upload' => 'nullable|file|mimes:pdf|max:204800'
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = auth()->user();

        $data = new InfiltrationTargetDynamics;
        $data->satker_id = $satker->id_satker;
        $data->case_id = $request->id_case;
        $data->infiltration_operasi_rahasia_id = $request->id_infiltration_operasi_rahasia;

        $data->dinamika_teramati = $request->dinamika_teramati;
        $data->tanggal_dinamika_teramati = $request->tanggal_dinamika_teramati;
        $data->deskripsi_dinamika_teramati = $request->deskripsi_dinamika_teramati;
        


        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        $document_pdf = new Documents;
        if ($request->hasFile('dinamika_target_dokumen_upload')) {
            $ext_upload_info = $request->file('dinamika_target_dokumen_upload')->extension();
            $upload_info = $request->file('dinamika_target_dokumen_upload')
                ->storePubliclyAs(
                    'close/infiltration/target-dynamics/dinamika_target_dokumen_upload',
                    Str::slug('infiltration target-dynamics document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->dinamika_target_dokumen_upload = $upload_info;
            
            
            $document_pdf->doc_path = $upload_info;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";

        }

        $document_video = new VideoDocuments;
        $video_audio_data = new VideoAudioDocuments;
        if ($request->hasFile('dinamika_target_video_upload')) {
            $ext_upload_info = $request->file('dinamika_target_video_upload')->extension();
            $upload_info = $request->file('dinamika_target_video_upload')
                ->storePubliclyAs(
                    'close/infiltration/target-dynamics/dinamika_target_video_upload',
                    Str::slug('infiltration target-dynamics video', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->dinamika_target_video_upload = $upload_info;

            
            $document_video->doc_path = $upload_info;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";

            $video_audio_data->doc_path = $upload_info;
            $video_audio_data->doc_type = "video_audio";
            $video_audio_data->doc_status = "0";
            $video_audio_data->doc_status_remark = "Waiting Analysis";
            $video_audio_data->created_by =  $user->id;
        }
        
        $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update->case_id = $data->id;
        $data_case_close_historical_update->action = "Penambahan Dinamika Target";

        $data_case_close_historical_update->created_by = $user->id;
        $data_case_close_historical_update->updated_by = $user->id;

        
        $data_case_close_historical_update->created_by = $user->id;
        $data_case_close_historical_update->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('infiltration_dinamika_target', '0')
            ->update([
                'infiltration_dinamika_target' => "1",
                'status' => "Penyusupan",
                'substatus' => "Penambahan Dinamika Target",
                'percentage' => round((19/29)*100,2)
            ]);;
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('infiltration_dinamika_target', '0')
            ->update([
                'infiltration_dinamika_target' => "1",
                'status' => "Penyusupan",
                'substatus' => "Penambahan Dinamika Target",
                'percentage' => round((29/29)*100,2)
            ]);;

        }

        if ($data->save()) {
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update->save();
            
            $document_pdf->relation_id = $data->id;
            $document_video->relation_id = $data->id;
            $video_audio_data->relation_id = $data->id;
            
            $document_pdf->save();
            $document_video->save();
            $video_audio_data->save();
            

            return redirect()->route('close.infiltration.target-dynamics.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $target_dynamic, InfiltrationTargetDynamicsVideoShowDataTable $dataTable)
    {
        $data = InfiltrationTargetDynamics::find($target_dynamic);
        $infiltration_operasi_rahasia = InfiltrationSecretOperation::find($data->infiltration_operasi_rahasia_id);
        $case = CloseCase::find($data->case_id);
        $satker = MasterSatker::find($data->satker_id);
        $document_pdf_data = Documents::where('relation_id', $data->id)->first();
        $data->dinamika_target_video_upload = Storage::url( $data->dinamika_target_video_upload);
        $data->dinamika_target_dokumen_upload = Storage::url( $data->dinamika_target_dokumen_upload);
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();

        return $dataTable->with('target_dynamic', $target_dynamic)
            ->render('backoffice.close.infiltration.target-dynamics.show', 
            compact('data', 
            'case', 
            'satker', 
            'infiltration_operasi_rahasia',
            'document_pdf_data',
            'bodycam_devices'));
    
    }

    public function destroy( Request $request, $id)
    {

        $data = InfiltrationTargetDynamics::find($id);
        
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!' );
        }

        if ($data->surat_perintah_path) {
            if (Storage::disk('public')->exists($data->surat_perintah_path)) {
                Storage::disk('public')->delete($data->surat_perintah_path);
            }
        }

        $data->delete();
        InfiltrationResultAchievement::where('infiltration_dinamika_target_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

    public function uploadVideo1(Request $request)
    {
        $path = $request->file('path'); // Mengambil file video dari FormData

        // Mendapatkan id dari request
        $id = $request->input('id');

        
        if ($path) {
            // $filename = 'dinamika_target' . time() . '.mp4';
            // $path = 'close/infiltration/target-dynamic/dinamika_target_video_upload/' . $filename;

            $data_interview_hasil = InfiltrationTargetDynamics::where('id', $id)->first();
            $data_interview_hasil->dinamika_target_video_upload = $path;
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
        $data = InfiltrationTargetDynamics::where('id', $id)->first();
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
        $mpdf->WriteHTML(view("backoffice.close.infiltration.target-dynamics.pdf", compact(
            'data',
            'images',
            'satker',
            'video_audio_analytics_data')));
        

        $filename = 'Open_Infiltration_Secret_Operation_Audio_to_Text_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
}
