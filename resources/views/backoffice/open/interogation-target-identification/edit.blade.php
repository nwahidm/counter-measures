<x-backoffice.layout.app-layout title="UBAH DATA Identifikasi Target">
    @push('css')
    <style>
        thead {
            background: #f5f4f8;
            text-align: center;
        }
    </style>
    @endpush
    <x-backoffice.toolbar heading="UBAH DATA Identifikasi Target" subheading="" breadcrumb="interogation-target-id-edit"
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
                                        action="{{ route('open.data.interogg-target-id.update', $data->id_interogation_target_identification) }}"
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
                                                    <div class="form-group col-md-12">
                                                        <label for="id_elicitation_interview_result"
                                                                class="fs-6 fw-semibold mb-2 required">Interogation Record</label>
                                                        <select
                                                            class="form-select select @error('id_interogation_record') is-invalid @enderror"
                                                            name="id_interogation_record" id="id_interogation_record"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Interogation Record berdasarkan Nama---</option>
                                                            @foreach ($interogrecord as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('interogation_record_id', $data->interogation_record_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_interogation_record')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-7">
                                                            <label for="alamat" class="fs-6 fw-semibold mb-2 required">Hasil Target Identification</label>
                                                            <textarea class="form-control ckeditor2" name="hasil_target_identification" id="hasil_target_identification"
                                                                rows="4">{{ $data->hasil_target_identification }}</textarea>
                                                            <p class="text-danger">{{ $errors->first('hasil_target_identification') }}</p>
                                                            <button
                                                                class="btn btn-warning waves-effect waves-classic waves-effect waves-classic"
                                                                type="button"
                                                                onclick="setHasilIdentifikasiTarget()"
                                                                >Auto Generate Hasil
                                                            </button>   
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="upload_berita_acara"
                                                            class="fs-6 fw-semibold mb-2 required">Dokumen Hasil Identifikasi Target (pdf)</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('upload_berita_acara') is-invalid @enderror"
                                                                name="upload_berita_acara" type="file"
                                                                id="upload_berita_acara"
                                                                value="{{ old('upload_berita_acara') }}">
                                                            @if($data->hasil_target_identification_path)
                                                                <a class="btn btn-dark"
                                                                    href="{{ route('open.data.interrog-target-id.download-file', encrypt($data->hasil_target_identification_path)) }}"
                                                                    id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('upload_berita_acara')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->hasil_target_identification_path)
                                                        <input
                                                            class="form-control form-control-solid @error('temp_upload_berita_acara') is-invalid @enderror"
                                                            name="temp_upload_berita_acara" type="hidden"
                                                            id="temp_upload_berita_acara"
                                                            value="{{ old('temp_upload_berita_acara', $data->hasil_target_identification_path) }}">
                                                        @error('temp_upload_berita_acara')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="upload_video_identifikasi_target"
                                                            class="fs-6 fw-semibold mb-2 required">Video
                                                            Identifikasi Target</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('upload_video_identifikasi_target') is-invalid @enderror"
                                                                name="upload_video_identifikasi_target" type="file"
                                                                id="upload_video_identifikasi_target"
                                                                value="{{ old('upload_video_identifikasi_target') }}">
                                                            @if($data->hasil_video_target_identification_path)
                                                            <a class="btn btn-dark"
                                                                href="{{ route('open.research.warrant.download-file', encrypt($data->hasil_video_target_identification_path)) }}"
                                                                id="button-addon-file_surat_referensi">
                                                                <span class="fa fa-file-download"></span> Unduh
                                                            </a>
                                                            @endif
                                                        </div>
                                                        @error('upload_video_identifikasi_target')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->upload_video_identifikasi_target)
                                                        <input
                                                            class="form-control form-control-solid @error('temp_upload_video_identifikasi_target') is-invalid @enderror"
                                                            name="temp_upload_video_identifikasi_target" type="hidden"
                                                            id="temp_upload_video_identifikasi_target"
                                                            value="{{ old('temp_upload_video_identifikasi_target', $data->upload_video_identifikasi_target) }}">
                                                        @error('temp_upload_video_identifikasi_target')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @endif
                                                    </div>
                                                </div>
                                                <input type="hidden" name="submit_type" id="submit_type" value="">
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <button class="btn btn-dark waves-effect waves-classic"
                                                                onclick="setSubmitType('update')"
                                                                type="submit">Update
                                                        </button>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn btn-dark waves-effect waves-classic"
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
        function setHasilIdentifikasiTarget(){
            console.log("Button was clicked!");
            var caseIdValue = $('#case_id').val();
            var interogationRecordIdValue = $('#id_interogation_record').val();

            $.ajax({
                url: '/open/data/interogg-achieve/get-hasil-identifikasi-target',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    case_id: caseIdValue, // Nilai dari dropdown case_id
                    interogation_record_id: interogationRecordIdValue 
                },
                success: function(response) {
                    if (response.status === 'success') {
                        console.log("Data received:", response.data.response_llm);
                        if(response.data.response_llm.identifikasi_target){
                            $('#hasil_target_identification').val(response.data.response_llm.identifikasi_target);
                        }else{
                            responseee = response.data.response_llm.replace("{identifikasi_target:\"", "");
                            responseee = responseee.replace("\"}", "");
                            $('#hasil_target_identification').val(responseee);
                        }
        
                        

                        

                        console.log("Data received:", response.data.response_llm.identifikasi_target);
                        // Lakukan sesuatu dengan data yang diterima
                    } else {
                        console.log("Error:", response.message);
                        // Tangani error
                    }
                }
            });
        }

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

                    $('#satker_id').change(function() {
                        $('#case_id').empty();
                        $('#id_interogation_record').empty();

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

                    // ClassicEditor
                    //     .create(document.querySelector('#hasil_target_identification'))
                    //     .catch(error => {
                    //         console.error(error);
                    //     });
                }
            };
    </script>
    @endpush
</x-backoffice.layout.app-layout>