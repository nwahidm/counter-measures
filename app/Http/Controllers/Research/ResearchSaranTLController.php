<?php

namespace App\Http\Controllers\Research;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Documents;
use App\Helpers\DataHelper;
use Illuminate\Http\Request;

use App\Models\CaseProgresses;
use App\Helpers\ResearchDataHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\Open\Research\ResearchSuratPerintah;
use App\DataTables\Research\ResearchSaranTLDataTable;
use App\Models\Open\Research\ResearchSaranTindakLanjut;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;
use App\Models\Open\Research\ResearchPotensiAght;

class ResearchSaranTLController extends Controller
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
    public function index(ResearchSaranTLDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.open.research.saran_tl.index', compact('satker', 'users'));
    }

    public function create()
    {
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCase();
        $satker = DataHelper::getSatkerGlobal();

        return view('backoffice.open.research.saran_tl.create', compact('users', 'case', 'satker'));
    }

    public function createFromLapinsus($id)
    {
        $case = DataHelper::getCase();
        $satker = DataHelper::getSatkerGlobal();
        $lapinsus = ResearchLaporanInformasiKhusus::where('id', $id)->first();
        $listSprint = ResearchDataHelper::getSuratPerintahByCase($lapinsus->case_id);
        $listLapinsus = ResearchDataHelper::getLapinsusBySuratPerintah($lapinsus->surat_perintah_id);

        return view('backoffice.open.research.saran_tl.create_lapinsus', compact('case', 'satker', 'lapinsus', 'listSprint','listLapinsus'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            // 'id_sprint' => 'required|string|max:128',
            // 'id_lapinsus' => 'required|string|max:128',
            'tanggal_tl' => 'required|date',
            'saran_tl' => 'required|string|max:1000000',
        ]);

        $user = auth()->user();

        $data = new ResearchSaranTindakLanjut;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->surat_perintah_id = $request->id_sprint;
        $data->laporan_informasi_khusus_id = $request->id_lapinsus;
        $data->saran_dan_tindak_lanjut_date = $request->tanggal_tl;
        $data->saran_dan_tindak_lanjut = $request->saran_tl;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $request->id_case)->first();
                $op->penelitian_saran_dan_tindak_lanjut = 1;
                $op->status = $op->percentage > 29.4 ? $op->status : "Penelitian";
                $op->substatus = $op->percentage > 29.4 ? $op->substatus : "Input Saran dan Tindak Lanjut Penelitian";
                $op->percentage = $op->percentage > 29.4 ? $op->percentage : 29.4;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id =$request->id_cased;
                $cp->action = 'Penambahan Penelitian Saran dan Tindak Lanjut';
                $cp->created_by = $user->id;
                $cp->save();    
                return redirect()->route('open.research.advice-measure.index')->with("success", "Data berhasil ditambah.");
            }    
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
            
        }else{
            if ($data->save()) {
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->penelitian_saran_dan_tindak_lanjut = 1;
                $updateCaseProgresses->status = 'Penelitian';
                $updateCaseProgresses->substatus = 'Penambahan Saran, Tindak Lanjut Penelitian dan Selesai';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Penelitian Saran dan Tindak Lanjut';
                $cp->created_by = $user->id;
                $cp->save();

                return redirect()->route('open.research.advice-measure.index')->with("success", "Data berhasil ditambah.");
            }
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
            
        }
        
    }

    public function show(Request $request, $id)
    {
        $data = ResearchSaranTindakLanjut::find($id);
        $document_pdf_data = Documents::where('relation_id', $data->id)->first();
   
        return view('backoffice.open.research.saran_tl.show', compact('data', 'document_pdf_data'));
    }

    public function edit(Request $request, $id)
    {
        $data = ResearchSaranTindakLanjut::with('researchLaporanInformasiKhusus.researchSuratPerintah.case', 'researchLaporanInformasiKhusus.researchSuratPerintah')
                                        ->where('id_saran_dan_tindak_lanjut', $id)
                                        ->first();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCase();
        $satker = DataHelper::getSatkerGlobal();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $sprint = ResearchDataHelper::getSprintPerCase($data->case_id);
        $lik = ResearchDataHelper::getLapinsusBySuratPerintah($data?->surat_perintah_id);

        return view('backoffice.open.research.saran_tl.edit', compact('data', 'users', 'case', 'sprint', 'lik', 'satker'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            // 'id_sprint' => 'required|string|max:128',
            // 'id_lapinsus' => 'required|string|max:128',
            'tanggal_tl' => 'required|date',
            'saran_tl' => 'required|string|max:1000000',
        ]);

        $user = auth()->user();

        $data = ResearchSaranTindakLanjut::find($id);
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->surat_perintah_id = $request->id_sprint;
        $data->laporan_informasi_khusus_id = $request->id_lapinsus;
        $data->saran_dan_tindak_lanjut_date = $request->tanggal_tl;
        $data->saran_dan_tindak_lanjut = $request->saran_tl;

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->penelitian_saran_dan_tindak_lanjut = 1;
            $updateCaseProgresses->status = 'Penelitian';
            $updateCaseProgresses->substatus = 'Penambahan Saran, Tindak Lanjut Penelitian dan Selesai';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }

        if ($data->update()) {
            return redirect()->route('open.research.advice-measure.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = ResearchSaranTindakLanjut::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $data->delete();
        // ResearchSaranTindakLanjut::where('laporan_informasi_khusus_id', $id)->delete();
        ResearchPotensiAght::where('id_saran_tl', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
