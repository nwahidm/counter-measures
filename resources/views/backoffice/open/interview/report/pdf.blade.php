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
</style>
<table align="center" border="0" style="width: 100%;">
    <tr>
        <!-- <td valign="top"  align="center" rowspan="2" ><img src="{{ public_path('assets/kejaksaan.png') }}" width="12%"> </td> -->
        {{-- <td valign="top" align="center"  style="font-size:22"><strong>KEJAKSAAN REPUBLIK INDONESIA</strong></td> --}}
        @if($data->satker->tipe_satker == '4')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }} <br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
        @elseif($data->satker->tipe_satker == '3')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }} <br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
        @elseif($data->satker->tipe_satker == '2')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }}<br>ASISTEN BIDANG INTELIJEN</strong></td>
        @else
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>KEJAKSAAN AGUNG<br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
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
            LAPORAN WAWANCARA
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
        <td colspan="4" class="sub-header">JADWAL WAWANCARA</td>
    </tr>

    @forelse ($data->interviewJadwal as $index => $interviewJadwal)
    @php
    $alphabet = chr(65 + $index); // Convert index to corresponding alphabet letter (A, B, C, etc.)
    $validationIndex = 0; // Initialize a separate counter for validations
    @endphp
    <tr>
        <td></td>
        <td colspan="3"><strong>{{ $alphabet }}. Informasi Jadwal Wawancara ke - {{ $index + 1 }}</strong></td>
    </tr>
    <tr>
        <td></td>
        <td>Nama Pewawancara</td>
        <td>:</td>
        <td>{{ $interviewJadwal->interviewer_name }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Jadwal Wawancara</td>
        <td>:</td>
        <td>{{ $interviewJadwal->interviewer_schedule->isoFormat('DD MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Nama Diwawancara</td>
        <td>:</td>
        <td>{{ $interviewJadwal->source_person_name }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Tipe Identitas Target</td>
        <td>:</td>
        <td>{{ $interviewJadwal->target_type_identity_number }}</td>
    </tr>
    <tr>
        <td></td>
        <td>No. Identitas Target</td>
        <td>:</td>
        <td>{{ $interviewJadwal->target_identity_number }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Jenis Kelamin Target</td>
        <td>:</td>
        <td>{{ $interviewJadwal->target_gender }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Agama Target</td>
        <td>:</td>
        <td>{{ $interviewJadwal->target_religion }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Pekerjaan Target</td>
        <td>:</td>
        <td>{{ $interviewJadwal->target_occupation }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Pendidikan Target</td>
        <td>:</td>
        <td>{{ $interviewJadwal->target_education }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Foto Target</td>
        <td>:</td>
        <td style="text-align: left;">

            <div class="image-container">
                @if(!empty($interviewJadwal->target_photo ))
                <img src="https://rode.kejaksaanri.id/storage/{{ $interviewJadwal->target_photo }}" alt="Image"
                    style="max-width: 25%;">
                @else
                <p>No image available</p>
                @endif

            </div>
        </td>

    </tr>
    @empty
    <tr>
        <td colspan="4" style="text-align: center;">Belum ada Jadwal Wawancara.</td>
    </tr>
    @endforelse
    {{-- End Interview Jadwal --}}


    
   

    <tr>
        <td colspan="4" class="sub-header">SARAN DAN TINDAK LANJUT WAWANCARA</td>
    </tr>
    @forelse ($data->interviewSaranTL as $index1 => $interviewSaranTL)
    @php
    $alphabet = chr(65 + $index1); // Convert index to corresponding alphabet letter (A, B, C, etc.)
    $validationIndex = 0; // Initialize a separate counter for validations
    @endphp
    <tr>
        <td></td>
        <td colspan="3"><strong>{{ $alphabet }}. Informasi Saran dan Tindak Lanjut Wawancara ke - {{ $index1 + 1 }}</strong></td>
    </tr>
    <tr>
        <td></td>
        <td>Tgl. Saran dan Tindak Lanjut</td>
        <td>:</td>
        <td>{{ $interviewSaranTL->saran_dan_tindak_lanjut_date->isoFormat('DD MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Saran dan Tindak Lanjut</td>
        <td>:</td>
        <td style="text-align: justify;">{{ strip_tags($interviewSaranTL->saran_dan_tindak_lanjut) }}</td>
    </tr>
    {{-- End Interview Saran dan Tindak Lanjut --}}

    @empty
    <tr>
        <td colspan="4" style="text-align: center;">Belum ada Saran dan Tindak Lanjut.</td>
    </tr>
    @endforelse

   

    
</table>