<?php

namespace App\Imports;

use App\Models\DataDesa;
use Maatwebsite\Excel\Concerns\ToModel;

class DataDesaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DataDesa([
            'id_satker'     => $row[0],
            'kode_satker'    => $row[1],
            'provinsi_id'    => $row[2],
            'kota_id'    => $row[3],
            'kecamatan_id'    => $row[4],
            'desa_id'    => $row[5],
            'tahun'    => $row[6],
            'kades_nama'    => $row[7],
            'kades_nomor'    => $row[8],
            'sekdes_nama'    => $row[9],
            'sekdes_nomor'    => $row[10],
            'bendes_nama'    => $row[11],
            'bendes_nomor'    => $row[12],
            'kepbpd_nama'    => $row[13],
            'kepbpd_nomor'    => $row[14],
            'status'    => $row[15],
            'dana_desa'    => $row[16],
            'alokasi_dandes'    => $row[17],
            'dana_bantuan'    => $row[18],
            'dana_bagi_hasil'    => $row[19],
        ]);
    }
}
