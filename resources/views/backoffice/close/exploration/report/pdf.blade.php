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
        {{-- <td valign="top" align="center" style="font-size:22"><strong>KEJAKSAAN REPUBLIK INDONESIA</strong></td>
        --}}
        @if($satker->tipe_satker == '4')
            <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA
                    <br>{{ $satker->nama_satker }} <br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
        @elseif($satker->tipe_satker == '3')
            <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA
                    <br>{{ $satker->nama_satker }}<br>JAKSA AGUNG MUDA INTELIJEN </strong></td>
        @elseif($satker->tipe_satker == '2')
            <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA
                    <br>{{ $satker->nama_satker }}<br>ASISTEN BIDANG INTELIJEN</strong></td>
        @else
            <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>KEJAKSAAN
                    AGUNG<br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
        @endif
    </tr>
    <!-- <tr>
        @if($data->tipe_satker == '4')
            <td valign="top" align="center">{{ $data->alamat_satker }}</td>
        @elseif($data->tipe_satker == '3')
            <td valign="top" align="center">{{ $data->alamat_satker }}</td>
        @elseif($data->tipe_satker == '2')
            <td valign="top" align="center">{{ $data->alamat_satker }}</td>
        @else
            <td valign="top" align="center" style="line-height: 1;">{{ $data->alamat_satker }} <br> Telp (021) 7236510 . www.kejaksaan.go.id</td>
        @endif
    </tr> -->
    <tr>
        <td valign="top" width="50%" align="center" colspan="2" style="border-top: 5px solid black; height: 2px;"></td>
    </tr>
</table>
<table style="width: 100%;">
    <tr>
        <td colspan="4" class="header">
            EXPLORATION REPORT
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
        <td>{{ $data->nama_satker }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Kasus</td>
        <td width="5%">:</td>
        <td>{{ $data->case_name }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Tanggal Kasus</td>
        <td width="5%">:</td>
        <td>{{ $data->case_date?->isoFormat('DD MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Deskripsi Kasus</td>
        <td width="5%">:</td>
        <td style="text-align: justify;">{{ strip_tags($data->case_description) }}</td>
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
        <td>{{ $data->target_name }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Jenis Identitas</td>
        <td width="5%">:</td>
        <td>{{ $data->target_identity_number_type }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Nomor Identitas</td>
        <td>:</td>
        <td>{{ $data->target_identity_number }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Agama</td>
        <td>:</td>
        <td>{{ $data->target_religion }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Jenis Kelamin</td>
        <td>:</td>
        <td>{{ $data->target_gender }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Pekerjaan</td>
        <td>:</td>
        <td>{{ $data->target_occupation }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $data->target_address }}</td>
    </tr>


    <tr>
        <td></td>
        <td>Foto</td>
        <td>:</td>
        <td style="text-align: left;">

            @if($data->target_photo && sizeof(json_decode($data->target_photo)) > 0)
                <table>
                    <tr>
                        @foreach(json_decode($data->target_photo) as $foto)
                            <td><img src="https://rode.kejaksaanri.id/storage/close/case/{{ str_replace(" ", "%20", $foto) }}"
                                    alt="Image" style="max-width: 25%;"></td>
                        @endforeach
                    </tr>
                </table>
            @else
                Tidak ada foto
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="4" class="sub-header">Penjajakan Rencana Aksi</td>
    </tr>

    @if($data->explorationRencanaAksi->count() > 0)
        @foreach ($data->explorationRencanaAksi as $index => $explorationrencanaaksi)
            @php
                $alphabet = chr(65 + $index); // Convert index to corresponding alphabet letter (A, B, C, etc.)
                $validationIndex = 0; // Initialize a separate counter for validations
            @endphp
            @if($explorationrencanaaksi)
                <tr>
                    <td></td>
                    <td colspan="3"><strong>{{ $alphabet }}. Explorasi Rencana Aksi ke - {{ $index + 1 }}</strong></td>
                </tr>
                <tr>
                    <td width="10%"></td>
                    <td width="30%">Rencana Aksi</td>
                    <td width="5%">:</td>
                    <td>{{ $explorationrencanaaksi->rencana_aksi_data }}</td>
                </tr>
                <tr>
                    <td width="10%"></td>
                    <td width="30%">Keterangan Rencana Aksi</td>
                    <td width="5%">:</td>
                    <td style="text-align: justify;">{{ strip_tags($explorationrencanaaksi->rencana_aksi_detail)}}</td>
                </tr>

            @endif
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada eksplorasi rencana aksi</td>
        </tr>
    @endif

    <tr>
        <td colspan="4" class="sub-header">Exploration Identitas Target</td>
    </tr>

    @if($data->explorationTargetIdentity->count() > 0)
        @foreach ($data->explorationTargetIdentity as $index => $explorationtarget)
            @php
                $alphabet = chr(65 + $index); // Convert index to corresponding alphabet letter (A, B, C, etc.)
                $validationIndex = 0; // Initialize a separate counter for validations
            @endphp

            @if($explorationtarget)
                <tr>
                    <td></td>
                    <td colspan="3"><strong>{{ $alphabet }}. Explorasi Target Identitas ke - {{ $index + 1 }}</strong></td>
                </tr>
                <tr>
                    <td width="10%"></td>
                    <td width="30%">Jenis Identitas</td>
                    <td width="5%">:</td>
                    <td>{{ $explorationtarget->target_identity_number_type ?? 'Data Belum tersedia' }}</td>
                </tr>
                <tr>
                    <td width="10%"></td>
                    <td width="30%">Nomor Identitas</td>
                    <td width="5%">:</td>
                    <td>{{ $explorationtarget->target_identity_number ?? 'Data Belum tersedia' }}</td>
                </tr>
                <tr>
                    <td width="10%"></td>
                    <td width="30%">Jenis Kelamin</td>
                    <td width="5%">:</td>
                    <td>{{ $explorationtarget->target_gender ?? 'Data Belum tersedia' }}</td>
                </tr>
                <tr>
                    <td width="10%"></td>
                    <td width="30%">Agama</td>
                    <td width="5%">:</td>
                    <td>{{ $explorationtarget->target_religion ?? 'Data Belum tersedia' }}</td>
                </tr>
                <tr>
                    <td width="10%"></td>
                    <td width="30%">Pekerjaan</td>
                    <td width="5%">:</td>
                    <td>{{ $explorationtarget->target_occupation ?? 'Data Belum tersedia' }}</td>
                </tr>
                <tr>
                    <td width="10%"></td>
                    <td width="30%">Pendidikan</td>
                    <td width="5%">:</td>
                    <td>{{ $explorationtarget->target_education ?? 'Data Belum tersedia' }}</td>
                </tr>
            @endif
        @endforeach

    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada eksplorasi target identitas</td>
        </tr>
    @endif


    <tr>
        <td colspan="4" class="sub-header">Exploration Hasil Yang dicapai</td>
    </tr>
    @if($data->explorationResultAchievement->count() > 0)
        @foreach ($data->explorationResultAchievement as $index => $explorationresult)
            @php
                $alphabet = chr(65 + $index); // Convert index to corresponding alphabet letter (A, B, C, etc.)
                $validationIndex = 0; // Initialize a separate counter for validations
            @endphp
            @if($explorationresult)
                <tr>
                    <td></td>
                    <td colspan="3"><strong>{{ $alphabet }}. Explorasi Hasil Capaian ke - {{ $index + 1 }}</strong></td>
                </tr>
                <tr>
                    <td width="10%"></td>
                    <td width="30%">Hasil Yang Dicapai</td>
                    <td width="5%">:</td>
                    <td style="text-align: justify;">{{ strip_tags($explorationresult->hasil_yang_dicapai)}}</td>
                </tr>

            @endif
        @endforeach

    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada hasil capaian</td>
        </tr>
    @endif

</table>