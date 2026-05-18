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
        </style>
    </head>
    <body>

    <p>
    <br>
    <div align="justify" style="Times New Roman, Times, serif; font-size:12;">
    <table border="0" width="100%">
            <tr>
                <td valign="top">
                    {{ $data->satker->nama_satker }}
                </td>
                <td valign="top" align="right">
                    IN.4
                </td>
            </tr>
        </table>
    </p>
    <br>
    
    <p>
        <div align="center" style="Times New Roman, Times, serif;"><u><b>LAPORAN HASIL PELAKSANAAN TUGAS</b></u></div>
    </p>
    <br>

    <table border="0" style="width: 100%">
            <tr>
                <td style="padding-right:10px;">1. &nbsp;&nbsp;&nbsp; Pendahuluan: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{strip_tags($elicitationResult->pendahuluan)}} </td>
            </tr>
            <tr>
                <td>2. &nbsp;&nbsp;&nbsp; Pelaksanaan kegiatan : {{strip_tags($elicitationResult->pelaksanaan_kegiatan)}}</td>
            </tr>
            <tr>
                <td>3. &nbsp;&nbsp;&nbsp; Kendala/Hambatan : {{strip_tags($elicitationResult->kendala)}}</td>
            </tr>
            <tr>
                <td>4. &nbsp;&nbsp;&nbsp; Analisa : {{strip_tags($elicitationResult->analisa)}}</td>
            </tr>
            <tr>
                <td>5. &nbsp;&nbsp;&nbsp; Penutup :</td>
            </tr>
            <table style="padding-left: 30px">
                <tr>
                    <td>-</td>
                    <td>Kesimpulan</td>
                    <td>:</td>
                    <td>{{strip_tags($elicitationResult->kesimpulan)	}}</td>
                </tr>
                <tr>
                    <td>-</td>
                    <td>Saran</td>
                    <td>:</td>
                    <td>{{strip_tags($elicitationResult->saran)}}</td>
                </tr>
                >
            </table>
            
            
        </table>

        <br><br>
        Demikian laporan hasil pelaksaan tugas ini dibuat dengan sebenarnya untuk dapat digunakan seperlunya.
        <br><br>

        <table border='0' autosize="1" width=100% cellpadding="0" cellspacing="0"
            style="page-break-inside:avoid; font-family:Bookman Old Style;">
            <tr>
                <td width=50%></td>
                <td width=50% style="text-align: center;">
                    {{$data->satker->city}}, {{ \Carbon\Carbon::parse($elicitationResult->created_at)->translatedFormat('d F Y') }}
              
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">JAM INTEL/ KAJATI/ KAJARI/ KACABJARI/ PELAKSANA TUGAS<br>
                    </td>
            </tr>
            <tr>

                <td style="text-align: center;">
                    <br><br><br><br><br><br>
                </td>
            <tr>
                <td></td>
                <td style="text-align: center;">
                    <u>{{$elicitationResult->interviewer_name}}</u><br>
                    {{$elicitationResult->pangkat}}/{{$elicitationResult->nip}}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;"></td>
            </tr>
            </tr>
        </table>
     
        <br>
		

        	
        
    
    </body>
</html>

   