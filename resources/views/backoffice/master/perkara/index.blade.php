<x-backoffice.layout.app-layout title="Master Perkara">
    @push('css')
        <style>
            thead {
                font-weight: bold;
            }
        </style>
    @endpush
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar d-flex flex-stack py-4 py-lg-8">
            <div class="d-flex flex-grow-1 flex-stack flex-wrap gap-2 mb-n10" id="kt_toolbar">
                <x-backoffice.section-header heading="Master Perkara" breadcrumb="master-perkara" icon="fas fa-users" />
                <div class="d-flex align-items-center w-25">
                    <x-backoffice.notification />
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                    </div>
                    <div class="card-toolbar">
                    </div>
                </div>
                <div class="card-body py-5">
                    <div class="table-responsive">
                        {{ $dataTable->table(['class' => 'table table-striped table-row-bordered gy-5 gs-7 border rounded w-100', 'id' => 'data-table'], true) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush
    @push('js')
    <script>
    </script>
    @endpush
</x-backoffice.layout.app-layout>
