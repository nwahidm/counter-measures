<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SatkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $satkers = File::get(database_path('json/satker.json'));
        $satkers = json_decode($satkers);

        foreach ($satkers as $satker) {
            MasterSatker::updateOrCreate(
            [
                'id_satker' => $satker->id_satker
            ],
            [   
                'tipe_satker' => $satker->tipe_satker,
                'kode_satker' => $satker->kode_satker,
                'nama_satker' => $satker->nama_satker,
                'parent_id' => empty($satker->parent_id) ? null : $satker->parent_id,
                'provinsi' => $satker->provinsi,
                'city' => $satker->city,
                'alamat_satker' => $satker->alamat_satker,
                'foto_sakter' => $satker->foto_sakter,
                'lat' => $satker->lat,
                'long' => $satker->long,
                'telp_satker' => $satker->telp_satker,
                'website_satker' => $satker->website_satker,
                'rating' => $satker->rating
            ]);
        }
    }
}
