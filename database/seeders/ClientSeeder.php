<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::updateOrCreate([
            'client_id' => 'INTEL_001'
        ], [
            'client_username' => 'INTEL_USER',
            'client_password' => bcrypt('Inte7P@sSw0rd'),
            'client_key' => '7b27b1a8-33e2-4224-b69e-81aec958d5fc',
            'client_name' => 'INTEL CLIENT'
        ]);
    }
}
