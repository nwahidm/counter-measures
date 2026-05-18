<?php

namespace App\Http\Controllers\Infiltration;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Infiltration\InfiltrationResultAchievementDataTable;
use App\Helpers\DataHelper;
use App\Helpers\InfiltrationDataHelper;
use App\Models\User;
use App\Models\MasterSatker;
use App\Models\CloseCase;
use App\Models\Documents;
use App\Models\Infiltration\InfiltrationResultAchievement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;

class InfiltrationResultAchievementController extends Controller
{

    public function index(InfiltrationResultAchievementDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');


        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.infiltration.result-achievement.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        
        $case = DataHelper::getCloseCase();

           $dinamika_target = DB::table('infiltration_dinamika_target')->get();
          $operasi_rahasia = DB::table('infiltration_operasi_rahasia')->get();

        return view('backoffice.close.infiltration.result-achievement.create', 
        compact('satker', 'users', 'case','dinamika_target','operasi_rahasia'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            // 'infiltration_dinamika_target_id' => 'required|string|max:128',
            // 'infiltration_operasi_rahasia_id' => 'required|string|max:128',
            'hasil_yang_dicapai' => 'required|string',
            'upload_hasil_yang_dicapai' => 'required|file|mimes:pdf|max:2048',
        ]);

        $user = auth()->user();

        $data = new InfiltrationResultAchievement;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->infiltration_dinamika_target_id = $request->infiltration_dinamika_target_id;
        $data->infiltration_operasi_rahasia_id = $request->infiltration_operasi_rahasia_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        $document_pdf = new Documents;
        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'close/infiltration/result-achievement',
                    Str::slug('hasil', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
                    'public'
                );

            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;

            $document_pdf->doc_path = $upload_hasil_yang_dicapai;;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
        }

        $data->created_by = $user->id;
        $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update->case_id = $data->id;
        $data_case_close_historical_update->action = "Penambahan Infiltration Hasil yang Dicapai";

        $data_case_close_historical_update->created_by = $user->id;
        $data_case_close_historical_update->updated_by = $user->id;

        $data_case_close_historical_update2 = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update2->case_id = $data->id;
        $data_case_close_historical_update2->action = "Penambahan Infiltration Report";

        $data_case_close_historical_update2->created_by = $user->id;
        $data_case_close_historical_update2->updated_by = $user->id;
        
        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('infiltration_hasil_yang_dicapai', '0')
            ->update([
                'infiltration_hasil_yang_dicapai' => "1",
                'infiltration_laporan' => "1",
                'status' => "Penyusupan",
                'substatus' => "Penambahan Laporan",
                'percentage' => round((21/29)*100, 2)
            ]);;

        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('infiltration_hasil_yang_dicapai', '0')
            ->update([
                'infiltration_hasil_yang_dicapai' => "1",
                'infiltration_laporan' => "1",
                'status' => "Penyusupan",
                'substatus' => "Penambahan Laporan",
                'percentage' => round((29/29)*100, 2)
            ]);;


        }

        if ($data->save()) {
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update2->case_id = $request->id_case;
            $document_pdf->relation_id = $data->id;
            $data_case_close_historical_update->save();
            $data_case_close_historical_update2->save();
            $document_pdf->save();

            
            return redirect()->route('close.infiltration.result-achievement.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = InfiltrationResultAchievement::with(['InfiltrationTargetDynamics', 'InfiltrationSecretOperation', 'case', 'satker'])->find($id);

        return view('backoffice.close.infiltration.result-achievement.show', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = InfiltrationResultAchievement::find($id);
        
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();

         $dinamika_target = InfiltrationDataHelper::getInfiltrationDinamikaTargetbyOperasiRahasiaId($data->infiltration_operasi_rahasia_id);
          $operasi_rahasia = InfiltrationDataHelper::getInfiltrationOperasiRahasiabyCaseId($data->case_id);


        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.infiltration.result-achievement.edit', compact('data', 'satker', 'case', 'dinamika_target', 'operasi_rahasia'));
    }

    public function update(Request $request)
    {

         $this->validate($request, [
            'id_case' => 'required|string|max:128',
            // 'id_satker' => 'required|string|max:128',
            // 'infiltration_dinamika_target_id' => 'required|string|max:128',
            // 'infiltration_operasi_rahasia_id' => 'required|string|max:128',
            'hasil_yang_dicapai' => 'required|string',
            'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $user = auth()->user();

        $data = InfiltrationResultAchievement::find($request->id);
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->infiltration_dinamika_target_id = $request->infiltration_dinamika_target_id;
        $data->infiltration_operasi_rahasia_id = $request->infiltration_operasi_rahasia_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'close/infiltration/result-achievement',
                    Str::slug('hasil', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
                    'public'
                );

            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;

            $document_pdf = Documents::where('relation_id',$request->id)->first();
            $document_pdf->doc_path = $upload_hasil_yang_dicapai;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->updated_by = $user->id;
            $document_pdf->save();
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('infiltration_hasil_yang_dicapai', '0')
            ->update([
                'infiltration_hasil_yang_dicapai' => "1",
                'infiltration_laporan' => "1",
                'status' => "Penyusupan",
                'substatus' => "Penambahan Laporan",
                'percentage' => round((29/29)*100, 2)
            ]);;
        }

        if ($data->update()) {
            return redirect()->route('close.infiltration.result-achievement.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = InfiltrationResultAchievement::find($id);

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
