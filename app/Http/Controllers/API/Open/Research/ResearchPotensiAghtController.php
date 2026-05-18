<?php

namespace App\Http\Controllers\API\Open\Research;

use App\Http\Controllers\Controller;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\Open\Research\ResearchPotensiAght;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ResearchPotensiAghtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $user = Auth::guard('api')->user();
        $datas = ResearchPotensiAght::with([
            "case",
            "case.satker",
            "case.caseProgress", 
            "case.caseEventHistoricalUpdates",
            "researchSuratPerintah",
            "researchLaporanInformasiKhusus",
            "researchSaranTindakLanjut"])
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('open_case.id_satker', '=', $user->id_satker)
                              ->orWhere('master_satker.parent_id', '=', $user->id_satker);
                        }) ->orderby('research_potensi_aght.created_at','DESC')->paginate(10);
        
        
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
        $this->validate($request, [
            'case_id' => 'required|string|max:128',
            // 'id_sprint' => 'nullable|string|max:128',
            // 'id_lapinsus' => 'nullable|string|max:128',
            // 'id_saran_tl' => 'nullable|string|max:128',
            'ancaman' => 'required|string|max:1000000',
            'gangguan' => 'required|string|max:1000000',
            'hambatan' => 'required|string|max:1000000',
            'tantangan' => 'required|string|max:1000000',
        ]);

        $user = Auth::guard('api')->user();

        $data = new ResearchPotensiAght;
        $data->id_satker = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->id_sprint = $request->surat_perintah_id;
        $data->id_lapinsus = $request->laporan_informasi_khusus_id;
        $data->id_saran_tl = $request->saran_tinjut_id;
        $data->waktu = $request->waktu;
        $data->tempat = $request->tempat;

        $data->ancaman = $request->ancaman;
        $data->gangguan = $request->gangguan;
        $data->hambatan = $request->hambatan;
        $data->tantangan = $request->tantangan;


        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $data->researchSaranTindakLanjut->researchLaporanInformasiKhusus->researchSuratPerintah->case->id)->first();
                $op->penelitian_aght = 1;
                $op->status = $op->percentage > 35.28 ? $op->status : "Penelitian";
                $op->substatus = $op->percentage > 35.28 ? $op->substatus : "Input AGHT Penelitian";
                $op->percentage = $op->percentage > 35.28 ? $op->percentage : 35.28;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->researchSaranTindakLanjut->researchLaporanInformasiKhusus->researchSuratPerintah->case->id;
                $cp->action = 'Penambahan Penelitian Ancaman, Gangguan, Hambatan, dan Tantangan';
                $cp->created_by = $user->id;
                $cp->save();
    
                // Laporan
                $op = CaseProgresses::where('case_id', $data->researchSaranTindakLanjut->researchLaporanInformasiKhusus->researchSuratPerintah->case_id)->first();
                $op->penelitian_laporan = 1;
                $op->status = $op->percentage > 41.16 ? $op->status : "Penelitian";
                $op->substatus = $op->percentage > 41.16 ? $op->substatus : "Penelitian Laporan";
                $op->percentage = $op->percentage > 41.16 ? $op->percentage : 41.16;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->researchSaranTindakLanjut->researchLaporanInformasiKhusus->researchSuratPerintah->case->id;
                $cp->action = 'Penambahan Penelitian Laporan';
                $cp->created_by = $user->id;
                $cp->save();
    
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
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->penelitian_aght = 1;
                $updateCaseProgresses->status = 'Penelitian';
                $updateCaseProgresses->substatus = 'Penambahan AGHT Penelitian dan Selesai';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->researchSaranTindakLanjut->researchLaporanInformasiKhusus->researchSuratPerintah->case->id;
                $cp->action = 'Penambahan Penelitian Laporan';
                $cp->created_by = $user->id;
                $cp->save();

                
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
        $datas = ResearchPotensiAght::with([
            "case",
            "case.satker",
            "case.caseProgress", 
            "case.caseEventHistoricalUpdates",
            "researchSuratPerintah",
            "researchLaporanInformasiKhusus",
            "researchSaranTindakLanjut"])->where('id', $id)
            ->first();
        
        
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $datas,
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
            'case_id' => 'required|string|max:128',
            // 'id_sprint' => 'nullable|string|max:128',
            // 'id_lapinsus' => 'nullable|string|max:128',
            // 'id_saran_tl' => 'nullable|string|max:128',
            'ancaman' => 'required|string|max:1000000',
            'gangguan' => 'required|string|max:1000000',
            'hambatan' => 'required|string|max:1000000',
            'tantangan' => 'required|string|max:1000000',
        ]);
        $user = Auth::guard('api')->user();
        
        $data = ResearchPotensiAght::find($id);
        $data->id_satker = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->id_sprint = $request->surat_perintah_id;
        $data->id_lapinsus = $request->laporan_informasi_khusus_id;
        $data->id_saran_tl = $request->saran_tinjut_id;
        $data->waktu = $request->waktu;
        $data->tempat = $request->tempat;

        $data->ancaman = $request->ancaman;
        $data->gangguan = $request->gangguan;
        $data->hambatan = $request->hambatan;
        $data->tantangan = $request->tantangan;


        $data->created_by = $user->id;
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
            $cp = CaseEventHistoricalUpdates::where('case_id', $data->researchSaranTindakLanjut->researchLaporanInformasiKhusus->researchSuratPerintah->case_id)->first();
            $cp->action = "Perubahan Penelitian Saran dan Tindak Lanjut";
            $cp->updated_by = $request->user_id;
            $cp->update();

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
        $data = ResearchPotensiAght::find($id);

        if ($data) {
            $cp = CaseEventHistoricalUpdates::where('case_id', $data->researchSaranTindakLanjut->researchLaporanInformasiKhusus->researchSuratPerintah->case_id)->first();
            $cp->action = "Penghapusan Penelitian Saran dan Tindak Lanjut";
            $cp->updated_by = $data->created_by;
            $cp->update();

            $data->delete();

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
}
