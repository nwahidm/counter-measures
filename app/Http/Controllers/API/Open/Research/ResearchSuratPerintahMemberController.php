<?php

namespace App\Http\Controllers\API\Open\Research;

use App\Http\Controllers\Controller;
use App\Models\Open\Research\ResearchSuratPerintahMember;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResearchSuratPerintahMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $request->validate([
            'surat_perintah_id' => 'required|string|max:255'
        ]);

        $caseId = $request->get('surat_perintah_id');

        return response()->json(ResearchSuratPerintahMember::where('surat_perintah_id', $caseId)->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'user_id' => 'required|string|max:255',
            'surat_perintah_id' => 'required|string|max:255',
            'member_id' => 'required|string|max:255',
        ]);

        $data = new ResearchSuratPerintahMember;
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->member_id = $request->member_id;

        $data->created_by = $request->user_id;
        $data->updated_by = $request->user_id;

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
            "message" => 'Data gagal disimpan',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = ResearchSuratPerintahMember::with(['researchSuratPerintah', 'researchSuratPerintah.case'])
            ->where('id_surat_perintah_member', $id)
            ->first();

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'user_id' => 'required|string|max:255',
            'surat_perintah_id' => 'required|string|max:255',
            'member_id' => 'required|string|max:255',
        ]);

        $data = ResearchSuratPerintahMember::find($id);
        $data->surat_perintah_id = $request->surat_perintah_id;
        $data->member_id = $request->member_id;

        $data->updated_by = $request->user_id;

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = ResearchSuratPerintahMember::find($id);

        if ($data) {
            $data->delete();

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
        ]);
    }
}
