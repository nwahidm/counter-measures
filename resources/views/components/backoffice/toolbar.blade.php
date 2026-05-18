<div id="kt_app_toolbar" class="app-toolbar py-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
        <div class="d-flex flex-column flex-row-fluid">
            {{-- <div class="d-flex align-items-center pt-1">
                @if ($breadcrumb != 'dashboard')
                    {{ Breadcrumbs::render($breadcrumb) }}
                @endif
            </div> --}}
            <div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-13 pb-6">
                <div class="d-flex justify-content-start align-items-center">
                    @if ($breadcrumb != 'dashboard')
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm me-5 py-2 px-2">
                        <i class="ki-duotone ki-arrow-left fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>
                    @endif
                    <div class="page-title me-5">
                        <h1 class="page-heading d-flex text-white fw-bold fs-2 flex-column justify-content-center my-0">{{ $heading }}
                        @if (!empty($subheading))
                        <span class="page-desc text-white-700 fw-semibold fs-6 pt-3">{{ $subheading }}</span>
                        @endif
                        </h1>
                    </div>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>