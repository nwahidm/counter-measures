<x-backoffice.layout.app-layout title="Penyadapan Laporan">
    @push('css')
    <style>
        thead {
            font-weight: bold;
        }
    </style>
    @endpush
    <x-backoffice.toolbar heading="Penyadapan Laporan" subheading="" breadcrumb="research-report" icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification />
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
                                        Penyadapan Laporan
                                    </div>
                                    <div class="card-toolbar d-none">
                                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                            <a href="javascript:void(0)" class="btn btn-dark btn-sm">Tambah</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body py-5">
                                    <div class="table-responsive">
                                        {{ $dataTable->table(['class' => 'table table-striped table-row-bordered gy-5
                                        gs-7 border rounded w-100 text-center', 'id' => 'data-table'], true) }}
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
    {{ $dataTable->scripts() }}
    @endpush
</x-backoffice.layout.app-layout>