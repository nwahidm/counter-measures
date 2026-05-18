<html>
    <head>
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
            .avoid-page-break {
                page-break-inside: avoid;
            }
            .text-overflow {
                overflow: hidden;
                text-overflow: ellipsis;
            }
        </style>
    </head>
    <body>

    <p>

    <br>
    <div align="justify" style="Times New Roman, Times, serif; font-size:12;">
        <table border="0" width="100%">
            <tr>
                <td valign="top">
                 
                    <u><font style="font-size:12">{{ $data->case->satker->nama_satker }} </font></u>
                </td>
                <td valign="top" align="right">
                    <font style="font-size:12">
                        L.IN.2 <br>
                        COPY KE 	: ....<br>
                        DARI    	: .... COPIES
                    </font>
                </td>
            </tr>
        </table>
    </p>
    <br>

    <p>
        <div align="center" style="Times New Roman, Times, serif;; font-size:12;"><u>LAPORAN INFORMASI KHUSUS</u></div>
        <div align="center" style="Times New Roman, Times, serif;; font-size:12;">Nomor : {{$data->nomor_surat}}</div>
    </p>
    <br>
    
    <table border="0" style="Times New Roman, Times, serif; font-size:12;" width="100%" cellpadding="3" cellspacing="6" >
    <tr>
        <td width="5%">I.</td>
        <td width="95%">INFORMASI YANG DIPEROLEH</td>
    </tr>
    <tr>
        <td width="5%"></td>
        <td width="95%" style="text-align: justify; text-justify: inter-word;" class="avoid-page-break text-overflow">
            {{ strip_tags($data->informasi_diperoleh) }}
        </td>
    </tr>

        <tr>
            <td width="5%">II.</td>
            <td width="95%">
            SUMBER INFORMASI
            </td>
        </tr>
        <tr>
            <td width="5%"></td>
            <td width="95%" style="text-align: justify;
            text-justify: inter-word; page-break-inside: avoid;">
            {{ strip_tags($data->sumber_informasi) }}
            </td>
        </tr>

        <tr>
            <td width="5%">III.</td>
            <td width="95%">
            TREND PERKEMBANGAN
            </td>
        </tr>
        <tr>
            <td width="5%"></td>
            <td width="95%" style="text-align: justify;
            text-justify: inter-word; page-break-inside: avoid;">
            {{ strip_tags($data->tren_perkembangan) }}
            </td>
        </tr>

        <tr>
            <td width="5%">IV.</td>
            <td width="95%">
            SARAN TINDAK
            </td>
        </tr>
        <tr>
            <td width="5%"></td>
            <td width="95%" style="text-align: justify;
            text-justify: inter-word; page-break-inside: avoid;">
            {{ strip_tags($data->saran_tindak) }}
            </td>
        </tr>
    </table> 
        <br>
        <br>   
        AUTENTIKASI:
        <br>

            <table border='0' autosize="1" width=100% cellpadding="0" cellspacing="0" style="page-break-inside:avoid; font-family:Bookman Old Style; font-size:12;">
                <tr>
                    <td width=50%></td>
                    <td width=50% style="text-align: center;">Dikeluarkan di {{ $data->case?->satker?->city }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;">Pada tanggal {{ \Carbon\Carbon::parse($data->tanggal_surat)->translatedFormat('d F Y') }} </td>
                </tr>
                <tr>
                    <td colspan="2"><br></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;">Yang Membuat Laporan,
                        <br><br><br><br><br>
                        <u>{{$penandatangan->nama ?? ''}}</u><br>
                        {{$penandatangan->pangkat ?? ''}} / NIP.{{$penandatangan->nip ?? ''}}
                    </table>
    </body>
</html>
