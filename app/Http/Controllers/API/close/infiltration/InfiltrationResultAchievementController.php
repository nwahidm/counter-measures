<?php

namespace App\Http\Controllers\API\close\infiltration;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Infiltration\InfiltrationResultAchievement;
use Illuminate\Support\Str;
use App\Models\Documents;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;

class InfiltrationResultAchievementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = InfiltrationResultAchievement::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('infiltration_hasil_yang_dicapai.satker_id', '=', $idSatker);
                                })
                                ->with('case')
                                ->with('satker')
                                ->with('InfiltrationSecretOperation')
                                ->with('InfiltrationTargetDynamics')
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
        
        // $data = InfiltrationResultAchievement::find($id)
        //                         ?->with(['satker', 'case'])
        //                         ->with('case')
        //                         ->with('satker')
        //                         ->with('InfiltrationSecretOperation')
        //                         ->with('InfiltrationTargetDynamics')
        //                         ->first();
        $data = InfiltrationResultAchievement::with([
            'satker',
            'case',
            'InfiltrationSecretOperation',
            'InfiltrationTargetDynamics'
        ])->where('id', $id)->first();

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

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
        $data = InfiltrationResultAchievement::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if($data->surat_perintah_path){
            if (Storage::disk('public')->exists($data->surat_perintah_path)) {
                Storage::disk('public')->delete($data->surat_perintah_path);
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

        $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'infiltration_dinamika_target_id' => 'required|string|max:128',
            'infiltration_operasi_rahasia_id' => 'required|string|max:128',
            'hasil_yang_dicapai' => 'required|string|max:128',
            // 'upload_hasil_yang_dicapai' => 'required|file|mimes:pdf|max:2048',
        ]);

        $user = Auth::guard('api')->user();

        $data = new InfiltrationResultAchievement;
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->infiltration_dinamika_target_id = $request->infiltration_dinamika_target_id;
        $data->infiltration_operasi_rahasia_id = $request->infiltration_operasi_rahasia_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        $document_pdf = new Documents;
        // if ($request->hasFile('upload_hasil_yang_dicapai')) {
        //     $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
        //     $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
        //         ->storePubliclyAs(
        //             'close/infiltration/result-achievement',
        //             Str::slug('hasil', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
        //             'public'
        //         );

        //     $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;

        //     $document_pdf->doc_path = $upload_hasil_yang_dicapai;;
        //     $document_pdf->doc_type = "pdf";
        //     $document_pdf->doc_status = "0";
        //     $document_pdf->doc_status_remark = "Waiting Analysis";
        // }
        if ($request->input('upload_hasil_yang_dicapai')) {
            $base64_pdf = $request->input('upload_hasil_yang_dicapai');
            
            // Extract file extension from Base64 string, assuming it's a data URI format (e.g., data:application/pdf;base64,...)
            // if (preg_match('/^data:application\/(\w+);base64,/', $base64_pdf, $type)) {
            //     $ext_upload_hasil_yang_dicapai = strtolower($type[1]); // Get the file extension (e.g., pdf)
            //     $base64_pdf = substr($base64_pdf, strpos($base64_pdf, ',') + 1); // Remove the mime type from the Base64 string
            // } else {
            //     // Handle error: invalid Base64 string format
            //     return response()->json(['error' => 'Invalid PDF format'], 400);
            // }
        
            // Decode the Base64 string
            $pdf_data = base64_decode($base64_pdf);
            
            // Generate a unique filename
            // $filename = Str::slug('hasil', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai;
            $filename = Str::slug('hasil', '_') . '_' . Str::random() . '.pdf';
            
            // Store the decoded PDF file
            $file_path = 'close/infiltration/result-achievement/' . $filename;
            Storage::disk('public')->put($file_path, $pdf_data);
            
            // Save the file path to the `upload_hasil_yang_dicapai` field in the database
            $data->upload_hasil_yang_dicapai = $file_path;
        
            // Save to the `Documents` model
           
            $document_pdf->doc_path = $file_path;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            
        }

        
        $data->created_by = $user->id;
        $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update->case_id = $data->id;
        $data_case_close_historical_update->action = "Penambahan Infiltration Hasil yang Dicapai";

        $data_case_close_historical_update->created_by = $user->id;
        $data_case_close_historical_update->updated_by = $user->id;

        $data_case_close_historical_update2 = new CaseCloseEventHistoricalUpdates;
        $data_case_close_historical_update2->case_id = $data->id;
        $data_case_close_historical_update2->action = "Penambahan Infiltration Report";

        $data_case_close_historical_update2->created_by = $user->id;
        $data_case_close_historical_update2->updated_by = $user->id;
       
        if ($request->submit_type === 'save') {
            

            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('infiltration_hasil_yang_dicapai', '0')
            ->update([
                'infiltration_hasil_yang_dicapai' => "1",
                'infiltration_laporan' => "1",
                'status' => "Penyusupan",
                'substatus' => "Penambahan Laporan",
                'percentage' => round((21/29)*100, 2)
            ]);;

        }else{
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('infiltration_hasil_yang_dicapai', '0')
            ->update([
                'infiltration_hasil_yang_dicapai' => "1",
                'infiltration_laporan' => "1",
                'status' => "Penyusupan",
                'substatus' => "Penambahan Laporan",
                'percentage' => round((29/29)*100, 2)
            ]);;


        }

        if ($data->save()) {
            $data_case_close_historical_update->case_id = $request->id_case;
            $data_case_close_historical_update2->case_id = $request->id_case;
            
            $data_case_close_historical_update->save();
            $data_case_close_historical_update2->save();

            if ($request->input('upload_hasil_yang_dicapai')) {
                $document_pdf->relation_id = $data->id;
                $document_pdf->save();
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
            "message" => 'Data Gagal Disimpan',
            "data" => null,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public function update(Request $request)
    {

         $this->validate($request, [
            'id_case' => 'required|string|max:128',
            'id_satker' => 'required|string|max:128',
            'infiltration_dinamika_target_id' => 'required|string|max:128',
            'infiltration_operasi_rahasia_id' => 'required|string|max:128',
            'hasil_yang_dicapai' => 'required|string|max:128',
            // 'upload_hasil_yang_dicapai' => 'required|file|mimes:pdf|max:2048',
        ]);

        $user = Auth::guard('api')->user();

        $data = InfiltrationResultAchievement::find($request->id);
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->infiltration_dinamika_target_id = $request->infiltration_dinamika_target_id;
        $data->infiltration_operasi_rahasia_id = $request->infiltration_operasi_rahasia_id;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        $document_pdf = new Documents;
        // if ($request->hasFile('upload_hasil_yang_dicapai')) {
        //     $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
        //     $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
        //         ->storePubliclyAs(
        //             'close/infiltration/result-achievement',
        //             Str::slug('hasil', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
        //             'public'
        //         );

        //     $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;

        //     $document_pdf->doc_path = $data->upload_hasil_yang_dicapai ;
        //     $document_pdf->doc_type = "pdf";
        //     $document_pdf->doc_status = "0";
        //     $document_pdf->doc_status_remark = "Waiting Analysis";
        //     $document_pdf->relation_id = $data->id;
        //     $document_pdf->save();
        // }

        if ($request->input('upload_hasil_yang_dicapai')) {
            $base64_pdf = $request->input('upload_hasil_yang_dicapai');
            
            // Extract file extension from Base64 string, assuming it's in a data URI format (e.g., data:application/pdf;base64,...)
            // if (preg_match('/^data:application\/(\w+);base64,/', $base64_pdf, $type)) {
            //     $ext_upload_hasil_yang_dicapai = strtolower($type[1]); // Get the file extension (e.g., pdf)
            //     $base64_pdf = substr($base64_pdf, strpos($base64_pdf, ',') + 1); // Remove the mime type from the Base64 string
            // } else {
            //     // Handle error: invalid Base64 string format
            //     return response()->json(['error' => 'Invalid PDF format'], 400);
            // }
        
            // Decode the Base64 string
            $pdf_data = base64_decode($base64_pdf);
            
            // Generate a unique filename
            // $filename = Str::slug('hasil', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai;
            $filename = Str::slug('hasil', '_') . '_' . Str::random() . '.pdf';
            
            // Store the decoded PDF file
            $file_path = 'close/infiltration/result-achievement/' . $filename;
            Storage::disk('public')->put($file_path, $pdf_data);
            
            // Save the file path to the `upload_hasil_yang_dicapai` field in the database
            $data->upload_hasil_yang_dicapai = $file_path;
        
            // Save the document details in the `Documents` model
            $document_pdf = new Documents;
            $document_pdf->doc_path = $data->upload_hasil_yang_dicapai;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->relation_id = $data->id;
            $document_pdf->save();
        }
        

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
       
            $close_case_progress = CaseCloseProgresses::where('case_id',  $request->id_case)
            ->where('infiltration_hasil_yang_dicapai', '0')
            ->update([
                'infiltration_hasil_yang_dicapai' => "1",
                'infiltration_laporan' => "1",
                'status' => "Penyusupan",
                'substatus' => "Penambahan Laporan",
                'percentage' => round((29/29)*100, 2)
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
