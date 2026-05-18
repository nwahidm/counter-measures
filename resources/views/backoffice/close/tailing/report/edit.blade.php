<x-backoffice.layout.app-layout title="Ubah Laporan Pembuntutan">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Laporan Pembuntutan" subheading=""
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
                                          action="{{ route('close.tailing.pemahaman-perilaku.update', $data->id) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="id_satker" class="fs-6 fw-semibold mb-2 required">Satuan
                                                            Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_satker') is-invalid @enderror"
                                                            name="id_satker_label" id="id_satker_label"
                                                            data-control="select2" data-hide-search="true" disabled>
                                                            <option value="">---Pilih Satker---</option>
                                                            @foreach ($satker as $row)
                                                                <option value="{{ $row['id'] }}"
                                                                        @if($row['kode_satker'] === old('kode_satker', $data->kode_satker)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="id_satker" value="{{ $data->kode_satker }}">
                                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                                        @error('id_satker')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
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
                                                                    @if($row['id'] === old('id_case', $data->case_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_name"
                                                               class="fs-6 fw-semibold mb-2 required">Nama Target</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('target_name') is-invalid @enderror"
                                                               name="target_name" id="target_name"
                                                               value="{{ old('target_name', $data->target_name) }}">
                                                        @error('target_name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="target_gender"
                                                               class="fs-6 fw-semibold mb-2 required">Jenis Kelamin</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('target_gender') is-invalid @enderror"
                                                            name="target_gender" id="target_gender"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Gender---</option>
                                                                <option value="Laki-Laki" 
                                                                @if('Laki-Laki' === old('target_gender', $data->target_gender)) selected @endif>Laki-Laki</option>
                                                                <option value="Perempuan" 
                                                                @if('Perempuan' === old('target_gender', $data->target_gender)) selected @endif>Perempuan</option>
                                                        </select>
                                                        @error('target_gender')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                 <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_religion"
                                                               class="fs-6 fw-semibold mb-2 required">Agama</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('target_religion') is-invalid @enderror"
                                                            name="target_religion" id="target_religion"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Agama---</option>
                                                            @foreach ($agama as $row)
                                                                <option
                                                                    value="{{ $row['kode'] }}"
                                                                    @if($row['kode'] === old('target_religion', $data->target_religion)) selected @endif>{{ $row['nama'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('kode')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                   
                                               
                                                    <div class="form-group col-md-6">
                                                        <label for="target_identity_number"
                                                               class="fs-6 fw-semibold mb-2 required">Identity Number</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('target_identity_number') is-invalid @enderror"
                                                               name="target_identity_number" id="target_identity_number"
                                                               value="{{ old('target_identity_number', $data->target_identity_number) }}">
                                                        @error('target_identity_number')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                     </div>
                                                 <div class="row mb-7">

                                                    <div class="form-group col-md-6">
                                                        <label for="target_identity_number_type"
                                                               class="fs-6 fw-semibold mb-2 required">Identity Number Type</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('target_identity_number_type') is-invalid @enderror"
                                                            name="target_identity_number_type" id="target_identity_number_type"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Data---</option>
                                                                <option value="KTP" 
                                                                @if('KTP' === old('target_identity_number_type', $data->target_identity_number_type)) selected @endif>KTP</option>
                                                                <option value="SIM" 
                                                                @if('SIM' === old('target_identity_number_type', $data->target_identity_number_type)) selected @endif>SIM</option>
                                                        </select>
                                                        @error('target_identity_number_type')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="target_occupation"
                                                               class="fs-6 fw-semibold mb-2 required">Occuopation</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('target_occupation') is-invalid @enderror"
                                                               name="target_occupation" id="target_occupation"
                                                               value="{{ old('target_occupation', $data->target_occupation) }}">
                                                        @error('target_occupation')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                   
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_education"
                                                               class="fs-6 fw-semibold mb-2 required">Education</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('target_education') is-invalid @enderror"
                                                               name="target_education" id="target_education"
                                                               value="{{ old('target_education', $data->target_education) }}">
                                                        @error('target_education')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="target_photo"
                                                               class="fs-6 fw-semibold mb-2 required">Foto
                                                            </label>
                                                        <input
                                                            class="form-control form-control-solid @error('target_photo') is-invalid @enderror"
                                                            name="target_photo"
                                                            type="file"
                                                            id="target_photo"
                                                            value="{{ old('target_photo') }}">
                                                        @error('target_photo')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                               
                                                <div class="row mb-7">
                                                   <div class="form-group col-md-6">
                                                        <label for="perilaku_tercatat"
                                                               class="fs-6 fw-semibold mb-2 required">Perilaku Tercatat</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('perilaku_tercatat') is-invalid @enderror"
                                                            name="perilaku_tercatat" id="perilaku_tercatat">{{ old('perilaku_tercatat', $data->perilaku_tercatat) }}</textarea>
                                                        @error('aktivitas_rutin')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                     <div class="form-group col-md-6">
                                                        <label for="aktivitas_rutin"
                                                               class="fs-6 fw-semibold mb-2 required">Aktivitas Rutin</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('aktivitas_rutin') is-invalid @enderror"
                                                            name="aktivitas_rutin" id="aktivitas_rutin">{{ old('aktivitas_rutin', $data->aktivitas_rutin) }}</textarea>
                                                        @error('aktivitas_rutin')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                 <div class="row mb-7">
                                                   <div class="form-group col-md-6">
                                                        <label for="hubungan_sosial"
                                                               class="fs-6 fw-semibold mb-2 required">Perilaku Tercatat</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('hubungan_sosial') is-invalid @enderror"
                                                            name="hubungan_sosial" id="hubungan_sosial">{{ old('hubungan_sosial', $data->hubungan_sosial) }}</textarea>
                                                        @error('hubungan_sosial')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                     <div class="form-group col-md-6">
                                                        <label for="prediksi_perilaku"
                                                               class="fs-6 fw-semibold mb-2 required">Prediksi Perilaku</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('prediksi_perilaku') is-invalid @enderror"
                                                            name="prediksi_perilaku" id="prediksi_perilaku">{{ old('prediksi_perilaku', $data->prediksi_perilaku) }}</textarea>
                                                        @error('prediksi_perilaku')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="pemahaman_perilaku_video_upload"
                                                               class="fs-6 fw-semibold mb-2 required">Upload File</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('pemahaman_perilaku_video_upload') is-invalid @enderror"
                                                                name="pemahaman_perilaku_video_upload"
                                                                type="file"
                                                                id="pemahaman_perilaku_video_upload"
                                                                value="{{ old('pemahaman_perilaku_video_upload') }}">
                                                            @if($data->pemahaman_perilaku_video_upload)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.tailing.pemahaman-perilaku.download-file', encrypt($data->pemahaman_perilaku_video_upload)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('pemahaman_perilaku_video_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->pemahaman_perilaku_video_upload)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_pemahaman_perilaku_video_upload') is-invalid @enderror"
                                                                name="temp_pemahaman_perilaku_video_upload"
                                                                type="hidden"
                                                                id="temp_pemahaman_perilaku_video_upload"
                                                                value="{{ old('temp_pemahaman_perilaku_video_upload', $data->pemahaman_perilaku_video_upload) }}">
                                                            @error('temp_pemahaman_perilaku_video_upload')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <button
                                                            class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                            type="submit">Simpan
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
        <script type="module">
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

                    ClassicEditor
                        .create(document.querySelector('#perilaku_tercatat'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#aktivitas_rutin'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#hubungan_sosial'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#prediksi_perilaku'),{
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
