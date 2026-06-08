<x-backoffice.layout.app-layout title="Ubah Lokasi Target Penyurupan">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Lokasi Target Penyurupan" subheading="" breadcrumb="edit-intrusion-target-loc"
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
                                    <form id="form" action="{{ route('close.intrusion.target-loc.update', $data->id) }}"
                                          method="post"
                                          enctype="multipart/form-data" autocomplete="off">
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
                                                            data-control="select2" data-hide-search="false" disabled>
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
                                                                    @if($row['id'] === $data->case_id) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('case_id')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <label class="fs-3 fw-bold mb-3 mt-3">BIODATA TARGET:</label> <hr>
                                                <div class="form-group col-md-12">
                                                    <label for="target_identity_number" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                    <input type="text" class="form-control" name="target_identity_number" id="target_identity_number" placeholder="" value="{{$data->target_identity_number}}" required="required">                                                        
                                                    <br>
                                                    <!-- <div class="form-group">
                                                        <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                            Cari
                                                        </button>
                                                    </div> -->
                                                    <br>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_name" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                        <input type="text" class="form-control form-control-solid @error('target_name') is-invalid @enderror"
                                                               name="target_name" id="target_name" value="{{ $data->target_name }}">
                                                        @error('target_name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_gender" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                        <input type="text" class="form-control form-control-solid @error('target_gender') is-invalid @enderror"
                                                        name="target_gender" id="target_gender" value="{{ $data->target_gender }}">
                                                        @error('target_gender')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="target_occupation" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                            <input type="text" class="form-control form-control-solid @error('target_occupation') is-invalid @enderror"
                                                            name="target_occupation" id="target_occupation" value="{{ $data->target_occupation }}">
                                                            @error('target_occupation')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="target_education" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                            <input type="text" class="form-control form-control-solid @error('target_education') is-invalid @enderror"
                                                            name="target_education" id="target_education" value="{{ $data->target_education }}">
                                                            @error('target_education')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_religion" class="fs-6 fw-semibold mb-2">Agama</label>
                                                        <input type="text" class="form-control form-control-solid @error('target_religion') is-invalid @enderror"
                                                        name="target_religion" id="target_religion" value="{{ $data->target_religion }}">
                                                        @error('target_religion')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="lokasi_target"
                                                               class="fs-6 fw-semibold mb-2 required">Alamat Target / Latitude Longitude</label>
                                                        <textarea
                                                               class="form-control form-control-solid @error('lokasi_target') is-invalid @enderror"
                                                               name="lokasi_target" id="lokasi_target"
                                                               value="{{ $data->lokasi_target }}"></textarea>
                                                        @error('lokasi_target')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                </div>
                                                <div class="row mb-12">
                                                    <div class="form-group col-md-12">
                                                        <label for="deskripsi_lokasi"
                                                               class="fs-6 fw-semibold mb-2 required">Deskripsi Lokasi</label>
                                                               <textarea class="form-control form-control-solid @error('deskripsi_lokasi') is-invalid @enderror" name="deskripsi_lokasi" id="deskripsi_lokasi"
                                                               rows="4">{!! $data->deskripsi_lokasi !!}</textarea>
                                                        </textarea>
                                                        @error('deskripsi_lokasi')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="lokasi_target_upload"
                                                               class="fs-6 fw-semibold mb-2 ">Upload File Lokasi</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('lokasi_target_upload') is-invalid @enderror"
                                                                name="lokasi_target_upload"
                                                                type="file"
                                                                id="lokasi_target_upload">
                                                            @if($data->lokasi_target_upload)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('close.intrusion.target-loc.download-file', encrypt($data->lokasi_target_upload)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('lokasi_target_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->lokasi_target_upload)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_lokasi_target_upload') is-invalid @enderror"
                                                                name="temp_lokasi_target_upload"
                                                                type="hidden"
                                                                id="temp_lokasi_target_upload"
                                                                value="{{ $data->lokasi_target_upload }}">
                                                            @error('temp_lokasi_target_upload')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-6">
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
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                        <label for="video_upload"
                                                               class="fs-6 fw-semibold mb-2 ">File Video</label>
                                                        <div class="input-group">
                                                            <input
                                                                class="form-control form-control-solid @error('video_upload') is-invalid @enderror"
                                                                name="video_upload"
                                                                type="file"
                                                                id="video_upload"
                                                                value="{{ old('video_upload') }}">
                                                            @if($data->video_upload)
                                                                <a class="btn btn-dark"
                                                                   href="{{ route('open.research.warrant.download-file', encrypt($data->video_upload)) }}"
                                                                   id="button-addon-file_surat_referensi">
                                                                    <span class="fa fa-file-download"></span> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                        @error('video_upload')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @if($data->video_upload)
                                                            <input
                                                                class="form-control form-control-solid @error('temp_video_upload') is-invalid @enderror"
                                                                name="temp_video_upload"
                                                                type="hidden"
                                                                id="temp_video_upload"
                                                                value="{{ old('temp_video_upload', $data->video_upload) }}">
                                                            @error('temp_video_upload')
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
            $('#cari').on('click',function(e){
                    e.preventDefault();
                    var nip = $('#nip').val();
                    ambilnip( nip );
            });
            function processCekNik() {
                var $this = $('#buttonProcessNik');
                let target_identity_number = $('#target_identity_number').val();

                if (target_identity_number == '' || target_identity_number.length == 0 ) {
                    alert("Silahkan isi NIK");
                    return false;
                }

                BtnLoading($this);
                var target_name = $("#target_name");

                console.log("Starting AJAX request with NIK: ", target_identity_number); // Debugging

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('open.kependudukan.ceknik', ['nik' => 'NIK_PLACEHOLDER']) }}".replace('NIK_PLACEHOLDER', target_identity_number),
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                        // console.log("AJAX response received: ", response); // Debugging
                        if (response.status = 'sukses') {
                            var val_nama = response.nama;
                            var val_target_religion = response.agama;
                            var val_target_education = response.pendidikan;
                            var val_target_occupation = response.pekerjaan;
                            var val_target_gender = response.jenis_kelamin;
                            var val_lokasi_target = response.alamat;
                            // console.log("Setting target_name to: ", val_nama); // Debugging
                            $("#target_name").val(val_nama);
                            $("#target_religion").val(val_target_religion);
                            $("#target_education").val(val_target_education);
                            $("#target_occupation").val(val_target_occupation);
                            $("#target_gender").val(val_target_gender);
                            $("#lokasi_target").val(val_lokasi_target);
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

                    // ajax get case list
                $('#satker_id').change(function() {
                    var satker_id = $(this).val();

                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected satker_id
                    $.ajax({
                        url: '/close/helper-case', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {satker_id: satker_id},
                        success: function(response) {
                            // Clear the existing options in the case_id select element
                            $('#case_id').empty();

                            // Add the new options to the case_id select element
                            $.each(response, function(key, value) {
                                $('#case_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            // Re-initialize the select2 plugin on the case_id select element
                            $('#case_id').select2();
                        }
                    });
                });

                    const input = $('.image-input');
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

                    ClassicEditor
                    .create(document.querySelector('#deskripsi_lokasi'),{
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
