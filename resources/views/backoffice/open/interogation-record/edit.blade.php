<x-backoffice.layout.app-layout title="Ubah Data Catatan Interogasi">
    @push('css')
    <style>
        thead {
            background: #f5f4f8;
            text-align: center;
        }
    </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Data Catatan Interogasi" subheading="" breadcrumb="interogation-record-edit"
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
                                        action="{{ route('open.data.interrog-record.update', $data->id_interogation_record) }}"
                                        method="post" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="satker_id"
                                                               class="fs-6 fw-semibold mb-2 required">Satuan Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('satker_id') is-invalid @enderror"
                                                            name="satker_id" id="satker_id"
                                                            data-control="select2" data-hide-search="false"
                                                            @if(auth()->user()->user_roles != "superadmin") disabled @endif>
                                                            <option value="">---Pilih Satuan Kerja---</option>
                                                            @foreach ($satker as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] == $data->satker_id) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('satker_id')
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
                                                        <label for="nomor_surat"
                                                            class="fs-6 fw-semibold mb-2 required">Nomor Surat Perintah</label>
                                                        <input class="form-control form-control-solid @error('nomor_surat') is-invalid @enderror"
                                                            name="nomor_surat" type="text"
                                                            id="nomor_surat" value="{{ old('letter_number', $data->letter_number) }}">
                                                            @error('nomor_surat')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-7">
                                                            <label for="perihal"
                                                                class="fs-6 fw-semibold mb-2 required">Perihal</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('perihal') is-invalid @enderror"
                                                                name="perihal" id="perihal"
                                                                value="{{ old('perihal', $data->perihal) }}">
                                                                @error('perihal')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="tanggal_surat"
                                                            class="fs-6 fw-semibold mb-2 required">Tanggal Surat</label>
                                                        <input class="form-control form-control-solid @error('pekerjaan') is-invalid @enderror"
                                                            name="tanggal_surat" type="date"
                                                            id="tanggal_surat" value="{{ old('letter_date', $data->letter_date) }}">
                                                            @error('tanggal_surat')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="jaksa" class="fs-6 fw-semibold mb-2 required">Jaksa Peminta Keterangan</label>
                                                        <select class="form-control form-control-solid select" id="pegawai" name="pegawai[]" multiple="multiple">
                                                            @foreach($listPegawai as $pegawai)
                                                                @if($data->jaksa)
                                                                    <option value="{{ $pegawai['nip'] }}" {{ in_array($pegawai['nip'], json_decode($data->jaksa)) ? 'selected' : '' }}>{{ $pegawai['text'] }}</option>
                                                                @else
                                                                    <option value="{{ $pegawai['nip'] }}" >{{ $pegawai['text'] }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @error('jaksa')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <hr>
                                                <h4>DATA TARGET</h4>
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
                                                        name="jenis_kelamin" id="jenis_kelamin" value="{{ old('jenis_kelamin', $data->target_gender) }}">
                                                        @error('jenis_kelamin')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="pekerjaan" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                        <input type="text" class="form-control form-control-solid @error('pekerjaan') is-invalid @enderror"
                                                        name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan', $data->target_occupation) }}">
                                                        @error('pekerjaan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="pendidikan" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                        <input type="text" class="form-control form-control-solid @error('pendidikan') is-invalid @enderror"
                                                        name="pendidikan" id="pendidikan" value="{{ old('pendidikan', $data->target_education) }}">
                                                        @error('pendidikan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="agama" class="fs-6 fw-semibold mb-2">Agama</label>
                                                        <input type="text" class="form-control form-control-solid @error('agama') is-invalid @enderror"
                                                        name="agama" id="agama" value="{{ old('agama', $data->target_religion) }}">
                                                        @error('agama')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="alamat" class="fs-6 fw-semibold mb-2">Alamat</label>
                                                        <div class="form-group col-md-12">
                                                            <textarea type="text" class="form-control" name="alamat" id="alamat" placeholder="" value="{{old('alamat', $data->target_address)}}" required="required"></textarea>
                                                            <p class="text-danger">{{ $errors->first('alamat') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="born_place" class="fs-6 fw-semibold mb-2">Tempat Lahir</label>
                                                        <input type="text" class="form-control form-control-solid @error('born_place') is-invalid @enderror"
                                                        name="born_place" id="born_place" value="{{ old('born_place', $data->born_place) }}">
                                                        @error('born_place')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="born_date" class="fs-6 fw-semibold mb-2">Tanggal Lahir</label>
                                                        <input type="date" class="form-control form-control-solid @error('born_date') is-invalid @enderror"
                                                        name="born_date" id="born_date" value="{{ old('born_date', $data->born_date) }}">
                                                        @error('born_date')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="phone_number" class="fs-6 fw-semibold mb-2">No. Telp/HP</label>
                                                        <input type="text" class="form-control form-control-solid @error('phone_number') is-invalid @enderror"
                                                        name="phone_number" id="phone_number" value="{{ old('phone_number', $data->phone_number) }}">
                                                        @error('phone_number')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="nationality" class="fs-6 fw-semibold mb-2">Kebangsaan</label>
                                                        <input type="text" class="form-control form-control-solid @error('nationality') is-invalid @enderror"
                                                        name="nationality" id="nationality" value="{{ old('nationality', $data->nationality) }}">
                                                        @error('nationality')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="hasil" class="fs-6 fw-semibold mb-2">Tanya Jawab</label>
                                                        <div class="form-group col-md-12">
                                                            <textarea type="text" class="form-control" name="hasil" id="hasil" tabindex="-1" placeholder="" value="{{old('hasil', $data->hasil)}}" ></textarea>
                                                            <p class="text-danger">{{ $errors->first('hasil') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-7">
                                                            <label for="image" class="fs-6 fw-semibold mb-2">Foto (Multiple Input Max. 2MB Per Foto) Kosongkan jika tidak diubah</label>
                                                            <input id="image" type="file" class="form-control image-input" name="image[]" accept=".jpg,.jpeg,.png" autocomplete="off" multiple>
                                                            <p class="text-danger">{{ $errors->first('image') }}</p>
                                                            <div class="image-preview-container">
                                                                @foreach ($images as $image)
                                                                    <img class="image-preview" style="max-width: 350px; margin-right: 10px; margin-bottom: 10px;" src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}" alt="Preview">
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                    {{-- <div class="form-group col-md-4">
                                                        <label for="upload_berita_acara"
                                                            class="fs-6 fw-semibold mb-2 required">Upload File
                                                            Hasil yang dicapai (pdf)</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('upload_berita_acara') is-invalid @enderror"
                                                                name="upload_berita_acara" type="file"
                                                                id="upload_berita_acara"
                                                                value="{{ old('upload_berita_acara') }}">
                                                            @if($data->berita_acara_path)
                                                            <a class="btn btn-dark"
                                                                href="{{ route('open.data.interrog-record.download-file', encrypt($data->berita_acara_path)) }}"
                                                                id="button-addon-file_surat_referensi">
                                                                <span class="fa fa-file-download"></span> Unduh
                                                            </a>
                                                            @endif
                                                        </div>
                                                        @error('upload_berita_acara')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->berita_acara_path)
                                                        <input
                                                            class="form-control form-control-solid @error('temp_upload_berita_acara') is-invalid @enderror"
                                                            name="temp_upload_berita_acara" type="hidden"
                                                            id="temp_upload_berita_acara"
                                                            value="{{ old('temp_upload_berita_acara', $data->berita_acara_path) }}">
                                                        @error('temp_upload_berita_acara')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @endif
                                                    </div> --}}
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
                                                            onclick="setSubmitType('update_and_finish')"
                                                            type="submit">Simpan dan Selesai
                                                        </button>                                                       
                                                    </div>
                                                    
                                                    {{-- <div class="col-md-3">
                                                        <button
                                                            class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                            type="submit">Update
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
        function checkSubmitType() {
            var submitType = document.getElementById('submit_type').value;
            if (submitType === '') {
                // Ensure a default value is set if none was provided
                document.getElementById('submit_type').value = 'update';
            }
            return true; // Proceed with form submission
        }

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
                $('#pegawai').select2({
                    placeholder: 'Pilih Pegawai',
                    allowClear: true,
                    multiple: true
                });

                $('#satker_id').change(function() {
                        $('#case_id').empty();

                        var satker_id = $(this).val();

                        // Make an AJAX request to your controller to retrieve the list of cases based on the selected satker_id
                        $.ajax({
                            url: '/open/helper-case', // Replace this with the actual route to your controller
                            type: 'GET',
                            data: {satker_id: satker_id},
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
                .create(document.querySelector('#hasil'),{
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
        $(document).ready(function(){
            const input = $('#image');
            const preview = $('.image-preview-container');

            // Set the input change event to trigger the preview update
            input.on('change', function() {
                // Clear the preview container
                preview.empty();

                if (input.val()) {
                    // Show the preview container
                    preview.show();
                    // Add a border to the preview container
                    preview.css('border', '1px solid #ccc');
                    preview.css('border-radius', '5px');
                    // Justify the images in the container
                    preview.css('display', 'flex');
                    preview.css('justify-content', 'space-between');
                    preview.css('padding', '5px');
                    preview.css('flex-wrap', 'wrap');
                }
                else {
                    // Hide the preview container
                    preview.hide();
                    // Remove the border from the preview container
                    preview.css('border', 'none');
                }

                // Loop through each selected file
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const image_url = URL.createObjectURL(file);

                    // Check if the file size is less than or equal to 2 MB
                    if (file.size <= 2000000) {
                        // Create a new image element and set its source to the image URL
                        const img = $('<img>');
                        img.attr('src', image_url);
                        img.css('max-width', '350px');
                        img.css('margin-right', '10px');
                        img.css('margin-bottom', '10px');

                        // Append the image element to the preview container
                        preview.append(img);
                    }
                    else {
                        // Display an error message if the file size is too large
                        alert('File size should not exceed 2 MB');
                        // Clear the input field
                        input.val('');
                        // Break the loop
                        break;
                    }
                }
            });

           
        });
    </script>

    @endpush
</x-backoffice.layout.app-layout>