<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class JagaDesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_satker' => 1,
            'kode_satker' => 1,
            'nama_satker' => 1,
            'id_wilayah' => 1,
            'nama_wilayah' => 1,
            'level_wilayah' => 1,
            'id_tahun' => 1,
            'penanggung_jawab' => 1,
            'jumlah_desa' => 1,
            'jumlah_dana_desa' => 1,
            'alokasi_dana_desa' => 1,
            'dana_bagi_hasil' => 1,
            'dana_bantuan' => 1,
            'bumdes' => 1,
            'permasalahan_hukum' => 1,
            'saran_tindak' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}