<x-backoffice.layout.app-layout title="DETAIL SINGLE FORM CLOSE CASE">
    @push('css')
    <style>
        thead {
            background: #f5f4f8;
            text-align: center;
        }

        .image-preview-container {
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            justify-content: space-evenly;
            padding: 5px;
            flex-wrap: wrap;
        }
        .custom-controls {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-top: 10px;
            }

            .custom-controls button {
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 16px;
                border: none;
                cursor: pointer;
            }

            #recordStatus {
                display: none;
                color: red;
                font-size: 18px;
                font-weight: bold;
                animation: blink 1s infinite;
                margin-left: 10px;
            }
            @keyframes blink {
                0% { opacity: 1; }
                50% { opacity: 0; }
                100% { opacity: 1; }
            }
    </style>
    @endpush
    <x-backoffice.toolbar heading="DETAIL SINGLE FORM CLOSE CASE" subheading="" breadcrumb="detail-single-form-close-case"
        icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification />
        </div>
    </x-backoffice.toolbar>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/@ffmpeg/ffmpeg@0.9.8/dist/ffmpeg.min.js"></script>
        <link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet">
        <script src="https://vjs.zencdn.net/7.17.0/video.min.js"></script>
        
    </head>


    <div class="app-container container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="row g-5 g-xl-8">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card ">
                                        <div class="card-header border-0">
                                            <div class="card-title">
                                                <h3 class="card-label
                                                    ">Detail single form close case</h3>
                                            </div>
                                            <div class="card-toolbar">
                                                <div class="d-flex justify-content-end w-100" data-kt-customer-table-toolbar="base">
                                                   <button type="button" class="btn btn-dark btn-sm me-2" onclick="window.location.href='{{ route('close.singleform.single-form.download-file', encrypt($data->id)) }}'">
                                                        <i class="fas fa-file"></i> Download PDF
                                                    </button>
                                                    <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#videoStreamModal" id="streamButton">
                                                        <i class="fas fa-video"></i> Stream
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="card-body">
                                            <label class="fs-3 fw-bold">DETAIL KASUS:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Satuan Kerja</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->satker->nama_satker }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->case_name }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->case_date }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{!! $data->case_description !!}</span>
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">BIODATA TARGET:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->target_name }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nomor Identitas</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->target_identity_number }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->target_religion }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->target_education }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->target_occupation }}</span>
                                                </div>
                                            </div>
                                           

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->target_gender }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Alamat</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">{{ $data->target_address }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Foto</label>
                                                <div class="image-preview-container">
                                                    @foreach ($images as $image)
                                                        <img class="image-preview" style="max-width: 200px; margin-right: 10px; margin-bottom: 10px;" src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}" alt="Preview">
                                                    @endforeach
                                                </div>
                                            </div>

                                            @if($data->close_procedure_type =='observation' || $data->close_procedure_type =='all' )
                                                <label class="fs-3 fw-bold">PENGAMATAN</label>
                                                <hr>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nomor Surat Perintah</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_surat_perintah }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Surat Perintah</label>
                                                    <div class="col-lg-8">
                                                        <iframe 
                                                            src="{{ $data->observation_upload_surat_perintah }}" 
                                                            style="width: 100%; height: 500px;" 
                                                            frameborder="0">
                                                        </iframe>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Sumber Informasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_sumber_informasi }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Detail Informasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_detail_informasi }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Ancaman</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_ancaman_detail }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Gangguan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_gangguan_detail }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hambatan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_hambatan_detail }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Tantangan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_tantangan_detail }}</span>
                                                    </div>
                                                </div>

                                                <label class="fs-3 fw-bold">BIODATA TARGET (PENGAMATAN)</label>
                                                <hr>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observaiton_nama_terkait }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">NIK</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_nik_terkait }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_jenis_kelamin_terkait }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_pekerjaan_terkait }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_pendidikan_terkait }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->observation_agama_terkait }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Foto</label>
                                                    <div class="image-preview-container">
                                                        @foreach ($observation_photos as $image)
                                                            <img class="image-preview" style="max-width: 200px; margin-right: 10px; margin-bottom: 10px;" src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}" alt="Preview">
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if($data->close_procedure_type =='delineation' || $data->close_procedure_type =='all')
                                                <label class="fs-3 fw-bold">Penggambaran</label>
                                                <hr>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Kredibilitas Sumber</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->delineation_informasi_verifikasi_kredibilitas_sumber }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Metode Verifikasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->delineation_informasi_verifikasi_metode_verifikasi }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Tanggal Verifikasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->delineation_informasi_verifikasi_tanggal_verifikasi }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Detail Informasi Verifikasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{!! $data->delineation_informasi_verifikasi_detail_informasi !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Metode Validasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->delineation_informasi_validasi_metode_validasi }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Tanggal Validasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->delineation_informasi_validasi_tanggal_validasi }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Subjek Utama Terhubung</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->delineation_identitas_terhubung_subjek_utama }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Subjek Terkait Terhubung</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->delineation_identitas_terhubung_subjek_terkait }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Relasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->delineation_identitas_terhubung_jenis_relasi }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Kekuatan Relasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->delineation_identitas_terhubung_kekuatan_relasi }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($data->close_procedure_type =='exploration' || $data->close_procedure_type =='all')
                                                <label class="fs-3 fw-bold">Penjajakan</label>
                                                <hr>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Rencana Aksi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{!! $data->exploration_rencana_aksi !!}</span>
                                                    </div>
                                                </div>

                                                <label class="fs-3 fw-bold">BIODATA TARGET (PENJAJAKAN)</label>
                                                <hr>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->exploration_identitas_terhubung_nama_target }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nomor Identitas</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->exploration_identitas_terhubung_nomor_identitas_target }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->exploration_identitas_terhubung_jenis_kelamin_target }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->exploration_identitas_terhubung_pekerjaan_target }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->exploration_identitas_terhubung_pendidikan_target }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->exploration_identitas_terhubung_agama_target }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Foto</label>
                                                    <div class="image-preview-container">
                                                        @foreach ($exploration_photos as $image)
                                                            <img class="image-preview" style="max-width: 200px; margin-right: 10px; margin-bottom: 10px;" src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}" alt="Preview">
                                                        @endforeach
                                                    </div>
                                                </div>

                                                
                                                <div class="row mb-5 " style="text-align: justify;">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Capaian</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800 text-justify">{!! $data->exploration_hasil_yang_dicapai !!}</span>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($data->close_procedure_type =='tailing' || $data->close_procedure_type =='all')
                                                <label class="fs-3 fw-bold">TAILING</label>
                                                <hr>
                                                
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tailing_pemahaman_perilaku_nama }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nomor Identitas</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tailing_pemahaman_perilaku_nomor_identitas }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tailing_pemahaman_perilaku_jenis_kelamin }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tailing_pemahaman_perilaku_pekerjaan }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tailing_pemahaman_perilaku_pendidikan }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tailing_pemahaman_perilaku_agama }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Foto</label>
                                                    <div class="image-preview-container">
                                                        @foreach ($tailing_photos as $image)
                                                            <img class="image-preview" style="max-width: 200px; margin-right: 10px; margin-bottom: 10px;" src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}" alt="Preview">
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Perilaku Tercatat</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{!! $data->tailing_pemahaman_perilaku_perilaku_tercatat !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Video Pemahaman Perilaku</label>

                                                    <div class="col-lg-8">
                                                        <video controls style="width: 100%; height: 500px;">
                                                            <source src="{{$data->tailing_pemahaman_perilaku_upload_video }}"type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                </div>
                                                <label class="fs-20 fw-bold">Analisa Dokumen Video:</label> <hr>
                                                <div class="card-body py-5">
                                                    <div class="table-responsive">
                                                        {!! $tailingPerilakuDataTable->table(['class' => 'table table-bordered', 'id' => 'data-table-tailing-perilaku']) !!}
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Rencana Operasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tailing_rencana_operasi }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Target Operasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tailing_target_operasi }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Video Target Operasi</label>

                                                    <div class="col-lg-8">
                                                        <video controls style="width: 100%; height: 500px;">
                                                            <source src="{{$data->tailing_target_operasi_upload_video }}"type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                </div>
                                                <label class="fs-20 fw-bold">Analisa Dokumen Video:</label> <hr>
                                                <div class="card-body py-5">
                                                    <div class="table-responsive">
                                                        {!! $tailingOperasiDataTable->table(['class' => 'table table-bordered', 'id' => 'data-table-tailing-operasi']) !!}
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Capaian</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tailing_hasil_yang_dicapai }}</span>
                                                    </div>
                                                </div>

                                                
                                            @endif

                                            @if($data->close_procedure_type =='infiltration' || $data->close_procedure_type =='all')
                                                <label class="fs-3 fw-bold">PENYUSUPAN</label>
                                                <hr>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama Operasi Rahasia</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->infiltration_nama_operasi_rahasia }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Metode Eksekusi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->infiltration_metode_eksekusi }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Video Operasi Rahasia</label>

                                                    <div class="col-lg-8">
                                                        <video controls style="width: 100%; height: 500px;">
                                                            <source src="{{$data->infiltration_operasi_rahasia_upload_video }}"type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                </div>
                                                <label class="fs-20 fw-bold">Analisa Dokumen Video:</label> <hr>
                                                <div class="card-body py-5">
                                                    <div class="table-responsive">
                                                        {!! $infiltrationOperasiDataTable->table(['class' => 'table table-bordered', 'id' => 'data-table-infiltration-operasi']) !!}
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Dinamika Teramati</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->infiltration_dinamika_teramati }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Video Dinamika Teramati</label>

                                                    <div class="col-lg-8">
                                                        <video controls style="width: 100%; height: 500px;">
                                                            <source src="{{$data->infiltration_dinamika_teramati_upload_video }}"type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                </div>
                                                <label class="fs-20 fw-bold">Analisa Dokumen Video:</label> <hr>
                                                <div class="card-body py-5">
                                                    <div class="table-responsive">
                                                        {!! $infiltrationDinamikaDataTable->table(['class' => 'table table-bordered', 'id' => 'data-table-infiltration-dinamika']) !!}
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Capaian</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->infiltration_hasil_yang_dicapai }}</span>
                                                    </div>
                                                </div>
                                            
                                            @endif

                                            @if($data->close_procedure_type =='intrusion' || $data->close_procedure_type =='all')
                                                <label class="fs-3 fw-bold">PENYURUPAN</label>
                                                <hr>
                                                
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->intrusion_nama }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nomor Identitas</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->intrusion_nomor_identitas }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->intrusion_jenis_kelamin }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->intrusion_pekerjaan }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->intrusion_pendidikan }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->intrusion_agama }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Foto</label>
                                                    <div class="image-preview-container">
                                                        @foreach ($intrusion_photos as $image)
                                                            <img class="image-preview" style="max-width: 200px; margin-right: 10px; margin-bottom: 10px;" src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}" alt="Preview">
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Deskripsi Lokasi</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->intrusion_deskripsi_lokasi }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Tipe Lingkungan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->intrusion_tipe_lingkungan }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Deskripsi Lingkungan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->intrusion_deskripsi_lingkungan }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Capaian</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->intrusion_hasil_yang_dicapai }}</span>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($data->close_procedure_type =='tapping' || $data->close_procedure_type =='all')
                                                <label class="fs-3 fw-bold">PENYADAPAN</label>
                                                <hr>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Sumber Data</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tapping_sumber_data }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Metode Penyadapan</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tapping_metode_penyadapan }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Video Data Perangkat Elektronik</label>

                                                    <div class="col-lg-8">
                                                        <video controls style="width: 100%; height: 500px;">
                                                            <source src="{{$data->tapping_data_perangkat_elektronik_upload_video }}"type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                </div>
                                                <label class="fs-20 fw-bold">Analisa Dokumen Video:</label> <hr>
                                                <div class="card-body py-5">
                                                    <div class="table-responsive">
                                                        {!! $tappingOperasiDataTable->table(['class' => 'table table-bordered', 'id' => 'data-table-tapping-operasi']) !!}
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Sinyal</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{{ $data->tapping_jenis_sinyal }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Deskripsi Hasil Sinyal</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{!! $data->tapping_deskripsi_hasil_sinyal !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Capaian</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">{!! $data->tapping_hasil_yang_dicapai !!}</span>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="videoStreamModal" tabindex="-1" aria-labelledby="videoStreamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoStreamModalLabel">Video Stream</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bodycamSelect" class="form-label">Select Bodycam Stream</label>
                        <select id="bodycamSelect">
                            <option value="" selected>Select a bostream</option>
                            @foreach ($bodycam_devices as $row)
                                <option value="{{ $row['id'] }}" @if($row['id'] === old('id_case')) selected @endif>{{ $row['text'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="penyelidikan_type_all">
                        <label for="penyelidikan_type_select_all" class="form-label">Tipe Penyelidikan</label>
                        <select id="penyelidikan_type_select_all">
                            <option value="" selected>Pilih Penyelidikan</option>
                            <option value="tailing_pemahaman_perilaku" selected>Pembuntutan Pemahaman Perilaku</option>
                            <option value="tailing_target_operasi" selected>Pembuntutan Target Operasi</option>
                            <option value="infiltration_operasi_rahasia" selected>Penyusupan Operasi Rahasia</option>
                            <option value="infiltration_dinamika_teramati" selected>Penyusupan Dinamika Teramati</option>
                            <option value="tapping_data_perangkat_elektronik" selected>Penyadapan Data Perangkat Elektronik</option>
                            
                        </select>
                    </div>
                    <div class="mb-3" id="penyelidikan_type_tailing">
                        <label for="penyelidikan_type_select_tailing" class="form-label">Tipe Penyelidikan</label>
                        <select id="penyelidikan_type_select_tailing">
                            <option value="" selected>Pilih Penyelidikan</option>
                            <option value="tailing_pemahaman_perilaku" selected>Pembuntutan Pemahaman Perilaku</option>
                            <option value="tailing_target_operasi" selected>Pembuntutan Target Operasi</option>
                        </select>
                    </div>

                    <div class="mb-3" id="penyelidikan_type_infiltration">
                        <label for="penyelidikan_type_select_infiltration" class="form-label">Tipe Penyelidikan</label>
                        <select id="penyelidikan_type_select_infiltration">
                            <option value="" selected>Pilih Penyelidikan</option>
                            <option value="infiltration_operasi_rahasia" selected>Penyusupan Operasi Rahasia</option>
                            <option value="infiltration_dinamika_teramati" selected>Penyusupan Dinamika Teramati</option>
                         
                        </select>
                    </div>

                    <div class="mb-3" id="penyelidikan_type_tapping">
                        <label for="penyelidikan_type_select_tapping" class="form-label">Tipe Penyelidikan</label>
                        <select id="penyelidikan_type_select_tapping">
                            <option value="" selected>Pilih Penyelidikan</option>
                            <option value="tapping_data_perangkat_elektronik" selected>Penyadapan Data Perangkat Elektronik</option>
                            
                        </select>
                    </div>

                    <div class="mb-12" id="videoContainer">
                        <video 
                            id="videoPlayer"
                            class="video-js vjs-default-skin" 
                            width="750" height="500"></video>
                    </div>
                    <div class="custom-controls">
                        <button id="playButton" class="btn btn-primary">Play</button>
                        <button id="pauseButton" class="btn btn-warning">Pause</button>
                        <button id="stopButton" class="btn btn-danger">Stop</button>
                        <button id="fullscreenButton" class="btn btn-success">Full Screen</button>
                        <button id="recordButton" class="btn btn-secondary">Record</button>
                        <span id="recordStatus">Recording...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    
    @endpush
    @push('scripts')
        {{ $tailingPerilakuDataTable->scripts() }}
        {{ $tailingOperasiDataTable->scripts() }}
        {{ $infiltrationOperasiDataTable->scripts() }}
        {{ $infiltrationDinamikaDataTable->scripts() }}
        {{ $tappingOperasiDataTable->scripts() }}
    @endpush
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            document.getElementById('streamButton').style.display = 'none';
            if('{{$data->close_procedure_type}}' == "all"){
                document.getElementById('streamButton').style.display = 'block';
                document.getElementById('penyelidikan_type_all').style.display = 'block';
                document.getElementById('penyelidikan_type_tailing').style.display = 'none';
                document.getElementById('penyelidikan_type_infiltration').style.display = 'none';
                document.getElementById('penyelidikan_type_tapping').style.display = 'none';
            }
            if('{{$data->close_procedure_type}}' == "tailing"){
                document.getElementById('streamButton').style.display = 'block';
                document.getElementById('penyelidikan_type_all').style.display = 'none';
                document.getElementById('penyelidikan_type_tailing').style.display = 'block';
                document.getElementById('penyelidikan_type_infiltration').style.display = 'none';
                document.getElementById('penyelidikan_type_tapping').style.display = 'none';
            }
            if('{{$data->close_procedure_type}}' == "infiltration"){
                document.getElementById('streamButton').style.display = 'block';
                document.getElementById('penyelidikan_type_all').style.display = 'none';
                document.getElementById('penyelidikan_type_tailing').style.display = 'none';
                document.getElementById('penyelidikan_type_infiltration').style.display = ' block';
                document.getElementById('penyelidikan_type_tapping').style.display = 'none';
            }
            if('{{$data->close_procedure_type}}' == "tapping"){
                document.getElementById('streamButton').style.display = 'block';
                document.getElementById('penyelidikan_type_all').style.display = 'none';
                document.getElementById('penyelidikan_type_tailing').style.display = 'none';
                document.getElementById('penyelidikan_type_infiltration').style.display = 'none';
                document.getElementById('penyelidikan_type_tapping').style.display = 'block';
            }
           
            // Populate the dropdown
            var bodycamSelect = document.getElementById('bodycamSelect');

            // Initialize Video.js player
            var player = videojs('videoPlayer');
            var mediaRecorder;
            var recordedChunks = [];

            // Handle dropdown change
            bodycamSelect.addEventListener('change', function(){
                var selectedStream = bodycamSelect.value;
                console.log(selectedStream);
                if(selectedStream != ""){
                    $.ajax({
                        url: '/bodycam-device-by-id', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {bodycam_id: selectedStream},
                        success: function(response) {
                             console.log(response);

                            if (selectedStream) {
                                player.src({
                                    src: response.device_source_url,
                                    type: 'application/x-mpegURL'
                                });
                                player.play();
                            } else {
                                player.pause();
                                player.src('');
                            }
                        }
                    });
                }
                
              
            });

             // Play button functionality
            document.getElementById('playButton').addEventListener('click', function() {
                player.play();
            });

            // Pause button functionality
            document.getElementById('pauseButton').addEventListener('click', function() {
                player.pause();
            });

            // Stop button functionality
            document.getElementById('stopButton').addEventListener('click', function() {
                player.pause();
                player.currentTime(0);
            });

            // Fullscreen button functionality
            document.getElementById('fullscreenButton').addEventListener('click', function() {
                if (player.isFullscreen()) {
                    player.exitFullscreen();
                } else {
                    player.requestFullscreen();
                }
            });

             // Record button functionality
            document.getElementById('recordButton').addEventListener('click', function() {
                if (mediaRecorder && mediaRecorder.state === "recording") {
                    mediaRecorder.stop();
                    this.textContent = 'Record';
                    document.getElementById('recordStatus').style.display = 'none';
                } else {
                    startRecording();
                    this.textContent = 'Stop Recording';
                    document.getElementById('recordStatus').style.display = 'inline';
                }
            });

            function startRecording() {
                var stream = player.el().querySelector('video').captureStream();
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.ondataavailable = handleDataAvailable;
                mediaRecorder.start();
            }

            function handleDataAvailable(event) {
                if (event.data.size > 0) {
                    recordedChunks.push(event.data);
                    download();
                    uploadToServer(event.data); 
                }
            }

            function download() {
                var blob = new Blob(recordedChunks, {
                    type: 'video/mp4'
                });
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('close.singleform.single-form.upload.video') }}', true);
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                //xhr.setRequestHeader('Content-Type', 'application/octet-stream'); // Tidak perlu mengatur Content-Type saat menggunakan FormData

                xhr.onload = function () {
                    console.log(xhr.status);
                    if (xhr.status === 200) {
                        alert('Video uploaded successfully');
                    } else {
                        alert('Failed to upload video');
                    }
                };

                // Membuat FormData untuk mengirim blob dan id
                var formData = new FormData();
                var penyelidikan_type = "";
                if('{{$data->close_procedure_type}}' == "all"){
                    var penyelidikan_type_select = document.getElementById('penyelidikan_type_select_all');
                    var penyelidikan_selected = penyelidikan_type_select.value;
                    penyelidikan_type = penyelidikan_selected;
                }
                if('{{$data->close_procedure_type}}' == "tailing"){
                    var penyelidikan_type_select = document.getElementById('penyelidikan_type_select_tailing');
                    var penyelidikan_selected = penyelidikan_type_select.value;
                    console.log(penyelidikan_selected)
                    penyelidikan_type = penyelidikan_selected;
                }
                if('{{$data->close_procedure_type}}' == "infiltration"){
                    var penyelidikan_type_select = document.getElementById('penyelidikan_type_select_infiltration');
                    var penyelidikan_selected = penyelidikan_type_select.value;
                    penyelidikan_type = penyelidikan_selected;
                }
                if('{{$data->close_procedure_type}}' == "tapping"){
                    var penyelidikan_type_select = document.getElementById('penyelidikan_type_select_tapping');
                    var penyelidikan_selected = penyelidikan_type_select.value;
                    penyelidikan_type = penyelidikan_selected;
                }
                console.log(penyelidikan_type)
                formData.append('video', blob); // blob adalah video yang diunggah
                formData.append('type', penyelidikan_type);
                formData.append('id', '{{$data->id}}'); // menambahkan id ke dalam formData
                console.log('Data ID:', '{{$data->id}}')
                xhr.send(formData);
                // var url = URL.createObjectURL(blob);
                // var a = document.createElement('a');
                // document.body.appendChild(a);
                // a.style = 'display: none';
                // a.href = url;
                // a.download = 'recorded_video.mp4';
                // a.click();
                // window.URL.revokeObjectURL(url);
            }

            function uploadToServer(blob) {
                var formData = new FormData();
                formData.append('video', blob, 'recorded_video.mp4');

                $.ajax({
                    url: '{{url('upload-video')}}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Include the CSRF token
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Video uploaded successfully:', response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Video upload failed:', error);
                    }
                });
            }

            $('#videoStreamModal').on('hide.bs.modal', function () {
                player.pause();
            });
        });
    </script>

</x-backoffice.layout.app-layout>