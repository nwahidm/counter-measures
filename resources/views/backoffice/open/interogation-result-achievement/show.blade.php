<x-backoffice.layout.app-layout title="DETAIL INTEROGATION RESULT ACHIEVEMENT">
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
    <x-backoffice.toolbar heading="DETAIL INTEROGATION RESULT ACHIEVEMENT" subheading="" breadcrumb="interogation-result-achievement-detail"
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
                                                    ">Detail Interogation Result Achievement</h3>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="card-body">
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->case->nama_kasus }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Dicapai</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ strip_tags($data->hasil_yang_dicapai) }}</span>
                                                </div>
                                            </div>
                                           
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Dokumen Hasil Target Identification</label>
                                                <div class="col-lg-8">
                                                    @if($data->upload_hasil_yang_dicapai)
                                                        <iframe id="iframeDocument" src="{{ asset('storage/'.$data->upload_hasil_yang_dicapai) }}"
                                                            style="width:100%; min-height: 670px" frameborder="0">
                                                        </iframe>
                                                    @else
                                                        <h3 class="card-label">Tidak ada file</h3>
                                                    @endif
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