<x-backoffice.layout.app-layout title="Tambah Delineation Scenario Relation">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Delineation Scenario Relation" subheading=""
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
                                    <form id="form" action="{{ route('close.delineation.scenario-relation.store') }}"
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
                                                    <div class="form-group col-md-4">
                                                        <label for="id_information_collection"
                                                               class="fs-6 fw-semibold mb-2">Pengumpulan Informasi</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_information_collection') is-invalid @enderror"
                                                            name="id_information_collection" id="id_information_collection"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Pengumpulan Informasi---</option>
                                                            <!-- @foreach ($information_collection as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('id_information_collection')) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach -->
                                                        </select>
                                                        @error('id_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    
                                                    <div class="form-group col-md-4">
                                                        <label for="id_information_collection"
                                                               class="fs-6 fw-semibold mb-2">Informasi Verifikasi</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_information_verification') is-invalid @enderror"
                                                            name="id_information_verification" id="id_information_verification"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Informasi Verifikasi---</option>
                                                            <!-- @foreach ($information_verification as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('id_information_verification')) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach -->
                                                        </select>
                                                        @error('id_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="id_information_collection"
                                                               class="fs-6 fw-semibold mb-2">Informasi Validasi</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('id_information_validation') is-invalid @enderror"
                                                            name="id_information_validation" id="id_information_validation"
                                                            data-control="select2" data-hide-search="true">
                                                            <option value="">---Pilih Informasi Validasi---</option>
                                                            <!-- @foreach ($information_validation as $row)
                                                                <option
                                                                    value="{{ $row['id'] }}"
                                                                    @if($row['id'] === old('id_information_validation')) selected @endif>{{ $row['text'] }}</option>
                                                            @endforeach -->
                                                        </select>
                                                        @error('id_sprint')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                

                                                    <div class="form-group col-md-4">
                                                        <label for="verification_date"
                                                               class="fs-6 fw-semibold mb-2 required">Subjek Utama</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('subjek_utama') is-invalid @enderror"
                                                               name="subjek_utama" id="subjek_utama"
                                                               value="{{ old('subjek_utama') }}">
                                                        @error('subjek_utama')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="verification_date"
                                                               class="fs-6 fw-semibold mb-2 required">Subjek Terkait</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('subjek_terkait') is-invalid @enderror"
                                                               name="subjek_terkait" id="subjek_terkait"
                                                               value="{{ old('subjek_terkait') }}">
                                                        @error('subjek_terkait')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                

                                                    <div class="form-group col-md-4">
                                                        <label for="verification_date"
                                                               class="fs-6 fw-semibold mb-2 required">Jenis Relasi</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('jenis_relasi') is-invalid @enderror"
                                                               name="jenis_relasi" id="jenis_relasi"
                                                               value="{{ old('jenis_relasi') }}">
                                                        @error('jenis_relasi')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="verification_date"
                                                               class="fs-6 fw-semibold mb-2 required">Kekuatan Relasi</label>
                                                        <input type="text"
                                                               class="form-control form-control-solid @error('kekuatan_relasi') is-invalid @enderror"
                                                               name="kekuatan_relasi" id="kekuatan_relasi"
                                                               value="{{ old('kekuatan_relasi') }}">
                                                        @error('kekuatan_relasi')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                
                                                </div>

                                                <div class="row mb-7">
                                                    <div class="form-group col-md-4">
                                                        <label for="tanggal_pencatatan"
                                                               class="fs-6 fw-semibold mb-2 required">Tanggal
                                                            Pencatatan</label>
                                                        <input type="date"
                                                               class="form-control form-control-solid @error('tanggal_pencatatan') is-invalid @enderror"
                                                               name="tanggal_pencatatan" id="tanggal_pencatatan"
                                                               value="{{ old('tanggal_pencatatan') }}">
                                                        @error('tanggal_pencatatan')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-7">
                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="telaahan"
                                                               class="fs-6 fw-semibold mb-2">Detail Relasi</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('detail_relasi') is-invalid @enderror"
                                                            name="detail_relasi" id="detail_relasi">{{ old('detail_relasi') }}</textarea>
                                                        @error('detail_relasi')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                  
                                                    <div class="form-group col-md-6">
                                                        <label for="telaahan"
                                                               class="fs-6 fw-semibold mb-2">Dampak Potensial</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('dampak_potensial') is-invalid @enderror"
                                                            name="dampak_potensial" id="dampak_potensial">{{ old('dampak_potensial') }}</textarea>
                                                        @error('dampak_potensial')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                   
                                                </div>

                                                <div class="row mb-7">
                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="telaahan"
                                                               class="fs-6 fw-semibold mb-2">Catatan Analisa</label>
                                                        <textarea
                                                            class="form-control form-control-solid @error('catatan_analisa') is-invalid @enderror"
                                                            name="catatan_analisa" id="catatan_analisa">{{ old('catatan_analisa') }}</textarea>
                                                        @error('catatan_analisa')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
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
        <script type="module">
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    $('.select').select2();

                    ClassicEditor
                        .create(document.querySelector('#detail_relasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    ClassicEditor
                        .create(document.querySelector('#dampak_potensial'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    
                    ClassicEditor
                        .create(document.querySelector('#catatan_analisa'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    
                }

               
            };
                $('#id_satker').change(function() {
                    $('#id_case').empty();
                    $('#id_information_collection').empty();
                    $('#id_information_verification').empty();
                    $('#id_information_validation').empty();

                    $('#id_case').append('<option value="">---Pilih Kasus---</option>');
                    $('#id_information_collection').append('<option value="">---Pilih Pengumpulan Informasi---</option>');
                    $('#id_information_verification').append('<option value="">---Pilih Informasi Verifikasi---</option>');
                    $('#id_information_validation').append('<option value="'+''+'">---Pilih Information Validation---</option>');

                    var id_satker = $(this).val();

                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected id_satker
                    $.ajax({
                        url: '/close/helper-case', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {satker_id: id_satker},
                        success: function(response) {
                            // Clear the existing options in the id_case select element
                            $('#id_case').empty();
                            $('#id_case').append('<option value="">---Pilih Kasus---</option>');

                            // Add the new options to the id_case select element
                            $.each(response, function(key, value) {
                                $('#id_case').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            // Re-initialize the select2 plugin on the id_case select element
                            $('#id_case').select2();
                        }
                    });
                });

                $('#id_case, #id_satker').change(function() {
                        var case_id = $(this).val();
                        $('#id_information_collection').empty();
                        $('#id_information_verification').empty();
                        $('#id_information_validation').empty();

                        $('#id_information_collection').append('<option value="">---Pilih Pengumpulan Informasi---</option>');
                        $('#id_information_verification').append('<option value="">---Pilih Informasi Verifikasi---</option>');
                        $('#id_information_validation').append('<option value="'+''+'">---Pilih Information Validation---</option>');

                        // Make an AJAX request to your controller to retrieve the list of cases based on the selected case_id
                        $.ajax({
                            url: '/close/helper-information-collection', // Replace this with the actual route to your controller
                            type: 'GET',
                            data: {case_id: case_id},
                            success: function(response) {
                                $('#id_information_collection').empty();

                                $('#id_information_collection').append('<option value="'+''+'">---Pilih Pengumpulan Informasi---</option>');
                                $.each(response, function(key, value) {
                                    $('#id_information_collection').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#id_information_collection').select2(); // Reinitialize select2
                            }
                        });
                });

                $('#id_information_collection, id_case, #id_satker').change(function() {
                        var information_collection_id = $(this).val();
                        $('#id_information_verification').empty();
                        $('#id_information_validation').empty();

                        $('#id_information_verification').append('<option value="">---Pilih Informasi Verifikasi---</option>');
                        $('#id_information_validation').append('<option value="'+''+'">---Pilih Information Validation---</option>');

                        // Make an AJAX request to your controller to retrieve the list of cases based on the selected case_id
                        $.ajax({
                            url: '/close/helper-information-verification', // Replace this with the actual route to your controller
                            type: 'GET',
                            data: {information_collection_id: information_collection_id},
                            success: function(response) {
                                $('#id_information_verification').empty();
                                
                                $('#id_information_verification').append('<option value="'+''+'">---Pilih Informasi Verifikasi---</option>');
                            
                                $.each(response, function(key, value) {
                                    $('#id_information_verification').append('<option value="' + value.id + '">' + value.text + '</option>');
                                });

                                $('#id_information_verification').select2(); // Reinitialize select2
                            }
                        });
                });

                $('#id_information_verification, #id_information_collection, id_case, #id_satker').change(function() {
                    var information_verification_id = $(this).val();

                    // Make an AJAX request to your controller to retrieve the list of cases based on the selected case_id
                    $.ajax({
                        url: '/close/helper-information-validation', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: {information_verification_id: information_verification_id},
                        success: function(response) {
                            $('#id_information_validation').empty();

                            $('#id_information_validation').append('<option value="'+''+'">---Pilih Information Validation---</option>');
                            
                            $.each(response, function(key, value) {
                                $('#id_information_validation').append('<option value="' + value.id + '">' + value.text + '</option>');
                            });

                            $('#id_information_validation').select2(); // Reinitialize select2
                        }
                    });
                });
        </script>
    @endpush
</x-backoffice.layout.app-layout>
