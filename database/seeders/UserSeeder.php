<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\UserPortal;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name'       => 'Superadmin',
            'username'   => 'superadmin',
            'email'      => 'superadmin@gmail.com',
            'password'   => bcrypt('123456'),
            'id_satker' => '1'
        ]);

        $user->assignRole('superadmin');
    }
}
