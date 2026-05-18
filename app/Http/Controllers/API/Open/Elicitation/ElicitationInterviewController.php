<?php

namespace App\Http\Controllers\API\Open\Elicitation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ElicitationInterview;
use App\Models\MasterSatker;
use Illuminate\Support\Str;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\ElicitationAdFoll;
use App\Models\ElicitationResult;
use App\Helpers\DataHelper;
use Carbon;
use App\Models\Documents;
use App\Models\VideoDocuments;
use App\Models\VideoDocumentAnalytics;
use App\Models\VideoAudioDocuments;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;

class ElicitationInterviewController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = ElicitationInterview::when(!$user->hasRole(['superadmin',]), function ($q) use ($idSatker) {
            $q->where('elicitation_hasil_wawancara.satker_id', '=', $idSatker);
        })
            ->with('case')
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
        $data = ElicitationInterview::find($id);

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
        $data = ElicitationInterview::find($id);

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
        ElicitationAdFoll::where('elicitation_hasil_wawancara_id', $id)->delete();
        ElicitationResult::where('elicitation_interview_result_id', $id)->delete();



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

        // $this->validate($request, [
        //     'id_case' => 'required',
        //     'id_satker' => 'required',
        //     'interviewer_name' => 'required',
        //     'interviewer_schedule' => 'required',
        //     'source_person_name' => 'required',
        //     'interview_result_path' => 'required|mimes:pdf|max:30000',
        // ]);

        $satker = MasterSatker::findOrFail($request->id_satker);
        $user = Auth::guard('api')->user();

        $data = new ElicitationInterview;
        // $data->kode_satker = $satker->kode_satker;
        $data->satker_id = $request->id_satker;

        $data->case_id = $request->id_case;
        $data->nip = $request->interviewer_nip;
        $data->interviewer_name = $request->interviewer_name;
        $data->pangkat = $request->interviewer_pangkat;
        $data->interviewer_schedule = $request->interviewer_schedule;
        
        $data->source_person_name = $request->source_person_name;
        $data->target_identity_number = $request->nik;
        $data->target_identity_number_type = 'NIK';
        $data->target_gender = $request->jenis_kelamin;
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;
        $data->target_religion = $request->agama;
        
        $data->target_address = $request->alamat;

        // if ($request->hasFile('interview_result_path')) {
        //     $ext_interview_result = $request->file('interview_result_path')->extension();
        //     $interview_result = $request->file('interview_result_path')
        //         ->storePubliclyAs(
        //             'open/data/elicitation/interview',
        //             Str::slug('elicitation interview', '_') . '_' . Str::random() . '.' . $ext_interview_result,
        //             'public'
        //         );

        //     $data->interview_result_path = $interview_result;
        // }

        // if ($request->hasFile('upload_video_elicitation')) {
        //     $ext_interview_result = $request->file('upload_video_elicitation')->extension();
        //     $interview_video_result = $request->file('upload_video_elicitation')
        //         ->storePubliclyAs(
        //             'open/data/elicitation/interview_video',
        //             Str::slug('elicitation interview video', '_') . '_' . Str::random() . '.' . $ext_interview_result,
        //             'public'
        //         );

        //     $data->interview_video_path = $interview_video_result;
        // }

        // if ($request->hasFile('target_photo')) {
        //     $ext_target_photo = $request->file('target_photo')->extension();
        //     $target_photo = $request->file('target_photo')
        //         ->storePubliclyAs(
        //             'open/data/elicitation/interview',
        //             Str::slug('interview foto', '_') . '_' . Str::random() . '.' . $ext_target_photo,
        //             'public'
        //         );

        //     $data->target_photo = $target_photo;
        // }

        if ($request->interview_result_path) {
            // Mendekode base64 interview_result_path
            $base64_interview_result = $request->input('interview_result_path');

            // Pisahkan metadata base64 jika ada
            // if (strpos($base64_interview_result, ',') !== false) {
            //     list(, $base64_interview_result) = explode(',', $base64_interview_result);
            // }

            // Lakukan decoding base64
            $decoded_interview_result = base64_decode($base64_interview_result);

            // Tentukan ekstensi file dari base64
            // $finfo = finfo_open(FILEINFO_MIME_TYPE);
            // $mimeType = finfo_buffer($finfo, $decoded_interview_result);
            // $ext_interview_result = explode('/', $mimeType)[1];

            // Buat nama file dan simpan ke storage
            $interview_result_path = 'open/data/elicitation/interview/' .
                // Str::slug('elicitation interview', '_') . '_' . Str::random() . '.' . $ext_interview_result;
                Str::slug('elicitation interview', '_') . '_' . Str::random() . '.mp4';

            Storage::disk('public')->put($interview_result_path, $decoded_interview_result);

            // Menyimpan path file ke data
            $data->interview_result_path = $interview_result_path;
        }

        if ($request->upload_video_elicitation) {
            // Mendekode base64 upload_video_elicitation
            $base64_upload_video = $request->input('upload_video_elicitation');

            // Pisahkan metadata base64 jika ada
            // if (strpos($base64_upload_video, ',') !== false) {
            //     list(, $base64_upload_video) = explode(',', $base64_upload_video);
            // }

            // Lakukan decoding base64
            $decoded_video = base64_decode($base64_upload_video);

            // Tentukan ekstensi file dari base64
            // $finfo = finfo_open(FILEINFO_MIME_TYPE);
            // $mimeType = finfo_buffer($finfo, $decoded_video);
            // $ext_upload_video = explode('/', $mimeType)[1];

            // Buat nama file dan simpan ke storage
            $interview_video_path = 'open/data/elicitation/interview_video/' .
                // Str::slug('elicitation interview video', '_') . '_' . Str::random() . '.' . $ext_upload_video;
                Str::slug('elicitation interview video', '_') . '_' . Str::random() . '.mp4';

            Storage::disk('public')->put($interview_video_path, $decoded_video);

            // Menyimpan path file ke data
            $data->interview_video_path = $interview_video_path;
        }

        if ($request->hasFile('target_photo')) {
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'open/elicitation/interview/target_photo',
                    Str::slug('elicitation interview', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            $data->target_photo = $target_photo;
        }


        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        $data->satker_id = $user->id_satker;

        if ($request->submit_type === 'save') {

            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->elisitasi_hasil_wawancara = 1;
            $updateCaseProgresses->status = 'Elicitation';
            $updateCaseProgresses->substatus = 'Penambahan Elisitasi Hasil Wawancara';
            $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 82.32 ? $updateCaseProgresses->percentage : 82.32;
            $updateCaseProgresses->save();

            if ($data->save()) {
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Elisitasi Hasil Wawancara';
                $cp->created_by = $user->id;
                $cp->save();

                $document_pdf = new Documents;
                $document_pdf->doc_path = $data->interview_result_path;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_elicitation_interview_result;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();

                $video_document_pdf = new VideoDocuments;
                $video_document_pdf->doc_path = $data->interview_video_path;
                $video_document_pdf->doc_type = "video";
                $video_document_pdf->doc_status = "0";
                $video_document_pdf->doc_status_remark = "Waiting Analysis";
                $video_document_pdf->relation_id = $data->id_elicitation_interview_result;
                $video_document_pdf->created_by = $user->id;
                $video_document_pdf->updated_by = $user->id;
                $video_document_pdf->save();

                $video_document_pdf = new VideoAudioDocuments;
                $video_document_pdf->doc_path = $data->interview_video_path;
                $video_document_pdf->doc_type = "video_audio";
                $video_document_pdf->doc_status = "0";
                $video_document_pdf->doc_status_remark = "Waiting Analysis";
                $video_document_pdf->relation_id = $data->id_elicitation_interview_result;
                $video_document_pdf->created_by = $user->id;
                $video_document_pdf->updated_by = $user->id;
                $video_document_pdf->save();

                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->update([
                    'elisitasi_hasil_wawancara' => '1',
                    'status' => 'Elicitation',
                    'substatus' => 'Penambahan Elisitasi Hasil Wawancara',
                    'updated_at' => Carbon\Carbon::now(),
                    'updated_by' => $user->id
                ]);

                // $log = DataHelper::logUpdateCase($data->case_id, 'Penambahan Elisitasi Hasil Wawancara');

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
        } else {
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->elisitasi_hasil_wawancara = 1;
            $updateCaseProgresses->status = 'Elicitation';
            $updateCaseProgresses->substatus = 'Penambahan Elisitasi Hasil Wawancara';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();


            if ($data->save()) {

                $document_pdf = new Documents;
                $document_pdf->doc_path = $data->interview_result_path;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_elicitation_interview_result;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();

                $video_document_pdf = new VideoDocuments;
                $video_document_pdf->doc_path = $data->interview_video_path;
                $video_document_pdf->doc_type = "video";
                $video_document_pdf->doc_status = "0";
                $video_document_pdf->doc_status_remark = "Waiting Analysis";
                $video_document_pdf->relation_id = $data->id_elicitation_interview_result;
                $video_document_pdf->created_by = $user->id;
                $video_document_pdf->updated_by = $user->id;
                $video_document_pdf->save();

                $video_document_pdf = new VideoAudioDocuments;
                $video_document_pdf->doc_path = $data->interview_video_path;
                $video_document_pdf->doc_type = "video_audio";
                $video_document_pdf->doc_status = "0";
                $video_document_pdf->doc_status_remark = "Waiting Analysis";
                $video_document_pdf->relation_id = $data->id_elicitation_interview_result;
                $video_document_pdf->created_by = $user->id;
                $video_document_pdf->updated_by = $user->id;
                $video_document_pdf->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $request->id_case;
                $cp->action = 'Penambahan Elisitasi Hasil Wawancara';
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
            // 'id_case' => 'required',
            // 'interviewer_name' => 'required',
            // 'interviewer_schedule' => 'required',
            // 'source_person_name' => 'required',
            // 'target_identity_number' => 'required',
            // 'target_identity_number_type' => 'required',
            // 'target_gender' => 'required',
            // 'target_religion' => 'required',
            // 'target_occupation' => 'required',
            // 'target_education' => 'required',
            // 'target_photo' => 'mimes:jpg,jpeg,png,bmp,tiff |max:10000',
            // 'target_address' => 'required',
        ]);

        $user = Auth::guard('api')->user();

        $data = ElicitationInterview::find($id);
        $data->case_id = $request->id_case;
        $data->nip = $request->interviewer_nip;
        $data->interviewer_name = $request->interviewer_name;
        $data->pangkat = $request->interviewer_pangkat;
        $data->interviewer_schedule = $request->interviewer_schedule;
        $data->source_person_name = $request->source_person_name;
        $data->target_identity_number = $request->nik;
        $data->target_identity_number_type = 'NIK';

        $data->target_gender = $request->jenis_kelamin;
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;
        $data->target_religion = $request->agama;
        $data->target_address = $request->alamat;

        // // DOKUMEN 
        // if ($request->hasFile('interview_result_path')) {
        //     $ext_interview_result = $request->file('interview_result_path')->extension();
        //     $interview_result = $request->file('interview_result_path')
        //         ->storePubliclyAs(
        //             'open/data/elicitation/interview',
        //             Str::slug('elicitationInterview', '_') . '_' . Str::random() . '.' . $ext_interview_result,
        //             'public'
        //         );

        //     if (Storage::disk('public')->exists($request->temp_interview_result)) {
        //         Storage::disk('public')->delete($request->temp_interview_result);
        //     }

        //     $data->interview_result_path = $interview_result;

        //     $document_pdf = Documents::where('relation_id', $id)->first();
        //     if ($document_pdf) {
        //         $document_pdf->doc_path = $interview_result;
        //         $document_pdf->doc_type = "pdf";
        //         $document_pdf->doc_status = "0";
        //         $document_pdf->doc_status_remark = "Waiting Analysis";
        //         $document_pdf->updated_by = $user->id;
        //         $document_pdf->update();
        //     } else {
        //         $document_pdf = new Documents;
        //         $document_pdf->doc_path = $interview_result;
        //         $document_pdf->doc_type = "pdf";
        //         $document_pdf->doc_status = "0";
        //         $document_pdf->doc_status_remark = "Waiting Analysis";
        //         $document_pdf->relation_id = $data->id_interogation_result_achievement;
        //         $document_pdf->created_by = $user->id;
        //         $document_pdf->updated_by = $user->id;
        //         $document_pdf->save();
        //     }
        // } else {
        //     $interview_result = $request->temp_interview_result;

        //     $data->interview_result_path = $interview_result;
        // }

        // if ($request->hasFile('upload_video_elicitation')) {
        //     $ext_interview_result = $request->file('upload_video_elicitation')->extension();
        //     $interview_video_result = $request->file('upload_video_elicitation')
        //         ->storePubliclyAs(
        //             'open/data/elicitation/interview_video',
        //             Str::slug('elicitation interview video', '_') . '_' . Str::random() . '.' . $ext_interview_result,
        //             'public'
        //         );

        //     $data->interview_video_path = $interview_video_result;

        //     $document_video = new VideoDocuments;
        //     $document_video->doc_path = $interview_video_result;
        //     $document_video->doc_status = "0";
        //     $document_video->doc_type = "video";
        //     $document_video->doc_status_remark = "Waiting Analysis";
        //     $document_video->relation_id = $id;
        //     $document_video->save();

        //     $video_document_pdf = new VideoAudioDocuments;
        //     $video_document_pdf->doc_path = $data->interview_video_path;
        //     $video_document_pdf->doc_type = "video_audio";
        //     $video_document_pdf->doc_status = "0";
        //     $video_document_pdf->doc_status_remark = "Waiting Analysis";
        //     $video_document_pdf->relation_id = $data->id_elicitation_interview_result;
        //     $video_document_pdf->created_by = $user->id;
        //     $video_document_pdf->updated_by = $user->id;
        //     $video_document_pdf->save();

        // } else {
        //     $interview_result = $request->temp_upload_video_elicitation;

        //     $data->interview_result_path = $interview_result;
        // }

        // // FOTO
        // if ($request->hasFile('target_photo')) {
        //     $ext_target_photo = $request->file('target_photo')->extension();
        //     $target_photo = $request->file('target_photo')
        //         ->storePubliclyAs(
        //         'open/data/elicitation/interview',
        //         Str::slug('elicitationInterview', '_') . '_' . Str::random() . '.' . $ext_target_photo,
        //         'public'
        //     );

        //     if (Storage::disk('public')->exists($request->target_photo)) {
        //         Storage::disk('public')->delete($request->target_photo);
        //     }
        //     $data->target_photo = $target_photo;
        // }

        // DOKUMEN 
        if ($request->interview_result_path) {
            // Mendekode base64 interview_result_path
            $base64_interview_result = $request->input('interview_result_path');

            // Pisahkan metadata base64 jika ada
            // if (strpos($base64_interview_result, ',') !== false) {
            //     list(, $base64_interview_result) = explode(',', $base64_interview_result);
            // }

            // Lakukan decoding base64
            $decoded_interview_result = base64_decode($base64_interview_result);

            // Tentukan ekstensi file dari base64
            // $finfo = finfo_open(FILEINFO_MIME_TYPE);
            // $mimeType = finfo_buffer($finfo, $decoded_interview_result);
            // $ext_interview_result = explode('/', $mimeType)[1];

            // Buat nama file dan simpan ke storage
            $interview_result = 'open/data/elicitation/interview/' .
                // Str::slug('elicitationInterview', '_') . '_' . Str::random() . '.' . $ext_interview_result;
                Str::slug('elicitationInterview', '_') . '_' . Str::random() . '.pdf';

            Storage::disk('public')->put($interview_result, $decoded_interview_result);

            // Hapus file lama jika ada
            if (Storage::disk('public')->exists($request->temp_interview_result)) {
                Storage::disk('public')->delete($request->temp_interview_result);
            }

            $data->interview_result_path = $interview_result;

            // Menyimpan ke database
            $document_pdf = Documents::where('relation_id', $id)->first();
            if ($document_pdf) {
                $document_pdf->doc_path = $interview_result;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->updated_by = $user->id;
                $document_pdf->update();
            } else {
                $document_pdf = new Documents;
                $document_pdf->doc_path = $interview_result;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->relation_id = $data->id_interogation_result_achievement;
                $document_pdf->created_by = $user->id;
                $document_pdf->updated_by = $user->id;
                $document_pdf->save();
            }
        } else {
            $interview_result = $request->temp_interview_result;

            $data->interview_result_path = $interview_result;
        }

        if ($request->upload_video_elicitation) {
            // Mendekode base64 upload_video_elicitation
            $base64_upload_video = $request->input('upload_video_elicitation');

            // Pisahkan metadata base64 jika ada
            // if (strpos($base64_upload_video, ',') !== false) {
            //     list(, $base64_upload_video) = explode(',', $base64_upload_video);
            // }

            // Lakukan decoding base64
            $decoded_video = base64_decode($base64_upload_video);

            // Tentukan ekstensi file dari base64
            // $finfo = finfo_open(FILEINFO_MIME_TYPE);
            // $mimeType = finfo_buffer($finfo, $decoded_video);
            // $ext_upload_video = explode('/', $mimeType)[1];

            // Buat nama file dan simpan ke storage
            $interview_video_result = 'open/data/elicitation/interview_video/' .
                // Str::slug('elicitation interview video', '_') . '_' . Str::random() . '.' . $ext_upload_video;
                Str::slug('elicitation interview video', '_') . '_' . Str::random() . '.mp4';

            Storage::disk('public')->put($interview_video_result, $decoded_video);

            // Menyimpan path file ke data
            $data->interview_video_path = $interview_video_result;

            // Menyimpan ke database
            $document_video = new VideoDocuments;
            $document_video->doc_path = $interview_video_result;
            $document_video->doc_status = "0";
            $document_video->doc_type = "video";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->relation_id = $id;
            $document_video->save();

            $video_document_pdf = new VideoAudioDocuments;
            $video_document_pdf->doc_path = $data->interview_video_path;
            $video_document_pdf->doc_type = "video_audio";
            $video_document_pdf->doc_status = "0";
            $video_document_pdf->doc_status_remark = "Waiting Analysis";
            $video_document_pdf->relation_id = $data->id_elicitation_interview_result;
            $video_document_pdf->created_by = $user->id;
            $video_document_pdf->updated_by = $user->id;
            $video_document_pdf->save();

        } else {
            $interview_result = $request->temp_upload_video_elicitation;

            $data->interview_result_path = $interview_result;
        }

        if ($request->hasFile('target_photo')){
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'open/elicitation/interview/target_photo',
                    Str::slug('elicitation interview', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            if ($request->old_target_photo && Storage::disk('public')->exists($request->old_target_photo)) {
                Storage::disk('public')->delete($request->old_target_photo);
            }

            $data->target_photo = $target_photo;
        } else {
            $target_photo = $request->old_target_photo;

            $data->target_photo = $target_photo;
        }

        $data->updated_by = $user->id;

        if ($data->update()) {

            if ($request->submit_type === 'update_and_finish') {

                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->elisitasi_hasil_wawancara = 1;
                $updateCaseProgresses->status = 'Elicitation';
                $updateCaseProgresses->substatus = 'Penambahan Elisitasi Hasil Wawancara';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();


            }

            // $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Elisitasi Hasil Wawancara');

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
