<x-backoffice.layout.app-layout title="Tambah Indentifikasi Targer">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Indentifikasi Target" subheading="" breadcrumb="interogation-target-identification-create"
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
                                    <form id="form" action="{{ route('open.data.interogg-target-id.store') }}" method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold d-none">DETAIL KASUS:</label>
                                                <hr class="d-none">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="satker_id"
                                                               class="fs-6 fw-semibold mb-2 required">Satuan Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('satker_id') is-invalid @enderror"
                                                            name="satker_id" id="satker_id"
                                                            data-control="select2" data-hide-search="false"
                                                            @if(auth()->user()->user_roles != "superadmin") disabled @endif>
                                                            <option value="">---Pilih Satuan Kerja---</option>
                                                            @foreach ($satker as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] == auth()->user()?->satker?->id_satker) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('satker_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if(auth()->user()->user_roles != "superadmin")
                                                            <input type="hidden" name="satker_id" value="{{ auth()->user()?->satker?->id_satker }}">
                                                        @endif
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
                                                                <option value="{{ $row['id'] }}" @if($row['id'] === old('case_id')) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('case_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="id_interogation_record" class="fs-6 fw-semibold mb-2">Catatan Interogasi</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_interogation_record') is-invalid @enderror"
                                                            name="id_interogation_record" id="id_interogation_record" 
                                                            data-control="select2" data-hide-search="false">
                                                            <option value="">---Pilih Catatan ---</option>
                                                            {{-- @foreach ($interogrecord as $row)
                                                                <option value="{{ $row->id_interogation_record }}">
                                                                    {{ $row->target_name }}</option>
                                                            @endforeach --}}
                                                        </select>
                                                        @error('id_interogation_record')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-7">
                                                            <label for="hasil_target_identification" class="fs-6 fw-semibold mb-2 required">Hasil Identifikasi Target</label>
                                                            <textarea class="form-control ckeditor1" name="hasil_target_identification" id="hasil_target_identification"
                                                                rows="4">{{ old('hasil_target_identification') }}</textarea>
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
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-7">
                                                            <label for="upload_berita_acara" class="fs-6 fw-semibold mb-2 required">Dokumen Hasil Identifikasi Target (pdf)</label>
                                                            <input class="form-control form-control-solid" required="required"
                                                                name="upload_berita_acara" type="file" id="upload_berita_acara"
                                                                value="{{ old('upload_berita_acara') }}" accept="application/pdf">
                                                            <p class="text-danger">{{ $errors->first('upload_berita_acara') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="upload_video_identifikasi_target"
                                                               class="fs-6 fw-semibold mb-2 required">Video Identifikasi Target</label>
                                                        <input
                                                            class="form-control form-control-solid @error('upload_video_identifikasi_target') is-invalid @enderror"
                                                            name="upload_video_identifikasi_target"
                                                            type="file"
                                                            id="upload_video_identifikasi_target"
                                                            accept="video/*"
                                                            value="{{ old('upload_video_identifikasi_target') }}">
                                                        @error('upload_video_identifikasi_target')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" name="submit_type" id="submit_type" value="">
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
                        console.log("Data received:", response.data);
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

                
            }
        };
    </script>
    @endpush
</x-backoffice.layout.app-layout>
