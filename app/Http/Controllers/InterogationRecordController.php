<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\User;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\CaseProgresses;
use Illuminate\Support\Carbon;
use App\Models\InterogationRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\InterogationTargetIdentification;
use App\DataTables\Interogation\InterogationRecordDataTable;
use App\Models\MasterPegawai;

use App\Models\InterogationResultAchievement;

class InterogationRecordController extends Controller
{

    public function __construct()
    {
        Carbon::setLocale('id');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(InterogationRecordDataTable $dataTable)
    {
        //
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();
        return $dataTable->render('backoffice.open.interogation-record.index', compact('satker', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCaseValidInterogationRecord();
        $agama = DataHelper::getAgama();
        $pekerjaan = DataHelper::getPekerjaan();
        $pendidikan = DataHelper::getPendidikan();
        $listPegawai = DataHelper::getPegawai();
        return view('backoffice.open.interogation-record.create', compact('case','satker', 'agama', 'pekerjaan', 'pendidikan', 'listPegawai'));
    }

    public function upload()
    {
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCase();
        $satker = DataHelper::getSatkerGlobal();
        $listPegawai = DataHelper::getPegawai();

        return view('backoffice.open.interogation-record.upload', compact('users', 'case', 'satker', 'listPegawai'));
    }

    public function storeUpload(Request $request)
    {
        //
        $this->validate($request, [
            'satker_id' => 'required',
            'case_id' => 'required',
            'upload_berita_acara' => 'nullable|mimes:pdf|max:30000',
        ]);

        $user = auth()->user();

        $data = new InterogationRecord();
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;

        if ($request->hasFile('upload_berita_acara')) {
            $ext_upload_berita_acara = $request->file('upload_berita_acara')->extension();
            $upload_berita_acara = $request->file('upload_berita_acara')
                ->storePubliclyAs(
                    'open/data/interogation',
                    Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_upload_berita_acara,
                    'public'
                );

            $data->berita_acara_path = $upload_berita_acara;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        
        if ($request->submit_type === 'save') {
            $updateCaseProgresses = CaseProgresses::where('case_id', $data->case_id)->first();
            $updateCaseProgresses->interogasi_berita_acara = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Berita Acara';
            $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 58.8 ? $updateCaseProgresses->percentage : 58.8;
            $updateCaseProgresses->save();

            $cp = new CaseEventHistoricalUpdates;
            $cp->case_id = $request->case_id;
            $cp->action = 'Penambahan Interogasi Berita Acara';
            $cp->created_by = $user->id;
            $cp->save();      
            
            if ($data->save()) {

                return redirect()->route('open.data.interrog-record.index')->with("success", "Data berhasil ditambah.");
            }

            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }else{
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->interogasi_berita_acara = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Berita Acara';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();

            $cp = new CaseEventHistoricalUpdates;
            $cp->case_id = $request->case_id;
            $cp->action = 'Penambahan Interogasi Berita Acara';
            $cp->created_by = $user->id;
            $cp->save();               

            if ($data->save()) {

                return redirect()->route('open.data.interrog-record.index')->with("success", "Data berhasil ditambah.");
            }

            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }
        
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'satker_id' => 'required',
            'case_id' => 'required',
            'no_surat' => 'required',
            'tanggal_surat' => 'required',
            'perihal' => 'required',
            'nik' => 'required',
            'nama_target' => 'required',
            'hasil' => 'required',
            'pegawai' => 'required',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:20048',
        ]);

        $user = auth()->user();

        $data = new InterogationRecord();
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->letter_number = $request->no_surat;
        $data->letter_date = $request->tanggal_surat;
        $data->perihal = $request->perihal;
        $data->target_name = $request->nama_target;
        $data->target_identity_number = $request->nik;
        $data->target_type_identity_number = 'NIK/KTP';
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;
        $data->born_place = $request->born_place;
        $data->born_date = $request->born_date;
        $data->phone_number = $request->phone_number;
        $data->nationality = $request->nationality;
        $data->target_address = $request->alamat;
        $data->hasil = $request->hasil;
        $data->jaksa = json_encode($request->pegawai);

        if ($request->hasFile('upload_berita_acara')) {
            $ext_upload_berita_acara = $request->file('upload_berita_acara')->extension();
            $upload_berita_acara = $request->file('upload_berita_acara')
                ->storePubliclyAs(
                    'open/data/interogation',
                    Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_upload_berita_acara,
                    'public'
                );

            $data->berita_acara_path = $upload_berita_acara;
        }

        // if ($request->hasFile('foto')) {
        //     $ext_foto = $request->file('foto')->extension();
        //     $foto = $request->file('foto')
        //         ->storePubliclyAs(
        //             'open/data/interogation',
        //             Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_foto,
        //             'public'
        //         );

        //     $data->target_photo = $foto;
        // }
        $folderPath =  'open/data/interogation';
        $filenames = [];
        $index = 1;

        if($request->file('image') != null){
            
            foreach ($request->file('image') as $image) {
                $filename = $image->storePubliclyAs(
                    $folderPath,
                    time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension(),
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
            $updateCaseProgresses = CaseProgresses::where('case_id', $data->case_id)->first();
            $updateCaseProgresses->interogasi_berita_acara = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Berita Acara';
            $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 58.8 ? $updateCaseProgresses->percentage : 58.8;
            $updateCaseProgresses->save();

            $cp = new CaseEventHistoricalUpdates;
            $cp->case_id = $request->case_id;
            $cp->action = 'Penambahan Interogasi Berita Acara';
            $cp->created_by = $user->id;
            $cp->save();               

            if ($data->save()) {

                return redirect()->route('open.data.interrog-record.index')->with("success", "Data berhasil ditambah.");
            }

            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }else{
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->interogasi_berita_acara = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Berita Acara';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();

            $cp = new CaseEventHistoricalUpdates;
            $cp->case_id = $request->case_id;
            $cp->action = 'Penambahan Interogasi Berita Acara';
            $cp->created_by = $user->id;
            $cp->save();               

            if ($data->save()) {

                return redirect()->route('open.data.interrog-record.index')->with("success", "Data berhasil ditambah.");
            }

            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $data = InterogationRecord::find($id);
        $listPegawai = DataHelper::getPegawai();
        $listJaksa = [];
        if ($data->jaksa) {
            foreach (json_decode($data->jaksa) as $jaksa) {
                $foundJaksa = $listPegawai->whereIn('nip', $jaksa)->first();
                if($foundJaksa){
                    // dd($foundJaksa);
                    $listJaksa[] = $foundJaksa['text'];
                }
            }
        }
        $images = [];

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);

            foreach ($imagePaths as $imagePath) {
                $images[] = asset('storage/' . $imagePath);
            }
        }



        return view('backoffice.open.interogation-record.show', compact('data','images', 'listJaksa'));
    }

    // download BAP
    public function downloadBap($id_interogation_record)
    {

        
        $data = InterogationRecord::find(decrypt($id_interogation_record));

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        if($data->berita_acara_path){
            return Storage::disk('public')->download($data->berita_acara_path);
        }
        
        $satker = MasterSatker::find($data->satker_id);
        $listJaksa = json_decode($data->jaksa);
        $listJaksa = MasterPegawai::whereIn('nip', $listJaksa)->get();

        // dd($data, $listJaksa, $satker);

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'margin_top' => 25,    // Space between top of page and main content
            'margin_bottom' => 25,
            'format' => [215, 330]
        ]);

        $headerHTML = '<div style="text-align: center; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">RAHASIA</div>';
        $mpdf->SetHTMLHeader($headerHTML);

        $footerHTML = '<div style="text-align: center; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">RAHASIA</div>';
        $mpdf->SetHTMLFooter($footerHTML);


        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->AddPage();
        $mpdf->WriteHTML(view("backoffice.template_laporan.in-10", compact('data', 'satker', 'listJaksa')));

        $filename = 'Open_Interogation_BAP-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //edit table
        $data = InterogationRecord::find($id);
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCaseValidInterogationRecord($data->satker_id);
        $agama = DataHelper::getAgama();
        $pekerjaan = DataHelper::getPekerjaan();
        $pendidikan = DataHelper::getPendidikan();
        $listPegawai = DataHelper::getPegawai();
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        // dd($data);
        $images = [];
        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);

            foreach ($imagePaths as $imagePath) {
                $images[] = asset('storage/' . $imagePath);
            }
        }


        if($data->berita_acara_path){
            return view('backoffice.open.interogation-record.upload-edit', compact('data', 'satker', 'case', 'agama', 'pekerjaan', 'pendidikan', 'listPegawai'));
        } else{
            return view('backoffice.open.interogation-record.edit', compact(
                'data', 
                'satker',
                'case',
                'images', 
                'agama', 
                'pekerjaan', 
                'pendidikan', 
                'listPegawai'));
        }

       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            // 'satker_id' => 'required',
            'case_id' => 'required',
            'nomor_surat' => 'required',
            'tanggal_surat' => 'required',
            'perihal' => 'required',
            'nama_target' => 'required',
            'nik' => 'required',
            // 'tipe_target' => 'required',
            'jenis_kelamin' => 'nullable',
            'agama' => 'nullable',
            'pekerjaan' => 'nullable',
            'pendidikan' => 'nullable',
            'alamat' => 'nullable',
            'pegawai' => 'required',
            'hasil' => 'required',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'upload_berita_acara' => 'nullable|mimes:pdf|max:30000'
        ]);
        
        $user = auth()->user();

        $data = InterogationRecord::find($id);

        // $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->letter_number = $request->nomor_surat;
        $data->letter_date = $request->tanggal_surat;
        $data->perihal = $request->perihal;
        $data->target_name = $request->nama_target;
        $data->target_identity_number = $request->nik;
        $data->target_type_identity_number = 'NIK/KTP';
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;
        $data->target_address = $request->alamat;
        $data->born_place = $request->born_place;
        $data->born_date = $request->born_date;
        $data->phone_number = $request->phone_number;
        $data->nationality = $request->nationality;
        $data->hasil = $request->hasil;
        $data->jaksa = json_encode($request->pegawai);

        // FOTO 
        // if ($request->hasFile('foto')) {
        //     $ext_foto = $request->file('foto')->extension();
        //     $foto = $request->file('foto')
        //         ->storePubliclyAs(
        //             'open/data/interogation/',
        //             Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_foto,
        //             'public'
        //         );

        //     if (Storage::disk('public')->exists($request->foto)) {
        //         Storage::disk('public')->delete($request->foto);
        //     }

        //     $data->target_photo = $foto;
        // } else {
        //     $data->target_photo = $data->target_photo;
        // }

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
            $folderPath =  'open/data/interogation';
            foreach ($request->file('image') as $image) {
                $filename = $image->storePubliclyAs(
                    $folderPath,
                    time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension(),
                    'public'
                );
                $newImages[] = $filename;
                $index++;
            }    
        } else{
            $newImages = json_decode($data->target_photo);
        }

        $data->target_photo =json_encode($newImages);


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
            $data->berita_acara_path = $upload_berita_acara;
        } else {
            $upload_berita_acara = $request->temp_upload_berita_acara;

            $data->berita_acara_path = $upload_berita_acara;
        }

        $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Data Interogasi Berita Acara', $data->target_name);

        $data->updated_by = $user->id;
        if ($request->submit_type === 'update_and_finish') {
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->interogasi_berita_acara = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Berita Acara';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }
        if ($data->update()) {
            return redirect()->route('open.data.interrog-record.index')->with(["success" => "Data berhasil diupdate."]);
        }
        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function updateUpload(Request $request, $id)
    {
        //
        $this->validate($request, [
            'satker_id' => 'required',
            'case_id' => 'required',
            'upload_berita_acara' => 'nullable|mimes:pdf|max:30000',
        ]);
        
        $user = auth()->user();

        $data = InterogationRecord::find($id);

        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;

        // DOKUMEN
        if ($request->hasFile('upload_berita_acara')) {
            $ext_upload_berita_acara = $request->file('upload_berita_acara')->extension();
            $upload_berita_acara = $request->file('upload_berita_acara')
                ->storePubliclyAs(
                    'open/data/interogation/',
                    Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_upload_berita_acara,
                    'public'
                );

            
            $data->berita_acara_path = $upload_berita_acara;
        } else {
            $upload_berita_acara = $request->temp_upload_berita_acara;

            $data->berita_acara_path = $upload_berita_acara;
        }

        $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Data Interogasi Berita Acara', $data->target_name);

        $data->updated_by = $user->id;
        if ($request->submit_type === 'update_and_finish') {
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->interogasi_berita_acara = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Berita Acara';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }
        if ($data->update()) {
            return redirect()->route('open.data.interrog-record.index')->with(["success" => "Data berhasil diupdate."]);
        }
        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        //
        $data = InterogationRecord::find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        
        $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Data Interogasi Berita Acara', $data->target_name);

        $data->delete();

        InterogationTargetIdentification::where('interogation_record_id', $id)->delete();
        InterogationResultAchievement::where('interogation_record_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

}
