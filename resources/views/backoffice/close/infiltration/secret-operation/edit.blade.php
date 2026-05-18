<x-backoffice.layout.app-layout title="Ubah Infiltration Operasi Rahasia">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Infiltration Operasi Rahasia" subheading=""
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
                                    <form id="form" action="{{ route('close.infiltration.secret-operation.update',  $data->id) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
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
                                                                    @if($row['id'] === old('id_case', $data->case_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                   
                                                    <div class="form-group col-md-4">
                                                        <label for="id_case"
                                                               class="fs-6 fw-semibold mb-2 required">Nama Operasi Rahasia</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('nama_operasi_rahasia') is-invalid @enderror"
                                                               name="nama_operasi_rahasia" id="nama_operasi_rahasia"
                                                               value="{{ old('nama_operasi_rahasia',  $data->nama_operasi_rahasia) }}">
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                </div>
                                                <div class="row mb-7">
                                                    
                                                    <div class="form-group col-md-4">
                                                        <label for="verification_date"
                                                               class="fs-6 fw-semibold mb-2">Tanggal
                                                            Operasi Rahasia</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_operasi_rahasia') is-invalid @enderror"
                                                               name="tanggal_operasi_rahasia" id="tanggal_operasi_rahasia"
                                                               value="{{ old('tanggal_operasi_rahasia', $data->tanggal_operasi_rahasia) }}">
                                                        @error('tanggal_operasi_rahasia')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    

                                                    <div class="form-group col-md-4">
                                                        <label for="perihal_surat"
                                                               class="fs-6 fw-semibold mb-2">Metode Eksekusi</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('metode_eksekusi') is-invalid @enderror"
                                                               name="metode_eksekusi" id="metode_eksekusi"
                                                               value="{{ old('metode_eksekusi', $data->metode_eksekusi) }}">
                                                        @error('metode_validasi')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="operasi_rahasia_dokumen_upload"
                                                               class="fs-6 fw-semibold mb-2">Upload File
                                                            Dokumen</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('operasi_rahasia_dokumen_upload') is-invalid @enderror"
                                                                name="operasi_rahasia_dokumen_upload"
                                                                type="file"
                                                                id="operasi_rahasia_dokumen_upload"
                                                                value="{{ old('operasi_rahasia_dokumen_upload') }}">
                                                            @if($data->operasi_rahasia_dokumen_upload)
                                                                <a class="btn btn-dark"
                                                                    href="{{ route('close.tailing.pemahaman-perilaku.download-file', encrypt($data->operasi_rahasia_dokumen_upload)) }}"
                                                                    id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                            @error('upload_lapinsus')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="operasi_rahasia_video_upload"
                                                               class="fs-6 fw-semibold mb-2 required">Upload File
                                                            Video</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('operasi_rahasia_video_upload') is-invalid @enderror"
                                                                name="operasi_rahasia_video_upload"
                                                                type="file"
                                                                id="operasi_rahasia_video_upload"
                                                                value="{{ old('operasi_rahasia_video_upload') }}">
                                                            @if($data->operasi_rahasia_video_upload)
                                                                <a class="btn btn-dark"
                                                                    href="{{ route('close.tailing.pemahaman-perilaku.download-file', encrypt($data->operasi_rahasia_video_upload)) }}"
                                                                    id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('upload_lapinsus')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
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
        <script type="module">
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

                    ClassicEditor
                        .create(document.querySelector('#hasil_validasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    ClassicEditor
                        .create(document.querySelector('#catatan_validasi'),{
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
