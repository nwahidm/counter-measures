<?php

namespace App\Http\Controllers\API\Open\Interrogation;

use App\Models\Documents;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CaseProgresses;
use App\Models\InterogationRecord;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\InterogationTargetIdentification;
use App\Models\InterogationResultAchievement;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CaseCloseEventHistoricalUpdates;

class InterrogationTargetIdentificationController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = InterogationTargetIdentification::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('interrogation_identifikasi_target.satker_id', '=', $idSatker);
                                })
                                ->with('case')
                                ->with('interogrecord')
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
        $data = InterogationTargetIdentification::find($id);

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
        $data->load(['satker', 'case', 'interogrecord']);

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
        $data = InterogationTargetIdentification::find($id);

        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        $data->delete();
        InterogationResultAchievement::where('interogation_target_identification_id', $id)->delete();


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
            // 'id_interogation_record' => 'required',
            'id_satker' => 'required',
            'id_case' => 'required',
            'hasil_target_identification' => 'required',
            // 'upload_berita_acara' => 'required|mimes:pdf|max:30000'
        ]);
        $user = Auth::guard('api')->user();

        $data = new InterogationTargetIdentification();
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->interogation_record_id = $request->id_interogation_record;
        $data->hasil_target_identification = $request->hasil_target_identification;

        // if ($request->hasFile('upload_berita_acara')) {
        //     $ext_upload_berita_acara = $request->file('upload_berita_acara')->extension();
        //     $upload_berita_acara = $request->file('upload_berita_acara')
        //         ->storePubliclyAs(
        //             'open/data/interogation',
        //             Str::slug('interogationtargetid', '_') . '_' . Str::random() . '.' . $ext_upload_berita_acara,
        //             'public'
        //         );
        //     $data->hasil_target_identification_path = $upload_berita_acara;
        // }

        if ($request->upload_berita_acara) {
            $base64Document = $request->upload_berita_acara;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('interogationtargetid', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'open/data/interogation/' . $fileName;

            // Simpan dokumen
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->hasil_target_identification_path = $uploadPath;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;

        if ($request->submit_type === 'save') {
            
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->interogasi_identifikasi_target = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Indentifikasi Target';
            $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 64.68 ? $updateCaseProgresses->percentage : 64.68;
            $updateCaseProgresses->save();

            if ($data->save()) {
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Interogasi Indentifikasi Target';
                $cp->created_by = $user->id;
                $cp->save();
                
                $document_pdf = new Documents;
                $document_pdf->doc_path = $data->hasil_target_identification_path;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_target_identification;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
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
        }else{

            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->interogasi_identifikasi_target = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Indentifikasi Target';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();

            

            if ($data->save()) {
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Interogasi Indentifikasi Target';
                $cp->created_by = $user->id;
                $cp->save();
                
                $document_pdf = new Documents;
                $document_pdf->doc_path = $data->hasil_target_identification_path;;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_target_identification;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
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

    }

    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            // 'id_interogation_record' => 'required',
            'id_satker' => 'required',
            'id_case' => 'required',
            'hasil_target_identification' => 'required',
            'upload_berita_acara' => 'nullable|mimes:pdf|max:30000'
        ]);
        
        $user = Auth::guard('api')->user();

        $data = InterogationTargetIdentification::find($id);

        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->interogation_record_id = $request->id_interogation_record;
        $data->hasil_target_identification = $request->hasil_target_identification;

        // DOKUMEN
        if ($request->hasFile('upload_berita_acara')) {
            $ext_upload_berita_acara = $request->file('upload_berita_acara')->extension();
            $upload_berita_acara = $request->file('upload_berita_acara')
                ->storePubliclyAs(
                    'open/data/interogation/',
                    Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_upload_berita_acara,
                    'public'
                );

            if ($data->hasil_target_identification_path && Storage::disk('public')->exists($data->hasil_target_identification_path)) {
                Storage::disk('public')->delete($data->hasil_target_identification_path);
            }
            $data->hasil_target_identification_path = $upload_berita_acara;

            $document_pdf = Documents::where('relation_id',$id)->first();
            $document_pdf->doc_path = $upload_berita_acara;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->updated_by = $user->id;
        } 
    
        $data->updated_by = $user->id;
        if ($request->submit_type === 'update_and_finish') {
       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->interogasi_identifikasi_target = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Indentifikasi Target';
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
        ]);
    }
}
