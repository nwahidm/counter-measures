<x-backoffice.layout.app-layout title="Ubah Pegawai">

    <x-backoffice.toolbar heading="Ubah Pegawai" subheading="" breadcrumb="master-pegawai-edit" icon="fas fa-users">
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
                                    <form id="form" action="{{ route('master.pegawai.update', $data->id) }}" method="post" autocomplete="off">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group mb-7">
                                            <label for="nip" class="fs-6 fw-semibold mb-2 required">NIP</label>
                                            <input class="form-control form-control-solid" required="required" name="nip" type="text"
                                                id="nip" value="{{ $data->nip }}" readonly>
                                            <p class="text-danger">{{ $errors->first('nip') }}</p>
                                        </div>
                                        <div class="form-group mb-7">
                                            <label for="nama" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                            <input class="form-control form-control-solid" required="required" name="nama" type="text"
                                                id="nama" value="{{ $data->nama }}">
                                            <p class="text-danger">{{ $errors->first('nama') }}</p>
                                        </div>
                                        <div class="form-group mb-7">
                                            <label for="id_satker" class="fs-6 fw-semibold mb-2 required">Satuan Kerja</label>
                                            <select class="form-select form-select-solid select" required="required" name="id_satker" id="id_satker">
                                                <option value=''>--Pilih Satuan Kerja--</option>
                                                @foreach($satker as $item)
                                                    <option value="{{ $item['id'] }}" @if ($item['id'] == $data->id_satker)
                                                        selected
                                                    @endif>{{ $item['text'] }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger">{{ $errors->first('id_satker') }}</p>
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
            $('.select').select2();
        });
    </script>
    @endpush
</x-backoffice.layout.app-layout>