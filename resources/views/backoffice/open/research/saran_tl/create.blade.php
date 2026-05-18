<x-backoffice.layout.app-layout title="Tambah Penelitian Saran dan Tindak Lanjut">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Penelitian Saran dan Tindak Lanjut" subheading=""
                          breadcrumb="open-research-advice-measure-create"
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
                                    <form id="form" action="{{ route('open.research.advice-measure.store') }}"
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
                                                            @if(auth()->user()->user_roles != "superadmin")
                                                                <input type="hidden" name="id_satker" value="{{ auth()->user()?->satker?->id_satker }}">
                                                            @endif
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
                                                    <div class="form-group col-md-12">
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
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="tanggal_tl"
                                                               class="fs-6 fw-semibold mb-2 required">Tgl. Tindak Lanjut</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_tl') is-invalid @enderror"
                                                               name="tanggal_tl" id="tanggal_tl"
                                                               value="{{ old('tanggal_tl') }}">
                                                        @error('tanggal_tl')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="saran_tl"
                                                               class="fs-6 fw-semibold mb-2 required">Saran Tindak Lanjut</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('saran_tl') is-invalid @enderror"
                                                            name="saran_tl" id="saran_tl">{{ old('saran_tl') }}</textarea>
                                                        @error('saran_tl')
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
                            },
                            error: function(response) {
                                $('#id_sprint').empty();
                                $('#id_sprint').append('<option value="">---Pilih Surat Perintah---</option>');

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
                                console.log(response);
                                $('#id_lapinsus').empty();
                                $('#id_lapinsus').append('<option value="">---Pilih Lapinsus---</option>');

                                $.each(response, function(key, value) {
                                    $('#id_lapinsus').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#id_lapinsus').select2();
                            },
                            error: function(response) {
                                $('#id_lapinsus').empty();
                                $('#id_lapinsus').append('<option value="">---Pilih Lapinsus---</option>');

                                $('#id_lapinsus').select2();
                            }
                        });
                    });

                    ClassicEditor
                        .create(document.querySelector('#saran_tl'),{
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
