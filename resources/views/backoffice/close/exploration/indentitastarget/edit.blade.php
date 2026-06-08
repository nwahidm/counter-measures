<x-backoffice.layout.app-layout title="Edit Identifikasi Target">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Edit Identifikasi Target" subheading=""
                          breadcrumb="open-research-tibc-create"
                          icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification/>
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
                                    <form id="form" action="{{ route('close.exploration.identitas-target.update', $data->id_exploration_target_identity) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
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
                                                    <div class="form-group col-md-4">
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
                                                    <div class="form-group col-md-4">
                                                        <label for="exploration_rencana_aksi_id" class="fs-6 fw-semibold mb-2">Pilih Rencana Aksi</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('exploration_rencana_aksi_id') is-invalid @enderror"
                                                            name="exploration_rencana_aksi_id" id="exploration_rencana_aksi_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih---</option>
                                                            @foreach ($rencana as $data1)
                                                                <option value="{{ $data1->id_exploration_rencana_aksi }}" @if($data->exploration_rencana_aksi_id === old('id_exploration_rencana_aksi', $data1->id_exploration_rencana_aksi)) selected @endif>{{ $data1->rencana_aksi_data }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('exploration_rencana_aksi_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold mb-3 mt-3">BIODATA TARGET:</label> <hr>
                                                <div class="form-group col-md-12">
                                                    <label for="nama_target" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                    <input type="text" class="form-control" name="nik" id="nik" placeholder="" value="{{old('nik', $data->target_identity_number)}}" required="required">                                                        
                                                    <br>
                                                    <!-- <div class="form-group">
                                                        <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                            Cari
                                                        </button>
                                                    </div> -->
                                                </div>
                                                <div class="row mb-7">
                                                   
                                                    <div class="form-group col-md-6">
                                                        <label for="nama_target" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                        <input type="text" class="form-control form-control-solid @error('nama_target') is-invalid @enderror"
                                                               name="nama_target" id="nama_target" value="{{ old('nama_target', $data->target_name) }}">
                                                        @error('nama_target')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="jenis_kelamin" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                        <input type="text" class="form-control form-control-solid @error('jenis_kelamin') is-invalid @enderror"
                                                        name="jenis_kelamin" id="target_gender" value="{{ old('jenis_kelamin', $data->target_gender) }}">
                                                        @error('jenis_kelamin')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>    
                                                <div class="row mb-7">
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="pekerjaan" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                            <input type="text" class="form-control form-control-solid @error('pekerjaan') is-invalid @enderror"
                                                            name="pekerjaan" id="target_occupation" value="{{ old('pekerjaan', $data->target_occupation) }}">
                                                            @error('pekerjaan')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="pendidikan" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                            <input type="text" class="form-control form-control-solid @error('pendidikan') is-invalid @enderror"
                                                            name="pendidikan" id="target_education" value="{{ old('pendidikan', $data->target_education) }}">
                                                            @error('pendidikan')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>         
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="agama" class="fs-6 fw-semibold mb-2">Agama</label>
                                                        <input type="text" class="form-control form-control-solid @error('agama') is-invalid @enderror"
                                                        name="agama" id="target_religion" value="{{ old('agama',  $data->target_religion) }}">
                                                        @error('agama')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>    
                                    
                                                    <div class="form-group col-md-4">
                                                        <label for="target_photo" class="fs-6 fw-semibold mb-2 ">Upload Foto</label>
                                                        <div class="input-group">
                                                            <input class="form-control form-control-solid @error('target_photo') is-invalid @enderror"
                                                                name="target_photo" type="file" id="target_photo" value="{{ old('target_photo') }}">
                                                            @if($data->target_photo)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.exploration.indentitastarget.collect-info.download-file', encrypt($data->target_photo)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('target_photo')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->target_photo)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_target_photo') is-invalid @enderror"
                                                                name="temp_target_photo"
                                                                type="hidden"
                                                                id="temp_target_photo"
                                                                value="{{ old('temp_target_photo', $data->target_photo) }}">
                                                            @error('temp_target_photo')
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

                                $.each(response, function(key, value) {
                                    $('#exploration_rencana_aksi_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#exploration_rencana_aksi_id').select2();
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

<script>
        $('#cari').on('click',function(e){
                e.preventDefault();
                var nip = $('#nip').val();
                ambilnip( nip );
        });
        function processCekNik() {
            var $this = $('#buttonProcessNik');
            let nik = $('#nik').val();

            if (nik == '' || nik.length == 0 ) {
                alert("Silahkan isi NIK");
                return false;
            }

            BtnLoading($this);
            var nama_target = $("#nama_target");

            console.log("Starting AJAX request with NIK: ", nik); // Debugging

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('open.kependudukan.ceknik', ['nik' => 'NIK_PLACEHOLDER']) }}".replace('NIK_PLACEHOLDER', nik),
                method: "GET",
                dataType: 'json',
                success: function(response) {
                    // console.log("AJAX response received: ", response); // Debugging
                    if (response.status = 'sukses') {
                        var val_nama = response.nama;
                        var val_agama = response.agama;
                        var val_pendidikan = response.pendidikan;
                        var val_pekerjaan = response.pekerjaan;
                        var val_jenis_kelamin = response.jenis_kelamin;
                        var val_alamat = response.alamat;
                        // console.log("Setting nama_target to: ", val_nama); // Debugging
                        $("#nama_target").val(val_nama);
                        $("#target_religion").val(val_agama);
                        $("#target_education").val(val_pendidikan);
                        $("#target_occupation").val(val_pekerjaan);
                        $("#target_gender").val(val_jenis_kelamin);
                        $("#alamat").val(val_alamat);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat memproses data.');
                    console.log("AJAX error: ", error); // Debugging
                },
                complete: function() {
                    $this.html($this.attr("data-original-text"));
                    $this.prop("disabled", false);
                }
            });
        }

        function BtnLoading(elem) {
            $(elem).attr("data-original-text", $(elem).html());
            $(elem).prop("disabled", true);
            $(elem).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        }

        $(document).ready(function() {
            setTimeout(function() {
            $(".alert").slideUp(500);
            }, 3000);

        });
    </script>

    @endpush
</x-backoffice.layout.app-layout>
