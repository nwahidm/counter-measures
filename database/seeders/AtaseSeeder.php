<?php

namespace Database\Seeders;

use App\Models\MasterWilayah;
use App\Models\Satker;
use App\Models\WilayahSatker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AtaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        $atase1 = Satker::create([
            'id_satker' => 537,
            'parent_id'       => 1,
            'nama_satker'   => 'Atase Kejaksaan KJRI Hong Kong',
            'website_satker'      => 'atasehongkong@kejaksaan.com',
            'alamat_satker'   => 'Causeway Bay, 127 - 129 Leighton Road; 6-8 Keswick Street Causeway Bay Hong Kong',
            'telp_satker'   => '085236510201',
            'kode_satker'   => '00.01',
            'provinsi' => null,
            'city' => 'Causeway Bay',
            'foto_sakter' => null,
            'lat' => '22.278731261991418',
            'long' => '114.18664357671685',
            'tipe_satker' => 2,
            'rating' => null
        ]);

        $atase2 = Satker::create([
            'id_satker' => 538,
            'parent_id'       => 1,
            'nama_satker'   => 'Atase Kejaksaan KBRI Bangkok',
            'website_satker'      => 'atasebangkok@kejaksaan.com',
            'alamat_satker'   => '600, PHETCHABURI 602 PHETCHABURI RD, THANON PHETCHABURI, RATCHATHEWI, BANGKOK 10400, THAILAND',
            'telp_satker'   => '+66 800799712',
            'kode_satker'   => '00.02',
            'provinsi' => null,
            'city' => 'Bangkok',
            'foto_sakter' => null,
            'lat' => '13.751265154496053',
            'long' => '100.53657475646884',
            'tipe_satker' => 2,
            'rating' => null
        ]);

        $atase3 = Satker::create([
            'id_satker' => 539,
            'parent_id'       => 1,
            'nama_satker'   => 'Atase Kejaksaan KBRI Riyadh',
            'website_satker'      => 'ataseriyadh@kejaksaan.com',
            'alamat_satker'   => 'MJJF+MRC, Amr Aldamri St, Al Safarat, Riyadh 12512, Arab Saudi',
            'telp_satker'   => '+966 11 488 2800',
            'kode_satker'   => '00.03',
            'provinsi' => null,
            'city' => 'Riyadh',
            'foto_sakter' => null,
            'lat' => '24.682039951532104',
            'long' => '46.624528157671676',
            'tipe_satker' => 2,
            'rating' => null
        ]);

        $atase4 = Satker::create([
            'id_satker' => 540,
            'parent_id'       => 1,
            'nama_satker'   => 'Atase Kejaksaan KBRI Singapura',
            'website_satker'      => 'atasesingapura@kejaksaan.com',
            'alamat_satker'   => ' 7 Chatsworth Rd, Singapore 249761',
            'telp_satker'   => '+65 6737 7422',
            'kode_satker'   => '00.04',
            'provinsi' => null,
            'city' => 'Chatsworth Rd',
            'foto_sakter' => null,
            'lat' => '1.2997083909052067',
            'long' => '103.82318085343364',
            'tipe_satker' => 2,
            'rating' => null
        ]);

        $masterWilayah1 = MasterWilayah::create([
            'id_wilayah'       => 92001,
            'nama'       => 'Hongkong',
            'kode'   => 1,
            'level'      => 'PROVINSI'
        ]);

        $masterWilayah2 = MasterWilayah::create([
            'id_wilayah'       => 92002,
            'nama'       => 'Bangkok',
            'kode'   => 2,
            'level'      => 'PROVINSI'
        ]);

        $masterWilayah3 = MasterWilayah::create([
            'id_wilayah'       => 92003,
            'nama'       => 'Riyadh',
            'kode'   => 3,
            'level'      => 'PROVINSI'
        ]);

        $masterWilayah4 = MasterWilayah::create([
            'id_wilayah'       => 92004,
            'nama'       => 'Singapura',
            'kode'   => 4,
            'level'      => 'PROVINSI'
        ]);

        $masterWilayahSatker1 = WilayahSatker::create([
            'id_satker'       => 537,
            'id_wilayah'   => 92001,
        ]);

        $masterWilayahSatker2 = WilayahSatker::create([
            'id_satker'       => 538,
            'id_wilayah'   => 92002,
        ]);

        $masterWilayahSatker3 = WilayahSatker::create([
            'id_satker'       => 539,
            'id_wilayah'   => 92003,
        ]);

        $masterWilayahSatker4 = WilayahSatker::create([
            'id_satker'       => 540,
            'id_wilayah'   => 92004,
        ]);

        DB::commit();
    }
}
