<?php

namespace App\Http\Controllers\API\Open\Interrogation;

use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\MasterPegawai;
use App\Models\CaseProgresses;
use App\Models\InterogationRecord;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseEventHistoricalUpdates;
use Symfony\Component\HttpFoundation\Response;

use App\Models\InterogationTargetIdentification;
use App\Models\InterogationResultAchievement;

use App\Models\CaseCloseEventHistoricalUpdates;
use App\Models\Delineation\DelineationInformationVerification;

class InterrogationRecordController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker->id_satker;

        $data = InterogationRecord::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('interrogation_berita_acara.satker_id', '=', $idSatker);
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
        $data = InterogationRecord::find($id);

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

    // download BAP
    public function downloadBap($id_interogation_record)
    {
        $data = InterogationRecord::find($id_interogation_record);

        if(!$data){
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        $satker = MasterSatker::find($data->satker_id);
        $listJaksa = json_decode($data->jaksa);
        $listJaksa = MasterPegawai::whereIn('nip', $listJaksa)->get();

        // dd($data, $listJaksa, $satker);

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'margin_top' => 25,    // Space between top of page and main content
            'margin_bottom' => 25,
            'format' => [215, 330]
        ]);

        $headerHTML = '<div style="text-align: center; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">RAHASIA</div>';
        $mpdf->SetHTMLHeader($headerHTML);

        $footerHTML = '<div style="text-align: center; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">RAHASIA</div>';
        $mpdf->SetHTMLFooter($footerHTML);


        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->AddPage();
        $mpdf->WriteHTML(view("backoffice.template_laporan.in-10", compact('data', 'satker', 'listJaksa')));

        $filename = 'Open_Interogation_BAP-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'D');
    }

    public function destroy($id, Request $request)
    {
        $data = InterogationRecord::find($id);

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
        InterogationTargetIdentification::where('interogation_record_id', $id)->delete();
        InterogationResultAchievement::where('interogation_record_id', $id)->delete();


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
            'id_case' => 'required',
            'no_surat' => 'required',
            'tanggal_surat' => 'required',
            'perihal' => 'required',
            'nik' => 'required',
            'nama_target' => 'required',
            'hasil' => 'required',
            'pegawai' => 'required',
            'foto' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff |max:10000',
        ]);

        $user = Auth::guard('api')->user();

        $data = new InterogationRecord();
        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->letter_number = $request->no_surat;
        $data->letter_date = $request->tanggal_surat;
        $data->perihal = $request->perihal;
        $data->target_name = $request->nama_target;
        $data->target_identity_number = $request->nik;
        $data->target_type_identity_number = 'NIK/KTP';
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;
        $data->born_place = $request->born_place;
        $data->born_date = $request->born_date;
        $data->phone_number = $request->phone_number;
        $data->nationality = $request->nationality;
        $data->target_address = $request->alamat;
        $data->hasil = $request->hasil;
        // $data->jaksa = json_encode($request->pegawai);
        $data->jaksa = $request->pegawai;

        if ($request->upload_berita_acara) {
            $base64Document = $request->upload_berita_acara;

            $decodedDocument = base64_decode($base64Document);
            // $fileName = Str::slug('interview hasil', '_') . '_' . Str::random() . '.' . $extension;
            $fileName = Str::slug('interogationrecord', '_') . '_' . Str::random() . '.pdf';
            $upload_berita_acara = 'open/interview/hasil/interogation/' . $fileName;

            // Store the document
            Storage::disk('public')->put($upload_berita_acara, $decodedDocument);
            $data->berita_acara_path = $upload_berita_acara;
        }

        // if ($request->hasFile('upload_berita_acara')) {
        //     $ext_upload_berita_acara = $request->file('upload_berita_acara')->extension();
        //     $upload_berita_acara = $request->file('upload_berita_acara')
        //         ->storePubliclyAs(
        //             'open/data/interogation',
        //             Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_upload_berita_acara,
        //             'public'
        //         );

        //     $data->berita_acara_path = $upload_berita_acara;
        // }

        if ($request->foto) {
            $base64Foto = $request->foto;

            $decodedFoto = base64_decode($base64Foto);
            $fileName = Str::slug('interogationrecord', '_') . '_' . Str::random() . '.jpg';
            $uploadPath = 'open/data/interogation/' . $fileName;

            // Simpan foto
            Storage::disk('public')->put($uploadPath, $decodedFoto);
            $data->target_photo = $uploadPath;
        }

        // if ($request->hasFile('foto')) {
        //     $ext_foto = $request->file('foto')->extension();
        //     $foto = $request->file('foto')
        //         ->storePubliclyAs(
        //             'open/data/interogation',
        //             Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_foto,
        //             'public'
        //         );

        //     $data->target_photo = $foto;
        // }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        
        if ($request->submit_type === 'save') {
            $updateCaseProgresses = CaseProgresses::where('case_id', $data->case_id)->first();
            $updateCaseProgresses->interogasi_berita_acara = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Berita Acara';
            $updateCaseProgresses->percentage = $updateCaseProgresses->percentage > 58.8 ? $updateCaseProgresses->percentage : 58.8;
            $updateCaseProgresses->save();

            $cp = CaseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
            $cp->action = 'Penambahan Interogasi Berita Acara';
            $cp->created_by = $user->id;
            $cp->save();               

            if ($data->save()) {

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
            $updateCaseProgresses->interogasi_berita_acara = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Berita Acara';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();

            $cp = CaseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
            $cp->action = 'Penambahan Interogasi Berita Acara';
            $cp->created_by = $user->id;
            $cp->save();               

            if ($data->save()) {

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
            'id_satker' => 'required',
            'id_case' => 'required',
            'nomor_surat' => 'required',
            'tanggal_surat' => 'required',
            'perihal' => 'required',
            'nama_target' => 'required',
            'nik' => 'required',
            // 'tipe_target' => 'required',
            'jenis_kelamin' => 'nullable',
            'agama' => 'nullable',
            'pekerjaan' => 'nullable',
            'pendidikan' => 'nullable',
            'alamat' => 'nullable',
            'pegawai' => 'required',
            'hasil' => 'required',
            'foto' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff |max:10000',
            'upload_berita_acara' => 'nullable|mimes:pdf|max:30000'
        ]);
        
        $user = Auth::guard('api')->user();

        $data = InterogationRecord::find($id);

        $data->satker_id = $request->id_satker;
        $data->case_id = $request->id_case;
        $data->letter_number = $request->nomor_surat;
        $data->letter_date = $request->tanggal_surat;
        $data->perihal = $request->perihal;
        $data->target_name = $request->nama_target;
        $data->target_identity_number = $request->nik;
        $data->target_type_identity_number = 'NIK/KTP';
        $data->target_gender = $request->jenis_kelamin;
        $data->target_religion = $request->agama;
        $data->target_occupation = $request->pekerjaan;
        $data->target_education = $request->pendidikan;
        $data->target_address = $request->alamat;
        $data->hasil = $request->hasil;
        // $data->jaksa = json_encode($request->pegawai);
        $data->jaksa = $request->pegawai;

        // FOTO 
        if ($request->hasFile('foto')) {
            $ext_foto = $request->file('foto')->extension();
            $foto = $request->file('foto')
                ->storePubliclyAs(
                    'open/data/interogation/',
                    Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_foto,
                    'public'
                );

            if (Storage::disk('public')->exists($request->foto)) {
                Storage::disk('public')->delete($request->foto);
            }

            $data->target_photo = $foto;
        } 
        // DOKUMEN
        if ($request->hasFile('upload_berita_acara')) {
            $ext_upload_berita_acara = $request->file('upload_berita_acara')->extension();
            $upload_berita_acara = $request->file('upload_berita_acara')
                ->storePubliclyAs(
                    'open/data/interogation/',
                    Str::slug('interogationrecord', '_') . '_' . Str::random() . '.' . $ext_upload_berita_acara,
                    'public'
                );

            if (Storage::disk('public')->exists($request->temp_upload_berita_acara)) {
                Storage::disk('public')->delete($request->temp_upload_berita_acara);
            }
            $data->berita_acara_path = $upload_berita_acara;
        } else {
            $upload_berita_acara = $request->temp_upload_berita_acara;

            $data->berita_acara_path = $upload_berita_acara;
        }

        $log = DataHelper::logUpdateCase($data->case_id, 'Perubahan Data Interogasi Berita Acara', $data->target_name);

        $data->updated_by = $user->id;
        if ($request->submit_type === 'update_and_finish') {
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->interogasi_berita_acara = 1;
            $updateCaseProgresses->status = 'Interogation';
            $updateCaseProgresses->substatus = 'Penambahan Interogasi Berita Acara';
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
