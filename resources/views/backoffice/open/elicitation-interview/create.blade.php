<x-backoffice.layout.app-layout title="TAMBAH ELICITATION INTERVIEW">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="TAMBAH ELICITATION INTERVIEW" subheading="" breadcrumb="elicitation-interview-create"
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
                                    <form id="form" action="{{ route('open.data.elicit-interview.store') }}" method="post"
                                          enctype="multipart/form-data" autocomplete="off"  onsubmit="return checkSubmitType();">
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
                                                                    @if($row['id'] === old('id_case')) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_case')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="nip"
                                                               class="fs-6 fw-semibold mb-2 required">Interviewer NIP</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('nip') is-invalid @enderror"
                                                               name="nip" id="nip"
                                                               value="{{ old('nip') }}">
                                                        @error('nip')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="interviewer_name"
                                                               class="fs-6 fw-semibold mb-2 required">Interviewer Name</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('interviewer_name') is-invalid @enderror"
                                                               name="interviewer_name" id="interviewer_name"
                                                               value="{{ old('interviewer_name') }}">
                                                        @error('interviewer_name')
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
                                                        <label for="pangkat"
                                                               class="fs-6 fw-semibold mb-2 required">Interviewer Pangkat</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('pangkat') is-invalid @enderror"
                                                               name="pangkat" id="pangkat"
                                                               value="{{ old('pangkat') }}">
                                                        @error('pangkat')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="interviewer_schedule"
                                                               class="fs-6 fw-semibold mb-2 required">Interviewer Schedule</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('interviewer_schedule') is-invalid @enderror"
                                                               name="interviewer_schedule" id="interviewer_schedule"
                                                               value="{{ old('interviewer_schedule') }}">
                                                        @error('interviewer_schedule')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <hr>
                                                <h4>BIODATA TARGET</h4>
                                                <hr>                                                
                                                <div class="form-group col-md-12">
                                                    <label for="nama_target" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                    <input type="text" class="form-control" name="nik" id="nik" placeholder="" value="{{old('nik')}}" >                                                        
                                                    <br>
                                                    <div class="form-group">
                                                        <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                            Cari
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="source_person_name" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                        <input type="text" class="form-control form-control-solid @error('source_person_name') is-invalid @enderror"
                                                               name="source_person_name" id="nama_target" value="{{ old('source_person_name') }}">
                                                        @error('source_person_name')
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
                                                <input type="hidden" name="submit_type" id="submit_type" value="">
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
                                                        <label for="alamat" class="fs-6 fw-semibold mb-2">Alamat</label>
                                                        <div class="form-group col-md-12">
                                                            <textarea type="text" class="form-control" name="alamat" id="alamat" placeholder="" value="{{old('alamat')}}" ></textarea>
                                                            <p class="text-danger">{{ $errors->first('alamat') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="target_photo" class="fs-6 fw-semibold mb-2 ">Foto (png/jpeg/jpg)</label>
                                                            <input class="form-control form-control-solid"
                                                                name="target_photo" type="file" id="target_photo" accept=".jpg, .jpeg, .png"
                                                                value="{{ old('target_photo') }}">
                                                            <p class="text-danger">{{ $errors->first('target_photo') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="interview_result_path" class="fs-6 fw-semibold mb-2 required">Hasil Interview Elisitasi (pdf)</label>
                                                            <input class="form-control form-control-solid" required="required"
                                                                name="interview_result_path" type="file" id="interview_result_path" accept="application/pdf"
                                                                value="{{ old('interview_result_path') }}">
                                                            <p class="text-danger">{{ $errors->first('interview_result_path') }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-7">
                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="upload_video_elicitation"
                                                               class="fs-6 fw-semibold mb-2 required">Video Wawancara Elisitasi</label>
                                                        <input
                                                            class="form-control form-control-solid @error('upload_video_elicitation') is-invalid @enderror"
                                                            name="upload_video_elicitation"
                                                            type="file"
                                                            id="upload_video_elicitation"
                                                            accept="video/*"
                                                            value="{{ old('upload_video_elicitation') }}">
                                                        @error('upload_video_elicitation')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row">
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
        function processCekNip() {
            var nip = $('#nip').val();
    
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
                        $('#pangkat').val(response.pangkat || '');
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
    <script>
            function setSubmitType(type) {
                document.getElementById('submit_type').value = type;
            }

            function checkSubmitType() {
                var submitType = document.getElementById('submit_type').value;
                if (submitType === '') {
                    // Ensure a default value is set if none was provided
                    document.getElementById('submit_type').value = 'update';
                }
                return true; // Proceed with form submission
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
        <script>
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

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
    @endpush
</x-backoffice.layout.app-layout>
