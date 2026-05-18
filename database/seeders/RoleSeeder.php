<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'superadmin',
            'admin',
            'operator'
        ];
        $this->createRole($roles);

        $listPermissions = [
            'master',
            'master-data',
            'master-wilayah',
            'master-satker',
            'master-wilayah-satker',
            'master-provinsi',
            'master-kota',
            'master-kecamatan',
            'master-desa',
            'master-agama',
            'master-pekerjaan',
            'master-tahun',
            'master-partai',
            'master-capres',
            'master-capres-tahun',
            'master-capres-partai-tahun',
            'master-perkara',
            'master-jenis-pemilihan',

            'read-kirka-capres',
            'modify-kirka-capres',
            'approve-kirka-capres',

            'read-kegiatan-posko',
            'modify-kegiatan-posko',
            'approve-kegiatan-posko',

            'setting',
            'read-dct',
            'modify-dct',
            'approve-dct',

            'read-users',
            'modify-users',
            'approve-users',

            'read-role',
            'modify-role',
            'approve-role',

            'read-nphd',
            'modify-nphd',
            'approve-nphd',

            'read-aght',
            'modify-aght',
            'approve-aght',

            'read-jalan-daerah',
            'modify-jalan-daerah',
            'approve-jalan-daerah',

            'read-logistik-kpu',
            'modify-logistik-kpu',
            'approve-logistik-kpu',

            'read-posko',
            'modify-posko',
            'approve-posko',

            'read-pakem',
            'modify-pakem',
            'approve-pakem',

            'read-paslon',
            'modify-paslon',
            'approve-paslon',
        ];
        $this->createPermissions($listPermissions);

        $this->createSuperadminHasPermission($listPermissions);

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function createRole($roles)
    {
        foreach ($roles as $key => $role) {
            Role::updateOrCreate(
            [
                'name' => $role,
                'guard_name' => 'web'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    private function createPermissions($listPermissions)
    {
        foreach ($listPermissions as $key => $permissions) {
            Permission::updateOrCreate(
            [
                'name' => $permissions,
                'guard_name' => 'web'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    private function createSuperadminHasPermission($listPermissions)
    {
        $role = Role::findByName('superadmin', 'web');
        $role->givePermissionTo(Permission::all());
    }
}
