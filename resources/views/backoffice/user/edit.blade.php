<x-backoffice.layout.app-layout title="Ubah User">
    <x-backoffice.toolbar heading="Ubah User" subheading="" breadcrumb="user-edit" icon="fas fa-users">
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
                                    <form id="form" action="{{ $isProfile ? route('update.profile', $data->id) : route('user.update', $data->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group mb-7">
                                            <label for="satker" class="fs-6 fw-semibold mb-2 required">Satker</label>
                                            <select class="form-select form-control-solid" name="satker" id="satker" data-control="select2" required="required">
                                            {!! optSatkerByRole() !!}
                                            </select>
                                            <p class="text-danger">{{ $errors->first('satker') }}</p>
                                        </div>

                                        <input type="hidden" name="isProfile" value="{{ $isProfile }}">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="nik" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                    <input class="form-control form-control-solid" required="required" name="nik" type="text"
                                                        id="nik" value="{{ $data->nik }}">
                                                    <p class="text-danger">{{ $errors->first('nik') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="nip" class="fs-6 fw-semibold mb-2 required">NIP</label>
                                                    <input class="form-control form-control-solid" required="required" name="nip" type="text"
                                                        id="nip" value="{{ $data->nip }}">
                                                    <p class="text-danger">{{ $errors->first('nip') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="name" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                    <input class="form-control form-control-solid" required="required" name="name" type="text"
                                                        id="name" value="{{ $data->name }}">
                                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="username" class="fs-6 fw-semibold mb-2 required">Username</label>
                                                    <input class="form-control form-control-solid" required="required" name="username" type="text"
                                                        id="username" value="{{ $data->username }}">
                                                    <p class="text-danger">{{ $errors->first('username') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="email" class="fs-6 fw-semibold mb-2 required">Email</label>
                                                    <input class="form-control form-control-solid" required="required" name="email" type="email"
                                                        id="email" value="{{ $data->email }}">
                                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="password" class="fs-6 fw-semibold mb-2">Password</label>
                                                    <input class="form-control form-control-solid" name="password" type="password"
                                                        id="password">
                                                    <p class="text-primary">*Kosongkan jika tidak ingin mengganti</p>
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
                                                        <option value="{{ $row->name }}" {{ $row->name == ($data->roles->first() ? $data->roles->first()->name : '') ? 'selected' : '' }}>{{ $row->name }}</option>
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
                                                    <p class="text-primary">*Kosongkan jika tidak ingin mengganti</p>
                                                    <p class="text-danger">{{ $errors->first('avatar') }}</p>
                                                    <img class="image-preview" style="max-width: 100px; margin-right: 10px; margin-bottom: 10px;" src="{{ $data->profile ? asset($data->profile) : asset('assets/images/avatar/user.png') }}" alt="Preview">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-7">
                                                    <label for="nomor" class="fs-6 fw-semibold mb-2 required">Status</label>
                                                    <div class="custom-switches-stacked mt-2">
                                                        <div class="form-check form-switch mb-4">
                                                            <input name="is_active" value="1" type="checkbox"
                                                                role="switch" class="form-check-input" {{ $data->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-semibold text-gray-800">Aktif</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="form-group mb-7">
                                            <label for="nomor" class="fs-6 fw-semibold mb-2 required">Permission</label>
                                            <div class="custom-switches-stacked mt-2">
                                                <div class="row">
                                                    @foreach ($permissions as $key => $permission)
                                                    <div class="col-md-4">
                                                        <div class="form-check form-switch mb-4">
                                                            <input name="permission[]" value="{{ $permission->name }}" type="checkbox"
                                                                role="switch" class="form-check-input"
                                                                {{ $data->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                            <label
                                                                class="form-check-label fw-semibold text-gray-800">{{ $permission->name }}</label>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div> --}}

                                        <button class="btn btn-primary waves-effect waves-classic waves-effect waves-classic mt-3"
                                                type="submit">Simpan
                                        </button>
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
            $(document).ready(function () {
                $("#satker").val('{{ $data->id_satker }}').trigger("change");
            });
        </script>
    @endpush
</x-backoffice.layout.app-layout>
