<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SatkerSeeder::class,
            OccupationSeeder::class,
            ReligionSeeder::class,
            RegionSeeder::class,
            WilayahSeeder::class,
            WilayahSatkerSeeder::class,
            MasterPartaiSeeder::class,
            AtaseSeeder::class,
            ClientSeeder::class,
            MasterMenuSeeder::class
        ]);
    }
}
