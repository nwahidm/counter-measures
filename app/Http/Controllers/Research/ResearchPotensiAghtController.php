<?php

namespace App\Http\Controllers\Research;

use App\DataTables\Research\ResearchPotensiAghtDataTable;
use App\Http\Controllers\Controller;
use App\Models\Open\Research\ResearchPotensiAght;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Helpers\DataHelper;
use App\Helpers\ResearchDataHelper;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use Illuminate\Http\Request;

class ResearchPotensiAghtController extends Controller
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
    public function index(ResearchPotensiAghtDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.open.research.potensi_aght.index', compact('satker', 'users'));
    }

    public function create()
    {
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCase();
        $satker = DataHelper::getSatker();

        return view('backoffice.open.research.potensi_aght.create', compact('users', 'case', 'satker'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            // 'id_sprint' => 'nullable|string|max:128',
            // 'id_lapinsus' => 'nullable|string|max:128',
            // 'id_saran_tl' => 'nullable|string|max:128',
            'ancaman' => 'required|string|max:1000000',
            'gangguan' => 'required|string|max:1000000',
            'hambatan' => 'required|string|max:1000000',
            'tantangan' => 'required|string|max:1000000',
        ]);

        $user = auth()->user();

        $data = new ResearchPotensiAght;
        $data->id_satker = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->id_sprint = $request->id_sprint;
        $data->id_lapinsus = $request->id_lapinsus;
        $data->id_saran_tl = $request->id_saran_tl;
        $data->jenis_aght = $request->jenis_aght;
        $data->waktu = $request->waktu;
        $data->tempat = $request->tempat;
        $data->perihal = $request->perihal;
        $data->keterangan = $request->keterangan;

        $data->ancaman = $request->ancaman;
        $data->gangguan = $request->gangguan;
        $data->hambatan = $request->hambatan;
        $data->tantangan = $request->tantangan;


        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $request->id_case)->first();
                $op->penelitian_aght = 1;
                $op->status = $op->percentage > 35.28 ? $op->status : "Penelitian";
                $op->substatus = $op->percentage > 35.28 ? $op->substatus : "Input AGHT Penelitian";
                $op->percentage = $op->percentage > 35.28 ? $op->percentage : 35.28;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Penelitian Ancaman, Gangguan, Hambatan, dan Tantangan';
                $cp->created_by = $user->id;
                $cp->save();
    
                // Laporan
                $op = CaseProgresses::where('case_id', $request->id_case)->first();
                $op->penelitian_laporan = 1;
                $op->status = $op->percentage > 41.16 ? $op->status : "Penelitian";
                $op->substatus = $op->percentage > 41.16 ? $op->substatus : "Penelitian Laporan";
                $op->percentage = $op->percentage > 41.16 ? $op->percentage : 41.16;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;;
                $cp->action = 'Penambahan Penelitian Laporan';
                $cp->created_by = $user->id;
                $cp->save();
    
                return redirect()->route('open.research.tibc.index')->with("success", "Data berhasil ditambah.");
            }    
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
        }else{
            if ($data->save()) {
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->penelitian_aght = 1;
                $updateCaseProgresses->status = 'Penelitian';
                $updateCaseProgresses->substatus = 'Penambahan AGHT Penelitian dan Selesai';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Penelitian Laporan';
                $cp->created_by = $user->id;
                $cp->save();

                return redirect()->route('open.research.tibc.index')->with("success", "Data berhasil ditambah.");
            }
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');            
        }        
    }

    public function show(Request $request, $id)
    {
        $data = ResearchPotensiAght::find($id);

        return view('backoffice.open.research.potensi_aght.show', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = ResearchPotensiAght::with('researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.case', 'researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah')
                                    ->where('id', $id)
                                    ->first();
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCase();
        $satker = DataHelper::getSatker();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }


        $sprint = ResearchDataHelper::getSprintPerCase($data->case_id);
        $lik = ResearchDataHelper::getLapinsusPerSprint($data->researchSaranTindakLanjut?->researchLaporanInformasiKhusus?->surat_perintah_id);
        $saran = DataHelper::getSaranTlPerLapinsus($data->researchSaranTindakLanjut?->researchLaporanInformasiKhusus->id);

        return view('backoffice.open.research.potensi_aght.edit', compact('data', 'users', 'case', 'sprint', 'lik',  'saran', 'satker'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            // 'id_sprint' => 'nullable|string|max:128',
            // 'id_lapinsus' => 'nullable|string|max:128',
            // 'id_saran_tl' => 'nullable|string|max:128',
            'ancaman' => 'required|string|max:1000000',
            'gangguan' => 'required|string|max:1000000',
            'hambatan' => 'required|string|max:1000000',
            'tantangan' => 'required|string|max:1000000',

        ]);

        $user = auth()->user();

        $data = ResearchPotensiAght::find($id);
        // $data->id_satker = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->id_sprint = $request->id_sprint;
        $data->id_lapinsus = $request->id_lapinsus;
        $data->id_saran_tl = $request->id_saran_tl;
        $data->jenis_aght = $request->jenis_aght;
        $data->waktu = $request->waktu;
        $data->tempat = $request->tempat;
        $data->perihal = $request->perihal;
        $data->keterangan = $request->keterangan;
        $data->ancaman = $request->ancaman;
        $data->gangguan = $request->gangguan;
        $data->hambatan = $request->hambatan;
        $data->tantangan = $request->tantangan;

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->penelitian_aght = 1;
            $updateCaseProgresses->status = 'Penelitian';
            $updateCaseProgresses->substatus = 'Penambahan AGHT Penelitian dan Selesai';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }

        if ($data->update()) {
            $cp = CaseEventHistoricalUpdates::where('case_id', $request->id_case)->first();
            $cp->action = "Perubahan Penelitian Saran dan Tindak Lanjut";
            $cp->updated_by = $user->id;
            $cp->update();

            return redirect()->route('open.research.tibc.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = ResearchPotensiAght::find($id);

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
