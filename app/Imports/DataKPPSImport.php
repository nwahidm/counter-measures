<?php

namespace App\Imports;

use App\Models\QuickCount;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class DataKPPSImport implements ToCollection, WithProgressBar, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    use Importable;

    public function __construct()
    {
        ini_set('memory_limit', '-1');
        HeadingRowFormatter::default('none');
    }

    public function batchSize(): int{
        return 10000;
    }
    public function chunkSize(): int
    {
        return 10000;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 1;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $provinsi = $row['PROVINSI'] ?? '';
            $kab = $row['KABUPATEN'] ?? '';
            $kec = $row['KECAMATAN'] ?? '';
            $kel = $row['KELURAHAN'] ?? '';
            $kodeProvinsi = splitKodeWilayah(trimOnlyDigit($row['KODE PROVINSI'] ?? ''));
            $kodeKab = splitKodeWilayah(trimOnlyDigit($row['KODE KABUPATEN'] ?? ''));
            $kodeKec = splitKodeWilayah(trimOnlyDigit($row['KODE KECAMATAN'] ?? ''));
            $kodeKel = splitKodeWilayah(trimOnlyDigit($row['KODE KELURAHAN'] ?? ''));
            $namaTPS = trimOnlyDigit($row['TPS'] ?? '');
            $nomorHP = phoneNumber(trimOnlyDigit($row['HP'] ?? ''));
            $namaPJ = $row['PJ'] ?? '';
    
            $quickCount = QuickCount::where([
                                        'kode_kelurahan' => $kodeKel,
                                        'nama_tps' => $namaTPS
                                    ])->first();
    
            if ($quickCount) {
                $nomorHP .= ",{$quickCount->nomor_whatsapp}";
                $namaPJ .= ",{$quickCount->nama_penanggung_jawab}";
    
                $quickCount->update([
                    'nama_penanggung_jawab' => Str::limit($namaPJ, 500),
                    'nomor_whatsapp' => Str::limit($nomorHP, 500)
                ]);
            } else {
                $quickCount =  QuickCount::create([
                    'nama_provinsi' => $provinsi,
                    'nama_kab_kota' => $kab,
                    'nama_kecamatan' => $kec,
                    'nama_kelurahan' => $kel,
                    'kode_provinsi'     => $kodeProvinsi,
                    'kode_kab_kota'    => $kodeKab,
                    'kode_kecamatan'    => $kodeKec,
                    'kode_kelurahan'    => $kodeKel,
                    'nama_tps'    => $namaTPS,
                    'nama_penanggung_jawab'    => Str::limit($namaPJ, 500),
                    'nomor_whatsapp'    => Str::limit($nomorHP, 500),
                    'suara_anis'    => null,
                    'suara_prabowo'    => null,
                    'suara_ganjar'    => null
                ]);
            }
        }
    }
}
