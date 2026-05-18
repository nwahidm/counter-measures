<html>

<head>
    <title>BERITA ACARA PERMINTAAN KETERANGAN</title>
    <style>
        @page {
            margin: 1.5cm 2cm 2cm 2cm;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
        }

        .footer-i {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            color: black;
            text-align: center;
        }
        td{
            vertical-align: top;
        }
    </style>
</head>

<body>
    <table border="0" width="100%">
        <tr>
            <td colspan="2"><span><u>{{ $satker->nama_satker }}</u></span></td>
            <td align="right">IN.10</td>
        </tr>
        <tr>
            <td colspan="3"><br><br></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">
                <span style="font-weight: bold;"><u>BERITA ACARA PERMINTAAN KETERANGAN</u></span>
                <br>
            </td>
        </tr>
        <tr>
            <td colspan="3"><br><br></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: justify;">
                <span>----- Pada hari ini {{ \Carbon\Carbon::parse($data->letter_date)?->isoFormat('dddd') }} tanggal {{ numberToIndonesianWord(date('j', strtotime($data->created_at))) }} bulan {{ \Carbon\Carbon::parse($data->letter_date)?->translatedFormat('F') }} tahun {{ \Carbon\Carbon::parse($data->letter_date)?->year }}, bertempat di {{ $satker->nama_satker }}, saya/kami Jaksa : --------</span>
            </td>
        </tr>
        <tr>
            <td colspan="3"><br><br></td>
        </tr>
    </table>
    <table border="0" width="100%">
        @foreach ($listJaksa as $item)
            <tr>
                <td width="25px">{{ $loop->iteration }}.</td>
                <td width="80px">Nama</td>
                <td width="20px">:</td>
                <td >{{ $item->nama }}</td>
            </tr>   
            <tr>
                <td ></td>
                <td width="80px">Pangkat/NIP</td>
                <td width="20px">:</td>
                <td >{{ $item->nip }}</td>
            </tr>   
            <tr>
                <td ></td>
                <td width="80px">Jabatan</td>
                <td width="20px">:</td>
                <td >Jaksa {{ $item->nip }}</td>
            </tr>   
            <tr>
                <td colspan="3"><br><br></td>
            </tr> 
        @endforeach
    </table>
    <table border="0" width="100%">
        <tr>
            <td colspan="3" style="text-align: justify;">
                <span>Berdasarkan Surat Perintah Nomor : {{ $data->letter_number }}, tanggal {{ numberToIndonesianWord(date('j', strtotime($data->letter_date))) }}, bulan {{ \Carbon\Carbon::parse($data->letter_date)?->translatedFormat('F') }}, tahun {{ \Carbon\Carbon::parse($data->letter_date)?->year }}, telah meminta keterangan terhadap seseorang yang mengaku : ------------</span>
            </td>
        </tr>
    </table>
    <table border="0" width="100%">
        <tr>
            <td width="35px"></td>
            <td width="150px">Nama Lengkap</td>
            <td width="20px">:</td>
            <td >{{ $data->target_name }}</td>
        </tr>   
        <tr>
            <td width="35px"></td>
            <td width="150px">Tempat Lahir</td>
            <td width="20px">:</td>
            <td >{{ $data->born_place }}</td>
        </tr>   
        <tr>
            <td width="35px"></td>
            <td width="150px">Umur / Tgl. Lahir</td>
            <td width="20px">:</td>
            @if ($data->born_date)
                <td >{{ floor(\Carbon\Carbon::createFromFormat('Y-m-d',$data->born_date)->diffInYears(\Carbon\Carbon::now())) }} Tahun / {{ \Carbon\Carbon::parse($data->born_date)?->translatedFormat('d F Y') }}</td>    
            @else
                <td > - Tahun / - </td>    
            @endif
            
        </tr>   
        <tr>
            <td width="35px"></td>
            <td width="150px">Kebangsaan</td>
            <td width="20px">:</td>
            <td >{{ $data->nationality }}</td>
        </tr>   
        <tr>
            <td width="35px"></td>
            <td width="150px">Tempat Tinggal</td>
            <td width="20px">:</td>
            <td >{{ $data->target_address }}</td>
        </tr>   
        <tr>
            <td width="35px"></td>
            <td width="150px">Agama</td>
            <td width="20px">:</td>
            <td >{{ $data->target_religion }}</td>
        </tr>   
        <tr>
            <td width="35px"></td>
            <td width="150px">Pekerjaan</td>
            <td width="20px">:</td>
            <td >{{ $data->target_occupation }}</td>
        </tr>   
        <tr>
            <td width="35px"></td>
            <td width="150px">Pendidikan</td>
            <td width="20px">:</td>
            <td >{{ $data->target_education }}</td>
        </tr>   
        <tr>
            <td width="35px"></td>
            <td width="150px">No. Telp/HP</td>
            <td width="20px">:</td>
            <td >{{ $data->phone_number }}</td>
        </tr>   
        <tr>
            <td width="35px"></td>
            <td width="150px">No. Identitas</td>
            <td width="20px">:</td>
            <td >{{ $data->target_identity_number }}</td>
        </tr>   
        <tr>
            <td colspan="3"><br><br></td>
        </tr> 
    </table>
    <table border="0" width="100%">
        <tr>
            <td colspan="3">
                <span>-------- Ia dimintai keterangan sehubungan dengan adanya {{ $data->perihal }} -------</span>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: justify;">
                <span>--------------- Atas pertanyaan saya/kami, ia memberikan jawaban/keterangannya sebagai berikut : ----------</span>
            </td>
        </tr>
    </table>
    <table border="0" width="100%">
        <tr>
            <td colspan="3"><br><br></td>
        </tr>
        <tr>
            <td colspan="2"><span><u>PERTANYAAN</u></span></td>
            <td align="right"><u>JAWABAN</u></td>
        </tr>
        <tr>
            <td colspan="3"><br><br></td>
        </tr>
    </table>
    <table border="0" width="100%">
        <tr>
            <td colspan="3">
                <span>{!! $data->hasil !!}</span>
            </td>
        </tr>
    </table>
    <table border="0" width="100%">
        <tr>
            <td colspan="3"><br><br></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: justify;">
                <span >------------Setelah Berita Acara ini dibaca kembali oleh yang memberikan keterangan, ia tetap pada keterangannya seperti tersebut di atas dan membenarkan dengan membubuhkan tanda tangannya.-----</span>
            </td>
        </tr>
    </table>
    <table border='0' autosize="1" width=100% cellpadding="0" cellspacing="0"
               style="page-break-inside:avoid; margin: 40px 0 0 0;">
        <tr>
            <td width="200px"></td>
            <td></td>
            <td style="text-align: center;">
                Yang Memberikan Keterangan
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <br><br><br><br><br>
            </td>
        </tr>
        <tr>
            <td width="200px"></td>
            <td></td>
            <td style="text-align: center;">
                <b><u>{{ strtoupper($data->target_name) }}</u></b><br>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"></td>
        </tr>
    </table>
    <table border="0" width="100%">
        <tr>
            <td colspan="3"><br><br></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: justify;">
                <span >-----------Demikianlah Berita Acara Permintaan Keterangan ini dibuat dengan sebenarnya atas kekuatan sumpah jabatan, kemudian ditutup dan ditanda tangani pada waktu dan tempat sebagaimana tersebut di atas.-------------------------------</span>
            </td>
        </tr>
    </table>
    <table border='0' autosize="1" width=100% cellpadding="0" cellspacing="0"
               style="page-break-inside:avoid; margin: 40px 0 0 0;">
        <tr>
            <td width="200px"></td>
            <td></td>
            <td style="text-align: center;">
                <u>{{$satker->city}}, {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y') }}</u>
                <br>
                Yang Meminta Keterangan
            </td>
        </tr>
        @foreach ($listJaksa as $item)
            <tr>
                <td style="text-align: center;">
                    <br><br><br><br><br>
                </td>
            </tr>
            <tr>
                <td width="200px"></td>
                <td></td>
                <td style="text-align: center;">
                    <b><u>{{ strtoupper($item->nama) }}</u></b><br>
                    {{ ('Pangkat / NIP.' . $item->nip) }}
                </td>
            </tr>
        @endforeach
        
        <tr>
            <td colspan="2" style="text-align: center;"></td>
        </tr>
    </table>

        
    </body>

</html>