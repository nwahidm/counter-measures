<x-backoffice.layout.app-layout title="Profil">
    <x-backoffice.section-header heading="Profile" breadcrumb="profile" icon="fas fa-user" />
    <x-backoffice.section-sub-header title="Hi! {{ Str::words(auth()->user()->name, 1, '') }}"
        lead="Change information about yourself on this page." />

    <div class="row mt-sm-4">
        <div class="col-12">
            <x-backoffice.notification />
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updateProfileInformation()))
                @include('backoffice.profile.update-profile-information-form')
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                @include('backoffice.profile.update-password-form')
            @endif
        </div>
    </div>
</x-backoffice.layout.app-layout>
