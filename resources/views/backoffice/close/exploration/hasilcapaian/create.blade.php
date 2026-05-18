<x-backoffice.layout.app-layout title="Tambah Penjajakan Hasil Pencapaian">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Penjajakan Hasil Pencapaian" subheading=""
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
                                    <form id="form" action="{{ route('close.exploration.hasil-pencapaian.store') }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="id_satker" class="fs-6 fw-semibold mb-2 required">Satuan
                                                            Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_satker') is-invalid @enderror"
                                                            name="id_satker" id="id_satker"
                                                            data-control="select2" data-hide-search="true"
                                                            @if(auth()->user()->user_roles != "superadmin") disabled @endif>
                                                            <option value="">---Pilih Satker---</option>
                                                            @foreach ($satker as $row)
                                                                <option value="{{ $row['id'] }}" @if($row['id'] === auth()->user()->satker->id_satker) selected @endif>{{ $row['text'] }}</option>
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
                                                        <label for="case_id" class="fs-6 fw-semibold mb-2 required">Kasus</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('case_id') is-invalid @enderror"
                                                            name="case_id" id="case_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Kasus---</option>
                                                            @foreach ($case as $row)
                                                                <option value="{{ $row['id'] }}" @if($row['id'] === old('case_id')) selected @endif> {{ $row['text'] }} </option>
                                                            @endforeach
                                                        </select>
                                                        @error('case_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="exploration_rencana_aksi_id" class="fs-6 fw-semibold mb-2 ">Pilih Rencana Aksi</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('exploration_rencana_aksi_id') is-invalid @enderror"
                                                            name="exploration_rencana_aksi_id" id="exploration_rencana_aksi_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Rencana Aksi---</option>
                                                            {{-- @foreach ($rencana as $data)
                                                                <option value="{{ $data->id_exploration_rencana_aksi }}">{{ $data->rencana_aksi_data }}</option>
                                                            @endforeach --}}
                                                        </select>
                                                        @error('exploration_rencana_aksi_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="exploration_target_identity_id" class="fs-6 fw-semibold mb-2">Target</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('exploration_target_identity_id') is-invalid @enderror"
                                                            name="exploration_target_identity_id" id="exploration_target_identity_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Identitas Target---</option>
                                                            {{-- @foreach ($target as $data)
                                                                <option value="{{ $data->id_exploration_target_identity }}">{{ $data->target_name }}</option>
                                                            @endforeach --}}
                                                        </select>
                                                        @error('exploration_target_identity_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="hasil_yang_dicapai"
                                                               class="fs-6 fw-semibold mb-2 required">Hasil Yang Dicapai</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('hasil_yang_dicapai') is-invalid @enderror"
                                                            name="hasil_yang_dicapai" id="hasil_yang_dicapai">{{ old('hasil_yang_dicapai') }}</textarea>
                                                        @error('hasil_yang_dicapai')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="upload_hasil_yang_dicapai" class="fs-6 fw-semibold mb-2">Upload File Rencana Aksi</label>
                                                        <input class="form-control form-control-solid @error('upload_hasil_yang_dicapai') is-invalid @enderror"
                                                            name="upload_hasil_yang_dicapai" type="file" id="rencana_aksi_upload" value="{{ old ('upload_hasil_yang_dicapai') }}" accept="application/pdf">
                                                        @error('upload_hasil_yang_dicapai')
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

                $('#id_satker').change(function() {
                    var id_satker = $(this).val();

                    $.ajax({
                        url: '/close/helper-case', 
                        type: 'GET',
                        data: {satker_id: id_satker},
                        success: function(response) {
                            $('#case_id').empty();
                            $('#case_id').append('<option value="">---Pilih Kasus---</option>');
                            $('#exploration_rencana_aksi_id').empty();
                            $('#exploration_rencana_aksi_id').append('<option value="">---Pilih Rencana Aksi---</option>');
                            $('#exploration_target_identity_id').empty();
                            $('#exploration_target_identity_id').append('<option value="">---Pilih Identitas Target---</option>');

                            $.each(response, function(key, value) {
                                $('#case_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#case_id').select2();
                        }
                    });
                });

                $('#id_satker, #case_id').change(function() {
                    var case_id = $(this).val();

                    $.ajax({
                        url: '/close/helper-exploration-rencana-aksi', 
                        type: 'GET',
                        data: {case_id: case_id},
                        success: function(response) {
                            $('#exploration_rencana_aksi_id').empty();
                            $('#exploration_rencana_aksi_id').append('<option value="">---Pilih Rencana Aksi---</option>');
                            $('#exploration_target_identity_id').empty();
                            $('#exploration_target_identity_id').append('<option value="">---Pilih Identitas Target---</option>');

                            $.each(response, function(key, value) {
                                $('#exploration_rencana_aksi_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#exploration_rencana_aksi_id').select2();
                        }
                    });
                });

                $('#id_satker, #case_id, #exploration_rencana_aksi_id').change(function() {
                    var exploration_rencana_aksi_id = $(this).val();

                    $.ajax({
                        url: '/close/helper-exploration-target-identity', 
                        type: 'GET',
                        data: {exploration_rencana_aksi_id: exploration_rencana_aksi_id},
                        success: function(response) {
                            $('#exploration_target_identity_id').empty();
                            $('#exploration_target_identity_id').append('<option value="">---Pilih Identitas Target---</option>');

                            $.each(response, function(key, value) {
                                $('#exploration_target_identity_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#exploration_target_identity_id').select2();
                        }
                    });
                });

                ClassicEditor
                    .create(document.querySelector('#hasil_yang_dicapai'),{
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
