<?php

namespace App\Http\Controllers\API;

use ResponseApi;
use App\Models\Perkara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\FetchKegiatanPoskoSentimentEvent;

class PerkaraController extends Controller
{
    public function listByNik(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nik' => 'required|numeric|digits:16'
        ]);

        if ($validator->fails()){
            return [
                'status' => 422,
                'Ket' => 'Silahkan Isi NIK dan Pastikan NIK 16 Digit'
            ];
        }
        
        $nik = $request->nik;
        $data = Perkara::join('master_satker_perkara', function ($join) {
                                $join
                                    ->on('master_satker_perkara.id_kejati', '=', 'perkaras.id_kejati_new')
                                    ->on('master_satker_perkara.id_kejari', '=', 'perkaras.id_kejari_new')
                                    ->on('master_satker_perkara.id_cabjari', '=', 'perkaras.id_cabjari_new');
                            })
                        ->when($nik, function($q, $nik) {
                            $q->where('perkaras.no_identitas', $nik);
                        })
                        ->select('master_satker_perkara.nama_satker', 'perkaras.nama', 'perkaras.no_identitas', 
                        'perkaras.jenis_identitas', 'perkaras.pasal_disangkakan', 'perkaras.pidana', 'perkaras.perkara', 
                        'perkaras.putusan', 'perkaras.tgl_putusan')
                        ->orderBy('perkaras.id_kejati_new')
                        ->orderBy('perkaras.id_kejari_new')
                        ->orderBy('perkaras.id_cabjari_new')
                        ->first();

        return ResponseApi::success('Get data Successfully', $data);
    }

    public function listByNama(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama' => 'required|min:4'
        ]);

        if ($validator->fails()){
            return [
                'status' => 422,
                'Ket' => 'Silahkan Isi Nama dan Pastkan minimal 5 Huruf'
            ];
        }
        
        $nama = $request->nama;
        $data = Perkara::join('master_satker_perkara', function ($join) {
                                $join
                                    ->on('master_satker_perkara.id_kejati', '=', 'perkaras.id_kejati_new')
                                    ->on('master_satker_perkara.id_kejari', '=', 'perkaras.id_kejari_new')
                                    ->on('master_satker_perkara.id_cabjari', '=', 'perkaras.id_cabjari_new');
                            })
                        ->when($nama, function($q, $nama) {
                            $q->where('perkaras.nama', 'like', '%' . $nama . '%');
                        })
                        ->select('master_satker_perkara.nama_satker', 'perkaras.nama', 'perkaras.no_identitas', 
                        'perkaras.jenis_identitas', 'perkaras.pasal_disangkakan', 'perkaras.pidana', 'perkaras.perkara', 
                        'perkaras.putusan', 'perkaras.tgl_putusan')
                        ->orderBy('perkaras.id_kejati_new')
                        ->orderBy('perkaras.id_kejari_new')
                        ->orderBy('perkaras.id_cabjari_new')
                        ->limit(2)
                        ->get();

        return ResponseApi::success('Get data Successfully', $data);
    }
}
