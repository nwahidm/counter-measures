<x-backoffice.layout.app-layout title="Ubah Hasil Penyurupan">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Hasil Penyurupan" subheading="" breadcrumb="edit-intrusion-result"
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
                                    <form id="form" action="{{ route('close.intrusion.result.update', $data->id) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="satker_id"
                                                               class="fs-6 fw-semibold mb-2 required">Satuan Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('satker_id') is-invalid @enderror"
                                                            name="satker_id" id="satker_id"
                                                            data-control="select2" data-hide-search="false" disabled>
                                                            <option value="">---Pilih Satuan Kerja---</option>
                                                            @foreach ($satker as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] == $data->satker_id) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('satker_id')
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
                                                        <label for="intrusion_target_location_id"
                                                               class="fs-6 fw-semibold mb-2">Lokasi target</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('intrusion_target_location_id') is-invalid @enderror"
                                                            name="intrusion_target_location_id" id="intrusion_target_location_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih target---</option>
                                                            @foreach ($location as $row)
                                                                   <option
                                                                       value="{{ $row['id'] }}"
                                                                       @if($row['id'] === old('intrusion_target_location_id', $data->intrusion_target_location_id)) selected @endif>{{ $row['text'] }}</option>
                                                               @endforeach
                                                        </select>
                                                        @error('intrusion_target_location_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="intrusion_target_environment_id"
                                                               class="fs-6 fw-semibold mb-2">Lingkungan target</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('intrusion_target_environment_id') is-invalid @enderror"
                                                            name="intrusion_target_environment_id" id="intrusion_target_environment_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Lingkungan---</option>
                                                            @foreach ($environment as $row)
                                                                   <option
                                                                       value="{{ $row['id'] }}"
                                                                       @if($row['id'] === old('intrusion_target_environment_id', $data->intrusion_target_environment_id)) selected @endif>{{ $row['text'] }}</option>
                                                               @endforeach
                                                        </select>
                                                        @error('intrusion_target_environment_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="upload_hasil_yang_dicapai"
                                                               class="fs-6 fw-semibold mb-2 ">Upload Hasil yang Dicapai</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('upload_hasil_yang_dicapai') is-invalid @enderror"
                                                                name="upload_hasil_yang_dicapai"
                                                                type="file"
                                                                id="upload_hasil_yang_dicapai"
                                                                value="{{ old('upload_hasil_yang_dicapai') }}">
                                                            @if($data->upload_hasil_yang_dicapai)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.intrusion.result.download-file', encrypt($data->upload_hasil_yang_dicapai)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('upload_hasil_yang_dicapai')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->upload_hasil_yang_dicapai)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_upload_hasil_yang_dicapai') is-invalid @enderror"
                                                                name="temp_upload_hasil_yang_dicapai"
                                                                type="hidden"
                                                                id="temp_upload_hasil_yang_dicapai"
                                                                value="{{ old('temp_upload_hasil_yang_dicapai', $data->upload_hasil_yang_dicapai) }}">
                                                            @error('temp_upload_hasil_yang_dicapai')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="hasil_yang_dicapai" class="fs-6 fw-semibold mb-2 required">Hasil Yang Dicapai</label>
                                                        <textarea required class="form-control form-control-solid @error('hasil_yang_dicapai') is-invalid @enderror" name="hasil_yang_dicapai" id="hasil_yang_dicapai"
                                                        rows="4">{!! old('hasil_yang_dicapai', $data->hasil_yang_dicapai) !!}</textarea>
                                                        <p class="text-danger">{{ $errors->first('hasil_yang_dicapai') }}</p>
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

                    // ajax get case list
                    $('#satker_id').change(function() {
                        $('#case_id').empty();
                        $('#intrusion_target_location_id').empty();
                        $('#intrusion_target_environment_id').empty();

                        $('#case_id').append('<option value="">---Pilih Kasus---</option>');
                        $('#intrusion_target_location_id').append('<option value="">---Pilih Surat perintah---</option>');
                        $('#intrusion_target_environment_id').append('<option value="">---Pilih Lingkungan---</option>');

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

                    // ajax get list location
                    $('#case_id').change(function() {
                        var case_id = $(this).val();

                        $.ajax({
                            url: '/close/helper-target-loc', 
                            type: 'GET',
                            data: {case_id: case_id},
                            success: function(response) {
                                $('#intrusion_target_location_id').empty();
                                $('#intrusion_target_location_id').append('<option value="">---Pilih Target---</option>');

                                $.each(response, function(key, value) {
                                    $('#intrusion_target_location_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#intrusion_target_location_id').select2();
                            }
                        });
                    });

                    // ajax get list env
                    $('#intrusion_target_location_id').change(function() {
                        var intrusion_target_location_id = $(this).val();

                        $.ajax({
                            url: '/close/helper-target-env', 
                            type: 'GET',
                            data: {intrusion_target_location_id: intrusion_target_location_id},
                            success: function(response) {
                                $('#intrusion_target_environment_id').empty();
                                $('#intrusion_target_environment_id').append('<option value="">---Pilih Lingkungan---</option>');

                                $.each(response, function(key, value) {
                                    $('#intrusion_target_environment_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#intrusion_target_environment_id').select2();
                            }
                        });
                    });

                    ClassicEditor
                    .create(document.querySelector('#hasil_yang_dicapai'),{
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
