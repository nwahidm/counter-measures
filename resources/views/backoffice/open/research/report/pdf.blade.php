<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 0px solid black;
    }

    table th,
    table td {
        text-align: left;
        vertical-align: top;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    .header,
    .sub-header {
        text-align: center;
        vertical-align: middle;
        font-size: 18px;
        font-weight: bold;
    }

    .sub-header {
        font-size: 14px;
    }

    .image-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-container img {
        transform: rotate(90deg);
        /* Rotate the image by 90 degrees */
        max-width: 100%;
        height: auto;
    }

    .no-border {
        border: none;
    }

    .no-border-top {
        border-top: none;
    }

    .no-border-bottom {
        border-bottom: none;
    }

    .justify-content {
        text-align: justify;
    }
</style>
<table align="center" border="0" style="width: 100%;">
    <tr>
        <!-- <td valign="top"  align="center" rowspan="2" ><img src="{{ public_path('assets/kejaksaan.png') }}" width="12%"> </td> -->
        {{-- <td valign="top" align="center"  style="font-size:22"><strong>KEJAKSAAN REPUBLIK INDONESIA</strong></td> --}}
        @if($data->satker->tipe_satker == '4')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }}  <br>ASISTEN BIDANG INTELIJEN</strong></td>
        @elseif($data->satker->tipe_satker == '3')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }}  <br>ASISTEN BIDANG INTELIJEN </strong></td>
        @elseif($data->satker->tipe_satker == '2')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }} <br>ASISTEN BIDANG INTELIJEN </strong></td>
        @else
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>KEJAKSAAN AGUNG  <br>JAKSA AGUNG MUDA INTELIJEN </strong></td>
        @endif

    </tr>
    <!-- <tr>
        @if($data->satker->tipe_satker == '4')
            <td valign="top" align="center">{{ $data->satker->alamat_satker }}</td>
        @elseif($data->satker->tipe_satker == '3')
            <td valign="top" align="center">{{ $data->satker->alamat_satker }}</td>
        @elseif($data->tipe_satker == '2')
            <td valign="top" align="center">{{ $data->satker->alamat_satker }}</td>
        @else
            <td valign="top" align="center" style="line-height: 1;">{{ $data->satker->alamat_satker }} <br> Telp (021) 7236510 . www.kejaksaan.go.id</td>
        @endif
    </tr> -->
    <tr>
        <td valign="top" width="50%" align="center" colspan="2" style="border-top: 5px solid black; height: 2px;"></td>
    </tr>
</table>

<table>
    <tr>
        <td colspan="4" class="header">
            PENELITIAN LAPORAN
        </td>
    </tr>
    <tr>
        <td colspan="4" class="no-border-top no-border-bottom">
            <hr>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="sub-header">DETAIL KASUS</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Satuan Kerja</td>
        <td width="5%">:</td>
        <td>{{ $data->satker->nama_satker }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Kasus</td>
        <td width="5%">:</td>
        <td>{{ $data->nama_kasus }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Tanggal Kasus</td>
        <td width="5%">:</td>
        <td>{{ $data->tanggal_kasus?->isoFormat('DD MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Deskripsi Kasus</td>
        <td width="5%">:</td>
        <td>{{ $data->deskripsi_kasus }}</td>
    </tr>


    <tr>
        <td colspan="4" class="no-border-top no-border-bottom">
            <hr>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="sub-header">BIODATA TARGET</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Nama</td>
        <td width="5%">:</td>
        <td>{{ $data->nama_target }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Jenis Identitas</td>
        <td width="5%">:</td>
        <td>{{ $data->tipe_identitas }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Nomor Identitas</td>
        <td>:</td>
        <td>{{ $data->no_identitas }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Agama</td>
        <td>:</td>
        <td>{{ $data->agama }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Jenis Kelamin</td>
        <td>:</td>
        <td>{{ $data->jenis_kelamin }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $data->alamat }}</td>
    </tr>


    <tr>
        <td></td>
        <td>Foto</td>
        <td>:</td>
        <td style="text-align: left;">

            <div class="image-container">
                @if(!empty($data->foto[0]))
                <img src="https://rode.kejaksaanri.id/storage/{{ $data->foto[0] }}" alt="Image"
                    style="max-width: 25%;">
                @else
                <p>No image available</p>
                @endif

            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="sub-header">PENELITIAN SURAT PERINTAH</td>
    </tr>

    @forelse ($data->researchSuratPerintah as $index => $researchSuratPerintah)
        @php
        $alphabet = chr(65 + $index); // Convert index to corresponding alphabet letter (A, B, C, etc.)
        $validationIndex = 0; // Initialize a separate counter for validations
        @endphp
        <tr>
            <td></td>
            <td colspan="3"><strong>{{ $alphabet }}. Informasi Surat Perintah ke - {{ $index + 1 }}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td>No. Surat Perintah</td>
            <td>:</td>
            <td>{{ $researchSuratPerintah->surat_perintah_number }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Perihal Surat Perintah</td>
            <td>:</td>
            <td>{{ $researchSuratPerintah->surat_perintah_perihal }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Tgl. Surat Perintah</td>
            <td>:</td>
            <td>{{ optional($researchSuratPerintah->surat_perintah_date)->isoFormat('DD MMMM YYYY') ??''}}</td>
        </tr>
        <tr>
            <td></td>
            <td>Tgl. Mulai Surat Perintah</td>
            <td>:</td>
            <td>{{ optional($researchSuratPerintah->surat_perintah_date_started)->isoFormat('DD MMMM YYYY')??'' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Tgl. Berakhir Surat Perintah</td>
            <td>:</td>
            <td>{{ optional($researchSuratPerintah->surat_perintah_date_finished)->isoFormat('DD MMMM YYYY') ??''}}</td>
        </tr>
        {{-- End Research Surat Perintah --}}

        
    @empty
    <tr>
        <td colspan="4" style="text-align: center;">Belum ada Surat Perintah.</td>
    </tr>
    @endforelse
    <tr>
        <td colspan="4" class="sub-header">PENELITIAN LAPORAN INFORMASI KHUSUS</td>
    </tr>
    
    @forelse ($data->researchLaporanInformasiKhusus as $index0 => $researchLaporanInformasiKhusus)
        @if(!$researchLaporanInformasiKhusus->file_laporan_informasi_khusus)
        @php
        $alphabet = chr(65 + $index0); // Convert index to corresponding alphabet letter (A, B, C, etc.)
        $validationIndex = 0; // Initialize a separate counter for validations
        @endphp
        <tr>
            <td></td>
            <td colspan="3"><strong>{{ $alphabet }}. Informasi Lapinsus ke - {{ $index0 + 1 }}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td>No. Surat Perintah</td>
            <td>:</td>
            <td>{{ $researchLaporanInformasiKhusus->nomor_surat }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Perihal Surat</td>
            <td>:</td>
            <td>{{ $researchLaporanInformasiKhusus->perihal_surat }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Tgl. Surat</td>
            <td>:</td>
            <td>{{ optional($researchLaporanInformasiKhusus->tanggal_surat)->isoFormat('DD MMMM YYYY') ??'' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Informasi Diperoleh</td>
            <td>:</td>
            <td style="text-align: justify;">{{ strip_tags($researchLaporanInformasiKhusus->informasi_diperoleh) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Sumber Informasi</td>
            <td>:</td>
            <td style="text-align: justify;">{{ strip_tags($researchLaporanInformasiKhusus->sumber_informasi) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Tren Perkembangan</td>
            <td>:</td>
            <td style="text-align: justify;">{{ strip_tags($researchLaporanInformasiKhusus->tren_perkembangan) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Saran Tindak</td>
            <td>:</td>
            <td style="text-align: justify;">{{ strip_tags($researchLaporanInformasiKhusus->saran_tindak) }}</td>
        </tr>
        
        {{-- End Research Laporan Informasi Khusus --}}

        
        @endif
    @empty
    <tr>
        <td colspan="4" style="text-align: center;">Belum ada Laporan Informasi Khusus.</td>
    </tr>
    @endforelse

    <tr>
            <td colspan="4" class="sub-header">PENELITIAN SARAN DAN TINDAK LANJUT</td>
        </tr>

    @forelse ($data->researchSaranTindakLanjut as $index1 => $researchSaranTindakLanjut)
    @php
    $alphabet = chr(65 + $index1); // Convert index to corresponding alphabet letter (A, B, C, etc.)
    $validationIndex = 0; // Initialize a separate counter for validations
    @endphp
    <tr>
        <td></td>
        <td colspan="3"><strong>{{ $alphabet }}. Informasi Saran dan Tindak Lanjut ke - {{ $index1 + 1 }}</strong></td>
    </tr>
    <tr>
        <td></td>
        <td>Tgl. Saran dan Tindak Lanjut</td>
        <td>:</td>
        <td>{{ $researchSaranTindakLanjut->saran_dan_tindak_lanjut_date->isoFormat('DD MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Saran dan Tindak Lanjut</td>
        <td>:</td>
        <td style="text-align: justify;">{{ strip_tags($researchSaranTindakLanjut->saran_dan_tindak_lanjut) }}</td>
    </tr>
    {{-- End Research Saran dan Tindak Lanjut --}}

    @empty
    <tr>
        <td colspan="4" style="text-align: center;">Belum ada Saran dan Tindak Lanjut.</td>
    </tr>
    @endforelse


    <tr>
        <td colspan="4" class="sub-header">PENELITIAN ANCAMAN, GANGGUAN, HAMBATAN, DAN TANTANGAN</td>
    </tr>

    @forelse ($data->researchPotensiAght as $index2 => $researchPotensiAght)
    @php
    $alphabet = chr(65 + $index2); // Convert index to corresponding alphabet letter (A, B, C, etc.)
    $validationIndex = 0; // Initialize a separate counter for validations
    @endphp
    <tr>
        <td></td>
        <td colspan="3"><strong>{{ $alphabet }}. Informasi Potensi Ancaman, Gangguan, Hambatan, dan Tantangan ke - {{ $index2 + 1 }}</strong></td>
    </tr>
   
    <tr>
        <td></td>
        <td>Waktu</td>
        <td>:</td>
        <td>{{ optional($researchPotensiAght->waktu)->isoFormat('DD MMMM YYYY') ??''}}</td>
    </tr>
    <tr>
        <td></td>
        <td>Tempat</td>
        <td>:</td>
        <td>{{ strip_tags($researchPotensiAght->tempat) }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Ancaman</td>
        <td>:</td>
        <td style="text-align: justify;">{{ strip_tags($researchPotensiAght->ancaman) }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Gangguan</td>
        <td>:</td>
        <td style="text-align: justify;">{{ strip_tags($researchPotensiAght->gangguan) }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Hambatan</td>
        <td>:</td>
        <td style="text-align: justify;">{{ strip_tags($researchPotensiAght->hambatan) }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Tantangan</td>
        <td>:</td>
        <td style="text-align: justify;">{{ strip_tags($researchPotensiAght->tantangan) }}</td>
    </tr>
    {{-- End Research Potensi AGHT --}}

    @empty
    <tr>
        <td colspan="4" style="text-align: center;">Belum ada Potensi AGHT.</td>
    </tr>
    @endforelse

   
    

    
</table>