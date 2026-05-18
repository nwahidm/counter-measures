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
        <!-- <td valign="top" align="center" rowspan="2"><img src="{{ public_path('assets/kejaksaan.png') }}" width="12%">
        </td> -->
        {{-- <td valign="top" align="center" style="font-size:22"><strong>KEJAKSAAN REPUBLIK INDONESIA</strong></td>
        --}}
        @if($data->satker->tipe_satker == '4')
        <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{
                $data->satker->nama_satker }} <br>ASISTEN BIDANG INTELIJEN</strong></td>
        @elseif($data->satker->tipe_satker == '3')
        <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{
                $data->satker->nama_satker }} <br>ASISTEN BIDANG INTELIJEN</strong></td>
        @elseif($data->satker->tipe_satker == '2')
        <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{
                $data->satker->nama_satker }}<br>ASISTEN BIDANG INTELIJEN</strong></td>
        @else
        <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>KEJAKSAAN
                AGUNG<br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
        @endif
    </tr>
    <!-- <tr>
        @if($data->tipe_satker == '4')
        <td valign="top" align="center">{{ $data->satker->alamat_satker }}</td>
        @elseif($data->tipe_satker == '3')
        <td valign="top" align="center">{{ $data->satker->alamat_satker }}</td>
        @elseif($data->tipe_satker == '2')
        <td valign="top" align="center">{{ $data->satker->alamat_satker }}</td>
        @else
        <td valign="top" align="center" style="line-height: 1;">{{ $data->satker->alamat_satker }} <br> Telp (021)
            7236510 . www.kejaksaan.go.id</td>
        @endif
    </tr> -->
    <tr>
        <td valign="top" width="50%" align="center" colspan="2" style="border-top: 5px solid black; height: 2px;"></td>
    </tr>
</table><br>
<table>
    <tr>
        <td align="center" colspan="4" class="header">
            LAPORAN PENYADAPAN
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
        <td>{{ $data->satker?->nama_satker }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Kasus</td>
        <td width="5%">:</td>
        <td style="text-align: justify">{{ $data->case_name }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Tanggal Kasus</td>
        <td width="5%">:</td>
        <td style="text-align: justify">{{ $data->case_date?->isoFormat('DD MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Deskripsi Kasus</td>
        <td width="5%">:</td>
        <td style="text-align: justify">{!! $data->case_description !!}</td>
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
        <td style="text-align: justify">{{ $data->target_name }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Jenis Identitas</td>
        <td width="5%">:</td>
        <td style="text-align: justify">{{ $data->target_identity_number_type }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Nomor Identitas</td>
        <td>:</td>
        <td style="text-align: justify">{{ $data->target_identity_number }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Agama</td>
        <td>:</td>
        <td style="text-align: justify">{{ $data->target_religion }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Jenis Kelamin</td>
        <td>:</td>
        <td style="text-align: justify">{{ $data->target_gender ? $data->target_gender : '-' }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Alamat</td>
        <td>:</td>
        <td style="text-align: justify">{{ $data->target_occupation }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Foto</td>
        <td>:</td>
        <td style="text-align: left;">
            @foreach (json_decode($data->target_photo) as $image)
            <img src="https://rode.kejaksaanri.id/storage/close/case/{{ $image }}" alt="Image"
                style="max-width: 25%;">
            @endforeach
        </td>
    </tr>
    <tr>
        <td colspan="4" class="sub-header">PENYADAPAN DATA PERANGKAT ELEKTRONIK</td>
    </tr>
    @if($data->tappingElectronicDevice->count()>0)
        @foreach ($data->tappingElectronicDevice as $index => $tED)
        @php
        $alphabet = chr(65 + $index); // Convert index to corresponding alphabet letter (A, B, C, etc.)
        $envId = 0; // Initialize a separate counter for validations
        @endphp
        <tr>
            <td></td>
            <td colspan="3"><strong>{{ $alphabet }}. Data Perangkat Elektronik ke - {{ $index + 1 }}</strong></td>
        </tr>
        {{-- <tr>
            <td></td>
            <td>Foto</td>
            <td>:</td>
            <td style="text-align: left;">
                <div class="image-container">
                    @if ($location->target_photo)
                    @foreach (json_decode($location->target_photo) as $image)
                    <img src="https://rode.kejaksaanri.id/storage/{{ $image }}" alt="Image"
                        style="max-width: 20%; padding-top: 60px;">
                    @endforeach
                    @endif
                </div>
            </td>
        </tr> --}}
        <tr>
            <td></td>
            <td>Tgl. Penyadapan</td>
            <td>:</td>
            <td>{{ $tED->tanggal_penyadapan->isoFormat('DD MMMM YYYY') }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Sumber Data</td>
            <td>:</td>
            <td>{{ $tED->sumber_data }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Metode Penyadapan</td>
            <td>:</td>
            <td style="text-align: justify;">{!! $tED->metode_penyadapan !!}</td>
        </tr>
        <tr>
            <td></td>
            <td>Deskripsi Hasil</td>
            <td>:</td>
            <td style="text-align: justify;">{!! $tED->deskripsi_hasil !!}</td>
        </tr>
        {{-- <tr>
            <td></td>
            <td>Dokumen</td>
            <td>:</td>
            <td>{{ $tED->dokumen_upload ? $tED->dokumen_upload : 'Belum ada dokumen.' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Video</td>
            <td>:</td>
            <td>{{ $tED->video_upload ? $tED->video_upload : 'Belum ada video.' }}</td>
        </tr> --}}
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada penyadapan hasil elektronik</td>
        </tr>
    @endif

    <tr>
        <td colspan="4" class="sub-header">PENYADAPAN DATA SINYAL INTEL</td>
    </tr>
    
    @if($data->tappingIntelligentSignal->count()>0)
        @foreach ($data->tappingIntelligentSignal as $tIS)
        @php
        $alphabet2 = chr(97 + $envId); // Convert index to corresponding alphabet letter (a, b, c, etc.)
        $envId++; // Increment the counter only if the condition is met

        $resultId = 0;
        @endphp
        <tr>
            <td></td>
            <td colspan="3"><strong>&bull; Data Sinyal Intel ke - {{ $envId }}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td>Tgl. Penyadapan</td>
            <td>:</td>
            <td>{{ $tIS->tanggal_penyadapan->isoFormat('DD MMMM YYYY') }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jenis Sinyal</td>
            <td>:</td>
            <td>{{ $tIS->jenis_sinyal }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Deskripsi Hasil</td>
            <td>:</td>
            <td style="text-align: justify;">{!! $tIS->deskripsi_hasil !!}</td>
        </tr>
        {{-- <tr>
            <td></td>
            <td>Dokumen</td>
            <td>:</td>
            <td>{{ $tIS->dokumen_upload ? $tIS->dokumen_upload : 'Belum ada dokumen.' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Video</td>
            <td>:</td>
            <td>{{ $tIS->video_upload ? $tIS->video_upload : 'Belum ada video.' }}</td>
        </tr> --}}
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada penyadapan data sinyal intel</td>
        </tr>
    @endif

    <tr>
        <td colspan="4" class="sub-header">PENYADAPAN DATA HASIL CAPAIAN</td>
    </tr>

    @if($data->tappingResultAchievement->count()>0)
        @foreach ($data->tappingResultAchievement as $tRA)
        @php
        $resultId++; // Increment the counter only if the condition is met
        @endphp
        <tr>
            <td></td>
            <td colspan="3"><strong>&bull; Hasil yang Dicapai ke - {{ $resultId }}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td>Hasil yang Dicapai</td>
            <td>:</td>
            <td style="text-align: justify;">{!! $tRA->hasil_yang_dicapai !!}</td>
        </tr>
        {{-- <tr>
            <td></td>
            <td>Dokumen</td>
            <td>:</td>
            <td>{{ $tRA->upload_hasil_yang_dicapai ? $tRA->upload_hasil_yang_dicapai : 'Belum ada dokumen.' }}</td>
        </tr> --}}
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada hasil capaian</td>
        </tr>
    @endif
    
</table>