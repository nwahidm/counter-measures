<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KegiatanPosko;
use App\Models\MasterSatker;
use App\Models\Posko;
use App\Models\Dct;
use App\Models\Penduduk;
use Redirect;
use URL;
use Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Exception;
use PDF;
use Mail;
use Excel;
use Auth;

class SyncDctKependudukan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:syncdctktp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dct = Dct::limit(20)->where('status_sync_ktp', null)->select('id_calon', 'pas_photo','status_sync_ktp')->get();
        $data="Gagal Validasi";
        foreach($dct as $val){
            $fileData = 'https://infopemilu.kpu.go.id/dct/'.$val->pas_photo;
            $prefixname = 'CallFrFotoDct.jpeg';
            $path = public_path().'/storage/filefotoktp/'.$prefixname;
            file_put_contents(public_path().'/storage/filefotoktp/'.$prefixname, $fileData);
            
            $response = Http::attach('image', file_get_contents($fileData), $prefixname)->post('http://192.168.12.5:801/api/dukcapil/recognition', [
                'images' => $fileData,
            ]);

            // Respon API FR
            if ($response->successful()) {
                $result = json_decode($response->body());
                $data = collect();
                foreach ($result as $key => $value) {
                    $data[$key] = $value;
                }
                $data->prepend('200', 'status');
                $result = json_decode($data['response'], true);
                $data = $result['face']['FACE_T5'];
                $nik = array_keys($data)[0];

                // API CALL NIK
                $response = Http::post('http://192.168.12.5:801/api/dukcapil/origin/nik', 
                                [
                                    'NIK' => $nik
                                ]
                            );

                if ($response->successful()) {
                    $result = json_decode($response->body());
                    if (is_object($result)) {
                        $data = collect();
                        foreach ($result as $key => $value) {
                            $data[$key] = $value;
                        }
                        $data->prepend('200', 'status');
                        $data = $data['content'];
                        CreateToPendudukHelper($data);
                    }
                    else {
                        $data = [
                            'status'    => '404',
                            'ket'       => $result
                        ];
                    }
                }
                // End API CALL NIK
            }
            // End Respon API FR

            Dct::where('id_calon', $val->id_calon)->update([
                'status_sync_ktp' => 1
            ]);
        }
        return response()->json($data);
    }
}
