<?php

namespace App\Http\Controllers\API;

use ResponseApi;
use App\Models\Posko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\FetchKegiatanPoskoSentimentEvent;

class PoskoController extends Controller
{
    public function list(Request $request)
    {
        $data = tap(Posko::with('inventarisposko:id_posko,nama_barang')
                    ->join('master_satker', 'posko.id_satker', '=', 'master_satker.id_satker')
                    ->join('master_wilayah', 'posko.id_wilayah', '=', 'master_wilayah.id_wilayah')
                    ->when($request->kode_satker, function($q, $kodeSatker) {
                        $q->where('master_satker.kode_satker', $kodeSatker);
                    })
                    ->orderByDesc('posko.created_at')
                    ->select([
                        'posko.id_posko as id_posko', 'posko.kode_satker as kode_satker', 'master_satker.nama_satker as nama_satker',
                        'master_wilayah.nama as nama_wilayah', 'master_wilayah.level as level_wilayah',
                        'posko.penanggung_jawab_nip as nip_penanggung_jawab', 'posko.penanggung_jawab_nama as nama_penanggung_jawab', 'posko.isp_internet', 'posko.kondisi_posko',
                        'posko.kendala', 'posko.jumlah_laporan', 'posko.foto_posko'
                    ])
                    ->simplePaginate($request->input('results', 10)));

        $data = $data->map(function($item, $key) {
            $item['foto_posko'] = loadAsset('imageposko', $item['foto_posko']);
            return $item;
        });

        return ResponseApi::success('Get data Successfully', $data);
    }
}
