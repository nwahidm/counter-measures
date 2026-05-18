<x-backoffice.layout.app-layout title="Ubah Infiltration Result Achievement">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Infiltration Result Achievement" subheading=""
                          breadcrumb="open-research-spesific-intel-report-create"
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
                                          action="{{ route('close.infiltration.result-achievement.update', $data->id) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PATCH')
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
                                                                        @if($row['id'] === auth()->user()->satker->id_satker) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="id" value="{{ $data->id }}">
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
                                                                    @if($row['id'] === old('id_case', $data->case_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                 <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="infiltration_operasi_rahasia_id" class="fs-6 fw-semibold mb-2 ">Operasi Rahasia</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('infiltration_operasi_rahasia_id') is-invalid @enderror"
                                                            name="infiltration_operasi_rahasia_id" id="infiltration_operasi_rahasia_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Operasi Rahasia---</option>
                                                            @foreach ($operasi_rahasia as $row)
                                                                <option value="{{ $row['id'] }}" @if($row['id'] === old('infiltration_operasi_rahasia_id', $data->infiltration_operasi_rahasia_id)) selected @endif>{!! $row['text'] !!}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('infiltration_operasi_rahasia_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="infiltration_dinamika_target_id" class="fs-6 fw-semibold mb-2 ">Dinamika Target</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('infiltration_dinamika_target_id') is-invalid @enderror"
                                                            name="infiltration_dinamika_target_id" id="infiltration_dinamika_target_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Dinamika Target---</option>
                                                            @foreach ($dinamika_target as $row)
                                                                <option value="{{ $row['id'] }}" @if($row['id'] === old('infiltration_dinamika_target_id', $data->infiltration_dinamika_target_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('infiltration_dinamika_target_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                   <div class="form-group col-md-12">
                                                        <label for="hasil_yang_dicapai"
                                                               class="fs-6 fw-semibold mb-2 required">Hasil Yang Dicapai</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('hasil_yang_dicapai') is-invalid @enderror"
                                                            name="hasil_yang_dicapai" id="hasil_yang_dicapai">{{ old('hasil_yang_dicapai', $data->hasil_yang_dicapai) }}</textarea>
                                                        @error('hasil_yang_dicapai')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                               <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="upload_hasil_yang_dicapai"
                                                               class="fs-6 fw-semibold mb-2 required">Upload File</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('upload_hasil_yang_dicapai') is-invalid @enderror"
                                                                name="upload_hasil_yang_dicapai"
                                                                type="file"
                                                                id="upload_hasil_yang_dicapai"
                                                                value="{{ old('upload_hasil_yang_dicapai') }}">
                                                            @if($data->upload_hasil_yang_dicapai)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.tailing.pemahaman-perilaku.download-file', encrypt($data->upload_hasil_yang_dicapai)) }}"
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
                                                            @error('temp_pemahaman_perilaku_video_upload')
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
        <script  type="module">
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

            $('#id_case').change(function() {
                    var case_id = $(this).val();
                    $('#infiltration_operasi_rahasia_id').empty();
                    $('#infiltration_operasi_rahasia_id').append('<option value="">---Pilih Operasi Rahasia---</option>');
                    $('#infiltration_dinamika_target_id').empty();
                    $('#infiltration_dinamika_target_id').append('<option value="">---Pilih Dinamika Target---</option>');
                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected case_id
                    $.ajax({
                        url: '/close/helper-infiltration-operasi-rahasia', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {case_id: case_id},
                        success: function(response) {

                            $.each(response, function(key, value) {
                                $('#infiltration_operasi_rahasia_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#infiltration_operasi_rahasia_id').select2(); // Reinitialize select2
                        }
                    });
            });

            

            $('#infiltration_operasi_rahasia_id, #id_case').change(function() {
                    var infiltration_operasi_rahasia_idd = $(this).val();
                    $('#infiltration_dinamika_target_id').empty();
                    $('#infiltration_dinamika_target_id').append('<option value="">---Pilih Dinamika Target---</option>');

                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected case_id
                    $.ajax({
                        url: '/close/helper-infiltration-dinamika-target', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {infiltration_operasi_rahasia_idd: infiltration_operasi_rahasia_idd},
                        success: function(response) {

                            $.each(response, function(key, value) {
                                $('#infiltration_dinamika_target_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#infiltration_dinamika_target_id').select2(); // Reinitialize select2
                        }
                    });
            });
        </script>
    @endpush
</x-backoffice.layout.app-layout>
