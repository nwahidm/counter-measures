<x-backoffice.layout.app-layout title="Ubah Penyadapan Hasil Pencapaian">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Penyadapan Hasil Pencapaian" subheading=""
                          breadcrumb="close-tapping-result_achievement-edit"
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
                                    <form id="form" action="{{ route('close.tapping.result_achievement.update', $data->id_tapping_result_achievement) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="card ">
                                            <div class="card-body">
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
                                                                        @if($row['id'] == $data->satker_id) selected @endif>{{ $row['text'] }}</option>
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
                                                                    @if($row['id'] == $data->case_id) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="tapping_electronic_device_data_id"
                                                               class="fs-6 fw-semibold mb-2 ">Electronic Device</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('tapping_electronic_device_data_id') is-invalid @enderror"
                                                            name="tapping_electronic_device_data_id" id="tapping_electronic_device_data_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Electronic Device---</option>
                                                            @foreach ($eldev as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('tapping_electronic_device_data_id', $data->tapping_electronic_device_data_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('tapping_electronic_device_data_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="tapping_intelligent_signal_data_id"
                                                               class="fs-6 fw-semibold mb-2 ">Sinyal Pintar</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('tapping_intelligent_signal_data_id') is-invalid @enderror"
                                                            name="tapping_intelligent_signal_data_id" id="tapping_intelligent_signal_data_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Sinyal Pintar---</option>
                                                            @foreach ($itlsig as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('tapping_intelligent_signal_data_id', $data->tapping_intelligent_signal_data_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('tapping_intelligent_signal_data_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="hasil_yang_dicapai"
                                                               class="fs-6 fw-semibold mb-2 ">Hasil Yang Dicapai</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('hasil_yang_dicapai') is-invalid @enderror"
                                                            name="hasil_yang_dicapai"
                                                            id="hasil_yang_dicapai">{{ old('hasil_yang_dicapai', $data->hasil_yang_dicapai) }}</textarea>
                                                        @error('hasil_yang_dicapai')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="upload_hasil_yang_dicapai"
                                                               class="fs-6 fw-semibold mb-2 ">File Dokumen</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('upload_hasil_yang_dicapai') is-invalid @enderror"
                                                                name="upload_hasil_yang_dicapai"
                                                                type="file"
                                                                id="upload_hasil_yang_dicapai"
                                                                value="{{ old('upload_hasil_yang_dicapai') }}">
                                                            @if($data->upload_hasil_yang_dicapai)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.tapping.intelligent_signal.download-dokumen', encrypt($data->upload_hasil_yang_dicapai)) }}"
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

                    $('#id_case').change(function() {
                        var case_id = $(this).val();

                        $.ajax({
                            url: '/helper-tapping-device', 
                            type: 'GET',
                            data: {case_id: case_id},
                            success: function(response) {
                                $('#tapping_electronic_device_data_id').empty();
                                $('#tapping_electronic_device_data_id').append('<option value="">---Pilih Perangkat Elektronik---</option>');

                                $.each(response, function(key, value) {
                                    $('#tapping_electronic_device_data_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#tapping_electronic_device_data_id').select2();
                            }
                        });
                    });

                    $('#id_case, #tapping_electronic_device_data_id').change(function() {
                        var device_id = $(this).val();

                        $.ajax({
                            url: '/helper-tapping-signal', 
                            type: 'GET',
                            data: {device_id: device_id},
                            success: function(response) {
                                $('#tapping_intelligent_signal_data_id').empty();
                                $('#tapping_intelligent_signal_data_id').append('<option value="">---Pilih Penyadapan Sinyal Pintar---</option>');

                                $.each(response, function(key, value) {
                                    $('#tapping_intelligent_signal_data_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#tapping_intelligent_signal_data_id').select2();
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
