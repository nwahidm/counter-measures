<?php

namespace App\Http\Controllers\CloseSingleForm;

use Mpdf\Mpdf;
use App\Models\User;
use App\Models\Documents;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VideoDocuments;
use App\Models\CloseCaseSingleForm;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use App\Helpers\BodycamDeviceDataHelper;
use App\DataTables\CloseSingleForm\CloseSingleFormDataTable;
use App\DataTables\CloseSingleForm\CloseSingleFormTailingOperasiDataTable;
use App\DataTables\CloseSingleForm\CloseSingleFormTappingOperasiDataTable;
use App\DataTables\CloseSingleForm\CloseSingleFormTailingPerilakuDataTable;
use App\DataTables\CloseSingleForm\CloseSingleFormInfiltrationOperasiDataTable;
use App\DataTables\CloseSingleForm\CloseSingleFormInfiltrationDinamikaDataTable;


class CloseSingleFormController extends Controller
{
    public function index(CloseSingleFormDataTable $dataTable)
    {
        return $dataTable->render('backoffice.close.single-form.index');
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $agama = DataHelper::getListAgama();
        $pendidikan = DataHelper::getPendidikan();
        $pekerjaan = DataHelper::getPekerjaan();

        return view('backoffice.close.single-form.create', compact('satker', 'users', 'agama', 'pendidikan', 'pekerjaan'));
    }

    public function show(Request $request, $id, CloseSingleFormTailingPerilakuDataTable $tailingPerilakuDataTable, CloseSingleFormTailingOperasiDataTable $tailingOperasiDataTable, CloseSingleFormInfiltrationOperasiDataTable $infiltrationOperasiDataTable,
    CloseSingleFormInfiltrationDinamikaDataTable $infiltrationDinamikaDataTable, CloseSingleFormTappingOperasiDataTable $tappingOperasiDataTable)
    {
        $data = CloseCaseSingleForm::find($id);
        $images = [];

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);
            foreach ($imagePaths as $imagePath) {
                $images[] =Storage::url('close/single-form/' . $imagePath);
            }
        }

        $data->observation_upload_surat_perintah = Storage::url( $data->observation_upload_surat_perintah);
        $observation_photos = [];
        if ($data->observation_foto_terkait) {
            $imagePaths = json_decode($data->observation_foto_terkait);
            foreach ($imagePaths as $imagePath) {
                $observation_photos[] =Storage::url('close/single-form/observation_foto_terkait/' . $imagePath);
            }
        }

        $exploration_photos = [];
        if ($data->exploration_identitas_terhubung_foto_target) {
            $imagePaths = json_decode($data->exploration_identitas_terhubung_foto_target);
            foreach ($imagePaths as $imagePath) {
                $exploration_photos[] =Storage::url('close/single-form/exploration_identitas_terhubung_foto_target/' . $imagePath);
            }
        }

        $data->tailing_pemahaman_perilaku_upload_video = Storage::url( $data->tailing_pemahaman_perilaku_upload_video);
        $data->tailing_target_operasi_upload_video = Storage::url( $data->tailing_target_operasi_upload_video);
        
        $tailing_photos = [];
        if ($data->tailing_pemahaman_perilaku_foto) {
            $imagePaths = json_decode($data->tailing_pemahaman_perilaku_foto);
            foreach ($imagePaths as $imagePath) {
                $tailing_photos[] =Storage::url('close/single-form/tailing_pemahaman_perilaku_foto/' . $imagePath);
            }
        }

        $data->infiltration_operasi_rahasia_upload_video = Storage::url( $data->infiltration_operasi_rahasia_upload_video);
        $data->infiltration_dinamika_teramati_upload_video = Storage::url( $data->infiltration_dinamika_teramati_upload_video);
        

        $intrusion_photos = [];
        if ($data->intrusion_foto) {
            $imagePaths = json_decode($data->intrusion_foto);
            foreach ($imagePaths as $imagePath) {
                $intrusion_photos[] =Storage::url('close/single-form/intrusion_foto/' . $imagePath);
            }
        }

        $data->tapping_data_perangkat_elektronik_upload_video = Storage::url( $data->tapping_data_perangkat_elektronik_upload_video);
        
        // dd($observation_photos);
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();

        // datatable
        $tailingPerilakuDataTable->setData($data);
        $tailingOperasiDataTable->setData($data);
        $infiltrationOperasiDataTable->setData($data);
        $tappingOperasiDataTable->setData($data);

        return view('backoffice.close.single-form.show', compact(
            'data', 
            'images',
            'observation_photos',
            'exploration_photos',
            'tailing_photos',
            'intrusion_photos',


            'bodycam_devices'), [
                'tailingPerilakuDataTable' => $tailingPerilakuDataTable->html(),
                'tailingOperasiDataTable' => $tailingOperasiDataTable->html(),
                'infiltrationOperasiDataTable' => $infiltrationOperasiDataTable->html(),
                'infiltrationDinamikaDataTable' => $infiltrationDinamikaDataTable->html(),
                'tappingOperasiDataTable' => $tappingOperasiDataTable->html(),
            ]);
    }

    // MANAGE VIDEO ANALYSIS
    public function videoTailingPerilaku(CloseSingleFormTailingPerilakuDataTable $tailingPerilakuDataTable, Request $request)
    {
        $id = $request->query('data_id');
        $data = CloseCaseSingleForm::find($id);
        $tailingPerilakuDataTable->setData($data);
        
        return $tailingPerilakuDataTable->render('backoffice.close.single-form.show');
    }
    public function videoTailingOperasi(CloseSingleFormTailingOperasiDataTable $tailingOperasiDataTable, Request $request)
    {
        $id = $request->query('data_id');
        $data = CloseCaseSingleForm::find($id);
        $tailingOperasiDataTable->setData($data);

        return $tailingOperasiDataTable->render('backoffice.close.single-form.show');
    }
    public function videoInfiltrationOperasi(CloseSingleFormInfiltrationOperasiDataTable $infiltrationOperasiDataTable, Request $request)
    {
        $id = $request->query('data_id');
        $data = CloseCaseSingleForm::find($id);
        $infiltrationOperasiDataTable->setData($data);

        return $infiltrationOperasiDataTable->render('backoffice.close.single-form.show');
    }
    public function videoInfiltrationDinamika(CloseSingleFormInfiltrationDinamikaDataTable $infiltrationDinamikaDataTable, Request $request)
    {
        $id = $request->query('data_id');
        $data = CloseCaseSingleForm::find($id);
        $infiltrationDinamikaDataTable->setData($data);

        return $infiltrationDinamikaDataTable->render('backoffice.close.single-form.show');
    }
    public function videoTappingOperasi(CloseSingleFormTappingOperasiDataTable $tappingOperasiDataTable, Request $request)
    {
        $id = $request->query('data_id');
        $data = CloseCaseSingleForm::find($id);
        $tappingOperasiDataTable->setData($data);

        return $tappingOperasiDataTable->render('backoffice.close.single-form.show');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'procedure_type' => 'required|string|max:128',
            'case_name'  => 'required|string|max:128',
            'case_date'  => 'required|string|max:128',
            'case_description'  => 'required|string|max:128',
            'satker_id'  => 'required|string|max:128',

            'nik'  => 'required|string|max:128',
            'target_name'  => 'required|string|max:128',
            // 'target_religion'  => 'required|string|max:128',
            // 'target_education' => 'required|string|max:128',
            // 'target_occupation' => 'required|string|max:128',
            // 'target_gender' => 'required|string|max:128',

            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // 'research_dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            // 'research_video_upload' => 'nullable|file|mimes:mp4|max:200048',

            // 'interview_interviewer_name' => 'required|string|max:128',
            // 'interview_interviewer_schedule' => 'required|string|max:128',
            // 'interview_target_name' => 'required|string|max:128',
            // 'interview_nik' => 'required|string|max:128',
        ]);

        $user = auth()->user();
        

        $data = new CloseCaseSingleForm;
        $data->created_by = $user->id;
        $data->case_name = $request->case_name;
        $data->case_date = $request->case_date;
        $data->case_description = $request->case_description;

        $data->target_name = $request->target_name;
        $data->target_identity_number = $request->nik;
        $data->target_identity_number_type = 'NIK/KTP';
        $data->target_religion = $request->target_religion;
        $data->target_education = $request->target_education;
        $data->target_occupation = $request->target_occupation;
        $data->target_gender = $request->target_gender;
        $data->target_address = $request->target_address;

        // save the image first
        $filenames = [];
        $index = 1;
        if($request->file('target_image') != null){
            foreach ($request->file('target_image') as $image) {
                $filename = time(). ' - '. $request->target_name.' - '. $index . '.'. $image->getClientOriginalExtension();
                
                
                // $image->move($folderPath, $filename);
                $filenames[] = $filename;
                $index++;

                $target_photo = $image
                ->storePubliclyAs(
                    'close/single-form',
                    $filename,
                    'public'
                );

                // $data->target_photo = $target_photo;
            }   
            $data->target_photo  = json_encode($filenames); 
        }
        
        
        $data->satker_id = $request->satker_id;
        $data->close_procedure_type = $request->procedure_type;

        if($request->procedure_type == "observation" ||$request->procedure_type == "all"  ){
            $data->observation_surat_perintah = $request->observation_surat_perintah;
            $data->observation_sumber_informasi = $request->observation_sumber_informasi;
            $data->observation_detail_informasi = $request->observation_detail_informasi;
            $data->observation_ancaman_detail = $request->observation_ancaman_detail;
            $data->observation_gangguan_detail = $request->observation_gangguan_detail;
            $data->observation_hambatan_detail = $request->observation_hambatan_detail;
            $data->observation_tantangan_detail = $request->observation_tantangan_detail;
            $data->observaiton_nama_terkait = $request->observaiton_nama_terkait;

            $data->observation_nik_terkait = $request->observation_nik_terkait;
            $data->observation_jenis_kelamin_terkait = $request->observation_jenis_kelamin_terkait;
            $data->observation_pekerjaan_terkait = $request->observation_pekerjaan_terkait;
            $data->observation_pendidikan_terkait = $request->observation_pendidikan_terkait;
            $data->observation_agama_terkait = $request->observation_agama_terkait;

            if ($request->hasFile('observation_upload_surat_perintah')) {
                $ext_upload_info = $request->file('observation_upload_surat_perintah')->extension();
                $upload_info = $request->file('observation_upload_surat_perintah')
                    ->storePubliclyAs(
                        'close/single-form/observation_upload_surat_perintah',
                        Str::slug('single-form observation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                        'public'
                    );

                $data->observation_upload_surat_perintah = $upload_info;
            }

            $filenames = [];
            $index = 1;
            if($request->file('observation_foto_terkait') != null){
                foreach ($request->file('observation_foto_terkait') as $image) {
                    $filename = time(). ' - '. $request->observaiton_nama_terkait.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'close/single-form/observation_foto_terkait',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                } 
                $data->observation_foto_terkait  = json_encode($filenames);   
            }
            
        }

        if($request->procedure_type == "delineation" ||$request->procedure_type == "all"  ){
            $data->delineation_informasi_verifikasi_kredibilitas_sumber = $request->delineation_informasi_verifikasi_kredibilitas_sumber;
            $data->delineation_informasi_verifikasi_metode_verifikasi = $request->delineation_informasi_verifikasi_metode_verifikasi;
            $data->delineation_informasi_verifikasi_tanggal_verifikasi = $request->delineation_informasi_verifikasi_tanggal_verifikasi;
            $data->delineation_informasi_verifikasi_detail_informasi = $request->delineation_informasi_verifikasi_detail_informasi;
            $data->delineation_informasi_validasi_metode_validasi = $request->delineation_informasi_validasi_metode_validasi;
            $data->delineation_informasi_validasi_tanggal_validasi = $request->delineation_informasi_validasi_tanggal_validasi;
            $data->delineation_informasi_validasi_hasil_validasi = $request->delineation_informasi_validasi_hasil_validasi;
            $data->delineation_identitas_terhubung_subjek_utama = $request->delineation_identitas_terhubung_subjek_utama;

            $data->delineation_identitas_terhubung_subjek_terkait = $request->delineation_identitas_terhubung_subjek_terkait;
            $data->delineation_identitas_terhubung_jenis_relasi = $request->delineation_identitas_terhubung_jenis_relasi;
            $data->delineation_identitas_terhubung_kekuatan_relasi = $request->delineation_identitas_terhubung_kekuatan_relasi;
            

          
        }

        if($request->procedure_type == "exploration" ||$request->procedure_type == "all"  ){
            $data->exploration_rencana_aksi = $request->exploration_rencana_aksi;
            $data->exploration_identitas_terhubung_nama_target = $request->exploration_identitas_terhubung_nama_target;
            $data->exploration_identitas_terhubung_nomor_identitas_target = $request->exploration_identitas_terhubung_nomor_identitas_target;
            $data->exploration_identitas_terhubung_jenis_kelamin_target = $request->exploration_identitas_terhubung_jenis_kelamin_target;
            $data->exploration_identitas_terhubung_pekerjaan_target = $request->exploration_identitas_terhubung_pekerjaan_target;
            $data->exploration_identitas_terhubung_pendidikan_target = $request->exploration_identitas_terhubung_pendidikan_target;
            $data->exploration_identitas_terhubung_agama_target = $request->exploration_identitas_terhubung_agama_target;
            $data->exploration_hasil_yang_dicapai = $request->exploration_hasil_yang_dicapai;

            $filenames = [];
            $index = 1;
            if($request->file('exploration_identitas_terhubung_foto_target') != null){
                foreach ($request->file('exploration_identitas_terhubung_foto_target') as $image) {
                    $filename = time(). ' - '. $request->exploration_identitas_terhubung_nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'close/single-form/exploration_identitas_terhubung_foto_target',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                } 
                $data->exploration_identitas_terhubung_foto_target  = json_encode($filenames);   
            }

        }

        $document_tailing_upload_video1 =new VideoDocuments;
        $document_tailing_upload_video2 =new VideoDocuments;
        if($request->procedure_type == "tailing" ||$request->procedure_type == "all"  ){
            
            $data->tailing_pemahaman_perilaku_nama = $request->tailing_pemahaman_perilaku_nama;
            $data->tailing_pemahaman_perilaku_nomor_identitas = $request->tailing_pemahaman_perilaku_nomor_identitas;
            $data->tailing_pemahaman_perilaku_jenis_kelamin = $request->tailing_pemahaman_perilaku_jenis_kelamin;
            $data->tailing_pemahaman_perilaku_pekerjaan = $request->tailing_pemahaman_perilaku_pekerjaan;
            $data->tailing_pemahaman_perilaku_pendidikan = $request->tailing_pemahaman_perilaku_pendidikan;
            $data->tailing_pemahaman_perilaku_agama = $request->tailing_pemahaman_perilaku_agama;
            $data->tailing_pemahaman_perilaku_perilaku_tercatat = $request->tailing_pemahaman_perilaku_perilaku_tercatat;
            $data->tailing_rencana_operasi = $request->tailing_rencana_operasi;
            $data->tailing_target_operasi = $request->tailing_target_operasi;
            $data->tailing_hasil_yang_dicapai = $request->tailing_hasil_yang_dicapai;

            $filenames = [];
            $index = 1;
            if($request->file('tailing_pemahaman_perilaku_foto') != null){
                foreach ($request->file('tailing_pemahaman_perilaku_foto') as $image) {
                    $filename = time(). ' - '. $request->tailing_pemahaman_perilaku_nama.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'close/single-form/tailing_pemahaman_perilaku_foto',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                } 
                $data->tailing_pemahaman_perilaku_foto  = json_encode($filenames);   
            }

            if ($request->hasFile('tailing_pemahaman_perilaku_upload_video')) {
         
                $ext_upload_info1 = $request->file('tailing_pemahaman_perilaku_upload_video')->extension();
                $upload_info1 = $request->file('tailing_pemahaman_perilaku_upload_video')
                    ->storePubliclyAs(
                        'close/single-form/tailing_pemahaman_perilaku_upload_video',
                        Str::slug('single-form tailing video1', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->tailing_pemahaman_perilaku_upload_video= $upload_info1;
                $data->relation_id_tailing_video_1 = Str::uuid()->toString();;

                $document_tailing_upload_video1->doc_path = $upload_info1;
                $document_tailing_upload_video1->doc_type = "video";
                $document_tailing_upload_video1->doc_status = "0";
                $document_tailing_upload_video1->doc_status_remark = "Waiting Analysis";
                $document_tailing_upload_video1->relation_id = $data->relation_id_tailing_video_1;
                $document_tailing_upload_video1->save();
                
            }
            if ($request->hasFile('tailing_target_operasi_upload_video')) {
         
                $ext_upload_info1 = $request->file('tailing_target_operasi_upload_video')->extension();
                $upload_info1 = $request->file('tailing_target_operasi_upload_video')
                    ->storePubliclyAs(
                        'close/single-form/tailing_target_operasi_upload_video',
                        Str::slug('single-form tailing video2', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->tailing_target_operasi_upload_video= $upload_info1;
                $data->relation_id_tailing_video_2 = Str::uuid()->toString();;

                $document_tailing_upload_video2->doc_path = $upload_info1;
                $document_tailing_upload_video2->doc_type = "video";
                $document_tailing_upload_video2->doc_status = "0";
                $document_tailing_upload_video2->doc_status_remark = "Waiting Analysis";
                $document_tailing_upload_video2->relation_id = $data->relation_id_tailing_video_2;
                $document_tailing_upload_video2->save();
                
            }
        }

        $document_infiltration_upload_video1 =new VideoDocuments;
        $document_infiltration_upload_video2 =new VideoDocuments;
        if($request->procedure_type == "infiltration" ||$request->procedure_type == "all"  ){
            $data->infiltration_nama_operasi_rahasia = $request->infiltration_nama_operasi_rahasia;
            $data->infiltration_metode_eksekusi = $request->infiltration_metode_eksekusi;
            $data->infiltration_dinamika_teramati = $request->infiltration_dinamika_teramati;
            $data->infiltration_hasil_yang_dicapai = $request->infiltration_hasil_yang_dicapai;
            
            if ($request->hasFile('infiltration_operasi_rahasia_upload_video')) {
         
                $ext_upload_info1 = $request->file('infiltration_operasi_rahasia_upload_video')->extension();
                $upload_info1 = $request->file('infiltration_operasi_rahasia_upload_video')
                    ->storePubliclyAs(
                        'close/single-form/infiltration_operasi_rahasia_upload_video',
                        Str::slug('single-form infiltration video1', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->infiltration_operasi_rahasia_upload_video= $upload_info1;
                $data->relation_id_infiltration_video_1 = Str::uuid()->toString();;

                $document_infiltration_upload_video1->doc_path = $upload_info1;
                $document_infiltration_upload_video1->doc_type = "video";
                $document_infiltration_upload_video1->doc_status = "0";
                $document_infiltration_upload_video1->doc_status_remark = "Waiting Analysis";
                $document_infiltration_upload_video1->relation_id = $data->relation_id_infiltration_video_1;
                $document_infiltration_upload_video1->save();
                
            }
            if ($request->hasFile('infiltration_dinamika_teramati_upload_video')) {
         
                $ext_upload_info1 = $request->file('infiltration_dinamika_teramati_upload_video')->extension();
                $upload_info1 = $request->file('infiltration_dinamika_teramati_upload_video')
                    ->storePubliclyAs(
                        'close/single-form/infiltration_dinamika_teramati_upload_video',
                        Str::slug('single-form infiltration video2', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->infiltration_dinamika_teramati_upload_video= $upload_info1;
                $data->relation_id_infiltration_video_2 = Str::uuid()->toString();;

                $document_infiltration_upload_video2->doc_path = $upload_info1;
                $document_infiltration_upload_video2->doc_type = "video";
                $document_infiltration_upload_video2->doc_status = "0";
                $document_infiltration_upload_video2->doc_status_remark = "Waiting Analysis";
                $document_infiltration_upload_video2->relation_id = $data->relation_id_infiltration_video_2;
                $document_infiltration_upload_video2->save();
                
            }
        }

        if($request->procedure_type == "intrusion" ||$request->procedure_type == "all"  ){
            $data->intrusion_nama = $request->intrusion_nama;
            $data->intrusion_nomor_identitas = $request->intrusion_nomor_identitas;
            $data->intrusion_jenis_kelamin = $request->intrusion_jenis_kelamin;
            $data->intrusion_pekerjaan = $request->intrusion_pekerjaan;
            $data->intrusion_pendidikan = $request->intrusion_pendidikan;
            $data->intrusion_agama = $request->intrusion_agama;
            $data->intrusion_deskripsi_lokasi = $request->intrusion_deskripsi_lokasi;
            $data->intrusion_tipe_lingkungan = $request->intrusion_tipe_lingkungan;
            $data->intrusion_deskripsi_lingkungan = $request->intrusion_deskripsi_lingkungan;
            $data->intrusion_hasil_yang_dicapai = $request->intrusion_hasil_yang_dicapai;

            $filenames = [];
            $index = 1;
            if($request->file('intrusion_foto') != null){
                foreach ($request->file('intrusion_foto') as $image) {
                    $filename = time(). ' - '. $request->intrusion_nama.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'close/single-form/intrusion_foto',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                } 
                $data->intrusion_foto  = json_encode($filenames);   
            }
        }

        if($request->procedure_type == "tapping" ||$request->procedure_type == "all"  ){
            
            $data->tapping_sumber_data = $request->tapping_sumber_data;
            $data->tapping_metode_penyadapan = $request->tapping_metode_penyadapan;
            $data->tapping_jenis_sinyal = $request->tapping_jenis_sinyal;
            $data->tapping_deskripsi_hasil_sinyal = $request->tapping_deskripsi_hasil_sinyal;
            $data->tapping_hasil_yang_dicapai = $request->tapping_hasil_yang_dicapai;
            
            $document_tapping_upload_video1 =new VideoDocuments;
            if ($request->hasFile('tapping_data_perangkat_elektronik_upload_video')) {
         
                $ext_upload_info1 = $request->file('tapping_data_perangkat_elektronik_upload_video')->extension();
                $upload_info1 = $request->file('tapping_data_perangkat_elektronik_upload_video')
                    ->storePubliclyAs(
                        'close/single-form/tapping_data_perangkat_elektronik_upload_video',
                        Str::slug('single-form tapping video1', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                $data->tapping_data_perangkat_elektronik_upload_video= $upload_info1;
                $data->relation_id_tapping_video_1 = Str::uuid()->toString();;

                $document_tapping_upload_video1->doc_path = $upload_info1;
                $document_tapping_upload_video1->doc_type = "video";
                $document_tapping_upload_video1->doc_status = "0";
                $document_tapping_upload_video1->doc_status_remark = "Waiting Analysis";
                $document_tapping_upload_video1->relation_id = $data->relation_id_tapping_video_1;
                $document_tapping_upload_video1->save();
                
            }

        }

        if ($data->save()) {
            return redirect()->route('close.singleform.single-form.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'procedure_type' => 'required|string|max:128',
            'case_name'  => 'required|string|max:128',
            'case_date'  => 'required|string|max:128',
            'case_description'  => 'required|string|max:128',
            'satker_id'  => 'required|string|max:128',

            'nik'  => 'required|string|max:128',
            'target_name'  => 'required|string|max:128',
            'target_religion'  => 'required|string|max:128',
            'target_education' => 'required|string|max:128',
            'target_occupation' => 'required|string|max:128',
            'target_gender' => 'required|string|max:128',

            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // 'research_dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            // 'research_video_upload' => 'nullable|file|mimes:mp4|max:200048',

            // 'interview_interviewer_name' => 'required|string|max:128',
            // 'interview_interviewer_schedule' => 'required|string|max:128',
            // 'interview_target_name' => 'required|string|max:128',
            // 'interview_nik' => 'required|string|max:128',
        ]);

        $user = auth()->user();
        

        $data = CloseCaseSingleForm::find($id);
        $data->created_by = $user->id;
        $data->case_name = $request->case_name;
        $data->case_date = $request->case_date;
        $data->case_description = $request->case_description;

        $data->target_name = $request->target_name;
        $data->target_identity_number = $request->nik;
        $data->target_religion = $request->target_religion;
        $data->target_education = $request->target_education;
        $data->target_occupation = $request->target_occupation;
        $data->target_gender = $request->target_gender;
        $data->target_address = $request->target_address;

        // save the image first
        $filenames = [];
        $index = 1;
        if($request->file('target_image') != null){
            foreach ($request->file('target_image') as $image) {
                $filename = time(). ' - '. $request->target_name.' - '. $index . '.'. $image->getClientOriginalExtension();
                
                
                // $image->move($folderPath, $filename);
                $filenames[] = $filename;
                $index++;

                $target_photo = $image
                ->storePubliclyAs(
                    'close/single-form',
                    $filename,
                    'public'
                );

                // $data->target_photo = $target_photo;
            }    
            $data->target_photo  = json_encode($filenames);
        }
        
        $data->satker_id = $request->satker_id;
        $data->close_procedure_type = $request->procedure_type;

        if($request->procedure_type == "observation" ||$request->procedure_type == "all"  ){
            $data->observation_surat_perintah = $request->observation_surat_perintah;
            $data->observation_sumber_informasi = $request->observation_sumber_informasi;
            $data->observation_detail_informasi = $request->observation_detail_informasi;
            $data->observation_ancaman_detail = $request->observation_ancaman_detail;
            $data->observation_gangguan_detail = $request->observation_gangguan_detail;
            $data->observation_hambatan_detail = $request->observation_hambatan_detail;
            $data->observation_tantangan_detail = $request->observation_tantangan_detail;
            $data->observaiton_nama_terkait = $request->observaiton_nama_terkait;

            $data->observation_nik_terkait = $request->observation_nik_terkait;
            $data->observation_jenis_kelamin_terkait = $request->observation_jenis_kelamin_terkait;
            $data->observation_pekerjaan_terkait = $request->observation_pekerjaan_terkait;
            $data->observation_pendidikan_terkait = $request->observation_pendidikan_terkait;
            $data->observation_agama_terkait = $request->observation_agama_terkait;

            if ($request->hasFile('observation_upload_surat_perintah')) {
                $ext_upload_info = $request->file('observation_upload_surat_perintah')->extension();
                $upload_info = $request->file('observation_upload_surat_perintah')
                    ->storePubliclyAs(
                        'close/single-form/observation_upload_surat_perintah',
                        Str::slug('single-form observation document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                        'public'
                    );

                $data->observation_upload_surat_perintah = $upload_info;
            }

            $filenames = [];
            $index = 1;
            if($request->file('observation_foto_terkait') != null){
                foreach ($request->file('observation_foto_terkait') as $image) {
                    $filename = time(). ' - '. $request->observaiton_nama_terkait.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'close/single-form/observation_foto_terkait',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                } 
                $data->observation_foto_terkait  = json_encode($filenames);   
            }
            
        }

        if($request->procedure_type == "delineation" ||$request->procedure_type == "all"  ){
            $data->delineation_informasi_verifikasi_kredibilitas_sumber = $request->delineation_informasi_verifikasi_kredibilitas_sumber;
            $data->delineation_informasi_verifikasi_metode_verifikasi = $request->delineation_informasi_verifikasi_metode_verifikasi;
            $data->delineation_informasi_verifikasi_tanggal_verifikasi = $request->delineation_informasi_verifikasi_tanggal_verifikasi;
            $data->delineation_informasi_verifikasi_detail_informasi = $request->delineation_informasi_verifikasi_detail_informasi;
            $data->delineation_informasi_validasi_metode_validasi = $request->delineation_informasi_validasi_metode_validasi;
            $data->delineation_informasi_validasi_tanggal_validasi = $request->delineation_informasi_validasi_tanggal_validasi;
            $data->delineation_informasi_validasi_hasil_validasi = $request->delineation_informasi_validasi_hasil_validasi;
            $data->delineation_identitas_terhubung_subjek_utama = $request->delineation_identitas_terhubung_subjek_utama;

            $data->delineation_identitas_terhubung_subjek_terkait = $request->delineation_identitas_terhubung_subjek_terkait;
            $data->delineation_identitas_terhubung_jenis_relasi = $request->delineation_identitas_terhubung_jenis_relasi;
            $data->delineation_identitas_terhubung_kekuatan_relasi = $request->delineation_identitas_terhubung_kekuatan_relasi;
            

          
        }

        if($request->procedure_type == "exploration" ||$request->procedure_type == "all"  ){
            $data->exploration_rencana_aksi = $request->exploration_rencana_aksi;
            $data->exploration_identitas_terhubung_nama_target = $request->exploration_identitas_terhubung_nama_target;
            $data->exploration_identitas_terhubung_nomor_identitas_target = $request->exploration_identitas_terhubung_nomor_identitas_target;
            $data->exploration_identitas_terhubung_jenis_kelamin_target = $request->exploration_identitas_terhubung_jenis_kelamin_target;
            $data->exploration_identitas_terhubung_pekerjaan_target = $request->exploration_identitas_terhubung_pekerjaan_target;
            $data->exploration_identitas_terhubung_pendidikan_target = $request->exploration_identitas_terhubung_pendidikan_target;
            $data->exploration_identitas_terhubung_agama_target = $request->exploration_identitas_terhubung_agama_target;
            $data->exploration_hasil_yang_dicapai = $request->exploration_hasil_yang_dicapai;

            $filenames = [];
            $index = 1;
            if($request->file('exploration_identitas_terhubung_foto_target') != null){
                foreach ($request->file('exploration_identitas_terhubung_foto_target') as $image) {
                    $filename = time(). ' - '. $request->exploration_identitas_terhubung_nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'close/single-form/exploration_identitas_terhubung_foto_target',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                } 
                $data->exploration_identitas_terhubung_foto_target  = json_encode($filenames);   
            }

        }

        $document_tailing_upload_video1 =new VideoDocuments;
        $document_tailing_upload_video2 =new VideoDocuments;
        if($request->procedure_type == "tailing" ||$request->procedure_type == "all"  ){
            
            $data->tailing_pemahaman_perilaku_nama = $request->tailing_pemahaman_perilaku_nama;
            $data->tailing_pemahaman_perilaku_nomor_identitas = $request->tailing_pemahaman_perilaku_nomor_identitas;
            $data->tailing_pemahaman_perilaku_jenis_kelamin = $request->tailing_pemahaman_perilaku_jenis_kelamin;
            $data->tailing_pemahaman_perilaku_pekerjaan = $request->tailing_pemahaman_perilaku_pekerjaan;
            $data->tailing_pemahaman_perilaku_pendidikan = $request->tailing_pemahaman_perilaku_pendidikan;
            $data->tailing_pemahaman_perilaku_agama = $request->tailing_pemahaman_perilaku_agama;
            $data->tailing_pemahaman_perilaku_perilaku_tercatat = $request->tailing_pemahaman_perilaku_perilaku_tercatat;
            $data->tailing_rencana_operasi = $request->tailing_rencana_operasi;
            $data->tailing_target_operasi = $request->tailing_target_operasi;
            $data->tailing_hasil_yang_dicapai = $request->tailing_hasil_yang_dicapai;

            $filenames = [];
            $index = 1;
            if($request->file('tailing_pemahaman_perilaku_foto') != null){
                foreach ($request->file('tailing_pemahaman_perilaku_foto') as $image) {
                    $filename = time(). ' - '. $request->tailing_pemahaman_perilaku_nama.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'close/single-form/tailing_pemahaman_perilaku_foto',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                } 
                $data->tailing_pemahaman_perilaku_foto  = json_encode($filenames);   
            }

            if ($request->hasFile('tailing_pemahaman_perilaku_upload_video')) {
         
                $ext_upload_info1 = $request->file('tailing_pemahaman_perilaku_upload_video')->extension();
                $upload_info1 = $request->file('tailing_pemahaman_perilaku_upload_video')
                    ->storePubliclyAs(
                        'close/single-form/tailing_pemahaman_perilaku_upload_video',
                        Str::slug('single-form tailing video1', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->tailing_pemahaman_perilaku_upload_video= $upload_info1;
                $data->relation_id_tailing_video_1 = Str::uuid()->toString();;

                $document_tailing_upload_video1->doc_path = $upload_info1;
                $document_tailing_upload_video1->doc_type = "video";
                $document_tailing_upload_video1->doc_status = "0";
                $document_tailing_upload_video1->doc_status_remark = "Waiting Analysis";
                $document_tailing_upload_video1->relation_id = $data->relation_id_tailing_video_1;
                $document_tailing_upload_video1->save();
                
            }
            if ($request->hasFile('tailing_target_operasi_upload_video')) {
         
                $ext_upload_info1 = $request->file('tailing_target_operasi_upload_video')->extension();
                $upload_info1 = $request->file('tailing_target_operasi_upload_video')
                    ->storePubliclyAs(
                        'close/single-form/tailing_target_operasi_upload_video',
                        Str::slug('single-form tailing video2', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->tailing_target_operasi_upload_video= $upload_info1;
                $data->relation_id_tailing_video_2 = Str::uuid()->toString();;

                $document_tailing_upload_video2->doc_path = $upload_info1;
                $document_tailing_upload_video2->doc_type = "video";
                $document_tailing_upload_video2->doc_status = "0";
                $document_tailing_upload_video2->doc_status_remark = "Waiting Analysis";
                $document_tailing_upload_video2->relation_id = $data->relation_id_tailing_video_2;
                $document_tailing_upload_video2->save();
                
            }
        }

        $document_infiltration_upload_video1 =new VideoDocuments;
        $document_infiltration_upload_video2 =new VideoDocuments;
        if($request->procedure_type == "infiltration" ||$request->procedure_type == "all"  ){
            $data->infiltration_nama_operasi_rahasia = $request->infiltration_nama_operasi_rahasia;
            $data->infiltration_metode_eksekusi = $request->infiltration_metode_eksekusi;
            $data->infiltration_dinamika_teramati = $request->infiltration_dinamika_teramati;
            $data->infiltration_hasil_yang_dicapai = $request->infiltration_hasil_yang_dicapai;
            
            if ($request->hasFile('infiltration_operasi_rahasia_upload_video')) {
         
                $ext_upload_info1 = $request->file('infiltration_operasi_rahasia_upload_video')->extension();
                $upload_info1 = $request->file('infiltration_operasi_rahasia_upload_video')
                    ->storePubliclyAs(
                        'close/single-form/infiltration_operasi_rahasia_upload_video',
                        Str::slug('single-form infiltration video1', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->infiltration_operasi_rahasia_upload_video= $upload_info1;
                $data->relation_id_infiltration_video_1 = Str::uuid()->toString();;

                $document_infiltration_upload_video1->doc_path = $upload_info1;
                $document_infiltration_upload_video1->doc_type = "video";
                $document_infiltration_upload_video1->doc_status = "0";
                $document_infiltration_upload_video1->doc_status_remark = "Waiting Analysis";
                $document_infiltration_upload_video1->relation_id = $data->relation_id_infiltration_video_1;
                $document_infiltration_upload_video1->save();
                
            }
            if ($request->hasFile('infiltration_dinamika_teramati_upload_video')) {
         
                $ext_upload_info1 = $request->file('infiltration_dinamika_teramati_upload_video')->extension();
                $upload_info1 = $request->file('infiltration_dinamika_teramati_upload_video')
                    ->storePubliclyAs(
                        'close/single-form/infiltration_dinamika_teramati_upload_video',
                        Str::slug('single-form infiltration video2', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                    
                $data->infiltration_dinamika_teramati_upload_video= $upload_info1;
                $data->relation_id_infiltration_video_2 = Str::uuid()->toString();;

                $document_infiltration_upload_video2->doc_path = $upload_info1;
                $document_infiltration_upload_video2->doc_type = "video";
                $document_infiltration_upload_video2->doc_status = "0";
                $document_infiltration_upload_video2->doc_status_remark = "Waiting Analysis";
                $document_infiltration_upload_video2->relation_id = $data->relation_id_infiltration_video_2;
                $document_infiltration_upload_video2->save();
                
            }
        }

        if($request->procedure_type == "intrusion" ||$request->procedure_type == "all"  ){
            $data->intrusion_nama = $request->intrusion_nama;
            $data->intrusion_nomor_identitas = $request->intrusion_nomor_identitas;
            $data->intrusion_jenis_kelamin = $request->intrusion_jenis_kelamin;
            $data->intrusion_pekerjaan = $request->intrusion_pekerjaan;
            $data->intrusion_pendidikan = $request->intrusion_pendidikan;
            $data->intrusion_agama = $request->intrusion_agama;
            $data->intrusion_deskripsi_lokasi = $request->intrusion_deskripsi_lokasi;
            $data->intrusion_tipe_lingkungan = $request->intrusion_tipe_lingkungan;
            $data->intrusion_deskripsi_lingkungan = $request->intrusion_deskripsi_lingkungan;
            $data->intrusion_hasil_yang_dicapai = $request->intrusion_hasil_yang_dicapai;

            $filenames = [];
            $index = 1;
            if($request->file('intrusion_foto') != null){
                foreach ($request->file('intrusion_foto') as $image) {
                    $filename = time(). ' - '. $request->intrusion_nama.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'close/single-form/intrusion_foto',
                        $filename,
                        'public'
                    );

                    // $data->target_photo = $target_photo;
                } 
                $data->intrusion_foto  = json_encode($filenames);   
            }
        }

        if($request->procedure_type == "tapping" ||$request->procedure_type == "all"  ){
            
            $data->tapping_sumber_data = $request->tapping_sumber_data;
            $data->tapping_metode_penyadapan = $request->tapping_metode_penyadapan;
            $data->tapping_jenis_sinyal = $request->tapping_jenis_sinyal;
            $data->tapping_deskripsi_hasil_sinyal = $request->tapping_deskripsi_hasil_sinyal;
            $data->tapping_hasil_yang_dicapai = $request->tapping_hasil_yang_dicapai;
            
            $document_tapping_upload_video1 =new VideoDocuments;
            if ($request->hasFile('tapping_data_perangkat_elektronik_upload_video')) {
         
                $ext_upload_info1 = $request->file('tapping_data_perangkat_elektronik_upload_video')->extension();
                $upload_info1 = $request->file('tapping_data_perangkat_elektronik_upload_video')
                    ->storePubliclyAs(
                        'close/single-form/tapping_data_perangkat_elektronik_upload_video',
                        Str::slug('single-form tapping video1', '_') . '_' . Str::random() . '.' . $ext_upload_info1,
                        'public'
                    );
                $data->tapping_data_perangkat_elektronik_upload_video= $upload_info1;
                $data->relation_id_tapping_video_1 = Str::uuid()->toString();;

                $document_tapping_upload_video1->doc_path = $upload_info1;
                $document_tapping_upload_video1->doc_type = "video";
                $document_tapping_upload_video1->doc_status = "0";
                $document_tapping_upload_video1->doc_status_remark = "Waiting Analysis";
                $document_tapping_upload_video1->relation_id = $data->relation_id_tapping_video_1;
                $document_tapping_upload_video1->save();
                
            }

        }

        if ($data->update()) {
            return redirect()->route('close.singleform.single-form.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }


    public function edit(Request $request, $id)
    {
        $data = CloseCaseSingleForm::find($id);
        $satker = DataHelper::getSatker();
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.single-form.edit', 
        compact('data', 'satker',  ));
    }

    public function destroy(Request $request, $id)
    {
        $data = CloseCaseSingleForm::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        
        $data->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadDokumen($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

    public function downloadFile($id_case)
    {
        $data = CloseCaseSingleForm::find(decrypt($id_case));
        $images = [];

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);
            foreach ($imagePaths as $imagePath) {
                $images[] =Storage::url('close/single-form/' . $imagePath);
            }
        }

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.close.single-form.pdf", compact(
            'data',
        'images')));

        $filename = 'Close_Single_Form_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    public function uploadVideo1(Request $request)
    {
        $video = $request->file('video'); // Mengambil file video dari FormData

        // Mendapatkan id dari request
        $id = $request->input('id');
        $type = $request->input('type');

        if($type == "tailing_pemahaman_perilaku"){
            if ($video) {
                $filename = 'pemahaman_perilaku_' . time() . '.mp4';
                $path = 'close/single-form/tailing-pemahaman-perilaku-video_upload/' . $filename;
    
                $data_interview_hasil = CloseCaseSingleForm::where('id', $id)->first();
                $data_interview_hasil->relation_id_tailing_video_1 = Str::uuid()->toString();
                $data_interview_hasil->tailing_pemahaman_perilaku_upload_video = $path;
                $data_interview_hasil->update();

                $document_video = new VideoDocuments;
                $document_video->doc_path = $path;
                $document_video->doc_status = "0";
                $document_video->doc_type = "video";
                $document_video->doc_status_remark = "Waiting Analysis";
                $document_video->relation_id = $data_interview_hasil->relation_id_tailing_video_1;
                $document_video->save();
        
                Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

                return response()->json(['success' => true, 'path' => $path]);
            }

            return response()->json(['success' => false, 'message' => 'No video data uploaded']);

        }

        if($type == "tailing_target_operasi"){
            if ($video) {
                $filename = 'target_operasi_' . time() . '.mp4';
                $path = 'close/single-form/tailing-target-operasi-video_upload/' . $filename;
    
                $data_interview_hasil = CloseCaseSingleForm::where('id', $id)->first();
                $data_interview_hasil->relation_id_tailing_video_2 = Str::uuid()->toString();
                $data_interview_hasil->tailing_target_operasi_upload_video = $path;
                $data_interview_hasil->update();

                $document_video = new VideoDocuments;
                $document_video->doc_path = $path;
                $document_video->doc_status = "0";
                $document_video->doc_type = "video";
                $document_video->doc_status_remark = "Waiting Analysis";
                $document_video->relation_id = $data_interview_hasil->relation_id_tailing_video_2;
                $document_video->save();
        
                Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

                return response()->json(['success' => true, 'path' => $path]);
            }

            return response()->json(['success' => false, 'message' => 'No video data uploaded']);

        }

        if($type == "infiltration_operasi_rahasia"){
            if ($video) {
                $filename = 'operasi_rahasia_' . time() . '.mp4';
                $path = 'close/single-form/infiltration-operasi-rahasia-video_upload/' . $filename;
    
                $data_interview_hasil = CloseCaseSingleForm::where('id', $id)->first();
                $data_interview_hasil->relation_id_infiltration_video_1 = Str::uuid()->toString();
                $data_interview_hasil->infiltration_operasi_rahasia_upload_video = $path;
                $data_interview_hasil->update();

                $document_video = new VideoDocuments;
                $document_video->doc_path = $path;
                $document_video->doc_status = "0";
                $document_video->doc_type = "video";
                $document_video->doc_status_remark = "Waiting Analysis";
                $document_video->relation_id = $data_interview_hasil->relation_id_infiltration_video_1;
                $document_video->save();
        
                Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

                return response()->json(['success' => true, 'path' => $path]);
            }

            return response()->json(['success' => false, 'message' => 'No video data uploaded']);

        }

        if($type == "infiltration_dinamika_teramati"){
            if ($video) {
                $filename = 'dinamika_teramati_' . time() . '.mp4';
                $path = 'close/single-form/infiltration-dinamika-teramati-video_upload/' . $filename;
    
                $data_interview_hasil = CloseCaseSingleForm::where('id', $id)->first();
                $data_interview_hasil->relation_id_infiltration_video_2 = Str::uuid()->toString();
                $data_interview_hasil->infiltration_dinamika_teramati_upload_video = $path;
                $data_interview_hasil->update();

                $document_video = new VideoDocuments;
                $document_video->doc_path = $path;
                $document_video->doc_status = "0";
                $document_video->doc_type = "video";
                $document_video->doc_status_remark = "Waiting Analysis";
                $document_video->relation_id = $data_interview_hasil->relation_id_infiltration_video_2;
                $document_video->save();
        
                Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

                return response()->json(['success' => true, 'path' => $path]);
            }

            return response()->json(['success' => false, 'message' => 'No video data uploaded']);

        }


        if($type == "tapping_data_perangkat_elektronik"){
            if ($video) {
                $filename = 'data_perangkat_elektronik_' . time() . '.mp4';
                $path = 'close/single-form/tapping-data-perangkat-elektronik-video_upload/' . $filename;
    
                $data_interview_hasil = CloseCaseSingleForm::where('id', $id)->first();
                $data_interview_hasil->relation_id_tapping_video_1 = Str::uuid()->toString();
                $data_interview_hasil->tapping_data_perangkat_elektronik_upload_video = $path;
                $data_interview_hasil->update();

                $document_video = new VideoDocuments;
                $document_video->doc_path = $path;
                $document_video->doc_status = "0";
                $document_video->doc_type = "video";
                $document_video->doc_status_remark = "Waiting Analysis";
                $document_video->relation_id = $data_interview_hasil->relation_id_tapping_video_1;
                $document_video->save();
        
                Storage::disk('public')->put($path, file_get_contents($video->getRealPath()));

                return response()->json(['success' => true, 'path' => $path]);
            }

            return response()->json(['success' => false, 'message' => 'No video data uploaded']);

        }
        
        
    }
}
