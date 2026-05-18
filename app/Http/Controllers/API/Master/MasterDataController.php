<?php

namespace App\Http\Controllers\API\Master;

use Carbon\Carbon;
use App\Models\OpenCase;
use App\Models\CloseCase;
use App\Models\MasterSatker;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ElicitationAdFoll;
use App\Models\InterogationRecord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ElicitationInterview;
use Illuminate\Support\Facades\Auth;
use App\Models\Observation\ObservDirective;
use App\Models\Observation\ObservCollectInfo;
use App\Models\Observation\ObservThreat;

use Symfony\Component\HttpFoundation\Response;
use App\Models\InterogationTargetIdentification;
use App\Models\Open\Research\ResearchSuratPerintah;
use App\Models\Open\Research\ResearchSaranTindakLanjut;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;
use App\Models\Interview\InterviewJadwal;
use App\Models\Interview\InterviewHasil;

use App\Models\Delineation\DelineationInformationVerification;
use App\Models\Delineation\DelineationInformationValidation;

use App\Models\ExplorationRencanaAksi;
use App\Models\ExplorationTargetIdentity;

use App\Models\Tailing\TailingPemahamanPerilaku;
use App\Models\Tailing\TailingTargetOperasi;

use App\Models\Infiltration\InfiltrationSecretOperation;
use App\Models\Infiltration\InfiltrationTargetDynamics;

use App\Models\Intrusion\IntrusionTargetLoc;
use App\Models\Intrusion\IntrusionTargetEnv;

use App\Models\Tapping\TappingElectronicDevice;
use App\Models\Tapping\TappingIntelligentSignal;







class MasterDataController extends Controller
{

    public static function getSatker()
    {
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        $satker = MasterSatker::where('nama_satker', 'ILIKE', "%" . request()->term . "%")
            // ->when($role, function ($query, $role) {
            //     if ($role == 'superadmin') {
            //         return $query->where('id_satker', auth()->user()->id_satker);
            //     }
            // })
            ->orderBy('id_satker', 'asc')
            ->get();

        $dataSatker = $satker->map(function ($item, $key) {
            $data['id'] = $item->id_satker;
            $data['text'] = $item->nama_satker;
            $data['kode_satker'] = $item->kode_satker;

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $dataSatker,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    // OPEN CASE
    public static function getOpenCase()
    {
        // $case = OpenCase::where('id_satker', Auth::guard('api')->user()->id_satker)->get();

        // $data_case = $case->map(function ($item, $key) {
        //     $data['id'] = $item->id;
        //     $data['text'] = $item->nama_kasus;

        //     return $data;
        // });
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        $case = OpenCase::join('case_progresses', 'open_case.id', 'case_progresses.case_id')
            ->where('case_progresses.percentage', '!=', '100')
            // ->where('id_satker', Auth::guard('api')->user()->id_satker)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('id_satker', auth()->user()->id_satker);
                }
            })
            ->select('open_case.*')
            ->get();

        $data_case = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = 'Kasus ' . $item->nama_kasus;

            return $data;
        });


        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_case,
            'timestamp' => floor(microtime(true) * 1000)
        ]);


    }

    public static function getSuratPerintahbyCaseId(Request $request)
    {
        $caseId = $request->query('caseId');
        if (!$caseId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = ResearchSuratPerintah::leftJoin('open_case', 'open_case.id', '=', 'research_surat_perintah.case_id')
            ->where('research_surat_perintah.case_id', $caseId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('open_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('research_surat_perintah.*', 'open_case.nama_kasus')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id_surat_perintah,
                'text' => $item->nama_kasus . ' - ' . $item->surat_perintah_number,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getLapinsusbySuratPerintahId(Request $request)
    {
        $suratPerintahId = $request->query('suratPerintahId');
        if (!$suratPerintahId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = ResearchLaporanInformasiKhusus::leftJoin('open_case', 'open_case.id', '=', DB::raw("research_laporan_informasi_khusus.case_id::uuid"))
            ->where(DB::raw("research_laporan_informasi_khusus.surat_perintah_id::uuid"), $suratPerintahId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('open_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('research_laporan_informasi_khusus.*', 'open_case.nama_kasus')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nomor_surat,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getSaranTLbyLapinsusId(Request $request)
    {
        $lapinsusId = $request->query('lapinsusId');
        if (!$lapinsusId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = ResearchSaranTindakLanjut::leftJoin('open_case', 'open_case.id', '=', DB::raw("research_saran_dan_tindak_lanjut.case_id::uuid"))
            ->where(DB::raw("research_saran_dan_tindak_lanjut.laporan_informasi_khusus_id::uuid"), $lapinsusId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('open_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('research_saran_dan_tindak_lanjut.*', 'open_case.nama_kasus')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id_saran_dan_tindak_lanjut,
                'text' => Str::limit(strip_tags($item->saran_dan_tindak_lanjut), 128),
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getJadwalWawancarabyCaseId(Request $request)
    {
        $caseId = $request->query('caseId');
        if (!$caseId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = InterviewJadwal::leftJoin('open_case', 'open_case.id', '=', 'interview_jadwal.case_id')
            ->where('interview_jadwal.case_id', $caseId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('open_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('interview_jadwal.*', 'open_case.nama_kasus')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id_interview_scheduler,
                'text' => $item->interviewer_name . ' - ' . $item->source_person_name,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getHasilWawancarabyJadwalWawancaraId(Request $request)
    {
        $jadwalWawancaraId = $request->query('jadwalWawancaraId');
        if (!$jadwalWawancaraId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = InterviewHasil::leftJoin('open_case', 'open_case.id', '=', 'interview_hasil.case_id')
            ->where('interview_hasil.interview_scheduler_id', $jadwalWawancaraId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('open_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('interview_hasil.*', 'open_case.nama_kasus')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id_interview_result,
                'text' => Str::limit(strip_tags($item->keterangan), 128) .' - '.Carbon::parse($item->created_at)->translatedFormat('d F Y'),
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getBeritaAcarabyCaseId(Request $request)
    {
        $caseId = $request->query('caseId');
        if (!$caseId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = InterogationRecord::leftJoin('open_case', 'open_case.id', '=', 'interrogation_berita_acara.case_id')
            ->where('interrogation_berita_acara.case_id', $caseId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('open_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('interrogation_berita_acara.*', 'open_case.nama_kasus')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id_interogation_record,
                'text' => $item->letter_number . ' - ' . $item->target_name,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getIdentifikasiTargetbyBeritaAcaraId(Request $request)
    {
        $beritaAcaraId = $request->query('beritaAcaraId');
        if (!$beritaAcaraId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = InterogationTargetIdentification::leftJoin('open_case', 'open_case.id', '=', 'interrogation_identifikasi_target.case_id')
            ->where('interrogation_identifikasi_target.interogation_record_id', $beritaAcaraId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('open_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('interrogation_identifikasi_target.*', 'open_case.nama_kasus')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id_interview_result,
                'text' => Str::limit(strip_tags($item->hasil_target_identification), 128) .' - '.Carbon::parse($item->created_at)->translatedFormat('d F Y'),
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }
    
    public static function getHasilWawancaraElicitationbyCaseId(Request $request)
    {
        $caseId = $request->query('caseId');
        if (!$caseId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = ElicitationInterview::leftJoin('open_case', 'open_case.id', '=', 'elicitation_hasil_wawancara.case_id')
            ->where('elicitation_hasil_wawancara.case_id', $caseId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('open_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('elicitation_hasil_wawancara.*', 'open_case.nama_kasus')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id_elicitation_interview_result,
                'text' => $item->interviewer_name . ' - ' . $item->source_person_name,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getElicitationSaranTLbyElicitationInterviewId(Request $request)
    {
        $elicitationInterviewId = $request->query('elicitationInterviewId');
        if (!$elicitationInterviewId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = ElicitationAdFoll::leftJoin('open_case', 'open_case.id', '=', 'elicitation_saran_dan_tindak_lanjut.case_id')
            ->where('elicitation_saran_dan_tindak_lanjut.elicitation_hasil_wawancara_id', $elicitationInterviewId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('open_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('elicitation_saran_dan_tindak_lanjut.*', 'open_case.nama_kasus')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id_interview_result,
                'text' => Str::limit(strip_tags($item->saran_dan_tindak_lanjut), 128) .' - '.Carbon::parse($item->created_at)->translatedFormat('d F Y'),
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }


    public static function getCloseCase()
    {
        // $case = OpenCase::where('id_satker', Auth::guard('api')->user()->id_satker)->get();

        // $data_case = $case->map(function ($item, $key) {
        //     $data['id'] = $item->id;
        //     $data['text'] = $item->nama_kasus;

        //     return $data;
        // });
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        $case = CloseCase::join('case_close_progresses', 'close_case.id', 'case_close_progresses.case_id')
            ->where('case_close_progresses.percentage', '!=', '100')
            // ->where('id_satker', Auth::guard('api')->user()->id_satker)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('id_satker', auth()->user()->id_satker);
                }
            })
            ->select('close_case.*')
            ->get();

        $data_case = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = 'Kasus ' . $item->case_name;

            return $data;
        });


        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_case,
            'timestamp' => floor(microtime(true) * 1000)
        ]);


    }

    public static function getSuratPerintahClosebyCaseId(Request $request)
    {
        $caseId = $request->query('caseId');
        if (!$caseId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = ObservDirective::leftJoin('close_case', 'close_case.id', '=', 'observation_surat_perintah.case_id')
            ->where('observation_surat_perintah.case_id', $caseId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('observation_surat_perintah.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->surat_perintah_number ,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getSumberInformasibySuratPerintahId(Request $request)
    {
        $suratPerintahId = $request->query('suratPerintahId');
        if (!$suratPerintahId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = ObservCollectInfo::leftJoin('close_case', 'close_case.id', '=', 'observation_information_collection.case_id')
            ->where('observation_information_collection.surat_perintah_id', $suratPerintahId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('observation_information_collection.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => Str::limit(strip_tags($item->information_collection_perihal), 128) .' - '.Carbon::parse($item->information_collection_date)->translatedFormat('d F Y'),
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getAGHTbySumberInformasiId(Request $request)
    {
        $sumberInformasiId = $request->query('sumberInformasiId');
        if (!$sumberInformasiId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = ObservThreat::leftJoin('close_case', 'close_case.id', '=', 'observation_potensi_aght.case_id')
            ->where('observation_potensi_aght.information_collection_id', $sumberInformasiId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('observation_potensi_aght.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => Str::limit(strip_tags($item->aght_type), 128) .' - '.Str::limit(strip_tags($item->keterangan)),
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getInformationVerificationbyInformationCollectionId(Request $request)
    {
        $sumberInformasiId = $request->query('sumberInformasiId');
        if (!$sumberInformasiId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = DelineationInformationVerification::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(delineation_information_verification.case_id AS UUID)'))
        ->where('delineation_information_verification.information_collection_id', $sumberInformasiId)
        ->when($role, function ($query, $role) {
            if ($role != 'superadmin') {
                return $query->where('close_case.id_satker', auth()->user()->id_satker);
            }
        })
        ->select('delineation_information_verification.*', 'close_case.case_name')
        ->get();
    
        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => Str::limit(strip_tags($item->kredibilitas_sumber), 128) .' - '.Str::limit(strip_tags($item->detail_informasi_verifikasi)),
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getInformationValidationbyInformationVerificationId(Request $request)
    {
        $informationVerificationId = $request->query('informationVerificationId');
        if (!$informationVerificationId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = DelineationInformationValidation::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(delineation_information_validation.case_id AS UUID)'))
        ->where('delineation_information_validation.information_verification_id', $informationVerificationId)
        ->when($role, function ($query, $role) {
            if ($role != 'superadmin') {
                return $query->where('close_case.id_satker', auth()->user()->id_satker);
            }
        })
        ->select('delineation_information_validation.*', 'close_case.case_name')
        ->get();
    
        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => Str::limit(strip_tags($item->metode_validasi), 128) .' - '.Str::limit(strip_tags($item->hasil_validasi)),
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getRencanaAksibyCaseId(Request $request)
    {
        $caseId = $request->query('caseId');
        if (!$caseId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = ExplorationRencanaAksi::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(exploration_rencana_aksi.case_id AS UUID)'))
            ->where('exploration_rencana_aksi.case_id', $caseId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('exploration_rencana_aksi.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id_exploration_rencana_aksi,
                'text' => Str::limit(strip_tags($item->rencana_aksi_data), 128) . ' - ' . Str::limit(strip_tags($item->rencana_aksi_detail), 128) ,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    
    public static function getIdentitasTargetbyRencanaAksiId(Request $request)
    {
        $rencanaAksiId = $request->query('rencanaAksiId');
        if (!$rencanaAksiId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = ExplorationTargetIdentity::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(exploration_target_identitas.case_id AS UUID)'))
            ->where('exploration_target_identitas.exploration_rencana_aksi_id', $rencanaAksiId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('exploration_target_identitas.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id_exploration_target_identity,
                'text' => Str::limit(strip_tags($item->target_name), 128) . ' - ' . Str::limit(strip_tags($item->target_identity_number), 128) ,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getTailingPemahamanPerilakubyCaseId(Request $request)
    {
        $caseId = $request->query('caseId');
        if (!$caseId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = TailingPemahamanPerilaku::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(tailing_pemahaman_perilaku.case_id AS UUID)'))
            ->where('tailing_pemahaman_perilaku.case_id', $caseId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('tailing_pemahaman_perilaku.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => Str::limit(strip_tags($item->target_name), 128) . ' - ' . Str::limit(strip_tags($item->perilaku_tercatat), 128) ,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getTailingTargetOperasibyTailingPemahamanPerilakuId(Request $request)
    {
        $tailingPemahamanPerilakuId = $request->query('tailingPemahamanPerilakuId');
        if (!$tailingPemahamanPerilakuId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = TailingTargetOperasi::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(tailing_target_operasi.case_id AS UUID)'))
            ->where('tailing_target_operasi.tailing_pemahaman_perilaku_id', $tailingPemahamanPerilakuId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('tailing_target_operasi.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => Str::limit(strip_tags($item->rencana_target_operasi), 128) . ' - ' . Str::limit(strip_tags($item->target_operasi), 128) ,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getPenyusupanOperasiRahasiabyCaseId(Request $request)
    {
        $caseId = $request->query('caseId');
        if (!$caseId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = InfiltrationSecretOperation::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(infiltration_operasi_rahasia.case_id AS UUID)'))
            ->where('infiltration_operasi_rahasia.case_id', $caseId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('infiltration_operasi_rahasia.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => Str::limit(strip_tags($item->nama_operasi_rahasia), 128) . ' - '.Carbon::parse($item->tanggal_operasi_rahasia)->translatedFormat('d F Y') ,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }
    
    public static function getPenyusupanDinamikaTargetbyPenyusupanOperasiRahasiaId(Request $request)
    {
        $penyusupanOperasiRahasiaId = $request->query('penyusupanOperasiRahasiaId');
        if (!$penyusupanOperasiRahasiaId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = InfiltrationTargetDynamics::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(infiltration_dinamika_target.case_id AS UUID)'))
            ->where('infiltration_dinamika_target.infiltration_operasi_rahasia_id', $penyusupanOperasiRahasiaId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('infiltration_dinamika_target.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => Str::limit(strip_tags($item->dinamika_teramati), 128) . ' - ' . Str::limit(strip_tags($item->tanggal_dinamika_teramati), 128) ,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getIntrusionLokasiTargetbyCaseId(Request $request)
    {
        $caseId = $request->query('caseId');
        if (!$caseId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = IntrusionTargetLoc::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(intrusion_target_lokasi.case_id AS UUID)'))
            ->where('intrusion_target_lokasi.case_id', $caseId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('intrusion_target_lokasi.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => Str::limit(strip_tags($item->lokasi_target), 128) . ' - '.Str::limit(strip_tags($item->target_name)),
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }


    public static function getIntrusionLingkunganTargetbyIntrusionLokasiId(Request $request)
    {
        $intursionLocationId = $request->query('intursionLocationId');
        if (!$intursionLocationId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = IntrusionTargetEnv::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(intrusion_lingkungan_target.case_id AS UUID)'))
            ->where('intrusion_lingkungan_target.intrusion_target_location_id', $intursionLocationId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('intrusion_lingkungan_target.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => Str::limit(strip_tags($item->nama_lingkungan), 128) . ' - ' . Str::limit(strip_tags($item->deskripsi_lingkungan), 128) ,
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getTappingDataElectronicbyCaseId(Request $request)
    {
        $caseId = $request->query('caseId');
        if (!$caseId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = TappingElectronicDevice::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(tapping_electronic_device_2.case_id AS UUID)'))
            ->where('tapping_electronic_device_2.case_id', $caseId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('tapping_electronic_device_2.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id_tapping_electronic_device,
                'text' => Str::limit(strip_tags($item->metode_penyadapan), 128) . ' - '.Str::limit(strip_tags($item->sumber_data)),
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getTappingSinyalDatabyTappingDataElectronicId(Request $request)
    {
        $tappingDataElectronicId = $request->query('tappingDataElectronicId');
        if (!$tappingDataElectronicId) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
        // Mengambil peran pengguna (menggunakan auth()->user() atau Auth::guard() sesuai kebutuhan)
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        // Melakukan join antara ResearchSuratPerintah dan OpenCase
        $case = TappingIntelligentSignal::leftJoin('close_case', 'close_case.id', '=', DB::raw('CAST(tapping_intelligent_signal.case_id AS UUID)'))
            ->where('tapping_intelligent_signal.tapping_electronic_device_data_id', $tappingDataElectronicId)
            ->when($role, function ($query, $role) {
                if ($role != 'superadmin') {
                    return $query->where('close_case.id_satker', auth()->user()->id_satker);
                }
            })
            ->select('tapping_intelligent_signal.*', 'close_case.case_name')
            ->get();

        // Memeriksa apakah ada data yang ditemukan
        if ($case->isEmpty()) {
            return response()->json([
                "status" => Response::HTTP_NOT_FOUND,
                "statusMessage" => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                "message" => 'Data not found',
                "data" => [],
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }

        // Mapping data_sprint
        $data_sprint = $case->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => Str::limit(strip_tags($item->jenis_sinyal), 128) . ' - ' . Str::limit(strip_tags($item->deskripsi_hasil), 128) . ' - ' . Carbon::parse($item->tanggal_penyadapan)->translatedFormat('d F Y'),
            ];
        });

        // Mengembalikan response dengan data yang telah dimapping
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }















    // open case for interogation record
    public static function getCaseValidInterogationRecord()
    {
        $case = OpenCase::leftJoin('case_progresses', 'open_case.id', '=', 'case_progresses.case_id')
            ->where('case_progresses.wawancara_saran_dan_tindak_lanjut', '1')
            ->where('open_case.id_satker', Auth::guard('api')->user()->id_satker)
            ->select('open_case.id as id', 'open_case.nama_kasus')
            ->get();

        $data_case = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = $item->nama_kasus;

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_case,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getSprint()
    {
        $case = ResearchSuratPerintah::whereRelation('case.satker', 'kode_satker', Auth::guard('api')->user()->satker->kode_satker)
            ->get();

        $data_sprint = $case->map(function ($item, $key) {
            $data['id'] = $item->id_surat_perintah;
            $data['text'] = $item->case->nama_kasus . ' - ' . $item->surat_perintah_number;

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_sprint,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getInterogrecord()
    {
        $role = strtolower(Auth::guard('api')->user()->roles->first()->name);
        $user = Auth::guard('api')->user();

        $interogrecord = InterogationRecord::when($role != 'superadmin', function ($q) use ($user) {
            $q->where('satker_id', $user->id_satker);
        })
            ->get();

        $data_interogrecord = $interogrecord->map(function ($item, $key) {
            $data['id_interogation_record'] = $item->id_interogation_record;
            $data['target_name'] = $item->target_name;

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_interogrecord,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getInterogTarget()
    {
        $role = strtolower(Auth::guard('api')->user()->roles->first()->name);
        $user = Auth::guard('api')->user();

        $interogtarget = InterogationTargetIdentification::when($role != 'superadmin', function ($q) use ($user) {
            $q->where('satker_id', $user->id_satker);
        })
            ->get();

        $data_interogtarget = $interogtarget->map(function ($item, $key) {
            $data['id_interogation_target_identification'] = $item->id_interogation_target_identification;
            $data['hasil_target_identification'] = $item->hasil_target_identification;

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_interogtarget,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getLapinsus()
    {
        $case = ResearchLaporanInformasiKhusus::whereRelation('researchSuratPerintah.case.satker', 'kode_satker', Auth::guard('api')->user()->satker->kode_satker)
            ->get();

        $data_lik = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = $item->researchSuratPerintah->case->nama_kasus . ' - ' . $item->nomor_surat;

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_lik,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getElicitationInterview()
    {
        $user = Auth::guard('api')->user();
        $elinterview = ElicitationInterview::where('elicitation_hasil_wawancara.satker_id', $user->id_satker)->get();

        $data_elinterview = $elinterview->map(function ($item, $key) {
            $data['id_elicitation_interview_result'] = $item->id_elicitation_interview_result;
            $data['interviewer_name'] = $item->interviewer_name;

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_elinterview,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getSaranTl()
    {
        $case = ResearchSaranTindakLanjut::whereRelation(
            'researchLaporanInformasiKhusus.researchSuratPerintah.case.satker',
            'kode_satker',
            Auth::guard('api')->user()->satker->kode_satker
        )
            ->get();

        $data_saran = $case->map(function ($item, $key) {
            $data['id'] = $item->id_saran_dan_tindak_lanjut;
            $data['text'] = '(' . $item->researchLaporanInformasiKhusus->researchSuratPerintah->case->satker->nama_satker . ')' .
                ' ' . $item->researchLaporanInformasiKhusus->researchSuratPerintah->case->nama_kasus . ' - ' .
                Str::limit(strip_tags($item->saran_dan_tindak_lanjut), 128);

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_saran,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    public static function getElicitationAdfoll()
    {
        $user = Auth::guard('api')->user();
        $elintAdfoll = ElicitationAdFoll::where('elicitation_saran_dan_tindak_lanjut.satker_id', $user->id_satker)->get();
        $data_elintadfoll = $elintAdfoll->map(function ($item, $key) {
            $data['id_elicitation_saran_dan_tindak_lanjut'] = $item->id_elicitation_saran_dan_tindak_lanjut;
            $data['saran_dan_tindak_lanjut_date'] = $item->saran_dan_tindak_lanjut_date;
            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data_elintadfoll,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }


    // CLOSE CASE HELPER
    // public static function getCloseCase()
    // {
    //     if (request()->query('satker_id')) {
    //         $case = CloseCase::where('satker_id', request()->query('satker_id'))->get();
    //     } else {
    //         $case = CloseCase::where('satker_id', Auth::guard('api')->user()->id_satker)->get();
    //     }


    //     $data_case = $case->map(function ($item, $key) {
    //         $data['id'] = $item->id;
    //         $data['text'] = $item->case_name . " - Tgl. Kasus " . $item->case_date->isoFormat('DD MMMM YYYY');

    //         return $data;
    //     });

    //     return response()->json([
    //         "status" => Response::HTTP_OK,
    //         "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
    //         "message" => 'Get Data',
    //         "data" => $data_case,
    //         'timestamp' => floor(microtime(true) * 1000)
    //     ]);
    // }

    public static function getCloseSprint($case_id = null)
    {
        $case_id = request()->query('case_id') ?? $case_id;

        if (!$case_id) {
            return [];
        } else {
            $sprint = ObservDirective::where('case_id', $case_id)->get();
            $data_sprint = $sprint->map(function ($item, $key) {
                $data['id'] = $item->id;
                $data['text'] = $item->surat_perintah_number . " - " . $item->surat_perintah_date?->isoFormat('YYYY-MM-DD');
                return $data;
            });

            return response()->json([
                "status" => Response::HTTP_OK,
                "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
                "message" => 'Get Data',
                "data" => $data_sprint,
                'timestamp' => floor(microtime(true) * 1000)
            ]);
        }
    }

    public static function getCloseCaseByObservDirective()
    {
        if (Auth::guard('api')->user()->user_roles == "superadmin") {
            $case = CloseCase::
                join('observation_surat_perintah', 'close_case.id', 'observation_surat_perintah.case_id')
                ->select('close_case.id', 'close_case.case_name')
                ->distinct('close_case.case_name', 'close_case.id')
                ->orderBy('close_case.case_name')
                ->get();
        } else {
            $case = CloseCase::
                join('observation_surat_perintah', 'close_case.id', 'observation_surat_perintah.case_id')
                ->select('close_case.id', 'close_case.case_name')
                ->distinct('close_case.case_name', 'close_case.id')
                ->orderBy('close_case.case_name')
                ->where('close_case.satker_id', Auth::guard('api')->user()->satker->kode_satker)
                ->get();
        }

        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    // case sudah input collect info
    public static function getCloseCaseByObservCollectInfo()
    {
        if (Auth::guard('api')->user()->user_roles == "superadmin") {
            $case = CloseCase::
                join('observation_information_collection', 'close_case.id', 'observation_information_collection.case_id')
                ->select('close_case.id', 'close_case.case_name')
                ->distinct('close_case.case_name', 'close_case.id')
                ->orderBy('close_case.case_name')
                ->get();
        } else {
            $case = CloseCase::
                join('observation_information_collection', 'close_case.id', 'observation_information_collection.case_id')
                ->select('close_case.id', 'close_case.case_name')
                ->distinct('close_case.case_name', 'close_case.id')
                ->orderBy('close_case.case_name')
                ->where('close_case.satker_id', Auth::guard('api')->user()->satker->kode_satker)
                ->get();
        }

        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    // case sudah input threat
    public static function getCloseCaseByObservThreat()
    {
        if (Auth::guard('api')->user()->user_roles == "superadmin") {
            $case = CloseCase::
                join('observation_potensi_aght', 'close_case.id', 'observation_potensi_aght.case_id')
                ->select('close_case.id', 'close_case.case_name')
                ->distinct('close_case.case_name', 'close_case.id')
                ->orderBy('close_case.case_name')
                ->get();
        } else {
            $case = CloseCase::
                join('observation_potensi_aght', 'close_case.id', 'observation_potensi_aght.case_id')
                ->select('close_case.id', 'close_case.case_name')
                ->distinct('close_case.case_name', 'close_case.id')
                ->orderBy('close_case.case_name')
                ->where('close_case.satker_id', Auth::guard('api')->user()->satker->kode_satker)
                ->get();
        }

        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

    // case sudah input connected identity
    public static function getCloseCaseByObservConnect()
    {
        if (Auth::guard('api')->user()->user_roles == "superadmin") {
            $case = CloseCase::
                join('observation_connected_identity', 'close_case.id', 'observation_connected_identity.case_id')
                ->select('close_case.id', 'close_case.case_name')
                ->distinct('close_case.case_name', 'close_case.id')
                ->orderBy('close_case.case_name')
                ->get();
        } else {
            $case = CloseCase::
                join('observation_connected_identity', 'close_case.id', 'observation_connected_identity.case_id')
                ->select('close_case.id', 'close_case.case_name')
                ->distinct('close_case.case_name', 'close_case.id')
                ->orderBy('close_case.case_name')
                ->where('close_case.satker_id', Auth::guard('api')->user()->satker->kode_satker)
                ->get();
        }

        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data',
            "data" => $data,
            'timestamp' => floor(microtime(true) * 1000)
        ]);
    }

}
