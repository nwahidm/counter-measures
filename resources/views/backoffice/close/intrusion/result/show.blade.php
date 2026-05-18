<x-backoffice.layout.app-layout title="Detail Hasil Penyurupan">
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
        </style>
    @endpush
    <x-backoffice.toolbar heading="Detail Hasil Penyurupan" subheading="" breadcrumb="open-case-detail"
                          icon="fas fa-users">
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
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Satuan Kerja</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->satker?->nama_satker }}</span>
                                                </div>
                                            </div>
                                            <label class="fs-3 fw-bold">DETAIL KASUS:</label> <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->case?->case_name }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Kasus</label>
                                                <div class="col-lg-8">
                                                        <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->case?->case_date}}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{!! $data->case?->case_description !!}</span>
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">DETAIL LOKASI TARGET:</label> <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama Target</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->location?->target_name }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->location?->target_gender }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jenis Identitas</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->location?->target_identity_number_type }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">No. Identitas</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->location?->target_identity_number }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->location?->target_religion }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pendidikan Terakhit</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->location?->target_education }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->location?->target_occupation }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Lokasi Target</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->location?->lokasi_target }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Lokasi</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{!! $data->location?->deskripsi_lokasi !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Lokasi</label>
                                                <div class="col-lg-8">
                                                    @if ($data->location?->lokasi_target_upload)
                                                        <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.intrusion.target-loc.download-file', encrypt($data->location?->lokasi_target_upload)) }}">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Foto Target</label>
                                                <div class="image-preview-container">
                                                    @if ($data->location?->target_photo)
                                                        @foreach (json_decode($data->location?->target_photo) as $image)
                                                            <img class="image-preview" style="max-width: 350px; margin-right: 10px; margin-bottom: 10px;" src="{{ $image ? asset('storage/'. $image) : asset('assets/images/placeholder.jpeg') }}" alt="Preview">
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">DETAIL LINGKUNGAN TARGET:</label> <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama Lingkungan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->environment?->nama_lingkungan }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tipe Lingkungan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->environment?->tipe_lingkungan }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Lingkungan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{!! $data->environment?->deskripsi_lingkungan !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Aktivitas Teramati</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->environment?->aktivitas_teramati }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Informasi Terkumpul</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->environment?->informasi_terkumpul }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Lokasi</label>
                                                <div class="col-lg-8">
                                                    @if ($data->environment?->target_environment_upload)
                                                        <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.intrusion.target-env.download-file', encrypt($data->environment?->target_environment_upload)) }}">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">DETAIL HASIL:</label> <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Yang Dicapai</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify">{!! $data->hasil_yang_dicapai !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">File Hasil</label>
                                                <div class="col-lg-8">
                                                    @if ($data->upload_hasil_yang_dicapai)
                                                        <iframe style="width: 100%; height: 500px; overflow: hidden;" src="{{ asset('storage/'. $data->upload_hasil_yang_dicapai) }}" frameborder="0" id="information_collection_upload">
                                                        </iframe>
                                                    @else
                                                        <span class="badge badge-danger">Tidak ada dokumen</span>
                                                    @endif
                                                    {{-- <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.intrusion.result.download-file', encrypt($data->upload_hasil_yang_dicapai)) }}">
                                                        <i class="fas fa-file-download"></i>
                                                    </a> --}}
                                                </div>
                                            </div>
                                            <label class="fs-3 fw-bold">ANALISA DOKUMEN HASIL:</label> <hr>
                                            <div class="row mb-5" style="text-align: justify;">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Analisa</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify">{{ $document_pdf_data?->doc_analytics_2 }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5 " style="text-align: justify;">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Kesimpulan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify">{{ $document_pdf_data?->doc_summary_2 }}</span>
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
