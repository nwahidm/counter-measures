<x-backoffice.layout.app-layout title="Detail Kasus">
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
    <x-backoffice.toolbar heading="Detail Kasus dan Target" subheading="" breadcrumb="open-case-detail" icon="fas fa-users">
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
                                            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Informasi Umum</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Penelitian</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Wawancara</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_4">Interogasi</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5">Pemancingan</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="myTabContent">
                                                <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                                                    <label class="fs-3 fw-bold">DETAIL KASUS:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Satuan Kerja</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->nama_satker ?? 'Belum Masuk Tahap Ini' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Nama Kasus</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->nama_kasus ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tanggal Kasus</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data && $data->tanggal_kasus ? $data->tanggal_kasus->isoFormat('DD MMMM YYYY') : 'Belum Masuk Tahap Ini' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-10">
                                                        <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->deskripsi_kasus ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <br>

                                                    <label class="fs-3 fw-bold">BIODATA TARGET:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Nama Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->nama_target ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Jenis Identitas Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->tipe_identitas ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">No. Identitas Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->no_identitas ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Agama Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->nama_agama ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Pendidikan Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->pendidikan ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Pekerjaan Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->pekerjaan ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Alamat Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->alamat ?? 'Belum Masuk Tahap Ini'}}</span>
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
                                                    <label class="fs-3 fw-bold">Surat Perintah:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Nomor Surat
                                                            Perintah</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $warant->surat_perintah_number ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Perihal Surat
                                                            Perintah</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $warant->surat_perintah_perihal ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tgl. Surat
                                                            Perintah</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">
                                                                {{ $warant && $warant->surat_perintah_date ? \Carbon\Carbon::parse($warant->surat_perintah_date)->isoFormat('DD MMMM YYYY') : 'Belum Masuk Tahap Ini' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tgl. Mulai Surat
                                                            Perintah</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">
                                                                {{ $warant && $warant->surat_perintah_date_started ? \Carbon\Carbon::parse($warant->surat_perintah_date_started)->isoFormat('DD MMMM YYYY') : 'Belum Masuk Tahap Ini' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tgl. Berakhir Surat
                                                            Perintah</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">
                                                                {{ $warant && $warant->surat_perintah_date_finished ? \Carbon\Carbon::parse($warant->surat_perintah_date_finished)->isoFormat('DD MMMM YYYY') : 'Belum Masuk Tahap Ini' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Upload File Surat Perintah</label>
                                                        <div class="col-lg-8">
                                                            <a class="btn btn-dark btn-sm btn-icon" href="{{ $warant && $warant->surat_perintah_path ? route('open.research.warrant.download-file', encrypt($warant->surat_perintah_path)) : '#' }}">
                                                                <i class="fas fa-file-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    
                                                    <label class="fs-3 fw-bold">Lapinsus:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Nomor Surat</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $lapinsus->nomor_surat ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tgl. Surat</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">
                                                                {{ $lapinsus && $lapinsus->tanggal_surat ? \Carbon\Carbon::parse($lapinsus->tanggal_surat)->isoFormat('DD MMMM YYYY') : 'Belum Masuk Tahap Ini' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Perihal Surat Surat</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $lapinsus->perihal_surat ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Informasi Yang Diperoleh</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $lapinsus && $lapinsus->informasi_diperoleh ? strip_tags($lapinsus->informasi_diperoleh) : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Sumber Informasi</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $lapinsus && $lapinsus->sumber_informasi ? strip_tags($lapinsus->sumber_informasi) : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tren Perkembangan / Perkiraan</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $lapinsus && $lapinsus->tren_perkembangan ? strip_tags($lapinsus->tren_perkembangan) : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Saran / Tindak</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $lapinsus && $lapinsus->saran_tindak ? strip_tags($lapinsus->saran_tindak) : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Pendapat</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $lapinsus && $lapinsus->pendapat ? strip_tags($lapinsus->pendapat) : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div> --}}
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Upload File Lapinsus</label>
                                                        <div class="col-lg-8">
                                                            <a class="btn btn-dark btn-sm btn-icon" href="{{ $lapinsus && $lapinsus->upload_lapinsus ? route('open.research.spesific-intel-report.download-file', encrypt($lapinsus->upload_lapinsus)) :'#' }}">
                                                                <i class="fas fa-file-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    {{-- <label class="fs-3 fw-bold">AGHT:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">No. Surat
                                                            Perintah</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $aght->sprint->nomor_sprint ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">No. Lapinsus</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $aght->lapinsus->nomor_surat ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Saran Tindak Lanjut</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800">
                                                                
                                                                {{ $aght && $aght->saranTl->saran_tl ? strip_tags($aght->saranTl->saran_tl) : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Jenis AGHT</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $aght->jenis_aght ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Waktu</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $aght && $aght->waktu ? $aght->waktu->isoFormat('DD MMMM YYYY') : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tempat</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $aght && $aght->tempat ? strip_tags($aght->tempat) : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Perihal</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $aght && $aght->perihal ? strip_tags($aght->perihal) : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Keterangan</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800">{{ $aght && $aght->keterangan ? strip_tags($aght->keterangan) : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div> --}}

                                                    <label class="fs-3 fw-bold">Saran dan Tindak Lanjut:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tgl. Tindak Lanjut</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $sarantl && $sarantl->saran_dan_tindak_lanjut_date ? \Carbon\Carbon::parse($sarantl->saran_dan_tindak_lanjut_date)->isoFormat('DD MMMM YYYY') : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Saran Tindak Lanjut</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $sarantl && $sarantl->saran_dan_tindak_lanjut ? strip_tags($sarantl->saran_dan_tindak_lanjut) : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                                                    <label class="fs-3 fw-bold">Jadwal Wawancara:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Nama Pewawancara</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $wawancarajadwal->interviewer_name ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Interviewer Schedule</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $wawancarajadwal && $wawancarajadwal->interviewer_schedule ? $wawancarajadwal->interviewer_schedule->isoFormat('DD MMMM YYYY') : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Nama Diwawancara</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                    class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $wawancarajadwal->source_person_name ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tipe Identitas Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $wawancarajadwal->target_type_identity_number ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">No. Identitas Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $wawancarajadwal->target_identity_number ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin Target</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">
                                                                {{ $wawancarajadwal && $wawancarajadwal->target_gender === 'L' ? 'Laki-laki' : ($wawancarajadwal && $wawancarajadwal->target_gender === 'P' ? 'Perempuan' : 'Belum Masuk Tahap Ini') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Agama Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $wawancarajadwal->target_religion ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Pekerjaan Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $wawancarajadwal->target_occupation ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Pendidikan Terakhir Target</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $wawancarajadwal->target_education ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Foto Target</label>
                                                        <div class="col-lg-8">
                                                            @if($wawancarajadwal && $wawancarajadwal->target_photo)
                                                                <img class="img-thumbnail" src="{{ asset('storage/' . $wawancarajadwal->target_photo) }}" style="max-width: 128px;">
                                                            @else
                                                                <span class="badge badge-danger">Tidak ada foto</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <label class="fs-3 fw-bold">Jadwal Wawancara:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">File Dokumen Wawancara</label>
                                                        <div class="col-lg-8">
                                                            @if($wawancarahasil && $wawancarahasil->upload_dokumen_wawancara)
                                                                <a class="btn btn-dark btn-sm btn-icon" href="{{ route('open.interview.hasil.download-dokumen-wawancara', encrypt($wawancarahasil->upload_dokumen_wawancara)) }}">
                                                                    <i class="fas fa-file-download"></i>
                                                                </a>
                                                            @else
                                                                <span
                                                                    class="badge badge-danger">Tidak ada dokumen wawancara</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">File Video Wawancara</label>
                                                        <div class="col-lg-8">
                                                            @if($wawancarahasil && $wawancarahasil->upload_video_wawancara)
                                                                <a class="btn btn-dark btn-sm btn-icon" href="{{ route('open.interview.hasil.download-video-wawancara', encrypt($wawancarahasil->upload_video_wawancara)) }}">
                                                                    <i class="fas fa-file-download"></i>
                                                                </a>
                                                            @else
                                                                <span
                                                                    class="badge badge-danger">Tidak ada video wawancara</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <label class="fs-3 fw-bold">Saran dan Tindaklanjut:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tgl. Saran Tindak Lanjut</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">
                                                                {{ $wawancarasaran && $wawancarasaran->saran_dan_tindak_lanjut_date ? $wawancarasaran->saran_dan_tindak_lanjut_date->isoFormat('DD MMMM YYYY') : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Saran Tindak Lanjut</label>
                                                        <div class="col-lg-8">
                                                            <span
                                                                class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $wawancarasaran && $wawancarasaran->saran_dan_tindak_lanjut ?  strip_tags($wawancarasaran->saran_dan_tindak_lanjut) : 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
                                                    <label class="fs-3 fw-bold">Interogasi Target:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Hasil Dicapai</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $interogasitarget->hasil_target_identification ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Dokumen Hasil Target Identification</label>
                                                        <div class="col-lg-8">
                                                            @if($interogasitarget && $interogasitarget->hasil_target_identification_path)
                                                                <a class="btn btn-dark btn-sm btn-icon" href="{{ route('open.data.interrog-target-id.download-file', encrypt($interogasitarget->hasil_target_identification_path)) }}">
                                                                    <i class="fas fa-file-download"></i>
                                                                </a>
                                                            @else
                                                                <span class="badge badge-danger">File Hasil Target Identification Tidak Ditemukan</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <label class="fs-3 fw-bold">Interogasi Hasil Dicapai:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Hasil Dicapai</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $interogasihasil->hasil_yang_dicapai ?? 'Belum Masuk Tahap Ini'!!}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Dokumen Hasil Target Identification</label>
                                                        <div class="col-lg-8">
                                                            @if($interogasihasil && $interogasihasil->upload_hasil_yang_dicapai)
                                                                <a class="btn btn-dark btn-sm btn-icon" href="{{ route('open.data.interogg-achieve.download-file', encrypt($interogasihasil->upload_hasil_yang_dicapai)) }}">
                                                                    <i class="fas fa-file-download"></i>
                                                                </a>
                                                            @else
                                                                <span class="badge badge-danger">File Hasil Yang Dicapai Tidak Ditemukan</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
                                                    <label class="fs-3 fw-bold">Pemancingan Hasil Wawancara:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Interviewer Name</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $pamancinganwawancara->interviewer_name ?? 'Data Tidak Ditemukan' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Nama</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{
                                                                $pamancinganwawancara->source_person_name ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">NIK/NIP</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{
                                                                $pamancinganwawancara->target_identity_number ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $pamancinganwawancara->target_gender
                                                                ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $pamancinganwawancara->target_religion
                                                                ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $pamancinganwawancara->target_education
                                                                ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $pamancinganwawancara->target_occupation
                                                                ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Dokumen</label>
                                                        <div class="col-lg-8">
                                                            @if($pamancinganwawancara && $pamancinganwawancara->interview_result)
                                                                <a class="btn btn-dark btn-sm btn-icon" href="{{ route('open.data.elicit-interview.download-file', encrypt($pamancinganwawancara->interview_result)) }}">
                                                                    <i class="fas fa-file-download"></i>
                                                                </a>
                                                            @else
                                                                <span class="text-danger">Data Tidak Ditemukan</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Foto</label>
                                                        <div class="col-lg-8">
                                                                @if($pamancinganwawancara && $pamancinganwawancara->target_photo)
                                                                    <img class="img-thumbnail" src="{{ asset('storage/' . $pamancinganwawancara->target_photo) }}" alt="Foto">
                                                                @else
                                                                    <span class="text-danger">Data Foto Tidak Ditemukan</span>
                                                                @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <label class="fs-3 fw-bold">Pemancingan Saran dan Tindak Lanjut:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Tanggal Tindak Lanjut</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $pamancingantl->saran_dan_tindak_lanjut_date ?? 'Belum Masuk Tahap Ini'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Saran Tindak Lanjut</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $pamancingantl->saran_dan_tindak_lanjut ?? 'Belum Masuk Tahap Ini'!!}</span>
                                                        </div>
                                                    </div>

                                                    <label class="fs-3 fw-bold">Pemancingan Hasil:</label> <hr>
                                                    <div class="row mb-5">
                                                        <label class="col-lg-4 fw-semibold text-muted">Hasil Yang Dicapai</label>
                                                        <div class="col-lg-8">
                                                            <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $pamancinganhasil->hasil_yang_dicapai ?? 'Belum Masuk Tahap Ini'}}</span>
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