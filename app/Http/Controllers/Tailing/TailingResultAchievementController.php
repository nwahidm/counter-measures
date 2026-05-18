<?php

namespace App\Http\Controllers\Tailing;

use App\DataTables\Tailing\TailingResultAchievementDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MasterAgama;
use App\Models\Tailing\TailingPemahamanPerilaku;
use App\Models\Tailing\TailingTargetOperasi;
use App\Models\Tailing\TailingResultAchievement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Models\MasterSatker;
use App\Models\Documents;
use Illuminate\Http\Request;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;

class TailingResultAchievementController extends Controller
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
    public function index(TailingResultAchievementDataTable $dataTable)
    {

        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.tailing.result-achievement.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCloseCase();
          $pemahaman_perilaku = DB::table('tailing_pemahaman_perilaku')->get();
          $target_operasi = DB::table('tailing_target_operasi')->get();

        return view('backoffice.close.tailing.result-achievement.create', compact('satker', 'users', 'case', 'pemahaman_perilaku','target_operasi'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            // 'tailing_target_operasi_id' => 'required|string|max:128',
            // 'tailing_pemahaman_perilaku_id' => 'required|string|max:128',
            'hasil_yang_dicapai' => 'required|string|max:1000000',
            'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:20480'
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = auth()->user();

        $data = new TailingResultAchievement;
        $data->kode_satker = $satker->kode_satker;
        $data->case_id = $request->id_case;
        $data->tailing_pemahaman_perilaku_id = $request->tailing_pemahaman_perilaku_id;
        $data->tailing_target_operasi_id = $request->tailing_target_operasi_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;
        

        $document_pdf = new Documents;
        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'close/tailing/result-achievement',
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

        if ($request->submit_type === 'save') {
            
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_hasil_yang_dicapai', '0')
            ->where('tailing_laporan', '0')
            ->update([
                'tailing_hasil_yang_dicapai' => "1",
                'tailing_laporan' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Laporan",
                'percentage' => round((18/29)*100,2)
            ]);;


        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_hasil_yang_dicapai', '0')
            ->where('tailing_laporan', '0')
            ->update([
                'tailing_hasil_yang_dicapai' => "1",
                'tailing_laporan' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Laporan",
                'percentage' => round((29/29)*100,2)
            ]);;


        }
        

        if ($data->save()) {

            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->id;
            $data_case_close_historical_update->action = "Penambahan Tailing Hasil yang Dicapai";
            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            $data_case_close_historical_update2 = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update2->case_id = $request->id_case;
            $data_case_close_historical_update2->action = "Penambahan Tailing Report";
            $data_case_close_historical_update2->created_by = $user->id;
            $data_case_close_historical_update2->updated_by = $user->id;
            $data_case_close_historical_update2->save();

            $document_pdf->relation_id = $data->id;
            $document_pdf->save();

            

            return redirect()->route('close.tailing.result-achievement.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = TailingResultAchievement::with(['TailingTargetOperasi', 'TailingPemahamanPerilaku', 'case', 'satker'])->find($id);

        $data->upload_hasil_yang_dicapai = Storage::url($data->upload_hasil_yang_dicapai);

        return view('backoffice.close.tailing.result-achievement.show', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = TailingResultAchievement::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();

        $pemahaman_perilaku = DB::table('tailing_pemahaman_perilaku')->where('case_id', $data->case_id)->get();
        $target_operasi = DB::table('tailing_target_operasi')->where('tailing_pemahaman_perilaku_id', $data->tailing_pemahaman_perilaku_id)->whereNotNull('tailing_pemahaman_perilaku_id')->get();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.tailing.result-achievement.edit', compact('data', 'users', 'satker', 'case', 'pemahaman_perilaku', 'target_operasi'));
    }

    public function update(Request $request)
    {

         $this->validate($request, [
            'id_case' => 'required|string|max:128',
            // 'id_satker' => 'required|string|max:128',
            // 'tailing_target_operasi_id' => 'required|string|max:128',
            // 'tailing_pemahaman_perilaku_id' => 'required|string|max:128',
            'hasil_yang_dicapai' => 'required|string|max:1000000',
            'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:2048000'
        ]);

        $user = auth()->user();

        $data = TailingResultAchievement::find($request->id);
        $data->case_id = $request->id_case;
        $data->tailing_target_operasi_id = $request->tailing_target_operasi_id;
        $data->tailing_pemahaman_perilaku_id = $request->tailing_pemahaman_perilaku_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'close/tailing/result-achievement',
                    Str::slug('hasil', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
                    'public'
                );

            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
            $document_pdf = Documents::where('relation_id', $request->id)->first();
            $document_pdf->doc_path = $upload_hasil_yang_dicapai;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->save();
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('tailing_hasil_yang_dicapai', '0')
            ->where('tailing_laporan', '0')
            ->update([
                'tailing_hasil_yang_dicapai' => "1",
                'tailing_laporan' => "1",
                'status' => "Pembuntutan",
                'substatus' => "Penambahan Laporan",
                'percentage' => round((29/29)*100,2)
            ]);;
        
        }

        if ($data->update()) {
            return redirect()->route('close.tailing.result-achievement.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = TailingResultAchievement::find($id);

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
