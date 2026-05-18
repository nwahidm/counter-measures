<?php

namespace App\Http\Controllers\Observation;

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
use App\Models\Observation\ObservDirective;
use App\Models\Observation\ObservCollectInfo;
use App\Models\Observation\ObservThreat;
use App\Models\Observation\ObservConnect;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\DataTables\Observation\ObservConnectDataTable;
use App\Helpers\ObservationDataHelper;

class ObservConnectController extends Controller
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
    public function index(ObservConnectDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.observation.connected-identity.index', compact('satker', 'users'));
    }

    // API
    public function list(Request $request)
    {
        $user = Auth::user();
        $idSatker = $user->satker->id_satker;

        $data = ObservConnect::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('observation_surat_perintah.satker_id', '=', $idSatker);
                                })
                                ->with(['satker', 'case', 'sprint', 'collectInfo', 'threat'])
                                ->latest()
                                ->paginate(10);
        return response()->json($data);
    }
    public function individual($id)
    {

        $data = ObservConnect::with(['satker', 'case', 'sprint', 'collectInfo', 'threat'])
                                ->findOrFail($id);
        return response()->json($data);
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();
        $agama = DataHelper::getAgama();
        $tipeIdentitas = tipeIndentitas();
        $pendidikan = DataHelper::getPendidikan();
        $pekerjaan = DataHelper::getPekerjaan();

        return view('backoffice.close.observation.connected-identity.create', compact('satker', 'case', 'tipeIdentitas', 'agama', 'pendidikan', 'pekerjaan'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'satker_id' => 'required|string|max:255',
            'case_id' => 'required|string|max:255',
            // 'surat_perintah_id' => 'required|string|max:255',
            // 'information_collection_id' => 'required|string|max:255',
            // 'potensi_aght_id' => 'required|string|max:255',
            'target_name' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:255',
            // 'target_identity_number' => 'required|string|max:100',
            // 'target_gender' => 'required|string|max:50',
            // 'target_religion' => 'required|string|max:100',
            // 'target_education' => 'required|string|max:100',
            // 'target_occupation' => 'required|string',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        $data = new ObservConnect;
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_id = $request->information_collection_id;
        $data->potensi_aght_id = $request->potensi_aght_id;

        $data->target_name = $request->target_name;
        $data->target_identity_number_type = 'NIK/KTP';
        $data->target_identity_number = $request->nik;
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_education = $request->pendidikan;
        $data->target_occupation = $request->pekerjaan;

        // save the image first
        $filenames = [];
        $index = 1;
        if($request->file('image') != null){
            foreach ($request->file('image') as $image) {
                $filename = $image->storePubliclyAs(
                    'close/observation/connected-identity/foto-target',
                    time(). ' - '. $request->target_name.' - '. $index . ' - ' . Str::random() . '.'. $image->getClientOriginalExtension(),
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
                'observation_identitas_terhubung' => "1",
                'observation_laporan' => "1",
                'status' => $close_case_progress->percentage > 18 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 18 ? $close_case_progress->substatus : 'Input Pihak Lain Yang Terhubung Pengamatan',
                'percentage' => $close_case_progress->percentage > 18 ? $close_case_progress->percentage : 18
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'observation_identitas_terhubung' => "1",
                'observation_laporan' => "1",
                'status' => $close_case_progress->percentage > 18 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 18 ? $close_case_progress->substatus : 'Input Pihak Lain Yang Terhubung Pengamatan',
                'percentage' => 100
            ]);
        }

        if ($data->save()) {
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Pihak Lain Yang Terhubung Pengamatan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            // update progress
            
            return redirect()->route('close.observation.connected-identity.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = ObservConnect::find($id);

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

        return view('backoffice.close.observation.connected-identity.show', compact('data', 'images'));
    }

    public function edit(Request $request, $id)
    {
        $data = ObservConnect::find($id);
        $satker = DataHelper::getSatker();

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $case = DataHelper::getCloseCase();
        $agama = DataHelper::getAgama();
        $tipeIdentitas = tipeIndentitas();
        $pendidikan = DataHelper::getPendidikan();
        $pekerjaan = DataHelper::getPekerjaan();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $surat_perintah = DataHelper::getCloseSprint($data->case?->id);
        $collect_info = DataHelper::getCollectInfo($data->sprint?->id);
        $aght = DataHelper::getCloseAght($data->collectInfo?->id);
        $images = [];

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);

            foreach ($imagePaths as $imagePath) {
                $images[] = asset('storage/' . $imagePath);
            }
        }

        return view('backoffice.close.observation.connected-identity.edit', compact('data', 'satker', 'case', 'surat_perintah', 'collect_info', 'agama', 'tipeIdentitas', 'aght', 'images', 'pendidikan', 'pekerjaan'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'satker_id' => 'required|string|max:255',
            'case_id' => 'required|string|max:255',
            'surat_perintah_id' => 'required|string|max:255',
            'information_collection_id' => 'required|string|max:255',
            'potensi_aght_id' => 'required|string|max:255',
            'target_name' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:255',
            // 'target_identity_number' => 'required|string|max:100',
            // 'target_gender' => 'required|string|max:50',
            // 'target_religion' => 'required|string|max:100',
            // 'target_education' => 'required|string|max:100',
            // 'target_occupation' => 'required|string',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        $data = ObservConnect::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        // $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->information_collection_id = $request->information_collection_id;
        $data->potensi_aght_id = $request->potensi_aght_id;

        $data->target_name = $request->target_name;
        $data->target_identity_number_type = "NIK/KTP";
        $data->target_identity_number = $request->nik;
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_education = $request->pendidikan;
        $data->target_occupation = $request->pekerjaan;

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
                    'close/observation/connected-identity/foto-target',
                    time(). ' - '. $request->target_name.' - '. $index . ' - ' . Str::random() . '.'. $image->getClientOriginalExtension(),
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
                'observation_identitas_terhubung' => "1",
                'observation_laporan' => "1",
                'status' => $close_case_progress->percentage > 18 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 18 ? $close_case_progress->substatus : 'Input Pihak Lain Yang Terhubung Pengamatan',
                'percentage' => 100
            ]);
        }

        if ($data->update()) {
            return redirect()->route('close.observation.connected-identity.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = ObservConnect::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
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

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }



}
