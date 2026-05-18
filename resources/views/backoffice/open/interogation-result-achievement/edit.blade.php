<x-backoffice.layout.app-layout title="UBAH DATA INTEROGATION RESULT ACHIEVEMENT">
    @push('css')
    <style>
        thead {
            background: #f5f4f8;
            text-align: center;
        }
    </style>
    @endpush
    <x-backoffice.toolbar heading="UBAH DATA INTEROGATION RESULT ACHIEVEMENT" subheading="" breadcrumb="interogation-result-achievemnet-edit"
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
                                        action="{{ route('open.data.interogg-achieve.update', $data->id_interogation_result_achievement) }}"
                                        method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return checkSubmitType();">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                        <div class="form-group col-md-6">
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
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="id_interogation_record"
                                                                class="fs-6 fw-semibold mb-2">Interogation Record</label>
                                                            <select
                                                                class="form-select form-select-solid select @error('id_interogation_record') is-invalid @enderror"
                                                                name="id_interogation_record" id="id_interogation_record"
                                                                data-control="select2" data-hide-search="true">
                                                                <option value="">---Pilih Interogation Record---</option>
                                                                @foreach ($interogrecord as $row)
                                                                    <option
                                                                        value="{{ $row['id'] }}"
                                                                        @if($row['id'] === old('interogation_record_id', $data->interogation_record_id)) selected @endif>{{ $row['text'] }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('case_id')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="id_interogation_target_identification"
                                                                class="fs-6 fw-semibold mb-2">Indentifikasi Target</label>
                                                            <select
                                                                class="form-select form-select-solid select @error('id_interogation_target_identification') is-invalid @enderror"
                                                                name="id_interogation_target_identification" id="id_interogation_target_identification"
                                                                data-control="select2" data-hide-search="true">
                                                                <option value="">---Pilih Indentifikasi Target---</option>
                                                                @foreach ($interogtarget as $row)
                                                                    <option
                                                                        value="{{ $row['id_interogation_target_identification'] }}"
                                                                        @if($row['id_interogation_target_identification'] === old('id_interogation_target_identification', $data->interogation_target_identification_id)) selected @endif>{!! $row['hasil_target_identification'] !!}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('case_id')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-7">
                                                            <label for="alamat" class="fs-6 fw-semibold mb-2 required">Hasil Yang Dicapai</label>
                                                            <textarea class="form-control ckeditor2" name="hasil_yang_dicapai" id="hasil_yang_dicapai"
                                                                rows="4">{{ $data->hasil_yang_dicapai }}</textarea>
                                                            <p class="text-danger">{{ $errors->first('hasil_yang_dicapai') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="submit_type" id="submit_type" value="">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="upload_berita_acara"
                                                            class="fs-6 fw-semibold mb-2">Dokumen Hasil yang Dicapai (pdf)</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('upload_hasil_yang_dicapai') is-invalid @enderror"
                                                                name="upload_hasil_yang_dicapai" type="file"
                                                                id="upload_hasil_yang_dicapai"
                                                                value="{{ old('upload_hasil_yang_dicapai') }}">
                                                            @if($data->upload_hasil_yang_dicapai)
                                                            <a class="btn btn-dark"
                                                                href="{{ route('open.data.interrog-target-id.download-file', encrypt($data->upload_hasil_yang_dicapai)) }}"
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
                                                            name="temp_upload_hasil_yang_dicapai" type="hidden"
                                                            id="temp_upload_hasil_yang_dicapai"
                                                            value="{{ old('temp_upload_hasil_yang_dicapai', $data->upload_hasil_yang_dicapai) }}">
                                                        @error('temp_upload_hasil_yang_dicapai')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" name="submit_type" id="submit_type" value="">
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

            function checkSubmitType() {
                var submitType = document.getElementById('submit_type').value;
                if (submitType === '') {
                    // Ensure a default value is set if none was provided
                    document.getElementById('submit_type').value = 'update';
                }
                return true; // Proceed with form submission
            }
        </script>
    <script>
        document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

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

        
            $(document).ready(function() {
                $('#satker_id').change(function() {
                    $('#case_id').empty();
                    $('#id_interogation_record').empty();
                    $('#id_interogation_target_identification').empty();

                    var satker_id = $(this).val();

                    $.ajax({
                        url: '/open/helper-case', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {satker_id: satker_id},
                        success: function(response) {
                            $('#case_id').empty();
                            $('#case_id').append('<option value="">---Pilih Kasus---</option>');

                            $.each(response, function(key, value) {
                                $('#case_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#case_id').select2();
                        }
                    });
                });

                $('#case_id').change(function() {
                    var caseId = $(this).val();
                    $('#id_interogation_record').empty();
                    $.ajax({
                        url: '/open/data/interrog/' + caseId,
                        type: 'GET',
                        success: function(response) {
                            $('#id_interogation_record').empty();
                            $('#id_interogation_record').append('<option value="">---Pilih Catatan Interogasi---</option>');

                            $.each(response, function(key, value) {
                                $('#id_interogation_record').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });
                            $('#id_interogation_record').select2();
                        }
                    });
                });

                $('#id_interogation_record').on('change', function() {
                    var interog_id = $(this).val();
                    if (interog_id) {
                        $.get('/open/data/interrog/target/' + interog_id, function(data) {
                            $('#id_interogation_target_identification').empty();
                            $('#id_interogation_target_identification').append($('<option>').text('--Pilih Identifikasi Target--'));
                            // $.each(data, function(key, value) {
                            //     $('#kecamatan').append($('<option>').text(value.dis_name).attr('value', key));
                            // });
                            $.each(data, function(index, interogTarget) {
                                var text = interogTarget.text;
                                text = text.replace(/<[^>]+>/g, '');
                                $('#id_interogation_target_identification').append($('<option>').text(text).attr('value', interogTarget.id));
                            });
                        });
                    } else {
                        $('#id_interogation_target_identification').empty();
                    }
                });
                
            });
    </script>
    @endpush
</x-backoffice.layout.app-layout>