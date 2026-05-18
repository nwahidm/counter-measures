<x-backoffice.layout.app-layout title="Manajemen Role Permission">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Manajemen Permission" subheading="" breadcrumb="manage-role-rolepermission" icon="fas fa-users">
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
                                    <form action="{{ route('manage-role.rolepermission.index') }}" method="GET">
                                        <div class="form-group">
                                            <label class="fs-6 fw-semibold mb-2">Roles</label>
                                            <div class="input-group">
                                                <select name="role" class="form-select form-select-solid">
                                                    @foreach ($roles as $value)
                                                    <option value="{{ $value }}"
                                                        {{ request()->get('role') == $value ? 'selected':'' }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-warning">Check!</button>
                                                </span>
                                            </div>
                                        </div>
                                    </form>
                                    @if (!empty($permissions))
                                    <form action="{{ route('manage-role.rolepermission.setRolePermission', request()->get('role')) }}"
                                        method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group mt-5">
                                            <div>
                                                <ul class="nav nav-tabs mb-1" id="pills-tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active show" id="tab_1" data-toggle="pill" href="#tab_1"
                                                            role="tab" aria-controls="pills-tab_1"
                                                            aria-selected="true"><strong>Permissions</strong></a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content" id="pills-tabContent">
                                                    <div class="tab-pane fade active show" id="tab_1_content" role="tabpanel"
                                                        aria-labelledby="pills-tab_1-tab">
                                                        <div class="custom-switches-stacked mt-2">
                                                            <div class="row">
                                                                @foreach ($permissions as $key => $permission)
                                                                <div class="col-md-4">
                                                                    <div class="form-check form-switch mb-4">
                                                                        <input name="permission[]" value="{{ $permission }}" type="checkbox" role="switch" class="form-check-input" {{ in_array($permission, $hasPermission) ? 'checked':'' }}>
                                                                        <label class="form-check-label fw-semibold text-gray-800">{{ $permission }}</label>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end mt-5">
                                            <button class="btn btn-primary btn-sm">
                                                <i class="fa fa-share-square"></i> Set Permission
                                            </button>
                                        </div>
                                    </form>
                                    @endif
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
    </script>
    @endpush
</x-backoffice.layout.app-layout>