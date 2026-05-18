<x-backoffice.layout.app-layout title="Tambah Penelitian Potensi Ancaman, Gangguan, Hambatan, dan Tantangan">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Penelitian Potensi Ancaman, Gangguan, Hambatan, dan Tantangan" subheading=""
                          breadcrumb="open-research-tibc-create"
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
                                    <form id="form" action="{{ route('open.research.tibc.store') }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="id_satker"
                                                               class="fs-6 fw-semibold mb-2 required">Satuan Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_satker') is-invalid @enderror"
                                                            name="id_satker" id="id_satker"
                                                            data-control="select2" data-hide-search="false"
                                                            @if(auth()->user()->user_roles != "superadmin") disabled @endif>
                                                            <option value="">---Pilih Satuan Kerja---</option>
                                                            @foreach ($satker as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === auth()->user()?->satker?->id_satker) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_satker')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if(auth()->user()->user_roles != "superadmin")
                                                            <input type="hidden" name="id_satker" value="{{ auth()->user()?->satker?->id_satker }}">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
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

                                                    <div class="form-group col-md-6">
                                                        <label for="id_sprint"
                                                               class="fs-6 fw-semibold mb-2">No. Surat
                                                            Perintah</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_sprint') is-invalid @enderror"
                                                            name="id_sprint" id="id_sprint"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Surat Perintah---</option>
                                                        </select>
                                                        @error('id_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="id_lapinsus"
                                                               class="fs-6 fw-semibold mb-2">No. Lapinsus</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_lapinsus') is-invalid @enderror"
                                                            name="id_lapinsus" id="id_lapinsus"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Lapinsus---</option>
                                                        </select>
                                                        @error('id_lapinsus')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="id_saran_tl"
                                                               class="fs-6 fw-semibold mb-2">Saran Tindak Lanjut</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_saran_tl') is-invalid @enderror"
                                                            name="id_saran_tl" id="id_saran_tl"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Saran Tindak Lanjut---</option>
                                                        </select>
                                                        @error('id_saran_tl')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                   
                                                    <div class="form-group col-md-6">
                                                        <label for="waktu"
                                                               class="fs-6 fw-semibold mb-2">Waktu</label>
                                                        <input
                                                            type="date"
                                                            class="form-control form-control-solid @error('waktu') is-invalid @enderror"
                                                            name="waktu" id="waktu" value="{{ old('waktu') }}">
                                                        @error('waktu')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="tempat"
                                                               class="fs-6 fw-semibold mb-2">Tempat</label>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control-solid @error('tempat') is-invalid @enderror"
                                                            name="tempat" id="tempat" value="{{ old('tempat') }}">
                                                        @error('tempat')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="ancaman"
                                                               class="fs-6 fw-semibold mb-2 required">Ancaman</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('ancaman') is-invalid @enderror"
                                                            name="ancaman" id="ancaman">{{ old('ancaman') }}</textarea>
                                                        @error('keterangan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="gangguan"
                                                               class="fs-6 fw-semibold mb-2 required">Gangguan</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('gangguan') is-invalid @enderror"
                                                            name="gangguan" id="gangguan">{{ old('gangguan') }}</textarea>
                                                        @error('keterangan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-7">
                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="hambatan"
                                                               class="fs-6 fw-semibold mb-2 required">Hambatan</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('hambatan') is-invalid @enderror"
                                                            name="hambatan" id="hambatan">{{ old('hambatan') }}</textarea>
                                                        @error('keterangan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="tantangan"
                                                               class="fs-6 fw-semibold mb-2 required">Tantangan</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('tantangan') is-invalid @enderror"
                                                            name="tantangan" id="tantangan">{{ old('tantangan') }}</textarea>
                                                        @error('tantangan')
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
        </script>
        <script >
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
                            url: '/helper-research-sprint', 
                            type: 'GET',
                            data: {case_id: case_id},
                            success: function(response) {
                                $('#id_sprint').empty();
                                $('#id_sprint').append('<option value="">---Pilih Surat Perintah---</option>');

                                $.each(response, function(key, value) {
                                    $('#id_sprint').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#id_sprint').select2();
                            }
                        });
                    });

                    $('#id_sprint, #id_case, #id_satker').change(function() {
                        var surat_perintah_id = $(this).val();

                        $.ajax({
                            url: '/helper-research-lapinsus', 
                            type: 'GET',
                            data: {surat_perintah_id: surat_perintah_id},
                            success: function(response) {
                                $('#id_lapinsus').empty();
                                $('#id_lapinsus').append('<option value="">---Pilih Lapinsus---</option>');

                                $.each(response, function(key, value) {
                                    $('#id_lapinsus').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#id_lapinsus').select2();
                            }
                        });
                    });

                    $('#id_lapinsus, #id_sprint, #id_case, #id_satker').change(function() {
                        var lapinsus_id = $(this).val();

                        $.ajax({
                            url: '/helper-research-saran', 
                            type: 'GET',
                            data: {lapinsus_id: lapinsus_id},
                            success: function(response) {
                                console.log(response)
                                $('#id_saran_tl').empty();
                                $('#id_saran_tl').append('<option value="">---Pilih Saran---</option>');

                                $.each(response, function(key, value) {
                                    $('#id_saran_tl').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#id_saran_tl').select2();
                            }
                        });
                    });

                    ClassicEditor
                        .create(document.querySelector('#keterangan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    ClassicEditor
                        .create(document.querySelector('#ancaman'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    ClassicEditor
                        .create(document.querySelector('#gangguan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    
                    ClassicEditor
                        .create(document.querySelector('#hambatan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                        
                    ClassicEditor
                        .create(document.querySelector('#tantangan'),{
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
