<?php

namespace App\Http\Controllers\API\Open\Research;

use App\Http\Controllers\Controller;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\Open\Research\ResearchSaranTindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Open\Research\ResearchPotensiAght;

class ResearchSaranTindakLanjutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
   

        $user = Auth::guard('api')->user();
        $datas = ResearchSaranTindakLanjut::with([
            "case",
            "case.satker",
            "case.caseProgress", 
            "case.caseEventHistoricalUpdates",
            "researchSuratPerintah",
            "researchLaporanInformasiKhusus"])
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('open_case.id_satker', '=', $user->id_satker)
                              ->orWhere('master_satker.parent_id', '=', $user->id_satker);
                        }) ->orderby('research_saran_dan_tindak_lanjut.created_at','DESC')->paginate(10);
        
        
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
            // 'id_sprint' => 'required|string|max:128',
            // 'id_lapinsus' => 'required|string|max:128',
            'saran_dan_tindak_lanjut_date' => 'required|date',
            'saran_dan_tindak_lanjut' => 'required|string|max:1000000',
        ]);
        $user = Auth::guard('api')->user();

        $data = new ResearchSaranTindakLanjut;
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->laporan_informasi_khusus_id = $request->laporan_informasi_khusus_id;
        $data->saran_dan_tindak_lanjut_date = $request->saran_dan_tindak_lanjut_date;
        $data->saran_dan_tindak_lanjut = $request->saran_dan_tindak_lanjut;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $request->case_id)->first();
                $op->penelitian_saran_dan_tindak_lanjut = 1;
                $op->status = $op->percentage > 29.4 ? $op->status : "Penelitian";
                $op->substatus = $op->percentage > 29.4 ? $op->substatus : "Input Saran dan Tindak Lanjut Penelitian";
                $op->percentage = $op->percentage > 29.4 ? $op->percentage : 29.4;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->case_id;
                $cp->action = 'Penambahan Penelitian Saran dan Tindak Lanjut';
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
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
                $updateCaseProgresses->penelitian_saran_dan_tindak_lanjut = 1;
                $updateCaseProgresses->status = 'Penelitian';
                $updateCaseProgresses->substatus = 'Penambahan Saran, Tindak Lanjut Penelitian dan Selesai';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->case_id;
                $cp->action = 'Penambahan Penelitian Saran dan Tindak Lanjut';
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
        $user = Auth::guard('api')->user();
        $data = ResearchSaranTindakLanjut::with([
            "case",
            "case.satker",
            "case.caseProgress", 
            "case.caseEventHistoricalUpdates",
            "researchSuratPerintah",
            "researchLaporanInformasiKhusus"])->where('id_saran_dan_tindak_lanjut', $id)
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
        $this->validate($request, [
            'satker_id' => 'required|string|max:128',
            'case_id' => 'required|string|max:128',
            // 'id_sprint' => 'required|string|max:128',
            // 'id_lapinsus' => 'required|string|max:128',
            'saran_dan_tindak_lanjut_date' => 'required|date',
            'saran_dan_tindak_lanjut' => 'required|string|max:1000000',
        ]);
        $user = Auth::guard('api')->user();

        $data = ResearchSaranTindakLanjut::find($id);
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->laporan_informasi_khusus_id = $request->laporan_informasi_khusus_id;
        $data->saran_dan_tindak_lanjut_date = $request->saran_dan_tindak_lanjut_date;
        $data->saran_dan_tindak_lanjut = $request->saran_dan_tindak_lanjut;

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        $data->updated_by = $request->user_id;

        if ($request->submit_type === 'update_and_finish') {       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->case_id)->first();
            $updateCaseProgresses->penelitian_saran_dan_tindak_lanjut = 1;
            $updateCaseProgresses->status = 'Penelitian';
            $updateCaseProgresses->substatus = 'Penambahan Saran, Tindak Lanjut Penelitian dan Selesai';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }
        if ($data->update()) {
            $cp = CaseEventHistoricalUpdates::where('case_id', $data->researchLaporanInformasiKhusus->researchSuratPerintah->case_id)->first();
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
        $data = ResearchSaranTindakLanjut::find($id);

        if ($data) {
            $cp = CaseEventHistoricalUpdates::where('case_id', $data->researchLaporanInformasiKhusus->researchSuratPerintah->case_id)->first();
            $cp->action = "Penghapusan Penelitian Saran dan Tindak Lanjut";
            $cp->updated_by = $data->created_by;
            $cp->update();

            $data->delete();
            ResearchPotensiAght::where('id_saran_tl', $id)->delete();

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
