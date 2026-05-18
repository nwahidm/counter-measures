<x-backoffice.layout.app-layout title="Detail Penjajakan Hasil Pencapaian">
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
    <x-backoffice.toolbar heading="Detail Penjajakan Hasil Pencapaian" subheading=""
                          breadcrumb="open-case-detail"
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
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $data->case?->case_name }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Rencana Aksi</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ strip_tags($data->explorationRencanaAksi?->rencana_aksi_data) }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Detail Rencana Aksi</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ strip_tags($data->explorationRencanaAksi?->rencana_aksi_detail) }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama Target</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ strip_tags($data->explorationTargetIdentitas?->target_name) }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ strip_tags($data->hasil_yang_dicapai) }}</span>
                                                </div>
                                            </div>                                            
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Dokumen</label>
                                                <div class="col-lg-8">
                                                    @if ($data->upload_hasil_yang_dicapai)
                                                        <iframe style="width: 100%; height: 500px; overflow: hidden;"
                                                            src="{{ asset('storage/' . $data->upload_hasil_yang_dicapai) }}"
                                                            frameborder="0" id="information_collection_upload">
                                                        </iframe>
                                                    @else
                                                        <span class="badge badge-danger">Tidak ada dokumen</span>
                                                    @endif
                                                    {{-- <a class="btn btn-dark btn-sm btn-icon"
                                                        href="{{ route('close.intrusion.target-loc.download-file', encrypt($data->lokasi_target_upload)) }}">
                                                        <i class="fas fa-file-download"></i>
                                                    </a> --}}
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted"> Kesimpulan Dokumen</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $summary->doc_summary_2 }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted"> Analisis Dokumen</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify">{{ $summary->doc_analytics_2 }}</span>
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
