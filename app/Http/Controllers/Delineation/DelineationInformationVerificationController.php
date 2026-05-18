<?php

namespace App\Http\Controllers\Delineation;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Delineation\DelineationInformationVerificationDataTable;
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


class DelineationInformationVerificationController extends Controller
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
    public function index(DelineationInformationVerificationDataTable $dataTable)
    {

        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.delineation.information-verification.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();

        $case = DataHelper::getCloseCase();
        $information_collection = DataHelper::getinformationcollection();

        return view(
            'backoffice.close.delineation.information-verification.create',
            compact('satker', 'users', 'case', 'information_collection', )
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            // 'id_information_collection' => 'required|string|max:128',
            'metode_verifikasi' => 'required|string|max:128',
            'kredibilitas_sumber' => 'required|string|max:128',
            'verification_date' => 'required|date',
            'verified_by' => 'required|string|max:128',
            'detail_informasi_verifikasi' => 'required|string|max:1000000',
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = auth()->user();

        $data = new DelineationInformationVerification;
        $data->satker_id = $satker->id_satker;

        $data->case_id = $request->id_case;
        $data->information_collection_id = $request->id_information_collection;
        $data->kredibilitas_sumber = $request->kredibilitas_sumber;
        $data->metode_verifikasi = $request->metode_verifikasi;
        $data->detail_informasi_verifikasi = $request->detail_informasi_verifikasi;
        $data->verified_by = $request->verified_by;
        $data->verification_date = $request->verification_date;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;


        $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update->case_id = $data->id;
        $data_case_close_historical_update->action = "Penambahan Informasi Verifikasi";

        $data_case_close_historical_update->created_by = $user->id;
        $data_case_close_historical_update->updated_by = $user->id;

        if ($request->submit_type === 'save') {


            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('delineation_informasi_verifikasi', '0')
                ->update([
                    'delineation_informasi_verifikasi' => "1",
                    'status' => "Penggambaran",
                    'substatus' => "Penambahan Informasi Verifikasi",
                    'percentage' => round((6 / 29) * 100, 2)
                ]);
            ;
        } else {
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('delineation_informasi_verifikasi', '0')
                ->update([
                    'delineation_informasi_verifikasi' => "1",
                    'status' => "Penggambaran",
                    'substatus' => "Penambahan Informasi Verifikasi",
                    'percentage' => round((29 / 29) * 100, 2)
                ]);
            ;

        }

        if ($data->save()) {
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update->save();

            return redirect()->route('close.delineation.information-verification.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = DelineationInformationVerification::where('id', $id)->first();
        $satker = MasterSatker::findOrFail($data->satker_id);
        $case = CloseCase::find($data->case_id);
        // $observation_information_collection = ObservCollectInfo::where('case_id',$data->case_id);

        $observation_information_collection = ObservCollectInfo::where('case_id', $data->case_id)
            ->where('id', $data->information_collection_id)
            ->first();

        return view('backoffice.close.delineation.information-verification.show', compact(
            'data',
            'satker',
            'case',
            'observation_information_collection'
        ));
    }

    public function edit(Request $request, $id)
    {
        $data = DelineationInformationVerification::find($id);


        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();
        $sprint = DataHelper::getSprint();
        $lik = DataHelper::getLapinsus();
        $information_collection = DataHelper::getinformationcollection($data->case_id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.delineation.information-verification.edit', compact('data', 'users', 'satker', 'case', 'information_collection', 'lik'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            // 'id_satker' => 'required|string|max:128',
            // 'id_information_collection' => 'required|string|max:128',
            'metode_verifikasi' => 'required|string|max:128',
            // 'kredibilitas_sumber' => 'required|string|max:128',
            'verification_date' => 'required|date',
            'verified_by' => 'required|string|max:128',
            'detail_informasi_verifikasi' => 'required|string|max:1000000',
        ]);

        $user = auth()->user();

        $data = DelineationInformationVerification::find($id);
        // $data->satker_id = $request->id_satker;

        $data->case_id = $request->id_case;
        $data->information_collection_id = $request->id_information_collection;
        $data->kredibilitas_sumber = $request->kredibilitas_sumber;
        $data->metode_verifikasi = $request->metode_verifikasi;
        $data->detail_informasi_verifikasi = $request->detail_informasi_verifikasi;
        $data->verified_by = $request->verified_by;
        $data->verification_date = $request->verification_date;

        $data->updated_by = $user->id;


        if ($request->submit_type === 'update_and_finish') {

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->id_case)
                ->where('delineation_informasi_verifikasi', '0')
                ->update([
                    'delineation_informasi_verifikasi' => "1",
                    'status' => "Penggambaran",
                    'substatus' => "Penambahan Informasi Verifikasi",
                    'percentage' => round((29 / 29) * 100, 2)
                ]);
            ;
        }


        if ($data->update()) {
            return redirect()->route('close.delineation.information-verification.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = DelineationInformationVerification::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $data->delete();
        DelineationInformationValidation::where('information_verification_id', $id)->delete();
        DelineationScenarioRelation::where('information_verification_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }

}
