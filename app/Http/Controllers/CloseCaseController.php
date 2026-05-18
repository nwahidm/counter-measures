<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\DataHelper;
use App\Models\CloseCase;
use App\Models\MasterAgama;
use App\Models\MasterSatker;
use App\Models\MasterPendidikan;
use App\Models\MasterPekerjaan;
use App\Models\Observation\ObservDirective;
use App\Models\Observation\ObservCollectInfo;
use App\Models\Observation\ObservThreat;
use App\Models\Observation\ObservConnect;
use App\Models\Delineation\DelineationInformationValidation;
use App\Models\Delineation\DelineationInformationVerification;
use App\Models\Delineation\DelineationScenarioRelation;
use App\Models\ExplorationRencanaAksi;
use App\Models\ExplorationResultAchievment;
use App\Models\ExplorationTargetIdentity;
use App\Models\Tailing\TailingPemahamanPerilaku;
use App\Models\Tailing\TailingReport;
use App\Models\Tailing\TailingResultAchievement;
use App\Models\Tailing\TailingTargetOperasi;
use App\Models\Infiltration\InfiltrationReport;
use App\Models\Infiltration\InfiltrationResultAchievement;
use App\Models\Infiltration\InfiltrationSecretOperation;
use App\Models\Infiltration\InfiltrationTargetDynamics;
use App\Models\Intrusion\IntrusionResult;
use App\Models\Intrusion\IntrusionTargetEnv;
use App\Models\Intrusion\IntrusionTargetLoc;
use App\Models\Tapping\TappingElectronicDevice;
use App\Models\Tapping\TappingIntelligentSignal;
use App\Models\Tapping\TappingResultAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\DataTables\CloseCaseDataTable;
use App\DataTables\SummaryCloseCaseDataTable;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseCloseEventHistoricalUpdates;


class CloseCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CloseCaseDataTable $dataTable)
    {
        return $dataTable->render('backoffice.close.case.index');
    }

    public function create()
    {
        $agama = MasterAgama::select('kode', 'nama')->get();
        $pendidikan = MasterPendidikan::select('kode', 'nama')->get();
        $pekerjaan = MasterPekerjaan::select('kode', 'nama')->get();
        $tipeIdentitas = tipeIndentitas();
        $jenis_kelamin = DataHelper::getListJenisKelamin();
        return view('backoffice.close.case.create', compact(
            'agama', 
            'pendidikan',
            'pekerjaan',
            'tipeIdentitas',
            'jenis_kelamin'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_kasus' => 'required',
            // 'tanggal_kasus' => 'required|date',
            // 'deskripsi_kasus' => 'required',

            'nik' => 'required',
            'nama_target' => 'required',
            // 'agama' => 'required',
            // 'pendidikan' => 'required',
            // 'pekerjaan' => 'required',
            // 'alamat' => 'required',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
            $folderPath = public_path('close_case_target_image/' . $folderName);

            if (! file_exists($folderPath)) {
                mkdir($folderPath, 0775, true);
            }

            // save the image first
            $filenames = [];
            $index = 1;
            if($request->file('image') != null){
                foreach ($request->file('image') as $image) {
                    $filename = time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'close/case',
                        $filename,
                        'public'
                    );
    
                    // $data->target_photo = $target_photo;
                }    
            }

            // store data to database
            $closeCaseSave = CloseCase::create([
                'satker_id' => $satker->id_satker,
                'case_name' => $request->nama_kasus,
                'case_date' => $request->tanggal_kasus,
                'case_description' => $request->deskripsi_kasus,

                'target_name' => $request->nama_target,
                'target_identity_number_type' => 'NIK/KTP',
                'target_identity_number' => $request->nik,
                'target_religion' => $request->agama,
                'target_gender'=> $request->jenis_kelamin,
                'target_education' => $request->pendidikan,
                'target_occupation' => $request->pekerjaan,
                'target_address' => $request->alamat,
                'target_photo' => json_encode($filenames),

                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            $data = DB::commit();
            $case_close_progress = new CaseCloseProgresses;
            $case_close_progress->case_id = $closeCaseSave->id;
            
            $case_close_progress->observation_surat_perintah = "0";
            $case_close_progress->observation_information_collection= "0";
            $case_close_progress->observation_potensi_aght= "0";
            $case_close_progress->observation_identitas_terhubung= "0";
            $case_close_progress->observation_laporan = "0";
            $case_close_progress->delineation_informasi_verifikasi = "0";
            $case_close_progress->delineation_informasi_validation = "0";
            $case_close_progress->delineation_skenario_relasi = "0";
            $case_close_progress->delineation_laporan = "0";
            $case_close_progress->exploration_rencana_aksi = "0";
            $case_close_progress->exploration_identitas_target = "0";
            $case_close_progress->exploration_hasil_yang_dicapai = "0";
            $case_close_progress->exploration_laporan = "0";
            $case_close_progress->tailing_pemahaman_perilaku = "0";
            $case_close_progress->tailing_target_operasi = "0";
            $case_close_progress->tailing_hasil_yang_dicapai = "0";
            $case_close_progress->tailing_laporan = "0";
            $case_close_progress->infiltration_operasi_rahasia = "0";
            $case_close_progress->infiltration_dinamika_target = "0";
            $case_close_progress->infiltration_hasil_yang_dicapai = "0";
            $case_close_progress->infiltration_laporan = "0";
            $case_close_progress->intrusion_lokasi_target = "0";
            $case_close_progress->intrusion_lingkungan_target = "0";
            $case_close_progress->intrusion_hasil_yang_dicapai = "0";
            $case_close_progress->intrusion_laporan = "0";
            $case_close_progress->tapping_data_penyelidikan_komunikasi_elektronik = "0";
            $case_close_progress->tapping_data_sinyal_intelijen = "0";
            $case_close_progress->tapping_hasil_penyadapan = "0";
            $case_close_progress->tapping_laporan = "0";
            $case_close_progress->status = "Penambahan Kasus";
            $case_close_progress->substatus = "Penambahan Kasus";
            $case_close_progress->open_method_percentage = "0";
            $case_close_progress->close_method_percentage = "0";
            $case_close_progress->percentage = "0";

            $case_close_progress->created_by = $user->id;
            $case_close_progress->updated_by = $user->id;

            $case_close_progress->save();

            return  redirect()->route('close.case.index')->with("success", "Data berhasil ditambah");
        } 
        catch(\Exception $ex)
        {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', $ex->getMessage() . ' ' . $ex->getLine());
        }
    }

    public function show($id)
    {
        $data = CloseCase::join('master_satker', DB::raw("CAST(master_satker.id_satker AS bigint)"), '=',DB::raw("CAST(close_case.satker_id AS bigint)"))
                            // ->join('master_agama', 'master_agama.kode', 'close_case.target_religion')
                            // ->selectRaw('close_case.*, master_satker.nama_satker as nama_satker, master_agama.nama as nama_agama')
                            ->selectRaw('close_case.*, master_satker.nama_satker as nama_satker')
                            ->where('close_case.id', $id)
                            ->first();
        // dd($data);
        $folderName = strtolower(trim($data->nama_satker));
        $folderName = str_replace(" ","_", $folderName);
        $folderPath = public_path('close_case_target_image/' . $folderName);
                
        $folderPath = public_path('close_case_target_image/'. $folderName);
        $images = [];

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);
            foreach ($imagePaths as $imagePath) {
                $images[] =Storage::url('close/case/' . $imagePath);
            }
        }

        $history = CaseCloseEventHistoricalUpdates::where('case_id', $id)
                    ->orderBy('updated_at', 'desc')
                    ->take(5) 
                    ->get();

        return view('backoffice.close.case.show', compact('data', 'images', 'history'));
    }

    public function edit(Request $request, $id)
    {
        $data = CloseCase::findOrFail($id);
        $agama = MasterAgama::select('kode', 'nama')->get();
        $satker = MasterSatker::where('id_satker', $data->satker_id)->first();
        $pendidikan = MasterPendidikan::select('kode', 'nama')->get();
        $pekerjaan = MasterPekerjaan::select('kode', 'nama')->get();
        $tipeIdentitas = tipeIndentitas();
        $jenis_kelamin = DataHelper::getListJenisKelamin();
        
        $folderName = strtolower(trim($satker->nama_satker));
        $folderName = str_replace(" ","_", $folderName);
        $folderPath = public_path('close_case_target_image/'. $folderName);
        $images = [];

        if ($data->foto) {
            $imagePaths = json_decode($data->foto);

            foreach ($imagePaths as $imagePath) {
                $images[] = asset('close_case_target_image/'. $folderName. '/'. $imagePath);
            }
        }

        return view('backoffice.close.case.edit', compact(
            'data', 
            'agama', 
            'images', 
            'tipeIdentitas', 
            'pendidikan',
            'pekerjaan',
            'jenis_kelamin'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_kasus' => 'required',
            // 'tanggal_kasus' => 'required|date',
            // 'deskripsi_kasus' => 'required',

            'nama_target' => 'required',
            'no_identitas' => 'required',
            // 'agama' => 'required',
            // 'pendidikan' => 'required',
            // 'pekerjaan' => 'required',
            // 'alamat' => 'required',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $existingData = CloseCase::find($id);
        if (!$existingData) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // create folder name, based on satker name
        $satker = MasterSatker::where('master_satker.id_satker', $existingData->satker_id)
        ->select('master_satker.nama_satker')
        ->first();

        if($satker == null){
            return redirect()->back()->with('error', 'Satker Tidak Ditemukan!');
        }

        $folderName = strtolower(trim($satker->nama_satker));
        $folderName = str_replace(" ","_", $folderName);
        $folderPath = public_path('close_case_target_image/'. $folderName);

        $newImages = [];
        if ($request->file('image') != null) {
            // Remove existing images
            if ($existingData->target_photo) {
                $existingImagePaths = json_decode($existingData->target_photo);
    
                foreach ($existingImagePaths as $existingImagePath) {
                    if (file_exists($folderPath . '/' . $existingImagePath)) {
                        unlink($folderPath . '/' . $existingImagePath);
                    }
                }
            }
            // Save new images
            $index = 1;
            foreach ($request->file('image') as $image) {
                $filename = time(). ' - '. $request->nama_target .' - '. $index . '.'. $image->getClientOriginalExtension();
                // $image->move($folderPath, $filename);
                // $newImages[] = $filename;
                // $index++;

                $newImages[] = $filename;
                $index++;

                $target_photo = $image
                ->storePubliclyAs(
                    'close/case',
                    $filename,
                    'public'
                );
            }
        } else{
            $newImages = json_decode($existingData->target_photo);
        }

        $user = auth()->user();
        
        $data = CloseCase::findOrFail($id);

        $data->update([
            // 'satker_id' => $satker->id_satker,
            'case_name' => $request->nama_kasus,
            'case_date' => $request->tanggal_kasus,
            'case_description' => $request->deskripsi_kasus,

            'target_name' => $request->nama_target,
            // 'target_identity_number_type' => $request->tipe_identitas,
            'target_identity_number' => $request->no_identitas,
            'target_religion' => $request->agama,
            'target_education' => $request->pendidikan,
            'target_gender'=> $request->jenis_kelamin,
            'target_occupation' => $request->pekerjaan,
            'target_address' => $request->alamat,
            'target_photo' => json_encode($newImages),

            'updated_by' => $user->id,
        ]);

        return  redirect()->route('close.case.index')->with(["success" => "Data berhasil diubah"]);
    }

    public function destroy($id, Request $request)
    {
        //
        $data = CloseCase::find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $existingData = CloseCase::find($id);
        if (!$existingData) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $data->delete();
        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }

    public function summary(SummaryCloseCaseDataTable $dataTable)
    {
        // return 'cel';
        return $dataTable->render('backoffice.close.summary.index');
    }

    public function detailall($id)
    {
        $data = CloseCase::join('master_satker', DB::raw("CAST(master_satker.id_satker AS bigint)"), DB::raw("CAST(close_case.satker_id AS bigint)"))
                            ->leftJoin('master_agama', 'master_agama.kode', 'close_case.target_religion')
                            ->selectRaw('close_case.*, master_satker.nama_satker as nama_satker, master_agama.nama as nama_agama')
                            ->where('id', $id)
                            ->first();
        if(!$data){
            redirect()->back()->with('error', 'Kasus tidak ditemukan'); 
        }
        // dd($data);
        $observdirective = ObservDirective::where('case_id', $data->id)
                            ->first();
        $observcollectinfo = ObservCollectInfo::where('case_id', $data->id)
                            ->first();
        $observthreat = ObservThreat::where('case_id', $data->id)
                            ->first();
        $observconnect = ObservConnect::where('case_id', $data->id)
                            ->first();
                                    
        $deliinationinfovalid = DelineationInformationValidation::where('case_id', $data->id)
                            ->first();
        $deliinationinfovery = DelineationInformationVerification::where('case_id', $data->id)
                            ->first();
        $deliinationscenario = DelineationScenarioRelation::where('case_id', $data->id)
                            ->first();
        $explorationrencanaaksi = ExplorationRencanaAksi::where('case_id', $data->id)
                            ->first();
        $explorationresult = ExplorationResultAchievment::where('case_id', $data->id)
                            ->first();
        $explorationtarget = ExplorationTargetIdentity::where('case_id', $data->id)
                            ->first();
        $tailingpemahaman = TailingPemahamanPerilaku::where('case_id', $data->id)
                            ->first();
        $tailingtargetoperasi = TailingTargetOperasi::where('case_id', $data->id)
                            ->first();
        $tailingreult = TailingResultAchievement::where('case_id', $data->id)
                            ->first();
        $tailingreport = TailingReport::where('case_id', $data->id)
                            ->first();
        $infiltrationscret = InfiltrationSecretOperation::where('case_id', $data->id)
                            ->first();
        $infiltrationtarget = InfiltrationTargetDynamics::where('case_id', $data->id)
                            ->first();
        $infiltrationresult = InfiltrationResultAchievement::where('case_id', $data->id)
                            ->first();
        $intrusiontargetloc = IntrusionTargetLoc::where('case_id', $data->id)
                            ->first();
        $intrusiontargetenv = IntrusionTargetEnv::where('case_id', $data->id)
                            ->first();
        $intrusionresult = IntrusionResult::where('case_id', $data->id)
                            ->first();
        $tappingdevice = TappingElectronicDevice::where('case_id', $data->id)
                            ->first();
        $tappingsignal = TappingIntelligentSignal::where('case_id', $data->id)
                            ->first();  
        $tappingresult = TappingResultAchievement::where('case_id', $data->id)
                            ->first();
        $folderName = strtolower(trim($data->nama_satker));
        $folderName = str_replace(" ","_", $folderName);
        $folderPath = public_path('close_case_target_image/' . $folderName);
                
        $folderPath = public_path('close_case_target_image/'. $folderName);
        $images = [];

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);
            foreach ($imagePaths as $imagePath) {
                $images[] =Storage::url('close/case/' . $imagePath);
            }
        }

        return view('backoffice.close.summary.show', compact('data', 'images','observdirective','observcollectinfo','observthreat','observconnect','deliinationinfovalid', 'deliinationinfovery', 'deliinationscenario', 'explorationrencanaaksi','explorationresult', 'explorationtarget', 'tailingpemahaman', 'tailingreport', 'infiltrationresult', 'infiltrationscret', 'infiltrationtarget','tappingresult','intrusionresult', 'intrusiontargetenv', 'intrusiontargetloc', 'tappingdevice', 'tappingsignal','tailingpemahaman','tailingreult','infiltrationresult','tailingtargetoperasi'));
    }
    
}
