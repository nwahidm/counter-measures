<x-backoffice.layout.app-layout title="Detail AGHT Pengamatan">
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
    <x-backoffice.toolbar heading="Detail AGHT Pengamatan" subheading="" breadcrumb="close-case-observation-threat-detail"
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
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Satuan Kerja</label>
                                            <div class="col-lg-8">
                                                <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data?->satker->nama_satker }}</span>
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

                                        <label class="fs-3 fw-bold">DETAIL SURAT PERINTAH:</label> <hr>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Nomor Surat
                                                Perintah</label>
                                            <div class="col-lg-8">
                                                <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->sprint?->surat_perintah_number }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Perihal Surat Perintah</label>
                                            <div class="col-lg-8">
                                                <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->sprint?->surat_perintah_perihal }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Tgl. Surat
                                                Perintah</label>
                                            <div class="col-lg-8">
                                                <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->sprint?->surat_perintah_date?->isoFormat('DD MMMM YYYY') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Tgl. Mulai Surat
                                                Perintah</label>
                                            <div class="col-lg-8">
                                                <span
                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->sprint?->surat_perintah_date_started?->isoFormat('DD MMMM YYYY') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-10">
                                            <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Surat Perintah</label>
                                            <div class="col-lg-8">
                                                <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.observation.directive.download-file', encrypt($data->sprint?->surat_perintah_path)) }}">
                                                    <i class="fas fa-file-download"></i>
                                                </a>
                                            </div>
                                        </div>

                                        <label class="fs-3 fw-bold">DETAIL PENGUMPULAN INFORMASI:</label> <hr>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Sumber Informasi</label>
                                            <div class="col-lg-8">
                                                <span
                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->collectInfo?->information_collection_source }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Tgl. Informasi</label>
                                            <div class="col-lg-8">
                                                <span
                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->collectInfo?->information_collection_date?->isoFormat('DD MMMM YYYY') }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Perihal Informasi</label>
                                            <div class="col-lg-8">
                                                <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->collectInfo?->information_collection_perihal }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Informasi</label>
                                            <div class="col-lg-8">
                                                <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.observation.collect-info.download-file', encrypt($data->collectInfo?->information_collection_upload)) }}">
                                                    <i class="fas fa-file-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row mb-10">
                                            <label class="col-lg-4 fw-semibold text-muted">Detail Informasi</label>
                                            <div class="col-lg-8">
                                                <span
                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify">{!! $data->collectInfo?->information_collection_detail !!}</span>
                                            </div>
                                        </div>

                                        <label class="fs-3 fw-bold">DETAIL AGHT:</label> <hr>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Jenis AGHT</label>
                                            <div class="col-lg-8">
                                                <span
                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->aght_type }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Perihal AGHT</label>
                                            <div class="col-lg-8">
                                                <span
                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->perihal }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Waktu Terjadi</label>
                                            <div class="col-lg-8">
                                                <span
                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->aght_time }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Tempat Terjadi</label>
                                            <div class="col-lg-8">
                                                <span
                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->aght_place }}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted">Keterangan Tambahan</label>
                                            <div class="col-lg-8">
                                                <span
                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify">{!! $data->keterangan !!}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <label class="col-lg-4 fw-semibold text-muted mb-3">File AGHT</label>
                                            <div class="col-lg-8">
                                                @if ($data->aght_path)
                                                    <iframe style="width: 100%; height: 500px; overflow: hidden;" src="{{ asset('storage/'. $data->aght_path) }}" frameborder="0" id="information_collection_upload">
                                                    </iframe>
                                                @else
                                                    <span class="badge badge-danger">Tidak ada dokumen</span>
                                                @endif
                                                {{-- <a class="btn btn-dark btn-sm btn-icon" href="{{ route('close.observation.threat.download-file', encrypt($data->aght_path)) }}">
                                                    <i class="fas fa-file-download"></i>
                                                </a> --}}
                                            </div>
                                        </div>
                                        <label class="fs-3 fw-bold">ANALISA DOKUMEN AGHT:</label> <hr>
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

    @push('js')
    @endpush
</x-backoffice.layout.app-layout>
