<x-backoffice.layout.app-layout title="Tambah Hasil Pencapaian Pembuntutan">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Hasil Pencapaian Pembuntutan" subheading=""
                          breadcrumb="close-tailing-pemahaman-perilaku-report-create"
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
                                    <form id="form" action="{{ route('close.tailing.result-achievement.store') }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="card-body">
                                                
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="id_satker" class="fs-6 fw-semibold mb-2 required">Satuan Kerja</label>
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
                                                        
                                                        <label for="tailing_pemahaman_perilaku_id" class="fs-6 fw-semibold mb-2">Nama Target (Pemahaman Perilaku)</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('tailing_pemahaman_perilaku_id') is-invalid @enderror"
                                                            name="tailing_pemahaman_perilaku_id" id="tailing_pemahaman_perilaku_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Nama Target---</option>
                                                            <!-- @foreach ($pemahaman_perilaku as $row)
                                                                <option value="{{ $row->id }}">{{ $row->target_name }}</option>
                                                            @endforeach -->
                                                        </select>
                                                        @error('tailing_pemahaman_perilaku_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="tailing_target_operasi_id" class="fs-6 fw-semibold mb-2">Target Operasi</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('tailing_target_operasi_id') is-invalid @enderror"
                                                            name="tailing_target_operasi_id" id="tailing_target_operasi_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Operasi---</option>
                                                             {{-- @foreach ($target_operasi as $row)
                                                                <option value="{{ $row->id }}">{!! $row->rencana_target_operasi !!}</option>
                                                            @endforeach  --}}
                                                        </select>
                                                        @error('tailing_target_operasi_id')
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
                                                            name="hasil_yang_dicapai" id="hasil_yang_dicapai">{{ old('hasil_yang_dicapai') }}</textarea>
                                                        @error('hasil_yang_dicapai')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                 <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="upload_hasil_yang_dicapai"
                                                               class="fs-6 fw-semibold mb-2">Upload File Dokumen
                                                            </label>
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
        <script type="module">
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

            $('#id_satker').change(function() {
                var id_satker = $(this).val();
                $('#id_case').empty();
                $('#id_case').append('<option value="">---Pilih Kasus---</option>');
                $('#tailing_pemahaman_perilaku_id').empty();
                $('#tailing_pemahaman_perilaku_id').append('<option value="">---Pilih Nama Target---</option>');
                $('#tailing_target_operasi_id').empty();
                $('#tailing_target_operasi_id').append('<option value="">---Pilih Operasi---</option>');

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
                    $('#tailing_pemahaman_perilaku_id').empty();
                    $('#tailing_pemahaman_perilaku_id').append('<option value="">---Pilih Nama Target---</option>');
                    $('#tailing_target_operasi_id').empty();
                    $('#tailing_target_operasi_id').append('<option value="">---Pilih Operasi---</option>');

                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected case_id
                    $.ajax({
                        url: '/close/helper-tailing-pemahaman-perilaku', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {case_id: case_id},
                        success: function(response) {

                            $.each(response, function(key, value) {
                                $('#tailing_pemahaman_perilaku_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#tailing_pemahaman_perilaku_id').select2(); // Reinitialize select2
                        }
                    });
            });

            $('#tailing_pemahaman_perilaku_id, #id_case, #id_satker').change(function() {
                    var tailing_pemahaman_perilaku_id = $(this).val();
                    $('#tailing_target_operasi_id').empty();
                    $('#tailing_target_operasi_id').append('<option value="">---Pilih Operasi---</option>');

                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected case_id
                    $.ajax({
                        url: '/close/helper-tailing-target-operasi', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {tailing_pemahaman_perilaku_id: tailing_pemahaman_perilaku_id},
                        success: function(response) {

                            $.each(response, function(key, value) {
                                $('#tailing_target_operasi_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#tailing_target_operasi_id').select2(); // Reinitialize select2
                        }
                    });
            });
        </script>
    @endpush
</x-backoffice.layout.app-layout>
