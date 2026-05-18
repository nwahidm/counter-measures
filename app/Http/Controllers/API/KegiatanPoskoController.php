<?php

namespace App\Http\Controllers\API;

use ResponseApi;
use Illuminate\Http\Request;
use App\Models\KegiatanPosko;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\FetchKegiatanPoskoSentimentEvent;

class KegiatanPoskoController extends Controller
{
    public function list(Request $request)
    {
        $data = KegiatanPosko::join('master_satker', 'kegiatan_posko.id_satker', '=', 'master_satker.id_satker')
                                ->join('master_wilayah', 'kegiatan_posko.id_wilayah', '=', 'master_wilayah.id_wilayah')
                                ->when($request->kode_satker, function($q, $kodeSatker) {
                                    $q->where('kegiatan_posko.kode_satker', $kodeSatker);
                                })
                                ->when($request->tanggal, function($q, $tanggal) {
                                    $q->where('kegiatan_posko.tanggal', $tanggal);
                                })
                                ->orderByDesc('kegiatan_posko.created_at')
                                ->select([
                                    'kegiatan_posko.id', 'kegiatan_posko.kode_satker as kode_satker', 'master_satker.nama_satker as nama_satker',
                                    'master_wilayah.nama as nama_wilayah', 'master_wilayah.level as level_wilayah',
                                    'kegiatan_posko.tanggal as tanggal', 'kegiatan_posko.perihal', 'kegiatan_posko.uraian_singkat', 'kegiatan_posko.trend_perkembangan',
                                    'kegiatan_posko.saran_tindak', 'kegiatan_posko.conclusion as kesimpulan', 
                                    'kegiatan_posko.percent_negative as sentimen_negatif', 'kegiatan_posko.percent_positive as sentimen_positif', 'kegiatan_posko.percent_neutral as sentimen_netral',
                                    'kegiatan_posko.question_what', 'kegiatan_posko.question_why', 'kegiatan_posko.question_who', 'kegiatan_posko.question_when', 'kegiatan_posko.question_where', 'kegiatan_posko.locus', 'kegiatan_posko.tempus'
                                ])
                                ->simplePaginate($request->input('results', 10));

        return ResponseApi::success('Get data Successfully', $data);
    }
}
