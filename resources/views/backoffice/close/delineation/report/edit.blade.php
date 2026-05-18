<x-backoffice.layout.app-layout title="Ubah Research Warrant">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Research Warrant" subheading="" breadcrumb="open-research-warrant-create"
                          icon="fas fa-users">
        <div class="d-flex align-items-center w-25">

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
                                    <form id="form" action="{{ route('open.research.warrant.update', $data->id) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card ">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold d-none">DETAIL KASUS:</label>
                                                <hr class="d-none">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="id_satker" class="fs-6 fw-semibold mb-2 required">Satuan
                                                            Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_satker') is-invalid @enderror"
                                                            name="id_satker" id="id_satker"
                                                            data-control="select2" data-hide-search="true" disabled>
                                                            <option value="">---Pilih Satker---</option>
                                                            @foreach ($satker as $row)
                                                                <option value="{{ $row['id'] }}"
                                                                        @if($row['kode_satker'] === $data->kode_satker) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_satker')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="id_case"
                                                               class="fs-6 fw-semibold mb-2 required">Kasus</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_case') is-invalid @enderror"
                                                            name="id_case" id="id_case"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Kasus---</option>
                                                            @foreach ($case as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('id_case', $data->id_case)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="nomor_sprint"
                                                               class="fs-6 fw-semibold mb-2 required">Nomor Surat
                                                            Perintah</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('nomor_sprint') is-invalid @enderror"
                                                               name="nomor_sprint" id="nomor_sprint"
                                                               value="{{ old('nomor_sprint', $data->nomor_sprint) }}">
                                                        @error('nomor_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="perihal_sprint"
                                                               class="fs-6 fw-semibold mb-2 required">Perihal Surat
                                                            Perintah</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('perihal_sprint') is-invalid @enderror"
                                                               name="perihal_sprint" id="perihal_sprint"
                                                               value="{{ old('perihal_sprint', $data->perihal_sprint) }}">
                                                        @error('perihal_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="tanggal_sprint"
                                                               class="fs-6 fw-semibold mb-2 required">Tgl. Surat
                                                            Perintah</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_sprint') is-invalid @enderror"
                                                               name="tanggal_sprint" id="tanggal_sprint"
                                                               value="{{ old('tanggal_sprint', $data->tanggal_sprint->toDateString()) }}">
                                                        @error('tanggal_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="tanggal_mulai_sprint"
                                                               class="fs-6 fw-semibold mb-2 required">Tgl. Mulai Surat
                                                            Perintah</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_mulai_sprint') is-invalid @enderror"
                                                               name="tanggal_mulai_sprint" id="tanggal_mulai_sprint"
                                                               value="{{ old('tanggal_mulai_sprint', $data->tanggal_mulai_sprint->toDateString()) }}">
                                                        @error('tanggal_mulai_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="tanggal_akhir_sprint"
                                                               class="fs-6 fw-semibold mb-2 required">Tgl. Berakhir
                                                            Surat Perintah</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_akhir_sprint') is-invalid @enderror"
                                                               name="tanggal_akhir_sprint" id="tanggal_akhir_sprint"
                                                               value="{{ old('tanggal_akhir_sprint', $data->tanggal_akhir_sprint->toDateString()) }}">
                                                        @error('tanggal_akhir_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="upload_sprint"
                                                               class="fs-6 fw-semibold mb-2 required">Upload File
                                                            Surat Perintah</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('upload_sprint') is-invalid @enderror"
                                                                name="upload_sprint"
                                                                type="file"
                                                                id="upload_sprint"
                                                                value="{{ old('upload_sprint') }}">
                                                            @if($data->upload_sprint)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('open.research.warrant.download-file', encrypt($data->upload_sprint)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('upload_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->upload_sprint)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_upload_sprint') is-invalid @enderror"
                                                                name="temp_upload_sprint"
                                                                type="hidden"
                                                                id="temp_upload_sprint"
                                                                value="{{ old('temp_upload_sprint', $data->upload_sprint) }}">
                                                            @error('temp_upload_sprint')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <button
                                                            class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                            type="submit">Simpan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

                }
            };
        </script>
    @endpush
</x-backoffice.layout.app-layout>
