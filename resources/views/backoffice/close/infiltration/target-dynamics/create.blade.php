<x-backoffice.layout.app-layout title="Tambah Infiltration Dinamika Target">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Infiltration Dinamika Target" subheading=""
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
                                    <form id="form" action="{{ route('close.infiltration.target-dynamics.store') }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
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
                                                        @error('id_satker')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if(auth()->user()->user_roles != "superadmin")
                                                            <input type="hidden" name="id_satker" value="{{ auth()->user()?->satker?->id_satker }}">
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-4">
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

                                                    <div class="form-group col-md-4">
                                                        <label for="id_information_collection"
                                                               class="fs-6 fw-semibold mb-2">Operasi Rahasia</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_infiltration_operasi_rahasia') is-invalid @enderror"
                                                            name="id_infiltration_operasi_rahasia" id="id_infiltration_operasi_rahasia"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Operasi Rahasia---</option>
                                                            <!-- @foreach ($infiltration_operasi_rahasia as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('id_infiltration_operasi_rahasia')) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach -->
                                                        </select>
                                                        @error('id_infiltration_operasi_rahasia')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="dinamika_teramati"
                                                               class="fs-6 fw-semibold mb-2 required">Dinamika Teramati</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('dinamika_teramati') is-invalid @enderror"
                                                               name="dinamika_teramati" id="dinamika_teramati"
                                                               value="{{ old('dinamika_teramati') }}">
                                                        @error('dinamika_teramati')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="tanggal_dinamika_teramati"
                                                               class="fs-6 fw-semibold mb-2">Tanggal Dinamika Teramati</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_dinamika_teramati') is-invalid @enderror"
                                                               name="tanggal_dinamika_teramati" id="tanggal_dinamika_teramati"
                                                               value="{{ old('tanggal_dinamika_teramati') }}">
                                                        @error('tanggal_dinamika_teramati')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="deskripsi_dinamika_teramati"
                                                            class="fs-6 fw-semibold mb-2">Deskripsi Dinamika Teramati</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('deskripsi_dinamika_teramati') is-invalid @enderror"
                                                            name="deskripsi_dinamika_teramati" id="deskripsi_dinamika_teramati">{{ old('deskripsi_dinamika_teramati') }}</textarea>
                                                        @error('deskripsi_dinamika_teramati')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="dinamika_target_dokumen_upload"
                                                               class="fs-6 fw-semibold mb-2">Upload File
                                                            Dokument</label>
                                                        <input
                                                            class="form-control form-control-solid @error('dinamika_target_dokumen_upload') is-invalid @enderror"
                                                            name="dinamika_target_dokumen_upload"
                                                            type="file"
                                                            id="dinamika_target_dokumen_upload"
                                                            value="{{ old('dinamika_target_dokumen_upload') }}">
                                                        @error('dinamika_target_dokumen_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="dinamika_target_video_upload"
                                                               class="fs-6 fw-semibold mb-2 required">Upload File
                                                            Video</label>
                                                        <input
                                                            class="form-control form-control-solid @error('dinamika_target_video_upload') is-invalid @enderror"
                                                            name="dinamika_target_video_upload"
                                                            type="file"
                                                            id="dinamika_target_video_upload"
                                                            value="{{ old('dinamika_target_video_upload') }}">
                                                        @error('dinamika_target_video_upload')
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
        <script type="module">
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

                    ClassicEditor
                        .create(document.querySelector('#deskripsi_dinamika_teramati'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            };

            $('#id_satker').change(function() {
                var id_satker = $(this).val();
                $('#id_case').empty();
                $('#id_case').append('<option value="">---Pilih Kasus---</option>');
                $('#id_infiltration_operasi_rahasia').empty();
                $('#id_infiltration_operasi_rahasia').append('<option value="">---Pilih Operasi Rahasia---</option>');

                $.ajax({
                    url: '/close/helper-case', 
                    type: 'GET',
                    data: {satker_id: id_satker},
                    success: function(response) {

                        $.each(response, function(key, value) {
                            $('#id_case').append('<option value="' + value.id + '">' + value.text + '</option>');
                        });

                        $('#id_case').select2();
                    }
                });
            });

            $('#id_case, #id_satker').change(function() {
                    var case_id = $(this).val();
                    $('#id_infiltration_operasi_rahasia').empty();
                    $('#id_infiltration_operasi_rahasia').append('<option value="">---Pilih Operasi Rahasia---</option>');
                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected case_id
                    $.ajax({
                        url: '/close/helper-infiltration-operasi-rahasia', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {case_id: case_id},
                        success: function(response) {

                            $.each(response, function(key, value) {
                                $('#id_infiltration_operasi_rahasia').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#id_infiltration_operasi_rahasia').select2(); // Reinitialize select2
                        }
                    });
            });

        </script>
    @endpush
</x-backoffice.layout.app-layout>
