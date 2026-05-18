<x-backoffice.layout.app-layout title="UBAH DATA ELICITATION INTERVIEW">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="UBAH DATA ELICITATION INTERVIEW" subheading="" breadcrumb="elicitation-interview-edit"
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
                                    <form id="form" action="{{ route('open.data.elicit-interview.update', $data->id_elicitation_interview_result) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off" onsubmit="return checkSubmitType();">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card ">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold d-none">DETAIL KASUS:</label>
                                                <hr class="d-none">
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
                                                                <option value="{{ $row['id'] }}"
                                                                        @if($row['id'] == $data->satker_id) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
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
                                                        <label for="nip"
                                                               class="fs-6 fw-semibold mb-2 required">Interviewer NIP</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('nip') is-invalid @enderror"
                                                               name="nip" id="nip"
                                                               value="{{ $data->nip }}">
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
                                                               value="{{ $data->interviewer_name }}">
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
                                                               value="{{ $data->pangkat }}">
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
                                                               value="{{$data->interviewer_schedule?->isoFormat('YYYY-MM-DD')}}">
                                                        @error('interviewer_schedule')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <hr>
                                                <h4>DATA TARGET</h4>
                                                <hr>
                                                <div class="form-group col-md-12">
                                                    <label for="nama_target" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                    <input type="text" class="form-control" name="nik" id="nik" placeholder="" value="{{ $data->target_identity_number }}" >                                                        
                                                    <br>
                                                    <div class="form-group">
                                                        <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                            Cari
                                                        </button>
                                                    </div>
                                                </div>
                                                <br>
                                                {{-- <div class="row mb-7">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="source_person_name" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                            <input class="form-control form-control-solid" 
                                                                name="source_person_name" type="text" id="source_person_name"
                                                                value="{{ $data->source_person_name }}">
                                                            <p class="text-danger">{{ $errors->first('source_person_name') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="target_identity_number" class="fs-6 fw-semibold mb-2 required">NIP/NIK</label>
                                                            <input class="form-control form-control-solid" 
                                                                name="target_identity_number" type="text" id="target_identity_number"
                                                                value="{{ $data->target_identity_number }}">
                                                            <p class="text-danger">{{ $errors->first('target_identity_number') }}</p>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="source_person_name" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                        <input type="text" class="form-control form-control-solid @error('source_person_name') is-invalid @enderror"
                                                               name="source_person_name" id="nama_target" value="{{ $data->source_person_name }}">
                                                        @error('source_person_name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="jenis_kelamin" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                        <input type="text" class="form-control form-control-solid @error('jenis_kelamin') is-invalid @enderror"
                                                        name="jenis_kelamin" id="jenis_kelamin" value="{{ $data->target_gender }}">
                                                        @error('jenis_kelamin')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                {{-- <div class="row mb-7">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="target_identity_number_type" class="fs-6 fw-semibold mb-2 required">Tipe Target
                                                                (NIK/NIP)</label>
                                                            <select class="form-select form-select-solid select" name="target_identity_number_type"
                                                                id="target_identity_number_type" data-control="select2" data-hide-search="true"
                                                                >
                                                                <option value="NIK" @if('NIK' === old('tipe_target', $data->target_identity_number_type)) selected @endif>NIK</option>
                                                                <option value="NIP" @if('NIP' === old('tipe_target', $data->target_identity_number_type)) selected @endif>NIP</option>
                                                            </select>
                                                            <p class="text-danger">{{ $errors->first('target_identity_number_type') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="target_gender" class="fs-6 fw-semibold mb-2 required">Jenis
                                                                Kelamin</label>
                                                            <select class="form-select form-select-solid select" name="target_gender"
                                                                id="target_gender" data-control="select2" data-hide-search="true"
                                                                >
                                                                <option value="L" @if('L' === old('jenis_kelamin', $data->target_gender)) selected @endif>Laki-laki</option>
                                                                <option value="P" @if('P' === old('jenis_kelamin', $data->target_gender)) selected @endif>Perempuan</option>
                                                            </select>
                                                            <p class="text-danger">{{ $errors->first('target_gender') }}</p>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="pekerjaan" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                        <input type="text" class="form-control form-control-solid @error('pekerjaan') is-invalid @enderror"
                                                        name="pekerjaan" id="pekerjaan" value="{{ $data->target_occupation }}">
                                                        @error('pekerjaan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="pendidikan" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                        <input type="text" class="form-control form-control-solid @error('pendidikan') is-invalid @enderror"
                                                        name="pendidikan" id="pendidikan" value="{{ $data->target_education }}">
                                                        @error('pendidikan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="agama" class="fs-6 fw-semibold mb-2">Agama</label>
                                                        <input type="text" class="form-control form-control-solid @error('agama') is-invalid @enderror"
                                                        name="agama" id="agama" value="{{ $data->target_religion }}">
                                                        @error('agama')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="alamat" class="fs-6 fw-semibold mb-2">Alamat</label>
                                                        <div class="form-group col-md-12">
                                                            <textarea type="text" class="form-control" name="alamat" id="alamat" placeholder="" >{{ $data->target_address }}</textarea>
                                                            <p class="text-danger">{{ $errors->first('alamat') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="row mb-7">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="target_religion" class="fs-6 fw-semibold mb-2 required">Agama</label>
                                                            <select class="form-select form-select-solid select" name="target_religion" id="target_religion"
                                                            data-control="select2" data-hide-search="true" >
                                                            <option value="">---Pilih Agama---</option>
                                                                @foreach ($agama as $row)
                                                                    <option value="{{ $row['text'] }}"
                                                                            @if($row['text']=== old('target_religion', $data->target_religion)) selected @endif>
                                                                        {{ $row['text'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <p class="text-danger">{{ $errors->first('target_religion') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="target_education" class="fs-6 fw-semibold mb-2 required">Pendidikan</label>
                                                            <select class="form-select form-select-solid select" name="target_education" id="target_education"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Pendidikan---</option>
                                                                @foreach ($pendidikan as $row)
                                                                    <option value="{{ $row['text'] }}"
                                                                            @if($row['text']=== old('target_education', $data->target_education)) selected @endif>
                                                                        {{ $row['text'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <p class="text-danger">{{ $errors->first('target_education') }}</p>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                {{-- <div class="row mb-7">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-7">
                                                            <label for="target_occupation" class="fs-6 fw-semibold mb-2 required">Pekerjaan</label>
                                                            <select class="form-select form-select-solid select" name="target_occupation" id="target_occupation"
                                                            data-control="select2" data-hide-search="true" >
                                                            <option value="">---Pilih Pekerjaan---</option>
                                                                @foreach ($pekerjaan as $row)
                                                                    <option value="{{ $row['text'] }}"
                                                                            @if($row['text']=== old('target_occupation', $data->target_occupation)) selected @endif>
                                                                        {{ $row['text'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <p class="text-danger">{{ $errors->first('target_occupation') }}</p>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="row mb-7">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="target_photo"
                                                                   class="fs-6 fw-semibold mb-2 ">Foto</label>
                                                            <input
                                                                class="form-control form-control-solid @error('target_photo') is-invalid @enderror"
                                                                name="target_photo"
                                                                type="file"
                                                                id="target_photo"
                                                                placeholder="Lengkapi Foto...">
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-2">
                                                        @if($data->target_photo)
                                                            <img class="img-thumbnail"
                                                                 src="{{ asset('storage/' . $data->target_photo) }}"
                                                                 alt="Foto">
                                                        @endif
                                                    </div> --}}
                                                    <div class="form-group col-md-6">
                                                        <label for="interview_result_path"
                                                                class="fs-6 fw-semibold mb-2 required">Upload File
                                                                Hasil Interview (pdf)</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('interview_result_path') is-invalid @enderror"
                                                                name="interview_result_path"
                                                                type="file"
                                                                id="interview_result_path"
                                                                value="{{ old('interview_result_path') }}">
                                                            @if($data->interview_result_path)
                                                                <a class="btn btn-dark"
                                                                    href="{{ route('open.data.elicit-interview.download-file', encrypt($data->interview_result_path)) }}"
                                                                    id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('interview_result_path')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->interview_result_path)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_interview_result') is-invalid @enderror"
                                                                name="temp_interview_result"
                                                                type="hidden"
                                                                id="temp_interview_result"
                                                                value="{{ old('temp_interview_result', $data->interview_result_path) }}">
                                                                
                                                            @error('temp_interview_result')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-7">
                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="upload_video_wawancara"
                                                            class="fs-6 fw-semibold mb-2 required">Video
                                                            Wawancara Elisitasi</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('upload_video_elicitation') is-invalid @enderror"
                                                                name="upload_video_elicitation" type="file"
                                                                id="upload_video_elicitation"
                                                                value="{{ old('upload_video_elicitation') }}">
                                                            @if($data->interview_video_path)
                                                            <a class="btn btn-dark"
                                                                href="{{ route('open.research.warrant.download-file', encrypt($data->interview_video_path)) }}"
                                                                id="button-addon-file_surat_referensi">
                                                                <span class="fa fa-file-download"></span> Unduh
                                                            </a>
                                                            @endif
                                                        </div>
                                                        @error('upload_video_elicitation')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->upload_video_elicitation)
                                                        <input
                                                            class="form-control form-control-solid @error('temp_upload_video_elicitation') is-invalid @enderror"
                                                            name="temp_upload_video_elicitation" type="hidden"
                                                            id="temp_upload_video_elicitation"
                                                            value="{{ old('temp_upload_video_elicitation', $data->upload_video_elicitation) }}">
                                                        @error('temp_upload_video_elicitation')
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
