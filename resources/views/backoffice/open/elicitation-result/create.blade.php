<x-backoffice.layout.app-layout title="TAMBAH ELICITATION RESULT">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="TAMBAH ELICITATION RESULT" subheading="" breadcrumb="elicitation-result-create"
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
                                    <form id="form" action="{{ route('open.data.elicit-result.store') }}" method="post"
                                          enctype="multipart/form-data" autocomplete="off" onsubmit="return checkSubmitType();">
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
                                                               class="fs-6 fw-semibold mb-2">Hasil Wawancara Elisitasi</label>
                                                        <select
                                                            class="form-select select @error('id_elicitation_interview_result') is-invalid @enderror"
                                                            name="id_elicitation_interview_result" id="id_elicitation_interview_result"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Hasil Wawancara Elisitasi---</option>
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
                                                        <label for="id_case"
                                                               class="fs-6 fw-semibold mb-2">Saran Tindak Elisitasi</label>
                                                        <select
                                                            class="form-select select @error('id_elicitation_advice_and_followup') is-invalid @enderror"
                                                            name="id_elicitation_advice_and_followup" id="id_elicitation_advice_and_followup"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Saran Tindak Elisitasi---</option>
                                                            {{-- @foreach ($elintadfoll as $row)
                                                                <option
                                                                    value="{{ $row['id_elicitation_saran_dan_tindak_lanjut'] }}"
                                                                    @if($row['id_elicitation_saran_dan_tindak_lanjut'] === old('id_elicitation_saran_dan_tindak_lanjut')) selected @endif>{{ $row['saran_dan_tindak_lanjut_date'] }}</option>
                                                            @endforeach --}}
                                                        </select>
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="pendahuluan" class="fs-6 fw-semibold mb-2 required">Pendahuluan</label>
                                                            <textarea class="form-control form-control-solid @error('pendahuluan') is-invalid @enderror"
                                                            name="pendahuluan" id="pendahuluan">{{ old('pendahuluan') }}</textarea>
                                                            <p class="text-danger">{{ $errors->first('pendahuluan') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="pelaksanaan_kegiatan" class="fs-6 fw-semibold mb-2 required">Pelaksanaan Kegiatan</label>                                                            
                                                            <textarea class="form-control form-control-solid @error('pelaksanaan_kegiatan') is-invalid @enderror"
                                                            name="pelaksanaan_kegiatan" id="pelaksanaan_kegiatan">{{ old('pelaksanaan_kegiatan') }}</textarea>
                                                            <p class="text-danger">{{ $errors->first('pelaksanaan_kegiatan') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="kendala" class="fs-6 fw-semibold mb-2 required">Kendala / Hambatan</label>                                                            
                                                            <textarea class="form-control form-control-solid @error('kendala') is-invalid @enderror"
                                                            name="kendala" id="kendala">{{ old('kendala') }}</textarea>
                                                            <p class="text-danger">{{ $errors->first('kendala') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="analisa" class="fs-6 fw-semibold mb-2 required">Analisa</label>                                                            
                                                            <textarea class="form-control form-control-solid @error('analisa') is-invalid @enderror"
                                                            name="analisa" id="analisa">{{ old('analisa') }}</textarea>
                                                            <p class="text-danger">{{ $errors->first('analisa') }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="kesimpulan" class="fs-6 fw-semibold mb-2 required">Kesimpulan</label>                                                            
                                                            <textarea class="form-control form-control-solid @error('kesimpulan') is-invalid @enderror"
                                                            name="kesimpulan" id="kesimpulan">{{ old('kesimpulan') }}</textarea>
                                                            <p class="text-danger">{{ $errors->first('kesimpulan') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="saran" class="fs-6 fw-semibold mb-2 required">Saran</label>                                                            
                                                            <textarea class="form-control form-control-solid @error('saran') is-invalid @enderror"
                                                            name="saran" id="saran">{{ old('saran') }}</textarea>
                                                            <p class="text-danger">{{ $errors->first('saran') }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-7">
                                                            <label for="petunjuk_pimpinan" class="fs-6 fw-semibold mb-2 required">Petunjuk Pimpinan</label>                                                            
                                                            <textarea class="form-control form-control-solid @error('petunjuk_pimpinan') is-invalid @enderror"
                                                            name="petunjuk_pimpinan" id="petunjuk_pimpinan">{{ old('petunjuk_pimpinan') }}</textarea>
                                                            <p class="text-danger">{{ $errors->first('petunjuk_pimpinan') }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-7">
                                                            <label for="upload_hasil_yang_dicapai" class="fs-6 fw-semibold mb-2 required">Upload Hasil yang dicapai</label>
                                                            <input class="form-control form-control-solid" 
                                                                name="upload_hasil_yang_dicapai" type="file" id="upload_hasil_yang_dicapai"
                                                                value="{{ old('upload_hasil_yang_dicapai') }}" accept="application/pdf">
                                                            <p class="text-danger">{{ $errors->first('upload_hasil_yang_dicapai') }}</p>
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
                    
                    ClassicEditor
                        .create(document.querySelector('#pendahuluan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    ClassicEditor
                        .create(document.querySelector('#pelaksanaan_kegiatan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#kendala'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#analisa'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    ClassicEditor
                        .create(document.querySelector('#kesimpulan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#saran'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });

                     ClassicEditor
                        .create(document.querySelector('#petunjuk_pimpinan'),{
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
        <script>
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

                    ClassicEditor
                        .create(document.querySelector('#pendahuluan'),{
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
                            $('#id_elicitation_interview_result').append($('<option>').text('---Pilih Hasil Wawancara Elisitasi---'));
                            $.each(data, function(index, elicitrecord) {
                                $('#id_elicitation_interview_result').append($('<option>').text(elicitrecord.interviewer_name).attr('value', elicitrecord.id_elicitation_interview_result));
                            });
                        });
                    } else {
                        $('#id_elicitation_interview_result').empty();
                    }
                });

                $('#id_elicitation_interview_result').on('change', function() {
                    var elicit_id = $(this).val();
                    if (elicit_id) {
                        $.get('/open/data/elicit/adfl/' + elicit_id, function(data) {
                            $('#id_elicitation_advice_and_followup').empty();
                            $('#id_elicitation_advice_and_followup').append($('<option>').text('---Pilih Saran Tindak Elisitasi---'));
                            // $.each(data, function(key, value) {
                            //     $('#kecamatan').append($('<option>').text(value.dis_name).attr('value', key));
                            // });
                            $.each(data, function(index, elicitAdFl) {
                                $('#id_elicitation_advice_and_followup').append($('<option>').text(elicitAdFl.saran_dan_tindak_lanjut_date).attr('value', elicitAdFl.id_elicitation_saran_dan_tindak_lanjut));
                            });
                        });
                    } else {
                        $('#id_elicitation_advice_and_followup').empty();
                    }
                });
                
            });
        </script>
    @endpush
</x-backoffice.layout.app-layout>
