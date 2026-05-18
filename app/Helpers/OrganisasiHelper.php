<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Bidang;
use App\Models\Satker;
use App\Models\Jabatan;
use App\Models\Organisasi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class OrganisasiHelper {

    public static $ttlCache = 300; //5 Menit

    public static function getParentSatker($idSatker) 
    {
        $satker = Satker::with('parent')->where('id_satker', $idSatker)->first();
        return $satker;
    }

    public static function getChildrenSatker($idSatker) 
    {
        $satker = Satker::with('children')->where('id_satker', $idSatker)->first();
        return $satker;
    }

    public static function getParentBidang($idBidang) 
    {
        $bidang = Bidang::with('parent')->where('id_bidang', $idBidang)->first();
        return $bidang;
    }

    public static function getChildrenBidang($idBidang) 
    {
        $bidang = Bidang::with('children')->where('id_bidang', $idBidang)->first();
        return $bidang;
    }

    public static function getChildrenBidangWithoutMain($idBidang) 
    {
        $bidang = Cache::remember('get_children_bidang_without_main_'.$idBidang, self::$ttlCache, function () use ($idBidang) {
                        return Bidang::with('childrenWithoutMain')->where('id_bidang', $idBidang)->first();
                    });
        return $bidang;
    }

    public static function getParentJabatan($idJabatan) 
    {
        $jabatan = Jabatan::with('parent')->where('id_jabatan', $idJabatan)->first();
        return $jabatan;
    }

    public static function getChildrenJabatan($idJabatan) 
    {
        $jabatan = Jabatan::with('children')->where('id_jabatan', $idJabatan)->first();
        return $jabatan;
    }

    public static function getIdChildSatker($idSatker)
    {
        $tree = array();
        if (!empty($idSatker)) {
            $tree = Satker::where('parent_id', $idSatker)->pluck('id_satker')->toArray();
            foreach ($tree as $key => $val) {
                $ids = self::getIdChildSatker($val);
                if(!empty($ids)){
                    if(count($ids)>0) $tree = array_merge($tree, $ids);
                }
            }
        }
        return $tree;
    }

    public static function getIdParentBidang($data) {
        $id = [$data->id_bidang];
        $flatten = self::flatten($data->toArray());
        $idParent = collect($flatten)->pluck('id_bidang');
        $idMerge = array_merge($id, $idParent->toArray());
        return $idMerge;
    }

    public static function getFlattenParentBidang($data) {
        $id = [collect($data)->except(['parent'])->toArray()];
        $flatten = self::flatten($data->toArray());
        $idMerge = array_merge($id, $flatten);
        return $idMerge;
    }

    public static function getFlattenChildrenBidang($data) {
        $id = [collect($data)->except(['children'])->toArray()];
        $flatten = self::flatten($data->toArray());
        $idMerge = array_merge($id, $flatten);
        return $idMerge;
    }

    public static function getSesJambin($data) {
        $id = null;
        foreach ($data as $item) {
            if ($item == 2) {
                $id = 3;
            }
        }
        return $id;
    }

    public static function flatten($array) {
        $result = array();
        foreach ($array as $item) {
            if (is_array($item)) {
                $result[] = array_filter($item, function($array) {
                    return ! is_array($array);
                });
                $result = array_merge($result, self::flatten($item));
            } 
        }
        return array_filter($result);
    }

    public static function getSatkerBidangName($idOrganisasi) 
    {
        $organisasi = Organisasi::with('satker', 'bidang', 'jabatan')->findOrFail($idOrganisasi);
        return $organisasi->satker->nama_satker . " | " .$organisasi->bidang->nama_bidang;
       
    }

    public static function getSatkerBidangNameArr($idOrganisasi) 
    {
        $result = "";
        $organisasi = Organisasi::with('satker', 'bidang', 'jabatan')
                                    ->whereIn('id_organisasi', $idOrganisasi)
                                    ->get();
        foreach ($organisasi as $key => $org) {
            $result .= $org->satker->nama_satker . " | " .$org->bidang->nama_bidang.", ";
        }
        return rtrim($result, ", ");
    }

    public static function getIdAdminOrganisasiArr($idTujuan) 
    {
        $IdOrganisasiAdmin = collect();
        foreach ($idTujuan as $key => $value) {
            $detailOrganisasi = self::getDetailOrganisasi($value);
            if ($detailOrganisasi->bidang->nama_bidang == 'JAKSA AGUNG') {
                $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([self::getIdAdminTuPusat()]));
            }
            else {
                if ($detailOrganisasi->id_satker == auth()->user()->organisasi->id_satker) {
                    $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([(int) $value]));
                } else {
                    $idBidangChild = self::getIdChildBidang($detailOrganisasi->id_bidang);
                    $idBidang = array_merge([$detailOrganisasi->id_bidang], $idBidangChild);
                    $organisasi = Organisasi::whereHas('jabatan', function($q) {
                                                $q->where('is_admin', true);
                                            })
                                            ->whereIdSatker($detailOrganisasi->id_satker)
                                            ->whereIn('id_bidang', $idBidang)
                                            ->first();
                    if (!$organisasi) {
                        $parentBidang = self::getParentBidang($detailOrganisasi->id_bidang);
                        $idBidangParent = self::getIdParentBidang($parentBidang);
                        $organisasi = Organisasi::whereHas('jabatan', function($q) {
                                                    $q->where('is_admin', true);
                                                })
                                                ->whereIdSatker($detailOrganisasi->id_satker)
                                                ->whereIn('id_bidang', $idBidangParent)
                                                ->first();
                        if ($organisasi) {
                            $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([$organisasi->id_organisasi])); 
                        }
                        else {
                            $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([(int) $value]));
                        }
                    }
                    else {
                        $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([$organisasi->id_organisasi]));
                    }
                }
            }
        }

        return $IdOrganisasiAdmin->unique()->values()->all();
    }

    public static function getIdAdminOrganisasi($value) 
    {
        $IdOrganisasiAdmin = collect();
            $detailOrganisasi = self::getDetailOrganisasi($value);
            if ($detailOrganisasi->bidang->nama_bidang == 'JAKSA AGUNG') {
                $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([self::getIdAdminTuPusat()]));
            }
            else {
                if ($detailOrganisasi->id_satker == auth()->user()->organisasi->id_satker) {
                    $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([(int) $value]));
                } else {
                    $idBidangChild = self::getIdChildBidang($detailOrganisasi->id_bidang);
                    $idBidang = array_merge([$detailOrganisasi->id_bidang], $idBidangChild);
                    $organisasi = Organisasi::whereHas('jabatan', function($q) {
                                                $q->where('is_admin', true);
                                            })
                                            ->whereIdSatker($detailOrganisasi->id_satker)
                                            ->whereIn('id_bidang', $idBidang)
                                            ->first();
                    if (!$organisasi) {
                        $parentBidang = self::getParentBidang($detailOrganisasi->id_bidang);
                        $idBidangParent = self::getIdParentBidang($parentBidang);
                        $organisasi = Organisasi::whereHas('jabatan', function($q) {
                                                    $q->where('is_admin', true);
                                                })
                                                ->whereIdSatker($detailOrganisasi->id_satker)
                                                ->whereIn('id_bidang', $idBidangParent)
                                                ->first();
                        if ($organisasi) {
                            $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([$organisasi->id_organisasi])); 
                        }
                        else {
                            $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([(int) $value]));
                        }
                    }
                    else {
                        $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([$organisasi->id_organisasi]));
                    }
                }
            }

        return $IdOrganisasiAdmin->unique()->values()->all();
    }

    public static function getIdAdminTuPusat() 
    {
        $organisasi = Cache::remember('get_id_admin_tu_pusat', self::$ttlCache, function () {
                            return Organisasi::whereHas('satker', function($q) {
                                                $q->whereIdSatker(1);
                                            })
                                            ->whereHas('bidang', function($q) {
                                                $q->where('nama_bidang', 'ilike', '%BAGIAN TATA USAHA UMUM DAN PIMPINAN%');
                                            })
                                            ->whereHas('jabatan', function($q) {
                                                $q->where('nama_jabatan', 'ilike', '%KEPALA BAGIAN TATA USAHA UMUM DAN PIMPINAN%')
                                                    ->orWhere('id_jabatan', 30);
                                            })
                                            ->select('id_organisasi')
                                            ->first();
                        });
        if ($organisasi) {
            return $organisasi->id_organisasi;
        }
        return null;
    }

    public static function getDetailOrganisasi($idOrganisasi) 
    {
        $organisasi = Cache::remember('get_detail_organisasi_'.$idOrganisasi, 3600, function () use ($idOrganisasi) {
                            return Organisasi::with('satker', 'bidang', 'jabatan')
                                                ->whereIdOrganisasi($idOrganisasi)
                                                ->first();
                        });
        
        return $organisasi;
    }

    public static function checkIsAdmin($idOrganisasi) 
    {
        $organisasi = Organisasi::whereHas('jabatan', function($q) {
                                    $q->where('is_admin', true);
                                })
                                ->whereIdOrganisasi($idOrganisasi)
                                ->exists();
        
        return $organisasi;
    }

    public static function getIdChildBidang($idBidang)
    {
        $tree = array();
        if (!empty($idBidang)) {
            $tree = Bidang::where('parent_id', $idBidang)->pluck('id_bidang')->toArray();
            foreach ($tree as $key => $val) {
                $ids = self::getIdChildBidang($val);
                if(!empty($ids)){
                    if(count($ids)>0) $tree = array_merge($tree, $ids);
                }
            }
        }
        return $tree;
    }

    public static function getBidangMain($detailOrganisasi, $idBidangUser = null) {
        $bidang_main = null;
        if ($detailOrganisasi) {
            if ($detailOrganisasi->satker->tipe_satker == 1) {
                $parentBidang = self::getParentBidang($idBidangUser == null ? auth()->user()->organisasi->id_bidang : $idBidangUser);
                $bidangParent = self::getFlattenParentBidang($parentBidang);
                foreach ($bidangParent as $bidang) {
                    if ($bidang['main'] == 1) {
                        $bidang_main = $bidang['id_bidang'];
                        break;
                    }
                }
            }
        }

        return $bidang_main;
    }
	
	public static function getIdAdminOrganisasiApi($idTujuan) 
    {
        $IdOrganisasiAdmin = collect();
        foreach ($idTujuan as $key => $value) {
            $detailOrganisasi = self::getDetailOrganisasi($value);
            if ($detailOrganisasi->bidang->nama_bidang == 'JAKSA AGUNG') {
                $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([self::getIdAdminTuPusat()]));
            }
            else {
                $idBidangChild = self::getIdChildBidang($detailOrganisasi->id_bidang);
                    $idBidang = array_merge([$detailOrganisasi->id_bidang], $idBidangChild);
                    $organisasi = Organisasi::whereHas('jabatan', function($q) {
                                                $q->where('is_admin', true);
                                            })
                                            ->whereIdSatker($detailOrganisasi->id_satker)
                                            ->whereIn('id_bidang', $idBidang)
                                            ->first();
                    if (!$organisasi) {
                        $parentBidang = self::getParentBidang($detailOrganisasi->id_bidang);
                        $idBidangParent = self::getIdParentBidang($parentBidang);
                        $organisasi = Organisasi::whereHas('jabatan', function($q) {
                                                    $q->where('is_admin', true);
                                                })
                                                ->whereIdSatker($detailOrganisasi->id_satker)
                                                ->whereIn('id_bidang', $idBidangParent)
                                                ->first();
                        if ($organisasi) {
                            $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([$organisasi->id_organisasi])); 
                        }
                        else {
                            $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([(int) $value]));
                        }
                    }
                    else {
                        $IdOrganisasiAdmin = $IdOrganisasiAdmin->merge(collect([$organisasi->id_organisasi]));
                    }
            }
        }

        return $IdOrganisasiAdmin->unique()->values()->all();
    }

    public static function getDataUser($userId) {

        $user_nama = '';
        $user_username = '';
        $user_email = '';
        $user_satker = '';
        $user_bidang = '';
        $user_jabatan = '';
        $user_pangkat = '';

        $data = User::join('organisasi', 'organisasi.id_organisasi', '=', 'users.id_organisasi')
                        ->join('satker', 'satker.id_satker', '=', 'organisasi.id_satker')
                        ->join('bidang', 'bidang.id_bidang', '=', 'organisasi.id_bidang')
                        ->join('jabatan', 'jabatan.id_jabatan', '=', 'organisasi.id_jabatan')
                        ->where('users.id', $userId)
                        ->select('users.nama as user_nama', 'users.username as user_username', 'users.email as user_email', 'users.pangkat as user_pangkat', 'satker.nama_satker as user_satker', 'bidang.nama_bidang as user_bidang', 'jabatan.nama_jabatan as user_jabatan')
                        ->first();
        
        if ($data) {
            $user_nama = $data->user_nama;
            $user_username = $data->user_username;
            $user_email = $data->user_email;
            $user_satker = $data->user_satker;
            $user_bidang = $data->user_bidang;
            $user_jabatan = $data->user_jabatan;
            $user_pangkat = $data->user_pangkat;
        }

        return [
            'user_nama' => $user_nama,
            'user_username' => $user_username,
            'user_email' => $user_email,
            'user_satker' => $user_satker,
            'user_bidang' => $user_bidang,
            'user_jabatan' => $user_jabatan,
            'user_pangkat' => $user_pangkat
        ];
    }
}

?>