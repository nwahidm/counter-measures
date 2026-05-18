<?php

namespace App\Helpers;

use App\Models\OpenCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Open\Research\ResearchSuratPerintah;
use App\Models\Open\Research\ResearchSaranTindakLanjut;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;

class ResearchDataHelper
{
    public static function getSprintPerCase($case_id = null)
    {
        $case_id = request()->query('case_id') ?? $case_id;
        $sprint = OpenCase::join('research_surat_perintah', 'research_surat_perintah.case_id', 'open_case.id')
                        ->where('open_case.id_satker', auth()->user()->satker->id_satker)
                        ->where('open_case.id', $case_id)
                        ->select('research_surat_perintah.*', 'open_case.nama_kasus')
                        ->get();

        $data_sprint = $sprint->map(function ($item, $key) {
            $data['id'] = $item->id_surat_perintah;
            $data['text'] = 'Kasus ' . $item->nama_kasus . ' - No. Sprint: ' . $item->surat_perintah_number;

            return $data;
        });

        return collect($data_sprint);
    }

    public static function getLapinsusPerSprint($id_sprint = null)
    {
        $user = auth()->user();
        $idSatker = $user->satker?->id_satker;
        $id_sprint = request()->query('id_sprint') ?? $id_sprint;
        $sprint = ResearchLaporanInformasiKhusus::where('surat_perintah_id', $id_sprint)
                        ->with('researchSuratPerintah.case')
                        ->when(
                            !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
                            function ($q) use ($idSatker) {
                                $q->where('satker_id', $idSatker);
                            }
                        )
                        ->get();

        $data_sprint = $sprint->map(function ($item, $key) {
            $data['id'] = $item->id;

            $data['text'] = 'Kasus ' . $item->case->nama_kasus . ' - No. Sprint: ' . 
                            ($item->researchSuratPerintah->surat_perintah_number ?? 'No Sprint Number') . 
                            ' - No. Lapinsus: ' . $item->nomor_surat;

            return $data;
        });

        return collect($data_sprint);
    }

    public static function getSuratPerintahByCase($case_id=null){
        $case_id = request()->query('case_id') ?? $case_id;
        $sprint = ResearchSuratPerintah::where('case_id', $case_id)->get();

        $data_sprint = $sprint->map(function ($item, $key) {
            $data['id'] = $item->id_surat_perintah;

            $data['text'] = 'Kasus ' . $item->case->nama_kasus . ' - No. Sprint: ' . 
                            ($item->surat_perintah_number ?? 'No Sprint Number');

            return $data;
        });
        return collect($data_sprint);

    }

    public static function getSuratPerintahByCaseAPI($case_id=null){
        $case_id = request()->query('case_id') ?? $case_id;
        $sprint = ResearchSuratPerintah::where('case_id', $case_id)->get();

        $data_sprint = $sprint->map(function ($item, $key) {
            $data['id'] = $item->id_surat_perintah;

            $data['text'] = 'Kasus ' . $item->case->nama_kasus . ' - No. Sprint: ' . 
                            ($item->surat_perintah_number ?? 'No Sprint Number');

            return $data;
        });
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);

    }

    public static function getLapinsusBySuratPerintah($surat_perintah_id=null){
        $user = auth()->user();
        $idSatker = $user->satker?->id_satker;
        $surat_perintah_id = request()->query('id_sprint') ?? $surat_perintah_id ?? request()->query('surat_perintah_id');
        $lapinsus = ResearchLaporanInformasiKhusus::where('surat_perintah_id', $surat_perintah_id)->when(
            !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
            function ($q) use ($idSatker) {
                $q->where('satker_id', $idSatker);
            }
        )->get();

        $data_lapinsus = $lapinsus->map(function ($item, $key) {
            $data['id'] = $item->id;

            $data['text'] =  'No. Sprint: ' . 
                            ($item->researchSuratPerintah->surat_perintah_number ?? 'Sprint Number Tidak Ada').
                            ' - Lapinsus: '.($item->nomor_surat ?? 'No Lapinsus Tidak Ada');

            return $data;
        });
        return collect($data_lapinsus);

    }

    public static function getLapinsusBySuratPerintahAPI($surat_perintah_id=null){
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker?->id_satker;
        $surat_perintah_id = request()->query('surat_perintah_id') ?? $surat_perintah_id;
        $lapinsus = ResearchLaporanInformasiKhusus::where('surat_perintah_id', $surat_perintah_id)->when(
            !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
            function ($q) use ($idSatker) {
                $q->where('satker_id', $idSatker);
            }
        )->get();

        $data_lapinsus = $lapinsus->map(function ($item, $key) {
            $data['id'] = $item->id;

            $data['text'] =  'No. Sprint: ' . 
                            ($item->researchSuratPerintah->surat_perintah_number ?? 'Sprint Number Tidak Ada').
                            ' - Lapinsus: '.($item->nomor_surat ?? 'No Lapinsus Tidak Ada');

            return $data;
        });
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $data_lapinsus,
            'timestamp' => floor(microtime(true) * 1000)
        ]);

    }

    public static function getSaranTinjutByLapinsus($lapinsus_id=null){
        $user = auth()->user();
        $idSatker = $user->satker?->id_satker;
        $lapinsus_id = request()->query('lapinsus_id') ?? $lapinsus_id;
        $saran_tinjut = ResearchSaranTindakLanjut::where('laporan_informasi_khusus_id', $lapinsus_id)->when(
            !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
            function ($q) use ($idSatker) {
                $q->where('satker_id', $idSatker);
            }
        )->get();

        $data_saran_tinjut= $saran_tinjut->map(function ($item, $key) {
            $data['id'] = $item->id_saran_dan_tindak_lanjut;

            $data['text'] = 'Lapinsus: ' . 
                ($item->researchLaporanInformasiKhusus->nomor_surat ?? 'Lapinsus Tidak Ada') .
                ' - Saran Tinjut: ' . Str::limit(strip_tags($item->saran_dan_tindak_lanjut), 128, '') ?? 'No Saran Tinjut Tidak Ada';

            return $data;
        });
        return collect($data_saran_tinjut);

    }

    public static function getSaranTinjutByLapinsusAPI($lapinsus_id=null){
        $user = Auth::guard('api')->user();
        $idSatker = $user->satker?->id_satker;
        $lapinsus_id = request()->query('lapinsus_id') ?? $lapinsus_id;
        $saran_tinjut = ResearchSaranTindakLanjut::where('laporan_informasi_khusus_id', $lapinsus_id)->when(
            !$user->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum']),
            function ($q) use ($idSatker) {
                $q->where('satker_id', $idSatker);
            }
        )->get();

        $data_saran_tinjut= $saran_tinjut->map(function ($item, $key) {
            $data['id'] = $item->id_saran_dan_tindak_lanjut;

            $data['text'] = 'Lapinsus: ' . 
                ($item->researchLaporanInformasiKhusus->nomor_surat ?? 'Lapinsus Tidak Ada') .
                ' - Saran Tinjut: ' . Str::limit(strip_tags($item->saran_dan_tindak_lanjut), 128, '') ?? 'No Saran Tinjut Tidak Ada';

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Berhasil get data',
            "data" => $data_saran_tinjut,
            'timestamp' => floor(microtime(true) * 1000)
        ]);

    }
}