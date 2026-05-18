<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\MasterPekerjaan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobs = File::get(database_path('json/list_job.json'));
        $jobs = json_decode($jobs);

        foreach ($jobs as $job) {
            MasterPekerjaan::updateOrCreate(
            [
                'kode' => $job->key
            ],
            [   
                'nama' => $job->key
            ]);
        }
    }
}
