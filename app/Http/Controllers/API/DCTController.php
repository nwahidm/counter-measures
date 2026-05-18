<?php

namespace App\Http\Controllers\API;

use ResponseApi;
use App\Models\Dct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\FetchKegiatanPoskoSentimentEvent;

class DCTController extends Controller
{
    public function list(Request $request)
    {
        $data = Dct::join('master_satker', 'dct.id_satker', '=', 'master_satker.id_satker')
                    ->join('master_wilayah', 'dct.id_wilayah', '=', 'master_wilayah.id_wilayah')
                    ->join('master_partai', 'dct.id_partai', 'master_partai.id')
                    ->when($request->kode_satker, function($q, $kodeSatker) {
                        $q->where('master_satker.kode_satker', $kodeSatker);
                    })
                    ->when($request->jenis, function($q, $jenis) {
                        $q->where('dct.jenis', $jenis);
                    })
                    ->when($request->gender, function($q, $gender) {
                        $q->where('dct.jk', $gender);
                    })
                    ->orderByDesc('dct.created_at')
                    ->select([
                        'dct.id', 'dct.kode_satker as kode_satker', 'master_satker.nama_satker as nama_satker',
                        'master_wilayah.nama as nama_wilayah', 'master_wilayah.level as level_wilayah',
                        'master_partai.nama as nama_partai', 'dct.jenis as jenis_dct', 'dct.nama', 'dct.agama',
                        'dct.pekerjaan', 'dct.alamat', 'dct.jk as gender', 'dct.estimasi_dukungan'
                    ])
                    ->simplePaginate($request->input('results', 10));

        return ResponseApi::success('Get data Successfully', $data);
    }
}
