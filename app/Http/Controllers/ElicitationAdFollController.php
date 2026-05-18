<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CaseProgresses;
use App\Helpers\DataHelper;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ElicitationAdFoll;
use App\Models\ElicitationResult;
use App\Models\CaseEventHistoricalUpdates;
use Illuminate\Support\Facades\DB;
use App\DataTables\Elicitation\ElicitationAdFollDataTable;

class ElicitationAdFollController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ElicitationAdFollDataTable $dataTable)
    {
        //
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();
        return $dataTable->render('backoffice.open.elicitation-advice-and-followup.index', compact('satker', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCaseValidElicitationRecord();
        $satker = DataHelper::getSatker();
        $elinterview = DataHelper::getElicitationInterview();
        return view('backoffice.open.elicitation-advice-and-followup.create', compact('case','users','satker','elinterview'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'id_case' => 'required',
            'id_satker' => 'required',
            // 'id_elicitation_interview_result' => 'required',
            'tanggal_tinjut' => 'required',
            'saran_tinjut' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $user = auth()->user();

            ElicitationAdFoll::create([
                'case_id' => $request->id_case,
                'satker_id' => $request->id_satker,
                'elicitation_hasil_wawancara_id' => $request->id_elicitation_interview_result,
                'saran_dan_tindak_lanjut_date' => $request->tanggal_tinjut,
                'saran_dan_tindak_lanjut' => $request->saran_tinjut,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'satker_id' => $user->id_satker
            ]);

            if ($request->submit_type === 'save') {

                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->elisitasi_saran_dan_tindak_lanjut = 1;
                $updateCaseProgresses->status = 'Elicitation';
                $updateCaseProgresses->substatus = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 88.2 ? $updateCaseProgresses->percentage : 88.2;
                $updateCaseProgresses->save();

                // $cp = CaseEventHistoricalUpdates::where('case_id',$request->id_case)->first();
                // $cp->action = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                // $cp->created_by = $user->id;
                // $cp->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                $cp->created_by = $user->id;
                $cp->save();


            }else{
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->elisitasi_saran_dan_tindak_lanjut = 1;
                $updateCaseProgresses->status = 'Elicitation';
                $updateCaseProgresses->substatus = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                // $cp = CaseEventHistoricalUpdates::where('case_id',$request->id_case)->first();
                // $cp->action = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                // $cp->created_by = $user->id;
                // $cp->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                $cp->created_by = $user->id;
                $cp->save();


            }

            // $log = DataHelper::logUpdateCase($request->id_case, 'Penambahan Elisitasi Saran dan Tindak Lanjut');

            DB::commit();
            return redirect()->route('open.data.elicit-adfoll.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', $ex->getMessage() . ' ' . $ex->getLine());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        //
        $data = ElicitationAdFoll::find($id);
        return view('backoffice.open.elicitation-advice-and-followup.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
        $data = ElicitationAdFoll::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCaseValidElicitationRecord();
        $elinterview = DataHelper::getElicitationInterview();
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.open.elicitation-advice-and-followup.edit', compact('data', 'users', 'satker', 'case','elinterview'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //

        $this->validate($request, [
            'id_case' => 'required',
            // 'id_elicitation_interview_result' => 'required',
            'tanggal_tinjut' => 'required',
            'saran_tinjut' => 'required',
        ]);
        
        $user = auth()->user();

        $data = ElicitationAdFoll::find($id);
        $data->case_id = $request->id_case;
        $data->elicitation_hasil_wawancara_id = $request->id_elicitation_interview_result;
        $data->saran_dan_tindak_lanjut_date = $request->tanggal_tinjut;
        $data->saran_dan_tindak_lanjut = $request->saran_tinjut;

        $data->updated_by = $user->id;

        if ($data->update()) {
            if ($request->submit_type === 'update_and_finish') {
       
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->elisitasi_saran_dan_tindak_lanjut = 1;
                $updateCaseProgresses->status = 'Elicitation';
                $updateCaseProgresses->substatus = 'Penambahan Elisitasi Saran dan Tindak Lanjut';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();
    

            }

            $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Elisitasi Saran dan Tindak Lanjut');

            return redirect()->route('open.data.elicit-adfoll.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        //
        $data = ElicitationAdFoll::find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $log = DataHelper::logUpdateCase($data->case_id, 'Penghapusan Elisitasi Saran dan Tindak Lanjut');
        
        $data->delete();
        ElicitationResult::where('elicitation_advice_and_follow_up_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }

}
