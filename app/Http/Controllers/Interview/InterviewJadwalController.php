<?php

namespace App\Http\Controllers\Interview;

use App\DataTables\Interview\InterviewJadwalDataTable;
use App\Http\Controllers\Controller;
use App\Models\Interview\InterviewHasil;
use App\Models\Interview\InterviewJadwal;
use App\Models\Interview\InterviewSaranTL;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\DataHelper;
use App\Helpers\InterviewDataHelper;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use Illuminate\Http\Request;

class InterviewJadwalController extends Controller
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
    public function index(InterviewJadwalDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.open.interview.jadwal.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = InterviewDataHelper::getCloseCaseByResearchReport();
        $agama = DataHelper::getListAgama();
        $pendidikan = DataHelper::getPendidikan();
        $pekerjaan = DataHelper::getPekerjaan();

        return view('backoffice.open.interview.jadwal.create', compact('satker', 'users', 'case', 'agama', 'pendidikan', 'pekerjaan'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            'interviewer_name' => 'required|string|max:128',
            'interviewer_schedule' => 'required|date',
            'source_person_name' => 'required|string|max:128',
            // 'target_identity_number' => 'required|string|max:128',
            // 'target_type_identity_number' => 'required|string|max:128',
            // 'target_gender' => 'required|string|max:128',
            // 'target_religion' => 'required|string|max:128',
            // 'target_occupation' => 'required|string|max:128',
            // 'target_education' => 'required|string|max:128',
            // 'target_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        $data = new InterviewJadwal;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->interviewer_name = $request->interviewer_name;
        $data->interviewer_schedule = $request->interviewer_schedule;
        $data->interview_nip = $request->interview_nip;
        $data->interview_pangkat = $request->interview_pangkat;
        $data->interview_jabatan = $request->interview_jabatan;
        $data->tempat = $request->tempat;
        $data->dasar = $request->dasar;
        $data->source_person_name = $request->source_person_name;
        $data->target_identity_number = $request->target_identity_number;
        $data->target_type_identity_number = 'NIK/KTP';
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_occupation = $request->target_occupation;
        $data->target_education = $request->target_education;
        $data->target_alamat = $request->target_alamat;

        if ($request->hasFile('target_photo')) {
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'open/interview/jadwal/target_photo',
                    Str::slug('interview jadwal', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            $data->target_photo = $target_photo;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $data->case_id)->first();
                $op->wawancara_jadwal = 1;
                $op->status = $op->percentage > 47.04 ? $op->status : "Wawancara";
                $op->substatus = $op->percentage > 47.04 ? $op->substatus : "Input Wawancara";
                $op->percentage = $op->percentage > 47.04 ? $op->percentage : 47.04;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->case_id;
                $cp->action = 'Penambahan Wawancara Jadwal';
                $cp->created_by = $user->id;
                $cp->save();
    
                if ($request->hasFile('target_photo')) {
                    DataHelper::insertDocument($data->id_interview_scheduler, $data->target_photo, null, $user->id);
                }
    
                return redirect()->route('open.interview.jadwal.index')->with("success", "Data berhasil ditambah.");
            }
    
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }else{
            if ($data->save()) {
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->wawancara_jadwal = 1;
                $updateCaseProgresses->status = 'Wawancara';
                $updateCaseProgresses->substatus = 'Penambahan Wawancara';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->case_id;
                $cp->action = 'Penambahan Wawancara Jadwal';
                $cp->created_by = $user->id;
                $cp->save();

                if ($request->hasFile('target_photo')) {
                    DataHelper::insertDocument($data->id_interview_scheduler, $data->target_photo, null, $user->id);
                }
                return redirect()->route('open.interview.jadwal.index')->with("success", "Data berhasil ditambah.");
            }
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
            
        }

        
    }

    public function show(Request $request, $id)
    {
        $data = InterviewJadwal::find($id);

        return view('backoffice.open.interview.jadwal.show', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = InterviewJadwal::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCase();
        $agama = DataHelper::getListAgama();
        $pendidikan = DataHelper::getPendidikan();
        $pekerjaan = DataHelper::getPekerjaan();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.open.interview.jadwal.edit', compact('data', 'users', 'satker', 'case', 'agama', 'pekerjaan', 'pendidikan'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            'interviewer_name' => 'required|string|max:128',
            'interviewer_schedule' => 'required|date',
            'source_person_name' => 'required|string|max:128',
            // 'target_identity_number' => 'required|string|max:128',
            // 'target_type_identity_number' => 'required|string|max:128',
            // 'target_gender' => 'required|string|max:128',
            // 'target_religion' => 'required|string|max:128',
            // 'target_occupation' => 'required|string|max:128',
            // 'target_education' => 'required|string|max:128',
            // 'target_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        $data = InterviewJadwal::find($id);
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->interviewer_name = $request->interviewer_name;
        $data->interview_nip = $request->interview_nip;
        $data->interview_pangkat = $request->interview_pangkat;
        $data->interview_jabatan = $request->interview_jabatan;
        $data->tempat = $request->tempat;
        $data->dasar = $request->dasar;
        $data->interviewer_schedule = $request->interviewer_schedule;
        $data->source_person_name = $request->source_person_name;
        $data->target_identity_number = $request->target_identity_number;
        $data->target_type_identity_number = 'NIK/KTP';
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_occupation = $request->target_occupation;
        $data->target_education = $request->target_education;
        $data->target_alamat = $request->target_alamat;

        if ($request->hasFile('target_photo')) {
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'open/interview/jadwal/target_photo',
                    Str::slug('interview jadwal', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            if ($request->temp_target_photo && Storage::disk('public')->exists($request->temp_target_photo)) {
                Storage::disk('public')->delete($request->temp_target_photo);
            }

            $data->target_photo = $target_photo;
        } else {
            $target_photo = $request->temp_target_photo;

            $data->target_photo = $target_photo;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->wawancara_jadwal = 1;
            $updateCaseProgresses->status = 'Wawancara';
            $updateCaseProgresses->substatus = 'Update Wawancara';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }

        if ($data->update()) {
            $cp = CaseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
            $cp->action = 'Perubahan Wawancara Jadwal';
            $cp->updated_by = $user->id;
            $cp->update();

            return redirect()->route('open.interview.jadwal.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy(Request $request, $id)
    {
        $data = InterviewJadwal::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if ($data->target_photo && Storage::disk('public')->exists($data->target_photo)) {
            Storage::disk('public')->delete($data->target_photo);

            $data->target_photo = null;
            $data->update();
        }

        $cp = CaseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
        $cp->action = 'Penghapusan Wawancara Jadwal';
        $cp->updated_by = auth()->user()->id;
        $cp->update();

        $data->delete();
        InterviewHasil::where('interview_scheduler_id', $id)->delete();
        InterviewSaranTL::where('interview_schedule_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
