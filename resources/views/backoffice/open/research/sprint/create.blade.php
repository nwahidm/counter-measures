<x-backoffice.layout.app-layout title="Tambah Penelitian Surat Perintah">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Penelitian Surat Perintah" subheading="" breadcrumb="open-research-warrant-create"
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
                                    <form id="form" action="{{ route('open.research.warrant.store') }}" method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold d-none">DETAIL KASUS:</label>
                                                <hr class="d-none">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
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
                                                        <label for="nomor_sprint"
                                                               class="fs-6 fw-semibold mb-2 required">Nomor Surat
                                                            Perintah</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('nomor_sprint') is-invalid @enderror"
                                                               name="nomor_sprint" id="nomor_sprint"
                                                               value="{{ old('nomor_sprint') }}">
                                                        @error('nomor_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="perihal_sprint"
                                                               class="fs-6 fw-semibold mb-2 required">Perihal Surat
                                                            Perintah</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('perihal_sprint') is-invalid @enderror"
                                                               name="perihal_sprint" id="perihal_sprint"
                                                               value="{{ old('perihal_sprint') }}">
                                                        @error('perihal_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="tanggal_sprint"
                                                               class="fs-6 fw-semibold mb-2">Tgl. Surat
                                                            Perintah</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_sprint') is-invalid @enderror"
                                                               name="tanggal_sprint" id="tanggal_sprint"
                                                               value="{{ old('tanggal_sprint') }}">
                                                        @error('tanggal_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="tanggal_mulai_sprint"
                                                               class="fs-6 fw-semibold mb-2">Tgl. Mulai Surat
                                                            Perintah</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_mulai_sprint') is-invalid @enderror"
                                                               name="tanggal_mulai_sprint" id="tanggal_mulai_sprint"
                                                               value="{{ old('tanggal_mulai_sprint') }}">
                                                        @error('tanggal_mulai_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="tanggal_akhir_sprint"
                                                               class="fs-6 fw-semibold mb-2">Tgl. Berakhir
                                                            Surat Perintah</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_akhir_sprint') is-invalid @enderror"
                                                               name="tanggal_akhir_sprint" id="tanggal_akhir_sprint"
                                                               value="{{ old('tanggal_akhir_sprint') }}">
                                                        @error('tanggal_akhir_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="upload_sprint"
                                                               class="fs-6 fw-semibold mb-2 required">Upload File
                                                            Surat Perintah (Pdf)</label>
                                                        <input
                                                            class="form-control form-control-solid @error('upload_sprint') is-invalid @enderror"
                                                            name="upload_sprint"
                                                            type="file"
                                                            accept=".pdf"
                                                            id="upload_sprint"
                                                            value="{{ old('upload_sprint') }}">
                                                        @error('upload_sprint')
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

                    ClassicEditor
                        .create(document.querySelector('#keterangan'),{
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
