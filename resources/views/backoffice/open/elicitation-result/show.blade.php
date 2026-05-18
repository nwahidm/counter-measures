<x-backoffice.layout.app-layout title="DETAIL ELICITATION RESULT">
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
    <x-backoffice.toolbar heading="DETAIL ELICITATION RESULT" subheading="" breadcrumb="elicitation-result-detail"
        icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification />
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
                                        <div class="card-header border-0">
                                            <div class="card-title">
                                                <h3 class="card-label
                                                    ">Detail Elicitation Result</h3>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="card-body">
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Satuan Kerja</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->satker?->nama_satker }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;"> {{ $data->case?->nama_kasus }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pendahuluan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->pendahuluan !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pelaksanaan Kegiatan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->pelaksanaan_kegiatan !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kendala</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->kendala !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Analisa</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->analisa !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kesimpulan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->kesimpulan !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Saran</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->saran !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Petunjuk Pimpinan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->petunjuk_pimpinan !!}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Dokumen</label>
                                                <div class="col-lg-8">
                                                    <iframe id="iframeDocument" src="{{ asset('storage/'.$data->hasil_yang_dicapai_path) }}"
                                                        style="width:100%; min-height: 670px" frameborder="0">
                                                    </iframe>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="card-header border-0">
                                                <div class="card-title">
                                                    <h3 class="card-label">Analisa Dokumen PDF:</h3>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row mb-5" style="text-align: justify;">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Analisa</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $summary->doc_analytics_2  ?? ''  }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5 " style="text-align: justify;">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Kesimpulan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $summary->doc_summary_2  ?? ''  }}</span>
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