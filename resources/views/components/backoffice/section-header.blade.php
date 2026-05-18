
<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{ $heading}}</h1>
</div>
@if($search == 'true')
<div
    id="kt_docs_search_handler_basic"

    data-kt-search-keypress="true"
    data-kt-search-min-length="2"
    data-kt-search-enter="true"
    data-kt-search-layout="inline">

    <form data-kt-search-element="form" class="w-100 position-relative" autocomplete="off">
        <input type="hidden"/>
        <i class="ki-duotone ki-magnifier fs-2 fs-lg-1 text-gray-500 position-absolute top-50 ms-5 translate-middle-y"><span class="path1"></span><span class="path2"></span></i>

        <input type="text" class="form-control form-control-lg px-15"
            name="search"
            value=""
            placeholder="Cari Jenis Laporan"
            data-kt-search-element="input"/>
        <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5" data-kt-search-element="spinner">
            <span class="spinner-border h-15px w-15px align-middle text-gray-500"></span>
        </span>

        <span class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 me-5 d-none"
            data-kt-search-element="clear">
            <i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0"><span class="path1"></span><span class="path2"></span></i>
        </span>
    </form>
</div>
@endif