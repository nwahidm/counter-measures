<x-backoffice.layout.app-layout title="Ubah Infiltration Dinamika Target">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Infiltration Dinamika Target" subheading=""
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
                                    <form id="form" action="{{ route('close.infiltration.target-dynamics.update', $data->id) }}"
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
                                                        <label for="id_information_collection"
                                                               class="fs-6 fw-semibold mb-2 required">Operasi Rahasia</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_infiltration_operasi_rahasia') is-invalid @enderror"
                                                            name="id_infiltration_operasi_rahasia" id="id_infiltration_operasi_rahasia"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Operasi Rahasia---</option>
                                                            @foreach ($infiltration_secret_operation as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('id_infiltration_operasi_rahasia', $data->infiltration_operasi_rahasia_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                   
                                                   
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="id_case"
                                                               class="fs-6 fw-semibold mb-2 required">Dinamika Teramati</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('dinamika_teramati') is-invalid @enderror"
                                                               name="dinamika_teramati" id="dinamika_teramati"
                                                               value="{{ old('nama_operasi_rahasia',  $data->dinamika_teramati) }}">
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    
                                                    <div class="form-group col-md-4">
                                                        <label for="verification_date"
                                                               class="fs-6 fw-semibold mb-2">Tanggal
                                                            Dinamika Teramati</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_dinamika_teramati') is-invalid @enderror"
                                                               name="tanggal_dinamika_teramati" id="tanggal_dinamika_teramati"
                                                               value="{{ old('tanggal_dinamika_teramati', $data->tanggal_dinamika_teramati) }}">
                                                        @error('tanggal_dinamika_teramati')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    

                                                    
                                                    
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="pendahuluan"
                                                            class="fs-6 fw-semibold mb-2">Deskripsi Dinamika Teramati</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('deskripsi_dinamika_teramati') is-invalid @enderror"
                                                            name="deskripsi_dinamika_teramati" id="deskripsi_dinamika_teramati">
                                                            {{ old('deskripsi_dinamika_teramati',  $data->deskripsi_dinamika_teramati) }}</textarea>
                                                            @error('deskripsi_dinamika_teramati')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="dinamika_target_dokumen_upload"
                                                               class="fs-6 fw-semibold mb-2">Upload File
                                                            Dokument</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('operasi_rahasia_dokumen_upload') is-invalid @enderror"
                                                                name="dinamika_target_dokumen_upload"
                                                                type="file"
                                                                id="dinamika_target_dokumen_upload"
                                                                value="{{ old('dinamika_target_dokumen_upload') }}">
                                                            @if($data->dinamika_target_dokumen_upload)
                                                                <a class="btn btn-dark"
                                                                    href="{{ route('close.tailing.pemahaman-perilaku.download-file', encrypt($data->dinamika_target_dokumen_upload)) }}"
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
                                                        <label for="dinamika_target_video_upload"
                                                               class="fs-6 fw-semibold mb-2 required">Upload File
                                                            Video</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('dinamika_target_video_upload') is-invalid @enderror"
                                                                name="dinamika_target_video_upload"
                                                                type="file"
                                                                id="dinamika_target_video_upload"
                                                                value="{{ old('dinamika_target_video_upload') }}">
                                                            @if($data->dinamika_target_video_upload)
                                                                <a class="btn btn-dark"
                                                                    href="{{ route('close.tailing.pemahaman-perilaku.download-file', encrypt($data->dinamika_target_video_upload)) }}"
                                                                    id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                            @error('upload_lapinsus')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
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
        <script  type="module">
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

                    ClassicEditor
                        .create(document.querySelector('#deskripsi_dinamika_teramati'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });

               
                }
            };


            $('#id_case').change(function() {
                    var case_id = $(this).val();
                    console.log("Case ID changed:", case_id);

                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected case_id
                    $.ajax({
                        url: '/close/helper-infiltration-operasi-rahasia', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {case_id: case_id},
                        success: function(response) {
                            $('#id_infiltration_operasi_rahasia').empty();
                            $('#id_infiltration_operasi_rahasia').append('<option value="">---Pilih Operasi Rahasia---</option>');

                            $.each(response, function(key, value) {
                                $('#id_infiltration_operasi_rahasia').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#id_infiltration_operasi_rahasia').select2(); // Reinitialize select2
                        }
                    });
            });
        </script>
    @endpush
</x-backoffice.layout.app-layout>
