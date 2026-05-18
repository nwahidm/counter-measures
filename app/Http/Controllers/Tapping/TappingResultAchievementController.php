<?php

namespace App\Http\Controllers\Tapping;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Documents;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\Models\Tapping\TappingResultAchievement;
use App\DataTables\Tapping\TappingResultAchievementDataTable;

class TappingResultAchievementController extends Controller
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
    public function index(TappingResultAchievementDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.tapping.result_achievement.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCloseCase();
        $itlsig = DataHelper::getTappingIntelligentSignal();

        return view('backoffice.close.tapping.result_achievement.create', compact('satker', 'users', 'itlsig', 'case'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'tapping_electronic_device_data_id' => 'nullable|string|max:128',
            'tapping_intelligent_signal_data_id' => 'nullable|string|max:128',
            'hasil_yang_dicapai' => 'required|string|max:1280000',
            'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $user = auth()->user();

        $data = new TappingResultAchievement;
        $data->satker_id = $user->satker->id_satker;
        $data->case_id = $request->id_case;
        $data->tapping_electronic_device_data_id = $request->tapping_electronic_device_data_id;
        $data->tapping_intelligent_signal_data_id = $request->tapping_intelligent_signal_data_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'close/tapping/result_achievement/upload_hasil_yang_dicapai',
                    Str::slug('tapping hasil', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
                    'public'
                );

            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;

            DataHelper::insertDocument($data->id_tapping_result_achievement, $data->upload_hasil_yang_dicapai);
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        $data->satker_id = $user->id_satker;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'tapping_hasil_penyadapan' => "1",
                'status' => $close_case_progress->percentage > 100 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 100 ? $close_case_progress->substatus : 'Input Hasil Penyadapan',
                // 'percentage' => $close_case_progress->percentage > 100 ? $close_case_progress->percentage : 100,
                'percentage' => $close_case_progress->percentage > 100 ? $close_case_progress->percentage : $close_case_progress->percentage,
                'updated_by' => $user->id
            ]);

        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'tapping_hasil_penyadapan' => "1",
                'status' => $close_case_progress->percentage > 100 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 100 ? $close_case_progress->substatus : 'Input Hasil Penyadapan',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);

        }

        if ($data->save()) {
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Hasil Penyadapan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            // update progress
            

            // Laporan
            $op = CaseCloseProgresses::where('case_id', $data->case_id)->first();

            $op->tapping_laporan = 1;
            $op->updated_by = $user->id;
            $op->save();

            $cp = new CaseCloseEventHistoricalUpdates;
            $cp->case_id = $data->case_id;
            $cp->action = 'Penambahan Penyadapan Laporan';
            $cp->created_by = $user->id;
            $cp->save();
            
            return redirect()->route('close.tapping.result_achievement.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = TappingResultAchievement::with(['tappingIntelligentSignal', 'tappingElectronicDevice', 'case', 'satker'])->find($id);
        $document_pdf_data = Documents::where('relation_id', $data->id)->first();

        return view('backoffice.close.tapping.result_achievement.show', compact('data', 'document_pdf_data'));
    }

    public function edit(Request $request, $id)
    {
        $data = TappingResultAchievement::with(['tappingIntelligentSignal', 'tappingElectronicDevice', 'case', 'satker'])->find($id);
        
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $eldev = DataHelper::getTappingElectronicDeviceByCase($data->case_id);
        $itlsig = DataHelper::getTappingIntelligentSignalByDevice($data->tapping_electronic_device_data_id);

        return view('backoffice.close.tapping.result_achievement.edit', compact('data', 'satker', 'itlsig', 'case', 'eldev'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'tapping_electronic_device_data_id' => 'nullable|string|max:128',
            'tapping_intelligent_signal_data_id' => 'nullable|string|max:128',
            'hasil_yang_dicapai' => 'required|string|max:1280000',
            // 'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $user = auth()->user();

        $data = TappingResultAchievement::find($id);
        $data->case_id = $request->id_case;
        $data->tapping_electronic_device_data_id = $request->tapping_electronic_device_data_id;
        $data->tapping_intelligent_signal_data_id = $request->tapping_intelligent_signal_data_id;
        $data->tapping_intelligent_signal_data_id = $request->tapping_intelligent_signal_data_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'close/tapping/result_achievement/upload_hasil_yang_dicapai',
                    Str::slug('tapping result achievement', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
                    'public'
                );

            if ($request->old_upload_hasil_yang_dicapai && Storage::disk('public')->exists($request->old_upload_hasil_yang_dicapai)) {
                Storage::disk('public')->delete($request->old_upload_hasil_yang_dicapai);
            }

            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
        } else {
            $upload_hasil_yang_dicapai = $request->old_upload_hasil_yang_dicapai;

            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $data->case_id)->first();
            $close_case_progress->update([
                'tapping_hasil_penyadapan' => "1",
                'status' => $close_case_progress->percentage > 100 ? $close_case_progress->status : 'Penyadapan',
                'substatus' => $close_case_progress->percentage > 100 ? $close_case_progress->substatus : 'Input Hasil Penyadapan',
                'percentage' => 100,
                'updated_by' => $user->id
            ]);
        }

        if ($data->update()) {
            DataHelper::insertDocument($data->id_tapping_result_achievement, $data->upload_hasil_yang_dicapai, $request->old_upload_hasil_yang_dicapai, $user->id);

            $cp = CaseCloseEventHistoricalUpdates::where('case_id', $data->tappingIntelligentSignal->tappingElectronicDevice->case_id)->first();
            $cp->action = "Perubahan Hasil Penyadapan";
            $cp->updated_by = $request->user_id;
            $cp->update();

            return redirect()->route('close.tapping.result_achievement.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy(Request $request, $id)
    {
        $data = TappingResultAchievement::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if ($data->upload_hasil_yang_dicapai && Storage::disk('public')->exists($data->upload_hasil_yang_dicapai)) {
            Storage::disk('public')->delete($data->upload_hasil_yang_dicapai);

            Documents::where('relation_id', $data->id_tapping_result_achievement)
                ->where('doc_path', $data->upload_hasil_yang_dicapai)
                ->delete();

            $data->upload_hasil_yang_dicapai = null;
            $data->update();
        }

        $cp = CaseCloseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
        $cp->action = "Penghapusan Hasil Penyadapan";
        $cp->updated_by = $data->created_by;
        $cp->update();

        $data->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
