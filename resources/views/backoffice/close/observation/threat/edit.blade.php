<x-backoffice.layout.app-layout title="Ubah Analisis AGHT">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Analisis AGHT" subheading="" breadcrumb="edit-observation-threat"
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
                                    <form id="form" action="{{ route('close.observation.threat.update', $data->id) }}"
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
                                                                       @if($row['id'] == old('case_id', $data->case_id)) selected @endif>{{ $row['text'] }}</option>
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
                                                        <label for="information_collection_id"
                                                               class="fs-6 fw-semibold mb-2">Sumber Informasi</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('information_collection_id') is-invalid @enderror"
                                                            name="information_collection_id" id="information_collection_id"
                                                            data-control="select2" data-hide-search="false">
                                                            <option value="">---Pilih Informasi---</option>
                                                            @foreach ($collect_info as $row)
                                                                   <option
                                                                       value="{{ $row['id'] }}"
                                                                       @if($row['id'] === old('information_collection_id', $data->information_collection_id)) selected @endif>{{ $row['text'] }}</option>
                                                               @endforeach
                                                        </select>
                                                        @error('information_collection_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="aght_type"
                                                               class="fs-6 fw-semibold mb-2 required">Jenis AGHT</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('aght_type') is-invalid @enderror"
                                                            name="aght_type" id="aght_type"
                                                            data-control="select2" data-hide-search="false">
                                                            <option value="">---Pilih AGHT---</option>
                                                            <option value="Ancaman" @if(old('jenis_aght', $data->aght_type) === 'Ancaman') selected @endif>Ancaman</option>
                                                            <option value="Gangguan" @if(old('jenis_aght', $data->aght_type) === 'Gangguan') selected @endif>Gangguan</option>
                                                            <option value="Hambatan" @if(old('jenis_aght', $data->aght_type) === 'Hambatan') selected @endif>Hambatan</option>
                                                            <option value="Tantangan" @if(old('jenis_aght', $data->aght_type) === 'Tantangan') selected @endif>Tantangan</option>
                                                        </select>
                                                        @error('aght_type')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="aght_place"
                                                               class="fs-6 fw-semibold mb-2">Tempat Terjadi</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('aght_place') is-invalid @enderror"
                                                               name="aght_place" id="aght_place"
                                                               value="{{ old('aght_place', $data->aght_place) }}">
                                                        @error('aght_place')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="aght_time" class="fs-6 fw-semibold mb-2">Waktu Terjadi</label>
                                                        <input type="datetime-local" class="form-control form-control-solid @error('aght_time') is-invalid @enderror" name="aght_time" id="aght_time" value="{{ old('aght_time',  $data->aght_time) }}">
                                                        @error('aght_time')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="perihal"
                                                               class="fs-6 fw-semibold mb-2">Perihal AGHT</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('perihal') is-invalid @enderror"
                                                               name="perihal" id="perihal"
                                                               value="{{ old('perihal', $data->perihal) }}">
                                                        @error('perihal')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="aght_path"
                                                               class="fs-6 fw-semibold mb-2">Upload File AGHT</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('aght_path') is-invalid @enderror"
                                                                name="aght_path"
                                                                type="file"
                                                                id="aght_path"
                                                                value="{{ old('aght_path') }}">
                                                            @if($data->aght_path)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.observation.threat.download-file', encrypt($data->aght_path)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('aght_path')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->aght_path)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_aght_path') is-invalid @enderror"
                                                                name="temp_aght_path"
                                                                type="hidden"
                                                                id="temp_aght_path"
                                                                value="{{ old('temp_aght_path', $data->aght_path) }}">
                                                            @error('temp_aght_path')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="keterangan"
                                                               class="fs-6 fw-semibold mb-2 required">Keterangan Tambahan</label>
                                                        <textarea class="form-control form-control-solid @error('keterangan') is-invalid @enderror" name="keterangan" id="keterangan"
                                                        rows="4">{!! old('keterangan', $data->keterangan) !!}</textarea>
                                                        @error('keterangan')
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
    <script>
        document.onreadystatechange = function () {
            if (document.readyState === 'complete') {
                $('.select').select2();

                // ajax get case list
                $('#satker_id').change(function() {
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
                $('#case_id, #satker_id').change(function() {
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

                // ajax get collect info
                $('#surat_perintah_id, #case_id, #satker_id').change(function() {
                        var surat_perintah_id = $(this).val();

                        $.ajax({
                            url: '/close/helper-collect-info', 
                            type: 'GET',
                            data: {surat_perintah_id: surat_perintah_id},
                            success: function(response) {
                                $('#information_collection_id').empty();
                                $('#information_collection_id').append('<option value="">---Pilih Informasi---</option>');

                                $.each(response, function(key, value) {
                                    $('#information_collection_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#information_collection_id').select2();
                            }
                        });
                    });

                ClassicEditor
                    .create(document.querySelector('#keterangan'),{
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
