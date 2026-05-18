<?php

namespace App\Http\Controllers\Interview;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\CaseEventHistoricalUpdates;
use App\Helpers\BodycamDeviceDataHelper;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\CaseProgresses;
use App\Models\OpenCase;
use App\Models\Interview\InterviewJadwal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Interview\InterviewSaranTL;
use App\Models\Interview\InterviewHasil;
use App\DataTables\Interview\InterviewSaranTLDataTable;
use App\Helpers\InterviewDataHelper;

class InterviewSaranTLController extends Controller
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
    public function index(InterviewSaranTLDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.open.interview.saran_tl.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = InterviewDataHelper::getCloseCaseByResearchReport();
        $jadwal = InterviewDataHelper::getInterviewSchedule();
        $hasil = DataHelper::getInterviewHasil();

        return view('backoffice.open.interview.saran_tl.create', compact('users', 'case', 'jadwal', 'hasil', 'satker'));
    }

    public function createFromInterview($id)
    {
        // return 'cek';
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = InterviewDataHelper::getCloseCaseByResearchReport();
        $jadwal = InterviewJadwal::all();
        $hasil = InterviewHasil::all();
        $data = OpenCase::join('interview_jadwal','open_case.id','interview_jadwal.case_id')
                ->join('interview_hasil','interview_jadwal.id_interview_scheduler','interview_hasil.interview_scheduler_id')
                ->where('interview_hasil.id_interview_result',$id)->first();
        return view('backoffice.open.interview.saran_tl.createshortcut', compact('users', 'case', 'jadwal', 'hasil', 'satker','data'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            // 'interview_result_id' => 'nullable|string|max:128',
            'saran_dan_tindak_lanjut_date' => 'required|date',
            'saran_dan_tindak_lanjut' => 'required|string|max:1280000',
        ]);

        $user = auth()->user();

        $data = new InterviewSaranTL;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->interview_result_id = $request->interview_result_id;
        $data->interview_schedule_id = $request->interview_schedule_id;
        $data->saran_dan_tindak_lanjut_date = $request->saran_dan_tindak_lanjut_date;
        $data->saran_dan_tindak_lanjut = $request->saran_dan_tindak_lanjut;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $request->id_case)->first();
                $op->wawancara_saran_dan_tindak_lanjut = 1;
                $op->status = $op->percentage > 58.8 ? $op->status : "Wawancara";
                $op->substatus = $op->percentage > 58.8 ? $op->substatus : "Input Saran dan Tindak Lanjut";
                $op->percentage = $op->percentage > 58.8 ? $op->percentage : 58.8;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Wawancara Saran dan Tindak Lanjut';
                $cp->created_by = $user->id;
                $cp->save();
    
                // Laporan
                $op = CaseProgresses::where('case_id', $request->id_case)->first();
                $op->wawancara_laporan = 1;
                $op->status = $op->percentage > 64.68 ? $op->status : "Wawancara";
                $op->substatus = $op->percentage > 64.68 ? $op->substatus : "Input Laporan";
                $op->percentage = $op->percentage > 64.68 ? $op->percentage : 64.68;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Wawancara Laporan';
                $cp->created_by = $user->id;
                $cp->save();
                
                return redirect()->route('open.interview.saran_tl.index')->with("success", "Data berhasil ditambah.");
            }    
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }else{
            if ($data->save()) {

                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->wawancara_saran_dan_tindak_lanjut = 1;
                $updateCaseProgresses->status = 'Wawancara';
                $updateCaseProgresses->substatus = 'Penambahan Saran dan Tindak Lanjut"';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Wawancara Saran dan Tindak Lanjut';
                $cp->created_by = $user->id;
                $cp->save();
                
                return redirect()->route('open.interview.saran_tl.index')->with("success", "Data berhasil ditambah.");
            }    
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }
        
    }

    public function show(Request $request, $id)
    {
        $data = InterviewSaranTL::find($id);
        $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();
        return view('backoffice.open.interview.saran_tl.show', compact('data','bodycam_devices'));
    }

    public function edit(Request $request, $id)
    {
        $satker = DataHelper::getSatker();
        $data = InterviewSaranTL::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = InterviewDataHelper::getCloseCaseByResearchReport();
        $jadwal = InterviewDataHelper::getInterviewScheduleByCase($data->case_id);
        $hasil = DataHelper::getInterviewHasilByJadwal($data->interview_schedule_id);
        // dd($satker);
        // dd($data->satker_id);
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.open.interview.saran_tl.edit', compact('data', 'users', 'case', 'jadwal', 'hasil', 'satker'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            // 'interview_result_id' => 'nullable|string|max:128',
            'saran_dan_tindak_lanjut_date' => 'required|date',
            'saran_dan_tindak_lanjut' => 'required|string|max:1280000',
        ]);

        $user = auth()->user();

        $data = InterviewSaranTL::find($id);
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->interview_result_id = $request->interview_result_id;
        $data->interview_schedule_id = $request->interview_schedule_id;
        $data->saran_dan_tindak_lanjut_date = $request->saran_dan_tindak_lanjut_date;
        $data->saran_dan_tindak_lanjut = $request->saran_dan_tindak_lanjut;

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {                 
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->wawancara_saran_dan_tindak_lanjut = 1;
            $updateCaseProgresses->status = 'Wawancara';
            $updateCaseProgresses->substatus = 'Penambahan Saran dan Tindak Lanjut"';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }

        if ($data->update()) {
            $cp = CaseEventHistoricalUpdates::where('case_id',$request->id_case)->first();
            $cp->action = 'Perubahan Wawancara Saran dan Tindak Lanjut';
            $cp->updated_by = $user->id;
            $cp->update();

            return redirect()->route('open.interview.saran_tl.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy(Request $request, $id)
    {
        $data = InterviewSaranTL::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $cp = CaseEventHistoricalUpdates::where('case_id', $request->id_case)->first();
        $cp->action = 'Penghapusan Wawancara Saran dan Tindak Lanjut';
        $cp->updated_by = auth()->user()->id;
        $cp->update();

        $data->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
