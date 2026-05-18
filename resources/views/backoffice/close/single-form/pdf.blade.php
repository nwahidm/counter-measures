<table align="center" border="0" style="width: 100%;">
    <tr>
        <td valign="top"  align="center" rowspan="2" ><img src="{{ public_path('assets/kejaksaan.png') }}" width="12%"> </td>
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
    </tr>
    <tr>
        @if($data->satker->tipe_satker == '4')
            <td valign="top" align="center">{{ $data->satker->alamat_satker }}</td>
        @elseif($data->satker->tipe_satker == '3')
            <td valign="top" align="center">{{ $data->satker->alamat_satker }}</td>
        @elseif($data->tipe_satker == '2')
            <td valign="top" align="center">{{ $data->satker->alamat_satker }}</td>
        @else
            <td valign="top" align="center" style="line-height: 1;">{{ $data->satker->alamat_satker }} <br> Telp (021) 7236510 . www.kejaksaan.go.id</td>
        @endif
    </tr>
    <tr>
        <td valign="top" width="50%" align="center" colspan="2" style="border-top: 5px solid black; height: 2px;"></td>
    </tr>
</table>
<br>
<table style="width: 100%;">
    <tr>
        <td colspan="4" style="text-align: center; vertical-align: middle; font-size: 18px; font-weight: bold;">CLOSE CASE SINGLE FORM REPORT
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
                            <td><img src="https://rode.kejaksaanri.id/storage/close/single-form/{{ str_replace(" ", "%20", $foto) }}" alt="Image"
                                style="max-width: 25%;"></td>
                        @endforeach
                    </tr>
                </table>
            @else
                Tidak ada foto
            @endif
        </td>
    </tr>
    @if($data->close_procedure_type =='observation' || $data->close_procedure_type =='all' )
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">PENGAMATAN</td>
        </tr>
        <tr>
            <td></td>
            <td>Nomor Surat Perintah</td>
            <td>:</td>
            <td>{{ $data->observation_surat_perintah }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Sumber Informasi</td>
            <td>:</td>
            <td>{{ $data->observation_sumber_informasi }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Detail Informasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->observation_detail_informasi) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Ancaman</td>
            <td>:</td>
            <td>{{ strip_tags($data->observation_ancaman_detail) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Gangguan</td>
            <td>:</td>
            <td>{{ strip_tags($data->observation_gangguan_detail) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Hambatan</td>
            <td>:</td>
            <td>{{ strip_tags($data->observation_hambatan_detail) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Tantangan</td>
            <td>:</td>
            <td>{{ strip_tags($data->observation_tantangan_detail) }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">BIODATA TARGET (PENGAMATAN)</td>
        </tr>
        <tr>
            <td></td>
            <td>Nama</td>
            <td>:</td>
            <td>{{ strip_tags($data->observaiton_nama_terkait) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIK</td>
            <td>:</td>
            <td>{{ strip_tags($data->observation_nik_terkait) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ strip_tags($data->observation_jenis_kelamin_terkait) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>{{ strip_tags($data->observation_pekerjaan_terkait) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Pendidikan</td>
            <td>:</td>
            <td>{{ strip_tags($data->observation_pendidikan_terkait) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Agama</td>
            <td>:</td>
            <td>{{ strip_tags($data->observation_agama_terkait) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Foto</td>
            <td>:</td>
            <td>
                @if($data->observation_foto_terkait && sizeof(json_decode($data->observation_foto_terkait)) > 0)
                    <table>
                        <tr>
                            @foreach(json_decode($data->observation_foto_terkait) as $foto)
                                <td><img src="https://rode.kejaksaanri.id/storage/close/single-form/{{ str_replace(" ", "%20", $foto) }}" alt="Image"
                                    style="max-width: 25%;"></td>
                            @endforeach
                        </tr>
                    </table>
                @else
                    Tidak ada foto
                @endif
            </td>
        </tr>
    @endif

    @if($data->close_procedure_type =='delineation' || $data->close_procedure_type =='all' )
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">PENGGAMBARAN</td>
        </tr>
        <tr>
            <td></td>
            <td>Kredibilitas Sumber</td>
            <td>:</td>
            <td>{{ strip_tags($data->delineation_informasi_verifikasi_kredibilitas_sumber) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Metode Verifikasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->delineation_informasi_verifikasi_metode_verifikasi) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Tanggal Verifikasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->delineation_informasi_verifikasi_tanggal_verifikasi) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Detail Informasi Verifikasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->delineation_informasi_verifikasi_detail_informasi) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Metode Validasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->delineation_informasi_validasi_metode_validasi) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Tanggal Validasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->delineation_informasi_validasi_tanggal_validasi) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Subjek Utama Terhubung</td>
            <td>:</td>
            <td>{{ strip_tags($data->delineation_identitas_terhubung_subjek_utama) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Jenis Relasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->delineation_identitas_terhubung_jenis_relasi) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Kekuatan Relasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->delineation_identitas_terhubung_kekuatan_relasi) }}</td>
        </tr>

    @endif

    @if($data->close_procedure_type =='exploration' || $data->close_procedure_type =='all' )
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">PENJAJAKAN</td>
        </tr>

        <tr>
            <td></td>
            <td>Rencana Aksi</td>
            <td>:</td>
            <td>{{ strip_tags($data->exploration_rencana_aksi) }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">BIODATA TARGET (PENJAJAKAN)</td>
        </tr>
        <tr>
            <td></td>
            <td>Nama</td>
            <td>:</td>
            <td>{{ strip_tags($data->exploration_identitas_terhubung_nama_target) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIK</td>
            <td>:</td>
            <td>{{ strip_tags($data->exploration_identitas_terhubung_nomor_identitas_target) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ strip_tags($data->exploration_identitas_terhubung_jenis_kelamin_target) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>{{ strip_tags($data->exploration_identitas_terhubung_pekerjaan_target) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Pendidikan</td>
            <td>:</td>
            <td>{{ strip_tags($data->exploration_identitas_terhubung_pendidikan_target) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Agama</td>
            <td>:</td>
            <td>{{ strip_tags($data->exploration_identitas_terhubung_agama_target) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Foto</td>
            <td>:</td>
            <td>
                @if($data->exploration_identitas_terhubung_foto_target && sizeof(json_decode($data->exploration_identitas_terhubung_foto_target)) > 0)
                    <table>
                        <tr>
                            @foreach(json_decode($data->exploration_identitas_terhubung_foto_target) as $foto)
                                <td><img src="https://rode.kejaksaanri.id/storage/close/single-form/exploration_identitas_terhubung_foto_target/{{ str_replace(" ", "%20", $foto) }}" alt="Image"
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
            <td>Hasil Capaian</td>
            <td>:</td>
            <td>{{ strip_tags($data->exploration_hasil_yang_dicapai) }}</td>
        </tr>
    @endif      
    
    @if($data->close_procedure_type =='tailing' || $data->close_procedure_type =='all' )
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">TAILING</td>
        </tr>

        <tr>
            <td></td>
            <td>Nama</td>
            <td>:</td>
            <td>{{ strip_tags($data->tailing_pemahaman_perilaku_nama) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIK</td>
            <td>:</td>
            <td>{{ strip_tags($data->tailing_pemahaman_perilaku_nomor_identitas) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ strip_tags($data->tailing_pemahaman_perilaku_jenis_kelamin) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>{{ strip_tags($data->tailing_pemahaman_perilaku_pekerjaan) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Pendidikan</td>
            <td>:</td>
            <td>{{ strip_tags($data->tailing_pemahaman_perilaku_pendidikan) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Agama</td>
            <td>:</td>
            <td>{{ strip_tags($data->tailing_pemahaman_perilaku_agama) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Foto</td>
            <td>:</td>
            <td>
                @if($data->tailing_pemahaman_perilaku_foto && sizeof(json_decode($data->tailing_pemahaman_perilaku_foto)) > 0)
                    <table>
                        <tr>
                            @foreach(json_decode($data->tailing_pemahaman_perilaku_foto) as $foto)
                                <td><img src="https://rode.kejaksaanri.id/storage/close/single-form/tailing_pemahaman_perilaku_foto/{{ str_replace(" ", "%20", $foto) }}" alt="Image"
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
            <td>Perilaku Tercatat</td>
            <td>:</td>
            <td>{{ strip_tags($data->tailing_pemahaman_perilaku_perilaku_tercatat) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Analisa Video Pemahaman Perilaku</td>
            <td>:</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Rencana Operasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->tailing_rencana_operasi) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Target Operasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->tailing_target_operasi) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Analisa Video Target Operasi</td>
            <td>:</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Hasil Capaian</td>
            <td>:</td>
            <td>{{ strip_tags($data->tailing_hasil_yang_dicapai) }}</td>
        </tr>
    @endif 

    @if($data->close_procedure_type =='infiltration' || $data->close_procedure_type =='all' )
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">PENYUSUPAN</td>
        </tr>
        <tr>
            <td></td>
            <td>Nama Operasi Rahasia</td>
            <td>:</td>
            <td>{{ strip_tags($data->infiltration_nama_operasi_rahasia) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Metode Eksekusi</td>
            <td>:</td>
            <td>{{ strip_tags($data->infiltration_metode_eksekusi) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Analisa Video Operasi Rahasia</td>
            <td>:</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Dinamika Teramati</td>
            <td>:</td>
            <td>{{ strip_tags($data->infiltration_dinamika_teramati) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Analisa Video Dinamika Teramati</td>
            <td>:</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Hasil Capaian</td>
            <td>:</td>
            <td>{{ strip_tags($data->infiltration_hasil_yang_dicapai) }}</td>
        </tr>
    @endif 

    @if($data->close_procedure_type =='intrusion' || $data->close_procedure_type =='all' )
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">PENYURUPAN</td>
        </tr>

        <tr>
            <td></td>
            <td>Nama</td>
            <td>:</td>
            <td>{{ strip_tags($data->intrusion_nama) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIK</td>
            <td>:</td>
            <td>{{ strip_tags($data->intrusion_nomor_identitas) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ strip_tags($data->intrusion_jenis_kelamin) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>{{ strip_tags($data->intrusion_pekerjaan) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Pendidikan</td>
            <td>:</td>
            <td>{{ strip_tags($data->intrusion_pendidikan) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Agama</td>
            <td>:</td>
            <td>{{ strip_tags($data->intrusion_agama) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Foto</td>
            <td>:</td>
            <td>
                @if($data->intrusion_foto && sizeof(json_decode($data->intrusion_foto)) > 0)
                    <table>
                        <tr>
                            @foreach(json_decode($data->intrusion_foto) as $foto)
                                <td><img src="https://rode.kejaksaanri.id/storage/close/single-form/intrusion_foto/{{ str_replace(" ", "%20", $foto) }}" alt="Image"
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
            <td>Deskripsi Lokasi</td>
            <td>:</td>
            <td>{{ strip_tags($data->intrusion_deskripsi_lokasi) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Tipe Lingkungan</td>
            <td>:</td>
            <td>{{ strip_tags($data->intrusion_tipe_lingkungan) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Deskripsi Lingkungan</td>
            <td>:</td>
            <td>{{ strip_tags($data->intrusion_deskripsi_lingkungan) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Hasil Capaian</td>
            <td>:</td>
            <td>{{ strip_tags($data->intrusion_hasil_yang_dicapai) }}</td>
        </tr>
    @endif
    
    @if($data->close_procedure_type =='tapping' || $data->close_procedure_type =='all' )
        <tr>
            <td></td>
            <td colspan="3" style="text-align: center; vertical-align: middle;">
                <hr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold;">PENYADAPAN</td>
        </tr>

        <tr>
            <td></td>
            <td>Sumber Data</td>
            <td>:</td>
            <td>{{ strip_tags($data->tapping_sumber_data) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Metode Penyadapan</td>
            <td>:</td>
            <td>{{ strip_tags($data->tapping_metode_penyadapan) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Analisa Video Data Perangkat Elektronik</td>
            <td>:</td>
            <td></td>
        </tr>

        <tr>
            <td></td>
            <td>Jenis Sinyal</td>
            <td>:</td>
            <td>{{ strip_tags($data->tapping_jenis_sinyal) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Deskripsi Hasil Sinyal</td>
            <td>:</td>
            <td>{{ strip_tags($data->tapping_deskripsi_hasil_sinyal) }}</td>
        </tr>

        <tr>
            <td></td>
            <td>Hasil Capaian</td>
            <td>:</td>
            <td>{{ strip_tags($data->tapping_hasil_yang_dicapai) }}</td>
        </tr>

    @endif

</table>
