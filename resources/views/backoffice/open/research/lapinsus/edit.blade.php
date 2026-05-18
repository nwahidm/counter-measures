<x-backoffice.layout.app-layout title="Ubah Penelitian Laporan Informasi Khusus">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Penelitian Laporan Informasi Khusus" subheading=""
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
                                          action="{{ route('open.research.spesific-intel-report.update', $data->id) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PATCH')
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
                                                                    @if($row['id'] === $data?->case?->id_satker) selected @endif>{{ $row['text'] }}</option>
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

                                                    <div class="form-group col-md-6">
                                                        <label for="id_sprint"
                                                               class="fs-6 fw-semibold mb-2">No. Surat
                                                            Perintah</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_sprint') is-invalid @enderror"
                                                            name="id_sprint" id="id_sprint"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Surat Perintah---</option>
                                                            @foreach ($sprint as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('id_sprint', $data->surat_perintah_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="nomor_surat"
                                                               class="fs-6 fw-semibold mb-2 required">Nomor
                                                            Surat</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('nomor_surat') is-invalid @enderror"
                                                               name="nomor_surat" id="nomor_surat"
                                                               value="{{ old('nomor_surat', $data->nomor_surat) }}">
                                                        @error('nomor_surat')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="tanggal_surat"
                                                               class="fs-6 fw-semibold mb-2 required">Tanggal
                                                            Surat</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_surat') is-invalid @enderror"
                                                               name="tanggal_surat" id="tanggal_surat"
                                                               value="{{ old('tanggal_surat', $data->tanggal_surat?->toDateString()) }}">
                                                        @error('tanggal_surat')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="perihal_surat"
                                                               class="fs-6 fw-semibold mb-2 ">Perihal
                                                            Surat</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('perihal_surat') is-invalid @enderror"
                                                               name="perihal_surat" id="perihal_surat"
                                                               value="{{ old('perihal_surat', $data->perihal_surat) }}">
                                                        @error('perihal_surat')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="informasi_diperoleh"
                                                               class="fs-6 fw-semibold mb-2 required">Informasi Yang Diperoleh</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('informasi_diperoleh') is-invalid @enderror"
                                                            name="informasi_diperoleh" id="informasi_diperoleh">{{ old('informasi_diperoleh', $data->informasi_diperoleh) }}</textarea>
                                                        @error('informasi_diperoleh')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="sumber_informasi"
                                                               class="fs-6 fw-semibold mb-2 required">Sumber Informasi</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('sumber_informasi') is-invalid @enderror"
                                                            name="sumber_informasi" id="sumber_informasi">{{ old('sumber_informasi', $data->sumber_informasi) }}</textarea>
                                                        @error('sumber_informasi')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="tren_perkembangan"
                                                               class="fs-6 fw-semibold mb-2 required">Tren Perkembangan / Perkiraan</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('tren_perkembangan') is-invalid @enderror"
                                                            name="tren_perkembangan" id="tren_perkembangan">{{ old('tren_perkembangan', $data->tren_perkembangan) }}</textarea>
                                                        @error('tren_perkembangan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="saran_tindak"
                                                               class="fs-6 fw-semibold mb-2 required">Saran / Tindak</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('saran_tindak') is-invalid @enderror"
                                                            name="saran_tindak" id="saran_tindak">{{ old('saran_tindak', $data->saran_tindak) }}</textarea>
                                                        @error('saran_tindak')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="penandatangan"
                                                               class="fs-6 fw-semibold mb-2 required">Penandatangan</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('penandatangan') is-invalid @enderror"
                                                            name="penandatangan" id="penandatangan"
                                                            data-control="select2" data-hide-search="false">
                                                            <option value="">---Pilih Penandatangan---</option>
                                                            @foreach ($listPegawai as $row)
                                                                <option value="{{ $row['nip'] }}"
                                                                    @if ($data->nip == $row['nip'])
                                                                        selected
                                                                    @endif
                                                                    >{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('penandatangan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="jabatan"
                                                               class="fs-6 fw-semibold mb-2 required">Jabatan</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('jabatan') is-invalid @enderror"
                                                               name="jabatan" id="jabatan"
                                                               value="{{ old('jabatan', $data->jabatan) }}">
                                                        @error('tren_perkembangan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="nama_pejabat"
                                                               class="fs-6 fw-semibold mb-2 required">Nama Pejabat</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('nama_pejabat') is-invalid @enderror"
                                                               name="nama_pejabat" id="nama_pejabat"
                                                               value="{{ old('nama_pejabat', $data->nama_pejabat) }}">
                                                        @error('nama_pejabat')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="nip"
                                                               class="fs-6 fw-semibold mb-2 required">NIP Pejabat</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('nip') is-invalid @enderror"
                                                               name="nip" id="nip"
                                                               value="{{ old('nip', $data->nip) }}">
                                                        @error('nip')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div> --}}

                                                
                                                <div class="row">
                                                    <input type="hidden" name="submit_type" id="submit_type" value="">
                                                    <div class="col-md-1">
                                                        <button
                                                            class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                            onclick="setSubmitType('save')"
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

                    $('#id_case').change(function() {
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

                    ClassicEditor
                        .create(document.querySelector('#informasi_diperoleh'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#sumber_informasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#tren_perkembangan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#saran_tindak'),{
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
