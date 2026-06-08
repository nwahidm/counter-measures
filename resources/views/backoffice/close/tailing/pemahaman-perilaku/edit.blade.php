<x-backoffice.layout.app-layout title="Ubah Pemahaman Perilaku Pembuntutan">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Pemahaman Perilaku Pembuntutan" subheading=""
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
                                    <form id="form"
                                          action="{{ route('close.tailing.pemahaman-perilaku.update', $data->id) }}"
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
                                                            name="id_satker_label" id="id_satker_label"
                                                            data-control="select2" data-hide-search="true" 
                                                            @if(auth()->user()->user_roles != "superadmin") disabled @endif>
                                                            <option value="">---Pilih Satker---</option>
                                                            @foreach ($satker as $row)
                                                                <option value="{{ $row['id'] }}"
                                                                        @if($row['kode_satker'] === old('kode_satker', $data->kode_satker)) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="id_satker" value="{{ $data->kode_satker }}">
                                                        <input type="hidden" name="id" value="{{ $data->id }}">
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
                                                <h4>BIODATA TARGET</h4>
                                                <hr>                                                
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
                                                        <label for="target_name"
                                                               class="fs-6 fw-semibold mb-2 required">Nama Target</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('target_name') is-invalid @enderror"
                                                               name="target_name" id="target_name"
                                                               value="{{ old('target_name', $data->target_name) }}">
                                                        @error('target_name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="jenis_kelamin" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                        <input type="text" class="form-control form-control-solid @error('jenis_kelamin') is-invalid @enderror"
                                                        name="jenis_kelamin" id="jenis_kelamin" value="{{ old('jenis_kelamin', $data->target_gender) }}">
                                                        @error('jenis_kelamin')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="pekerjaan" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                        <input type="text" class="form-control form-control-solid @error('pekerjaan') is-invalid @enderror"
                                                        name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan', $data->target_occupation) }}">
                                                        @error('pekerjaan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="pendidikan" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                        <input type="text" class="form-control form-control-solid @error('pendidikan') is-invalid @enderror"
                                                        name="pendidikan" id="pendidikan" value="{{ old('pendidikan', $data->target_education) }}">
                                                        @error('pendidikan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>                                                    
                                                    <div class="form-group col-md-4">
                                                        <label for="agama" class="fs-6 fw-semibold mb-2">Agama</label>
                                                        <input type="text" class="form-control form-control-solid @error('agama') is-invalid @enderror"
                                                        name="agama" id="agama" value="{{ old('agama', $data->target_religion) }}">
                                                        @error('agama')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                 
                                                <div class="row mb-7">
                                                   <div class="form-group col-md-6">
                                                        <label for="perilaku_tercatat"
                                                               class="fs-6 fw-semibold mb-2 required">Perilaku Tercatat</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('perilaku_tercatat') is-invalid @enderror"
                                                            name="perilaku_tercatat" id="perilaku_tercatat">{{ old('perilaku_tercatat', $data->perilaku_tercatat) }}</textarea>
                                                        @error('aktivitas_rutin')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                     <div class="form-group col-md-6">
                                                        <label for="aktivitas_rutin"
                                                               class="fs-6 fw-semibold mb-2">Aktivitas Rutin</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('aktivitas_rutin') is-invalid @enderror"
                                                            name="aktivitas_rutin" id="aktivitas_rutin">{{ old('aktivitas_rutin', $data->aktivitas_rutin) }}</textarea>
                                                        @error('aktivitas_rutin')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                 <div class="row mb-7">
                                                   <div class="form-group col-md-6">
                                                        <label for="hubungan_sosial"
                                                               class="fs-6 fw-semibold mb-2 ">Perilaku Tercatat</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('hubungan_sosial') is-invalid @enderror"
                                                            name="hubungan_sosial" id="hubungan_sosial">{{ old('hubungan_sosial', $data->hubungan_sosial) }}</textarea>
                                                        @error('hubungan_sosial')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                     <div class="form-group col-md-6">
                                                        <label for="prediksi_perilaku"
                                                               class="fs-6 fw-semibold mb-2">Prediksi Perilaku</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('prediksi_perilaku') is-invalid @enderror"
                                                            name="prediksi_perilaku" id="prediksi_perilaku">{{ old('prediksi_perilaku', $data->prediksi_perilaku) }}</textarea>
                                                        @error('prediksi_perilaku')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="pemahaman_perilaku_video_upload"
                                                               class="fs-6 fw-semibold mb-2 required">Upload File Video</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('pemahaman_perilaku_video_upload') is-invalid @enderror"
                                                                name="pemahaman_perilaku_video_upload"
                                                                type="file"
                                                                id="pemahaman_perilaku_video_upload"
                                                                value="{{ old('pemahaman_perilaku_video_upload') }}">
                                                            @if($data->pemahaman_perilaku_video_upload)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.tailing.pemahaman-perilaku.download-file', encrypt($data->pemahaman_perilaku_video_upload)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('pemahaman_perilaku_video_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->pemahaman_perilaku_video_upload)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_pemahaman_perilaku_video_upload') is-invalid @enderror"
                                                                name="temp_pemahaman_perilaku_video_upload"
                                                                type="hidden"
                                                                id="temp_pemahaman_perilaku_video_upload"
                                                                value="{{ old('temp_pemahaman_perilaku_video_upload', $data->pemahaman_perilaku_video_upload) }}">
                                                            @error('temp_pemahaman_perilaku_video_upload')
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
                        .create(document.querySelector('#perilaku_tercatat'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#aktivitas_rutin'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#hubungan_sosial'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    ClassicEditor
                        .create(document.querySelector('#prediksi_perilaku'),{
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
                        $("#target_name").val(val_nama);
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
