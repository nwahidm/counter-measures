<x-backoffice.layout.app-layout title="Ubah Penyadapan Data Sinyal Pintar">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Penyadapan Data Sinyal Pintar" subheading=""
                          breadcrumb="close-tapping-intelligent_signal-edit"
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
                                    <form id="form" action="{{ route('close.tapping.intelligent_signal.update', $data->id_tapping_intelligent_signal) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="id_satker" class="fs-6 fw-semibold mb-2 required">Satuan
                                                            Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_satker') is-invalid @enderror"
                                                            name="id_satker" id="id_satker"
                                                            data-control="select2" data-hide-search="true" disabled>
                                                            <option value="">---Pilih Satker---</option>
                                                            @foreach ($satker as $row)
                                                                <option value="{{ $row['id'] }}"
                                                                        @if($row['id'] == $data->satker_id) selected @endif>{{ $row['text'] }}</option>
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
                                                                    @if($row['id'] === $data->case_id) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="tapping_electronic_device_data_id"
                                                               class="fs-6 fw-semibold mb-2 required">Electronic Device</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('tapping_electronic_device_data_id') is-invalid @enderror"
                                                            name="tapping_electronic_device_data_id" id="tapping_electronic_device_data_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Electronic Device---</option>
                                                            @foreach ($eldev as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('tapping_electronic_device_data_id', $data->tapping_electronic_device_data_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('tapping_electronic_device_data_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="tanggal_penyadapan"
                                                               class="fs-6 fw-semibold mb-2 ">Tgl.
                                                            Penyadapan</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_penyadapan') is-invalid @enderror"
                                                               name="tanggal_penyadapan" id="tanggal_penyadapan"
                                                               value="{{ old('tanggal_penyadapan', $data->tanggal_penyadapan?->toDateString()) }}">
                                                        @error('tanggal_penyadapan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="sumber_data"
                                                               class="fs-6 fw-semibold mb-2 required">Jenis Sinyal</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('jenis_sinyal') is-invalid @enderror"
                                                               name="jenis_sinyal" id="jenis_sinyal"
                                                               value="{{ old('jenis_sinyal', $data->jenis_sinyal) }}">
                                                        @error('jenis_sinyal')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="deskripsi_hasil"
                                                               class="fs-6 fw-semibold mb-2 required">Deskripsi Hasil</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('deskripsi_hasil') is-invalid @enderror"
                                                            name="deskripsi_hasil"
                                                            id="deskripsi_hasil">{{ old('deskripsi_hasil', $data->deskripsi_hasil) }}</textarea>
                                                        @error('deskripsi_hasil')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="dokumen_upload"
                                                               class="fs-6 fw-semibold mb-2 ">File Dokumen</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('dokumen_upload') is-invalid @enderror"
                                                                name="dokumen_upload"
                                                                type="file"
                                                                id="dokumen_upload"
                                                                value="{{ old('dokumen_upload') }}">
                                                            @if($data->dokumen_upload)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.tapping.intelligent_signal.download-dokumen', encrypt($data->dokumen_upload)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('dokumen_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->dokumen_upload)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_dokumen_upload') is-invalid @enderror"
                                                                name="temp_dokumen_upload"
                                                                type="hidden"
                                                                id="temp_dokumen_upload"
                                                                value="{{ old('temp_dokumen_upload', $data->dokumen_upload) }}">
                                                            @error('temp_dokumen_upload')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="video_upload"
                                                               class="fs-6 fw-semibold mb-2 ">File Video</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('video_upload') is-invalid @enderror"
                                                                name="video_upload"
                                                                type="file"
                                                                id="video_upload"
                                                                value="{{ old('video_upload') }}">
                                                            @if($data->video_upload)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.tapping.intelligent_signal.download-video', encrypt($data->video_upload)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('video_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->video_upload)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_video_upload') is-invalid @enderror"
                                                                name="temp_video_upload"
                                                                type="hidden"
                                                                id="temp_video_upload"
                                                                value="{{ old('temp_video_upload', $data->video_upload) }}">
                                                            @error('temp_video_upload')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                </div>
                                                <input type="hidden" name="submit_type" id="submit_type" value="">
                                                
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <button
                                                            class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                            onclick="setSubmitType('update')"
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
                            url: '/helper-tapping-device', 
                            type: 'GET',
                            data: {case_id: case_id},
                            success: function(response) {
                                $('#tapping_electronic_device_data_id').empty();
                                $('#tapping_electronic_device_data_id').append('<option value="">---Pilih Perangkat Elektronik---</option>');

                                $.each(response, function(key, value) {
                                    $('#tapping_electronic_device_data_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#tapping_electronic_device_data_id').select2();
                            }
                        });
                    });

                    ClassicEditor
                        .create(document.querySelector('#metode_penyadapan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    ClassicEditor
                        .create(document.querySelector('#deskripsi_hasil'),{
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
