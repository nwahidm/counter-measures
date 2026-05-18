<x-backoffice.layout.app-layout title="Tambah Penyadapan Hasil Pencapaian">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Penyadapan Hasil Pencapaian" subheading=""
                          breadcrumb="close-tapping-result_achievement-create"
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
                                    <form id="form" action="{{ route('close.tapping.result_achievement.store') }}"
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
                                                        <label for="tapping_electronic_device_data_id"
                                                               class="fs-6 fw-semibold mb-2 ">Perangkat Elektronik</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('tapping_electronic_device_data_id') is-invalid @enderror"
                                                            name="tapping_electronic_device_data_id" id="tapping_electronic_device_data_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Perangkat Elektronik---</option>
                                                            {{-- @foreach ($eldev as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('tapping_electronic_device_data_id')) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach --}}
                                                        </select>
                                                        @error('tapping_electronic_device_data_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="tapping_intelligent_signal_data_id"
                                                               class="fs-6 fw-semibold mb-2 ">Penyadapan Sinyal Pintar</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('tapping_intelligent_signal_data_id') is-invalid @enderror"
                                                            name="tapping_intelligent_signal_data_id" id="tapping_intelligent_signal_data_id"
                                                            data-control="select2" data-hide-search="false">
                                                            <option value="">---Pilih Penyadapan Sinyal Pintar---</option>
                                                            @foreach ($itlsig as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('tapping_intelligent_signal_data_id')) selected @endif>{{ $row['text'] }}</option>
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
                                                               class="fs-6 fw-semibold mb-2 required">Hasil Yang Dicapai</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('hasil_yang_dicapai') is-invalid @enderror"
                                                            name="hasil_yang_dicapai"
                                                            id="hasil_yang_dicapai">{{ old('hasil_yang_dicapai') }}</textarea>
                                                        @error('hasil_yang_dicapai')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="upload_hasil_yang_dicapai"
                                                               class="fs-6 fw-semibold mb-2">File Dokumen</label>
                                                        <input
                                                            class="form-control form-control-solid @error('upload_hasil_yang_dicapai') is-invalid @enderror"
                                                            name="upload_hasil_yang_dicapai"
                                                            type="file"
                                                            id="upload_hasil_yang_dicapai"
                                                            value="{{ old('upload_hasil_yang_dicapai') }}">
                                                        @error('upload_hasil_yang_dicapai')
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
