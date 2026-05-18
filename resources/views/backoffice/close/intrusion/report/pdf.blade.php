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
        <!-- <td valign="top"  align="center" rowspan="2" ><img src="{{ public_path('assets/kejaksaan.png') }}" width="12%"> </td> -->
        {{-- <td valign="top" align="center" style="font-size:22"><strong>KEJAKSAAN REPUBLIK INDONESIA</strong></td>
        --}}
        @if($data->satker->tipe_satker == '4')
            <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA
                    <br>{{ $data->satker->nama_satker }}<br>JAKSA AGUNG MUDA INTELIJEN </strong></td>
        @elseif($data->satker->tipe_satker == '3')
            <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA
                    <br>{{ $data->satker->nama_satker }}<br>JAKSA AGUNG MUDA INTELIJEN </strong></td>
        @elseif($data->satker->tipe_satker == '2')
            <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA
                    <br>{{ $data->satker->nama_satker }}<br>ASISTEN BIDANG INTELIJEN</strong></td>
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
            <td valign="top" align="center" style="line-height: 1;">{{ $data->satker->alamat_satker }} <br> Telp (021) 7236510 . www.kejaksaan.go.id</td>
        @endif
    </tr> -->
    <tr>
        <td valign="top" width="50%" align="center" colspan="2" style="border-top: 5px solid black; height: 2px;"></td>
    </tr>
</table><br>
<table>
    <tr>
        <td colspan="4" class="header">
            LAPORAN PENYURUPAN
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
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $data->target_occupation }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Foto</td>
        <td>:</td>
        <td style="text-align: left;">
            @foreach (json_decode($data->target_photo) as $image)
                <img src="https://rode.kejaksaanri.id/storage/close/case/{{ $image }}" alt="Image" style="max-width: 25%;">
            @endforeach
        </td>
    </tr>
    <tr>
        <td colspan="4" class="sub-header">LOKASI TARGET PENYURUPAN</td>
    </tr>
    @if($data->intrusionLocation->count() > 0)
        @foreach ($data->intrusionLocation as $index => $location)
            @php
                $alphabet = chr(65 + $index); // Convert index to corresponding alphabet letter (A, B, C, etc.)
                $envId = 0; // Initialize a separate counter for validations
            @endphp
            <tr>
                <td></td>
                <td colspan="3"><strong>{{ $alphabet }}. Lokasi Target ke - {{ $index + 1 }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Foto</td>
                <td>:</td>
                <td style="text-align: left;">
                    <div class="image-container">
                        <!-- @if ($location->target_photo)
                                    @foreach (json_decode($location->target_photo) as $image)
                                    <img src="https://rode.kejaksaanri.id/storage/close/intrusion/target-loc/target-photo/{{ $image }}" alt="Image" style="max-width: 25%;">
                                    @endforeach
                                @endif -->

                        @if ($location->target_photo)
                                    @php
                                        // Decode target_photo JSON string to an array
                                        $images = json_decode($location->target_photo, true);
                        
                                    @endphp

                                    @if (is_array($images) && count($images) > 0)
                                        @foreach ($images as $image)
                                            <img src="https://rode.kejaksaanri.id/storage/{{ str_replace(' ', '%20', $image) }}" alt="Image"
                                                style="max-width: 25%; margin: 10px;">
                                        @endforeach
                                    @else
                                        <p>No valid images found for this location.</p>
                                    @endif
                        @else
                            <p>No photos available for this location.</p>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $location->target_name }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Jenis Identitas</td>
                <td>:</td>
                <td>{{ $location->target_identity_number_type }}</td>
            </tr>
            <tr>
                <td></td>
                <td>No. Identitas</td>
                <td>:</td>
                <td>{{ $location->target_identity_number }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $location->target_gender }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Agama</td>
                <td>:</td>
                <td>{{ $location->target_religion }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Pekerjaan</td>
                <td>:</td>
                <td>{{ $location->target_occupation }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Pendidikan</td>
                <td>:</td>
                <td>{{ $location->target_education }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Lokasi Target</td>
                <td>:</td>
                <td>{{ $location->lokasi_target }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Deskripsi Lokasi</td>
                <td>:</td>
                <td style="text-align: justify;">{{ strip_tags($location->deskripsi_lokasi) }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada lokasi target</td>
        </tr>
    @endif


    <tr>
        <td colspan="4" class="sub-header">LINGKUNGAN TARGET PENYURUPAN</td>
    </tr>
    @if($data->intrusionEnv->count() > 0)
        @foreach ($data->intrusionEnv as $env)

            @php
                $alphabet2 = chr(97 + $envId); // Convert index to corresponding alphabet letter (a, b, c, etc.)
                $envId++; // Increment the counter only if the condition is met

                $resultId = 0;
            @endphp
            <tr>
                <td></td>
                <td colspan="3"><strong>&bull; Lingkungan Target ke - {{ $envId }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Nama Lingkungan</td>
                <td>:</td>
                <td>{{ $env->nama_lingkungan }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Tipe Lingkungan</td>
                <td>:</td>
                <td>{{ strip_tags($env->tipe_lingkungan) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Deskripsi Lingkungan</td>
                <td>:</td>
                <td style="text-align: justify;">{{ strip_tags($env->deskripsi_lingkungan) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Aktivitas Teramati</td>
                <td>:</td>
                <td>{{ strip_tags($env->aktivitas_teramati) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Informasi Terkumpul</td>
                <td>:</td>
                <td>{{ strip_tags($env->informasi_terkumpul) }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada lingkungan target</td>
        </tr>
    @endif

    <tr>
        <td colspan="4" class="sub-header">PENYUSUPAN HASIL CAPAIAN</td>
    </tr>
    @if($data->intrusionResult->count() > 0)
        @foreach ($data->intrusionResult as $result)

            @php
                $resultId++; // Increment the counter only if the condition is met
            @endphp
            <tr>
                <td></td>
                <td colspan="3"><strong>&bull; Hasil Capaian ke - {{ $resultId }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Hasil yang Dicapai</td>
                <td>:</td>
                <td style="text-align: justify;">{{ strip_tags($result->hasil_yang_dicapai) }}</td>
            </tr>

        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada hasil capaian</td>
        </tr>
    @endif

</table>