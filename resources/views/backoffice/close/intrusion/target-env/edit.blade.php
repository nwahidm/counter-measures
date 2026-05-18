<x-backoffice.layout.app-layout title="Ubah Lingkungan Target Penyurupan">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Lingkungan Target Penyurupan" subheading="" breadcrumb="edit-intrusion-target-env"
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
                                    <form id="form" action="{{ route('close.intrusion.target-env.update', $data->id) }}"
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
                                                        <label for="nama_lingkungan" class="fs-6 fw-semibold mb-2 required">Nama Lingkungan</label>
                                                        <input required type="text" class="form-control" name="nama_lingkungan" id="nama_lingkungan" placeholder="" value="{{old('nama_lingkungan', $data->nama_lingkungan)}}" >
                                                        <p class="text-danger">{{ $errors->first('nama_lingkungan') }}</p>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="tipe_lingkungan" class="fs-6 fw-semibold mb-2 required">Tipe Lingkungan</label>
                                                        <input required type="text" class="form-control" name="tipe_lingkungan" id="tipe_lingkungan" placeholder="" value="{{old('tipe_lingkungan', $data->tipe_lingkungan)}}" >
                                                        <p class="text-danger">{{ $errors->first('tipe_lingkungan') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="deskripsi_lingkungan" class="fs-6 fw-semibold mb-2 required">Deskripsi Lingkungan</label>
                                                        <textarea class="form-control" name="deskripsi_lingkungan" id="deskripsi_lingkungan" rows="4">{{old('deskripsi_lingkungan', $data->deskripsi_lingkungan)}}</textarea>
                                                        <p class="text-danger">{{ $errors->first('deskripsi_lingkungan') }}</p>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="informasi_terkumpul" class="fs-6 fw-semibold mb-2 ">Informasi Terkumpul</label>
                                                        <textarea class="form-control" name="informasi_terkumpul" id="informasi_terkumpul" rows="4">{{old('informasi_terkumpul', $data->informasi_terkumpul)}}</textarea>
                                                        <p class="text-danger">{{ $errors->first('informasi_terkumpul') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="aktivitas_teramati" class="fs-6 fw-semibold mb-2 ">Aktivitas Teramati</label>
                                                        <textarea class="form-control" name="aktivitas_teramati" id="aktivitas_teramati" rows="4">{{old('aktivitas_teramati', $data->aktivitas_teramati)}}</textarea>
                                                        <p class="text-danger">{{ $errors->first('aktivitas_teramati') }}</p>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_environment_upload"
                                                               class="fs-6 fw-semibold mb-2 ">Upload File Lokasi</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('target_environment_upload') is-invalid @enderror"
                                                                name="target_environment_upload"
                                                                type="file"
                                                                id="target_environment_upload"
                                                                value="{{ old('target_environment_upload') }}">
                                                            @if($data->target_environment_upload)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.intrusion.target-env.download-file', encrypt($data->target_environment_upload)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('target_environment_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->target_environment_upload)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_target_environment_upload') is-invalid @enderror"
                                                                name="temp_target_environment_upload"
                                                                type="hidden"
                                                                id="temp_target_environment_upload"
                                                                value="{{ old('temp_target_environment_upload', $data->target_environment_upload) }}">
                                                            @error('temp_target_environment_upload')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="video_upload"
                                                               class="fs-6 fw-semibold mb-2 ">File Video</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('video_upload') is-invalid @enderror"
                                                                name="video_upload"
                                                                type="file"
                                                                id="video_upload"
                                                                value="{{ old('video_upload') }}">
                                                            @if($data->video_upload)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('open.research.warrant.download-file', encrypt($data->video_upload)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('video_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->video_upload)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_video_upload') is-invalid @enderror"
                                                                name="temp_video_upload"
                                                                type="hidden"
                                                                id="temp_video_upload"
                                                                value="{{ old('temp_video_upload', $data->video_upload) }}">
                                                            @error('temp_video_upload')
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

                    // ajax get case list
                    $('#satker_id').change(function() {
                        $('#case_id').empty();
                        $('#intrusion_target_location_id').empty();

                        $('#case_id').append('<option value="">---Pilih Kasus---</option>');
                        $('#intrusion_target_location_id').append('<option value="">---Pilih Surat perintah---</option>');

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

                    ClassicEditor
                    .create(document.querySelector('#deskripsi_lingkungan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });
                    ClassicEditor
                    .create(document.querySelector('#informasi_terkumpul'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });
                    ClassicEditor
                    .create(document.querySelector('#aktivitas_teramati'),{
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
