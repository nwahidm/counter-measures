<?php

namespace Database\Seeders;

use App\Models\WilayahSatker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class WilayahSatkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = File::get(database_path('json/wilayah_satker.json'));
        $jobs = json_decode($jobs);

        foreach ($jobs as $job) {
            WilayahSatker::create(
            [
                'id_wilayah' => $job->id_wilayah,
                'id_satker' => $job->id_satker
            ]);
        }
    }
}
