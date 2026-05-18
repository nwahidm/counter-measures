<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KegiatanPosko;
use App\Models\MasterSatker;
use App\Models\Posko;
use Redirect;
use URL;
use Carbon;
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

class sendPolling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:polling';

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
        $date = Carbon\Carbon::now()->format('Y-m-d');
        $date1 = date('Y-m-d', strtotime('+1 days', strtotime( $date )));
        $data = DB::table('master_satker')
                    ->join('posko', 'posko.id_satker', '=', 'master_satker.id_satker')
                    ->whereNotExists(function ($query) {
                        $query->from('kirka_capres_polling')
                            ->select('kirka_capres_polling.id_satker')
                            ->whereRaw('kirka_capres_polling.id_satker = master_satker.id_satker');
                    })
                    ->whereNotNull('posko.no_penanggung_jawab_posko')
                    ->select('master_satker.nama_satker', 'posko.no_penanggung_jawab_posko', 'posko.penanggung_jawab_nama')
                    ->limit(20)
                    ->get();

        $msg='Tidak Ada Notif ';
        foreach ($data as $value) {
                $phone= $value->no_penanggung_jawab_posko;
                $link = 'inteliz.kejaksaan.go.id';
                $message = '*Notifikasi Posko Pemilu*'."\r\n\r\n".
                        'Yth. Sdr. *'.$value->penanggung_jawab_nama.'*'."\r\n".
                        'Satuan Kerja : '.$value->nama_satker.''. "\r\n". 
                        'Sampai Pada hari ini Tanggal '.$date.' anda belum melakukan input Polling, silahkan melakukan Input Polling pemilu melalui aplikasi https://inteliz.kejaksaan.go.id/kegiatan-posko' . "\r\n\r\n".
                        'Terima Kasih.';

                sendNotifWA($phone, $message);
        }
        return response()->json($data);
    }
}
