<x-backoffice.layout.app-layout title="Ubah Wilayah Satker">
    <x-backoffice.toolbar heading="Ubah Wilayah Satker" subheading="" breadcrumb="master-wilayah-satker-edit" icon="fas fa-users">
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
                                    <form id="form" action="{{ route('master.wilayah-satker.update', ['wilayah_satker' => $data->id]) }}" method="post" autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group mb-7">
                                            <label for="satker" class="fs-6 fw-semibold mb-2">Satker</label>
                                            <select class="form-select form-select-solid" name="satker" id="satker" data-control="select2" data-placeholder="Pilih Satker">
                                                {!! optSatker() !!}
                                            </select>
                                            <p class="text-danger">{{ $errors->first('satker') }}</p>
                                        </div>

                                        <div class="form-group mb-7">
                                            <label for="wilayah" class="fs-6 fw-semibold mb-2">Wilayah</label>
                                            <select class="form-select form-select-solid" name="wilayah" id="wilayah" data-control="select2" data-placeholder="Pilih Wilayah">
                                                
                                            </select>
                                            <p class="text-danger">{{ $errors->first('wilayah') }}</p>
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
            $("#satker").val('{{ $data->id_satker }}').trigger("change");
            $.ajax({
                url: "{{ route('master.wilayah.listWilayah') }}",
                type: "GET",
                success: function(data){
                    $('#wilayah').empty()
                    $('#wilayah').append('<option value="">--Pilih--</option>')
                    $.each(data, function(key, item) {
                        $('#wilayah').append('<option value="'+item.id+'">'+item.text+'</option>')
                    });

                    $("#wilayah").val('{{ $data->id_wilayah }}').trigger("change");
                    $(`#wilayah`).select2({
                        placeholder: 'Silahkan pilih'
                    });
                }
            });
        });
    </script>
    @endpush
</x-backoffice.layout.app-layout>