<x-backoffice.layout.app-layout title="Tambah Pihak Terkait">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Pihak Terkait" subheading="" breadcrumb="close-observation-connected-identity-create"
                          icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <div class="d-flex align-items-center w-25">
                <x-backoffice.notification/>
            </div>
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
                                    <form id="form" action="{{ route('close.observation.connected-identity.store') }}" method="post"
                                          enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="card-body">
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
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
                                                                    @if($row['id'] == auth()->user()?->satker?->id_satker) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('satker_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    @if(auth()->user()->user_roles != "superadmin")
                                                        <input type="hidden" name="satker_id" value="{{ auth()->user()?->satker?->id_satker }}">
                                                    @endif
                                                </div>
                                                <div class="row mb-7">
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
                                                                    @if($row['id'] === old('case_id')) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('case_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="surat_perintah_id"
                                                               class="fs-6 fw-semibold mb-2">Surat perintah</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('surat_perintah_id') is-invalid @enderror"
                                                            name="surat_perintah_id" id="surat_perintah_id"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Surat Perintah---</option>
                                                        </select>
                                                        @error('surat_perintah_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="information_collection_id"
                                                               class="fs-6 fw-semibold mb-2">Sumber Informasi</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('information_collection_id') is-invalid @enderror"
                                                            name="information_collection_id" id="information_collection_id"
                                                            data-control="select2" data-hide-search="false">
                                                            <option value="">---Pilih Informasi---</option>
                                                        </select>
                                                        @error('information_collection_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="potensi_aght_id"
                                                               class="fs-6 fw-semibold mb-2">AGHT Yang Ada</label>
                                                            <select
                                                               class="form-select form-select-solid select @error('potensi_aght_id') is-invalid @enderror"
                                                               name="potensi_aght_id" id="potensi_aght_id"
                                                               data-control="select2" data-hide-search="false">
                                                               <option value="">---Pilih AGHT---</option>
                                                           </select>
                                                        @error('potensi_aght_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold mb-3 mt-3">BIODATA TARGET:</label> <hr>
                                                <div class="form-group col-md-12">
                                                    <label for="nama_target" class="fs-6 fw-semibold mb-2">NIK</label>
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
                                                        <label for="target_name" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                        <input type="text" class="form-control form-control-solid @error('target_name') is-invalid @enderror"
                                                               name="target_name" id="target_name" value="{{ old('target_name') }}">
                                                        @error('target_name')
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
                                                        <label for="image" class="fs-6 fw-semibold mb-2 ">Foto (Multiple Input Max. 2MB Per Foto)</label>
                                                        <input id="image" type="file" class="form-control image-input" name="image[]" accept=".jpg,.jpeg,.png" autocomplete="off" multiple>
                                                            <p class="text-danger">{{ $errors->first('image') }}</p>
                                                            <div class="image-preview container"></div>
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
        <script type="module">
            // CKEDITOR SETTING
            document.addEventListener('DOMContentLoaded', () => {
                ClassicEditor
                    .create(document.querySelector('textarea.ckeditor1'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                });
            });
        </script>
        <script>
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

                    // ajax get case list
                    $('#satker_id').change(function() {
                        $('#case_id').empty();
                        $('#surat_perintah_id').empty();
                        $('#information_collection_id').empty();

                        $('#case_id').append('<option value="">---Pilih Kasus---</option>');
                        $('#surat_perintah_id').append('<option value="">---Pilih Surat perintah---</option>');
                        $('#information_collection_id').append('<option value="">---Pilih Informasi---</option>');

                        var satker_id = $(this).val();

                        // Make an AJAX request to your controller to retrieve the list of cases based on the selected satker_id
                        $.ajax({
                            url: '/close/helper-case', // Replace this with the actual route to your controller
                            type: 'GET',
                            data: {satker_id: satker_id},
                            success: function(response) {
                                // Clear the existing options in the case_id select element
                                $('#case_id').empty();
                                $('#case_id').append('<option value="">---Pilih Kasus---</option>');

                                // Add the new options to the case_id select element
                                $.each(response, function(key, value) {
                                    $('#case_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                // Re-initialize the select2 plugin on the case_id select element
                                $('#case_id').select2();
                            }
                        });
                    });

                    // ajax get list sprint
                    $('#case_id').change(function() {
                        var case_id = $(this).val();

                        // Make an AJAX request to your controller to retrieve the list of cases based on the selected satker_id
                        $.ajax({
                            url: '/close/helper-sprint', // Replace this with the actual route to your controller
                            type: 'GET',
                            data: {case_id: case_id},
                            success: function(response) {
                                $('#surat_perintah_id').empty();
                                $('#surat_perintah_id').append('<option value="">---Pilih Surat Perintah---</option>');

                                $.each(response, function(key, value) {
                                    $('#surat_perintah_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#surat_perintah_id').select2();
                            }
                        });
                    });

                    // ajax get collect info
                    $('#surat_perintah_id', '#case_id').change(function() {
                        var surat_perintah_id = $(this).val();

                        $.ajax({
                            url: '/close/helper-collect-info', 
                            type: 'GET',
                            data: {surat_perintah_id: surat_perintah_id},
                            success: function(response) {
                                $('#information_collection_id').empty();
                                $('#information_collection_id').append('<option value="">---Pilih Informasi---</option>');

                                $.each(response, function(key, value) {
                                    $('#information_collection_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#information_collection_id').select2();
                            }
                        });
                    });

                    // ajax get aght
                    $('#information_collection_id', '#surat_perintah_id', '#case_id').change(function() {
                        var information_collection_id = $(this).val();

                        $.ajax({
                            url: '/close/helper-threat', 
                            type: 'GET',
                            data: {information_collection_id: information_collection_id},
                            success: function(response) {
                                $('#potensi_aght_id').empty();
                                $('#potensi_aght_id').append('<option value="">---Pilih AGHT---</option>');

                                $.each(response, function(key, value) {
                                    $('#potensi_aght_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#potensi_aght_id').select2();
                            }
                        });
                    });

                    const input = $('.image-input');
                    const preview = $('.image-preview');

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

                }
            };
        </script>
    @endpush
</x-backoffice.layout.app-layout>
