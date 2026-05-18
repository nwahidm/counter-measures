<x-backoffice.layout.app-layout title="Ubah Satker">
    <x-backoffice.toolbar heading="Ubah Satker" subheading="" breadcrumb="master-satker-edit" icon="fas fa-users">
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
                                    <form id="form" action="{{ route('master.satker.update', $data->id_satker) }}" method="post" autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group mb-7">
                                            <label for="parent_id" class="fs-6 fw-semibold mb-2">Parent</label>
                                            <select class="form-select form-select-solid" name="parent_id" id="parent_id" data-control="select2" data-placeholder="Pilih Parent">
                                                {!! optSatker($data->parent_id) !!}
                                            </select>
                                            <p class="text-danger">{{ $errors->first('parent_id') }}</p>
                                        </div>

                                        <div class="form-group mb-7">
                                            <label for="kode_satker" class="fs-6 fw-semibold mb-2 required">Kode Satker</label>
                                            <input class="form-control form-control-solid" required="required" name="kode_satker" type="text"
                                                id="kode_satker" value="{{ $data->kode_satker }}">
                                            <p class="text-danger">{{ $errors->first('kode_satker') }}</p>
                                        </div>
                                        
                                        <div class="form-group mb-7">
                                            <label for="nama_satker" class="fs-6 fw-semibold mb-2 required">Nama Satker</label>
                                            <input class="form-control form-control-solid" required="required" name="nama_satker" type="text"
                                                id="nama_satker" value="{{ $data->nama_satker }}">
                                            <p class="text-danger">{{ $errors->first('nama_satker') }}</p>
                                        </div>

                                        <div class="form-group mb-7">
                                            <label for="tipe_satker" class="fs-6 fw-semibold mb-2 required">Tipe Satker</label>
                                            <select class="form-select form-select-solid" name="tipe_satker" id="tipe_satker" required>
                                                @foreach (\App\Helpers\DataHelper::tipeSatker() as $keyTipe => $valTipe)
                                                    <option value="{{ $keyTipe }}">{{ $valTipe }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger">{{ $errors->first('tipe_satker') }}</p>
                                        </div>

                                        <div class="form-group mb-7">
                                            <label for="alamat_satker" class="fs-6 fw-semibold mb-2 required">Alamat Satker</label>
                                            <input class="form-control form-control-solid" required="required" name="alamat_satker" type="text"
                                                id="alamat_satker" value="{{ $data->alamat_satker }}">
                                            <p class="text-danger">{{ $errors->first('alamat_satker') }}</p>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-7">
                                                    <label for="provinsi" class="fs-6 fw-semibold mb-2 required">Provinsi</label>
                                                    <select class="form-select form-select-solid" name="provinsi" id="provinsi" required>
                                                    </select>
                                                    <p class="text-danger">{{ $errors->first('provinsi') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-7">
                                                    <label for="kota" class="fs-6 fw-semibold mb-2 required">Kota/Kabupaten</label>
                                                    <select class="form-select form-select-solid" name="kota" id="kota" required>
                                                    </select>
                                                    <p class="text-danger">{{ $errors->first('kota') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-7">
                                                    <label for="telp_satker" class="fs-6 fw-semibold mb-2">Telpon Satker</label>
                                                    <input class="form-control form-control-solid" name="telp_satker" type="text"
                                                        id="telp_satker" value="{{ $data->telp_satker }}">
                                                    <p class="text-danger">{{ $errors->first('telp_satker') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-7">
                                                    <label for="website_satker" class="fs-6 fw-semibold mb-2">Website Satker</label>
                                                    <input class="form-control form-control-solid" name="website_satker" type="text"
                                                        id="website_satker" value="{{ $data->website_satker }}">
                                                    <p class="text-danger">{{ $errors->first('website_satker') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-7">
                                                    <label for="lat" class="fs-6 fw-semibold mb-2">Latitude</label>
                                                    <input class="form-control form-control-solid" name="lat" type="text"
                                                        id="lat" value="{{ $data->lat }}">
                                                    <p class="text-danger">{{ $errors->first('lat') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-7">
                                                    <label for="long" class="fs-6 fw-semibold mb-2">Longitude</label>
                                                    <input class="form-control form-control-solid" name="long" type="text"
                                                        id="long" value="{{ $data->long }}">
                                                    <p class="text-danger">{{ $errors->first('long') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary waves-effect waves-classic waves-effect waves-classic mt-5" type="submit">Simpan</button>
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
    $(document).ready(function(){  

        $("#tipe_satker").val('{{ $data->tipe_satker }}').trigger("change");

        $.ajax({
            url: "{{ route('master.wilayah.listProvinsi') }}",
            type: "GET",
            success: function(data){
                $('#provinsi').empty()
                $('#provinsi').append('<option value="">--Pilih--</option>')
                $.each(data, function(key, item) {
                    $('#provinsi').append('<option data-id="'+item.id+'" value="'+item.text+'">'+item.text+'</option>')
                });

                $("#provinsi").val('{{ $data->provinsi }}').trigger("change");
                $(`#provinsi`).select2({
                    placeholder: 'Silahkan pilih'
                });
            }
        });

        $('#provinsi').on('change', function() {
            $.ajax({
                url: "{{ route('master.wilayah.listKota') }}",
                type: "GET",
                data: { provinsi: $(this).find(':selected').data('id') },
                success: function(data){
                    $('#kota').empty()
                    $('#kota').append('<option value="">--None--</option>')
                    $.each(data, function(key, item) {
                        $('#kota').append('<option data-id="'+item.id+'" value="'+item.text+'">'+item.text+'</option>')
                    });

                    $("#kota").val('{{ $data->city }}').trigger("change");
                    $(`#kota`).select2({
                        placeholder: 'Silahkan pilih'
                    });
                }
            });
        });
        });
    </script>
    @endpush
</x-backoffice.layout.app-layout>