<x-backoffice.layout.app-layout title="Ubah Pengumpulan Informasi">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Pengumpulan Informasi" subheading="" breadcrumb="edit-observation-collect-info"
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
                                    <form id="form" action="{{ route('close.observation.collect-info.update', $data->id) }}"
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
                                                        <label for="surat_perintah_id"
                                                               class="fs-6 fw-semibold mb-2">Surat perintah</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('surat_perintah_id') is-invalid @enderror"
                                                            name="surat_perintah_id" id="surat_perintah_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Surat Perintah---</option>
                                                            @foreach ($surat_perintah as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('surat_perintah_id', $data->surat_perintah_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('surat_perintah_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="information_collection_source"
                                                               class="fs-6 fw-semibold mb-2 required">Sumber Informasi</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('information_collection_source') is-invalid @enderror"
                                                               name="information_collection_source" id="information_collection_source"
                                                               value="{{ old('information_collection_source', $data->information_collection_source) }}">
                                                        @error('information_collection_source')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="information_collection_date"
                                                               class="fs-6 fw-semibold mb-2">Tanggal Informasi</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('information_collection_date') is-invalid @enderror"
                                                               name="information_collection_date" id="information_collection_date"
                                                               value="{{ old('information_collection_date') ?? $data->information_collection_date?->toDateString() }}">
                                                        @error('information_collection_date')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="information_collection_perihal"
                                                               class="fs-6 fw-semibold mb-2 required">Perihal Informasi</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('information_collection_perihal') is-invalid @enderror"
                                                               name="information_collection_perihal" id="information_collection_perihal"
                                                               value="{{ old('information_collection_perihal', $data->information_collection_perihal) }}">
                                                        @error('information_collection_perihal')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="information_collection_upload"
                                                               class="fs-6 fw-semibold mb-2 ">Upload File
                                                            Informasi (Pdf)</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('information_collection_upload') is-invalid @enderror"
                                                                name="information_collection_upload"
                                                                type="file"
                                                                accept=".pdf"
                                                                id="information_collection_upload"
                                                                value="{{ old('information_collection_upload') }}">
                                                            @if($data->information_collection_upload)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.observation.collect-info.download-file', encrypt($data->information_collection_upload)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('information_collection_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->information_collection_upload)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_information_collection_upload') is-invalid @enderror"
                                                                name="temp_information_collection_upload"
                                                                type="hidden"
                                                                id="temp_information_collection_upload"
                                                                value="{{ old('temp_information_collection_upload', $data->information_collection_upload) }}">
                                                            @error('temp_information_collection_upload')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="information_collection_detail"
                                                               class="fs-6 fw-semibold mb-2 required">Detail Informasi</label>
                                                        <textarea class="form-control ckeditor1" name="information_collection_detail"
                                                        rows="4">{!! old('information_collection_detail', $data->information_collection_detail) !!}</textarea>
                                                        @error('information_collection_detail')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
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
    <script type="module">
        // CKEDITOR SETTING
        document.addEventListener('DOMContentLoaded', () => {
            ClassicEditor
                .create(document.querySelector('textarea.ckeditor1'))
                .catch(error => {
                    console.error(error);
            });
        });
    </script>
    <script>
        document.onreadystatechange = function () {
            if (document.readyState === 'complete') {
                $('.select').select2();

                // ajax get case list
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

                // ajax get list sprint
                $('#case_id').change(function() {
                    var case_id = $(this).val();

                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected satker_id
                    $.ajax({
                        url: '/close/helper-sprint', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {case_id: case_id},
                        success: function(response) {
                            $('#surat_perintah_id').empty();
                            $('#surat_perintah_id').append('<option value="">---Pilih Surat Perintah---</option>');

                            $.each(response, function(key, value) {
                                $('#surat_perintah_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#surat_perintah_id').select2();
                        }
                    });
                });

            }
        };
    </script>
    @endpush
</x-backoffice.layout.app-layout>
