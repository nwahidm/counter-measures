<x-backoffice.layout.app-layout title="Dashboard">
    @push('css')
        <style>
            .card{
                border-radius: 4px;
                box-shadow: 0 6px 10px rgba(0,0,0,.08), 0 0 6px rgba(0,0,0,.05);
                transition: .3s transform cubic-bezier(.155,1.105,.295,1.12),.3s box-shadow,.3s -webkit-transform cubic-bezier(.155,1.105,.295,1.12);
                cursor: pointer;
                background: #f5f4f8;
            }

            .card:hover{
                transform: scale(1.05);
                box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
                background:  white !important;
            }
        </style>
    @endpush

    <div class="app-main container-fluid d-flex flex-column align-items-center justify-content-center" id="kt_app_main" style="height: 100%;">
        <div class="row mb-3">
            <div class="col-md-12">
                <h1 class="text-center text-white fw-bolder">SILAHKAN PILIH METODE KASUS</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 ml-3 ">
                <a href="{{ route('open-dashboard') }}" class="card " style="width: 25rem; height: 25rem;">
                    <div class="card-body flex-column align-self-center align-content-center" style="height: 100%;">
                        <i class="ki-outline ki-fingerprint-scanning text-gray-700 fs-5tx d-flex justify-content-center"></i>
                        <br>
                        <h3 class="text-center text-gray-700">
                            Kasus Metode Terbuka
                        </h3>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 mr-3">
                <a href="{{ route('close-dashboard') }}" class="card" style="width: 25rem; height: 25rem;">
                    <div class="card-body flex-column align-self-center align-content-center" style="height: 100%;">
                        <i class="ki-outline ki-technology text-gray-700 fs-5tx d-flex justify-content-center"></i>
                        <br>
                        <h3 class="text-center text-gray-700">
                            Kasus Metode Tertutup
                        </h3>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">{{ config('app.name') }}</h1>
                <div class="text-gray-700 fs-base text-center fw-semibold"></div>
            </div>
        </div>
    </div>

    @push('js')
    @endpush
</x-backoffice.layout.app-layout>
