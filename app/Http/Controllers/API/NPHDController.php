<?php

namespace App\Http\Controllers\API;

use ResponseApi;
use App\Models\NPHD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\FetchKegiatanPoskoSentimentEvent;

class NPHDController extends Controller
{
    public function list(Request $request)
    {
        $data = NPHD::when($request->kode_satker, function($q, $kodeSatker) {
                        $q->where('nphd.kode_satker', $kodeSatker);
                    })
                    ->when($request->tahun, function($q, $tahun) {
                        $q->where('nphd.id_tahun', $tahun);
                    })
                    ->orderByDesc('nphd.created_at')
                    ->select([
                        'nphd.id', 'nphd.kode_satker as kode_satker', 'nphd.nama_satker as nama_satker',
                        'nphd.nama_wilayah as nama_wilayah', 'nphd.level_wilayah as level_wilayah',
                        'nphd.id_tahun as tahun', 'nphd.nphd_kpu', 'nphd.nphd_bawaslu', 'nphd.keterangan'
                    ])
                    ->simplePaginate($request->input('results', 10));

        return ResponseApi::success('Get data Successfully', $data);
    }
}
