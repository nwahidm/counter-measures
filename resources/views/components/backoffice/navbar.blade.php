<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky" data-kt-sticky-offset="{default: false, lg: '300px'}">
    <div class="app-container container-xxl d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
        <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_header_menu_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
        </div>
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-18">
            <a href="/">
                <img alt="Logo" src="{{ asset('assets/images/kejaksaan_compress.png') }}" class="h-25px d-sm-none" />
                <img alt="Logo" src="{{ asset('assets/images/kejaksaan_compress.png') }}" class="h-25px d-none d-sm-block" />
            </a>
        </div>
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
            <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                <div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">

                    @if(isset(auth()->user()->name))
                        @include('components.backoffice._partials.menu')
                    @endif

                </div>
            </div>

            <div class="app-navbar flex-shrink-0">
                {{-- <div class="app-navbar-item ms-1 ms-md-3 me-3">
					<h5 class="d-flex align-items-center text-danger my-1 fs-7 fw-medium d-none d-sm-block" id="countdownDate">

					</h5>
				</div> --}}

                <div class="app-navbar-item ms-3 ms-lg-9" id="kt_header_user_menu_toggle">
                @if(isset(auth()->user()->name))
                    <div class="d-flex align-items-center" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <div class="text-end d-none d-sm-flex flex-column justify-content-center me-3">
                            <span class="text-gray-500 fs-8 fw-bold">Hello</span>
                            <a href="javascript(0)"
                                class="text-gray-500 fs-7 fw-bold d-block">{{ auth()->user()->name ?? '-' }}</a>
                        </div>
                        <div class="cursor-pointer symbol symbol symbol-circle symbol-35px symbol-md-40px">
                            <img class="" src="{{ auth()->user()->profile ? asset(auth()->user()->profile) : asset('assets/images/avatar/user.png') }}" alt="user" />
                            <div
                                class="position-absolute translate-middle bottom-0 mb-1 start-100 ms-n1 bg-success rounded-circle h-8px w-8px">
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex align-items-center">
                        <a href="#" class="btn btn-icon btn-icon-muted btn-active-icon-primar ms-1"
                            data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
                            data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-night-day theme-light-show fs-1"><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span><span class="path4"></span><span
                                    class="path5"></span><span class="path6"></span><span class="path7"></span><span
                                    class="path8"></span><span class="path9"></span><span class="path10"></span></i> <i
                                class="ki-duotone ki-moon theme-dark-show fs-1"><span class="path1"></span><span
                                    class="path2"></span></i></a>

                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                            data-kt-menu="true" data-kt-element="theme-mode-menu" style="">
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2 active" data-kt-element="mode"
                                    data-kt-value="light">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-duotone ki-night-day fs-2"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span><span class="path5"></span><span
                                                class="path6"></span><span class="path7"></span><span
                                                class="path8"></span><span class="path9"></span><span
                                                class="path10"></span></i> </span>
                                    <span class="menu-title">
                                        Light
                                    </span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-duotone ki-moon fs-2"><span class="path1"></span><span
                                                class="path2"></span></i> </span>
                                    <span class="menu-title">
                                        Dark
                                    </span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-duotone ki-screen fs-2"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span></i> </span>
                                    <span class="menu-title">
                                        System
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                        data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-5">
                                    <img alt="Logo" src="{{ auth()->user()->profile ? asset(auth()->user()->profile) : asset('assets/images/avatar/user.png') }}" />
                                </div>
                                <div class="d-flex flex-column">
                                    <div
                                        class="fw-bold d-flex align-items-
                                    center fs-5">
                                        {{ auth()->user()->name ?? '-' }}
                                        <span
                                            class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                                                @if(isset(auth()->user()->name))
                                                    {{ auth()->user()->getRoleNames()->first() ?? '-'}}
                                                @endif
                                        </span>
                                    </div>
                                    <a href="#"
                                        class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->email ?? '-' }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('edit.profile', auth()->user()->id ?? '') }}"><span class="menu-icon"><i class="ki-outline ki-duotone ki-profile-circle fs-2"></i></span><span class="menu-title">MY PROFILE</span></a>
                        </div>
                        @if (auth()->user()->hasPermissionTo('read-users'))
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('commandcenter') }}">
                                    <span class="menu-icon">
                                        <i class="ki-outline ki-duotone ki-profile-circle fs-2"></i>
                                    </span>
                                    <span class="menu-title">Dashboard Command Center</span></a>
                            </div>

                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('bodycam.body-cam.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-outline ki-duotone ki-profile-circle fs-2"></i>
                                    </span>
                                    <span class="menu-title">Body Camera</span></a>
                            </div>

                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('bodycam.body-cam.index') }}">
                                    <span class="menu-icon">
                                        <i class="ki-outline ki-duotone ki-profile-circle fs-2"></i>
                                    </span>
                                    <span class="menu-title">Samsung Knox Management</span></a>
                            </div>
                            
                            <div class="menu-item">
                                <a class="menu-link {{ menuLinkHere(['manage-user*']) }}" href="{{ route('user.index') }}"><span class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">USER</span></a>
                            </div>

                            
                            
                            
                        @endif
                        @if (auth()->user()->hasPermissionTo('master-satker'))
                            <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                                class="menu-item menu-lg-down-accordion {{ menuHere(['master*']) }}">
                                <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-briefcase fs-2"></i></span><span class="menu-title">Master Data</span><span
                                        class="menu-arrow"></span></span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px"
                                    style="">
                                    <div class="menu-item">
                                        <a class="menu-link {{ menuLinkHere(['master/tahun*']) }}" href="{{ route('master.tahun.index') }}"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">TAHUN</span></a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ menuLinkHere(['master/pegawai*']) }}" href="{{ route('master.pegawai.index') }}"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">PEGAWAI</span></a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ menuLinkHere(['master/agama*']) }}" href="{{ route('master.agama.index') }}"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">AGAMA</span></a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ menuLinkHere(['master/pekerjaan*']) }}" href="{{ route('master.pekerjaan.index') }}"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">PEKERJAAN</span></a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ menuLinkHere(['master/pendidikan*']) }}" href="{{ route('master.pendidikan.index') }}"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">PENDIDIKAN</span></a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ menuLinkHere(['master/satker*']) }}" href="{{ route('master.satker.index') }}"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">SATUAN KERJA</span></a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ menuLinkHere(['master/wilayah*']) }}" href="{{ route('master.wilayah.index') }}"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">WILAYAH</span></a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ menuLinkHere(['master/wilayah-satker*']) }}" href="{{ route('master.wilayah-satker.index') }}"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">WILAYAH SATKER</span></a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (auth()->user()->hasPermissionTo('read-role'))
                            <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                                class="menu-item menu-lg-down-accordion {{ menuHere(['manage-role*']) }}">
                                <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-setting-2 fs-2"></i></span><span class="menu-title">Role Permission</span><span
                                        class="menu-arrow"></span></span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px"
                                    style="">
                                    <div class="menu-item">
                                        <a class="menu-link {{ menuLinkHere(['manage-role/role*'], ['manage-role/rolepermission*']) }}" href="{{ route('manage-role.role.index') }}"><span class="menu-icon"><i class="ki-outline ki-category fs-2"></i></span><span class="menu-title">ROLE</span></a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ menuLinkHere(['manage-role/permission*']) }}" href="{{ route('manage-role.permission.index') }}"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">PERMISSION</span></a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ menuLinkHere(['manage-role/rolepermission*']) }}" href="{{ route('manage-role.rolepermission.index') }}"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">ROLE PERMISSION</span></a>
                                    </div>
                                </div>
                            </div>

                            
                        @endif

                        <div class="menu-item">
                            <a class="menu-link" href="#" class="menu-link px-5"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="menu-icon"><i class="ki-outline ki-duotone ki-exit-left fs-2"></i></span><span class="menu-title">SIGN OUT</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

@push('js')
<script>
    function updateCurrentTime() {
        const now = new Date();

        const daysOfWeek = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const dayOfWeek = daysOfWeek[now.getDay()];

        const options = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZone: 'Asia/Jakarta',
        };

        const formattedDate = now.toLocaleDateString('id-ID', options)
                                .replace(/(\d{2})\/(\d{2})\/(\d{4})/, '$2-$1-$3')
                                .replace(".", ":")
                                .replace(",", "");

        const formattedTime = now.toLocaleTimeString('id-ID', {hour12: false});

        document.getElementById('countdownDate').innerText = `${dayOfWeek}, ${formattedDate}`;
        setTimeout(updateCurrentTime, 1000);
    }
    // updateCurrentTime();
</script>
@endpush
