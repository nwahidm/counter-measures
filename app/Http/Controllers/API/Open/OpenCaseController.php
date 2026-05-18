<?php

namespace App\Http\Controllers\API\Open;

use App\Models\User;
use App\Models\OpenCase;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\CaseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Open\Research\ResearchSaranTindakLanjut;
use App\Models\Open\Research\ResearchSuratPerintah;

use App\Models\ElicitationAdFoll;
use App\Models\ElicitationResult;
use App\Models\InterogationRecord;

use App\Models\ElicitationInterview;

use App\Models\Interview\InterviewHasil;
use App\Models\Interview\InterviewJadwal;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;
use App\Models\Interview\InterviewSaranTL;
use App\Models\Open\Research\ResearchPotensiAght;
use App\Models\InterogationResultAchievement;
use App\Models\InterogationTargetIdentification;
class OpenCaseController extends Controller
{
    public function getreport(Request $request)
    {
        // Tentukan jumlah item per halaman (misal 10)
        $perPage = $request->input('per_page', 10);

        // Ambil data OpenCase dengan paginasi dan eager loading
        $case_open_datas = OpenCase::with(['satker', 'progress', 'CaseEventHistoricalUpdates'])->paginate($perPage);

        // Buat response dengan data paginasi
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil Mengambil Data',
            'timestamp' => floor(microtime(true) * 1000),
            "data" => $case_open_datas->items(),
            "pagination" => [
                "total" => $case_open_datas->total(),
                "count" => $case_open_datas->count(),
                "per_page" => $case_open_datas->perPage(),
                "current_page" => $case_open_datas->currentPage(),
                "total_pages" => $case_open_datas->lastPage(),
                "links" => [
                    "next" => $case_open_datas->nextPageUrl(),
                    "prev" => $case_open_datas->previousPageUrl()
                ]
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $datas = OpenCase::with(["satker","caseProgress", "CaseEventHistoricalUpdates"])
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('open_case.id_satker', '=', $user->id_satker)
                              ->orWhere('master_satker.parent_id', '=', $user->id_satker);
                        }) ->orderby('open_case.created_at','DESC')->paginate(10);
        
        foreach ($datas as $item) { // Correct the foreach loop syntax
            if ($item->foto) { // Use $item instead of $data
                $imagePaths = json_decode($item->foto); // Decode the JSON string containing image paths
                    
                foreach ($imagePaths as $imagePath) { // Loop through each image path
                    $images[] = asset('storage/' . $imagePath); // Add the full image URL to the $images array
                }
                $item->foto = $imagePaths;
            }
        }
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
            'nama_kasus' => 'required',
            'tanggal_kasus' => 'required|date',
            // 'deskripsi_kasus' => 'required',

            'nama_target' => 'required',
            // 'agama' => 'required',
            // 'pendidikan' => 'required',
            // 'pekerjaan' => 'required',
            // 'alamat' => 'required',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('api')->user();

        if(!$user){
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'User tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        
        // create folder name, based on satker name
        $satker = MasterSatker::where('master_satker.id_satker', $user->id_satker)
            ->select([
                'master_satker.id_satker', 'master_satker.id_satker', 'master_satker.nama_satker',
            ])
            ->first();

        if($satker == null){
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Satuan Kerja tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        
        $folderName = strtolower(trim($satker->nama_satker));
        $folderName = str_replace(" ","_", $folderName);
        $folderPath = 'open_case_target_image/' . $folderName;

        // save the image first
        // $filenames = [];
        // $index = 1;
        // if($request->file('image') != null){
        //     foreach ($request->file('image') as $image) {
        //         $filename = $image->storePubliclyAs(
        //             $folderPath,
        //             time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension(),
        //             'public'
        //         );
        //         $filenames[] = $filename;
        //         $index++;
        //     }    
        // }

        $filenames = [];
        $index = 1;
        if($request->image != null){
            foreach ($request->image as $base64Image) {
                $decodedImage = base64_decode($base64Image);
                $fileName = time(). ' - '. $request->nama_target.' - '. $index . '.jpeg';
                $uploadPath = $folderPath . '/' . $fileName;

                // Simpan gambar
                Storage::disk('public')->put($uploadPath, $decodedImage);
                $filenames[] = $uploadPath;
                $index++;
            }    
        }

        // store data to database
        $case = OpenCase::create([
            'id_satker' => $satker->id_satker,
            'nama_kasus' => $request->nama_kasus,
            'tanggal_kasus' => $request->tanggal_kasus,
            'deskripsi_kasus' => $request->deskripsi_kasus,

            'nama_target' => $request->nama_target,
            'tipe_identitas' => $request->tipe_identitas,
            'no_identitas' => $request->no_identitas,
            'agama' => $request->agama,
            'pendidikan' => $request->pendidikan,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'foto' => json_encode($filenames),

            'jenis_kelamin'=> $request->jenis_kelamin,

            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        // case progress
        $case_progress = new CaseProgresses();
        $case_progress->case_id = $case->id;
        $case_progress->penelitian_upload_surat_perintah = "0";
        $case_progress->penelitian_lapinsus = "0";
        $case_progress->penelitian_aght = "0";
        $case_progress->penelitian_laporan = "0";
        $case_progress->penelitian_saran_dan_tindak_lanjut = "0";
        $case_progress->wawancara_jadwal = "0";
        $case_progress->wawancara_laporan = "0";
        $case_progress->wawancara_hasil = "0";
        $case_progress->wawancara_saran_dan_tindak_lanjut = "0";
        $case_progress->interogasi_berita_acara = "0";
        $case_progress->interogasi_identifikasi_target = "0";
        $case_progress->interogasi_hasil_yang_dicapai = "0";
        $case_progress->interogasi_laporan = "0";
        $case_progress->elisitasi_hasil_wawancara = "0";
        $case_progress->elisitasi_saran_dan_tindak_lanjut = "0";
        $case_progress->elisitasi_hasil_yang_dicapai = "0";
        $case_progress->elisitasi_laporan = "0";
        $case_progress->status = "Penambahan Kasus";
        $case_progress->substatus = "Penambahan Kasus";
        $case_progress->open_method_percentage = "0";
        $case_progress->close_method_percentage = "0";
        $case_progress->percentage = "0";

        $case_progress->created_by = $user->id;
        $case_progress->updated_by = $user->id;

        $case_progress->save();


        if ($case && $case_progress) {
            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Data berhasil disimpan',
                "data" => $case,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        } 
        
        return response()->json([
            "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            "message" => 'Data gagal disimpan',
            "data" => $case,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = OpenCase::with(["satker","caseProgress", "CaseEventHistoricalUpdates"])->find($id);

        if ($data->foto) { // Use $item instead of $data
            $imagePaths = json_decode($data->foto); // Decode the JSON string containing image paths
                
            foreach ($imagePaths as $imagePath) { // Loop through each image path
                $images[] = asset('storage/' . $imagePath); // Add the full image URL to the $images array
            }
            $data->foto = $imagePaths;
        }

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil Mengambil Data',
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
            'nama_kasus' => 'required',
            'tanggal_kasus' => 'required|date',
            // 'deskripsi_kasus' => 'required',

            'nama_target' => 'required',
            // 'agama' => 'required',
            // 'pendidikan' => 'required',
            // 'pekerjaan' => 'required',
            // 'alamat' => 'required',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $existingData = OpenCase::find($id);
        if (!$existingData) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // create folder name, based on satker name
        $satker = MasterSatker::where('master_satker.id_satker', $existingData->id_satker)
        ->select('master_satker.nama_satker')
        ->first();

        $folderName = strtolower(trim($satker->nama_satker));
        $folderName = str_replace(" ","_", $folderName);
        $folderPath = 'open_case_target_image/' . $folderName;

        $newImages = [];
        if ($request->image != null) {
            // Remove existing images
            if ($existingData->foto) {
                $existingImagePaths = json_decode($existingData->foto);
    
                foreach ($existingImagePaths as $existingImagePath) {
                    if (Storage::disk('public')->exists($existingImagePath)) {
                        Storage::disk('public')->delete($existingImagePath);
                    }
                }
            }
            // Save new images
            $index = 1;
            foreach ($request->image as $base64Image) {
                $decodedImage = base64_decode($base64Image);
                $filename = $folderPath . '/' . time() . ' - ' . $request->nama_target . ' - ' . $index . '.jpeg';
                Storage::disk('public')->put($filename, $decodedImage);
                $newImages[] = $filename;
                $index++;
            }    
        } else {
            $newImages = json_decode($existingData->foto);
        }

        // $newImages = [];
        // if ($request->file('image') != null) {
        //     // Remove existing images
        //     if ($existingData->foto) {
        //         $existingImagePaths = json_decode($existingData->foto);
    
        //         foreach ($existingImagePaths as $existingImagePath) {
        //             if (Storage::disk('public')->exists($existingImagePath)) {
        //                 Storage::disk('public')->delete($existingImagePath);
        //             }
        //         }
        //     }
        //     // Save new images
        //     $index = 1;
        //     foreach ($request->file('image') as $image) {
        //         $filename = $image->storePubliclyAs(
        //             $folderPath,
        //             time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension(),
        //             'public'
        //         );
        //         $newImages[] = $filename;
        //         $index++;
        //     }    
        // } else{
        //     $newImages = json_decode($existingData->foto);
        // }

        $user = Auth::guard('api')->user();
        
        $data = OpenCase::findOrFail($id);

        $data->update([
            'nama_kasus' => $request->nama_kasus,
            'tanggal_kasus' => $request->tanggal_kasus,
            'deskripsi_kasus' => $request->deskripsi_kasus,

            'nama_target' => $request->nama_target,
            'tipe_identitas' => $request->tipe_identitas,
            'no_identitas' => $request->no_identitas,
            'agama' => $request->agama,
            'pendidikan' => $request->pendidikan,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'foto' => json_encode($newImages),
            'jenis_kelamin'=> $request->jenis_kelamin,

            'updated_by' => $user->id,
        ]);

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil diubah',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = OpenCase::find($id);
        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                "message" => 'Data tidak ditemukan',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if ($data->foto) {
            $imagePaths = json_decode($data->foto);

            foreach ($imagePaths as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }
        $data->delete();
        ResearchSuratPerintah::where('case_id', $id)->delete();
        ResearchLaporanInformasiKhusus::where('case_id', $id)->delete();
        ResearchSaranTindakLanjut::where('case_id', $id)->delete();
        ResearchPotensiAght::where('case_id', $id)->delete();

        InterviewJadwal::where('case_id', $id)->delete();
        InterviewHasil::where('case_id', $id)->delete();
        InterviewSaranTL::where('case_id', $id)->delete();

        InterogationRecord::where('case_id', $id)->delete();
        InterogationTargetIdentification::where('case_id', $id)->delete();
        InterogationResultAchievement::where('case_id', $id)->delete();

        ElicitationInterview::where('case_id', $id)->delete();
        ElicitationAdFoll::where('case_id', $id)->delete();
        ElicitationResult::where('case_id', $id)->delete();

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Data berhasil dihapus',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }
}
