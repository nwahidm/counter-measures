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
    <div align="center" style="Times New Roman, Times, serif; font-size:16;">RAHASIA</div>
    <br>
    <div align="justify" style="Times New Roman, Times, serif; font-size:12;">
        <table border="0" width="100%">
            <tr>
                <td valign="top">
                 
                    <font style="font-size:12;text-decoration: underline;">{{ $data->nama_satker }} </font>
                </td>
                <td valign="top" align="right">
                    <font style="font-size:12">
                        L.IN.4 <br>
                        COPY KE 	: ....<br>
                        DARI    	: .... COPIES
                    </font>
                </td>
            </tr>
        </table>
    </p>
    <br>

    <p>
        <div align="center" style="Times New Roman, Times, serif;; font-size:12;"><u>LAPORAN HASIL PELAKSANAAN TUGAS</u></div>
        <div align="center" style="Times New Roman, Times, serif;; font-size:12;">Nomor : R-LAPHASTUG- {{ $data->nomor_surat }}</div>
    </p>
    <br>
    
    <table border="0" style="Times New Roman, Times, serif; font-size:12;" width="100%" cellpadding="3" cellspacing="6" >
        <tr>
            <td width="5%">I.</td>
            <td width="95%">
            	PENDAHULUAN
            </td>
        </tr>
        <tr >
            <td width="5%"></td>
            <td width="95%" style="text-align: justify;
            text-justify: inter-word; ">
            {!! $data->pendahuluan !!}
            </td>
        </tr>

        <tr>
            <td width="5%">II.</td>
            <td width="95%">
            PELAKSANAAN KEGIATAN
            </td>
        </tr>
        <tr>
            <td width="5%"></td>
            <td width="95%" style="text-align: justify;
            text-justify: inter-word; ">
            {!! $data->pelaksanaan_kegiatan !!}
            </td>
        </tr>

        <tr>
            <td width="5%">III.</td>
            <td width="95%">
           KENDALA / HAMBATAN
            </td>
        </tr>
        <tr>
            <td width="5%"></td>
            <td width="95%" style="text-align: justify;
            text-justify: inter-word; ">
            {!! $data->kendala !!}
            </td>
        </tr>

        <tr>
            <td width="5%">IV.</td>
            <td width="95%">
            ANALISA (disarankan untuk dihapus mengingat waktu sprintug singkat)
            </td>
        </tr>
        <tr>
            <td width="5%"></td>
            <td width="95%" style="">
            {!! $data->analisa !!}
            </td>
        </tr>

        <tr>
            <td width="5%">V.</td>
            <td width="95%">
           PENUTUP
            </td>
        </tr>
        <tr>
            <td width="5%"></td>
            <td width="95%">
            <ol>
                <li style="">Kesimpulan <br>
                    {!! $data->kesimpulan !!}</li>
                <li style="">Saran <br>
                    {!! $data->saran !!}
                </li>
            </ol>
            </td>
        </tr>
    </table>
   
     
        <br>
		
        <table border='0' autosize="1" width=100% cellpadding="0" cellspacing="0" style=" font-family:Bookman Old Style; font-size:12;">
            <tr>
                <td width=50%></td>
                <td width=50% style="text-align: center;">{{$data->satker->city}}, {{tglIndo(date('Y-m-d', strtotime($data->tanggal)))}}</td>
            </tr>
            <tr>
                <td colspan="2"><br></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">Pelaksana Tugas,</td>
            </tr>
            <tr>
                
                <td style="text-align: center;">
                   <br><br>
                </td>
                <tr>
                    <td></td>
                    <td style="text-align: center;">
                    <ol>
                        @foreach ($data->pelaksana_array as $pelaksana)
                        <li ><u>{{$pelaksana['nama']}}</u><br>
                            {{$pelaksana['pangkat']}} / NIP. {{$pelaksana['nip']}}</li>
                            <br>
                        @endforeach
                    </ol>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;"></td>
                </tr>
            </tr>
        </table>
            <br><br>

        	
            @if ($status == 'preview')
            <table border="1" style="Times New Roman, Times, serif; font-size:12;" width="100%" cellpadding="3" cellspacing="6">
            <tr>
                <td width="10%"></td>
                <td width="80%">
                    PETUNJUK JAM INTEL/KAJATI/KAJARI/KACABJARI : <br>
                    Atas LAPHASTUG No. R-LAPHASTUG- {{ $data->nomor_surat }}    Tgl.{{tglIndo(date('Y-m-d', strtotime($data->tanggal)))}}: <br>

                        <br><table border='0' autosize="1" width=100% cellpadding="0" cellspacing="0" style=" font-family:Bookman Old Style; font-size:12;">
                                <tr>
                                    <td width=50%></td>
                                    <td width=50% style="text-align: center;">Dikeluarkan di {{$satker->city}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align: center;">Pada tanggal {{tglIndo(date('Y-m-d', strtotime($data->tanggal)))}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><br></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align: center;">  @if ($user->jabatan !== null)
        {{ $user->jabatan }},
    @endif</td>
                                </tr>
                                <tr>
                                    
                                    <td style="text-align: center;">
                                    <br><br><br><br><br><br><br><br><br>
                                    </td>
                                    <tr>
                                        <td></td>
                                        <td style="text-align: center;">
                                            <u>{{$user->name}}</u><br>
                                        {{$user->pangkat}}/NIP. {{$user->username}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: center;"></td>
                                    </tr>
                                </tr>
                            </table>
                </td>
                <td width="10%"></td>
            </tr>
        </table>
        @else
        <table border="1" style="Times New Roman, Times, serif; font-size:12;" width="100%" cellpadding="3" cellspacing="6">
            <tr>
                <td width="10%"></td>
                <td width="80%">
                    PETUNJUK JAM INTEL/KAJATI/KAJARI/KACABJARI : <br>
                    Atas LAPHASTUG No. R-LAPHASTUG- {{ $data->nomor_surat }}    Tgl.{{tglIndo(date('Y-m-d', strtotime($data->tanggal)))}}: <br>

                        <br><table border='0' autosize="1" width=100% cellpadding="0" cellspacing="0" style=" font-family:Bookman Old Style; font-size:12;">
                                <tr>
                                    <td width=50%></td>
                                    <td width=50% style="text-align: center;">Dikeluarkan di {{$satker->city}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align: center;">Pada tanggal {{tglIndo(date('Y-m-d', strtotime($data->tanggal)))}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><br></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align: center;">  @if ($user->jabatan !== null)
        {{ $user->jabatan }},
    @endif,</td>
                                </tr>
                                <tr>
                                    
                                    <td style="text-align: center;">
                                    <br><br><br><br><br><br>
                                    </td>
                                    <tr>
                                        <td style="text-align: center;"><br><br><br>
                                            Powered By<br><img src="{{ public_path('assets/images/bsre.png') }}" width="150" alt="Balai Sertifikat Elektronik">
                                        </td>
                                        <td style="text-align: center;">
                                            <table border='0' width=100%>
                                                <tr>
                                                    <?php $path = url('storage/fileesign//'); ?>
                                                    <td width=50% valign=top align=right><img style="width: 90px;" src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->errorCorrection('H')->merge(public_path('assets/images/kejaksaannew2.jpg'), .3, true)->size(800)->margin(0)->generate('https://inteliz.kejaksaan.go.id/validate/lapinhar/'.$data->id)) }} "> </td>
                                                    <td width=50% valign=top align=left style="font-size:13px;"><br><br>Dokumen ini telah
                                                        <br>ditandatangani secara Elektronik </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="text-align: center;">
                                            <u>{{$user->name}}</u><br>
                                            {{$user->pangkat}}/NIP. {{$user->username}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: center;"></td>
                                    </tr>
                                </tr>
                            </table>
                </td>
                <td width="10%"></td>
            </tr>
        </table>
        @endif
        
    
    </body>
</html>

   