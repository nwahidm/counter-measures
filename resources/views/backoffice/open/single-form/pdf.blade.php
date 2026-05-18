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
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }} </strong></td>
        @elseif($data->satker->tipe_satker == '3')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }} </strong></td>
        @elseif($data->satker->tipe_satker == '2')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $data->satker->nama_satker }}</strong></td>
        @else
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>KEJAKSAAN AGUNG</strong></td>
        @endif

        {{-- <td valign="top" align="center"  style="font-size:22"><strong>JAKSA AGUNG MUDA INTELIJEN</strong></td> --}}
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
        <td colspan="4" style="text-align: center; vertical-align: middle; font-size: 18px; font-weight: bold;">OPEN CASE SINGLE FORM REPORT
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
        <td>{{ $data->case_name }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Tanggal Kasus</td>
        <td>:</td>
        <td>{{ $data->case_date }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Deskripsi Kasus</td>
        <td>:</td>
        <td>{{ $data->case_description }}</td>
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
        <td>{{ $data->target_name }}</td>
    </tr>
    <tr>
        <td></td>
        <td style="width: 200px;">Jenis Identitas</td>
        <td style="width: 20px;">:</td>
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
        <td>Pendidikan</td>
        <td>:</td>
        <td>{{ $data->target_education }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Jenis Kelamin</td>
        <td>:</td>
        <td>{{ $data->target_gender }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Pekerjaan</td>
        <td>:</td>
        <td>{{ $data->target_occupation }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $data->target_address }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Foto</td>
        <td>:</td>
        <td>
            @if($data->target_photo && sizeof(json_decode($data->target_photo)) > 0)
                <table>
                    <tr>
                        @foreach(json_decode($data->target_photo) as $foto)
                            <td><img src="https://rode.kejaksaanri.id/storage/open/single-form/{{ str_replace(" ", "%20", $foto) }}" alt="Image"
                                style="max-width: 25%;"></td>
                        @endforeach
                    </tr>
                </table>
            @else
                Tidak ada foto
            @endif
        </td>
    </tr>
    @if($data->open_procedure_type =='research' || $data->open_procedure_type =='all' )
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">PENELITIAN</td>
        </tr>
        <tr>
            <td></td>
            <td>Pendahuluan</td>
            <td>:</td>
            <td>{{ strip_tags($data->research_lapinsus_pendahuluan) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Data dan Fakta</td>
            <td>:</td>
            <td>{{ strip_tags($data->research_data_dan_fakta) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Informasi Diperoleh</td>
            <td>:</td>
            <td>{{ strip_tags($data->research_informasi_diperoleh) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Sumber Informasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->research_sumber_informasi) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Trend Perkembangan</td>
            <td>:</td>
            <td>{{ strip_tags($data->research_tren_perkembangan) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Saran dan Tindak Lanjut</td>
            <td>:</td>
            <td>{{ strip_tags($data->research_saran_tindak) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Tipe AGHT</td>
            <td>:</td>
            <td>{{ strip_tags($data->research_aght_type) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Deskripsi AGHT</td>
            <td>:</td>
            <td>{{ strip_tags($data->research_aght_description) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Hasil Analisa Dokumen</td>
            <td>:</td>
            <td>{{ strip_tags($research_document_pdf_data->doc_analytics_2 ) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Hasil Kesimpulan Dokumen</td>
            <td>:</td>
            <td>{{ strip_tags($research_document_pdf_data->doc_summary_2 ) }}</td>
        </tr>
    @endif

    @if($data->open_procedure_type =='interview' || $data->open_procedure_type =='all' )
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">WAWANCARA</td>
        </tr>
        <tr>
            <td></td>
            <td>Nama Pewawancara</td>
            <td>:</td>
            <td>{{ strip_tags($data->interview_interviewer_name) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Jadwal Wawancara</td>
            <td>:</td>
            <td>{{ strip_tags($data->interview_schedule) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Nama</td>
            <td>:</td>
            <td>{{ strip_tags($data->interview_target_name) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Nomor Identitas</td>
            <td>:</td>
            <td>{{ strip_tags($data->interview_target_identity_number) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Agama</td>
            <td>:</td>
            <td>{{ strip_tags($data->interview_target_religion) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Pendidikan</td>
            <td>:</td>
            <td>{{ strip_tags($data->interview_target_education) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ strip_tags($data->interview_target_gender) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ strip_tags($data->interview_target_address) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Foto</td>
            <td>:</td>
            <td>
                @if($data->interview_target_photo && sizeof(json_decode($data->interview_target_photo)) > 0)
                    <table>
                        <tr>
                            @foreach(json_decode($data->interview_target_photo) as $foto)
                                <td><img src="https://rode.kejaksaanri.id/storage/open/single-form/interview/{{ str_replace(" ", "%20", $foto) }}" alt="Image"
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
            <td></td>
            <td>Hasil Analisa Dokumen</td>
            <td>:</td>
            <td>{{ strip_tags($interview_document_pdf_data->doc_analytics_2 ) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Hasil Kesimpulan Dokumen</td>
            <td>:</td>
            <td>{{ strip_tags($interview_document_pdf_data->doc_summary_2 ) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Analisa Video Wawancara</td>
            <td>:</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Saran dan Tindak</td>
            <td>:</td>
            <td>{{ strip_tags($data->interview_saran_dan_tindak_lanjut ) }}</td>
        </tr>
        
    @endif

    @if($data->open_procedure_type =='interrogation' || $data->open_procedure_type =='all' )
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">INTEROGASI</td>
        </tr>
        <tr>
            <td></td>
            <td>Nama</td>
            <td>:</td>
            <td>{{ strip_tags($data->interrogation_target_name) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIK</td>
            <td>:</td>
            <td>{{ strip_tags($data->interrogation_target_identity_number) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Agama</td>
            <td>:</td>
            <td>{{ strip_tags($data->interrogation_target_religion) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Pendidikan</td>
            <td>:</td>
            <td>{{ strip_tags($data->interrogation_target_education) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ strip_tags($data->interrogation_target_gender) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ strip_tags($data->interrogation_target_address) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Foto</td>
            <td>:</td>
            <td>
                @if($data->interrogation_target_photo && sizeof(json_decode($data->interrogation_target_photo)) > 0)
                    <table>
                        <tr>
                           

                            @foreach(json_decode($data->interrogation_target_photo) as $foto)
                                <td><img src="https://rode.kejaksaanri.id/storage/open/single-form/interrogation/{{ str_replace(" ", "%20", $foto) }}" alt="Image"
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
            <td></td>
            <td>Hasil Analisa Dokumen</td>
            <td>:</td>
            <td>{{ strip_tags($interrogation_document_pdf_data->doc_analytics_2) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Hasil Kesimpulan Dokumen</td>
            <td>:</td>
            <td>{{ strip_tags($interrogation_document_pdf_data->doc_summary_2) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Analisa Video Identifikasi Target</td>
            <td>:</td>
            <td></td>
        </tr>

        <tr>
            <td></td>
            <td>Identifikasi Target</td>
            <td>:</td>
            <td>{{ strip_tags($data->interrogation_target_identification) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Hasil Capaian</td>
            <td>:</td>
            <td>{{ strip_tags($data->interrogation_result_achievement) }}</td>
        </tr>
    @endif      
    
    @if($data->open_procedure_type =='elicitation' || $data->open_procedure_type =='all' )
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">Elisitasi/ Pemancingan</td>
        </tr>

        <tr>
            <td></td>
            <td>Nama Pewawancara</td>
            <td>:</td>
            <td>{{ strip_tags($data->elicitation_interviewer_name) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jadwal Wawancara</td>
            <td>:</td>
            <td>{{ strip_tags($data->elicitation_interview_schedule) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Nama</td>
            <td>:</td>
            <td>{{ strip_tags($data->elicitation_interview_target_name) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Nomor Identitas</td>
            <td>:</td>
            <td>{{ strip_tags($data->elicitation_interview_target_identity_number) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Agama</td>
            <td>:</td>
            <td>{{ strip_tags($data->elicitation_target_religion) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Pendidikan</td>
            <td>:</td>
            <td>{{ strip_tags($data->elicitation_target_education) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ strip_tags($data->elicitation_target_gender) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ strip_tags($data->elicitation_target_address) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Foto</td>
            <td>:</td>
            <td>
                @if($data->elicitation_target_photo && sizeof(json_decode($data->elicitation_target_photo)) > 0)
                    <table>
                        <tr>
                          

                            @foreach(json_decode($data->elicitation_target_photo) as $foto)
                                <td><img src="https://rode.kejaksaanri.id/storage/open/single-form/elicitation/{{ str_replace(" ", "%20", $foto) }}" alt="Image"
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
            <td></td>
            <td>Hasil Analisa Dokumen</td>
            <td>:</td>
            <td>{{ strip_tags($elicitation_document_pdf_data->doc_analytics_2) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Hasil Kesimpulan Dokumen</td>
            <td>:</td>
            <td>{{ strip_tags($elicitation_document_pdf_data->doc_summary_2) }}</td>
        </tr>
        
       
        
    @endif 

   

</table>
