<x-backoffice.layout.app-layout title="Manajemen Permission">
    @push('css')
        <style>
            thead {
                font-weight: bold;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Manajemen Permission" subheading="" breadcrumb="manage-role-permission" icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification/>
        </div>
    </x-backoffice.toolbar>

    <div class="app-container container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="row g-5 g-xl-8">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        {{ $dataTable->table(['class' => 'table table-striped table-row-bordered gy-5 gs-7 border rounded w-100 text-center', 'id' => 'data-table'], true) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <form method="POST" action="{{ route('manage-role.permission.store') }}">
                                    @csrf
                                    <div class="card-header">
                                        <h3 class="card-title">Create Permission</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name"
                                                class="fs-6 fw-semibold mb-2 required">{{ __('Nama Permission') }}</label>
                                            <input type="text" class="form-control form-control-solid" name="name"
                                                required />
                                        </div>

                                        <button class="btn btn-primary btn-sm mt-5" type="submit">
                                            {{ __('Simpan') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <form id="deleteForm" action="#" method="post">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush
    @push('js')
        <script>
            function deleteData(event) {
                event.preventDefault();
                const id = event.currentTarget.getAttribute('data-id');
                let url = "{{ route('manage-role.permission.destroy', ':id') }}";
                url = url.replace(':id', id);
                Swal.fire({
                    title: "Anda yakin?",
                    text: "Akan menghapus permission ini",
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: "Yes!",
                    cancelButtonText: 'No',
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: 'btn btn-danger'
                    },
                    reverseButtons: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        let submit = $("#deleteForm").attr('action', url);
                        submit.submit()
                    }
                });
            }
        </script>
    @endpush
</x-backoffice.layout.app-layout>
