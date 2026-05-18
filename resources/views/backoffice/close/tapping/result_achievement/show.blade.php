<x-backoffice.layout.app-layout title="Detail Penyadapan Hasil Pencapaian">
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
    <x-backoffice.toolbar heading="Detail Penyadapan Hasil Pencapaian" subheading="" breadcrumb="close-case-detail"
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
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->case?->satker?->nama_satker }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->case?->case_name }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-5">
                                                <div class="col-lg-12">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800">PENYADAPAN PERANGKAT ELEKTRONIK</span>
                                                        <hr>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tgl. Penyadapan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->tappingElectronicDevice?->tanggal_penyadapan->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Sumber Data</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->tappingElectronicDevice?->sumber_data }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Metode Penyadapan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{!! $data->tappingElectronicDevice?->metode_penyadapan !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Hasil</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{!! $data->tappingElectronicDevice?->deskripsi_hasil !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">File Dokumen</label>
                                                <div class="col-lg-8">
                                                    @if ($data->tappingElectronicDevice?->dokumen_upload)
                                                        <iframe style="width: 100%; height: 500px; overflow: hidden;" src="{{ asset('storage/'. $data->tappingElectronicDevice->dokumen_upload) }}" frameborder="0" id="dokumen_upload">
                                                        </iframe>
                                                    @else
                                                        <span class="badge badge-danger">Tidak ada dokumen</span>
                                                    @endif
                                                    {{-- @if($data->dokumen_upload)
                                                        <a class="btn btn-dark btn-sm btn-icon"
                                                           href="{{ route('close.tapping.hasil.download-dokumen', encrypt($data->dokumen_upload)) }}">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    @else
                                                        <span
                                                            class="badge badge-danger">Tidak ada dokumen</span>
                                                    @endif --}}
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <div class="col-lg-12">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800">PENYADAPAN SINYAL PINTAR</span>
                                                        <hr>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tgl. Penyadapan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->tappingIntelligentSignal?->tanggal_penyadapan?->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jenis Sinyal</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->tappingIntelligentSignal?->jenis_sinyal }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Hasil</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{!! $data->tappingIntelligentSignal?->deskripsi_hasil !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">File Dokumen</label>
                                                <div class="col-lg-8">
                                                    @if ($data->tappingIntelligentSignal?->dokumen_upload)
                                                        <iframe style="width: 100%; height: 500px; overflow: hidden;" src="{{ asset('storage/'. $data->tappingIntelligentSignal?->dokumen_upload) }}" frameborder="0" id="dokumen_upload">
                                                        </iframe>
                                                    @else
                                                        <span class="badge badge-danger">Tidak ada dokumen</span>
                                                    @endif
                                                    {{-- @if($data->dokumen_upload)
                                                        <a class="btn btn-dark btn-sm btn-icon"
                                                           href="{{ route('close.tapping.intelligent_signal.download-dokumen', encrypt($data->dokumen_upload)) }}">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    @else
                                                        <span
                                                            class="badge badge-danger">Tidak ada dokumen</span>
                                                    @endif --}}
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <div class="col-lg-12">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800">HASIL YANG DICAPAI</span>
                                                        <hr>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Yang Dicapai</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{!! $data->hasil_yang_dicapai !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">File Dokumen</label>
                                                <div class="col-lg-8">
                                                    @if ($data->upload_hasil_yang_dicapai)
                                                        <iframe style="width: 100%; height: 500px; overflow: hidden;" src="{{ asset('storage/'. $data->upload_hasil_yang_dicapai) }}" frameborder="0" id="upload_hasil_yang_dicapai">
                                                        </iframe>
                                                    @else
                                                        <span class="badge badge-danger">Tidak ada dokumen</span>
                                                    @endif
                                                    {{-- @if($data->upload_hasil_yang_dicapai)
                                                        <a class="btn btn-dark btn-sm btn-icon"
                                                           href="{{ route('close.tapping.result_achievement.download-file', encrypt($data->upload_hasil_yang_dicapai)) }}">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    @else
                                                        <span
                                                            class="badge badge-danger">Tidak ada dokumen</span>
                                                    @endif --}}
                                                </div>
                                            </div>
                                            <label class="fs-3 fw-bold">ANALISA DOKUMEN:</label> <hr>
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
