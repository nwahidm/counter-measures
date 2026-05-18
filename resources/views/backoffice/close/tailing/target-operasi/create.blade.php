<x-backoffice.layout.app-layout title="Tambah Target Operasi Pembuntutan">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Target Operasi Pembuntutan" subheading=""
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
                                    <form id="form" action="{{ route('close.tailing.target-operasi.store') }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="card-body">

                                                <div class="row mb-7">
                                                <div class="form-group col-md-4">
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
                                                    
                                                </div>
                                                
                                               
                                                <div class="row mb-7">
                                                   <div class="form-group col-md-6">
                                                        <label for="rencana_target_operasi"
                                                               class="fs-6 fw-semibold mb-2 required">Rencana Operasi</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('rencana_target_operasi') is-invalid @enderror"
                                                            name="rencana_target_operasi" id="rencana_target_operasi">{{ old('rencana_target_operasi') }}</textarea>
                                                        @error('rencana_target_operasi')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                     <div class="form-group col-md-6">
                                                        <label for="target_operasi"
                                                               class="fs-6 fw-semibold mb-2 required">Target Operasi</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('target_operasi') is-invalid @enderror"
                                                            name="target_operasi" id="target_operasi">{{ old('target_operasi') }}</textarea>
                                                        @error('target_operasi')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                 <div class="row mb-7">
                                                   <div class="form-group col-md-12">
                                                        <label for="skenario_target_operasi"
                                                               class="fs-6 fw-semibold mb-2">Skenario Operasi</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('skenario_target_operasi') is-invalid @enderror"
                                                            name="skenario_target_operasi" id="skenario_target_operasi">{{ old('skenario_target_operasi') }}</textarea>
                                                        @error('skenario_target_operasi')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="target_operasi_video_upload"
                                                               class="fs-6 fw-semibold mb-2 required">Upload File Video
                                                            </label>
                                                        <input
                                                            class="form-control form-control-solid @error('target_operasi_video_upload') is-invalid @enderror"
                                                            name="target_operasi_video_upload"
                                                            type="file"
                                                            id="target_operasi_video_upload"
                                                            value="{{ old('target_operasi_video_upload') }}">
                                                        @error('target_operasi_video_upload')
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
                        .create(document.querySelector('#rencana_target_operasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#target_operasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#skenario_target_operasi'),{
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

                $.ajax({
                    url: '/close/helper-case', 
                    type: 'GET',
                    data: {satker_id: id_satker},
                    success: function(response) {
                        $('#id_case').empty();
                        $('#id_case').append('<option value="">---Pilih Kasus---</option>');
                        $('#tailing_pemahaman_perilaku_id').empty();
                        $('#tailing_pemahaman_perilaku_id').append('<option value="">---Pilih Nama Target---</option>');

                        $.each(response, function(key, value) {
                            $('#id_case').append('<option value="' + value.id + '">' + value.text + '</option>');
                        });

                        $('#id_case').select2();
                    }
                });
            });

            $('#id_case, #id_satker').change(function() {
                    var case_id = $(this).val();

                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected case_id
                    $.ajax({
                        url: '/close/helper-tailing-pemahaman-perilaku', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {case_id: case_id},
                        success: function(response) {
                            $('#tailing_pemahaman_perilaku_id').empty();
                            $('#tailing_pemahaman_perilaku_id').append('<option value="">---Pilih Nama Target---</option>');

                            $.each(response, function(key, value) {
                                $('#tailing_pemahaman_perilaku_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#tailing_pemahaman_perilaku_id').select2(); // Reinitialize select2
                        }
                    });
            });
        </script>
    @endpush
</x-backoffice.layout.app-layout>
