<x-backoffice.layout.app-layout title="Ubah Data Body Camera">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Data Body Camera" subheading=""
                          breadcrumb="open-research-spesific-intel-report-create"
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
                                    <form id="form"
                                          action="{{ route('bodycam.body-cam.update', $data->id) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="id_satker" class="fs-6 fw-semibold mb-2 required">Satuan
                                                            Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_satker') is-invalid @enderror"
                                                            name="id_satker" id="id_satker"
                                                            data-control="select2" data-hide-search="true" enabled>
                                                            <option value="">---Pilih Satker---</option>
                                                            @foreach ($satker as $row)
                                                                <option value="{{ $row['id'] }}"
                                                                        @if((string)$row['id'] === (string)$data->device_used_for) selected @endif>
                                                                    {{ $row['text'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_satker')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    
                                                   
                                                </div>
                                                <div class="row mb-7">
                                               
                                                <div class="form-group col-md-4">
                                                        <label for="perihal_surat"
                                                               class="fs-6 fw-semibold mb-2 required">Nama Alat</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('device_name') is-invalid @enderror"
                                                               name="device_name" id="device_name"
                                                               value="{{ old('device_name', $data->device_name) }}">
                                                        @error('device_name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="device_source_url"
                                                               class="fs-6 fw-semibold mb-2 required">URL Streaming
                                                            Verifikasi</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('device_source_url') is-invalid @enderror"
                                                               name="device_source_url" id="device_source_url"
                                                               value="{{ old('device_source_url', $data->device_source_url) }}">
                                                        @error('device_source_url')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="device_dahua_id"
                                                               class="fs-6 fw-semibold mb-2 required">Dahua Device Id</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('device_dahua_id') is-invalid @enderror"
                                                               name="device_dahua_id" id="device_dahua_id"
                                                               value="{{ old('device_dahua_id', $data->device_dahua_id) }}">
                                                        @error('device_dahua_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    
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

                    ClassicEditor
                        .create(document.querySelector('#detail_informasi_verifikasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#data_fakta'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#telaahan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#kesimpulan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#pendapat'),{
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
