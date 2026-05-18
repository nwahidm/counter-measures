<x-backoffice.layout.app-layout title="TAMBAH SARAN DAN TINDAK LANJUT">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="TAMBAH SARAN DAN TINDAK LANJUT" subheading="" breadcrumb="elicitation-advice-followup-create"
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
                                    <form id="form" action="{{ route('open.data.elicit-adfoll.store') }}" method="post"
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
                                                        <label for="id_case"
                                                               class="fs-6 fw-semibold mb-2">Elicitation Interview Result</label>
                                                        <select
                                                            class="form-select select @error('id_elicitation_interview_result') is-invalid @enderror"
                                                            name="id_elicitation_interview_result" id="id_elicitation_interview_result"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Elicitation Interview Result---</option>
                                                            {{-- @foreach ($elinterview as $row)
                                                                <option
                                                                    value="{{ $row['id_elicitation_interview_result'] }}"
                                                                    @if($row['id_elicitation_interview_result'] === old('id_elicitation_interview_result')) selected @endif>{{ $row['interviewer_name'] }}</option>
                                                            @endforeach --}}
                                                        </select>
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="tanggal_tinjut" class="fs-6 fw-semibold mb-2 required">Tanggal Tindak Lanjut</label>
                                                        <input class="form-control form-control-solid" required="required"
                                                            name="tanggal_tinjut" type="date" id="tanggal_tinjut"
                                                            value="{{ old('tanggal_tinjut') }}">
                                                        <p class="text-danger">{{ $errors->first('tanggal_tinjut') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-7">
                                                            <label for="saran_tinjut" class="fs-6 fw-semibold mb-2 required">Saran Tindak Lanjut</label>
                                                            <textarea class="form-control ckeditor2" name="saran_tinjut" id ="saran_tinjut"
                                                                rows="4">{{ old('saran_tinjut') }}</textarea>
                                                            <p class="text-danger">{{ $errors->first('saran_tinjut') }}</p>
                                                        </div>
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
                                                    <div class="col-md-2">
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

            function checkSubmitType() {
                var submitType = document.getElementById('submit_type').value;
                if (submitType === '') {
                    // Ensure a default value is set if none was provided
                    document.getElementById('submit_type').value = 'update';
                }
                return true; // Proceed with form submission
            }
        </script>
        <script type="module">
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

                    ClassicEditor
                        .create(document.querySelector('#saran_tinjut'),{
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
                $('#id_case').on('change', function() {
                    var caseId = $(this).val();
                    if (caseId) {
                        $.get('/open/data/elicit/' + caseId, function(data) {
                            $('#id_elicitation_interview_result').empty();
                            $('#id_elicitation_interview_result').append($('<option>').text('---Pilih Elicitation Interview Result---'));
                            $.each(data, function(index, elicitrecord) {
                                $('#id_elicitation_interview_result').append($('<option>').text(elicitrecord.interviewer_name).attr('value', elicitrecord.id_elicitation_interview_result));
                            });
                        });
                    } else {
                        $('#id_elicitation_interview_result').empty();
                    }
                });

                $('#id_interogation_record').on('change', function() {
                    var interog_id = $(this).val();
                    if (interog_id) {
                        $.get('/open/data/interrog/target/' + interog_id, function(data) {
                            $('#id_interogation_target_identification').empty();
                            $('#id_interogation_target_identification').append($('<option>').text('--Pilih Interogasi Identifikasi Sasaran--'));
                            // $.each(data, function(key, value) {
                            //     $('#kecamatan').append($('<option>').text(value.dis_name).attr('value', key));
                            // });
                            $.each(data, function(index, interogTarget) {
                                $('#id_interogation_target_identification').append($('<option>').text(interogTarget.hasil_target_identification).attr('value', interogTarget.id_interogation_target_identification));
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
