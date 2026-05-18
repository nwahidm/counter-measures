<x-backoffice.layout.app-layout title="Tambah Penyadapan Perangkat Elektronik">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Penyadapan Perangkat Elektronik" subheading=""
                          breadcrumb="close-tapping-electronic_device-create"
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
                                    <form id="form" action="{{ route('close.tapping.electronic_device.store') }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="id_satker" class="fs-6 fw-semibold mb-2 required">Satuan
                                                            Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_satker') is-invalid @enderror"
                                                            name="id_satker" id="id_satker"
                                                            data-control="select2" data-hide-search="true"
                                                            @if(auth()->user()->user_roles != "superadmin") disabled @endif>
                                                            <option value="">---Pilih Satker---</option>
                                                            @foreach ($satker as $row)
                                                                <option value="{{ $row['id'] }}"
                                                                        @if($row['id'] == auth()->user()->id_satker) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_satker')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if(auth()->user()->user_roles != "superadmin")
                                                            <input type="hidden" name="id_satker" value="{{ auth()->user()?->satker?->id_satker }}">
                                                        @endif
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
                                                                    @if($row['id'] === old('id_case')) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="tanggal_penyadapan"
                                                               class="fs-6 fw-semibold mb-2 required">Tgl.
                                                            Penyadapan</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_penyadapan') is-invalid @enderror"
                                                               name="tanggal_penyadapan" id="tanggal_penyadapan"
                                                               value="{{ old('tanggal_penyadapan') }}">
                                                        @error('tanggal_penyadapan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="sumber_data"
                                                               class="fs-6 fw-semibold mb-2 required">Sumber
                                                            Data</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('sumber_data') is-invalid @enderror"
                                                               name="sumber_data" id="sumber_data"
                                                               value="{{ old('sumber_data') }}">
                                                        @error('sumber_data')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="metode_penyadapan"
                                                               class="fs-6 fw-semibold mb-2 ">Metode
                                                            Penyadapan</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('metode_penyadapan') is-invalid @enderror"
                                                            name="metode_penyadapan"
                                                            id="metode_penyadapan">{{ old('metode_penyadapan') }}</textarea>
                                                        @error('metode_penyadapan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="deskripsi_hasil"
                                                               class="fs-6 fw-semibold mb-2 ">Deskripsi Hasil</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('deskripsi_hasil') is-invalid @enderror"
                                                            name="deskripsi_hasil"
                                                            id="deskripsi_hasil">{{ old('deskripsi_hasil') }}</textarea>
                                                        @error('deskripsi_hasil')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="dokumen_upload"
                                                               class="fs-6 fw-semibold mb-2 ">File Dokumen</label>
                                                        <input
                                                            class="form-control form-control-solid @error('dokumen_upload') is-invalid @enderror"
                                                            name="dokumen_upload"
                                                            type="file"
                                                            id="dokumen_upload"
                                                            value="{{ old('dokumen_upload') }}">
                                                        @error('dokumen_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="video_upload"
                                                               class="fs-6 fw-semibold mb-2 ">File Video</label>
                                                        <input
                                                            class="form-control form-control-solid @error('video_upload') is-invalid @enderror"
                                                            name="video_upload"
                                                            type="file"
                                                            id="video_upload"
                                                            value="{{ old('video_upload') }}">
                                                        @error('video_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <input type="hidden" name="submit_type" id="submit_type" value="">
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <button
                                                            class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                            onclick="setSubmitType('save')"
                                                            type="submit">Simpan
                                                        </button>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button
                                                            class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                            onclick="setSubmitType('save_and_finish')"
                                                            type="submit">Simpan dan Selesai
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
            function setSubmitType(type) {
                document.getElementById('submit_type').value = type;
            }
        </script>
        <script>
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

                    ClassicEditor
                        .create(document.querySelector('#metode_penyadapan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    ClassicEditor
                        .create(document.querySelector('#deskripsi_hasil'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            };
        </script>
    @endpush
</x-backoffice.layout.app-layout>
