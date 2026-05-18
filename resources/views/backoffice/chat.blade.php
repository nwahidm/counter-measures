<x-backoffice.layout.app-layout title="Manajemen User">
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
                <x-backoffice.section-header heading="Chating Agen Inteligen" breadcrumb="user" icon="fas fa-users"/>
                <div class="d-flex align-items-center w-25">
                    <x-backoffice.notification/>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div class="card">
            <iframe
                src="{{ config('chatify.routes.prefix')}}"
                style="width:100%; height:670px;" frameborder="0">
            </iframe>
            </div>
        </div>
    </div>
    
</x-backoffice.layout.app-layout>