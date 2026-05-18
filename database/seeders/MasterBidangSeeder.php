<?php

namespace Database\Seeders;

use App\Models\MasterBidang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterBidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = File::get(database_path('json/master_bidang.json'));
        $jobs = json_decode($jobs);

        foreach ($jobs as $job) {
            MasterBidang::updateOrCreate(
            [
                'id' => $job->id,
                'description' => $job->description,
                'tipe_satker' => $job->tipe_satker,
            ]);
        }
    }
}
