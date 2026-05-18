<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\OpenCase;
use App\Models\CloseCase;
use App\Models\Documents;
use App\Models\MasterDesa;
use App\Models\MasterKota;
use App\Models\MasterMenu;
use App\Models\QuickCount;
use App\Models\LogWorkflow;
use App\Models\MasterAgama;
use Illuminate\Support\Str;
use App\Models\MasterBidang;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use App\Models\MasterPegawai;
use App\Models\MasterWilayah;
use App\Models\MasterProvinsi;
use App\Models\VideoDocuments;
use Illuminate\Support\Carbon;
use App\Models\MasterKecamatan;
use App\Models\MasterPekerjaan;
use App\Models\QuickCountLevel;
use App\Models\MasterPendidikan;
use App\Models\ElicitationAdFoll;
use App\Models\InterogationRecord;
use App\Models\MasterJenisKelamin;
use Illuminate\Support\Facades\DB;
use App\Models\ElicitationInterview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use App\Models\Research\ResearchSprint;
use Illuminate\Support\Facades\Storage;
use App\Models\Interview\InterviewHasil;

use App\Models\Observation\ObservThreat;
use App\Models\Research\ResearchSaranTL;
use App\Models\Interview\InterviewJadwal;

use App\Models\Research\ResearchLapinsus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Models\CaseEventHistoricalUpdates;
use App\Models\Observation\ObservDirective;
use App\Models\Intrusion\IntrusionTargetEnv;
use App\Models\Intrusion\IntrusionTargetLoc;
use App\Models\Observation\ObservCollectInfo;
use App\Models\Tapping\TappingElectronicDevice;
use App\Models\InterogationTargetIdentification;
use App\Models\Tapping\TappingIntelligentSignal;
use App\Models\Open\Research\ResearchSuratPerintah;
use App\Models\Open\Research\ResearchSaranTindakLanjut;
use App\Models\Infiltration\InfiltrationSecretOperation;
use App\Models\Delineation\DelineationInformationValidation;
use App\Models\Open\Research\ResearchLaporanInformasiKhusus;
use App\Models\Delineation\DelineationInformationVerification;
use App\Models\ExplorationRencanaAksi;
use App\Models\ExplorationTargetIdentity;

class DataHelper
{

    public static function listProvinsi($code = null)
    {
        $data = MasterProvinsi::when($code, function ($q) use ($code) {
            $q->where('kode', $code);
        })
            ->select('id', 'kode as value', 'nama as text')
            ->get();

        return $data;
    }

    public static function listKotaQuickCount($code = null, $provinsiId = null)
    {
        /* return QuickCount::leftJoin('master_wilayah', 'quick_count.kode_kab_kota', '=', 'master_wilayah.kode')
            ->select('master_wilayah.kode as id', 'master_wilayah.nama as text')
            ->take(10)
            ->get(); */

        $data = QuickCount::when($code, function ($q) use ($code) {
            $q->where('kode_kab_kota', $code);
        })
            ->when($provinsiId, function ($q) use ($provinsiId) {
                $q->where('kode_provinsi', $provinsiId);
            })
            ->join('master_wilayah', 'quick_count.kode_kab_kota', '=', 'master_wilayah.kode')
            ->select('master_wilayah.nama as text', 'master_wilayah.kode as id')
            ->groupBy('master_wilayah.nama', 'master_wilayah.kode')
            ->get();

        return $data;
    }

    public static function listKecamatanQuickCount($provinsiId = null, $kotaId = null)
    {
        $data = QuickCount::when($provinsiId, function ($q) use ($provinsiId) {
            $q->where('kode_provinsi', $provinsiId);
        })
            ->when($kotaId, function ($q) use ($kotaId) {
                $q->where('kode_kab_kota', $kotaId);
            })
            ->join('master_wilayah', 'quick_count.kode_kecamatan', '=', 'master_wilayah.kode')
            ->select('master_wilayah.nama as text', 'master_wilayah.kode as id')
            ->groupBy('master_wilayah.nama', 'master_wilayah.kode')
            ->get();


        return $data;
    }

    public static function listKelurahanQuickCount($provinsiId = null, $kotaId = null, $kecamatanId = null)
    {
        $data = QuickCount::when($provinsiId, function ($q) use ($provinsiId) {
            $q->where('kode_provinsi', $provinsiId);
        })
            ->when($kotaId, function ($q) use ($kotaId) {
                $q->where('kode_kab_kota', $kotaId);
            })
            ->when($kecamatanId, function ($q) use ($kecamatanId) {
                $q->where('kode_kecamatan', $kecamatanId);
            })
            ->join('master_wilayah', 'quick_count.kode_kelurahan', '=', 'master_wilayah.kode')
            ->select('master_wilayah.nama as text', 'master_wilayah.kode as id')
            ->groupBy('master_wilayah.nama', 'master_wilayah.kode')
            ->get();

        return $data;
    }

    public static function listTpsQuickCount($provinsiId = null, $kotaId = null, $kecamatanId = null, $kelurahanId = null)
    {
        $data = QuickCount::when($provinsiId, function ($q) use ($provinsiId) {
            $q->where('kode_provinsi', $provinsiId);
        })
            ->when($kotaId, function ($q) use ($kotaId) {
                $q->where('kode_kab_kota', $kotaId);
            })
            ->when($kecamatanId, function ($q) use ($kecamatanId) {
                $q->where('kode_kecamatan', $kecamatanId);
            })
            ->when($kelurahanId, function ($q) use ($kelurahanId) {
                $q->where('kode_kelurahan', $kelurahanId);
            })
            ->select(DB::raw('DISTINCT quick_count.nama_tps as nama_tps'), DB::raw('quick_count.nama_tps::int as nomor'))
            ->orderBy('nomor')
            ->get();

        return $data;
    }


    public static function listSuaraQuickCount($provinsiId = null, $kotaId = null, $kecamatanId = null, $kelurahanId = null, $tpsId = null)
    {
        $data = QuickCount::when($provinsiId, function ($q) use ($provinsiId) {
            $q->where('kode_provinsi', $provinsiId);
        })
            ->when($kotaId, function ($q) use ($kotaId) {
                $q->where('kode_kab_kota', $kotaId);
            })
            ->when($kecamatanId, function ($q) use ($kecamatanId) {
                $q->where('kode_kecamatan', $kecamatanId);
            })
            ->when($kelurahanId, function ($q) use ($kelurahanId) {
                $q->where('kode_kelurahan', $kelurahanId);
            })
            ->when($tpsId, function ($q) use ($tpsId) {
                $q->where('nama_tps', $tpsId);
            })
            ->select('quick_count.suara_anis as paslon_1', 'quick_count.suara_prabowo as paslon_2', 'quick_count.suara_ganjar as paslon_3')
            ->latest()
            ->first();
        return $data;
    }

    public static function listSuaraQuickCountLevel($provinsiId = null, $kotaId = null, $kecamatanId = null, $kelurahanId = null)
    {
        $data = QuickCountLevel::when($provinsiId, function ($q) use ($provinsiId) {
            $q->where('kode_provinsi', $provinsiId);
        })
            ->when($kotaId, function ($q) use ($kotaId) {
                $q->where('kode_kab_kota', $kotaId);
            })
            ->when($kecamatanId, function ($q) use ($kecamatanId) {
                $q->where('kode_kecamatan', $kecamatanId);
            })
            ->when($kelurahanId, function ($q) use ($kelurahanId) {
                $q->where('kode_kelurahan', $kelurahanId);
            })
            ->select('quick_count_level.suara_anis as paslon_1', 'quick_count_level.suara_prabowo as paslon_2', 'quick_count_level.suara_ganjar as paslon_3')
            ->latest()
            ->first();
        return $data;
    }

    public static function listSuaraQuickCountLevelKec($provinsiId = null, $kotaId = null, $kecamatanId = null)
    {
        $data = QuickCountLevel::when($provinsiId, function ($q) use ($provinsiId) {
            $q->where('kode_provinsi', $provinsiId);
        })
            ->when($kotaId, function ($q) use ($kotaId) {
                $q->where('kode_kab_kota', $kotaId);
            })
            ->when($kecamatanId, function ($q) use ($kecamatanId) {
                $q->where('kode_kecamatan', $kecamatanId);
            })
            ->select('quick_count_level.suara_anis as paslon_1', 'quick_count_level.suara_prabowo as paslon_2', 'quick_count_level.suara_ganjar as paslon_3')
            ->latest()
            ->first();

        return $data;
    }

    public static function listKota($code = null, $provinsiId = null)
    {
        $data = MasterKota::when($code, function ($q) use ($code) {
            $q->where('kode', $code);
        })
            ->when($provinsiId, function ($q) use ($provinsiId) {
                $q->where('provinsi_id', $provinsiId);
            })
            ->select('kota_id as id', 'id as id_kota', 'kode as value', 'nama as text')
            ->get();

        return $data;
    }


    public static function listKecamatan($provinsiId = null, $kotaId = null)
    {
        $data = MasterKecamatan::when($provinsiId, function ($q) use ($provinsiId) {
            $q->where('provinsi_id', $provinsiId);
        })
            ->when($kotaId, function ($q) use ($kotaId) {
                $q->where('kota_id', $kotaId);
            })
            ->select('kecamatan_id as id', 'id as id_kecamatan', 'kode as value', 'nama as text')
            ->get();

        return $data;
    }

    public static function listDesa($provinsiId = null, $kotaId = null, $kecamatanId = null)
    {
        $data = MasterDesa::when($provinsiId, function ($q) use ($provinsiId) {
            $q->where('provinsi_id', $provinsiId);
        })
            ->when($kotaId, function ($q) use ($kotaId) {
                $q->where('kota_id', $kotaId);
            })
            ->when($kecamatanId, function ($q) use ($kecamatanId) {
                $q->where('kecamatan_id', $kecamatanId);
            })
            ->select('id as id', 'kode as value', 'nama as text')
            ->get();

        return $data;
    }

    public static function tipeSatker()
    {
        return [
            '1' => 'Kejaksaan Agung',
            '2' => 'Kejaksaan Tinggi',
            '3' => 'Kejaksaan Negeri',
            '4' => 'Cabang Kejaksaan Negeri'
        ];
    }

    public static function listWilayah($kodeSatker, $needChild, $level)
    {
        if (auth()->user()->hasRole('superadmin')) {
            return MasterWilayah::join('wilayah_satker', 'master_wilayah.id_wilayah', '=', 'wilayah_satker.id_wilayah')
                ->join('master_satker', 'master_satker.id_satker', 'wilayah_satker.id_satker')
                ->select('master_wilayah.id_wilayah', 'master_wilayah.nama')
                ->get();
        }

        $resp = MasterWilayah::join('wilayah_satker', 'master_wilayah.id_wilayah', '=', 'wilayah_satker.id_wilayah')
            ->join('master_satker', 'master_satker.id_satker', 'wilayah_satker.id_satker')
            ->when($needChild, function ($q) use ($needChild, $level, $kodeSatker) {
                if ($needChild == 1) {
                    $q->where('kode_satker', 'like', "$kodeSatker%")
                        ->whereIn('tipe_satker', $level);
                } else {
                    $q->where('master_satker.kode_satker', $kodeSatker);
                }
            })
            ->select('master_wilayah.id_wilayah', 'master_wilayah.nama')
            ->get();

        return $resp;
    }

    public static function listSatker($kodeSatker, $needChild, $level)
    {
        if (auth()->user()->hasRole('superadmin')) {
            return MasterSatker::select('master_satker.id_satker', 'master_satker.kode_satker', 'master_satker.nama_satker')
                ->orderBy('tipe_satker')
                ->get();
        }

        $resp = MasterSatker::when($needChild, function ($q) use ($needChild, $level, $kodeSatker) {
            if ($needChild == 1) {
                $q->where('kode_satker', 'like', "$kodeSatker%")
                    ->whereIn('tipe_satker', $level);
            } else {
                $q->where('master_satker.kode_satker', $kodeSatker);
            }
        })
            ->orderBy('tipe_satker')
            ->select('master_satker.id_satker', 'master_satker.kode_satker', 'master_satker.nama_satker')
            ->get();

        return $resp;
    }

    public static function jenisAGHT()
    {
        return [
            'ANCAMAN' => 'ANCAMAN',
            'GANGGUAN' => 'GANGGUAN',
            'HAMBATAN' => 'HAMBATAN',
            'TANTANGAN' => 'TANTANGAN'
        ];
    }

    public static function tahapLogistik()
    {
        return [
            'TAHAP 1' => 'TAHAP 1',
            'TAHAP 2' => 'TAHAP 2'
        ];
    }

    public static function createWorkflow($type, $userId, $refId, $actionAt, $status, $description = null, $oldValue = null, $newValue = null)
    {
        $userInfo = User::where('id', $userId)->first();
        $roleInfo = $userInfo?->roles?->first()?->name;
        $satkerInfo = $userInfo->satker;

        LogWorkflow::create([
            'jenis' => $type,
            'ref_id' => $refId,
            'action_at' => $actionAt,
            'actor_user_id' => $userInfo?->id,
            'actor_role' => $roleInfo,
            'actor_name' => $userInfo?->name,
            'actor_id_satker' => $satkerInfo?->id_satker,
            'actor_kode_satker' => $satkerInfo?->kode_satker,
            'actor_nama_satker' => $satkerInfo?->nama_satker,
            'status' => $status,
            'description' => $description,
            'old_value' => $oldValue,
            'new_value' => $newValue
        ]);
    }

    public static function jenisStatusAGHT()
    {
        return [
            'Laporan Masuk' => 'Laporan Masuk',
            'Tindak Lanjut' => 'Tindak Lanjut',
            'Penyelidikan' => 'Penyelidikan',
            'Penyidikan' => 'Penyidikan',
            'Proses Hukum' => 'Proses Hukum',
            'Tidak Valid' => 'Tidak Valid',
            'Mediasi' => 'Mediasi',
            'Laporan Selesai' => 'Laporan Selesai'
        ];
    }

    public static function jenisStatusJalanDaerah()
    {
        return [
            'TERKONTRAK' => 'TERKONTRAK'
        ];
    }

    public static function jenisMetodeJalanDaerah()
    {
        return [
            'E-PURCHASING' => 'E-PURCHASING'
        ];
    }

    public static function jenisStatusProgresJalanDaerah()
    {
        return [
            'SELESAI' => 'SELESAI',
            'BELUM SELESAI' => 'BELUM SELESAI'
        ];
    }

    public static function jenisStatusLogistik()
    {
        return [
            'SESUAI' => 'SESUAI',
            'BELUM SESUAI' => 'BELUM SESUAI'
        ];
    }


    //function untuk menu data desa
    public static function masterProvinsi($code = null)
    {
        $data = MasterProvinsi::when($code, function ($q) use ($code) {
            $q->where('kode', $code);
        })
            ->select('id', 'kode as value', 'nama as text')
            ->get();

        return $data;
    }

    public static function masterKota($code = null, $provinsiId = null)
    {
        $data = MasterKota::when($code, function ($q) use ($code) {
            $q->where('kode', $code);
        })
            ->when($provinsiId, function ($q) use ($provinsiId) {
                $q->where('provinsi_id', $provinsiId);
            })
            ->select('id', 'kode as value', 'nama as text')
            ->get();

        return $data;
    }

    public static function masterKecamatan($provinsiId = null, $kotaId = null)
    {
        $data = MasterKecamatan::when($provinsiId, function ($q) use ($provinsiId) {
            $q->where('provinsi_id', $provinsiId);
        })
            ->when($kotaId, function ($q) use ($kotaId) {
                $q->where('kota_id', $kotaId);
            })
            ->select('id', 'kode as value', 'nama as text')
            ->get();

        return $data;
    }

    public static function masterDesa($provinsiId = null, $kotaId = null, $kecamatanId = null)
    {
        $data = MasterDesa::when($provinsiId, function ($q) use ($provinsiId) {
            $q->where('provinsi_id', $provinsiId);
        })
            ->when($kotaId, function ($q) use ($kotaId) {
                $q->where('kota_id', $kotaId);
            })
            ->when($kecamatanId, function ($q) use ($kecamatanId) {
                $q->where('kecamatan_id', $kecamatanId);
            })
            ->select('id', 'kode as value', 'nama as text')
            ->get();

        return $data;
    }

    //end function data desa

    public static function userList($tipe_satker, $kode_satker)
    {
        $data = User::with('satker', 'satker.satkerInduk');

        if ($tipe_satker) {
            $data->whereHas('satker', function (Builder $query) use ($tipe_satker, $kode_satker) {
                if ($kode_satker) {
                    $query->where('kode_satker', $kode_satker);
                }
                if ($tipe_satker) {
                    $query->where('tipe_satker', $tipe_satker);
                }
            });
        }

        return response()->json($data->get());
    }

    public static function getParentMenu($group = null)
    {
        $data = MasterMenu::when($group, function ($q, $group) {
            $q->where('group', $group);
        })
            ->where([
                'parent_id' => null,
                'is_active' => true
            ])
            ->select('id', 'group', 'name', 'description', 'route_name', 'route_url', 'asset')
            ->get();

        return $data;
    }

    public static function getChildMenu($parentId = null)
    {
        $data = MasterMenu::when($parentId, function ($q, $parentId) {
            $q->where('parent_id', $parentId);
        })
            ->where('is_active', true)
            ->select('id', 'group', 'name', 'description', 'route_name', 'route_url', 'asset')
            ->orderBy('id', 'asc')
            ->get();

        return $data;
    }

    // get satker list, used on create page
    public static function getSatker()
    {
        $role = strtolower(auth()->user() ? auth()->user()->roles->first()->name : Auth::guard('api')->user()->roles->first()->name);

        $satker = MasterSatker::where('nama_satker', 'ILIKE', "%" . request()->term . "%")
            ->when($role, function ($query, $role) {
                if ($role == 'admin') {
                    return $query->where('id_satker', auth()->user()->id_satker);
                }
            })
            ->orderBy('id_satker', 'asc')
            ->get();

        $dataSatker = $satker->map(function ($item, $key) {
            $data['id'] = $item->id_satker;
            $data['text'] = $item->nama_satker;
            $data['kode_satker'] = $item->kode_satker;

            return $data;
        });

        return collect($dataSatker);
    }

    public static function getSatkerGlobal()
    {

        $satker = MasterSatker::when(!auth()->user()->hasRole('superadmin'), function($q){
            return $q->where('id_satker', auth()->user()->satker->id_satker);
        })
            ->orderBy('id_satker', 'asc')
            ->get();

        $dataSatker = $satker->map(function ($item, $key) {
            $data['id'] = $item->id_satker;
            $data['text'] = $item->nama_satker;
            $data['kode_satker'] = $item->kode_satker;

            return $data;
        });

        return collect($dataSatker);
    }

    public static function getSatkerKejati()
    {

        $satker = MasterSatker::when(!auth()->user()->hasRole('superadmin'), function($q){
                return $q->where('id_satker', auth()->user()->satker->id_satker);
            })
            ->where('tipe_satker', '2')
            ->where('nama_satker', 'not like', 'Atase%')
            ->when(auth()->user()->hasRole('superadmin'), function($q){
                return $q->orWhere('tipe_satker', '1');
            })
            ->orderBy('nama_satker', 'asc')
            ->get();

        $dataSatker = $satker->map(function ($item, $key) {
            $data['id'] = $item->id_satker;
            $data['text'] = $item->nama_satker;
            $data['kode_satker'] = $item->kode_satker;

            return $data;
        });

        return collect($dataSatker);
    }

    // get pegawai list by satker
    public static function getPegawai()
    {
        $jaksa = MasterPegawai::when(!auth()->user()->hasRole('superadmin'), function($q){
                    return $q->where('id_satker', auth()->user()->satker->id_satker);
                })
                ->orderBy('master_pegawai.nama')
                ->get();

        $dataSatker = $jaksa->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['nip'] = $item->nip;
            $data['text'] = $item->nama .' (' . $item->jabatan . ')';

            return $data;
        });

        return collect($dataSatker);
    }

    public static function getBidang()
    {
        // if(auth()->user()->satker->id_satker == '1'){
        //     // kejagung
        //     $bidangs = MasterBidang::select('description')
        //     ->where('id', '>', 5)
        //     ->get();
        // } else{
        //     $bidangs = MasterBidang::select('description')
        //     ->whereBetween('id', [1, 6])
        //     ->get();
        // }
        $tipe = auth()->user()->satker->tipe_satker;
        $bidangs = MasterBidang::select('description')->where('tipe_satker', 'like', "%$tipe%")->get();


        return collect($bidangs);
    }

    public static function getDIN17()
    {
        $types = [
            "PENKUM", "LUHKUM"
        ];


        return collect($types);
    }

    public static function getTriwulans()
    {
        $types = [
            1 => [
                "id" => 1,
                "name" => "Triwulan 1"
            ],
            2 => [
                "id" => 2,
                "name" => "Triwulan 2"
            ],
            3 => [
                "id" => 3,
                "name" => "Triwulan 3"
            ],
            4 => [
                "id" => 4,
                "name" => "Triwulan 4"
            ],
        ];


        return collect($types);
    }

    public static function getProdukIntel()
    {
        $produks = MasterMenu::select(DB::raw("concat(name, ' - ', description) as description"))->where('name', 'like', "L.IN.%")->get();


        return collect($produks);
    }

    // get user list, from satker
    public static function getUserSatker()
    {
        $role = strtolower(auth()->user()->roles->first()->name);

        $users = User::when($role, function ($query, $role) {
            if ($role == 'admin') {
                return $query->where('id_satker', auth()->user()->id_satker);
            }
        })
            ->orderBy('id_satker', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        // $users = User::where('id_satker', auth()->user()->id_satker)->get();

        return collect($users);
    }

    // get pimpinan from mysimkari
    public static function getPimpinan($satker)
    {
        $pimpinan = getPimpinanMySimkari($satker->id_satker);
        $dataPimpinan = null;
        if ($satker->tipe_satker == '1') {
            $dataPimpinan = collect($pimpinan)->firstWhere('nama_jabatan', 'SEKRETARIS JAKSA AGUNG MUDA BIDANG INTELIJEN');
        } elseif ($satker->tipe_satker == '2') {
            $dataPimpinan = collect($pimpinan)->firstWhere('nama_jabatan', 'ASISTEN BIDANG INTELIJEN');
        } elseif ($satker->tipe_satker == '3') {
            $dataPimpinan = collect($pimpinan)->firstWhere('nama_jabatan', 'KEPALA KEJAKSAAN NEGERI');
        } elseif ($satker->tipe_satker == '4') {
            $dataPimpinan = collect($pimpinan)->firstWhere('nama_jabatan', 'KEPALA CABANG KEJAKSAAN NEGERI');
        }

        return $dataPimpinan;
    }

    public static function listSatker2($kodeSatker, $needChild, $level)
    {
        if (auth()->user()->hasRole('superadmin')) {
            return MasterSatker::select('master_satker.id_satker', 'master_satker.kode_satker', 'master_satker.nama_satker')
                ->orderBy('tipe_satker')
                ->get();
        }

        $resp = MasterSatker::when($needChild, function ($q) use ($needChild, $level, $kodeSatker) {
            if ($needChild == 1) {
                if ($kodeSatker == '00') {
                    return $q->whereIn('tipe_satker', $level);
                } else {
                    return $q->where('kode_satker', 'like', "$kodeSatker%")
                        ->whereIn('tipe_satker', $level);
                }
            } else {
                return $q->where('master_satker.kode_satker', $kodeSatker);
            }
        })
            ->orderBy('tipe_satker')
            ->select('master_satker.id_satker', 'master_satker.kode_satker', 'master_satker.nama_satker')
            ->get();

        return $resp;
    }

    public static function getCase($id_satker = null)
    {
        $id_satker = request()->query('id_satker') ?? $id_satker ?? auth()->user()->id_satker;
        $case = OpenCase::join('case_progresses','open_case.id','case_progresses.case_id')
                ->where('case_progresses.percentage','!=','100')
                ->where('id_satker', $id_satker)
                ->select('open_case.*')
                ->get();

        $data_case = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = 'Kasus ' . $item->nama_kasus;

            return $data;
        });

        return collect($data_case);
    }

    public static function getCaseValidInterogationRecord($id_satker = null)
    {
        $id_satker = $id_satker ?? auth()->user()->id_satker;
        $case = OpenCase::leftJoin('case_progresses', 'open_case.id', '=', 'case_progresses.case_id')
            // ->where('case_progresses.wawancara_saran_dan_tindak_lanjut', '1')
            ->where('case_progresses.percentage', "!=", '100')
            ->where('open_case.id_satker', $id_satker)
            ->select('open_case.id as id', 'open_case.nama_kasus')
            ->get();

        $data_case = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = $item->nama_kasus;

            return $data;
        });

        return collect($data_case);
    }

    public static function getCaseValidElicitationRecord()
    {
        $case = OpenCase::leftJoin('case_progresses', 'open_case.id', '=', 'case_progresses.case_id')
            ->where('case_progresses.percentage', '!=' ,'100')
            ->where('open_case.id_satker', auth()->user()->id_satker)
            ->select('open_case.id as id', 'open_case.nama_kasus')
            ->get();

        $data_case = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = $item->nama_kasus;

            return $data;
        });

        return collect($data_case);
    }

    public static function getSprint()
    {
        $case = ResearchSuratPerintah::whereRelation('case.satker', 'id_satker', auth()->user()->satker?->id_satker)
            ->get();

        $data_sprint = $case->map(function ($item, $key) {
            $data['id'] = $item->id_surat_perintah;
            $data['text'] = 'Kasus ' . $item->case->nama_kasus . ' - No. Sprint: ' . $item->surat_perintah_number;

            return $data;
        });

        return collect($data_sprint);
    }

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

    public static function getInterogrecord()
    {
        $role = strtolower(auth()->user()->roles->first()->name);
        $user = auth()->user();

        $interogrecord = InterogationRecord::when($role != 'superadmin', function ($q) use ($user) {
            $q->where('satker_id', $user->id_satker);
        })
            ->get();

        $data_interogrecord = $interogrecord->map(function ($item, $key) {
            $data['id_interogation_record'] = $item->id_interogation_record;
            $data['target_name'] = $item->target_name;

            return $data;
        });

        return collect($data_interogrecord);
    }
    public static function getInterogrecordByCase($case_id = null)
    {
        $case_id = request()->query('case_id') ?? $case_id;

        $interogrecord = InterogationRecord::where('case_id', $case_id)
            ->get();

        $data_interogrecord = $interogrecord->map(function ($item, $key) {
            $data['id'] = $item->id_interogation_record;
            $data['text'] = $item->target_name;

            return $data;
        });

        return collect($data_interogrecord);
    }

    public static function getInterogTarget()
    {
        $role = strtolower(auth()->user()->roles->first()->name);
        $user = auth()->user();

        $interogtarget = InterogationTargetIdentification::when($role != 'superadmin', function ($q) use ($user) {
            $q->where('satker_id', $user->id_satker);
        })
            ->get();

        $data_interogtarget = $interogtarget->map(function ($item, $key) {
            $data['id_interogation_target_identification'] = $item->id_interogation_target_identification;
            $data['hasil_target_identification'] = $item->hasil_target_identification;

            return $data;
        });

        return collect($data_interogtarget);
    }
    public static function getInterogTargetByRecord($interrog_record_id = null)
    {
        $interrog_record_id = request()->query('interrog_record_id') ?? $interrog_record_id;

        $interogtarget = InterogationTargetIdentification::where('interogation_record_id', $interrog_record_id)
            ->get();

        $data_interogtarget = $interogtarget->map(function ($item, $key) {
            $data['id'] = $item->id_interogation_target_identification;
            $data['text'] = $item->hasil_target_identification;

            return $data;
        });

        return collect($data_interogtarget);
    }

    public static function getLapinsus()
    {
        $case = ResearchLaporanInformasiKhusus::whereRelation('researchSuratPerintah.case.satker', 'kode_satker', auth()->user()->satker->kode_satker)
            ->get();

        $data_lik = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = 'Kasus ' . $item->researchSuratPerintah->case->nama_kasus . ' - No. Sprint: ' . $item->researchSuratPerintah->surat_perintah_number . ' - No. Lapinsus: ' . $item->nomor_surat;

            return $data;
        });

        return collect($data_lik);
    }

    public static function getLapinsusPerSprint($id_sprint = null)
    {
        $id_sprint = request()->query('id_sprint') ?? $id_sprint;
        $sprint = ResearchLaporanInformasiKhusus::where('surat_perintah_id', $id_sprint)
                        ->with('researchSuratPerintah.case')
                        ->get();

        $data_sprint = $sprint->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = 'Kasus ' . $item->researchSuratPerintah->case->nama_kasus . ' - No. Sprint: ' . $item->researchSuratPerintah->surat_perintah_number . ' - No. Lapinsus: ' . $item->nomor_surat;

            return $data;
        });

        return collect($data_sprint);
    }

    public static function getElicitationInterview()
    {
        $elinterview = ElicitationInterview::all();

        $data_elinterview = $elinterview->map(function ($item, $key) {
            $data['id_elicitation_interview_result'] = $item->id_elicitation_interview_result;
            $data['interviewer_name'] = $item->interviewer_name;

            return $data;
        });

        return collect($data_elinterview);
    }

    public static function getSaranTl()
    {
        $case = ResearchSaranTindakLanjut::whereRelation(
            'researchLaporanInformasiKhusus.researchSuratPerintah.case.satker',
            'kode_satker',
            auth()->user()->satker->kode_satker
        )
            ->get();

        $data_saran = $case->map(function ($item, $key) {
            $data['id'] = $item->id_saran_dan_tindak_lanjut;
            $data['text'] = 'Kasus ' . $item->researchLaporanInformasiKhusus->researchSuratPerintah->case->nama_kasus . 
                ' - No. Sprint: ' . $item->researchLaporanInformasiKhusus->researchSuratPerintah->surat_perintah_number . 
                ' - No. Lapinsus: ' . $item->researchLaporanInformasiKhusus->nomor_surat . ' - Saran TL: ' .
                Str::limit(strip_tags($item->saran_dan_tindak_lanjut), 128);

            return $data;
        });

        return collect($data_saran);
    }

    public static function getSaranTlPerLapinsus($id_lapinsus = null)
    {
        $id_lapinsus = request()->query('id_lapinsus') ?? $id_lapinsus;
        $case = ResearchSaranTindakLanjut::whereRelation(
            'researchLaporanInformasiKhusus.researchSuratPerintah.case.satker',
            'kode_satker',
            auth()->user()->satker->kode_satker
        )
            ->whereRelation('researchLaporanInformasiKhusus', 'id', $id_lapinsus)
            ->get();

        $data_saran = $case->map(function ($item, $key) {
            $data['id'] = $item->id_saran_dan_tindak_lanjut;
            $data['text'] = 'Kasus ' . $item->researchLaporanInformasiKhusus->researchSuratPerintah->case->nama_kasus . 
                ' - No. Sprint: ' . $item->researchLaporanInformasiKhusus->researchSuratPerintah->surat_perintah_number . 
                ' - No. Lapinsus: ' . $item->researchLaporanInformasiKhusus->nomor_surat . ' - Saran TL: ' .
                Str::limit(strip_tags($item->saran_dan_tindak_lanjut), 128);

            return $data;
        });

        return collect($data_saran);
    }

    public static function getElicitationAdfoll()
    {
        $elintAdfoll = ElicitationAdFoll::all();
        $data_elintadfoll = $elintAdfoll->map(function ($item, $key) {
            $data['id_elicitation_saran_dan_tindak_lanjut'] = $item->id_elicitation_saran_dan_tindak_lanjut;
            $data['saran_dan_tindak_lanjut_date'] = $item->saran_dan_tindak_lanjut_date;
            return $data;
        });

        return collect($data_elintadfoll);
    }

    // OPEN CASE HELPER GENERAL
    public static function getOpenCase($satker_id = null)
    {
        $satker_id = request()->query('satker_id') ?? $satker_id;
        if ($satker_id) {
            $case = OpenCase::where('id_satker', request()->query('satker_id'))->get();
        } else {
            $case = OpenCase::where('id_satker', auth()->user()->id_satker)->get();
        }


        $data_case = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = $item->nama_kasus;

            return $data;
        });

        return collect($data_case);
    }

    // CLOSE CASE HELPER
    public static function getCloseCase()
    {
        if (request()->query('satker_id')) {
            $case = CloseCase::join('case_close_progresses','close_case.id','case_close_progresses.case_id')
            ->where('case_close_progresses.percentage','!=','100')
            ->where('satker_id', operator: request()->query('satker_id'))->get();
        } else {
            $case = CloseCase::join('case_close_progresses','close_case.id','case_close_progresses.case_id')
            ->where('case_close_progresses.percentage','!=','100')
            ->where('satker_id', auth()->user()->id_satker)->get();
        }

        $data_case = $case->map(function ($item, $key) {
            $data['id'] = $item->case_id;
            $data['text'] = $item->case_name . " - Tgl. Kasus " . $item->case_date->isoFormat('DD MMMM YYYY');

            return $data;
        });

        return collect($data_case);
    }

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
            return collect($data_sprint);
        }
    }

    // CLOSE CASE HELPER
    public static function getinformationcollection($case_id = null)
    {

        $observationinformationcollection = ObservCollectInfo::where('case_id', $case_id)->get();
        
        // $observationinformationcollection = ObservCollectInfo::all();
        $data_observationinformationcollection = $observationinformationcollection->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = $item->information_collection_perihal . " - " . $item->information_collection_source . " - " . $item->information_collection_date?->isoFormat('YYYY-MM-DD');
            return $data;
        });
        return collect($data_observationinformationcollection);
    }


    public static function getinformationverification($information_collection_id = null)
    {

        // $observationinformationcollection = ObservCollectInfo::where('case_id', $case_id)->get();
        // $observationinformationcollection = DelineationInformationVerification::all();
        $observationinformationcollection = DelineationInformationVerification::where('information_collection_id', $information_collection_id)->get();
        $data_observationinformationcollection = $observationinformationcollection->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = $item->kredibilitas_sumber . " - " . $item->metode_verifikasi . " - " . $item->verification_date;
            return $data;
        });
        return collect($data_observationinformationcollection);
    }


    public static function getinformationvalidation($information_verification_id = null)
    {

        // $observationinformationcollection = ObservCollectInfo::where('case_id', $case_id)->get();
        // $observationinformationcollection = DelineationInformationValidation::all();
        $observationinformationcollection = DelineationInformationValidation::where('information_verification_id', $information_verification_id)->get();
        $data_observationinformationcollection = $observationinformationcollection->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = $item->metode_validasi . " - " . $item->tanggal_validasi;
            return $data;
        });
        return collect($data_observationinformationcollection);
    }

    public static function getListAgama()
    {
        $case = MasterAgama::all();

        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->kode;
            $data['text'] = Str::limit(strip_tags($item->nama), 128);

            return $data;
        });
        return collect($data);
    }

    public static function getListJenisKelamin()
    {
        $jenis_kelamint_data = MasterJenisKelamin::all();

        $data = $jenis_kelamint_data->map(function ($item, $key) {
            $data['id'] = $item->kode;
            $data['text'] = Str::limit(strip_tags($item->nama), 128);

            return $data;
        });
        return collect($data);
    }

    public static function getAgama()
    {
        $agama = MasterAgama::all();
        $agama = $agama->map(function ($item, $key) {
            $data['id'] = $item->kode;
            $data['text'] = $item->nama;
            return $data;
        });
        return collect($agama);
    }

    public static function getClosTargetLoc($case_id = null)
    {
        $case_id = request()->query('case_id') ?? $case_id;

        if (!$case_id) {
            return [];
        } else {
            $target = IntrusionTargetLoc::where('case_id', $case_id)->get();
            $data_target = $target->map(function ($item, $key) {
                $data['id'] = $item->id;
                $data['text'] = $item->target_name;
                return $data;
            });
            return collect($data_target);
        }
    }

    public static function getCloseAght($information_collection_id = null)
    {
        $information_collection_id = request()->query('information_collection_id') ?? $information_collection_id;

        if (!$information_collection_id) {
            return [];
        } else {
            $env = ObservThreat::where('information_collection_id', $information_collection_id)->get();
            $data_aght = $env->map(function ($item, $key) {
                $data['id'] = $item->id;
                $data['text'] = $item->aght_type . ' - ' . $item->perihal;
                return $data;
            });
            return collect($data_aght);
        }
    }

    public static function getExplorationRencanaAksi($case_id = null)
    {
        $case_id = request()->query('case_id') ?? $case_id;

        if (!$case_id) {
            return [];
        } else {
            $env = ExplorationRencanaAksi::where('case_id', $case_id)->get();
            $data_rencana_aksi = $env->map(function ($item, $key) {
                $data['id'] = $item->id_exploration_rencana_aksi;
                $data['text'] = $item->rencana_aksi_data;
                return $data;
            });
            return collect($data_rencana_aksi);
        }
    }

    public static function getExplorationTargetId($exploration_rencana_aksi_id = null)
    {
        $exploration_rencana_aksi_id = request()->query('exploration_rencana_aksi_id') ?? $exploration_rencana_aksi_id;

        if (!$exploration_rencana_aksi_id) {
            return [];
        } else {
            $env = ExplorationTargetIdentity::where('exploration_rencana_aksi_id', $exploration_rencana_aksi_id)->get();
            $data_identitas_target = $env->map(function ($item, $key) {
                $data['id'] = $item->id_exploration_target_identity;
                $data['text'] = $item->target_name;
                return $data;
            });
            return collect($data_identitas_target);
        }
    }

    public static function getClosTargetEnv($intrusion_target_location_id = null)
    {
        $intrusion_target_location_id = request()->query('intrusion_target_location_id') ?? $intrusion_target_location_id;

        if (!$intrusion_target_location_id) {
            return [];
        } else {
            $env = IntrusionTargetEnv::where('intrusion_target_location_id', $intrusion_target_location_id)->get();
            $data_env = $env->map(function ($item, $key) {
                $data['id'] = $item->id;
                $data['text'] = $item->nama_lingkungan;
                return $data;
            });
            return collect($data_env);
        }
    }

    public static function getCollectInfo($surat_perintah_id = null)
    {
        $surat_perintah_id = request()->query('surat_perintah_id') ?? $surat_perintah_id;
        $observationinformationcollection = ObservCollectInfo::where('surat_perintah_id', $surat_perintah_id)->get();

        $data_observationinformationcollection = $observationinformationcollection->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = $item->information_collection_perihal . " - " . $item->information_collection_source . " - " . $item->information_collection_date?->isoFormat('YYYY-MM-DD');
            return $data;
        });
        return collect($data_observationinformationcollection);
    }

    public static function getInfiltrationOperasiRahasia($surat_perintah_id = null)
    {

        $observationinformationcollection = InfiltrationSecretOperation::all();

        $data_observationinformationcollection = $observationinformationcollection->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = $item->nama_operasi_rahasia . " - " . $item->tanggal_operasi_rahasia;
            return $data;
        });
        return collect($data_observationinformationcollection);
    }

    public static function getInterviewJadwal()
    {
        $case = InterviewJadwal::whereRelation('case.satker', 'kode_satker', auth()->user()->satker->kode_satker)
            ->get();

        $data = $case->map(function ($item, $key) {
            $person1 = $item->interviewer_name;
            $person2 = $item->source_person_name;
            $schedule = $item->interviewer_schedule->isoFormat('DD MMMM YYYY');

            $data['id'] = $item->id_interview_scheduler;
            $data['text'] = $person1 . ' dengan ' . $person2 . ' tanggal ' . $schedule;

            return $data;
        });

        return collect($data);
    }

    public static function getInterviewHasil()
    {
        $case = InterviewHasil::whereRelation('interviewJadwal.case.satker', 'kode_satker', auth()->user()->satker->kode_satker)
            ->get();

        $data = $case->map(function ($item, $key) {
            $person1 = $item->interviewJadwal->interviewer_name;
            $person2 = $item->interviewJadwal->source_person_name;
            $schedule = $item->interviewJadwal->interviewer_schedule->isoFormat('DD MMMM YYYY');

            $data['id'] = $item->id_interview_result;
            $data['text'] = 'Hasil interview ' . $person1 . ' dengan ' . $person2 . ' tanggal ' . $schedule;

            return $data;
        });

        return collect($data);
    }
    public static function getInterviewHasilByJadwal($id_jadwal = null)
    {
        $id_jadwal = request()->query('id_jadwal') ?? $id_jadwal;

        $case = InterviewHasil::whereRelation('interviewJadwal.case.satker', 'id_satker', auth()->user()->satker->id_satker)
            ->where('interview_scheduler_id', $id_jadwal)
            ->get();

        $data = $case->map(function ($item, $key) {
            $person1 = $item->interviewJadwal->interviewer_name;
            $person2 = $item->interviewJadwal->source_person_name;
            $schedule = $item->interviewJadwal->interviewer_schedule->isoFormat('DD MMMM YYYY');

            $data['id'] = $item->id_interview_result;
            $data['text'] = 'Hasil interview ' . $person1 . ' dengan ' . $person2 . ' tanggal ' . $schedule;

            return $data;
        });

        return collect($data);
    }

    public static function getTappingElectronicDevice()
    {
        $tED = TappingElectronicDevice::whereRelation('case.satker', 'kode_satker', auth()->user()->satker->kode_satker)
            ->get();

        $data = $tED->map(function ($item, $key) {
            $data['id'] = $item->id_tapping_electronic_device;
            $data['text'] = 'Kasus ' . $item->case->case_name.' - Perangkat Elektronik ' . Str::limit(strip_tags($item->sumber_data), 128);

            return $data;
        });

        return collect($data);
    }

    public static function getTappingElectronicDeviceByCase($case_id = null)
    {
        $case_id = request()->query('case_id') ?? $case_id;
        $tED = TappingElectronicDevice::whereRelation('case.satker', 'kode_satker', auth()->user()->satker->kode_satker)
            ->where('case_id', $case_id)
            ->get();

        $data = $tED->map(function ($item, $key) {
            $data['id'] = $item->id_tapping_electronic_device;
            $data['text'] = 'Kasus ' . $item->case->case_name.' - Perangkat Elektronik ' . Str::limit(strip_tags($item->sumber_data), 128);

            return $data;
        });

        return collect($data);
    }

    public static function getPendidikan()
    {
        $pendidikan = MasterPendidikan::all();
        $pendidikan = $pendidikan->map(function ($item, $key) {
            $data['id'] = $item->kode;
            $data['text'] = $item->nama;
            return $data;
        });
        return collect($pendidikan);
    }
    public static function getPekerjaan()
    {
        $pekerjaan = MasterPekerjaan::orderBy('kode', 'asc')->get();
        $pekerjaan = $pekerjaan->map(function ($item, $key) {
            $data['id'] = $item->kode;
            $data['text'] = $item->nama;
            return $data;
        });
        return collect($pekerjaan);
    }

    public static function insertDocument($id = null, $upload_path = null, $old_path = null, $created_updated_by = null)
    {
        if ($old_path) {
            $old_data = Documents::where('doc_path', $old_path)->first();

            if ($old_data) {
                $old_data->doc_path = $upload_path;
                $old_data->updated_by = $created_updated_by ? $created_updated_by : null;
                $old_data->update();
            } else {
                $document_pdf = new Documents;
                $document_pdf->relation_id = $id;
                $document_pdf->doc_path = $upload_path;
                $document_pdf->doc_type = "pdf";
                $document_pdf->doc_status = "0";
                $document_pdf->doc_status_remark = "Waiting Analysis";
                $document_pdf->updated_by = $created_updated_by ? $created_updated_by : null;
                $document_pdf->save();
            }
        } else {
            $document_pdf = new Documents;
            $document_pdf->relation_id = $id;
            $document_pdf->doc_path = $upload_path;
            $document_pdf->doc_type = "pdf";
            $document_pdf->doc_status = "0";
            $document_pdf->doc_status_remark = "Waiting Analysis";
            $document_pdf->updated_by = $created_updated_by ? $created_updated_by : null;
            $document_pdf->save();
        }
        return "success";
    }

    public static function imgToBase64($path)
    {
        return base64_encode(Storage::disk('public')->path($path));
    }

    public static function getTappingIntelligentSignal()
    {
        $case = TappingIntelligentSignal::whereRelation('tappingElectronicDevice.case.satker', 'kode_satker', auth()->user()->satker->kode_satker)
            ->get();

        $data = $case->map(function ($item, $key) {
            $text = 'Kasus ' . $item->tappingElectronicDevice->case->case_name . ' - ' . $item->tanggal_penyadapan->isoFormat('DD MMMM YYYY') . ' - ' . $item->jenis_sinyal;

            $data['id'] = $item->id_tapping_intelligent_signal;
            $data['text'] = strip_tags($text);

            return $data;
        });

        return collect($data);
    }

    public static function getTappingIntelligentSignalByDevice($device_id = null)
    {
        $device_id = request()->query('device_id') ?? $device_id;
        $case = TappingIntelligentSignal::whereRelation('tappingElectronicDevice.case.satker', 'kode_satker', auth()->user()->satker->kode_satker)
            ->where('tapping_electronic_device_data_id', $device_id)
            ->get();

        $data = $case->map(function ($item, $key) {
            $text = 'Kasus ' . $item->tappingElectronicDevice->case->case_name . ' - ' . $item->tanggal_penyadapan->isoFormat('DD MMMM YYYY') . ' - ' . $item->jenis_sinyal;

            $data['id'] = $item->id_tapping_intelligent_signal;
            $data['text'] = strip_tags($text);

            return $data;
        });

        return collect($data);
    }

    public static function logUpdateCase($case_id, $action)
    {
        $user_id = auth()->user() ? auth()->user()->id : Auth::guard('api')->user()->id;
        $caseLog = CaseEventHistoricalUpdates::create([
            'case_id' => $case_id,
            'action' => $action,
            'created_by' => $user_id,
            'created_at' => Carbon::now(),
            'updated_by' => $user_id,
            'updated_at' => Carbon::now(),
        ]);
    }

    public static function insertVideo($id = null, $upload_path = null, $old_path = null, $created_updated_by = null)
    {
        if ($old_path) {
            $old_data = VideoDocuments::where('doc_path', $old_path)->first();

            if ($old_data) {
                $old_data->doc_path = $upload_path;
                $old_data->updated_by = $created_updated_by ? $created_updated_by : null;
                $old_data->update();
            } else {
                $document_video = new VideoDocuments;
                $document_video->relation_id = $id;
                $document_video->doc_path = $upload_path;
                $document_video->doc_type = "video";
                $document_video->doc_status = "0";
                $document_video->doc_status_remark = "Waiting Analysis";
                $document_video->updated_by = $created_updated_by ? $created_updated_by : null;
                $document_video->save();
            }
        } else {
            $document_video = new VideoDocuments;
            $document_video->relation_id = $id;
            $document_video->doc_path = $upload_path;
            $document_video->doc_type = "video";
            $document_video->doc_status = "0";
            $document_video->doc_status_remark = "Waiting Analysis";
            $document_video->updated_by = $created_updated_by ? $created_updated_by : null;
            $document_video->save();
        }

        return "success";
    }

    public static function uploadCkeditor(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'upload' => 'required|file|image|max:5000'  // Max 5MB
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'message' => $validator->errors()->first('upload')
                ]
            ], 400);
        }
    
        $imageFile = $request->file('upload');
        $extension = $imageFile->getClientOriginalExtension();
        $defaultImage = Image::make($imageFile);
        $imageWidth = $defaultImage->width();
        $imageSizes = [400, 1024, 1920];
    
        $filename = Str::random(10) . '-' . Str::ulid();
        $path = 'uploads/' . $filename;
    
        // Ensure encoding for proper image data
        $defaultImage->encode($extension, 75); // Reduce quality to 75 to optimize size

        $resizedImage = Image::make($imageFile)
            ->resize($imageSizes[0], null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode($extension, 75); // Encode resized image

        $path = 'uploads/' . $filename . "-$imageSizes[0]." . $extension;
        Storage::disk('public')->put($path, (string) $resizedImage);
        $urls = asset("storage/" . $path);
    
        return response()->json(['fileName' => $filename, 'uploaded' => 1, 'url' => $urls]);
    }

    // Function to calculate the signature
    private static function calculateSignature($username, $password, $realm, $randomKey)
    {
        $temp1 = md5($password);
        $temp2 = md5($username . $temp1);
        $temp3 = md5($temp2);

        $temp4 = md5($username . ":" . $realm . ":" . $temp3);
        $signature = md5($temp4 . ":" . $randomKey);

        return $signature;
    }

    public static function loginDss()
    {
        $host = env('DSS_HOST'); 
        $username = env('DSS_USERNAME'); 
        $password = env('DSS_PASSWORD'); 

        $response = Http::withOptions([
            'verify' => false,  // Disable SSL verification
        ])->post("$host/brms/api/v1.0/accounts/authorize", [
            'userName' => $username,
            'clientType' => 'WINPC_V2',  
            'ipAddress' => '', 
        ]);
        // return $response['realm'];
        if ($response->status() == 401) {
            $realm = $response['realm'];
            $randomKey = $response['randomKey'];
            $publicKey = $response['publickey'];

            $signature = self::calculateSignature($username, $password, $realm, $randomKey);

            $secondResponse = Http::withOptions([
                'verify' => false,  // Disable SSL verification
            ])->post("$host/brms/api/v1.0/accounts/authorize", [
                // 'mac' => '30:9c:23:79:40:08',  
                'signature' => $signature,
                'userName' => $username,
                'randomKey' => $randomKey,
                'publicKey' => $publicKey,
                'encryptType' => 'MD5', 
                'clientType' => 'WINPC_V2',
                'ipAddress' => '',  
                'userType' => '0',  
            ]);

            $token = $secondResponse['token'];
            return $token;
        } else {
            return $response->status();
        }
    }


    public static function getLocation($dahuaId = '')
    {
        $host = env('DSS_HOST'); 
        $token = self::loginDss();  

        if(!$dahuaId){
            return [
                'error' => 'Device not found',
                'long' => null,
                'lat' => null
            ];
        }
        $response = Http::withHeaders([
            'X-Subject-Token' => $token,  
        ])->withOptions([
            'verify' => false,  
        ])->get("$host/brms/api/v1.1/map/gps/location/mpt/list");

        if ($response->successful()) {
            $responseData = $response->json();

            if($dahuaId == 'all'){
                return $responseData['data']['results'];
            }
            foreach ($responseData['data']['results'] as $device) {
                if ($device['deviceCode'] === $dahuaId) {
                    // Return gpsX and gpsY if deviceCode matches
                    $gpsX = $device['gpsX'];
                    $gpsY = $device['gpsY'];
                    
                    return [
                        'long' => $gpsX,
                        'lat' => $gpsY
                    ];
                }
            }

            return [
                'error' => 'Device not found',
                'long' => null,
                'lat' => null
            ];

        } else {
            // Handle errors
            return [
                'error' => 'Failed to fetch data',
                'status' => $response->status(),
            ];
        }
    }

    public static function getStreamToken($dahuaId = '')
    {
        $host = env('DSS_HOST'); 
        $token = self::loginDss();  

        if(!$dahuaId){
            return 'id is null';
        }

        $data = [
            "clientType" => "WINPC_V2",
            "clientMac" => "30:9c:23:79:40:08",
            "clientPushId" => "",
            "project" => "PSDK",
            "method" => "MTS.Video.StartVideo",
            "data" => [
                "streamType" => "1",
                "optional" => "/brms/api/v1.0/MTS/Video/StartVideo",
                "trackId" => "",
                "extend" => "",
                "channelId" => $dahuaId . "$1$0$0",
                "keyCode" => "",
                "planId" => "",
                "dataType" => "2",
                "enableRtsps" => "0",
                "enableMulticast" => "0"
            ]
        ];

        $response = Http::withHeaders([
            'X-Subject-Token' => $token,  // Add the token in the header
        ])->withOptions([
            'verify' => false,  
        ])->post($host . '/brms/api/v1.0/MTS/Video/StartVideo', $data);

        if ($response->successful()) {
            $responseData = $response->json();

            return $responseData['data']['token'];

        } else {
            // Handle errors
            return [
                'error' => 'Failed to fetch data',
                'status' => $response->status(),
            ];
        }
    }

}
