<?php

namespace App\Http\Controllers\API\Open\Research;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\Documents;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Open\Research\ResearchSuratPerintah;
use App\Models\Open\Research\ResearchSaranTindakLanjut;
use App\Models\Open\Research\ResearchPotensiAght;

class ResearchLaporanInformasiKhususController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $user = Auth::guard('api')->user();
        $datas = ResearchLaporanInformasiKhusus::with([
            "case",
            "case.satker",
            "case.caseProgress", 
            "case.caseEventHistoricalUpdates",
            "researchSuratPerintah"])
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('open_case.id_satker', '=', $user->id_satker)
                              ->orWhere('master_satker.parent_id', '=', $user->id_satker);
                        }) ->orderby('research_laporan_informasi_khusus.created_at','DESC')->paginate(10);
        
        
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $datas,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'satker_id' => 'required|string|max:128',
            'case_id' => 'required|string|max:128',
            'nomor_surat' => 'required|string|max:128',
            'jabatan' => 'required|string|max:128',
            'nama_pejabat' => 'required|string|max:128',
            'nip' => 'required|string|max:128'
        ]);

        $user = Auth::guard('api')->user();

        $data = new ResearchLaporanInformasiKhusus();
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->nomor_surat = $request->nomor_surat;
        $data->tanggal_surat = $request->tanggal_surat;
        $data->perihal_surat = $request->perihal_surat;
        $data->informasi_diperoleh = $request->informasi_diperoleh;
        $data->sumber_informasi = $request->sumber_informasi;
        $data->tren_perkembangan = $request->tren_perkembangan;
        $data->saran_tindak = $request->saran_tindak;
        $data->jabatan = $request->jabatan;
        $data->nama_pejabat = $request->nama_pejabat;
        $data->nip = $request->nip;

       
        $data->created_by = $request->user_id;
        $data->updated_by = $request->user_id;

        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $request->case_id)->first();
                $op->penelitian_lapinsus = 1;
                $op->status = $op->percentage > 11.76 ? $op->status : "Penelitian";
                $op->substatus = $op->percentage > 11.76 ? $op->substatus : "Input Laporan Informasi Khusus Penelitian";
                $op->percentage = $op->percentage > 11.76 ? $op->percentage : 11.76;
                $op->updated_by = $user->id;
                $op->update();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->case_id;
                $cp->action = 'Penambahan Penelitian Laporan Informasi Khusus';
                $cp->created_by = $user->id;
                $cp->update();
    
                if ($request->hasFile('upload_lapinsus')) {
                    DataHelper::insertDocument($data->id, $data->file_laporan_informasi_khusus);
                }
    
                return response()->json([
                    "status" => Response::HTTP_OK,
                    "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                    "message" => 'Data berhasil disimpan',
                    "data" => $data,
                    'timestamp' => floor(microtime(true) * 1000)
                ]);
            }    
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data gagal disimpan',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_UNPROCESSABLE_ENTITY); 

        }else{
            if ($data->save()) {
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
                $updateCaseProgresses->penelitian_lapinsus = 1;
                $updateCaseProgresses->status = 'Penelitian';
                $updateCaseProgresses->substatus = 'Penambahan Laporan Informasi Khusus Penelitian dan Selesai';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->case_id;
                $cp->action = 'Penambahan Penelitian Laporan Informasi Khusus';
                $cp->created_by = $user->id;
                $cp->update();

                if ($request->hasFile('upload_lapinsus')) {
                    DataHelper::insertDocument($data->id, $data->file_laporan_informasi_khusus);
                }

                return response()->json([
                            "status" => Response::HTTP_OK,
                            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                            "message" => 'Data berhasil disimpan',
                            "data" => $data,
                            'timestamp' => floor(microtime(true) * 1000)
                        ]);
            }
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data gagal disimpan',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_UNPROCESSABLE_ENTITY);           
        }        

        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        
        $user = Auth::guard('api')->user();
        $data = ResearchLaporanInformasiKhusus::with([
             "case",
                "case.satker",
                "case.caseProgress", 
                "case.caseEventHistoricalUpdates",
                "researchSuratPerintah"])->where('id', $id)
                ->first();
            

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $this->validate($request, [
            'satker_id' => 'required|string|max:128',
            'case_id' => 'required|string|max:128',
            'nomor_surat' => 'required|string|max:128',
            'jabatan' => 'required|string|max:128',
            'nama_pejabat' => 'required|string|max:128',
            'nip' => 'required|string|max:128'
        ]);
        $user = Auth::guard('api')->user();

        $data = ResearchLaporanInformasiKhusus::find($id);
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->nomor_surat = $request->nomor_surat;
        $data->tanggal_surat = $request->tanggal_surat;
        $data->perihal_surat = $request->perihal_surat;
        $data->informasi_diperoleh = $request->informasi_diperoleh;
        $data->sumber_informasi = $request->sumber_informasi;
        $data->tren_perkembangan = $request->tren_perkembangan;
        $data->saran_tindak = $request->saran_tindak;
        $data->jabatan = $request->jabatan;
        $data->nama_pejabat = $request->nama_pejabat;
        $data->nip = $request->nip;

       
        $data->created_by = $request->user_id;
        $data->updated_by = $request->user_id;

        
        if ($request->submit_type === 'update_and_finish') {       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->penelitian_lapinsus = 1;
            $updateCaseProgresses->status = 'Penelitian';
            $updateCaseProgresses->substatus = 'Penambahan Laporan Informasi Khusus Penelitian dan Selesai';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }
  
        
        if ($data->update()) {
           
            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Data berhasil disimpan',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        

        return response()->json([
            "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            "message" => 'Data gagal disimpan',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = ResearchLaporanInformasiKhusus::find($id);

        if ($data) {
            if ($data->file_laporan_informasi_khusus && Storage::disk('public')->exists($data->file_laporan_informasi_khusus)) {
                Storage::disk('public')->delete($data->file_laporan_informasi_khusus);

                Documents::where('relation_id', $data->id)
                    ->where('doc_path', $data->file_laporan_informasi_khusus)
                    ->delete();

                $data->file_laporan_informasi_khusus = null;
                $data->update();
            }

            $cp = CaseEventHistoricalUpdates::where('case_id', $data->researchSuratPerintah->case_id)->first();
            $cp->action = "Penghapusan Penelitian Laporan Informasi Khusus";
            $cp->updated_by = $data->created_by;
            $cp->update();

            $data->delete();
            ResearchSaranTindakLanjut::where('laporan_informasi_khusus_id', $id)->delete();
            ResearchPotensiAght::where('id_lapinsus', $id)->delete();


            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Data berhasil dihapus',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        return response()->json([
            "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            "message" => 'Data gagal dihapus',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
