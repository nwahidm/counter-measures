<?php

namespace App\Http\Controllers\API\close\exploration;

use App\Models\Documents;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ExplorationResultAchievment;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;

class ExplorationResultAchievementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = ExplorationResultAchievment::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('exploration_target_identitas.satker_id', '=', $idSatker);
                                })
                                ->with('case')
                                ->with('case.satker')
                                ->with('explorationRencanaAksi')
                                ->with('explorationTargetIdentitas')
                                ->latest()
                                ->paginate(10);

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function show(Request $request, $id)
    {
        $data = ExplorationResultAchievment::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Load the related models
        $data->load(['satker', 'case', 'explorationRencanaAksi', 'explorationTargetIdentitas']);

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function destroy($id, Request $request)
    {
        $data = ExplorationResultAchievment::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if($data->upload_hasil_yang_dicapai){
            if (Storage::disk('public')->exists($data->upload_hasil_yang_dicapai)) {
                Storage::disk('public')->delete($data->upload_hasil_yang_dicapai);
            }
        }

        $data->delete();

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil dihapus',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'id_satker' => 'required',
            'case_id' => 'required|string|max:255',
            'exploration_rencana_aksi_id' => 'required',
            'exploration_target_identity_id' => 'required',
            'hasil_yang_dicapai' => 'required|string|max:255',
            // 'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();
        
        $data = new ExplorationResultAchievment;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->exploration_rencana_aksi_id = $request->exploration_rencana_aksi_id;
        $data->exploration_target_identity_id = $request->exploration_target_identity_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        $document_pdf = new Documents;
        if ($request->upload_hasil_yang_dicapai) {
            $base64Document = $request->upload_hasil_yang_dicapai;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('exploration-hasil-dicapai', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/exploration/hasildicapai/upload/' . $fileName;

            // Simpan dokumen
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->upload_hasil_yang_dicapai = $uploadPath;
            $document_pdf->doc_path = $uploadPath;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
        }

        // if ($request->hasFile('upload_hasil_yang_dicapai')) {
        //     $ext_upload_info = $request->file('upload_hasil_yang_dicapai')->extension();
        //     $upload_info = $request->file('upload_hasil_yang_dicapai')
        //         ->storePubliclyAs(
        //             'close/exploration/hasildicapai/upload',
        //             Str::slug('exploration-hasil-dicapai', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->upload_hasil_yang_dicapai = $upload_info;
        //     $document_pdf->doc_path = $upload_info;
        //     $document_pdf->doc_type = "pdf";
        //     $document_pdf->doc_status = "0";
        //     $document_pdf->doc_status_remark = "Waiting Analysis";
        // }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_hasil_yang_dicapai' => "1",
                'exploration_laporan' => "1",
                'status' => "Hasil Dicapai",
                'substatus' => "Penambahan Hasil Dicapai",
                'percentage' => round((10/29)*100,2)
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_hasil_yang_dicapai' => "1",
                'exploration_laporan' => "1",
                'status' => "Hasil Dicapai",
                'substatus' => "Penambahan Hasil Dicapai",
                'percentage' => round((29/29)*100,2)
            ]);

        }


        if ($data->save()) {
            $data1 = ExplorationResultAchievment::where('satker_id', $user->satker->id_satker)
            ->orderby('created_at','DESC')->first();

            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $request->case_id;
            $data_case_close_historical_update->action = "Penambahan Hasil Dicapai";
    
            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();
    

            $document_pdf->relation_id = $data1->id_exploration_result_achievement;
            
            $document_pdf->save();

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
            "message" => 'Data Gagal Disimpan',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            // 'id_satker' => 'required',
            'exploration_rencana_aksi_id' => 'required',
            'exploration_target_identity_id' => 'required',
            'case_id' => 'required|string|max:255',
            'hasil_yang_dicapai' => 'required|string|max:255',
            // 'upload_hasil_yang_dicapai' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = ExplorationResultAchievment::find($id);
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->exploration_rencana_aksi_id = $request->exploration_rencana_aksi_id;
        $data->exploration_target_identity_id = $request->exploration_target_identity_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_sprint = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_sprint = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'close/exploration/hasildicapai/upload',
                    Str::slug('exploration-hasil-dicapai', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );
                $data->upload_hasil_yang_dicapai = $upload_sprint;

                $document_pdf = Documents::where('relation_id',$id)->first();
                $document_pdf->doc_path = $upload_sprint;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->save();
        } 

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_hasil_yang_dicapai' => "1",
                'exploration_laporan' => "1",
                'status' => "Hasil Dicapai",
                'substatus' => "Penambahan Hasil Dicapai",
                'percentage' => round((29/29)*100,2)
            ]);;

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
        ]);
    }
}
