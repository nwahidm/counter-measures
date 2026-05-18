<x-backoffice.layout.app-layout title="Tambah Wawancara Jadwal">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Wawancara Jadwal" subheading="" breadcrumb="open-interview-jadwal-create"
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
                                    <form id="form" action="{{ route('open.interview.jadwal.store') }}" method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
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
                                                                    @if($row['id'] == auth()->user()?->satker?->id_satker) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_satker')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if(auth()->user()->user_roles != "superadmin")
                                                            <input type="hidden" name="id_satker" value="{{ auth()->user()?->satker?->id_satker }}">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
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
                                                    <div class="form-group col-md-12">
                                                        <label for="interview_nip"
                                                               class="fs-6 fw-semibold mb-2 required">NIP
                                                            Pewawancara</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('interview_nip') is-invalid @enderror"
                                                               name="interview_nip" id="interview_nip"
                                                               value="{{ old('interview_nip') }}">
                                                        @error('interview_nip')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group">
                                                        <button onclick="processCekNip()" id="buttonProcessNip" type="button" class="btn btn-primary btn-lg btn-block">
                                                            Cari Pewawancara
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="interviewer_name"
                                                               class="fs-6 fw-semibold mb-2 required">Nama
                                                            Pewawancara</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('interviewer_name') is-invalid @enderror"
                                                               name="interviewer_name" id="interviewer_name"
                                                               value="{{ old('interviewer_name') }}">
                                                        @error('interviewer_name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="interview_jabatan"
                                                               class="fs-6 fw-semibold mb-2 required">Jabatan
                                                            Pewawancara</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('interview_jabatan') is-invalid @enderror"
                                                               name="interview_jabatan" id="interview_jabatan"
                                                               value="{{ old('interview_jabatan') }}">
                                                        @error('interview_jabatan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="interview_pangkat"
                                                               class="fs-6 fw-semibold mb-2 required">Pangkat
                                                            Pewawancara</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('interview_pangkat') is-invalid @enderror"
                                                               name="interview_pangkat" id="interview_pangkat"
                                                               value="{{ old('interview_pangkat') }}">
                                                        @error('interview_pangkat')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="interviewer_schedule"
                                                               class="fs-6 fw-semibold mb-2 required">Jadwal
                                                            Pewawancara</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('interviewer_schedule') is-invalid @enderror"
                                                               name="interviewer_schedule" id="interviewer_schedule"
                                                               value="{{ old('interviewer_schedule') }}">
                                                        @error('interviewer_schedule')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="dasar"
                                                               class="fs-6 fw-semibold mb-2 required">Dasar Wawancara</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('dasar') is-invalid @enderror"
                                                               name="dasar" id="dasar"
                                                               value="{{ old('dasar') }}">
                                                        @error('dasar')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="tempat"
                                                               class="fs-6 fw-semibold mb-2 required">Tempat Wawancara</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('tempat') is-invalid @enderror"
                                                               name="tempat" id="tempat"
                                                               value="{{ old('tempat') }}">
                                                        @error('tempat')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label for="target_identity_number" class="fs-6 fw-semibold mb-2">NIK</label>
                                                    <input type="text" class="form-control" name="target_identity_number" id="target_identity_number" placeholder="" value="{{old('target_identity_number')}}" required="required">                                                        
                                                    <br>
                                                    <div class="form-group">
                                                        <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                            Cari
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row mb-12">
                                                    <div class="form-group col-md-6">
                                                        <label for="source_person_name" class="fs-6 fw-semibold mb-2 required">Nama Diwawancara</label>
                                                        <input type="text" class="form-control form-control-solid @error('source_person_name') is-invalid @enderror"
                                                               name="source_person_name" id="source_person_name" value="{{ old('source_person_name') }}">
                                                        @error('source_person_name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_gender" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                        <input type="text" class="form-control form-control-solid @error('target_gender') is-invalid @enderror"
                                                        name="target_gender" id="target_gender" value="{{ old('target_gender') }}">
                                                        @error('target_gender')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-12">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_occupation" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                        <input type="text" class="form-control form-control-solid @error('target_occupation') is-invalid @enderror"
                                                        name="target_occupation" id="target_occupation" value="{{ old('target_occupation') }}">
                                                        @error('target_occupation')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_education" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                        <input type="text" class="form-control form-control-solid @error('target_education') is-invalid @enderror"
                                                        name="target_education" id="target_education" value="{{ old('target_education') }}">
                                                        @error('target_education')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-12">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_religion" class="fs-6 fw-semibold mb-2">Agama</label>
                                                        <input type="text" class="form-control form-control-solid @error('target_religion') is-invalid @enderror"
                                                        name="target_religion" id="target_religion" value="{{ old('target_religion') }}">
                                                        @error('target_religion')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_alamat" class="fs-6 fw-semibold mb-2">Alamat</label>
                                                        <input type="text" class="form-control form-control-solid @error('target_alamat') is-invalid @enderror"
                                                        name="target_alamat" id="alamat" value="{{ old('target_alamat') }}">
                                                        @error('target_alamat')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="target_photo"
                                                               class="fs-6 fw-semibold mb-2">Foto Target</label>
                                                        <input
                                                            class="form-control form-control-solid @error('target_photo') is-invalid @enderror"
                                                            name="target_photo"
                                                            type="file"
                                                            id="target_photo" accept="image/png, image/gif, image/jpeg"
                                                            value="{{ old('target_photo') }}">
                                                        @error('target_photo')
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
        $('#cari').on('click',function(e){
                e.preventDefault();
                var nip = $('#nip').val();
                ambilnip( nip );
        });
        function processCekNik() {
            var $this = $('#buttonProcessNik');
            let nik = $('#target_identity_number').val();

            if (nik == '' || nik.length == 0 ) {
                alert("Silahkan isi NIK");
                return false;
            }

            BtnLoading($this);
            var nama_target = $("#source_person_name");

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
                        $("#source_person_name").val(val_nama);
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
    <script>
        function processCekNip() {
            var nip = $('#interview_nip').val();
    
            if (!nip) {
                alert('NIP tidak boleh kosong');
                return;
            }
    
            $.ajax({
                url: '/pegawai/' + nip,
                method: 'GET',
                success: function(response) {
                    if (response) {
                        $('#interviewer_name').val(response.nama || '');
                        $('#interview_pangkat').val(response.pangkat || '');
                        $('#interview_jabatan').val(response.jabatan || '');
                    } else {
                        alert('NIP salah atau data tidak ditemukan.');
                    }
                },
                error: function() {
                    alert('NIP salah atau data tidak ditemukan.');
                }
            });
        }
    </script>
    @endpush
</x-backoffice.layout.app-layout>
