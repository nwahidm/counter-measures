<x-backoffice.layout.auth-layout title="Login">
    @push('css')
    <style>
        body {
            background-image: url("{{ asset('backend/assets/media/auth/bg6.jpg') }}");
        }

        [data-bs-theme="dark"] body {
            background-image: url("{{ asset('backend/assets/media/auth/bg6-dark.jpeg') }}");
        }
    </style>
    @endpush
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid justify-content-center">
            {{-- <div class="d-flex flex-lg-row-fluid">
                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                    <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="{{ asset('backend/assets/media/illustrations/sigma-1/8.png') }}" alt="" />
                    <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="{{ asset('backend/assets/media/illustrations/sigma-1/8-dark.png') }}" alt="" />
                    <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">{{ config('app.name') }}</h1>
                    <div class="text-gray-700 fs-base text-center fw-semibold"></div>
                </div>
            </div> --}}
            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
                <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                    <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                        <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                            <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="{{ route('customauth.loginpost') }}" method="POST">
                                @csrf
                                <div class="d-flex flex-column flex-center w-100 mb-10">
                                    <img class="theme-light-show w-150px w-lg-150px " src="{{ asset('backend/assets/media/illustrations/sigma-1/8.png') }}" alt="" />
                                    <img class="theme-dark-show w-150px w-lg-150px " src="{{ asset('backend/assets/media/illustrations/sigma-1/8-dark.png') }}" alt="" />
                                </div>
                                
                                <div class="text-center mb-11">
                                    <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                                </div>
                                <div class="fv-row mb-8">
                                    <input type="text" placeholder="Username" name="username" autocomplete="off"
                                        class="form-control @error('username') is-invalid @enderror bg-transparent"/>
                                    @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="fv-row mb-3">
                                    <input type="password" placeholder="Password" name="password" autocomplete="off"
                                        class="form-control bg-transparent"/>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-grid mb-10 mt-15">
                                    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary g-recaptcha"
                                            tabindex="4" data-sitekey="{{ config('services.google.gcaptcha_sitekey') }}"
                                            data-callback='onSubmit'>
                                        <span class="indicator-label">Sign In</span>
                                        <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                    <a href="{{url('login-sso')}}" class="btn btn-secondary">Login SSO</a>
                                </div>
                                <div class="row mt-15">
                                    <h1 class="text-gray-800 fs-1qx fw-bold text-center mb-7">{{ config('app.name') }}</h1>
                                    <div class="text-gray-700 fs-base text-center fw-semibold"> </div>
                                </div>
                            </form>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="d-none d-lg-block text-dark fs-base text-center"> <a href="javascript:void(0)" class="opacity-75-hover text-warning fw-bold me-1">Kejaksaan Republik Indonesia</a> </br>© {{ date('Y') }} All Rights Reserved.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script>
            function onSubmit(token) {
                document.getElementById("kt_sign_in_form").submit();
            }
        </script>
    @endpush
</x-backoffice.layout.auth-layout>
