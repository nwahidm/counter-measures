<?php

namespace App\Http\Controllers;

use App\Models\ExplorationTargetIdentity;
use App\Models\ExplorationResultAchievment;
use App\Models\ExplorationRencanaAksi;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;
use Illuminate\Http\Request;
use App\DataTables\ExplorationTargetIdentityDataTable;
use App\Http\Controllers\Controller;
use App\Models\CloseCase;
use App\Models\Observation\ObservCollectInfo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Models\MasterSatker;

class ExplorationTargetIdentityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        Carbon::setLocale('id');
    }
    
    public function index(ExplorationTargetIdentityDataTable $dataTable)
    {
        //
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.exploration.indentitastarget.index', compact('satker', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $satker = DataHelper::getSatker();
        // $case = DataHelper::getCloseCase();
        $case = DataHelper::getCloseCase();
        $rencana = ExplorationRencanaAksi::select('rencana_aksi_data','id_exploration_rencana_aksi')->get();
        $pekerjaan = DB::table('master_pekerjaan')->get();
        $pendidikan = DB::table('master_pendidikan')->get();

        return view('backoffice.close.exploration.indentitastarget.create', compact('satker', 'case','rencana','pekerjaan','pendidikan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // return 'data';
        $this->validate($request, [
            'id_satker' => 'required',
            'case_id' => 'required|string|max:255',
            // 'exploration_rencana_aksi_id' => 'required',
            'nama_target' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:255',
            // 'target_gender' => 'required|string|max:255',
            // 'target_religion' => 'required|string|max:255',
            // 'target_education' => 'required|string|max:255',
            // 'target_occupation' => 'required|string|max:255',
            'target_photo' => 'nullable|file|mimes:jpeg,jpg|max:2048'
        ]);

        
        $user = auth()->user();

        $data = new ExplorationTargetIdentity;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->exploration_rencana_aksi_id = $request->exploration_rencana_aksi_id;
        $data->target_name = $request->nama_target;
        $data->target_identity_number = $request->nik;
        $data->target_identity_number_type = 'KTP';
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;


        if ($request->hasFile('target_photo')) {
            $ext_upload_info = $request->file('target_photo')->extension();
            $upload_info = $request->file('target_photo')
                ->storePubliclyAs(
                    'close/exploration/identitastarget/upload',
                    Str::slug('exploration-identitas-target', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->target_photo = $upload_info;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_identitas_target' => "1",
                'status' => "Identitas Target",
                'substatus' => "Penambahan Identitas Targer",
                'percentage' => round((11/29)*100,2)
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_identitas_target' => "1",
                'status' => "Identitas Target",
                'substatus' => "Penambahan Identitas Targer",
                'percentage' => round((29/29)*100,2)
            ]);

        }

        if ($data->save()) {
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $request->case_id;
            $data_case_close_historical_update->action = "Penambahan Identitas Target";
    
            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();
            
            return redirect()->route('close.exploration.identitas-target.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        // $data = ExplorationTargetIdentity::leftJoin('exploration_rencana_aksi','exploration_target_identitas.exploration_rencana_aksi_id','exploration_rencana_aksi.id_exploration_rencana_aksi')
        // ->join('close_case', function($join) {
        //     $join->on(DB::raw('CAST(exploration_target_identitas.case_id AS uuid)'), '=', 'close_case.id');
        // })
        // ->with('satker')
        // ->where('id_exploration_target_identity',$id)->first();
        $data = ExplorationTargetIdentity::
        with(['satker', 'case', 'explorationRencanaAksi', 'explorationResultAchievements'])
        ->where('id_exploration_target_identity',$id)->first();

        return view('backoffice.close.exploration.indentitastarget.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $data = ExplorationTargetIdentity::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCloseCase();
        $satker = DataHelper::getSatker();
        $rencana = ExplorationRencanaAksi::select('rencana_aksi_data','id_exploration_rencana_aksi')->where('satker_id', $data->satker_id)->where('case_id', $data->case_id)->get();

        $case_id = $data->case->id;
        $surat_perintah = DataHelper::getCloseSprint($case_id);

        return view('backoffice.close.exploration.indentitastarget.edit', compact('data', 'users', 'case', 'satker','surat_perintah','rencana'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            // 'id_satker' => 'required',
            'case_id' => 'required|string|max:255',
            'nama_target' => 'required|string|max:255',
            // 'exploration_rencana_aksi_id' => 'required',
            'nik' => 'required|string|max:255',
            // 'target_identity_number_type' => 'required|string|max:255',
            // 'jenis_kelamin' => 'required|string|max:255',
            // 'agama' => 'required|string|max:255',
            // 'pekerjaan' => 'required|string|max:255',
            // 'pendidikan' => 'required|string|max:255',
            'target_photo' => 'nullable|file|mimes:jpeg,jpg|max:2048'
        ]);
        
        $user = auth()->user();

        $data = ExplorationTargetIdentity::find($id);
        
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->exploration_rencana_aksi_id = $request->exploration_rencana_aksi_id;
        $data->target_name = $request->nama_target;
        $data->target_identity_number = $request->nik;
        // $data->target_identity_number_type = $request->target_identity_number_type;
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;

        if ($request->hasFile('target_photo')) {
            $ext_upload_sprint = $request->file('target_photo')->extension();
            $upload_sprint = $request->file('target_photo')
                ->storePubliclyAs(
                    'close/exploration/identitastarget/upload',
                    Str::slug('exploration-identitas-target', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );


            $data->target_photo = $upload_sprint;
        } else {
            $information_collection_upload = $request->temp_target_photo;

            $data->target_photo = $information_collection_upload;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_identitas_target' => "1",
                'status' => "Identitas Target",
                'substatus' => "Penambahan Identitas Targer",
                'percentage' => round((29/29)*100,2)
            ]);

        }

        if ($data->update()) {
            return redirect()->route('close.exploration.identitas-target.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $data = ExplorationTargetIdentity::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $data->delete();
        ExplorationResultAchievment::where('exploration_target_identity_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
