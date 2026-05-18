<x-backoffice.layout.app-layout title="Detail Penelitian Surat Perintah">
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
    <x-backoffice.toolbar heading="Detail Penelitian Surat Perintah" subheading="" breadcrumb="open-case-detail"
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
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->case->satker->nama_satker }}</span>
                                                </div>
                                            </div>
                                            
                                            <label class="fs-3 fw-bold">DETAIL KASUS:</label> <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->case->nama_kasus }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Kasus</label>
                                                <div class="col-lg-8">
                                                        <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->case->tanggal_kasus->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ strip_tags($data->case->deskripsi_kasus) }}</span>
                                                </div>
                                            </div>
                                            
                                            <label class="fs-3 fw-bold">DETAIL SURAT PERINTAH:</label> <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nomor Surat
                                                    Perintah</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->surat_perintah_number }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Perihal Surat
                                                    Perintah</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->surat_perintah_perihal }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tgl. Surat
                                                    Perintah</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->surat_perintah_date?->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tgl. Mulai Surat
                                                    Perintah</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->surat_perintah_date_started?->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tgl. Selesai Surat
                                                    Perintah</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->surat_perintah_date_finished?->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Surat Perintah</label>
                                                <div class="col-lg-8">
                                                    @if ($data->surat_perintah_path)
                                                        <iframe style="width: 100%; height: 500px; overflow: hidden;" src="{{ asset('storage/'. $data->surat_perintah_path) }}" frameborder="0" id="file_surat_perintah">
                                                        </iframe>
                                                    @else
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada File</label>
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

    @push('js')
    @endpush
</x-backoffice.layout.app-layout>
