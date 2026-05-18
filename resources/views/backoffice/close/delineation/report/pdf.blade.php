<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table, th, td {
        border: 0px solid black;
    }
    table th, table td {
        text-align: left;
        vertical-align: top;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
    }
    .header, .sub-header {
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
        transform: rotate(90deg); /* Rotate the image by 90 degrees */
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
        @if($satker->tipe_satker == '4')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $satker->nama_satker }}<br>JAKSA AGUNG MUDA INTELIJEN<</strong></td>
        @elseif($satker->tipe_satker == '3')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $satker->nama_satker }} <br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
        @elseif($satker->tipe_satker == '2')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $satker->nama_satker }}<br>ASISTEN BIDANG INTELIJEN</strong></td>
        @else
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>KEJAKSAAN AGUNG<br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
        @endif
    </tr>
    <!-- <tr>
        @if($case->tipe_satker == '4')
            <td valign="top" align="center">{{ $case->alamat_satker }}</td>
        @elseif($case->tipe_satker == '3')
            <td valign="top" align="center">{{ $case->alamat_satker }}</td>
        @elseif($case->tipe_satker == '2')
            <td valign="top" align="center">{{ $case->alamat_satker }}</td>
        @else
            <td valign="top" align="center" style="line-height: 1;">{{ $case->alamat_satker }} <br> Telp (021) 7236510 . www.kejaksaan.go.id</td>
        @endif
    </tr> -->
    <tr>
        <td valign="top" width="50%" align="center" colspan="2" style="border-top: 5px solid black; height: 2px;"></td>
    </tr>
</table>
<table>
    <tr>
        <td colspan="4" class="header">
            DELINEATION REPORT
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
        <td>{{ $satker->nama_satker }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Kasus</td>
        <td width="5%">:</td>
        <td>{{ $case->case_name }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Tanggal Kasus</td>
        <td width="5%">:</td>
        <td>{{ $case->case_date?->isoFormat('DD MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Deskripsi Kasus</td>
        <td width="5%">:</td>
        <td style="text-align: justify;">{{ strip_tags($case->case_description) }}</td>
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
        <td>{{ $case->target_name }}</td>
    </tr>
    <tr>
        <td width="10%"></td>
        <td width="30%">Jenis Identitas</td>
        <td width="5%">:</td>
        <td>{{ $case->target_identity_number_type }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Nomor Identitas</td>
        <td>:</td>
        <td>{{ $case->target_identity_number }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Agama</td>
        <td>:</td>
        <td>{{ $case->target_religion }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Jenis Kelamin</td>
        <td>:</td>
        <td>{{ $case->target_gender }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Pekerjaan</td>
        <td>:</td>
        <td>{{ $case->target_occupation }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $case->target_address }}</td>
    </tr>

    
    <tr>
        <td></td>
        <td>Foto</td>
        <td>:</td>
        <td style="text-align: left;">
            
            @if($case->target_photo && sizeof(json_decode($case->target_photo)) > 0)
                <table>
                    <tr>
                        @foreach(json_decode($data->target_photo) as $foto)
                            <td><img src="https://rode.kejaksaanri.id/storage/close/case/{{ str_replace(" ", "%20", $foto) }}" alt="Image"
                                    style="max-width: 25%;"></td>
                        @endforeach
                    </tr>
                </table>
            @else
                Tidak ada foto
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="4" class="sub-header">DELINEATION VERIFIKASI INFORMASI</td>
    </tr>

    @if($case->delineationInformationVerifications->count()>0)
        @foreach ($case->delineationInformationVerifications as $index => $delineation_information_verification_data)
            @php
                $alphabet = chr(65 + $index); // Convert index to corresponding alphabet letter (A, B, C, etc.)
                $validationIndex = 0; // Initialize a separate counter for validations
            @endphp
            <tr>
                <td></td>
                <td colspan="3"><strong>{{ $alphabet }}. Informasi Verifikasi ke - {{ $index + 1 }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Kredibilitas Sumber</td>
                <td>:</td>
                <td>{{ $delineation_information_verification_data->kredibilitas_sumber }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Metode Verifikasi</td>
                <td>:</td>
                <td>{{ $delineation_information_verification_data->metode_verifikasi }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Detail Informasi Verifikasi</td>
                <td>:</td>
                <td style="text-align: justify;">{{ strip_tags($delineation_information_verification_data->detail_informasi_verifikasi) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Diverifikasi Oleh</td>
                <td>:</td>
                <td>{{ strip_tags($delineation_information_verification_data->verified_by) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Tanggal Verifikasi</td>
                <td>:</td>
                
                <td>{{ strip_tags(\Carbon\Carbon::parse($delineation_information_verification_data->verification_date)->isoFormat('DD MMMM YYYY')) }}</td>
            </tr>
        @endforeach

    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada informasi verifikasi</td>
        </tr>
    @endif

    @if(optional($case->delineationInformationValidations)->count()>0)
        @foreach ($case->delineationInformationValidations as $index=> $delineation_information_validation_data)
                @php
                    $alphabet2 = chr(97 + $index); // Convert index to corresponding alphabet letter (a, b, c, etc.)
                   
                @endphp
                <tr>
                    <td></td>
                    <td colspan="3"><strong>&bull; Informasi Validasi ke - {{ $index+1 }}</strong></td>
                </tr>
                <tr>
                    <td></td>
                    <td>Metode Validasi</td>
                    <td>:</td>
                    <td>{{ $delineation_information_validation_data->metode_validasi }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Tanggal Validasi</td>
                    <td>:</td>
                    <td>{{ strip_tags(\Carbon\Carbon::parse($delineation_information_validation_data->tanggal_validasi)->isoFormat('DD MMMM YYYY')) }}</td>

                </tr>
                <tr>
                    <td></td>
                    <td>Catatan Validasi</td>
                    <td>:</td>
                    <td style="text-align: justify;">{{ strip_tags($delineation_information_validation_data->catatan_validasi) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Hasil Validasi</td>
                    <td>:</td>
                    <td style="text-align: justify;">{{ strip_tags($delineation_information_validation_data->hasil_validasi) }}</td>
                </tr>

        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada informasi validasi</td>
        </tr>
    @endif

    @if(optional($case->delineationScenarioRelations)->count()>0)
        @foreach ($case->delineationScenarioRelations as $validationIndex2 => $delineation_scenario_relation_data)
                        @php
                            $validationIndex2++; // Increment the counter only if the condition is met
                        @endphp
                        <tr>
                            <td></td>
                            <td colspan="3"><strong>&bull; Skenario Terhubung ke - {{ $validationIndex2 }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Subjek Utama</td>
                            <td>:</td>
                            <td>{{ $delineation_scenario_relation_data->subjek_utama }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Subjek Terkait</td>
                            <td>:</td>
                            <td>{{ strip_tags($delineation_scenario_relation_data->subjek_terkait) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jenis Relasi</td>
                            <td>:</td>
                            <td>{{ strip_tags($delineation_scenario_relation_data->jenis_relasi) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Kekuatan Relasi</td>
                            <td>:</td>
                            <td>{{ strip_tags($delineation_scenario_relation_data->kekuatan_relasi) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Dampak Potensial</td>
                            <td>:</td>
                            <td>{{ strip_tags($delineation_scenario_relation_data->dampak_potensial) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Catatan Analisa</td>
                            <td>:</td>
                            <td>{{ strip_tags($delineation_scenario_relation_data->catatan_analisa) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Tanggal Pencatatan</td>
                            <td>:</td>
                            <td>{{ strip_tags(\Carbon\Carbon::parse($delineation_scenario_relation_data->tanggal_pencatatan)->isoFormat('DD MMMM YYYY')) }}</td>

                        </tr>
                    
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada skenario terhubung</td>
        </tr>
    @endif
      
</table>
