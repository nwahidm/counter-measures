<x-backoffice.layout.app-layout title="Manajemen User">
    @push('css')
        <style>
            thead {
                font-weight: bold;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Manajemen User" subheading="" breadcrumb="user" icon="fas fa-users">
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
                                <div class="card-header border-0 pt-6">
                                    <div class="card-title">
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                            <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">Create User</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        {{$dataTable->table(['class' => 'table align-middle table-row-dashed fs-6 gy-5', 'id' => 'user-table'], true)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        {{$dataTable->scripts()}}
    @endpush
    @push('js')
    <script>
        function deleteData(event, id) {
            event.preventDefault();
            let url = "{{ route('user.destroy', ':id') }}";
            url = url.replace(':id', id);

            Swal.fire({
                title: "Anda yakin?",
                text: "Akan menghapus user ini",
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
            }).then(function (result) {
                if (result.isConfirmed) {
                    let submit = $("#deleteForm").attr('action', url);
                    submit.submit()
                }
            });
        }
    </script>
    @endpush
</x-backoffice.layout.app-layout>