<x-backoffice.layout.app-layout title="Detail Verifikasi Informasi">
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
    <x-backoffice.toolbar heading="Detail Verifikasi Informasi" subheading="" breadcrumb="open-case-detail"
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
                                            <label class="fs-3 fw-bold">DETAIL KASUS:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Satuan Kerja</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $satker?->nama_satker }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $case?->case_name }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $case->case_date?->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-10">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{!! $case->case_description !!}</span>
                                                </div>
                                            </div>
                                            <label class="fs-3 fw-bold">BIODATA TARGET:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $case->target_name }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jenis Identitas
                                                    Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $case->target_identity_number_type }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">No. Identitas
                                                    Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $case->target_identity_number }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Agama Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $case->target_religion }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pendidikan Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $case->target_education }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pekerjaan Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $case->target_occupation }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Alamat Target</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $case->target_address }}</span>
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">Verifikasi Informasi</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kredibilitas
                                                    Sumber</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $data->kredibilitas_sumber }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Metode Verifikasi</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $data->metode_verifikasi }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Detail Informasi
                                                    Verifikasi</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ strip_tags($data->detail_informasi_verifikasi) }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Diveriikasi Oleh</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ $data->verified_by }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal
                                                    Verifikasi</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{  \Carbon\Carbon::parse($data->verification_date)->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Sumber Informasi</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        >
                     

                                                        {{ strip_tags($observation_information_collection?->information_collection_source) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Koleksi
                                                    Informasi</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                        style="text-align: justify">{{ \Carbon\Carbon::parse(strip_tags($observation_information_collection?->information_collection_date) )->isoFormat('DD MMMM YYYY')  }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Detail Koleksi
                                                    Infomasi</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800"
                                                    style="text-align: justify">{{ strip_tags($observation_information_collection?->information_collection_detail) }}</span>
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