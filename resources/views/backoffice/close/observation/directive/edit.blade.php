<x-backoffice.layout.app-layout title="Ubah Surat Perintah Pengamatan">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Surat Perintah Pengamatan" subheading="" breadcrumb="edit-observation-directive"
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
                                    <form id="form" action="{{ route('close.observation.directive.update', $data->id) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="id_satker"
                                                               class="fs-6 fw-semibold mb-2 required">Satuan Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_satker') is-invalid @enderror"
                                                            name="id_satker" id="id_satker"
                                                            data-control="select2" data-hide-search="false" disabled>
                                                            <option value="">---Pilih Satuan Kerja---</option>
                                                            @foreach ($satker as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] == $data->satker_id) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_satker')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="case_id"
                                                               class="fs-6 fw-semibold mb-2 required">Kasus</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('case_id') is-invalid @enderror"
                                                            name="case_id" id="case_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Kasus---</option>
                                                            @foreach ($case as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('case_id', $data->case_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('case_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="surat_perintah_perihal"
                                                               class="fs-6 fw-semibold mb-2 required">Perihal Surat
                                                            Perintah</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('surat_perintah_perihal') is-invalid @enderror"
                                                               name="surat_perintah_perihal" id="surat_perintah_perihal"
                                                               value="{{ old('surat_perintah_perihal', $data->surat_perintah_perihal) }}">
                                                        @error('surat_perintah_perihal')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="surat_perintah_number"
                                                               class="fs-6 fw-semibold mb-2 required">Nomor Surat
                                                            Perintah</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('surat_perintah_number') is-invalid @enderror"
                                                               name="surat_perintah_number" id="surat_perintah_number"
                                                               value="{{ old('surat_perintah_number', $data->surat_perintah_number) }}">
                                                        @error('surat_perintah_number')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="surat_perintah_date"
                                                               class="fs-6 fw-semibold mb-2">Tgl. Surat
                                                            Perintah</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('surat_perintah_date') is-invalid @enderror"
                                                               name="surat_perintah_date" id="surat_perintah_date"
                                                               value="{{ old('surat_perintah_date', $data->surat_perintah_date?->toDateString()) }}">
                                                        @error('surat_perintah_date')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="surat_perintah_date_started"
                                                               class="fs-6 fw-semibold mb-2">Tgl. Mulai Surat
                                                            Perintah</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('surat_perintah_date_started') is-invalid @enderror"
                                                               name="surat_perintah_date_started" id="surat_perintah_date_started"
                                                               value="{{ old('surat_perintah_date_started', $data->surat_perintah_date_started?->toDateString()) }}">
                                                        @error('surat_perintah_date_started')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="surat_perintah_path"
                                                               class="fs-6 fw-semibold mb-2 required">Upload File
                                                            Surat Perintah (Pdf)</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('surat_perintah_path') is-invalid @enderror"
                                                                name="surat_perintah_path"
                                                                type="file"
                                                                accept=".pdf"
                                                                id="surat_perintah_path"
                                                                value="{{ old('surat_perintah_path') }}">
                                                            @if($data->surat_perintah_path)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.observation.directive.download-file', encrypt($data->surat_perintah_path)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('surat_perintah_path')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->surat_perintah_path)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_surat_perintah_path') is-invalid @enderror"
                                                                name="temp_surat_perintah_path"
                                                                type="hidden"
                                                                id="temp_surat_perintah_path"
                                                                value="{{ old('temp_surat_perintah_path', $data->surat_perintah_path) }}">
                                                            @error('temp_surat_perintah_path')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                </div>
                                                <input type="hidden" name="submit_type" id="submit_type" value="">
                                                
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <button
                                                            class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                            onclick="setSubmitType('update')"
                                                            type="submit">Update
                                                        </button>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button
                                                            class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                            onclick="setSubmitType('update_and_finish')"
                                                            type="submit">Update dan Selesai
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

                    $('#id_satker').change(function() {
                        $('#surat_perintah_id').empty();
                        var satker_id = $(this).val();

                        // Make an AJAX request to your controller to retrieve the list of cases based on the selected satker_id
                        $.ajax({
                            url: '/close/helper-case', // Replace this with the actual route to your controller
                            type: 'GET',
                            data: {satker_id: satker_id},
                            success: function(response) {
                                // Clear the existing options in the case_id select element
                                $('#case_id').empty();
                                $('#case_id').append('<option value="">---Pilih Kasus---</option>');
                                // Add the new options to the case_id select element
                                $.each(response, function(key, value) {
                                    $('#case_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                // Re-initialize the select2 plugin on the case_id select element
                                $('#case_id').select2();
                            }
                        });
                    });
                }
            };
        </script>
    @endpush
</x-backoffice.layout.app-layout>
