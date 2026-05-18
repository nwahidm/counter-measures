<head>
    <style>
        td{
            vertical-align: top;
        }
    </style>
</head>
<table align="center" border="0" style="width: 100%;">
    <tr>
        <!-- <td valign="top"  align="center" rowspan="2" ><img src="{{ public_path('assets/kejaksaan.png') }}" width="12%"> </td> -->
        {{-- <td valign="top" align="center"  style="font-size:22"><strong>KEJAKSAAN REPUBLIK INDONESIA</strong></td> --}}
        @if($data->satker->tipe_satker == '4')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }}<br>JAKSA AGUNG MUDA INTELIJEN </strong></td>
        @elseif($data->satker->tipe_satker == '3')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }}<br>JAKSA AGUNG MUDA INTELIJEN </strong></td>
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
<br>
<table style="width: 100%;">
    <tr>
        <td colspan="4" style="text-align: center; vertical-align: middle; font-size: 18px; font-weight: bold;">INTEROGATION
            REPORT
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
        <td></td>
        <td style="width: 200px;">Satuan Kerja</td>
        <td style="width: 20px;">:</td>
        <td>{{ $data->satker->nama_satker }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Nama Kasus</td>
        <td>:</td>
        <td>{{ $data->nama_kasus }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Tanggal Kasus</td>
        <td>:</td>
        <td>{{ $data->tanggal_kasus->isoFormat('DD MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Deskripsi Kasus</td>
        <td>:</td>
        <td>{{ $data->deskripsi_kasus }}</td>
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
        <td style="width: 20px;">:</td>
        <td>{{ $data->nama_target }}</td>
    </tr>
    <tr>
        <td></td>
        <td style="width: 200px;">Jenis Identitas</td>
        <td style="width: 20px;">:</td>
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
        <td>Pendidikan</td>
        <td>:</td>
        <td>{{ $data->pendidikan }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Pekerjaan</td>
        <td>:</td>
        <td>{{ $data->pekerjaan }}</td>
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
        <td>
            @if($data->foto && sizeof(json_decode($data->foto)) > 0)
                <table>
                    <tr>
                        @foreach(json_decode($data->foto) as $foto)
                            <td><img src="https://rode.kejaksaanri.id/storage/{{ str_replace(" ", "%20", $foto) }}" alt="Image"
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
        <td colspan="4" style="text-align: center; vertical-align: middle;">
            <hr>
        </td>
    </tr>
    <tr>
        <td style="width: 1px; vertical-align: top;"></td>
        <td colspan="3" style="font-size: 16px; font-weight: bold; vertical-align: top;">INTEROGATION INTERVIEW
            <hr>
        </td>
    </tr>
    
    <table style="width: 100%;">
        @if($data->Interoggreport->count() > 0)
            @foreach($data->Interoggreport as $keyInterogRecord => $interogrecord)
                @if(!$interogrecord->berita_acara_path)
                <tr>
                    <td style="vertical-align: top; width: 30px;">{{ $keyInterogRecord + 1 }}.</td>
                    <td>
                        <table>
                            <tr>
                                <td style="width: 160px;">Nomor Surat</td>
                                <td style="width: 20px;">:</td>
                                <td>{{ $interogrecord->letter_number }}</td>
                            </tr>
                            <tr>
                                <td >Tanggal Surat</td>
                                <td>:</td>
                                <td>{{ $interogrecord->letter_date }}</td>
                            </tr>
                            <tr>
                                <td >Perihal</td>
                                <td>:</td>
                                <td>{{ $interogrecord->perihal }}</td>
                            </tr>
                            <tr>
                                <td >Pemberi Keterangan</td>
                                <td>:</td>
                                <td>{{ $interogrecord->target_name }}</td>
                            </tr>
                            <tr>
                                <td >No. Identitas</td>
                                <td>:</td>
                                <td>{{ $interogrecord->target_identity_number }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr></td>
                </tr>
                @endif
            @endforeach
        @else
            <tr>
                <td colspan="4" style="text-align: center;">Belum ada Interogasi Berita Acara.</td>
            </tr>

        @endif
        @if($data->InteroggTarget->count() > 0)
            <tr>
                    <td colspan="2" style="font-size: 16px; font-weight: bold;">INTEROGATION TARGET IDENTIFICATION</td>
            </tr>
            <tr>
                    <td colspan="2"><hr></td>
            </tr>
            @foreach($data->InteroggTarget as $keyInTaget => $intarget)
                    <tr>
                        <td style="vertical-align: top;">{{ $keyInTaget + 1 }}.</td>
                        <td>
                            <table>
                                <tr>
                                    <td>Hasil Target Identifikasi</td>
                                    <td>:</td>
                                    <td style="text-align: justify;"> {!! $intarget->hasil_target_identification !!}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" style="text-align: center;">Belum ada Interogasi Identifikasi Target.</td>
            </tr>
        @endif

        @if($data->interogachievement->count() > 0)
            <tr>
                <td colspan="2" style="font-size: 16px; font-weight: bold;">INTEROGATION RESULT</td>
            </tr>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            @foreach($data->interogachievement as $keyHasil => $hasil)
                <tr>
                    <td style="vertical-align: top;">{{ $keyHasil + 1 }}.</td>
                    <td>
                        <table>
                            <tr>
                                <td>Hasil Yang Dicapai</td>
                                <td>:</td>
                                <td style="text-align: justify;">{{ strip_tags($hasil->hasil_yang_dicapai) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr></td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" style="text-align: center;">Belum ada Interogasi Hasil Capaian.</td>
            </tr>
        @endif
        
    </table>
   
</table>
