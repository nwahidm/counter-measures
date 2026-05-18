<?php

namespace App\Http\Controllers;

use App\Models\Open\Research\ResearchSaranTindakLanjut;
use App\Models\Open\Research\ResearchSuratPerintah;
use Exception;
use App\Models\OpenCase;
use App\Helpers\DataHelper;
use App\Models\MasterAgama;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\CaseProgresses;
use App\Models\ElicitationAdFoll;
use App\Models\ElicitationResult;
use App\Models\InterogationRecord;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Models\ElicitationInterview;
use Illuminate\Support\Facades\Http;
use App\DataTables\OpenCaseDataTable;
use App\Models\Research\ResearchSprint;
use Illuminate\Support\Facades\Storage;
use App\Models\Interview\InterviewHasil;
use App\Models\Research\ResearchSaranTL;
use App\Models\Interview\InterviewJadwal;
use App\Models\Research\ResearchLapinsus;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\Interview\InterviewSaranTL;
use App\DataTables\SummaryOpenCaseDataTable;
use App\Models\Open\Research\ResearchPotensiAght;
use App\Models\InterogationResultAchievement;
use App\Models\InterogationTargetIdentification;

class OpenCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OpenCaseDataTable $dataTable)
    {
        return $dataTable->render('backoffice.open.case.index');
    }

    public function create()
    {
        // dd(asset('storage/'. "open_case_target_image/kejaksaan_agung/1715702976 - Sint sapiente vitae - 1.png"));
        $agama = MasterAgama::select('kode', 'nama')->get();
        $tipeIdentitas = tipeIndentitas();
        $pendidikan = DataHelper::getPendidikan();
        $pekerjaan = DataHelper::getPekerjaan();
        $jenis_kelamin = DataHelper::getListJenisKelamin();
        return view('backoffice.open.case.create', compact(
            'agama', 'tipeIdentitas', 'pendidikan', 'pekerjaan', 'jenis_kelamin'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_kasus' => 'required',
            'tanggal_kasus' => 'required|date',
            // 'deskripsi_kasus' => 'required',

            'nama_target' => 'required',
            // 'agama' => 'required',
            // 'pendidikan' => 'required',
            // 'pekerjaan' => 'required',
            // 'alamat' => 'required',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_dokumen' => 'array',
            'foto_dokumen.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();

            if(!$user){
                return redirect()->back()->with('error', "User Not Found");
            }
            
            // create folder name, based on satker name
            $satker = MasterSatker::where('master_satker.id_satker', $user->id_satker)
                ->select([
                    'master_satker.id_satker', 'master_satker.id_satker', 'master_satker.nama_satker',
                ])
                ->first();

            if($satker == null){
                return redirect()->back()->with('error', 'Satker Tidak Ditemukan!');
            }

            
            $folderName = strtolower(trim($satker->nama_satker));
            $folderName = str_replace(" ","_", $folderName);
            $folderPath = 'open_case_target_image/' . $folderName;
            $folderDocPath = 'open_case_target_doc_image/' . $folderName;

            // save the image first
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

            // save the image first
            $filenameDocs = [];
            $index = 1;
            if($request->file('foto_dokumen') != null){
                foreach ($request->file('foto_dokumen') as $foto_dokumen) {
                    $filename = $foto_dokumen->storePubliclyAs(
                        $folderDocPath,
                        time(). ' - '. $request->nama_target.' - '. $index . '.'. $foto_dokumen->getClientOriginalExtension(),
                        'public'
                    );
                    $filenameDocs[] = $filename;
                    $index++;
                }    
            }

            // store data to database
            $case = OpenCase::create([
                'id_satker' => $satker->id_satker,
                'nama_kasus' => $request->nama_kasus,
                'tanggal_kasus' => $request->tanggal_kasus,
                'deskripsi_kasus' => $request->deskripsi_kasus,

                'nama_target' => $request->nama_target,
                'tipe_identitas' => 'NIK/KTP',
                'no_identitas' => $request->nik,
                'agama' => $request->agama,
                'pendidikan' => $request->pendidikan,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'jenis_kelamin'=> $request->jenis_kelamin,
                'no_hp_target'=> $request->no_hp_target,
                'foto' => json_encode($filenames),
                'foto_dokumen' => json_encode($filenameDocs),

                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            DB::commit();

            // case progress
            $case_progress = new CaseProgresses();
            $case_progress->case_id = $case->id;
            $case_progress->penelitian_upload_surat_perintah = "0";
            $case_progress->penelitian_lapinsus = "0";
            $case_progress->penelitian_aght = "0";
            $case_progress->penelitian_laporan = "0";
            $case_progress->penelitian_saran_dan_tindak_lanjut = "0";
            $case_progress->wawancara_jadwal = "0";
            $case_progress->wawancara_laporan = "0";
            $case_progress->wawancara_hasil = "0";
            $case_progress->wawancara_saran_dan_tindak_lanjut = "0";
            $case_progress->interogasi_berita_acara = "0";
            $case_progress->interogasi_identifikasi_target = "0";
            $case_progress->interogasi_hasil_yang_dicapai = "0";
            $case_progress->interogasi_laporan = "0";
            $case_progress->elisitasi_hasil_wawancara = "0";
            $case_progress->elisitasi_saran_dan_tindak_lanjut = "0";
            $case_progress->elisitasi_hasil_yang_dicapai = "0";
            $case_progress->elisitasi_laporan = "0";
            $case_progress->status = "Penambahan Kasus";
            $case_progress->substatus = "Penambahan Kasus";
            $case_progress->open_method_percentage = "0";
            $case_progress->close_method_percentage = "0";
            $case_progress->percentage = "0";

            $case_progress->created_by = $user->id;
            $case_progress->updated_by = $user->id;

            $case_progress->save();

            return  redirect()->route('open.case.index')->with("success", "Data berhasil ditambah");
        } 
        catch(\Exception $ex)
        {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', $ex->getMessage() . ' ' . $ex->getLine());
        }
    }

    public function show($id)
    {
        $data = OpenCase::join('master_satker', 'master_satker.id_satker', 'open_case.id_satker')
                            ->leftJoin('master_agama', 'master_agama.kode', 'open_case.agama')
                            ->selectRaw('open_case.*, master_satker.nama_satker, master_agama.nama as nama_agama')
                            ->where('open_case.id', $id)
                            ->first();
        
        if(!$data){
            return redirect()->back()->with('error', 'Kasus Tidak Ditemukan!');
        }
        

        $images = [];
        $foto_dokumens = [];

        if ($data->foto) {
            $imagePaths = json_decode($data->foto);

            foreach ($imagePaths as $imagePath) {
                $images[] = asset('storage/' . $imagePath);
            }
        }

        if ($data->foto_dokumen) {
            $imagePaths = json_decode($data->foto_dokumen);

            foreach ($imagePaths as $imagePath) {
                $foto_dokumens[] = asset('storage/' . $imagePath);
            }
        }

        // history
        $history = CaseEventHistoricalUpdates::where('case_id', $id)
                    ->orderBy('updated_at', 'desc')
                    ->take(5) 
                    ->get();

        return view('backoffice.open.case.show', compact('data', 'images', 'history', 'foto_dokumens'));
    }

    public function edit(Request $request, $id)
    {
        $data = OpenCase::findOrFail($id);
        $agama = MasterAgama::select('kode', 'nama')->get();
        $satker = MasterSatker::where('id_satker', $data->id_satker)->first();
        $tipeIdentitas = tipeIndentitas();
        $pendidikan = DataHelper::getPendidikan();
        $pekerjaan = DataHelper::getPekerjaan();
        $jenis_kelamin = DataHelper::getListJenisKelamin();

        $folderName = $satker->nama_satker;
        $folderPath = public_path('open_case_target_image/'. $folderName);
        $images = [];
        $foto_dokumens = [];

        if(!$data){
            return redirect()->back()->with('error', 'Kasus Tidak Ditemukan!');
        }

        if ($data->foto) {
            $imagePaths = json_decode($data->foto);

            foreach ($imagePaths as $imagePath) {
                $images[] = asset('storage/' . $imagePath);
            }
        }

        if ($data->foto_dokumen) {
            $imagePaths = json_decode($data->foto_dokumen);

            foreach ($imagePaths as $imagePath) {
                $foto_dokumens[] = asset('storage/' . $imagePath);
            }
        }

        return view('backoffice.open.case.edit', compact(
            'data', 
            'agama', 
            'images', 
            'tipeIdentitas', 
            'pendidikan', 
            'pekerjaan',
            'jenis_kelamin',
            'foto_dokumens'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_kasus' => 'required',
            'tanggal_kasus' => 'required|date',
            // 'deskripsi_kasus' => 'required',

            'nama_target' => 'required',
            // 'agama' => 'required',
            // 'pendidikan' => 'required',
            // 'pekerjaan' => 'required',
            // 'alamat' => 'required',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_dokumen' => 'array',
            'foto_dokumen.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $existingData = OpenCase::find($id);
        if (!$existingData) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // create folder name, based on satker name
        $satker = MasterSatker::where('master_satker.id_satker', $existingData->id_satker)
        ->select('master_satker.nama_satker')
        ->first();

        if($satker == null){
            return redirect()->back()->with('error', 'Satker Tidak Ditemukan!');
        }

        $folderName = strtolower(trim($satker->nama_satker));
        $folderName = str_replace(" ","_", $folderName);
        $folderPath = 'open_case_target_image/' . $folderName;
        $folderDocPath = 'open_case_target_doc_image/' . $folderName;

        $newImages = [];
        $newDocImages = [];

        if ($request->file('image') != null) {
            // Remove existing images
            if ($existingData->foto) {
                $existingImagePaths = json_decode($existingData->foto);
    
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
                    $folderPath,
                    time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension(),
                    'public'
                );
                $newImages[] = $filename;
                $index++;
            }    
        } else{
            $newImages = json_decode($existingData->foto);
        }

        if ($request->file('foto_dokumen') != null) {
            // Remove existing images
            if ($existingData->foto_dokumen) {
                $existingImagePaths = json_decode($existingData->foto_dokumen);
    
                foreach ($existingImagePaths as $existingImagePath) {
                    if (Storage::disk('public')->exists($existingImagePath)) {
                        Storage::disk('public')->delete($existingImagePath);
                    }
                }
            }
            // Save new images
            $index = 1;
            foreach ($request->file('foto_dokumen') as $image) {
                $filename = $image->storePubliclyAs(
                    $folderDocPath,
                    time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension(),
                    'public'
                );
                $newDocImages[] = $filename;
                $index++;
            }    
        } else{
            $newDocImages = json_decode($existingData->foto_dokumen);
        }

        $user = auth()->user();
        
        $data = OpenCase::findOrFail($id);

        $data->update([
            'nama_kasus' => $request->nama_kasus,
            'tanggal_kasus' => $request->tanggal_kasus,
            'deskripsi_kasus' => $request->deskripsi_kasus,

            'nama_target' => $request->nama_target,
            'tipe_identitas' => 'NIK/KTP',
            'no_identitas' => $request->nik,
            'agama' => $request->agama,
            'pendidikan' => $request->pendidikan,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'foto' => json_encode($newImages),
            'foto_dokumen' => json_encode($newDocImages),
            'jenis_kelamin'=> $request->jenis_kelamin,

            'updated_by' => $user->id,
        ]);

        return  redirect()->route('open.case.index')->with(["success" => "Data berhasil diubah"]);
    }

    public function destroy($id, Request $request)
    {
        //
        $data = OpenCase::find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Kasus tidak ditemukan');
        }

        $existingData = OpenCase::find($id);
        if (!$existingData) {
            return redirect()->back()->with('error', 'Kasus tidak ditemukan');
        }

        if ($data->foto) {
            $imagePaths = json_decode($data->foto);

            foreach ($imagePaths as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        $data->delete();
        ResearchSuratPerintah::where('case_id', $id)->delete();
        ResearchLaporanInformasiKhusus::where('case_id', $id)->delete();
        ResearchSaranTindakLanjut::where('case_id', $id)->delete();
        ResearchPotensiAght::where('case_id', $id)->delete();

        InterviewJadwal::where('case_id', $id)->delete();
        InterviewHasil::where('case_id', $id)->delete();
        InterviewSaranTL::where('case_id', $id)->delete();

        InterogationRecord::where('case_id', $id)->delete();
        InterogationTargetIdentification::where('case_id', $id)->delete();
        InterogationResultAchievement::where('case_id', $id)->delete();

        ElicitationInterview::where('case_id', $id)->delete();
        ElicitationAdFoll::where('case_id', $id)->delete();
        ElicitationResult::where('case_id', $id)->delete();


        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }

    
    public function summary(SummaryOpenCaseDataTable $dataTable)
    {
        return $dataTable->render('backoffice.open.summary.index');
    }

    public function detailall($id)
    {
        // return $id;
        $data = OpenCase::join('master_satker', 'master_satker.id_satker', 'open_case.id_satker')
                            ->leftjoin('master_agama', 'master_agama.kode', 'open_case.agama')
                            ->selectRaw('open_case.*, master_satker.nama_satker, master_agama.nama as nama_agama')
                            ->where('id', $id)
                            ->first();
        $images = [];

        if ($data && $data->foto) {
            $imagePaths = json_decode($data->foto);

            foreach ($imagePaths as $imagePath) {
                $images[] = asset('storage/' . $imagePath);
            }
        }

        $warant = $data ? ResearchSprint::where('case_id',$data->id)->first() : null;
        $lapinsus = $data ? ResearchLaporanInformasiKhusus::where('case_id',$data->id)->first() : null;
        // $aght = $data ? ResearchPotensiAght::where('id_case',$data->id)->first() : null;
        $sarantl = $data ? ResearchSaranTL::where('case_id',$data->id)->first() : null;

        $wawancarajadwal = $data ? InterviewJadwal::where('case_id',$data->id)->first() : null;
        $wawancarahasil = $data ? InterviewHasil::join('interview_jadwal','interview_hasil.interview_scheduler_id','interview_jadwal.id_interview_scheduler')->where('interview_jadwal.case_id',$data->id)->first() : null;
        $wawancarasaran = $data ? InterviewSaranTL::join('interview_hasil','interview_saran_dan_tindak_lanjut.interview_result_id','interview_hasil.id_interview_result')
        ->join('interview_jadwal','interview_hasil.interview_scheduler_id','interview_jadwal.id_interview_scheduler')
        ->where('interview_saran_dan_tindak_lanjut.case_id',$data->id)->first() : null;

        $interogasitarget = $data ? InterogationTargetIdentification::where('case_id',$data->id)->first() : null;
        $interogasihasil = $data ? InterogationResultAchievement::where('case_id',$data->id)->first() : null;
        $interogasiberita = $data ? InterogationRecord::where('case_id',$data->id)->first() : null;

        $pamancingantl = $data ? ElicitationAdFoll::where('case_id',$data->id)->first() : null;
        $pamancinganwawancara = $data ? ElicitationInterview::where('case_id',$data->id)->first() : null;
        $pamancinganhasil = $data ? ElicitationResult::where('case_id',$data->id)->first() : null;


        return view('backoffice.open.summary.show', compact('data','images','warant','sarantl','lapinsus','wawancarahasil','wawancarajadwal','wawancarasaran','interogasitarget','interogasihasil','interogasiberita','pamancingantl','pamancinganwawancara','pamancinganhasil'));
    }

    public function checkNik($nik, Request $request)
    {
        // $nik = Crypt::decrypt($nik);
        if (strlen($nik) < 16) {
            return back()->with('error', 'Data NIK tidak ditemukan');
        }
        $res = (array) $this->do_search($nik);
        $headers = [];
        if (!$res) {
            return response()->json([
                'status' => 'error',
                'message' => "Pegawai tidak ditemukan"
            ]);
        }

        if ($res['status'] == '200' && count($res['data']) > 0) {
            $data = $res['data'];
            // $collect = new Collection;
            // $collect->push([
            //     'nama' => $data->NAMA_LGKP
            // ]);    
            // return $data['NAMA_LGKP'];    
            return response()->json([
                'status' => 'sukses',
                'nama' => $data['NAMA_LGKP'],
                'agama' => $data['AGAMA'],
                'pendidikan' => $data['PDDK_AKH'],
                'pekerjaan' => $data['JENIS_PKRJN'],
                'jenis_kelamin' => $data['JENIS_KLMIN'],
                'alamat' => $data['ALAMAT'].' '.$data['NO_RT'].'/'.$data['NO_RW'].' DESA/KEL. '.$data['KEL_NAME'].' KEC. '.$data['KEC_NAME'].' KAB/KOTA '.$data['KAB_NAME'],
            ], 200);
        } else {
            return back()->with('error', 'Data NIK tidak ditemukan, silahkan isi terlebih dahulu NIK pada tab Profile');
        }
    }

    public function do_search($nik)
    {
        if ($nik == null) return [
            'status' => '400',
            'message' => 'Input NIK tidak valid'
        ];
        $res = Http::withBasicAuth(env('DUKCAPIL_API_USERNAME'), env('DUKCAPIL_API_PASSWORD'))
            ->post(env('DUKCAPIL_API_URL'), [
                'nik' => $nik
            ]);
        return json_decode($res, true);
    }

    public function getPegawaiByNIP($nip)
    {
        $url = "https://mysimkari.kejaksaan.go.id/api/pegawai-nip/{$nip}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJSb2xlIjoia2FtZGFsIiwiSXNzdWVyIjoibXlzaW1rYXJpIiwiVXNlcm5hbWUiOiJtYWxpZmNoYSIsImV4cCI6MTY5Mjc4Mzk5NywiaWF0IjoxNjkyNzgzOTk3fQ.fS7sAGH5yVsAAVTBhPoarA5us_Stut72vTCAggA6oNY',
        ])->get($url);
    
        if ($response->successful()) {
            $data = $response['data'];
            $dataPegawai = $data[0];
            return response()->json([
                'nama' => $dataPegawai['nama'],
                'pangkat' => $dataPegawai['pangkat'],
                'jabatan' => $dataPegawai['jabatan'],
            ]);
        } else {
            return response()->json(null, $response->status());
        }
    }


}
