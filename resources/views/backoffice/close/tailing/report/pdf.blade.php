<style>
    table td {
        text-align: justify;
        vertical-align: top;
    }
</style>
<table align="center" border="0" style="width: 100%;">
    <tr>
        <!-- <td valign="top"  align="center" rowspan="2" ><img src="{{ public_path('assets/kejaksaan.png') }}" width="12%"> </td> -->
        {{-- <td valign="top" align="center" style="font-size:22"><strong>KEJAKSAAN REPUBLIK INDONESIA</strong></td>
        --}}
        @if($satker->tipe_satker == '4')
            <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA
                    <br>{{ $satker->nama_satker }}<br>ASISTEN BIDANG INTELIJEN </strong></td>
        @elseif($satker->tipe_satker == '3')
            <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA
                    <br>{{ $satker->nama_satker }}<br>ASISTEN BIDANG INTELIJEN </strong></td>
        @elseif($satker->tipe_satker == '2')
            <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA
                    <br>{{ $satker->nama_satker }}<br>ASISTEN BIDANG INTELIJEN</strong></td>
        @else
            <td valign="top" align="center" style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>KEJAKSAAN
                    AGUNG<br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
        @endif
    </tr>
    <!-- <tr>
        @if($satker->tipe_satker == '4')
            <td valign="top" align="center">{{ $satker->alamat_satker }}</td>
        @elseif($satker->tipe_satker == '3')
            <td valign="top" align="center">{{ $satker->alamat_satker }}</td>
        @elseif($satker->tipe_satker == '2')
            <td valign="top" align="center">{{ $satker->alamat_satker }}</td>
        @else
            <td valign="top" align="center" style="line-height: 1;">{{ $satker->alamat_satker }} <br> Telp (021) 7236510 . www.kejaksaan.go.id</td>
        @endif
    </tr> -->
    <tr>
        <td valign="top" width="50%" align="center" colspan="2" style="border-top: 5px solid black; height: 2px;"></td>
    </tr>
</table><br>
<table style="width: 100%;">
    <tr>
        <td colspan="4" style="text-align: center; vertical-align: middle; font-size: 18px; font-weight: bold;">LAPORAN
            PEMBUNTUTAN
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center; vertical-align: middle;">
            <hr>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3" style="font-size: 14px; font-weight: bold;">DETAIL KASUS</td>
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
        <td></td>
        <td colspan="3" style="text-align: center; vertical-align: middle;">
            <hr>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3" style="font-size: 14px; font-weight: bold;">BIODATA TARGET</td>
    </tr>
    <tr>
        <td></td>
        <td style="width: 200px;">Nama</td>
        <td style="width: 200px;">:</td>
        <td>{{ $case->target_name }}</td>
    </tr>
    <tr>
        <td></td>
        <td style="width: 200px;">Jenis Identitas</td>
        <td style="width: 200px;">:</td>
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
                        @foreach(json_decode($case->target_photo) as $foto)
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
        <td colspan="4" class="no-border-top no-border-bottom">
            <hr>
        </td>
    </tr>

    <tr>
        <td></td>
        <td colspan="3" style="font-size: 14px; font-weight: bold;">PEMBUNTUTAN PEMAHAMAN PERILAKU</td>
    </tr>
    @if($case->tailingPemahamanPerilaku->count() > 0)
        @foreach ($case->tailingPemahamanPerilaku as $index => $tailing_pemahaman_perilaku_data)
            @php
                $alphabet = chr(65 + $index); // Convert index to corresponding alphabet letter (A, B, C, etc.)
                $validationIndex = 0; // Initialize a separate counter for validations
            @endphp
            <tr>
                <td></td>
                <td colspan="3"><strong>{{ $alphabet }}. Pemahaman Perilaku ke - {{ $index + 1 }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Nama Target Pembuntutan</td>
                <td>:</td>
                <td>{{strip_tags($tailing_pemahaman_perilaku_data->perilaku_tercatat) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Perilaku Tercatat</td>
                <td>:</td>
                <td>{{ strip_tags($tailing_pemahaman_perilaku_data->perilaku_tercatat) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Aktivitas Rutin</td>
                <td>:</td>
                <td>{{ strip_tags($tailing_pemahaman_perilaku_data->aktivitas_rutin) }}</td>
            </tr>

            <tr>
                <td></td>
                <td>Hubungan Sosial</td>
                <td>:</td>
                <td>{{ strip_tags($tailing_pemahaman_perilaku_data->hubungan_sosial) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Prediksi Perilaku</td>
                <td>:</td>
                <td>{{ strip_tags($tailing_pemahaman_perilaku_data->prediksi_perilaku) }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada pemahaman perilaku</td>
        </tr>
    @endif

    <tr>
        <td></td>
        <td colspan="3" style="font-size: 14px; font-weight: bold;">PEMBUNTUTAN TARGET OPERASI</td>
    </tr>

    @if($case->tailingTargetOperasi->count() > 0)
        @foreach ($case->tailingTargetOperasi as $tailing_target_operasi_data)
            @php
                $alphabet2 = chr(97 + $validationIndex); // Convert index to corresponding alphabet letter (a, b, c, etc.)
                $validationIndex++; // Increment the counter only if the condition is met

                $validationIndex2 = 0;
            @endphp
            <tr>
                <td></td>
                <td colspan="3"><strong>&bull; Target Operasi ke - {{ $validationIndex }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Rencana Operasi</td>
                <td>:</td>
                <td>{{ strip_tags($tailing_target_operasi_data->rencana_target_operasi) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Target Operasi</td>
                <td>:</td>
                <td>{{ strip_tags($tailing_target_operasi_data->target_operasi) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Skenario</td>
                <td>:</td>
                <td>{{ strip_tags($tailing_target_operasi_data->skenario_target_operasi) }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada target operasi</td>
        </tr>
    @endif

    <tr>
        <td></td>
        <td colspan="3" style="font-size: 14px; font-weight: bold;">PEMBUNTUTAN HASIL CAPAIAN</td>
    </tr>
    @if($case->tailingResultAchievement->count() > 0)
        @foreach ($case->tailingResultAchievement as $tailing_result_achievement_data)
            @php
                $validationIndex2++; // Increment the counter only if the condition is met
            @endphp
            <tr>
                <td></td>
                <td colspan="3"><strong>&bull; Hasil yang Capaian ke - {{ $validationIndex2 }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Capaian Hasil</td>
                <td>:</td>
                <td>{{ strip_tags($tailing_result_achievement_data->hasil_yang_dicapai) }}</td>
            </tr>


        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada hasil capaian</td>
        </tr>
    @endif


</table>