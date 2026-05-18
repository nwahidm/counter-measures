<x-backoffice.layout.app-layout title="Tambah Penjajakan Identifikasi Target">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Penjajakan Identifikasi Target" subheading=""
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
                                    <form id="form" action="{{ route('close.exploration.identitas-target.store') }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
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
                                                        @if(auth()->user()->user_roles != "superadmin")
                                                            <input type="hidden" name="id_satker" value="{{ auth()->user()?->satker?->id_satker }}">
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="case_id" class="fs-6 fw-semibold mb-2 required">Kasus</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('case_id') is-invalid @enderror"
                                                            name="case_id" id="case_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Kasus---</option>
                                                            @foreach ($case as $row)
                                                            <option
                                                                value="{{ $row['id'] }}"
                                                                @if($row['id'] === old('case_id')) selected @endif>{{ $row['text'] }}</option>
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
                                                            <option value="">---Pilih Rencana Aksi---</option>
                                                            {{-- @foreach ($rencana as $data)
                                                                <option value="{{ $data->id_exploration_rencana_aksi }}">{{ $data->rencana_aksi_data }}</option>
                                                            @endforeach --}}
                                                        </select>
                                                        @error('exploration_rencana_aksi_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold mb-3 mt-3">BIODATA TARGET:</label> <hr>
                                                <div class="form-group col-md-12">
                                                    <label for="nama_target" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                    <input type="text" class="form-control" name="nik" id="nik" placeholder="" value="{{old('nik')}}" required="required">                                                        
                                                    <br>
                                                    <div class="form-group">
                                                        <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                            Cari
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="nama_target" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                        <input type="text" class="form-control form-control-solid @error('nama_target') is-invalid @enderror"
                                                               name="nama_target" id="nama_target" value="{{ old('nama_target') }}">
                                                        @error('nama_target')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="jenis_kelamin" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                        <input type="text" class="form-control form-control-solid @error('jenis_kelamin') is-invalid @enderror"
                                                        name="jenis_kelamin" id="jenis_kelamin" value="{{ old('jenis_kelamin') }}">
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
                                                            name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}">
                                                            @error('pekerjaan')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="pendidikan" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                            <input type="text" class="form-control form-control-solid @error('pendidikan') is-invalid @enderror"
                                                            name="pendidikan" id="pendidikan" value="{{ old('pendidikan') }}">
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
                                                        name="agama" id="agama" value="{{ old('agama') }}">
                                                        @error('agama')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>    
                                                    <div class="form-group col-md-6">
                                                        <label for="target_photo" class="fs-6 fw-semibold mb-2 ">Upload Foto</label>
                                                        <input class="form-control form-control-solid @error('target_photo') is-invalid @enderror"
                                                            name="target_photo" type="file" id="target_photo" value="{{ old ('target_photo') }}" accept="image/png, image/gif, image/jpeg">
                                                        @error('target_photo')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <input type="hidden" name="submit_type" id="submit_type" value="">
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <button class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
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
    <script>
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
                        $("#agama").val(val_agama);
                        $("#pendidikan").val(val_pendidikan);
                        $("#pekerjaan").val(val_pekerjaan);
                        $("#jenis_kelamin").val(val_jenis_kelamin);
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
