<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '4096M');
        DB::disableQueryLog();

        // DB::unprepared(file_get_contents('database/unprepared/region_province.sql'));
        // DB::unprepared(file_get_contents('database/unprepared/region_city.sql'));
        // DB::unprepared(file_get_contents('database/unprepared/region_district.sql'));
        // DB::unprepared(file_get_contents('database/unprepared/region_subdistrict.sql'));
    }
}
