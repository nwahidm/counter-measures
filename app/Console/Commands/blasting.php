<?php

namespace App\Console\Commands;

use App\Models\MasterWilayah;
use App\Models\QuickCount;
use Illuminate\Console\Command;

class blasting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:blasting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = QuickCount::orderBy('created_at','asc')->limit('1000')->get();
        foreach($data as $d){
            $kelurahan = MasterWilayah::where('kode',$d->kode_kelurahan)->first();
            $kecamatan = MasterWilayah::where('kode',$d->kode_kecamatan)->first();
            $kab_kota = MasterWilayah::where('kode',$d->kode_kab_kota)->first();
            $provinsi = MasterWilayah::where('kode',$d->kode_provinsi)->first();
            $respon = 'Diinformasikan ini adalah Nomor Command Center Pemilu Damai 2024 Kejaksaan Republik Indonesia \n\nBapak/Ibu/Sdr/Sdri '.$d->nama_penanggung_jawab.'\nAnda Terdaftar sebagai Penanggung Jawab pada TPS '.$d->nama_tps.'\nKelurahan '.$kelurahan->nama.'\nKecamatan '.$kecamatan->nama.'\nKab/Kota '.$kab_kota->nama.'\nProvinsi '.$provinsi->nama.'\n\nUntuk pendataan hasil pemungutan suara silahkan ketikan seperti format dibawah ini \n\n200, 205, 203\n\nFormat diatas adalah contoh, angka diatas diurutkan dari Paslon 1, Paslon 2 dan Paslon 3, Silahkan Chat kami kembali dengan format diatas, hanya angkanya saja dengan pemisah (koma)\n\nKami Ucapkan terimakasih atas kerjasamanya\n\n';
            $data = [
                'message_type' => 'text',
                'message' => array(
                    'message' => $respon
                )
            ];
            whatsappNotification($d->nomor_whatsapp, $d->nama_penanggung_jawab, $respon, 'helpdesk');

            QuickCount::where('id',$d->id)->update([
                'is_blasting' => 1
            ]);
        }
        
    }
}
