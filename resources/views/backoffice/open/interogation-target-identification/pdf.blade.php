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
        @if($data->case->satker->tipe_satker == '4')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }} </strong></td>
        @elseif($data->case->tipe_satker == '3')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }} </strong></td>
        @elseif($data->case->tipe_satker == '2')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }}</strong></td>
        @else
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>KEJAKSAAN AGUNG</strong></td>
        @endif
    </tr>
    
    <tr>
        <td valign="top" width="50%" align="center" colspan="2" style="border-top: 5px solid black; height: 2px;"></td>
    </tr>
</table>
<table>
    <tr>
        <td colspan="4" class="header">
            LAPORAN INTEROGASI IDENTIFIKASI TARGET (AUDIO KE TEKS)
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
        <td>{{ $data->case->satker->nama_satker }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Kasus</td>
        <td width="5%">:</td>
        <td>{{ $data->case->nama_kasus }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Tanggal Kasus</td>
        <td width="5%">:</td>
        <td>{{ $data->case->tanggal_kasus?->isoFormat('DD MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Deskripsi Kasus</td>
        <td width="5%">:</td>
        <td>{{ $data->case->deskripsi_kasus }}</td>
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
        <td>{{ $data->case->nama_target }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Jenis Identitas</td>
        <td width="5%">:</td>
        <td>{{ $data->case->tipe_identitas }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Nomor Identitas</td>
        <td>:</td>
        <td>{{ $data->case->no_identitas }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Agama</td>
        <td>:</td>
        <td>{{ $data->case->agama }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Jenis Kelamin</td>
        <td>:</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $data->case->alamat }}</td>
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
        <td colspan="4" class="no-border-top no-border-bottom">
            <hr>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="sub-header">NASKAH WAWANCARA</td>
    </tr>
    @forelse ($video_audio_analytics_data ?? [] as $index => $videoAudioAnalyticsData)
        <!-- Your loop code here -->
        <tr>
            <td></td>
            <td>{{ $videoAudioAnalyticsData->speaker }}</td>
            <td>:</td>
            <td>{{ $videoAudioAnalyticsData->generated_text }}</td>
        </tr>
    @empty
    <tr>
        <td colspan="4" style="text-align: center;">Belum ada Naskah Wawancara.</td>
    </tr>
    @endforelse

    
</table>