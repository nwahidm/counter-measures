<?php

namespace App\Http\Controllers\API\Open\Elicitation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ElicitationResult;
use App\Models\MasterSatker;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\Documents;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Helpers\DataHelper;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;


class ElicitationResultAchievementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = ElicitationResult::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('elicitation_hasil_yang_dicapai.satker_id', '=', $idSatker);
                                })
                                ->with('case')
                                ->with('elinterview')
                                ->with('elinadfoll')
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
        $data = ElicitationResult::find($id);

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
        $data->load(['satker', 'case', 'elinterview', 'elinadfoll']);

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
        $data = ElicitationResult::find($id);

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
            'id_case' => 'required',
            'id_satker' => 'required',
            'id_elicitation_interview_result' => 'required',
            'id_elicitation_advice_and_followup' => 'required',
            'pendahuluan' => 'required',
            'pelaksanaan_kegiatan' => 'required',
            'kendala' => 'required',
            'analisa' => 'required',
            'upload_hasil_yang_dicapai' => 'mimes:pdf|max:30000',
        ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();

        $data = new ElicitationResult();
        $data->satker_id = $request->id_satker;

        $data->case_id = $request->id_case;
        $data->elicitation_interview_result_id = $request->id_elicitation_interview_result;
        $data->elicitation_advice_and_follow_up_id = $request->id_elicitation_advice_and_followup;
        $data->pendahuluan = $request->pendahuluan;
        $data->pelaksanaan_kegiatan = $request->pelaksanaan_kegiatan;
        $data->kendala = $request->kendala;
        $data->analisa = $request->analisa;
        // if ($request->hasFile('upload_hasil_yang_dicapai')) {
        //     $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
        //     $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
        //         ->storePubliclyAs(
        //             'open/data/elicitation/result',
        //             Str::slug('Elicitation result', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
        //             'public'
        //         );

        //     $data->hasil_yang_dicapai_path = $upload_hasil_yang_dicapai;
        // }

        if ($request->upload_hasil_yang_dicapai) {
            // Mendekode base64 upload_hasil_yang_dicapai
            $base64_upload_hasil_yang_dicapai = $request->input('upload_hasil_yang_dicapai');
        
            // Pisahkan metadata base64 jika ada
            // if (strpos($base64_upload_hasil_yang_dicapai, ',') !== false) {
            //     list(, $base64_upload_hasil_yang_dicapai) = explode(',', $base64_upload_hasil_yang_dicapai);
            // }
        
            // Lakukan decoding base64
            $decoded_file = base64_decode($base64_upload_hasil_yang_dicapai);
        
            // Tentukan ekstensi file dari base64
            // $finfo = finfo_open(FILEINFO_MIME_TYPE);
            // $mimeType = finfo_buffer($finfo, $decoded_file);
            // $ext_upload_hasil_yang_dicapai = explode('/', $mimeType)[1];
        
            // Buat nama file dan simpan ke storage
            $upload_hasil_yang_dicapai = 'open/data/elicitation/result/' .
                // Str::slug('Elicitation result', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai;
            Str::slug('Elicitation result', '_') . '_' . Str::random() . '.pdf';
        
            Storage::disk('public')->put($upload_hasil_yang_dicapai, $decoded_file);
        
            // Menyimpan path file ke data
            $data->hasil_yang_dicapai_path = $upload_hasil_yang_dicapai;
        }

        
        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        $data->satker_id = $user->id_satker;
        

        $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
        $updateCaseProgresses->elisitasi_hasil_yang_dicapai = 1;
        $updateCaseProgresses->elisitasi_laporan = 1;
        $updateCaseProgresses->status = 'Elicitation';
        $updateCaseProgresses->substatus = 'Penambahan Elisitasi Hasil yang Dicapai';
        $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 100 ? $updateCaseProgresses->percentage : 100;
        $updateCaseProgresses->save();

        $cp = CaseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
        $cp->action = 'Penambahan Elisitasi Hasil yang Dicapai';
        $cp->created_by = $user->id;
        $cp->save();

        if ($data->save()) {

            $document_pdf = new Documents;
            $document_pdf->doc_path = $upload_hasil_yang_dicapai;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->relation_id = $data->id_elicitation_result;
            $document_pdf->created_by = $user->id;
            $document_pdf->updated_by = $user->id;
            $document_pdf->save();

            // $log = DataHelper::logUpdateCase($data->case_id, 'Penambahan Elisitasi Hasil yang Dicapai');

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

    public function update(Request $request, $id)
    {
        //

        $this->validate($request, [
            'id_case' => 'required',
            'pendahuluan' => 'required',
            'pelaksanaan_kegiatan' => 'required',
            'kendala' => 'required',
            'analisa' => 'required',
            'upload_hasil_yang_dicapai' => 'mimes:pdf|max:30000',
        ]);
        
        $user = Auth::guard('api')->user();

        $data = ElicitationResult::find($id);
        $data->case_id = $request->id_case;
        $data->elicitation_interview_result_id = $request->id_elicitation_interview_result;
        $data->elicitation_advice_and_follow_up_id = $request->id_elicitation_advice_and_followup;
        $data->pendahuluan = $request->pendahuluan;
        $data->pelaksanaan_kegiatan = $request->pelaksanaan_kegiatan;
        $data->kendala = $request->kendala;
        $data->analisa = $request->analisa;

        if ($request->upload_hasil_yang_dicapai) {
            // $ext_upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')->extension();
            // $upload_hasil_yang_dicapai = $request->file('upload_hasil_yang_dicapai')
            //     ->storePubliclyAs(
            //         'open/data/elicitation/result',
            //         Str::slug('elicitationresult', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai,
            //         'public'
            //     );

            // if (Storage::disk('public')->exists($request->temp_upload_hasil_yang_dicapai)) {
            //     Storage::disk('public')->delete($request->temp_upload_hasil_yang_dicapai);
            // }

            $base64_upload_hasil_yang_dicapai = $request->input('upload_hasil_yang_dicapai');
        
            // Pisahkan metadata base64 jika ada
            // if (strpos($base64_upload_hasil_yang_dicapai, ',') !== false) {
            //     list(, $base64_upload_hasil_yang_dicapai) = explode(',', $base64_upload_hasil_yang_dicapai);
            // }
        
            // Lakukan decoding base64
            $decoded_file = base64_decode($base64_upload_hasil_yang_dicapai);
        
            // Tentukan ekstensi file dari base64
            // $finfo = finfo_open(FILEINFO_MIME_TYPE);
            // $mimeType = finfo_buffer($finfo, $decoded_file);
            // $ext_upload_hasil_yang_dicapai = explode('/', $mimeType)[1];
        
            // Buat nama file dan simpan ke storage
            $upload_hasil_yang_dicapai = 'open/data/elicitation/result/' .
                // Str::slug('Elicitation result', '_') . '_' . Str::random() . '.' . $ext_upload_hasil_yang_dicapai;
            Str::slug('Elicitation result', '_') . '_' . Str::random() . '.pdf';
        
            Storage::disk('public')->put($upload_hasil_yang_dicapai, $decoded_file);
        

            $data->hasil_yang_dicapai_path = $upload_hasil_yang_dicapai;

            $document_pdf = Documents::where('relation_id',$id)->first();
            $document_pdf->doc_path = $upload_hasil_yang_dicapai;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->updated_by = $user->id;
            $document_pdf->save();
        } else {
            $hasil_yang_dicapai_path = $request->temp_upload_hasil_yang_dicapai;
            $data->hasil_yang_dicapai_path = $hasil_yang_dicapai_path;
        }

        $data->updated_by = $user->id;

        if ($data->update()) {
            // $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Elisitasi Hasil yang Dicapai');
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
