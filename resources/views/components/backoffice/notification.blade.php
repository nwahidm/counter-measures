@if (session('success'))
<div class="alert alert-success alert-dismissible d-flex align-items-center w-100 pe-0 pr-0">
    <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
    <div class="d-flex flex-column">
        <h4 class="mb-1 text-dark">Success!</h4>
        <span>{{ session('success') }}</span>
    </div>
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif
@if (session('error'))
<div class="alert alert-danger alert-dismissible d-flex align-items-center w-100 pe-0 pr-0">
    <i class="ki-duotone ki-shield-tick fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
    <div class="d-flex flex-column">
        <h4 class="mb-1 text-dark">Error!</h4>
        <span>{!! session('error') !!}</span>
    </div>
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible show fade w-100" role="alert">
            <strong> {{ $error }} </strong>
        </div>
    @endforeach
@endif
