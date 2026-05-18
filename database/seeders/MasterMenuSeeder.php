<?php

namespace Database\Seeders;

use App\Models\MasterMenu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = File::get(database_path('json/master_menu.json'));
        $menus = json_decode($menus);
        $menus = collect($menus);

        foreach ($menus  as $key => $value) {
            $this->fetchMenu($value);
        }
    }

    private function fetchMenu($data, $parent = null)
    {
        $menu = MasterMenu::updateOrCreate(
            [
                "name" => $data->name,
                "description" => $data->description
            ],
            [
                'group' => $data->group,
                'route_name' => $data->route_name,
                "route_url" => $data->route_url,
                "asset" => $data->asset,
                "parent_id" => $parent == null ? null : $parent->id
            ]
        );

        if (isset($data->child) && count($data->child) > 0) {
            foreach ($data->child as $child) {
                $this->fetchMenu($child, $menu);
            }
        }
    }
}
