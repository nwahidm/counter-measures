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

        .footer-i {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            color: black;
            text-align: center;
        }
    </style>
</head>

<body>


    <br>
    <div align="justify" style="Times New Roman, Times, serif;">
        <table border="0" width="100%">
            <tr>
                <td valign="top">
                    {{ $data->nama_satker }}
                </td>
                <td valign="top" align="right">
                    IN.11
                </td>
            </tr>
        </table>
        </p>
        <br>

        <p>
        <div align="center" style="Times New Roman, Times, serif;"><u><b>CATATAN WAWANCARA</b></u></div>
        </p>
        <br>

        <table border="0" style="width: 100%">
            <tr>
                <td style="padding-right:10px;">1. &nbsp;&nbsp;&nbsp; Dasar: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Surat
                    {{$data->dasar}} </td>
            </tr>
            <tr>
                <td>2. &nbsp;&nbsp;&nbsp; Tempat dan waktu pelaksanaan wawancara : {{$data->tempat}}, {{ \Carbon\Carbon::parse($data->interviewer_schedule)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>3. &nbsp;&nbsp;&nbsp; Identitas yang diwawancarai :</td>
            </tr>
            <table style="padding-left: 30px">
                <tr>
                    <td>-</td>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{$data->source_person_name	}}</td>
                </tr>
                <tr>
                    <td>-</td>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{$data->target_gender}}</td>
                </tr>
                <tr>
                    <td>-</td>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{$data->target_alamat}}</td>
                </tr>
                <tr>
                    <td>-</td>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{$data->target_occupation}}</td>
                </tr>
                <tr>
                    <td>-</td>
                    <td>Agama</td>
                    <td>:</td>
                    <td>{{$data->target_religion}}</td>
                </tr>
            </table>
            <tr>
                <td>4. &nbsp;&nbsp;&nbsp; Keterangan yang diperoleh dari hasil wawancara:</td>
            </tr>
        </table>

        <br>
        <div style="padding-left:40px;">{!!$data->keterangan!!}</div>

        <br><br>
        Demikian catatan wawancara ini dibuat dengan sebenarnya untuk dapat digunakan seperlunya.
        <br><br>
        <table border='0' autosize="1" width=100% cellpadding="0" cellspacing="0"
            style="page-break-inside:avoid; font-family:Bookman Old Style;">
            <tr>
                <td width=50%></td>
                <td width=50% style="text-align: center;">
                    {{$data->city}}, {{ \Carbon\Carbon::parse($data->interviewer_schedule)->translatedFormat('d F Y') }}
              
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">Petugas Wawancara,<br>
                    </td>
            </tr>
            <tr>

                <td style="text-align: center;">
                    <br><br><br><br><br><br>
                </td>
            <tr>
                <td></td>
                <td style="text-align: center;">
                        <u>{{$data->interviewer_name}}</u><br>
                        {{$data->interview_nip}}/{{$data->interview_pangkat}}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;"></td>
            </tr>
            </tr>
        </table>


</body>

</html>