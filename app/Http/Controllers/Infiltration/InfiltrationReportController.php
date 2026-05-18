<?php

namespace App\Http\Controllers\Infiltration;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Infiltration\InfiltrationReportDataTable;
use App\Helpers\DataHelper;
use App\Models\User;
use App\Models\MasterSatker;
use App\Models\CloseCase;

use App\Models\Infiltration\InfiltrationReport;
use App\Models\Infiltration\InfiltrationSecretOperation;
use App\Models\Infiltration\InfiltrationTargetDynamics;
use App\Models\Infiltration\InfiltrationResultAchievement;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Date;
use Mpdf\Mpdf;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;

class InfiltrationReportController extends Controller
{

    public function index(InfiltrationReportDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.infiltration.report.index', compact('satker', 'users'));
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        
        $case = DataHelper::getCloseCase();

        return view('backoffice.close.infiltration.report.create', 
        compact('satker', 'users', 'case',));
    }

    public function edit(Request $request, $id)
    {
        $data = InfiltrationReport::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCloseCase();
        $satker = DataHelper::getSatker();
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.infiltration.report.edit', 
        compact('data', 'users', 'case','satker' ));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'nama_operasi_rahasia' => 'required|string|max:1000000',
            'tanggal_operasi_rahasia' => 'required|date',
            'metode_eksekusi' => 'required|string|max:1000000',
            'operasi_rahasia_dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            'operasi_rahasia_video_upload' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = InfiltrationReport::find($id);
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;

        $data->nama_operasi_rahasia = $request->nama_operasi_rahasia;
        $data->tanggal_operasi_rahasia = $request->tanggal_operasi_rahasia;
        $data->metode_eksekusi = $request->metode_eksekusi;
        


        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->hasFile('operasi_rahasia_dokumen_upload')) {
            $ext_upload_info = $request->file('operasi_rahasia_dokumen_upload')->extension();
            $upload_info = $request->file('operasi_rahasia_dokumen_upload')
                ->storePubliclyAs(
                    'close/infiltration/report/operasi_rahasia_dokumen_upload',
                    Str::slug('infiltration report document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->operasi_rahasia_dokumen_upload = $upload_info;
        }

        if ($request->hasFile('operasi_rahasia_video_upload')) {
            $ext_upload_info = $request->file('operasi_rahasia_video_upload')->extension();
            $upload_info = $request->file('operasi_rahasia_video_upload')
                ->storePubliclyAs(
                    'close/infiltration/report/operasi_rahasia_video_upload',
                    Str::slug('infiltration report video', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->operasi_rahasia_video_upload = $upload_info;
        }
        $data->updated_by = $user->id;

        if ($data->update()) {
            return redirect()->route('close.infiltration.target-dynamics.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'nama_operasi_rahasia' => 'required|string|max:1000000',
            'tanggal_operasi_rahasia' => 'required|date',
            'metode_eksekusi' => 'required|string|max:1000000',
            'operasi_rahasia_dokumen_upload' => 'nullable|file|mimes:pdf|max:2048',
            'operasi_rahasia_video_upload' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = auth()->user();

        $data = new InfiltrationReport;
        $data->satker_id = $satker->id_satker;
        $data->case_id = $request->id_case;

        $data->nama_operasi_rahasia = $request->nama_operasi_rahasia;
        $data->tanggal_operasi_rahasia = $request->tanggal_operasi_rahasia;
        $data->metode_eksekusi = $request->metode_eksekusi;
        


        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->hasFile('operasi_rahasia_dokumen_upload')) {
            $ext_upload_info = $request->file('operasi_rahasia_dokumen_upload')->extension();
            $upload_info = $request->file('operasi_rahasia_dokumen_upload')
                ->storePubliclyAs(
                    'close/infiltration/report/operasi_rahasia_dokumen_upload',
                    Str::slug('infiltration report document', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->operasi_rahasia_dokumen_upload = $upload_info;
        }

        if ($request->hasFile('operasi_rahasia_video_upload')) {
            $ext_upload_info = $request->file('operasi_rahasia_video_upload')->extension();
            $upload_info = $request->file('operasi_rahasia_video_upload')
                ->storePubliclyAs(
                    'close/infiltration/report/operasi_rahasia_video_upload',
                    Str::slug('infiltration report video', '_') . '_' . Str::random() . '.' . $ext_upload_info,
                    'public'
                );

            $data->operasi_rahasia_video_upload = $upload_info;
        }

        if ($data->save()) {
            return redirect()->route('close.infiltration.report.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = InfiltrationReport::find($id);
        $case = CloseCase::find($data->case_id);
        $satker = MasterSatker::find($data->satker_id);
        

        return view('backoffice.close.infiltration.report.show', compact(
            'data', 'case', 'satker'));
    }

    public function destroy($id, Request $request)
    {
        $data = InfiltrationReport::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $data->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }


    public function downloadFile($id)
    {
        // $data = InfiltrationReport::find(decrypt($id));
        // $data->case->target_photo = json_decode($data->case->target_photo, true);

        $case = CloseCase::findOrFail(decrypt($id));
        $case->target_photo = json_decode($case->target_photo, true);

        $infiltration_secret_operation_datas = InfiltrationSecretOperation::where('case_id', $case->id)->get();
        $infiltration_target_dynamic_datas = InfiltrationTargetDynamics::where('case_id', $case->id)->get();
        $infiltration_result_achievement_datas = InfiltrationResultAchievement::where('case_id', $case->id)->get();
       
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(
            view(
                "backoffice.close.infiltration.report.pdf", 
                compact(
                    'case',
                    'infiltration_secret_operation_datas',
                    'infiltration_target_dynamic_datas',
                    'infiltration_result_achievement_datas'
                )));

        $filename = 'Infiltration_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');

    }
}
