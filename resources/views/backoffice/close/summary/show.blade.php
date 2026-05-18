<x-backoffice.layout.app-layout title="Show Summary">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
            .image-preview-container{
                border: 1px solid #ccc;
                border-radius: 5px;
                display: flex;
                justify-content: space-evenly;
                padding: 5px;
                flex-wrap: wrap;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Detail Kasus dan Target Metode Tertutup" subheading="" breadcrumb="close-case-detail" icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification/>
        </div>
    </x-backoffice.toolbar>

    <div class="app-container container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="row g-5 g-xl-8">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card ">
                                        <div class="card-body">
                                            {{--  --}}
                                        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Informasi Umum</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Pengamatan</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Penggambaran</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_4">Penajajakan</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5">Pembuntutan</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_6">Penyusupan</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_7">Penyurupan</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_8">Penyadapan</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                                                <label class="fs-3 fw-bold">DETAIL KASUS:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Satuan Kerja</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->nama_satker }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Nama Kasus</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->case_name }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tanggal Kasus</label>
                                                        <div class="col-lg-8">
                                                                <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->case_date}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-10">
                                                        <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{!! $data->case_description !!}</span>
                                                        </div>
                                                    </div>
                                                    <br>

                                                <label class="fs-3 fw-bold">BIODATA TARGET:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Nama Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->target_name }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Jenis Identitas Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->target_identity_number_type }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">No. Identitas Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->target_identity_number }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Agama Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->nama_agama }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Pendidikan Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->target_education }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Pekerjaan Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->target_occupation }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Alamat Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->target_address }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Foto Target</label>
                                                        <div class="image-preview-container">
                                                            @foreach ($images as $image)
                                                                <img class="image-preview" style="max-width: 350px; margin-right: 10px; margin-bottom: 10px;" src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}" alt="Preview">
                                                            @endforeach
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                                                <label class="fs-3 fw-bold">Observation Directive:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Satuan Kerja</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->nama_satker }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Nama Kasus</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $data->case_name }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">No dan Tanggal Surat</label>
                                                        <div class="col-lg-8">
                                                                <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $observdirective->surat_perintah_number ?? ''}} / {{ $observdirective->surat_perintah_date ?? ''}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-10">
                                                        <label class="col-lg-4 fw-semibold text-muted">Perihal Surat</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $observdirective->surat_perintah_perihal ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                    <br>

                                                <label class="fs-3 fw-bold">Observation Information Collect:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Perihal Informasi</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ $observcollectinfo->information_collection_perihal ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tgl. Informasi</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ optional($observcollectinfo)->information_collection_date ? $observcollectinfo->information_collection_date->isoFormat('DD MMMM YYYY') : '' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Informasi</label>
                                                        <div class="col-lg-8">
                                                            <a class="btn btn-dark btn-sm btn-icon" href="{{ $observcollectinfo ? route('close.observation.collect-info.download-file', encrypt($observcollectinfo->surat_perintah_path)) : '#' }}">
                                                                <i class="fas fa-file-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Detail Informasi</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800">
                                                                {!! $observcollectinfo->information_collection_detail ?? '' !!}</span>
                                                        </div>
                                                    </div>

                                                    

                                                <label class="fs-3 fw-bold">Observation Threat Analysis:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Jenis AGHT</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800">{{ $observthreat->aght_type ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Perihal AGHT</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $observthreat->perihal ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Waktu Terjadi</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $observthreat->aght_time ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tempat Terjadi</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $observthreat->aght_place ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Keterangan Tambahan</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{!! $observthreat->keterangan ?? '' !!}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Download File AGHT</label>
                                                        <div class="col-lg-8">
                                                            <a class="btn btn-dark btn-sm btn-icon" href="{{ $observthreat ? route('close.observation.threat.download-file', encrypt($observthreat->aght_path)) : '#' }}">
                                                                <i class="fas fa-file-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                
                                                <label class="fs-3 fw-bold">Observation Connected Identity:</label> <hr>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama Target</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $observconnect->target_name ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Identitas</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $observconnect->target_identity_number_type ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">No. Identitas</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $observconnect->target_identity_number ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $observconnect->target_gender ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $observconnect->target_religion ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pendidikan Terakhir</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $observconnect->target_education ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Alamat</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $observconnect->target_occupation ?? '' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                                                <label class="fs-3 fw-bold">Verifikasi Informasi:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Kredibilitas Sumber</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $deliinationinfovery->kredibilitas_sumber ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Metode Verifikasi</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ $deliinationinfovery->metode_verifikasi ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Detail Informasi Verifikasi</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{!! $deliinationinfovery->detail_informasi_verifikasi ?? '' !!}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Diveriikasi Oleh</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ $deliinationinfovery->verified_by ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tanggal Verifikasi</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $deliinationinfovery->verification_date ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                <label class="fs-3 fw-bold">Delineation Information Validation:</label> <hr>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Metode Validasi</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationinfovalid->metode_validasi ?? '') }}</span>
                                                        </div>
                                                        </div>
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Tanggal Validasi</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ ($deliinationinfovalid->tanggal_validasi ?? '') }}</span>
                                                            </div>
                                                        </div>      
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Catatan Validasi</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationinfovalid->catatan_validasi ?? '') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Hasil Validasi</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{!! strip_tags($deliinationinfovalid->hasil_validasi ?? '') !!}</span>
                                                            </div>
                                                        </div>
                                                    <label class="fs-3 fw-bold">Delineation Scenario Relation:</label> <hr>
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Subjek Utama</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationscenario->subjek_utama ?? '') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Subjek Terkait</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationscenario->subjek_terkait ?? '') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Subjek Terkait</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationscenario->subjek_terkait ?? '') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Subjek Terkait</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationscenario->subjek_terkait ?? '') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Jenis Relasi</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationscenario->jenis_relasi ?? '') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Detail Relasi</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationscenario->detail_relasi ?? '') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Kekuatan Relasi</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationscenario->kekuatan_relasi ?? '') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Dampak Potensial</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationscenario->dampak_potensial ?? '') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Catatan Analisa</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationscenario->catatan_analisa ?? '') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-5">
                                                            <label class="col-lg-4 fw-semibold text-muted">Tanggal Pencatatan</label>
                                                            <div class="col-lg-8">
                                                                <span
                                                                    class="fw-bold fs-6 text-gray-800">{{ strip_tags($deliinationscenario->tanggal_pencatatan ?? '') }}</span>
                                                            </div>
                                                        </div>
                                                </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
                                                <label class="fs-3 fw-bold">Exploration Action Plan:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Rencana Aksi</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ strip_tags($explorationrencanaaksi->rencana_aksi_data ?? '') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Keterangan Rencana Aksi</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ strip_tags($explorationrencanaaksi->rencana_aksi_detail ?? '') }}</span>
                                                        </div>
                                                    </div>
                                                <label class="fs-3 fw-bold">Exploration Target Identity:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Jenis Identitas</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ strip_tags($explorationtarget->target_identity_number_type ?? '') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Nomor Identitas</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ strip_tags($explorationtarget->target_identity_number ?? '') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ strip_tags($explorationtarget->target_gender ?? '') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ strip_tags($explorationtarget->target_religion ?? '') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ strip_tags($explorationtarget->target_occupation ?? '') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ strip_tags($explorationtarget->target_education ?? '') }}</span>
                                                        </div>
                                                    </div>
                                                <label class="fs-3 fw-bold">Exploration Result Achievement:</label> <hr>                                                
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Hasil</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{!! strip_tags($explorationresult->hasil_yang_dicapai ?? '') !!}</span>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
                                                <label class="fs-3 fw-bold">Pembuntutan Pemahaman Perilaku:</label> <hr>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama Target</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $tailingpemahaman->target_name ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $tailingpemahaman->target_gender ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $tailingpemahaman->target_religion ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Identity Number</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $tailingpemahaman->target_identity_number ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Identity Number Type</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $tailingpemahaman->target_identity_number_type ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Occuopation</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $tailingpemahaman->target_occupation ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Education</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $tailingpemahaman->target_education ?? '' }}</span>
                                                    </div>
                                                </div>
    
    
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Foto</label>
                                                    <div class="col-lg-8">
                                                        @if($tailingpemahaman && $tailingpemahaman->target_photo)
                                                            <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.tailing.pemahaman-perilaku.download-file', encrypt($tailingpemahaman->target_photo)) }}">
                                                        @else
                                                            <span class="text-danger">Data Foto Tidak Ditemukan</span>
                                                        @endif
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold">Pembuntutan Target Operasi:</label> <hr>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Rencana Operasi</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{!! $tailingtargetoperasi->rencana_target_operasi ?? '' !!}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Target Operasi</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{!! $tailingtargetoperasi->target_operasi ?? '' !!}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Skenario</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{!! $tailingtargetoperasi->skenario_target_operasi ?? '' !!}</span>
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold">Pembuntutan Result Achievement:</label> <hr>                                                
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Yang Dicapai</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{!! $tailingreult->hasil_yang_dicapai ?? '' !!}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_6" role="tabpanel">
                                                <label class="fs-3 fw-bold">Infiltration Secret Operation:</label> <hr>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama Operasi Rahasia</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $infiltrationscret->nama_operasi_rahasia ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Tanggal Operasi Rahasia</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $infiltrationscret->tanggal_operasi_rahasia ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Metode Eksekusi</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $infiltrationscret->metode_eksekusi ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Dokumen</label>
                                                    <div class="col-lg-8">
                                                        <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.infiltration.secret-operation.download-file', encrypt(optional($infiltrationscret)->operasi_rahasia_dokumen_upload)) }}">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Video</label>
                                                    <div class="col-lg-8">
                                                        <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.infiltration.secret-operation.download-file', encrypt(optional($infiltrationscret)->operasi_rahasia_video_upload)) }}">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold">Infiltration Target Dynamics:</label> <hr>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Dinamika Teramati</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $infiltrationtarget->dinamika_teramati ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Tanggal Dinamika Teramati</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $infiltrationtarget->tanggal_dinamika_teramati ?? '' }}</span>
                                                    </div>
                                                </div>
    
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Deskripsi Dinamika Teramati</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{!! strip_tags($infiltrationtarget->deskripsi_dinamika_teramati ?? '')  !!}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Dokumen</label>
                                                    <div class="col-lg-8">
                                                        <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.infiltration.target-dynamics.download-file', encrypt(optional($infiltrationtarget)->dinamika_target_dokumen_upload)) }}">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold">Infiltration Result Achievement:</label> <hr>
                                                
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Yang Dicapai</label>
                                                    <div class="col-lg-8">
                                                        <span class="fw-bold fs-6 text-gray-800">
                                                            {!! $infiltrationresult->hasil_yang_dicapai ?? '' !!}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_7" role="tabpanel">
                                                <label class="fs-3 fw-bold">Intrusion Target Location:</label> <hr>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama Target</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetloc->target_name ?? ''}}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetloc->target_gender ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Identitas</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetloc->target_identity_number_type ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">No. Identitas</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetloc->target_identity_number ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetloc->target_religion ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pendidikan Terakhit</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetloc->target_education ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetloc->target_occupation ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Lokasi Target</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetloc->lokasi_target ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Deskripsi Lokasi</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{!! $intrusiontargetloc->deskripsi_lokasi ?? '' !!}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Lokasi</label>
                                                    <div class="col-lg-8">
                                                        @if($intrusiontargetloc && $intrusiontargetloc->lokasi_target_upload)
                                                            <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.intrusion.target-loc.download-file', encrypt($intrusiontargetloc->lokasi_target_upload)) }}">
                                                                <i class="fas fa-file-download"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Foto Target</label>
                                                    <div class="image-preview-container">
                                                        @foreach ($images as $image)
                                                            <img class="image-preview" style="max-width: 350px; margin-right: 10px; margin-bottom: 10px;" src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}" alt="Preview">
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold">Intrusion Target Environment:</label> <hr>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama Lingkungan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetenv->nama_lingkungan ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Tipe Lingkungan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetenv->tipe_lingkungan ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Deskripsi Lingkungan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{!! $intrusiontargetenv->deskripsi_lingkungan ?? '' !!}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Aktivitas Teramati</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetenv->aktivitas_teramati ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Informasi Terkumpul</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $intrusiontargetenv->informasi_terkumpul ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Lokasi</label>
                                                    <div class="col-lg-8">
                                                        @if($intrusiontargetenv && $intrusiontargetenv->target_environment_upload)
                                                            <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.intrusion.target-env.download-file', encrypt($intrusiontargetenv->target_environment_upload)) }}">
                                                                <i class="fas fa-file-download"></i>
                                                            </a>
                                                        @else
                                                            <span class="text-danger">File tidak ditemukan</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold">Intrusion Result Achievement:</label> <hr>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Yand Dicapai</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                                class="fw-bold fs-6 text-gray-800">{!! $intrusionresult->hasil_yang_dicapai ?? '' !!}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_8" role="tabpanel">
                                                <label class="fs-3 fw-bold">Tapping Electronic Device Data:</label> <hr>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Tgl. Penyadapan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $tappingdevice?->tanggal_penyadapan->isoFormat('DD MMMM YYYY') }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Sumber Data</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $tappingdevice?->sumber_data ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Metode Penyadapan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ strip_tags($tappingdevice?->metode_penyadapan ?? '') }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Deskripsi Hasil</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{!! strip_tags($tappingdevice?->deskripsi_hasil ?? '') !!}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">File Dokumen</label>
                                                    <div class="col-lg-8">
                                                        @if($tappingdevice?->dokumen_upload)
                                                            <a class="btn btn-dark btn-sm btn-icon"
                                                               href="{{ route('close.tapping.hasil.download-dokumen', encrypt($tappingdevice?->dokumen_upload)) }}">
                                                                <i class="fas fa-file-download"></i>
                                                            </a>
                                                        @else
                                                            <span
                                                                class="badge badge-danger">Tidak ada dokumen</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">File Video</label>
                                                    <div class="col-lg-8">
                                                        @if($data->video_upload)
                                                            <a class="btn btn-dark btn-sm btn-icon"
                                                               href="{{ route('close.tapping.hasil.download-video', encrypt($tappingdevice?->video_upload)) }}">
                                                                <i class="fas fa-file-download"></i>
                                                            </a>
                                                        @else
                                                            <span
                                                                class="badge badge-danger">Tidak ada video</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold">Tapping Intelligent Signal Data:</label> <hr>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Sinyal</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $tappingsignal->jenis_sinyal ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Deskripsi Hasil</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800">{!! strip_tags($tappingsignal->deskripsi_hasil ?? '') !!}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">File Dokumen</label>
                                                    <div class="col-lg-8">
                                                        @if($tappingsignal && $tappingsignal->dokumen_upload)
                                                            <a class="btn btn-dark btn-sm btn-icon"
                                                               href="{{ route('close.tapping.intelligent_signal.download-dokumen', encrypt($tappingsignal->dokumen_upload)) }}">
                                                                <i class="fas fa-file-download"></i>
                                                            </a>
                                                        @else
                                                            <span
                                                                class="badge badge-danger">Tidak ada dokumen</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">File Video</label>
                                                    <div class="col-lg-8">
                                                        @if($tappingsignal && $tappingsignal->video_upload)
                                                            <a class="btn btn-dark btn-sm btn-icon"
                                                               href="{{ route('close.tapping.intelligent_signal.download-video', encrypt($tappingsignal->video_upload)) }}">
                                                                <i class="fas fa-file-download"></i>
                                                            </a>
                                                        @else
                                                            <span
                                                                class="badge badge-danger">Tidak ada video</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold">Tapping Result:</label> <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Yang Dicapai</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800">{!! strip_tags($tappingresult->hasil_yang_dicapai ?? '') !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">File Dokumen</label>
                                                <div class="col-lg-8">
                                                    @if($tappingresult && $tappingresult->upload_hasil_yang_dicapai)
                                                        <a class="btn btn-dark btn-sm btn-icon"
                                                           href="{{ route('close.tapping.result_achievement.download-file', encrypt($tappingresult->upload_hasil_yang_dicapai)) }}">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    @else
                                                        <span
                                                            class="badge badge-danger">Tidak ada dokumen</span>
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
                </div>
            </div>
        </div>
    </div>
    
    @push('js')
    @endpush
</x-backoffice.layout.app-layout>