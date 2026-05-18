<x-backoffice.layout.app-layout title="Tambah Wawancara Saran Tindak Lanjut">
    @push('css')
    <style>
        thead {
            background: #f5f4f8;
            text-align: center;
        }
    </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Wawancara Saran Tindak Lanjut" subheading=""
        breadcrumb="open-interview-saran_tl-create" icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible show fade w-100" role="alert">
                <strong> {{ $error }} </strong>
            </div>
            @endforeach
            @endif
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
                                    <form id="form" action="{{ route('open.interview.saran_tl.store') }}" method="post"
                                        enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="row mb-7">
                                                <div class="form-group col-md-12">
                                                    <label for="id_satker"
                                                           class="fs-6 fw-semibold mb-2 required">Satuan Kerja</label>
                                                    <select
                                                        class="form-select form-select-solid select @error('id_satker') is-invalid @enderror"
                                                        name="id_satker" id="id_satker" required
                                                        data-control="select2" data-hide-search="false">
                                                        <option value="">---Pilih Satuan Kerja---</option>
                                                        @foreach ($satker as $row)
                                                            <option
                                                                value="{{ $row['id'] }}"
                                                                @if($row['id'] === old('id', $data->id_satker)) selected @endif>{{ $row['text'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_satker')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-7">
                                                <div class="form-group col-md-6">
                                                    <label for="id_case"
                                                        class="fs-6 fw-semibold mb-2 required">Kasus</label>
                                                    <select
                                                        class="form-select form-select-solid select @error('id_case') is-invalid @enderror"
                                                        name="id_case" id="id_case" data-control="select2"
                                                        data-hide-search="true">
                                                        <option value="">---Pilih Kasus---</option>
                                                        @foreach ($case as $row)
                                                        <option value="{{ $row['id'] }}"
                                                            @if($row['id']===old('id_case', $data->case_id)) selected @endif>{{
                                                            $row['text'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_case')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="interview_scheduler_id"
                                                        class="fs-6 fw-semibold mb-2">Wawancara</label>
                                                    <select
                                                        class="form-select form-select-solid select @error('interview_schedule_id') is-invalid @enderror"
                                                        name="interview_schedule_id" id="interview_schedule_id"
                                                        data-control="select2" data-hide-search="true">
                                                        <option value="">---Pilih Wawancara---</option>
                                                        @foreach ($jadwal as $row)
                                                            <option value="{{ $row->id_interview_scheduler }}" @if($row->id_interview_scheduler == $data->interview_scheduler_id) selected @endif>Wawancara {{ $row->interviewer_name }} dengan {{ $row->source_person_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('interview_schedule_id')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-7">
                                                <div class="form-group col-md-12">
                                                    <label for="interview_result_id"
                                                        class="fs-6 fw-semibold mb-2">Hasil Wawancara</label>
                                                    <select
                                                        class="form-select form-select-solid select @error('interview_result_id') is-invalid @enderror"
                                                        name="interview_result_id" id="interview_result_id"
                                                        data-control="select2" data-hide-search="true">
                                                        <option value="" selected>---Pilih Hasil Wawancara---</option>
                                                        @foreach ($hasil as $row)
                                                        <option value="{{ $row->id_interview_result }}" @if($row->id_interview_result == $data->id_interview_result	) selected @endif>{!!
                                                            $row->keterangan !!}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('interview_result_id')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-7">
                                                <div class="form-group col-md-6">
                                                    <label for="saran_dan_tindak_lanjut_date"
                                                        class="fs-6 fw-semibold mb-2 required">Tgl. Saran Tindak
                                                        Lanjut</label>
                                                    <input type="date"
                                                        class="form-control form-control-solid @error('saran_dan_tindak_lanjut_date') is-invalid @enderror"
                                                        name="saran_dan_tindak_lanjut_date"
                                                        id="saran_dan_tindak_lanjut_date"
                                                        value="{{ old('saran_dan_tindak_lanjut_date') }}">
                                                    @error('saran_dan_tindak_lanjut_date')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="saran_dan_tindak_lanjut"
                                                        class="fs-6 fw-semibold mb-2 required">Saran Tindak
                                                        Lanjut</label>
                                                    <textarea
                                                        class="form-control form-control-solid @error('saran_dan_tindak_lanjut') is-invalid @enderror"
                                                        name="saran_dan_tindak_lanjut"
                                                        id="saran_dan_tindak_lanjut">{{ old('saran_dan_tindak_lanjut') }}</textarea>
                                                    @error('saran_dan_tindak_lanjut')
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
                                                <div class="col-md-2">
                                                    <button
                                                        class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                        onclick="setSubmitType('save_and_finish')"
                                                        type="submit">Simpan dan Selesai
                                                    </button>                                                       
                                                </div>
                                                {{-- <div class="col-md-3">
                                                    <button
                                                        class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                        type="submit">Simpan
                                                    </button>
                                                </div> --}}
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

                    $('#id_satker').change(function() {
                        var id_satker = $(this).val();

                        $.ajax({
                            url: '/helper-open-case', 
                            type: 'GET',
                            data: {id_satker: id_satker},
                            success: function(response) {
                                $('#id_case').empty();
                                $('#id_case').append('<option value="">---Pilih Kasus---</option>');

                                $.each(response, function(key, value) {
                                    $('#id_case').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#id_case').select2();
                            }
                        });
                    });

                    $('#id_case, #id_satker').change(function() {
                        var case_id = $(this).val();

                        $.ajax({
                            url: '/helper-interview-jadwal', 
                            type: 'GET',
                            data: {case_id: case_id},
                            success: function(response) {
                                $('#interview_schedule_id').empty();
                                $('#interview_schedule_id').append('<option value="">---Pilih Wawancara---</option>');

                                $.each(response, function(key, value) {
                                    $('#interview_schedule_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#interview_schedule_id').select2();
                            }
                        });
                    });

                    $('#id_case, #id_satker, #interview_schedule_id').change(function() {
                        var id_jadwal = $(this).val();

                        $.ajax({
                            url: '/helper-interview-hasil', 
                            type: 'GET',
                            data: {id_jadwal: id_jadwal},
                            success: function(response) {
                                console.log(response)
                                $('#interview_result_id').empty();
                                $('#interview_result_id').append('<option value="">---Pilih Hasil Wawancara---</option>');

                                $.each(response, function(key, value) {
                                    $('#interview_result_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#interview_result_id').select2();
                            }
                        });
                    });

                    ClassicEditor
                    .create(document.querySelector('#saran_dan_tindak_lanjut'),{
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