<x-backoffice.layout.app-layout title="Tambah Wilayah Satker">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush

    <x-backoffice.toolbar heading="Tambah Wilayah Satker" subheading="" breadcrumb="master-wilayah-satker-create" icon="fas fa-users">
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
                                    <div class="card-header-action">
                                        <button type="button" name="add" id="add" class="btn btn-primary btn-sm">Tambah Form</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form id="form" action="{{ route('master.wilayah-satker.store') }}" method="post">
                                        @csrf
                                        <div class="form-group mb-7">
                                            <label for="satker" class="fs-6 fw-semibold mb-2">Satker</label>
                                            <select class="form-select form-select-solid" name="satker" id="satker" data-control="select2" data-placeholder="Pilih Satker">
                                                {!! optSatker() !!}
                                            </select>
                                            <p class="text-danger">{{ $errors->first('satker') }}</p>
                                        </div>

                                        <div class="form-group mb-7">
                                            <label for="wilayah" class="fs-6 fw-semibold mb-2">Wilayah</label>
                                            <select class="form-select form-select-solid" name="wilayah[]" id="wilayah" data-control="select2" data-placeholder="Pilih Wilayah" multiple>
                                            </select>
                                            <p class="text-danger">{{ $errors->first('wilayah') }}</p>
                                        </div>
                                        <button class="btn btn-primary waves-effect waves-classic waves-effect waves-classic" type="submit">Simpan</button>
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
        $.ajax({
            url: "{{ route('master.wilayah.listWilayah') }}",
            type: "GET",
            success: function(data){
                $('#wilayah').empty()
                $('#wilayah').append('<option value="">--Pilih--</option>')
                $.each(data, function(key, item) {
                    $('#wilayah').append('<option value="'+item.id+'">'+item.text+'</option>')
                });

                $(`#wilayah`).select2({
                    placeholder: 'Silahkan pilih'
                });
            }
        });
    });
    </script>
    @endpush
</x-backoffice.layout.app-layout>