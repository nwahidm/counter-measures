<?php

namespace App\Http\Controllers\API\Open\Research;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\Documents;
use App\Models\Open\Research\ResearchSuratPerintah;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;
use App\Models\Open\Research\ResearchPotensiAght;
use App\Models\Open\Research\ResearchSaranTindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ResearchSuratPerintahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $datas = ResearchSuratPerintah::with(["case","case.satker","case.caseProgress", "case.caseEventHistoricalUpdates"])
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('open_case.id_satker', '=', $user->id_satker)
                              ->orWhere('master_satker.parent_id', '=', $user->id_satker);
                        }) ->orderby('research_surat_perintah.created_at','DESC')->paginate(10);
        
        
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
        //
        $this->validate($request, [
            'case_id' => 'required|string|max:128',
            'satker_id' => 'required|string|max:128',
            'surat_perintah_number' => 'required|string|max:128',
            'surat_perintah_perihal' => 'required|string|max:100000'
        ]);

        $user = Auth::guard('api')->user();
        $data = new ResearchSuratPerintah;
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->surat_perintah_number = $request->surat_perintah_number;
        $data->surat_perintah_perihal = $request->surat_perintah_perihal;
        $data->surat_perintah_date = $request->surat_perintah_date;
        $data->surat_perintah_date_started = $request->surat_perintah_date_started;
        $data->surat_perintah_date_finished = $request->surat_perintah_date_finished;

        // if ($request->hasFile('surat_perintah_path')) {
        //     $ext_upload_sprint = $request->file('surat_perintah_path')->extension();
        //     $upload_sprint = $request->file('surat_perintah_path')
        //         ->storePubliclyAs(
        //             'open/research/warrant/surat_perintah_path',
        //             Str::slug('penelitian surat perintah', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
        //             'public'
        //         );

        //     $data->surat_perintah_path = $upload_sprint;
        // }

        if ($request->surat_perintah_path) {
            $base64Document = $request->surat_perintah_path;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('penelitian surat perintah', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'open/research/warrant/surat_perintah_path/' . $fileName;

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->surat_perintah_path = $uploadPath;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        if ($request->submit_type === 'save') {

            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $data->case_id)->first();
                $op->penelitian_upload_surat_perintah = 1;
                $op->status = $op->percentage > 5.88 ? $op->status : "Penelitian";
                $op->substatus = $op->percentage > 5.88 ? $op->substatus : "Input Surat Perintah Penelitian";
                $op->percentage = $op->percentage > 5.88 ? $op->percentage : 5.88;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->case_id;
                $cp->action = 'Penambahan Penelitian Surat Perintah';
                $cp->created_by = $user->id;
                $cp->save();
    
                if ($request->surat_perintah_path) {
                    DataHelper::insertDocument($data->id_surat_perintah, $data->surat_perintah_path);
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
                "message" => 'Data gagal disimpan',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        }else{
            if ($data->save()) {
                $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
                $updateCaseProgresses->penelitian_upload_surat_perintah = 1;
                $updateCaseProgresses->status = 'Penelitian';
                $updateCaseProgresses->substatus = 'Penambahan Surat Perintah Penelitian Dan Selesai';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->case_id;
                $cp->action = 'Penambahan Penelitian Surat Perintah';
                $cp->created_by = $user->id;
                $cp->save();
                
                if ($request->surat_perintah_path) {
                    DataHelper::insertDocument($data->id_surat_perintah, $data->surat_perintah_path);
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
                "message" => 'Data gagal disimpan',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        

        // if ($data->save()) {
        //     $op = CaseProgresses::where('case_id', $data->case_id)->first();
        //     $op->penelitian_upload_surat_perintah = 1;
        //     $op->status = $op->percentage > 5.88 ? $op->status : "Penelitian";
        //     $op->substatus = $op->percentage > 5.88 ? $op->substatus : "Input Surat Perintah Penelitian";
        //     $op->percentage = $op->percentage > 5.88 ? $op->percentage : 5.88;
        //     $op->updated_by = $request->user_id;
        //     $op->save();

        //     $cp = new CaseEventHistoricalUpdates;
        //     $cp->case_id = $data->case_id;
        //     $cp->action = 'Penambahan Penelitian Surat Perintah';
        //     $cp->created_by = $request->user_id;
        //     $cp->save();

        //     if ($request->hasFile('surat_perintah_path')) {
        //         DataHelper::insertDocument($data->id_surat_perintah, $data->surat_perintah_path, null, $request->user_id);
        //     }

        //     return response()->json([
        //         "status" => Response::HTTP_OK,
        //         "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
        //         "message" => 'Data berhasil disimpan',
        //         "data" => $data,
        //         'timestamp' => floor(microtime(true) * 1000)
        //     ]);
        // }

        // if ($data->surat_perintah_path && Storage::disk('public')->exists($data->surat_perintah_path)) {
        //     Storage::disk('public')->delete($data->surat_perintah_path);
        // }

        // return response()->json([
        //     "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
        //     "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
        //     "message" => 'Data gagal disimpan',
        //     "data" => $data,
        //     'timestamp' => floor(microtime(true) * 1000)
        // ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        $user = Auth::guard('api')->user();
        $datas = ResearchSuratPerintah::with(["case","case.satker","case.caseProgress", "case.caseEventHistoricalUpdates"])
        ->where('id_surat_perintah', $id)
        ->firstOrFail();
        
        
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $datas,
            'timestamp' => floor(microtime(true) * 1000)
        ]);


       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $this->validate($request, [
            'case_id' => 'required|string|max:128',
            'satker_id' => 'required|string|max:128',
            'surat_perintah_number' => 'required|string|max:128',
            'surat_perintah_perihal' => 'required|string|max:100000'
        ]);
        $user = Auth::guard('api')->user();

        $data = ResearchSuratPerintah::find($id);
        $data->satker_id = $request->satker_id;
        $data->case_id = $request->case_id;
        $data->surat_perintah_number = $request->surat_perintah_number;
        $data->surat_perintah_perihal = $request->surat_perintah_perihal;
        $data->surat_perintah_date = $request->surat_perintah_date;
        $data->surat_perintah_date_started = $request->surat_perintah_date_started;
        $data->surat_perintah_date_finished = $request->surat_perintah_date_finished;


        // if ($request->hasFile('surat_perintah_path')) {
        //     $ext_surat_perintah_path = $request->file('surat_perintah_path')->extension();
        //     $surat_perintah_path = $request->file('surat_perintah_path')
        //         ->storePubliclyAs(
        //             'open/research/warrant/surat_perintah_path',
        //             Str::slug('penelitian surat perintah', '_') . '_' . Str::random() . '.' . $ext_surat_perintah_path,
        //             'public'
        //         );

        //     if ($data->surat_perintah_path && Storage::disk('public')->exists($request->old_surat_perintah_path)) {
        //         Storage::disk('public')->delete($request->old_surat_perintah_path);
        //     }

        //     $data->surat_perintah_path = $surat_perintah_path;
        // } 

        if ($request->surat_perintah_path) {
            $base64Document = $request->surat_perintah_path;

            $decodedDocument = base64_decode($base64Document);
            $fileName = Str::slug('penelitian surat perintah', '_') . '_' . Str::random() . '.pdf';
            $uploadPath = 'open/research/warrant/surat_perintah_path/' . $fileName;

            if ($data->surat_perintah_path && Storage::disk('public')->exists($request->surat_perintah_path)) {
                Storage::disk('public')->delete($request->surat_perintah_path);
            }

            // Store the document
            Storage::disk('public')->put($uploadPath, $decodedDocument);
            $data->surat_perintah_path = $uploadPath;
        }

        $data->updated_by = $request->user_id;

        if ($request->submit_type === 'update_and_finish') {       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->penelitian_upload_surat_perintah = 1;
            $updateCaseProgresses->status = 'Penelitian';
            $updateCaseProgresses->substatus = 'Penambahan Surat Perintah Penelitian dan Selesai';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }

        if ($data->update()) {
            if ($request->surat_perintah_path) {
                DataHelper::insertDocument($data->id_surat_perintah, $data->surat_perintah_path, $request->old_surat_perintah_path, $request->user_id);
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
            "message" => 'Data gagal disimpan',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = ResearchSuratPerintah::find($id);

        if ($data) {
            if ($data->surat_perintah_path && Storage::disk('public')->exists($data->surat_perintah_path)) {
                Storage::disk('public')->delete($data->surat_perintah_path);

                Documents::where('relation_id', $data->id_surat_perintah)
                    ->where('doc_path', $data->surat_perintah_path)
                    ->delete();

                $data->surat_perintah_path = null;
                $data->update();
            }

            $cp = CaseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
            $cp->action = "Penghapusan Penelitian Surat Perintah";
            $cp->updated_by = $data->created_by;
            $cp->update();

            $data->delete();
            ResearchLaporanInformasiKhusus::where('surat_perintah_id', $id)->delete();
            ResearchSaranTindakLanjut::where('surat_perintah_id', $id)->delete();
            ResearchPotensiAght::where('id_sprint', $id)->delete();

            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Data berhasil dihapus',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        return response()->json([
            "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            "message" => 'Data gagal dihapus',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
