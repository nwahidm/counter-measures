<?php

namespace Database\Seeders;

use App\Models\MasterPartai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MasterPartaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = File::get(database_path('json/master_partai.json'));
        $jobs = json_decode($jobs);

        foreach ($jobs as $job) {
            MasterPartai::updateOrCreate(
            [
                'nama' => $job->nama
            ],
            [
                'tanggal_berdiri' => $job->tanggal_berdiri,
                'ketua_umum' => $job->ketua_umum
            ]);
        }
    }
}
