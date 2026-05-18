<?php

namespace App\Http\Controllers\Delineation;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Delineation\DelineationScenarioRelationDataTable;
use App\Helpers\DataHelper;
use App\Helpers\DelineationDataHelper;
use App\Models\User;
use App\Models\MasterSatker;
use App\Models\Observation\ObservCollectInfo;
use App\Models\CloseCase;
use App\Models\Delineation\DelineationInformationVerification;
use App\Models\Delineation\DelineationInformationValidation;
use App\Models\Delineation\DelineationScenarioRelation;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;

class DelineationScenarioRelationController extends Controller
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
    public function index(DelineationScenarioRelationDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.delineation.scenario-relation.index', compact('satker', 'users'));
    }



    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        
        $case = DataHelper::getCloseCase();


        $information_collection  = DataHelper::getinformationcollection();
        $information_verification  = DataHelper::getinformationverification();
        $information_validation  = DataHelper::getinformationvalidation();
        

        return view('backoffice.close.delineation.scenario-relation.create', 
        compact('satker', 'users', 'case',
         'information_collection', 'information_verification','information_validation'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            // 'id_information_collection' => 'required|string|max:128',
            // 'id_information_verification' => 'required|string|max:128',
            // 'id_information_validation' => 'required|string|max:128',
            'tanggal_pencatatan' => 'required|date',
            'subjek_utama' => 'required|string|max:1000000',
            'subjek_terkait' => 'required|string|max:1000000',
            'jenis_relasi' => 'required|string|max:1000000',
            'kekuatan_relasi' => 'required|string|max:1000000',
            // 'dampak_potensial' => 'required|string|max:1000000',
            // 'detail_relasi' => 'required|string|max:1000000',
            // 'catatan_analisa' => 'required|string|max:1000000',
        ]);
        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = auth()->user();

        $data = new DelineationScenarioRelation;
        $data->satker_id = $satker->id_satker;

        $data->case_id = $request->id_case;
        $data->information_collection_id = $request->id_information_collection;
        $data->information_verification_id = $request->id_information_verification;
        $data->information_validation_id = $request->id_information_validation;

        $data->subjek_utama = $request->subjek_utama;
        $data->subjek_terkait = $request->subjek_terkait;
        $data->jenis_relasi = $request->jenis_relasi;
        $data->kekuatan_relasi = $request->kekuatan_relasi;

        $data->tanggal_pencatatan= $request->tanggal_pencatatan;
        $data->detail_relasi = $request->detail_relasi;
        $data->dampak_potensial = $request->dampak_potensial;
        $data->catatan_analisa = $request->catatan_analisa;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update->case_id = $data->id;
        $data_case_close_historical_update->action = "Penambahan Identitas Terhubung";

        $data_case_close_historical_update->created_by = $user->id;
        $data_case_close_historical_update->updated_by = $user->id;

        $data_case_close_historical_update2 = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update2->case_id = $data->id;
        $data_case_close_historical_update2->action = "Penambahan Delineation Report";

        $data_case_close_historical_update2->created_by = $user->id;
        $data_case_close_historical_update2->updated_by = $user->id;
        
        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('delineation_skenario_relasi', '0')
            ->update([
                'delineation_skenario_relasi' => "1",
                'delineation_laporan' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Skenario Relasi",
                'percentage' => round((9/29) * 100,2)
            ]);;
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('delineation_skenario_relasi', '0')
            ->update([
                'delineation_skenario_relasi' => "1",
                'delineation_laporan' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Skenario Relasi",
                'percentage' => round((29/29) * 100,2)
            ]);;

        }

        if ($data->save()) {
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update2->case_id = $request->id_case;
            $data_case_close_historical_update->save();
            $data_case_close_historical_update2->save();

            
            return redirect()->route('close.delineation.scenario-relation.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = DelineationScenarioRelation::find($id);
        $satker = MasterSatker::find($data->satker_id);
        $case = CloseCase::find($data->case_id);
        $observation_information_collection = ObservCollectInfo::find($data->information_collection_id);
        $delineation_information_verification = DelineationInformationVerification::find($data->information_verification_id);
        $delineation_information_validation = DelineationInformationValidation::find($data->information_validation_id);
         return view('backoffice.close.delineation.scenario-relation.show', compact(
            'data', 'satker', 'case', 'observation_information_collection',
        'delineation_information_verification', 'delineation_information_validation'));
    }

    public function edit(Request $request, $id)
    {
        $data = DelineationScenarioRelation::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();
        $sprint = DataHelper::getSprint();
        $lik = DataHelper::getLapinsus();
        $information_collection = DataHelper::getinformationcollection($data->case_id);
        $information_verification  = DataHelper::getinformationverification($data->information_collection_id);
        $information_validation  = DataHelper::getinformationvalidation($data->information_verification_id);
        
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.delineation.scenario-relation.edit', 
        compact('data', 'users', 'satker', 'case', 
        'information_collection', 'information_verification', 'information_validation'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            // 'id_satker' => 'required|string|max:128',
            // 'id_information_collection' => 'required|string|max:128',
            // 'id_information_verification' => 'required|string|max:128',
            // 'id_information_validation' => 'required|string|max:128',
            'tanggal_pencatatan' => 'required|date',
            'subjek_utama' => 'required|string|max:1000000',
            'subjek_terkait' => 'required|string|max:1000000',
            'jenis_relasi' => 'required|string|max:1000000',
            'kekuatan_relasi' => 'required|string|max:1000000',
            // 'dampak_potensial' => 'required|string|max:1000000',
            // 'detail_relasi' => 'required|string|max:1000000',
            // 'catatan_analisa' => 'required|string|max:1000000',
        ]);

        $user = auth()->user();

        $data = DelineationScenarioRelation::find($id);
        // $data->satker_id = $request->id_satker;

        $data->case_id = $request->id_case;
        $data->information_collection_id = $request->id_information_collection;
        $data->information_verification_id = $request->id_information_verification;
        $data->information_validation_id = $request->id_information_validation;

        $data->subjek_utama = $request->subjek_utama;
        $data->subjek_terkait = $request->subjek_terkait;
        $data->jenis_relasi = $request->jenis_relasi;
        $data->kekuatan_relasi = $request->kekuatan_relasi;

        $data->tanggal_pencatatan= $request->tanggal_pencatatan;
        $data->detail_relasi = $request->detail_relasi;
        $data->dampak_potensial = $request->dampak_potensial;
        $data->catatan_analisa = $request->catatan_analisa;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('delineation_skenario_relasi', '0')
            ->update([
                'delineation_skenario_relasi' => "1",
                'delineation_laporan' => "1",
                'status' => "Penggambaran",
                'substatus' => "Penambahan Skenario Relasi",
                'percentage' => round((29/29) * 100,2)
            ]);;
        }


        if ($data->update()) {
            return redirect()->route('close.delineation.scenario-relation.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = DelineationScenarioRelation::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $data->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

}
