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
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $satker->nama_satker }} <br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
        @elseif($satker->tipe_satker == '3')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $satker->nama_satker }} <br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
        @elseif($satker->tipe_satker == '2')
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>{{ $satker->nama_satker }}<br>ASISTEN BIDANG INTELIJEN</strong></td>
        @else
            <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>KEJAKSAAN AGUNG<br>JAKSA AGUNG MUDA INTELIJEN</strong></td>
        @endif
    </tr>
    <!-- <tr>
        @if($data->tipe_satker == '4')
            <td valign="top" align="center">{{ $data->alamat_satker }}</td>
        @elseif($data->tipe_satker == '3')
            <td valign="top" align="center">{{ $data->alamat_satker }}</td>
        @elseif($data->tipe_satker == '2')
            <td valign="top" align="center">{{ $data->alamat_satker }}</td>
        @else
            <td valign="top" align="center" style="line-height: 1;">{{ $data->alamat_satker }} <br> Telp (021) 7236510 . www.kejaksaan.go.id</td>
        @endif
    </tr> -->
    <tr>
        <td valign="top" width="50%" align="center" colspan="2" style="border-top: 5px solid black; height: 2px;"></td>
    </tr>
</table>
<table>
    <tr>
        <td colspan="4" class="header">
            LAPORAN PENGAMATAN
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
        <td style="text-align: justify;">{!!$data->case_description !!}</td>
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
            
            @if($data->target_photo && sizeof(json_decode($data->target_photo)) > 0)
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
        <td colspan="4" class="sub-header">SURAT PERINTAH PENGAMATAN</td>
    </tr>
    @if($data->observationDirective->count()>0)
        @foreach ($observationDirective as $index => $directive)
            @php
                $alphabet = chr(65 + $index); // Convert index to corresponding alphabet letter (A, B, C, etc.)
                $collectInfo = 0; // Initialize a separate counter for validations
            @endphp
            <tr>
                <td></td>
                <td colspan="3"><strong>{{ $alphabet }}. Surat Perintah ke - {{ $index + 1 }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Perihal Surat Perintah</td>
                <td>:</td>
                <td>{{ $directive->surat_perintah_perihal }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Nomor Surat Perintah</td>
                <td>:</td>
                <td>{{ $directive->surat_perintah_number }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Tanggal Surat Perintah</td>
                <td>:</td>
                <td>{{ $directive->surat_perintah_date?->isoFormat('DD MMMM YYYY') }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Tanggal Mulai Surat Perintah</td>
                <td>:</td>
                <td>{{ $directive->surat_perintah_date_started?->isoFormat('DD MMMM YYYY') }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada surat perintah</td>
        </tr>
    @endif

    @if($data->observationCollectInfo->count()>0)
        @foreach ($observationCollectInfo as $collectionInfo)
        
            @php
                $alphabet2 = chr(97 + $collectInfo); // Convert index to corresponding alphabet letter (a, b, c, etc.)
                $collectInfo++; // Increment the counter only if the condition is met

                $threatId = 0;
            @endphp
            <tr>
                <td></td>
                        <td colspan="3"><strong>&bull; Pengumpulan Informasi ke - {{ $collectInfo }}</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Sumber Informasi</td>
                        <td>:</td>
                        <td>{{ $collectionInfo->information_collection_source }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Tanggal Informasi</td>
                        <td>:</td>
                        <td>{{ $collectionInfo->information_collection_date?->isoFormat('DD MMMM YYYY') }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Perihal Informasi</td>
                        <td>:</td>
                        <td>{{ strip_tags($collectionInfo->information_collection_perihal) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Detail Informasi</td>
                        <td>:</td>
                        <td style="text-align: justify;">{{ strip_tags($collectionInfo->information_collection_detail) }}</td>
                    </tr>
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada pengumpulan informasi</td>
        </tr>
    @endif

    @if($data->observationThreat->count()>0)
        @foreach ($observationThreat as $threat)
          
                        @php
                            $threatId++; // Increment the counter only if the condition is met
                            $connectId = 0;
                        @endphp
                        <tr>
                            <td></td>
                            <td colspan="3"><strong>&bull; AGHT ke - {{ $threatId }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Jenis AGHT</td>
                            <td>:</td>
                            <td>{{ $threat->aght_type }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Waktu Terjadi</td>
                            <td>:</td>
                            <td>{{ strip_tags($threat->aght_time?->isoFormat('DD MMMM YYYY hh:mm')) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Tempat Terjadi</td>
                            <td>:</td>
                            <td>{{ strip_tags($threat->aght_place) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Perihal</td>
                            <td>:</td>
                            <td>{{ strip_tags($threat->perihal) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td style="text-align: justify;">{{ strip_tags($threat->keterangan) }}</td>
                        </tr>
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada AGHT</td>
        </tr>
    @endif

    @if($data->observationConnect->count()>0)
        @foreach ($observationConnect as $connect)
                            
                                @php
                                    $connectId++; // Increment the counter only if the condition is met
                                @endphp
                                <tr>
                                    <td></td>
                                    <td colspan="3"><strong>&bull; Target Terhubung ke - {{ $connectId }}</strong></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Foto</td>
                                    <td>:</td>
                                    <td style="text-align: left;">
                                        <div class="image-container">
                                            @if ($connect->target_photo)
                                                @foreach (json_decode($connect->target_photo) as $image)
                                                    @if (!empty($image))
                                                        <img src="https://rode.kejaksaanri.id/storage/{{ $image }}" alt="Preview" style="max-width: 20%; padding-top: 60px;">
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td>{{ $connect->target_name }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Jenis Identitas</td>
                                    <td>:</td>
                                    <td>{{ $connect->target_identity_number_type }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>No. Identitas</td>
                                    <td>:</td>
                                    <td>{{ $connect->target_identity_number }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>{{ $connect->target_gender }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Agama</td>
                                    <td>:</td>
                                    <td>{{ $connect->target_religion }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Pekerjaan</td>
                                    <td>:</td>
                                    <td>{{ $connect->target_occupation }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Pendidikan</td>
                                    <td>:</td>
                                    <td>{{ $connect->target_education }}</td>
                                </tr>
                      
        @endforeach
    @else
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px;">Belum ada relasi terhubung</td>
        </tr>
    @endif
                  
</table>
