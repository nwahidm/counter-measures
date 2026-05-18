<x-backoffice.layout.app-layout title="Edit Penjajakan Rencana Aksi">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Edit Penjajakan Rencana Aksi" subheading=""
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
                                    <form id="form" action="{{ route('close.exploration.rencana-aksi.update', $data->id_exploration_rencana_aksi) }}"
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
                                                            name="id_satker" id="id_satker"
                                                            data-control="select2" data-hide-search="true" disabled>
                                                            <option value="">---Pilih Satker---</option>
                                                            @foreach ($satker as $row)
                                                                <option value="{{ $row['kode_satker'] }}"
                                                                        @if($row['kode_satker'] === $data->satker->kode_satker) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_satker')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="case_id"
                                                               class="fs-6 fw-semibold mb-2 required">Kasus</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('case_id') is-invalid @enderror"
                                                            name="case_id" id="case_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Kasus---</option>
                                                            @foreach ($case as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('case_id', $data->case_id)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('case_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="rencana_aksi_data"
                                                               class="fs-6 fw-semibold mb-2 required">Rencana Aksi</label>
                                                        <input
                                                            type="text"
                                                            class="form-control form-control-solid @error('rencana_aksi_data') is-invalid @enderror"
                                                            name="rencana_aksi_data" id="rencana_aksi_data" value="{{ old('rencana_aksi_data', $data->rencana_aksi_data) }}">
                                                        @error('rencana_aksi_data')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="rencana_aksi_detail"
                                                               class="fs-6 fw-semibold mb-2">Keterangan Rencana Aksi</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('rencana_aksi_detail') is-invalid @enderror"
                                                            name="rencana_aksi_detail" id="rencana_aksi_detail">{{ old('keterangan', $data->rencana_aksi_detail) }}</textarea>
                                                        @error('rencana_aksi_detail')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="rencana_aksi_upload" class="fs-6 fw-semibold mb-2 required">Upload File</label>
                                                        <div class="input-group">
                                                            <input class="form-control form-control-solid @error('rencana_aksi_upload') is-invalid @enderror"
                                                                name="rencana_aksi_upload" type="file" id="rencana_aksi_upload" value="{{ old('rencana_aksi_upload') }}">
                                                            @if($data->rencana_aksi_upload)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.exploration.collect-info.download-file', encrypt($data->rencana_aksi_upload)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('rencana_aksi_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->rencana_aksi_upload)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_rencana_aksi_upload') is-invalid @enderror"
                                                                name="temp_rencana_aksi_upload"
                                                                type="hidden"
                                                                id="temp_rencana_aksi_upload"
                                                                value="{{ old('temp_rencana_aksi_upload', $data->rencana_aksi_upload) }}">
                                                            @error('temp_rencana_aksi_upload')
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
        <script type="module">
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

                    $('#id_satker').change(function() {
                        var id_satker = $(this).val();

                        $.ajax({
                            url: '/close/helper-case', 
                            type: 'GET',
                            data: {satker_id: id_satker},
                            success: function(response) {
                                $('#case_id').empty();
                                $('#case_id').append('<option value="">---Pilih Kasus---</option>');

                                $.each(response, function(key, value) {
                                    $('#case_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#case_id').select2();
                            }
                        });
                    });

                    ClassicEditor
                        .create(document.querySelector('#rencana_aksi_detail'),{
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

<script>
    function setSubmitType(type) {
        document.getElementById('submit_type').value = type;
    }
</script>
