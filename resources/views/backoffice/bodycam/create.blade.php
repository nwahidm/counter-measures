<x-backoffice.layout.app-layout title="Tambah Body Camera">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Body Camera" subheading=""
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
                                    <form id="form" action="{{ route('bodycam.body-cam.store') }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="id_satker" class="fs-6 fw-semibold mb-2 required">Satuan
                                                            Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_satker') is-invalid @enderror"
                                                            name="id_satker" id="id_satker"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Satker---</option>
                                                            @foreach ($satker as $row)
                                                                <option value="{{ $row['id'] }}"
                                                                        @if($row['id'] === auth()->user()->satker->id_satker) selected @endif>{{ $row['text'] }}</option>
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
                                                               value="{{ old('device_name') }}">
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
                                                               value="{{ old('device_source_url') }}">
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
                                                               value="{{ old('device_dahua_id') }}">
                                                        @error('device_dahua_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
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
        <script type="module">
            $(document).ready(function () {
                $('.select').select2();

                $('#id_case').change(function() {
                    var case_id = $(this).val();
                    console.log("Case ID changed:", case_id);

                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected case_id
                    $.ajax({
                        url: '/close/helper-information-collection', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {case_id: case_id},
                        success: function(response) {
                            $('#id_information_collection').empty();

                            $.each(response, function(key, value) {
                                $('#id_information_collection').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#id_information_collection').select2(); // Reinitialize select2
                        }
                    });
                });
            });
        </script>
    @endpush
</x-backoffice.layout.app-layout>
