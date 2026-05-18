<?php

namespace App\Http\Controllers\API\Open\Interrogation;

use App\Models\Documents;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CaseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\InterogationResultAchievement;
use Symfony\Component\HttpFoundation\Response;

class InterrogationResultAchievementController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = InterogationResultAchievement::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('interogation_result_achievement.satker_id', '=', $idSatker);
                                })
                                ->with('case')
                                ->with('interogrecord')
                                ->with('interoggtarget')
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
        $data = InterogationResultAchievement::find($id);

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
        $data->load(['satker', 'case', 'interogrecord', 'interoggtarget']);

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
        $data = InterogationResultAchievement::find($id);

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
            // 'id_interogation_target_identification' => 'required',
            // 'id_interogation_record' => 'required',
            'id_satker' => 'required',
            'id_case' => 'required',
            'hasil_yang_dicapai' => 'required',
            // 'upload_hasil_yang_dicapai' => 'required|mimes:pdf|max:30000'
        ]);

        $user = Auth::guard('api')->user();

        $data = new InterogationResultAchievement();
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->interogation_record_id = $request->id_interogation_record;
        $data->interogation_target_identification_id = $request->id_interogation_target_identification;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        if ($request->upload_hasil_yang_dicapai) {
            $base64Document = $request->upload_hasil_yang_dicapai;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('interogationtargetid', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'open/data/interogation/' . $fileName;

            // Simpan dokumen
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->upload_hasil_yang_dicapai = $uploadPath;
        }

        // if ($request->hasFile('upload_hasil_yang_dicapai')) {
        //     $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
        //     $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
        //         ->storePubliclyAs(
        //             'open/data/interogation',
        //             Str::slug('interogationtargetid', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
        //             'public'
        //         );

        //     $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;
        // }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        
        // dd($request->submit_type);
        if ($request->submit_type === 'save') {

            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->interogasi_hasil_yang_dicapai = 1;
            $updateCaseProgresses->interogasi_laporan = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Hasil Yang Dicapai';
            $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 76.44 ? $updateCaseProgresses->percentage : 76.44;
            $updateCaseProgresses->save();

            if ($data->save()) {

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Interogasi Hasil Yang Dicapai';
                $cp->created_by = $user->id;
                $cp->save();

                $document_pdf = new Documents;
                $document_pdf->doc_path = $data->upload_hasil_yang_dicapai;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_result_achievement;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();

                // $log = DataHelper::logUpdateCase($data->case_id, 'Penambahan Interogasi Hasil Yang Dicapi');

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
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Hasil Capaian';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();

            

            if ($data->save()) {
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Interogasi Hasil Capaian';
                $cp->created_by = $user->id;
                $cp->save();
                
                $document_pdf = new Documents;
                $document_pdf->doc_path = $data->upload_hasil_yang_dicapai;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_result_achievement;
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
            // 'id_interogation_target_identification' => 'required',
            // 'id_interogation_record' => 'required',
            'id_satker' => 'required',
            'id_case' => 'required',
            'hasil_yang_dicapai' => 'required',
            'upload_hasil_yang_dicapai' => 'nullable|mimes:pdf|max:30000'
        ]);
        
        $user = Auth::guard('api')->user();

        $data = InterogationResultAchievement::find($id);

        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->interogation_record_id = $request->id_interogation_record;
        $data->interogation_target_identification_id = $request->id_interogation_target_identification;
        $data->hasil_yang_dicapai = $request->hasil_yang_dicapai;

        // DOKUMEN
        if ($request->hasFile('upload_hasil_yang_dicapai')) {
            $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
                ->storePubliclyAs(
                    'open/data/interogation/',
                    Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
                    'public'
                );

            if ($data->upload_hasil_yang_dicapai && Storage::disk('public')->exists($data->upload_hasil_yang_dicapai)) {
                Storage::disk('public')->delete($data->upload_hasil_yang_dicapai);
            }
            $data->upload_hasil_yang_dicapai = $upload_hasil_yang_dicapai;

            $document_pdf = Documents::where('relation_id',$id)->first();
            $document_pdf->doc_path = $upload_hasil_yang_dicapai;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->updated_by = $user->id;
            $document_pdf->save();
        } 

        $data->updated_by = $user->id;
        // dd($request->submit_type);
        if ($request->submit_type === 'update_and_finish') {
       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->interogasi_identifikasi_target = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Hasil Capaian';
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
