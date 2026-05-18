<?php

namespace App\Http\Controllers\API\close\exploration;

use App\Models\Documents;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ExplorationRencanaAksi;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;

class ExplorationActionPlanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = ExplorationRencanaAksi::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('exploration_rencana_aksi.satker_id', '=', $idSatker);
                                })
                                ->with('case', 'satker')
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
        $data = ExplorationRencanaAksi::where('id_exploration_rencana_aksi', $id)->with('case', 'case.satker')->first();

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
        $data->load(['satker', 'case']);

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
        $data = ExplorationRencanaAksi::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if($data->rencana_aksi_upload){
            if (Storage::disk('public')->exists($data->rencana_aksi_upload)) {
                Storage::disk('public')->delete($data->rencana_aksi_upload);
            }
        }

        // $data->delete();

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
        $this->validate($request, [
            'id_satker' => 'required',
            'case_id' => 'required|string|max:255',
            'rencana_aksi_data' => 'required|string|max:255',
            // 'rencana_aksi_detail' => 'required',
            // 'rencana_aksi_upload' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = new ExplorationRencanaAksi;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->rencana_aksi_data = $request->rencana_aksi_data;
        $data->rencana_aksi_detail = $request->rencana_aksi_detail;

        $document_pdf = new Documents;
        if ($request->rencana_aksi_upload) {
            $base64Document = $request->rencana_aksi_upload;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('exploration-rencana-aksi', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'close/exploration/rencanaaksi/upload/' . $fileName;

            // Simpan dokumen
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->rencana_aksi_upload = $uploadPath;
            $document_pdf->doc_path = $uploadPath;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
        }

        // if ($request->hasFile('rencana_aksi_upload')) {
        //     $ext_upload_info = $request->file('rencana_aksi_upload')->extension();
        //     $upload_info = $request->file('rencana_aksi_upload')
        //         ->storePubliclyAs(
        //             'close/exploration/rencanaaksi/upload',
        //             Str::slug('exploration-rencana-aksi', '_') . '_' . Str::random() . '.' . $ext_upload_info,
        //             'public'
        //         );

        //     $data->rencana_aksi_upload = $upload_info;
        //     $document_pdf->doc_path = $upload_info;
        //     $document_pdf->doc_type = "pdf";
        //     $document_pdf->doc_status = "0";
        //     $document_pdf->doc_status_remark = "Waiting Analysis";
        // }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_rencana_aksi' => "1",
                'status' => "Rencana Aksi",
                'substatus' => "Penambahan Rencana Aksi",
                'percentage' => round((10/29)*100,2)
                // 'percentage' => 25
            ]);
        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->update([
                'exploration_rencana_aksi' => "1",
                'status' => "Rencana Aksi",
                'substatus' => "Penambahan Rencana Aksi",
                'percentage' => round((29/29)*100,2)
                // 'percentage' => 25
            ]);

        }
        
        if ($data->save()) {
            $data1 = ExplorationRencanaAksi::where('satker_id', $user->satker->id_satker)
            ->orderby('created_at','DESC')->first();

            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $request->case_id;
            $data_case_close_historical_update->action = "Penambahan Rencana Aksi";
    
            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();
    

            $document_pdf->relation_id = $data1->id_exploration_rencana_aksi;
            
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
            'case_id' => 'required|string|max:255',
            'rencana_aksi_data' => 'required|string|max:255',
            // 'rencana_aksi_detail' => 'required',
            'rencana_aksi_upload' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = Auth::guard('api')->user();

        $data = ExplorationRencanaAksi::find($id);
        // $data->satker_id = $request->id_satker;
        $data->case_id = $request->case_id;
        $data->rencana_aksi_data = $request->rencana_aksi_data;
        $data->rencana_aksi_detail = $request->rencana_aksi_detail;

        if ($request->hasFile('rencana_aksi_upload')) {
            $ext_upload_sprint = $request->file('rencana_aksi_upload')->extension();
            $upload_sprint = $request->file('rencana_aksi_upload')
                ->storePubliclyAs(
                    'close/exploration/rencanaaksi/upload',
                    Str::slug('exploration-rencana-aksi', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );
            $data->rencana_aksi_upload = $upload_sprint;

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
                'exploration_rencana_aksi' => "1",
                'status' => "Rencana Aksi",
                'substatus' => "Penambahan Rencana Aksi",
                'percentage' => round((29/29)*100,2)
                // 'percentage' => 25
            ]);
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
