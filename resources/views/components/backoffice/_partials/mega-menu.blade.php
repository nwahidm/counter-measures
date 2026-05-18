<div class="menu menu-rounded menu-column menu-lg-row menu-active-bg menu-title-gray-600 menu-state-primary menu-arrow-gray-500 fw-semibold fw-semibold fs-6 align-items-stretch my-5 my-lg-0 px-2 px-lg-0"
    id="#kt_app_header_menu" data-kt-menu="true">
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
        data-kt-menu-offset="12,0"
        class="menu-item {{ menuHere(['dashboard', 'dct*', 'kirka*', 'kegiatan-posko*', 'nphd*', 'aght*', 'posko*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="ki-duotone ki-abstract-42 fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span>
            <span class="menu-title">DIR A</span><span class="menu-arrow d-lg-none"></span></span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
                <div class="menu-item">
                    <a class="menu-link" href="{{ route('dashboard') }}"><span class="menu-icon"><i
                                class="ki-outline ki-category fs-2"></i></span><span class="menu-title">DASHBOARD</span></a>
                </div>
    
                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item menu-lg-down-accordion {{ menuHere(['dct*', 'kirka*', 'kegiatan-posko*', 'nphd*', 'aght*', 'posko*'], ['dct/laporan*']) }}">
                    <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-briefcase fs-2"></i></span><span
                            class="menu-title">HIMPUNAN DATA</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('polling.agenintel') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">QUICK COUNT</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('kirka-polling') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">POLLING PRESENTASE</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('dct.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">DCT</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('kirka.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">KIRKA</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('posko.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">PENDATAAN
                                    POSKO</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('kegiatan-posko.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">KEGIATAN
                                    POSKO</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('nphd.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">DATA
                                    NPHD</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('aght.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">DATA
                                    AGHT</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master.perkara.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">CMS PERKARA</span></a>
                        </div>
                      
                        

                    </div>
                </div>

                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item menu-lg-down-accordion {{ menuHere(['dct/laporan', 'report/kegiatan-posko*']) }}">
                    <span class="menu-link"><span class="menu-icon"><i
                                class="ki-outline ki-file-added fs-2"></i></span><span
                            class="menu-title">LAPORAN</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('dct.laporan') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">DCT</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="#"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">KIRKA</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('report.kegiatanPosko') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">KEGIATAN
                                    POSKO</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('nphd.createReport') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">DATA
                                    NPHD</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </span>
    </div>

    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
        data-kt-menu-offset="12,0"
        class="menu-item  {{ menuHere(['dashboard-dir-b']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="ki-duotone ki-abstract-3 fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
                </i>
            </span>
            <span class="menu-title">DIR B</span><span
                    class="menu-arrow d-lg-none"></span></span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
                <div class="menu-item {{ menuHere(['admin/home']) }}">
                    <a class="menu-link" href="{{ route('dashboard-dir-b') }}"><span class="menu-icon"><i class="ki-outline ki-category fs-2"></i></span><span class="menu-title">DASHBOARD</span></a>
                </div>
                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item menu-lg-down-accordion {{ menuHere(['dct*', 'kirka*', 'kegiatan-posko*', 'nphd*', 'aght*', 'posko*'], ['dct/laporan*']) }}">
                    <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-briefcase fs-2"></i></span><span
                            class="menu-title">HIMPUNAN DATA</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('posko-jaga-desa.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">POSKO JAGA DESA</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('data-desa.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">DATA DESA</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('bina-desa.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">DATA BINA DESA</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </span>
    </div>

    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
        data-kt-menu-offset="12,0"
        class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="ki-duotone ki-abstract-21 fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
                </i>
            </span>
            <span class="menu-title">DIR C</span><span
                    class="menu-arrow d-lg-none"></span></span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
                <div class="menu-item {{ menuHere(['admin/home']) }}">
                    <a class="menu-link" href="#"><span class="menu-icon"><i class="ki-outline ki-category fs-2"></i></span><span class="menu-title">DASHBOARD</span></a>
                </div>
                <div class="menu-item {{ menuHere(['admin/home']) }}">
                    <a class="menu-link" href="#"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">LAPORAN</span></a>
                </div>
            </div>
        </span>
    </div>

    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
        data-kt-menu-offset="12,0"
        class="menu-item {{ menuHere(['dashboard-dir-d', 'jalan-daerah*', 'logistik-kpu*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="ki-duotone ki-abstract-9 fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
                </i>
            </span>
            <span class="menu-title">DIR D</span><span
                    class="menu-arrow d-lg-none"></span></span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
                <div class="menu-item {{ menuHere(['dashboard-dir-d']) }}">
                    <a class="menu-link" href="{{ route('dashboard-dir-d') }}"><span class="menu-icon"><i class="ki-outline ki-category fs-2"></i></span><span class="menu-title">DASHBOARD</span></a>
                </div>
                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item menu-lg-down-accordion {{ menuHere(['jalan-daerah*', 'logistik-kpu*']) }}">
                    <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-briefcase fs-2"></i></span><span
                            class="menu-title">HIMPUNAN DATA</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('logistik-kpu.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">PENGADAAN LOGISTIK PEMILU</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('jalan-daerah.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">KONEKTIFITAS JALAN DAERAH</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href=""><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">PERKEMBANGAN PPS</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('data-aght.index') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">AGHT</span></a>
                        </div>
                    </div>
                </div>

                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item menu-lg-down-accordion {{ menuHere(['jalan-daerah/create-report'], ['jalan-daerah*']) }}">
                    <span class="menu-link"><span class="menu-icon"><i
                                class="ki-outline ki-file-added fs-2"></i></span><span
                            class="menu-title">LAPORAN</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('jalan-daerah.createReport') }}"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">KONEKTIFITAS JALAN DAERAH</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </span>
    </div>

    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
        data-kt-menu-offset="12,0"
        class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="ki-duotone ki-abstract-36 fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
                </i>
            </span>
            <span class="menu-title">DIR E</span><span
                    class="menu-arrow d-lg-none"></span></span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
                <div class="menu-item {{ menuHere(['admin/home']) }}">
                    <a class="menu-link" href="#"><span class="menu-icon"><i class="ki-outline ki-category fs-2"></i></span><span class="menu-title">DASHBOARD</span></a>
                </div>
                <div class="menu-item {{ menuHere(['admin/home']) }}">
                    <a class="menu-link" href="#"><span class="menu-icon"><i class="ki-outline ki-file-added fs-2"></i></span><span class="menu-title">LAPORAN</span></a>
                </div>
            </div>
        </span>
    </div>

    <!-- <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
        data-kt-menu-offset="12,0"
        class="menu-item {{ menuHere(['master*', 'master-data*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
                </i>
            </span>
            <span class="menu-title">MASTER DATA</span><span
                    class="menu-arrow d-lg-none"></span></span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item menu-lg-down-accordion {{ menuHere(['master-data/*']) }}">
                    <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-briefcase fs-2"></i></span><span class="menu-title">KEGIATAN</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master.tahun.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahun</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master.partai.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Partai</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master-data.capres.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Capres</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master-data.capres-tahun.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Capres Tahun</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master-data.capres-partai-tahun.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Capres Partai Tahun</span></a>
                        </div>
                    </div>
                </div>

                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item menu-lg-down-accordion {{ menuHere(['master/*']) }}">
                    <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-briefcase fs-2"></i></span><span class="menu-title">UMUM</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master.agama.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Agama</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master.pekerjaan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Pekerjaan</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master.provinsi.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Provinsi</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master.kota.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Kota</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master.kecamatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Kecamatan</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master.desa.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Desa</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('master.perkara.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Perkara</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </span>
    </div> -->

    <!-- <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
        data-kt-menu-offset="12,0"
        class="menu-item {{ menuHere(['manage-user*', 'manage-role*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        <span class="menu-link">
            <span class="menu-icon">
                <i class="ki-duotone ki-setting-3 fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
                <span class="path5"></span>
                </i>
            </span>
            <span class="menu-title">PENGATURAN</span><span
                    class="menu-arrow d-lg-none"></span></span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
                <div class="menu-item">
                    <a class="menu-link" href="{{ route('user.index') }}"><span class="menu-icon"><i class="ki-outline ki-user-tick fs-2"></i></span><span class="menu-title">USER</span></a>
                </div>

                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item menu-lg-down-accordion {{ menuHere(['manage-role*']) }}">
                    <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-lock-3 fs-2"></i></span><span class="menu-title">ROLE PERMISSION</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('manage-role.role.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Role</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('manage-role.permission.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Permission</span></a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('manage-role.rolepermission.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Role Permission</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </span>
    </div>
 -->
    <div class="menu-item">
        <a class="menu-link" href="{{ route('chat') }}"><span class="menu-icon"><i class="ki-outline ki-message-notif fs-2"></i></span><span class="menu-title">CHATING</span></a>
    </div>
    <div class="menu-item">
        <a class="menu-link" href="https://inderasembilan.id"><span class="menu-icon"><i class="ki-outline ki-directbox-default fs-2"></i></span><span class="menu-title">E-ADMINTEL</span></a>
    </div>
</div>
