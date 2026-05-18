<?php

namespace App\Http\Controllers\API\Open\Research;

use App\Http\Controllers\Controller;
use App\Models\Open\Research\ResearchPotensiAghtLampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\json;

class ResearchPotensiAghtLampiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $request->validate([
            'id_potensi_aght' => 'required|string|max:255'
        ]);

        $caseId = $request->get('id_potensi_aght');

        return response()->json(ResearchPotensiAghtLampiran::where('id_potensi_aght', $caseId)->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'user_id' => 'required|string|max:255',
            'id_potensi_aght' => 'required|string|max:255',
            'url_lampiran' => 'required|file|mimes:pdf|max:2048'
        ]);

        $data = new ResearchPotensiAghtLampiran;
        $data->id_potensi_aght = $request->id_potensi_aght;
        $data->url_lampiran = $request->url_lampiran;

        if ($request->hasFile('url_lampiran')) {
            $ext_url_lampiran = $request->file('url_lampiran')->extension();
            $url_lampiran = $request->file('url_lampiran')
                ->storePubliclyAs(
                    'open/research/potensi_aght_lampiran',
                    Str::slug('research potensi aght lampiran', '_') . '_' . Str::random() . '.' . $ext_url_lampiran,
                    'public'
                );

            $data->url_lampiran = $url_lampiran;
        }

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

        if ($data->url_lampiran && Storage::disk('public')->exists($data->url_lampiran)) {
            Storage::disk('public')->delete($data->url_lampiran);
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
        $data = ResearchPotensiAghtLampiran::with([
                'researchPotensiAght',
                'researchPotensiAght.researchSaranTindakLanjut', 
                'researchPotensiAght.researchSaranTindakLanjut.researchLaporanInformasiKhusus', 
                'researchPotensiAght.researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah', 
                'researchPotensiAght.researchSaranTindakLanjut.researchLaporanInformasiKhusus.researchSuratPerintah.case'
            ])
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
            'id_potensi_aght' => 'required|string|max:255',
            'url_lampiran' => 'required|file|mimes:pdf|max:2048'
        ]);

        $data = ResearchPotensiAghtLampiran::find($id);
        $data->id_potensi_aght = $request->id_potensi_aght;
        $data->url_lampiran = $request->url_lampiran;

        if ($request->hasFile('url_lampiran')) {
            $ext_url_lampiran = $request->file('url_lampiran')->extension();
            $url_lampiran = $request->file('url_lampiran')
                ->storePubliclyAs(
                    'open/research/potensi_aght_lampiran',
                    Str::slug('research potensi aght lampiran', '_') . '_' . Str::random() . '.' . $ext_url_lampiran,
                    'public'
                );

            if ($request->temp_url_lampiran && Storage::disk('public')->exists($request->temp_url_lampiran)) {
                Storage::disk('public')->delete($request->temp_url_lampiran);
            }

            $data->url_lampiran = $url_lampiran;
        } else {
            $url_lampiran = $request->temp_url_lampiran;

            $data->url_lampiran = $url_lampiran;
        }

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

        if ($data->url_lampiran && Storage::disk('public')->exists($data->url_lampiran)) {
            Storage::disk('public')->delete($data->url_lampiran);
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
        $data = ResearchPotensiAghtLampiran::find($id);

        if ($data) {
            if ($data->url_lampiran && Storage::disk('public')->exists($data->url_lampiran)) {
                Storage::disk('public')->delete($data->url_lampiran);

                $data->url_lampiran = null;
                $data->update();
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

        return response()->json([
            "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "statusMessage" => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            "message" => 'Data gagal dihapus',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }
}
