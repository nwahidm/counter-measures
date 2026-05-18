<?php

namespace Database\Seeders;

use App\Models\MasterWilayah;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = File::get(database_path('json/wilayah.json'));
        $jobs = json_decode($jobs);

        foreach ($jobs as $job) {
            MasterWilayah::updateOrCreate(
            [
                'id_wilayah' => $job->id_wilayah,
                'nama' => $job->nama,
                'kode' => $job->kode,
                'level' => $job->level
            ]);
        }
    }
}
