<x-backoffice.layout.app-layout title="Detail Penelitian Laporan Informasi Khusus">
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
    <x-backoffice.toolbar heading="Detail Penelitian Laporan Informasi Khusus" subheading="" breadcrumb="open-case-detail"
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
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->case->tanggal_kasus?->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->case->deskripsi_kasus }}</span>
                                                </div>
                                            </div>
                                            
                                            <label class="fs-3 fw-bold">DETAIL SURAT PERINTAH:</label> <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nomor Surat
                                                    Perintah</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800">{{ $data->researchSuratPerintah->surat_perintah_number ?? 'No Data Available' }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Perihal Surat
                                                    Perintah</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->researchSuratPerintah->surat_perintah_perihal ?? 'No Data Available'  }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tgl. Surat
                                                    Perintah</label>
                                                <div class="col-lg-8">
                                                   
                                                <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ optional($data->researchSuratPerintah)->surat_perintah_date?->isoFormat('DD MMMM YYYY') ?? 'No Date Available' }}</span>


                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tgl. Mulai Surat
                                                    Perintah</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ optional($data->researchSuratPerintah)->surat_perintah_date_started?->isoFormat('DD MMMM YYYY') ?? 'No Data Available'  }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tgl. Selesai Surat
                                                    Perintah</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ optional($data->researchSuratPerintah)->surat_perintah_date_finished?->isoFormat('DD MMMM YYYY') ?? 'No Data Available'  }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Surat Perintah</label>
                                                <div class="col-lg-8">
                                                    @if (optional($data->researchSuratPerintah)->surat_perintah_path)
                                                        <iframe style="width: 100%; height: 500px; overflow: hidden;" src="{{ asset('storage/'. $data->file_laporan_informasi_khusus) }}" frameborder="0" id="file_laporan_informasi_khusus">
                                                        </iframe>
                                                    @else
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada File</label>
                                                    @endif
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">DETAIL LAPORAN INFORMASI KHUSUS:</label> <hr>
                                            @if(!$data->file_laporan_informasi_khusus)
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nomor Surat</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->nomor_surat }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Perihal Surat</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->perihal_surat }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tgl. Surat
                                                    Perintah</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->tanggal_surat?->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Informasi Yang Diperoleh</label>
                                                <div class="col-lg-8">
                                                    <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ strip_tags($data->informasi_diperoleh) }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Sumber Informasi</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ strip_tags($data->sumber_informasi) }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tren Perkembangan / Perkiraan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ strip_tags($data->tren_perkembangan) }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Saran / Tindak</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ strip_tags($data->saran_tindak) }}</span>
                                                </div>
                                            </div>
                                            @else
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Download File Laporan Informasi Khusus</label>
                                                <div class="col-lg-8">
                                                    @if ($data->file_laporan_informasi_khusus)
                                                        <iframe style="width: 100%; height: 500px; overflow: hidden;" src="{{ asset('storage/'. $data->file_laporan_informasi_khusus) }}" frameborder="0" id="file_laporan_informasi_khusus">
                                                        </iframe>
                                                    @else
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada File</label>
                                                    @endif
                                                    {{-- <a class="btn btn-dark btn-sm btn-icon" href="{{ route('open.research.spesific-intel-report.download-file', encrypt($data->file_laporan_informasi_khusus)) }}">
                                                        <i class="fas fa-file-download"></i>
                                                    </a> --}}
                                                </div>
                                            </div>
                                            @endif
                                            <label class="fs-3 fw-bold">ANALISA DOKUMEN LAPORAN INFORMASI KHUSUS:</label> <hr>
                                            <div class="row mb-5" style="text-align: justify;">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Analisa</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $document_pdf_data?->doc_analytics_2 }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5 " style="text-align: justify;">
                                                <label class="col-lg-4 fw-semibold text-muted" >Hasil Kesimpulan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $document_pdf_data?->doc_summary_2 }}</span>
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
