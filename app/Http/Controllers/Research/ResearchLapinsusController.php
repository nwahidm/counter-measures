<?php

namespace App\Http\Controllers\Research;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Documents;
use App\Helpers\DataHelper;
use App\Helpers\ResearchDataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\DataTables\Research\ResearchLapinsusDataTable;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;
use App\Models\Open\Research\ResearchSuratPerintah;
use App\Models\Open\Research\ResearchSaranTindakLanjut;
use App\Models\Open\Research\ResearchPotensiAght;

use App\Models\Research\ResearchSprint;

class ResearchLapinsusController extends Controller
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
    public function index(ResearchLapinsusDataTable $dataTable)
    {
        // return $mo =  ResearchLaporanInformasiKhusus::select('surat_perintah_id')->with([
        //     'researchSuratPerintah', 
        //     'researchSuratPerintah.case',
        //     'researchSuratPerintah.case.satker',
        //     'ResearchSuratPerintah.case'
        // ])->get()
        //     ;
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.open.research.lapinsus.index', compact('satker', 'users'));
    }

    public function create()
    {
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCase();
        $satker = DataHelper::getSatkerGlobal();
        $listPegawai = DataHelper::getPegawai();

        return view('backoffice.open.research.lapinsus.create', compact('users', 'case', 'satker', 'listPegawai'));
    }

    public function upload()
    {
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCase();
        $satker = DataHelper::getSatkerGlobal();
        $listPegawai = DataHelper::getPegawai();

        return view('backoffice.open.research.lapinsus.upload', compact('users', 'case', 'satker', 'listPegawai'));
    }

    public function storeUpload(Request $request)
    {
        $this->validate($request, [
            'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            'upload_lapinsus' => 'nullable|mimes:pdf|max:30000',
        ]);

        $user = auth()->user();

        $data = new ResearchLaporanInformasiKhusus();
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->surat_perintah_id = $request->id_sprint;

        if ($request->hasFile('upload_lapinsus')) {
            $ext_upload_lapinsus = $request->file('upload_lapinsus')->extension();
            $upload_lapinsus = $request->file('upload_lapinsus')
                ->storePubliclyAs(
                    'open/research/spesific-intel-report/',
                    Str::slug('file_laporan_informasi_khusus_', '_') . '_' . Str::random() . '.' . $ext_upload_lapinsus,
                    'public'
                );
            $data->file_laporan_informasi_khusus = $upload_lapinsus;
            DataHelper::insertDocument($data->id, $data->file_laporan_informasi_khusus);
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $request->id_case)->first();
                $op->penelitian_lapinsus = 1;
                $op->status = $op->percentage > 11.76 ? $op->status : "Penelitian";
                $op->substatus = $op->percentage > 11.76 ? $op->substatus : "Upload Laporan Informasi Khusus Penelitian";
                $op->percentage = $op->percentage > 11.76 ? $op->percentage : 11.76;
                $op->updated_by = $user->id;
                $op->update();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Upload Penelitian Laporan Informasi Khusus';
                $cp->created_by = $user->id;
                $cp->update();
    
    
                return redirect()->route('open.research.spesific-intel-report.index')->with("success", "Laporan berhasil diunggah.");
            }    
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');

        }else{
            if ($data->save()) {
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->penelitian_lapinsus = 1;
                $updateCaseProgresses->status = 'Penelitian';
                $updateCaseProgresses->substatus = 'Upload Laporan Informasi Khusus Penelitian dan Selesai';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;;
                $cp->action = 'Upload Penelitian Laporan Informasi Khusus';
                $cp->created_by = $user->id;
                $cp->update();

                return redirect()->route('open.research.spesific-intel-report.index')->with("success", "Data berhasil diunggah.");
            }
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');            
        } 
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            'nomor_surat' => 'required|string|max:128',
            // 'jabatan' => 'required|string|max:128',
            // 'nama_pejabat' => 'required|string|max:128',
            'penandatangan' => 'required|string|max:128'
        ]);

        $user = auth()->user();

        $data = new ResearchLaporanInformasiKhusus();
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->surat_perintah_id = $request->id_sprint;
        $data->nomor_surat = $request->nomor_surat;
        $data->tanggal_surat = $request->tanggal_surat;
        $data->perihal_surat = $request->perihal_surat;
        $data->informasi_diperoleh = $request->informasi_diperoleh;
        $data->sumber_informasi = $request->sumber_informasi;
        $data->tren_perkembangan = $request->tren_perkembangan;
        $data->saran_tindak = $request->saran_tindak;
        // $data->jabatan = $request->jabatan;
        // $data->nama_pejabat = $request->nama_pejabat;
        $data->nip = $request->penandatangan;

        
        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $request->id_case)->first();
                $op->penelitian_lapinsus = 1;
                $op->status = $op->percentage > 11.76 ? $op->status : "Penelitian";
                $op->substatus = $op->percentage > 11.76 ? $op->substatus : "Input Laporan Informasi Khusus Penelitian";
                $op->percentage = $op->percentage > 11.76 ? $op->percentage : 11.76;
                $op->updated_by = $user->id;
                $op->update();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Penelitian Laporan Informasi Khusus';
                $cp->created_by = $user->id;
                $cp->update();
    
                if ($request->hasFile('upload_lapinsus')) {
                    DataHelper::insertDocument($data->id, $data->file_laporan_informasi_khusus);
                }
    
                return redirect()->route('open.research.spesific-intel-report.index')->with("success", "Data berhasil ditambah.");
            }    
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');

        }else{
            if ($data->save()) {
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->penelitian_lapinsus = 1;
                $updateCaseProgresses->status = 'Penelitian';
                $updateCaseProgresses->substatus = 'Penambahan Laporan Informasi Khusus Penelitian dan Selesai';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;;
                $cp->action = 'Penambahan Penelitian Laporan Informasi Khusus';
                $cp->created_by = $user->id;
                $cp->update();

                if ($request->hasFile('upload_lapinsus')) {
                    DataHelper::insertDocument($data->id, $data->file_laporan_informasi_khusus);
                }

                return redirect()->route('open.research.spesific-intel-report.index')->with("success", "Data berhasil ditambah.");
            }
            return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');            
        }        
    }

    public function show(Request $request, $id)
    {
        $data = ResearchLaporanInformasiKhusus::find($id);
        $document_pdf_data = Documents::where('relation_id', $data->id)->first();

        return view('backoffice.open.research.lapinsus.show', compact('data', 'document_pdf_data'));
    }

    public function edit(Request $request, $id)
    {
        $data = ResearchLaporanInformasiKhusus::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCase();
        $satker = DataHelper::getSatkerGlobal();
        $listPegawai = DataHelper::getPegawai();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        
        $dataSprint = ResearchSuratPerintah::find($data->surat_perintah_id);
        
        $sprint = ResearchDataHelper::getSuratPerintahByCase($data->case_id);

        if($data->file_laporan_informasi_khusus){
            return view('backoffice.open.research.lapinsus.upload-edit', compact('data', 'case', 'sprint', 'satker'));
        } else{
            return view('backoffice.open.research.lapinsus.edit', compact('data', 'users', 'case', 'sprint', 'satker', 'listPegawai'));
        }
    }

    public function update(Request $request, $id)
    {
        // $this->validate($request, [
        //     'id_case' => 'required|string|max:128',
        //     'id_sprint' => 'required|string|max:128',
        //     'nomor_surat' => 'required|string|max:128',
        //     'tanggal_surat' => 'required|date',
        //     'perihal_surat' => 'required|string|max:255',
        //     'informasi_diperoleh' => 'required|string|max:1000000',
        //     'sumber_informasi' => 'required|string|max:1000000',
        //     'tren_perkembangan' => 'required|string|max:1000000',
        //     'saran_tindak' => 'required|string|max:1000000',
        //     'upload_lapinsus' => 'nullable|file|mimes:pdf|max:2048'
        // ]);
        $this->validate($request, [
            // 'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            // 'id_sprint' => 'required|string|max:128',
            'nomor_surat' => 'required|string|max:128',

            // 'jabatan' => 'required|string|max:128',
            // 'nama_pejabat' => 'required|string|max:128',
            'penandatangan' => 'required|string|max:128',
        ]);

        $user = auth()->user();

        $data = ResearchLaporanInformasiKhusus::find($id);
        $data->case_id = $request->id_case;
        // $data->satker_id = $request->id_satker;
        $data->surat_perintah_id = $request->id_sprint;
        $data->nomor_surat = $request->nomor_surat;
        $data->tanggal_surat = $request->tanggal_surat;
        $data->perihal_surat = $request->perihal_surat;
        $data->informasi_diperoleh = $request->informasi_diperoleh;
        $data->sumber_informasi = $request->sumber_informasi;
        $data->tren_perkembangan = $request->tren_perkembangan;
        $data->saran_tindak = $request->saran_tindak;
        // $data->jabatan = $request->jabatan;
        // $data->nama_pejabat = $request->nama_pejabat;
        $data->nip = $request->penandatangan;

        

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->penelitian_lapinsus = 1;
            $updateCaseProgresses->status = 'Penelitian';
            $updateCaseProgresses->substatus = 'Penambahan Laporan Informasi Khusus Penelitian dan Selesai';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }

        if ($data->update()) {
            if ($request->hasFile('upload_lapinsus')) {
                DataHelper::insertDocument($data->id, $data->file_laporan_informasi_khusus);
                
            }

            return redirect()->route('open.research.spesific-intel-report.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function updateUpload(Request $request, $id)
    {
        // dd($request, $id);
        $this->validate($request, [
            // 'id_satker' => 'required|string|max:128',
            'id_case' => 'required|string|max:128',
            'upload_lapinsus' => 'nullable|mimes:pdf|max:30000',
        ]);

        $user = auth()->user();

        $data = ResearchLaporanInformasiKhusus::find($id);
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->surat_perintah_id = $request->id_sprint;

        if ($request->hasFile('upload_lapinsus')) {
            if ($data->file_laporan_informasi_khusus && Storage::disk('public')->exists($data->file_laporan_informasi_khusus)) {
                Storage::disk('public')->delete($data->file_laporan_informasi_khusus);
            }

            $ext_upload_lapinsus = $request->file('upload_lapinsus')->extension();
            $upload_lapinsus = $request->file('upload_lapinsus')
                ->storePubliclyAs(
                    'open/research/spesific-intel-report/',
                    Str::slug('file_laporan_informasi_khusus_', '_') . '_' . Str::random() . '.' . $ext_upload_lapinsus,
                    'public'
                );
            $data->file_laporan_informasi_khusus = $upload_lapinsus;
            // DataHelper::insertDocument($data->id, $data->file_laporan_informasi_khusus);
            $document_pdf = Documents::where('relation_id',$id)->first();
            if($document_pdf){
                $document_pdf->doc_path = $upload_lapinsus;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->updated_by = $user->id;
                $document_pdf->update();
            }else{
                $document_pdf = new Documents;
                $document_pdf->doc_path = $upload_lapinsus;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_result_achievement;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();
            }
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {    
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->penelitian_lapinsus = 1;
            $updateCaseProgresses->status = 'Penelitian';
            $updateCaseProgresses->substatus = 'Upload Laporan Informasi Khusus Penelitian dan Selesai';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }

        if ($data->update()) {
            if ($request->hasFile('upload_lapinsus')) {
                DataHelper::insertDocument($data->id, $data->file_laporan_informasi_khusus);
            }

            return redirect()->route('open.research.spesific-intel-report.index')->with(["success" => "Data berhasil diupdate."]);
        }
        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = ResearchLaporanInformasiKhusus::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if ($data->file_laporan_informasi_khusus && Storage::disk('public')->exists($data->file_laporan_informasi_khusus)) {
            Storage::disk('public')->delete($data->file_laporan_informasi_khusus);
        }

        $data->delete();
        // ResearchLaporanInformasiKhusus::where('surat_perintah_id', $id)->delete();
        ResearchSaranTindakLanjut::where('laporan_informasi_khusus_id', $id)->delete();
        ResearchPotensiAght::where('id_lapinsus', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
