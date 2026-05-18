<?php

namespace App\Http\Controllers\API\Open\Interview;

use Illuminate\Support\Facades\Auth;
use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\CaseProgresses;
use App\Models\Interview\InterviewHasil;
use App\Models\Interview\InterviewJadwal;
use App\Models\Interview\InterviewSaranTL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class InterviewJadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $caseId = $request->get('case_id');

        if ($caseId) {
            return response()->json(InterviewJadwal::where('case_id', $caseId)->paginate(10));
        }

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get data berhasil',
            "data" => InterviewJadwal::paginate(10),
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            // 'user_id' => 'required|string|max:128',
            // 'case_id' => 'required|string|max:128',
            // 'interviewer_name' => 'required|string|max:128',
            // 'interviewer_schedule' => 'required|date',
            // 'source_person_name' => 'required|string|max:128',
            // 'target_identity_number' => 'required|string|max:128',
            // 'target_type_identity_number' => 'required|string|max:128',
            // 'target_gender' => 'required|string|max:128',
            // 'target_religion' => 'required|string|max:128',
            // 'target_occupation' => 'required|string|max:128',
            // 'target_education' => 'required|string|max:128',
            // 'target_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('api')->user();

        $data = new InterviewJadwal;
        $data->case_id = $request->case_id;
        $data->interviewer_name = $request->interviewer_name;
        $data->interviewer_schedule = $request->interviewer_schedule;
        $data->source_person_name = $request->source_person_name;
        $data->target_identity_number = $request->target_identity_number;
        $data->target_type_identity_number = $request->target_type_identity_number;
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_occupation = $request->target_occupation;
        $data->target_education = $request->target_education;

        if ($request->hasFile('target_photo')) {
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'open/interview/jadwal/target_photo',
                    Str::slug('interview jadwal', '_') . '_' . Str::random() . '.' . $ext_target_photo,
                    'public'
                );

            $data->target_photo = $target_photo;
        }

        $data->created_by = $request->user_id;
        $data->updated_by = $request->user_id;

        if ($request->submit_type === 'save') {
            if ($data->save()) {
                $op = CaseProgresses::where('case_id', $data->case_id)->first();
                $op->wawancara_jadwal = 1;
                $op->status = $op->percentage > 47.04 ? $op->status : "Wawancara";
                $op->substatus = $op->percentage > 47.04 ? $op->substatus : "Input Wawancara";
                $op->percentage = $op->percentage > 47.04 ? $op->percentage : 47.04;
                $op->updated_by = $user->id;
                $op->save();
    
                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->case_id;
                $cp->action = 'Penambahan Wawancara Jadwal';
                $cp->created_by = $user->id;
                $cp->save();
    
                // if ($request->hasFile('target_photo')) {
                //     DataHelper::insertDocument($data->id_interview_scheduler, $data->target_photo, null, $user->id);
                // }
    
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
                $updateCaseProgresses->wawancara_jadwal = 1;
                $updateCaseProgresses->status = 'Wawancara';
                $updateCaseProgresses->substatus = 'Penambahan Wawancara';
                $updateCaseProgresses->percentage = 100;
                $updateCaseProgresses->save();

                $cp = new CaseEventHistoricalUpdates;
                $cp->case_id = $data->case_id;
                $cp->action = 'Penambahan Wawancara Jadwal';
                $cp->created_by = $user->id;
                $cp->save();

                // if ($request->hasFile('target_photo')) {
                //     DataHelper::insertDocument($data->id_interview_scheduler, $data->target_photo, null, $user->id);
                // }
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
        //     $op->wawancara_jadwal = 1;
        //     $op->status = $op->percentage > 47.04 ? $op->status : "Wawancara";
        //     $op->substatus = $op->percentage > 47.04 ? $op->substatus : "Input Jadwal Wawancara";
        //     $op->percentage = $op->percentage > 47.04 ? $op->percentage : 47.04;
        //     $op->updated_by = $request->user_id;
        //     $op->save();

        //     $cp = new CaseEventHistoricalUpdates;
        //     $cp->case_id = $data->case_id;
        //     $cp->action = 'Penambahan Wawancara Jadwal';
        //     $cp->created_by = $request->user_id;
        //     $cp->save();

        //     if ($request->hasFile('target_photo')) {
        //         DataHelper::insertDocument($data->id_interview_scheduler, $data->target_photo, null, $request->user_id);
        //     }

        //     return response()->json([
        //         "status" => Response::HTTP_OK,
        //         "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
        //         "message" => 'Data berhasil disimpan',
        //         "data" => $data,
        //         'timestamp' => floor(microtime(true) * 1000)
        //     ]);
        // }

        // if ($data->target_photo && Storage::disk('public')->exists($data->target_photo)) {
        //     Storage::disk('public')->delete($data->target_photo);
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
        // $data = InterviewJadwal::with('case')->first();
        $data = InterviewJadwal::with('case')->find($id);
        if (!$data) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data tidak ditemukan.',
                "data" => null,
                'timestamp' => floor(microtime(true) * 1000)
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get data berhasil',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ], Response::HTTP_OK);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            
            // 'case_id' => 'required|string|max:128',
            // 'interviewer_name' => 'required|string|max:128',
            // 'interviewer_schedule' => 'required|date',
            // 'source_person_name' => 'required|string|max:128',
            // 'target_identity_number' => 'required|string|max:128',
            // 'target_type_identity_number' => 'required|string|max:128',
            // 'target_gender' => 'required|string|max:128',
            // 'target_religion' => 'required|string|max:128',
            // 'target_occupation' => 'required|string|max:128',
            // 'target_education' => 'required|string|max:128',
            // 'target_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = InterviewJadwal::find($id);
        $data->case_id = $request->case_id;
        $data->interviewer_name = $request->interviewer_name;
        $data->interviewer_schedule = $request->interviewer_schedule;
        $data->source_person_name = $request->source_person_name;
        $data->target_identity_number = $request->target_identity_number;
        $data->target_type_identity_number = $request->target_type_identity_number;
        $data->target_gender = $request->target_gender;
        $data->target_religion = $request->target_religion;
        $data->target_occupation = $request->target_occupation;
        $data->target_education = $request->target_education;

        if ($request->hasFile('target_photo')) {
            $ext_target_photo = $request->file('target_photo')->extension();
            $target_photo = $request->file('target_photo')
                ->storePubliclyAs(
                    'open/interview/jadwal/target_photo',
                    Str::slug('interview jadwal', '_') . '_' . Str::random() . '.' . $ext_target_photo,
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

        $data->updated_by = $request->user_id;

        if ($request->submit_type === 'update_and_finish') {
       
            $updateCaseProgresses = CaseProgresses::where('case_id', $request->id_case)->first();
            $updateCaseProgresses->wawancara_jadwal = 1;
            $updateCaseProgresses->status = 'Wawancara';
            $updateCaseProgresses->substatus = 'Update Wawancara';
            $updateCaseProgresses->percentage = 100;
            $updateCaseProgresses->save();
        }
        
        if ($data->update()) {
            $cp = CaseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
            $cp->action = 'Perubahan Wawancara Jadwal';
            $cp->updated_by = $request->user_id;
            $cp->update();

            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Data berhasil disimpan',
                "data" => $data,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        if ($data->target_photo && Storage::disk('public')->exists($data->target_photo)) {
            Storage::disk('public')->delete($data->target_photo);
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
        $data = InterviewJadwal::find($id);

        if ($data) {
            if ($data->target_photo && Storage::disk('public')->exists($data->target_photo)) {
                Storage::disk('public')->delete($data->target_photo);

                $data->target_photo = null;
                $data->update();
            }

            $cp = CaseEventHistoricalUpdates::where('case_id', $data->case_id)->first();
            $cp->action = 'Penghapusan Wawancara Jadwal';
            $cp->updated_by = $data->created_by;
            $cp->update();

            $data->delete();
            InterviewHasil::where('interview_scheduler_id', $id)->delete();
            InterviewSaranTL::where('interview_schedule_id', $id)->delete();

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
