<x-backoffice.layout.app-layout title="Tambah User">
    <x-backoffice.toolbar heading="Tambah User" subheading="" breadcrumb="user-create" icon="fas fa-users">
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
                                    <form id="form" action="{{ route('user.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="form-group mb-7">
                                            <label for="satker" class="fs-6 fw-semibold mb-2 required">Satker</label>
                                            <select class="form-select form-select-solid" name="satker" id="satker" data-control="select2" required="required">
                                            {!! optSatkerByRole() !!}
                                            </select>
                                            <p class="text-danger">{{ $errors->first('satker') }}</p>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="nik" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                    <input class="form-control form-control-solid" required="required" name="nik" type="text"
                                                        id="nik" value="{{ old('nik') }}">
                                                    <p class="text-danger">{{ $errors->first('nik') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="nip" class="fs-6 fw-semibold mb-2 required">NIP</label>
                                                    <input class="form-control form-control-solid" required="required" name="nip" type="text"
                                                        id="nip" value="{{ old('nip') }}">
                                                    <p class="text-danger">{{ $errors->first('nip') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="name" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                    <input class="form-control form-control-solid" required="required" name="name" type="text"
                                                        id="name" value="{{ old('name') }}">
                                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="username" class="fs-6 fw-semibold mb-2 required">Username</label>
                                                    <input class="form-control form-control-solid" required="required" name="username" type="text"
                                                        id="username" value="{{ old('username') }}">
                                                    <p class="text-danger">{{ $errors->first('username') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="email" class="fs-6 fw-semibold mb-2 required">Email</label>
                                                    <input class="form-control form-control-solid" required="required" name="email" type="email"
                                                        id="email" value="{{ old('email') }}">
                                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="password" class="fs-6 fw-semibold mb-2 required">Password</label>
                                                    <input class="form-control form-control-solid" required="required" name="password" type="password"
                                                        id="password">
                                                    <p class="text-danger">{{ $errors->first('password') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="role" class="fs-6 fw-semibold mb-2 required">Role User</label>
                                                    <select class="form-select form-select-solid" name="role" id="role" data-control="select2" data-hide-search="true" required="required">
                                                        <option value="">None</option>
                                                        @foreach ($role as $row)
                                                        <option value="{{ $row->name }}">{{ $row->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">{{ $errors->first('role') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="avatar" class="fs-6 fw-semibold mb-2">Avatar</label>
                                                    <input class="form-control form-control-solid" name="avatar" type="file"
                                                        id="avatar" value="{{ old('avatar') }}">
                                                    <p class="text-danger">{{ $errors->first('avatar') }}</p>
                                                </div>
                                            </div>
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
    @endpush
</x-backoffice.layout.app-layout>
