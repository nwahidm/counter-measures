<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterWilayah;
use App\Models\MasterSatker;
use Illuminate\Support\Facades\DB;

class MasterWilayahController extends Controller
{
    public function getProvinsi()
    {
        $provinsi = MasterWilayah::whereRaw('LENGTH(kode) = 2')->get();
        return response()->json($provinsi);
    }

    public function getAtase()
    {
        $atase = MasterSatker::where('kode_satker', 'like', '%00.0%')->get();
        return response()->json($atase);
    }


    public function getChild($kodeParent)
    {
        $cekKodeParent = MasterWilayah::where('kode', '=' ,$kodeParent)->first();
        if($cekKodeParent->level == 'PROVINSI'){
            $levelSearch = 'KABUPATEN/KOTA';
        }elseif($cekKodeParent->level == 'KABUPATEN/KOTA'){
            $levelSearch = 'KECAMATAN';
        }elseif($cekKodeParent->level == 'KECAMATAN'){
            $levelSearch = 'KELURAHAN/DESA';
            $levelDigit = '13';
        }
        $child = MasterWilayah::where('kode', 'like' ,$kodeParent.'%')->where('level',$levelSearch)->orderBy('nama','asc')->get();
        return $child;
        return response()->json($child);
    }

    public function getChildSatker($kodeParent)
    {
        $cekKodeParent = MasterWilayah::where('kode', '=' ,$kodeParent)->first();
        
        $child = MasterWilayah::where('kode', 'like' ,$kodeParent.'%')->orderBy('nama','asc')->get();

        return response()->json($child);
    }
    

}
