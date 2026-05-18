<x-backoffice.layout.app-layout title="DETAIL Catatan Interogasi">
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
    <x-backoffice.toolbar heading="DETAIL Catatan Interogasi" subheading="" breadcrumb="interogation-record-detail"
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
                                        <div class="card-body">
                                            <label class="fs-3 fw-bold">DETAIL KASUS:</label> <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->case->nama_kasus }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->case->deskripsi_kasus }}</span>
                                                </div>
                                            </div>
                                            @if ($data->berita_acara_path)
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Berita Acara</label>
                                                <div class="col-lg-8">
                                                    @if ($data->berita_acara_path)
                                                        <iframe style="width: 100%; height: 500px; overflow: hidden;" src="{{ asset('storage/'. $data->berita_acara_path) }}" frameborder="0" id="berita_acara_path">
                                                        </iframe>
                                                    @else
                                                        <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada File</label>
                                                    @endif
                                                </div>
                                            </div>
                                            @else
                                            

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nomor Surat</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->letter_number }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Surat</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{  \Carbon\Carbon::parse( $data->letter_date)->isoFormat('DD MMMM YYYY')  }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Perihal</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->perihal }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_name }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_gender }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_religion }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_occupation }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_education }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tempat Lahir</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->born_place }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Lahir</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ \Carbon\Carbon::parse($data->born_date)->isoFormat('DD MMMM YYYY')  }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kebangsaan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->nationality }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jaksa Pelaksana</label>
                                                <div class="col-lg-8">
                                                    @foreach ($listJaksa as $jaksa)
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $jaksa }}</span>
                                                    <br>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Hasil Permintaan Keterangan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->hasil !!}</span>
                                                </div>
                                            </div>
                                            {{-- <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">File Surat</label>
                                                <div class="col-lg-8">
                                                    <a class="btn btn-dark btn-sm btn-icon" href="{{ route('open.data.interrog-record.download-file', encrypt($data->berita_acara_path)) }}">
                                                        <i class="fas fa-file-download"></i>
                                                    </a>
                                                </div>
                                            </div> --}}
                                            
                                            <div class="row mb-7">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Foto Target</label>
                                                <div class="image-preview-container">
                                                    @foreach ($images as $image)
                                                        <img class="image-preview" style="max-width: 350px; margin-right: 10px; margin-bottom: 10px;" src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}" alt="Preview">
                                                    @endforeach
                                                </div>
                                            </div>
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

    @push('js')
    @endpush
</x-backoffice.layout.app-layout>