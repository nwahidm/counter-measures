<x-backoffice.layout.app-layout title="Ubah Kasus">

    <x-backoffice.toolbar heading="Ubah Kasus" subheading="" breadcrumb="open-case-edit" icon="fas fa-users">
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
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h4></h4>
                                </div>
                                <div class="card-body">
                                    <form id="form" action="{{ route('open.case.update', $data->id) }}" method="post" autocomplete="off" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="card ">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">DETAIL KASUS:</label> <hr>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="nama_kasus" class="fs-6 fw-semibold mb-2 required">Nama Kasus</label>
                                                        <input type="text" class="form-control" name="nama_kasus" id="nama_kasus" placeholder="" value="{{$data->nama_kasus}}" required="required">
                                                        <p class="text-danger">{{ $errors->first('nama_kasus') }}</p>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="tanggal_kasus" class="fs-6 fw-semibold mb-2 required">Tanggal Kasus</label>
                                                        <input type="date" class="form-control" name="tanggal_kasus" id="tanggal_kasus" value="{{$data->tanggal_kasus?->isoFormat('YYYY-MM-DD') ?? \Carbon\Carbon::now('Asia/Jakarta')->toDateString()}}"required="required">
                                                        <p class="text-danger">{{ $errors->first('tanggal_kasus') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="deskripsi_kasus" class="fs-6 fw-semibold mb-2">Deksripsi Kasus</label>
                                                        <textarea class="form-control " name="deskripsi_kasus" type="text" rows='4'
                                                            placeholder="Lengkapi Data...">{{$data->deskripsi_kasus}}</textarea>
                                                        <p class="text-danger">{{ $errors->first('deskripsi_kasus') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">BIODATA TARGET:</label> <hr>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="nama_target" class="fs-6 fw-semibold mb-2">NIK</label>
                                                        <input type="text" class="form-control" name="nik" id="nik" placeholder="" value="{{old('nik', $data->no_identitas)}}" required="required">                                                        
                                                        <br>
                                                        <!-- <div class="form-group">
                                                            <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                                Cari
                                                            </button>
                                                        </div> -->
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="nama_target" class="fs-6 fw-semibold mb-2 required">Nama Lengkap Target</label>
                                                        <input type="text" class="form-control" name="nama_target" id="nama_target" placeholder="" value="{{old('nama_target', $data->nama_target)}}" required="required">
                                                        <p class="text-danger">{{ $errors->first('nama_target') }}</p>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="agama" class="fs-6 fw-semibold mb-2">Agama</label>
                                                        <input type="text" class="form-control" name="agama" id="agama" placeholder="" value="{{old('agama', $data->agama)}}" required="required">
                                                        <p class="text-danger">{{ $errors->first('agama') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_education" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                            <div class="form-group col-md-12">
                                                                <input type="text" class="form-control" name="pendidikan" id="pendidikan" placeholder="" value="{{old('pendidikan', $data->pendidikan)}}" required="required">
                                                                <p class="text-danger">{{ $errors->first('pendidikan') }}</p>
                                                            </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_occupation" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                            <div class="form-group col-md-12">
                                                                <input type="text" class="form-control" name="pekerjaan" id="pekerjaan" placeholder="" value="{{old('pekerjaan', $data->pekerjaan)}}" required="required">
                                                                <p class="text-danger">{{ $errors->first('pekerjaan') }}</p>
                                                            </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_education" class="fs-6 fw-semibold mb-2">Jenis Kelamin</label>
                                                            <div class="form-group col-md-12">
                                                                <input type="text" class="form-control" name="jenis_kelamin" id="jenis_kelamin" placeholder="" value="{{old('jenis_kelamin', $data->jenis_kelamin)}}" required="required">
                                                                <p class="text-danger">{{ $errors->first('jenis_kelamin') }}</p>
                                                            </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="alamat" class="fs-6 fw-semibold mb-2">Alamat</label>
                                                        <div class="form-group col-md-12">
                                                            <textarea type="text" class="form-control" name="alamat" id="alamat" placeholder="" value="{{old('alamat', $data->alamat)}}" required="required"></textarea>
                                                            <p class="text-danger">{{ $errors->first('alamat') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="no_hp_target" class="fs-6 fw-semibold mb-2">No HP Target</label>
                                                            <div class="form-group col-md-12">
                                                                <input type="text" class="form-control" name="no_hp_target" id="no_hp_target" placeholder="" value="{{old('jenis_kelamin', $data->no_hp_target)}}" required="required">
                                                                <p class="text-danger">{{ $errors->first('no_hp_target') }}</p>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="form-group col-md-12">
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
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="foto_dokumen" class="fs-6 fw-semibold mb-2">Foto Dokumen (Multiple Input Max. 2MB Per Foto) Kosongkan jika tidak diubah</label>
                                                        <input id="foto_dokumen" type="file" class="form-control image-input" name="foto_dokumen[]" accept=".jpg,.jpeg,.png" autocomplete="off" multiple>
                                                        <p class="text-danger">{{ $errors->first('foto_dokumen') }}</p>
                                                        <div class="doc-image-preview-container">
                                                            @foreach ($foto_dokumens as $image)
                                                                <img class="image-preview" style="max-width: 350px; margin-right: 10px; margin-bottom: 10px;" src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}" alt="Preview">
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-dark waves-effect waves-classic waves-effect waves-classic mt-5" type="submit">Simpan</button>
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
    <script src="{{ asset('vendor/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendor/validation/messages_id.js') }}"></script>
    <script src="{{ asset('vendor/validation/form-validation.js') }}"></script>

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
                    console.log("AJAX response received: ", response); // Debugging
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

            // docs image
            const inputDoc = $('#foto_dokumen');
            const previewDoc = $('.doc-image-preview-container');

            // Set the input change event to trigger the preview update
            inputDoc.on('change', function() {
                // Clear the preview container
                previewDoc.empty();

                if (inputDoc.val()) {
                    // Show the preview container
                    previewDoc.show();
                    // Add a border to the preview container
                    previewDoc.css('border', '1px solid #ccc');
                    previewDoc.css('border-radius', '5px');
                    // Justify the images in the container
                    previewDoc.css('display', 'flex');
                    previewDoc.css('justify-content', 'space-between');
                    previewDoc.css('padding', '5px');
                    previewDoc.css('flex-wrap', 'wrap');
                }
                else {
                    // Hide the preview container
                    previewDoc.hide();
                    // Remove the border from the preview container
                    previewDoc.css('border', 'none');
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
                        previewDoc.append(img);
                    }
                    else {
                        // Display an error message if the file size is too large
                        alert('File size should not exceed 2 MB');
                        // Clear the input field
                        inputDoc.val('');
                        // Break the loop
                        break;
                    }
                }
            });
        });
    </script>
    @endpush
</x-backoffice.layout.app-layout>