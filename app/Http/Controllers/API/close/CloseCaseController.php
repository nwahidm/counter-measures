<?php

namespace App\Http\Controllers\API\close;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CloseCase;
use App\Models\MasterSatker;
use Illuminate\Support\Facades\Auth;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;

class CloseCaseController extends Controller
{
    public function getreport(Request $request)
    {
        // Tentukan jumlah item per halaman (misal 10)
        $perPage = $request->input('per_page', 10);

        // Ambil data CloseCase dengan paginasi dan eager loading
        $case_close_datas = CloseCase::with(['satker', 'caseCloseProgress', 'caseCloseEventHistoricalUpdate'])->paginate($perPage);

        // Buat response dengan data paginasi
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data Success',
            'timestamp' => floor(microtime(true) * 1000),
            "data" => $case_close_datas->items(),
            "pagination" => [
                "total" => $case_close_datas->total(),
                "count" => $case_close_datas->count(),
                "per_page" => $case_close_datas->perPage(),
                "current_page" => $case_close_datas->currentPage(),
                "total_pages" => $case_close_datas->lastPage(),
                "links" => [
                    "next" => $case_close_datas->nextPageUrl(),
                    "prev" => $case_close_datas->previousPageUrl()
                ]
            ]
        ]);
    }

    public function save(Request $request)
    {
        
   

            $user = Auth::guard('api')->user();
    
            
            // create folder name, based on satker name
            $satker = MasterSatker::where('master_satker.id_satker', $user->id_satker)
                ->select([
                    'master_satker.id_satker', 'master_satker.id_satker', 'master_satker.nama_satker',
                ])
                ->first();

            if($satker == null){
                return redirect()->back()->with('error', 'Satker Tidak Ditemukan!');
            }

            
            $folderName = strtolower(trim($satker->nama_satker));
            $folderName = str_replace(" ","_", $folderName);
            $folderPath = public_path('close_case_target_image/' . $folderName);

            if (! file_exists($folderPath)) {
                mkdir($folderPath, 0775, true);
            }

            // save the image first
            $filenames = [];
            $index = 1;
            if($request->file('image') != null){
                foreach ($request->file('image') as $image) {
                    $filename = time(). ' - '. $request->nama_target.' - '. $index . '.'. $image->getClientOriginalExtension();
                    
                    
                    // $image->move($folderPath, $filename);
                    $filenames[] = $filename;
                    $index++;

                    $target_photo = $image
                    ->storePubliclyAs(
                        'close/case',
                        $filename,
                        'public'
                    );
    
                    // $data->target_photo = $target_photo;
                }    
            }

            // store data to database
            $closeCaseSave = CloseCase::create([
                'satker_id' => $satker->id_satker,
                'case_name' => $request->nama_kasus,
                'case_date' => $request->tanggal_kasus,
                'case_description' => $request->deskripsi_kasus,

                'target_name' => $request->nama_target,
                'target_identity_number_type' => 'NIK/KTP',
                'target_identity_number' => $request->nik,
                'target_religion' => $request->agama,
                'target_gender'=> $request->jenis_kelamin,
                'target_education' => $request->pendidikan,
                'target_occupation' => $request->pekerjaan,
                'target_address' => $request->alamat,
                'target_photo' => json_encode($filenames),

                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);


            $case_close_progress = new CaseCloseProgresses;
            $case_close_progress->case_id = $closeCaseSave->id;
            
            $case_close_progress->observation_surat_perintah = "0";
            $case_close_progress->observation_information_collection= "0";
            $case_close_progress->observation_potensi_aght= "0";
            $case_close_progress->observation_identitas_terhubung= "0";
            $case_close_progress->observation_laporan = "0";
            $case_close_progress->delineation_informasi_verifikasi = "0";
            $case_close_progress->delineation_informasi_validation = "0";
            $case_close_progress->delineation_skenario_relasi = "0";
            $case_close_progress->delineation_laporan = "0";
            $case_close_progress->exploration_rencana_aksi = "0";
            $case_close_progress->exploration_identitas_target = "0";
            $case_close_progress->exploration_hasil_yang_dicapai = "0";
            $case_close_progress->exploration_laporan = "0";
            $case_close_progress->tailing_pemahaman_perilaku = "0";
            $case_close_progress->tailing_target_operasi = "0";
            $case_close_progress->tailing_hasil_yang_dicapai = "0";
            $case_close_progress->tailing_laporan = "0";
            $case_close_progress->infiltration_operasi_rahasia = "0";
            $case_close_progress->infiltration_dinamika_target = "0";
            $case_close_progress->infiltration_hasil_yang_dicapai = "0";
            $case_close_progress->infiltration_laporan = "0";
            $case_close_progress->intrusion_lokasi_target = "0";
            $case_close_progress->intrusion_lingkungan_target = "0";
            $case_close_progress->intrusion_hasil_yang_dicapai = "0";
            $case_close_progress->intrusion_laporan = "0";
            $case_close_progress->tapping_data_penyelidikan_komunikasi_elektronik = "0";
            $case_close_progress->tapping_data_sinyal_intelijen = "0";
            $case_close_progress->tapping_hasil_penyadapan = "0";
            $case_close_progress->tapping_laporan = "0";
            $case_close_progress->status = "Penambahan Kasus";
            $case_close_progress->substatus = "Penambahan Kasus";
            $case_close_progress->open_method_percentage = "0";
            $case_close_progress->close_method_percentage = "0";
            $case_close_progress->percentage = "0";

            $case_close_progress->created_by = $user->id;
            $case_close_progress->updated_by = $user->id;

            if($case_close_progress->save()){
                return response()->json([
                    "status" => Response::HTTP_OK,
                    "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                    "message" => 'Data berhasil disimpan',
                    "data" => $closeCaseSave,
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

    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $datas = CloseCase::with(["satker","progress", "caseCloseEventHistoricalUpdate"])
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('close_case.id_satker', '=', $user->id_satker)
                              ->orWhere('master_satker.parent_id', '=', $user->id_satker);
                        }) ->orderby('close_case.created_at','DESC')->paginate(10);
        
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

    public function show(string $id)
    {
        //
        $data = CloseCase::with(["satker","progress", "caseCloseEventHistoricalUpdate"])->find($id);

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

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_kasus' => 'required',
            // 'tanggal_kasus' => 'required|date',
            // 'deskripsi_kasus' => 'required',

            'nama_target' => 'required',
            // 'agama' => 'required',
            // 'pendidikan' => 'required',
            // 'pekerjaan' => 'required',
            // 'alamat' => 'required',
            'image' => 'array',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $existingData = CloseCase::find($id);
        if (!$existingData) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // create folder name, based on satker name
        $satker = MasterSatker::where('master_satker.id_satker', $existingData->satker_id)
        ->select('master_satker.nama_satker')
        ->first();

        if($satker == null){
            return redirect()->back()->with('error', 'Satker Tidak Ditemukan!');
        }

        $folderName = strtolower(trim($satker->nama_satker));
        $folderName = str_replace(" ","_", $folderName);
        $folderPath = public_path('close_case_target_image/'. $folderName);

        $newImages = [];
        if ($request->file('image') != null) {
            // Remove existing images
            if ($existingData->foto) {
                $existingImagePaths = json_decode($existingData->foto);
    
                foreach ($existingImagePaths as $existingImagePath) {
                    if (file_exists($folderPath . '/' . $existingImagePath)) {
                        unlink($folderPath . '/' . $existingImagePath);
                    }
                }
            }
            // Save new images
            $index = 1;
            foreach ($request->file('image') as $image) {
                $filename = time(). ' - '. $request->nama_target .' - '. $index . '.'. $image->getClientOriginalExtension();
                $image->move($folderPath, $filename);
                $newImages[] = $filename;
                $index++;
            }
        } else{
            $newImages = json_decode($existingData->target_photo);
        }

        $user = Auth::guard('api')->user();
        
        $data = CloseCase::findOrFail($id);

        $data->update([
            // 'satker_id' => $satker->id_satker,
            'case_name' => $request->nama_kasus,
            'case_date' => $request->tanggal_kasus,
                'case_description' => $request->deskripsi_kasus,

                'target_name' => $request->nama_target,
                'target_identity_number_type' => $request->tipe_identitas,
                'target_identity_number' => $request->no_identitas,
                'target_religion' => $request->agama,
                'target_education' => $request->pendidikan,
                'target_gender'=> $request->jenis_kelamin,
                'target_occupation' => $request->pekerjaan,
                'target_address' => $request->alamat,
                'target_photo' => json_encode($newImages),


            'created_by' => $user->id,
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

    public function delete(string $id)
    {
        $data = CloseCase::find($id);
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
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

}
