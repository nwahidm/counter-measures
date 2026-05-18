<?php

namespace App\Http\Controllers\Research;

use App\DataTables\Research\ResearchSprintDataTable;
use App\Http\Controllers\Controller;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;
use App\Models\Open\Research\ResearchPotensiAght;
use App\Models\Open\Research\ResearchSaranTindakLanjut;
use App\Models\OpenCase;
use App\Models\Open\Research\ResearchSuratPerintah;
use App\Models\Research\ResearchLapinsus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\MasterSatker;
use Illuminate\Http\Request;

class ResearchSprintController extends Controller
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
    public function index(ResearchSprintDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.open.research.sprint.index', compact('satker', 'users'));
    }

    public function create()
    {
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCase();
        $satker = DataHelper::getSatkerGlobal();

        return view('backoffice.open.research.sprint.create', compact('users', 'case', 'satker'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'nomor_sprint' => 'required|string|max:128',
            'perihal_sprint' => 'required|string|max:255',
            'upload_sprint' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = new ResearchSuratPerintah;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->surat_perintah_number = $request->nomor_sprint;
        $data->surat_perintah_perihal = $request->perihal_sprint;
        $data->surat_perintah_date = $request->tanggal_sprint;
        $data->surat_perintah_date_started = $request->tanggal_mulai_sprint;
        $data->surat_perintah_date_finished = $request->tanggal_akhir_sprint;

        if ($request->hasFile('upload_sprint')) {
            $ext_upload_sprint = $request->file('upload_sprint')->extension();
            $upload_sprint = $request->file('upload_sprint')
                ->storePubliclyAs(
                    'open/research/warrant/surat_perintah_path',
                    Str::slug('penelitian surat perintah', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );

            $data->surat_perintah_path = $upload_sprint;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        if ($request->submit_type === 'save') {

            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $data->case_id)->first();
                $op->penelitian_upload_surat_perintah = 1;
                $op->status = $op->percentage > 5.88 ? $op->status : "Penelitian";
                $op->substatus = $op->percentage > 5.88 ? $op->substatus : "Input Surat Perintah Penelitian";
                $op->percentage = $op->percentage > 5.88 ? $op->percentage : 5.88;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->case_id;
                $cp->action = 'Penambahan Penelitian Surat Perintah';
                $cp->created_by = $user->id;
                $cp->save();
    
                if ($request->hasFile('upload_sprint')) {
                    DataHelper::insertDocument($data->id_surat_perintah, $data->surat_perintah_path);
                }
    
                return redirect()->route('open.research.warrant.index')->with("success", "Data berhasil ditambah.");
            }    
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');

        }else{
            if ($data->save()) {
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->penelitian_upload_surat_perintah = 1;
                $updateCaseProgresses->status = 'Penelitian';
                $updateCaseProgresses->substatus = 'Penambahan Surat Perintah Penelitian Dan Selesai';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->case_id;
                $cp->action = 'Penambahan Penelitian Surat Perintah';
                $cp->created_by = $user->id;
                $cp->save();
                
                if ($request->hasFile('upload_sprint')) {
                    DataHelper::insertDocument($data->id_surat_perintah, $data->surat_perintah_path);
                }

                return redirect()->route('open.research.warrant.index')->with("success", "Data berhasil ditambah.");

            }
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }
        
    }

    public function show(Request $request, $id)
    {
        $data = ResearchSuratPerintah::find($id);

        return view('backoffice.open.research.sprint.show', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = ResearchSuratPerintah::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCase();
        $satker = DataHelper::getSatkerGlobal();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.open.research.sprint.edit', compact('data', 'users', 'case', 'satker'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'nomor_sprint' => 'required|string|max:128',
            'perihal_sprint' => 'required|string|max:255',
            'tanggal_sprint' => 'required|string|max:128',
            'tanggal_mulai_sprint' => 'required|date|after_or_equal:tanggal_sprint|before:tanggal_akhir_sprint',
            'tanggal_akhir_sprint' => 'required|date|after:tanggal_mulai_sprint',
            'upload_sprint' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = ResearchSuratPerintah::find($id);
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->surat_perintah_number = $request->nomor_sprint;
        $data->surat_perintah_perihal = $request->perihal_sprint;
        $data->surat_perintah_date = $request->tanggal_sprint;
        $data->surat_perintah_date_started = $request->tanggal_mulai_sprint;
        $data->surat_perintah_date_finished = $request->tanggal_akhir_sprint;

        if ($request->hasFile('upload_sprint')) {
            $ext_upload_sprint = $request->file('upload_sprint')->extension();
            $upload_sprint = $request->file('upload_sprint')
                ->storePubliclyAs(
                    'open/research/warrant/surat_perintah_path',
                    Str::slug('penelitian surat perintah', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );

            if ($data->surat_perintah_path && Storage::disk('public')->exists($request->temp_upload_sprint)) {
                Storage::disk('public')->delete($request->temp_upload_sprint);
            }

            $data->surat_perintah_path = $upload_sprint;
        } else {
            $upload_sprint = $request->temp_upload_sprint;

            $data->surat_perintah_path = $upload_sprint;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->penelitian_upload_surat_perintah = 1;
            $updateCaseProgresses->status = 'Penelitian';
            $updateCaseProgresses->substatus = 'Penambahan Surat Perintah Penelitian dan Selesai';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }

        if ($data->update()) {
            if ($request->hasFile('upload_sprint')) {
                DataHelper::insertDocument($data->id_surat_perintah, $data->surat_perintah_path);
            }

            return redirect()->route('open.research.warrant.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = ResearchSuratPerintah::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if ($data->surat_perintah_path && Storage::disk('public')->exists($data->surat_perintah_path)) {
            Storage::disk('public')->delete($data->surat_perintah_path);
        }

        $data->delete();
        ResearchLaporanInformasiKhusus::where('surat_perintah_id', $id)->delete();
        ResearchSaranTindakLanjut::where('surat_perintah_id', $id)->delete();
        ResearchPotensiAght::where('id_sprint', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
