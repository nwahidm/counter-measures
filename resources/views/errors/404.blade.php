<x-backoffice.layout.auth-layout title="Dashboard">
    <section class="section text-center">
        <div class="container mt-5">
            <div class="page-error">
                <div class="page-inner">
                    <img src="{{ asset('assets/images/svg/404-notfound.svg') }}" width="450" class="img-responsive img-fluid  mb-5"/>
                    <h1>404</h1>
                    <div class="page-description">
                        The page you were looking for could not be found.
                    </div>
                    <div class="mt-3">
                        <a class="btn btn-primary" href="{{ route('dashboard') }}">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-backoffice.layout.auth-layout>