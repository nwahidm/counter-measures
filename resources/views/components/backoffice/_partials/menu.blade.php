{{-- OPEN MENU --}}
@if (\Str::startsWith(\Request::path(), 'open'))
    <div class="menu-item">
        <a class="menu-link {{ menuLinkHere(['open/dashboard*']) }}" href="{{ route('open-dashboard') }}">
            <span class="menu-icon">
                <i class="ki-duotone ki-abstract-23 fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span>
            <span class="menu-title">Dashboard</span>
        </a>
    </div>

    <div class="menu-item">
        <a class="menu-link {{ menuLinkHere(['open/singleform/single-form*']) }}" href="{{ route('open.singleform.single-form.index') }}">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-abstract-26 fs-2"><span class="path1"></span><span class="path2"></span></i>
            </span> --}}
            <span class="menu-title">Single Form</span>
        </a>
    </div>

    <div class="menu-item">
        <a class="menu-link {{ menuLinkHere(['open/case*']) }}" href="{{ route('open.case.index') }}">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-abstract-26 fs-2"><span class="path1"></span><span class="path2"></span></i>
            </span> --}}
            <span class="menu-title">Kasus</span>
        </a>
    </div>

    {{-- RESEARCH MENU --}}
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         data-kt-menu-offset="12,0"
         class="menu-item {{ menuHere(['open/research*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        {{-- MAIN MENU --}}
        <span class="menu-link">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span> --}}
            <span class="menu-title">Penelitian</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>

        {{-- SUB MENU --}}
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
            {{-- SUB MENU RESEARCH WARRANT MODULE --}}
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/research/warrant*']) }}"
                   href="{{ route('open.research.warrant.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-some-files fs-2"></i></span>
                    <span class="menu-title">Surat Perintah</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
            {{-- SUB MENU SPESIFIC INTELLIGENT REPORT MODULE --}}
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/research/spesific-intel-report*']) }}"
                   href="{{ route('open.research.spesific-intel-report.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-files-tablet fs-2"></i></span>
                    <span class="menu-title">Lapinsus</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
            {{-- SUB MENU ADVICE & FOLLOW-UP MEASURE MODULE --}}
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/research/advice-measure*']) }}"
                   href="{{ route('open.research.advice-measure.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-people fs-2"></i></span>
                    <span class="menu-title">Saran dan Tindak Lanjut</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
            {{-- SUB MENU RESEARCH THREATS, INTERFERENCE, BARRIER, CHALLENGES MODULE --}}
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/research/tibc*']) }}"
                   href="{{ route('open.research.tibc.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-theta fs-2"></i></span>
                    <span class="menu-title">AGHT</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
            {{-- SUB MENU RESEARCH REPORT MODULE --}}
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/research/report*']) }}"
                   href="{{ route('open.research.report.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-file-sheet fs-2"></i></span>
                    <span class="menu-title">Laporan</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
        </div>
    </div>

    {{-- INTERVIEW MENU --}}
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         data-kt-menu-offset="12,0"
         class="menu-item {{ menuHere(['open/interview*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        {{-- MAIN MENU --}}
        <span class="menu-link">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span> --}}
            <span class="menu-title">Wawancara</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>

        {{-- SUB MENU --}}
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/interview/jadwal*']) }}" href="{{ route('open.interview.jadwal.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-calendar  fs-2"></i></span><span class="menu-title">Jadwal Wawancara</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/interview/hasil*']) }}" href="{{ route('open.interview.hasil.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">Hasil Wawancara</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/interview/saran_tl*']) }}" href="{{ route('open.interview.saran_tl.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">Saran dan Tindak Lanjut</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/interview/report*']) }}" href="{{ route('open.interview.report.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">Laporan</span></a>
            </div>
        </div>
    </div>

    {{-- INTEROGATION MENU --}}
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         data-kt-menu-offset="12,0"
         class="menu-item {{ menuHere(['open/interogation*', 'open/data/interrog*', 'open/data/interogg-target-id*', 'open/data/interogg-achieve*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        {{-- MAIN MENU --}}
        <span class="menu-link">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span> --}}
            <span class="menu-title">Interogasi</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>

        {{-- SUB MENU --}}
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/data/interrog-record*']) }}"
                   href="{{ route('open.data.interrog-record.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-people fs-2"></i></span><span class="menu-title">Berita Acara</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/data/interogg-target-id*']) }}"
                   href="{{ route('open.data.interogg-target-id.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-theta fs-2"></i></span><span class="menu-title">Identifikasi Target</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/data/interogg-achieve*']) }}"
                   href="{{ route('open.data.interogg-achieve.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-some-files fs-2"></i></span><span class="menu-title">Resume BAPK</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/interogation/report*']) }}"
                   href="{{ route('open.interogation.report.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-file-sheet fs-2"></i></span>
                    <span class="menu-title">Laporan</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
        </div>
    </div>

    {{-- ELICITATION MENU --}}
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         data-kt-menu-offset="12,0"
         class="menu-item {{ menuHere(['open/data/elicit*', 'open/elicitation*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        {{-- MAIN MENU --}}
        <span class="menu-link">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span> --}}
            <span class="menu-title">Elisitasi</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>

        {{-- SUB MENU --}}
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/data/elicit-interview*']) }}"
                   href="{{ route('open.data.elicit-interview.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-people fs-2"></i></span><span
                        class="menu-title">Hasil Wawancara</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/elicitation/advice*']) }}"
                   href="{{ route('open.data.elicit-adfoll.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-theta fs-2"></i></span><span
                        class="menu-title">Saran dan Tindak Lanjut</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/elicitation/result*']) }}"
                   href="{{ route('open.data.elicit-result.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-some-files fs-2"></i></span><span
                        class="menu-title">Hasil yang Dicapai</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['open/elicitation/report*']) }}"
                   href="{{ route('open.elicitation.report.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-file-sheet fs-2"></i></span>
                    <span class="menu-title">Laporan</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
        </div>
    </div>

    <div class="menu-item">
        <a class="menu-link {{ menuLinkHere(['open/summary']) }}" href="{{ route('open.summary') }}">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-abstract-26 fs-2"><span class="path1"></span><span class="path2"></span></i>
            </span> --}}
            <span class="menu-title">Ringkasan</span>
        </a>
    </div>
@elseif (\Str::startsWith(\Request::path(), 'close'))


<div class="menu-item">
        <a class="menu-link {{ menuLinkHere(['close/dashboard*']) }}" href="{{ route('close-dashboard') }}">
            <span class="menu-icon">
                <i class="ki-duotone ki-abstract-23 fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span>
            <span class="menu-title">Dashboard</span>
        </a>
    </div>

    <div class="menu-item">
        <a class="menu-link {{ menuLinkHere(['close/singleform/single-form*']) }}" href="{{ route('close.singleform.single-form.index') }}">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-abstract-26 fs-2"><span class="path1"></span><span class="path2"></span></i>
            </span> --}}
            <span class="menu-title">Single Form</span>
        </a>
    </div>

    <div class="menu-item">
        <a class="menu-link {{ menuLinkHere(['close/case*']) }}" href="{{ route('close.case.index') }}">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-abstract-26 fs-2"><span class="path1"></span><span class="path2"></span></i>
            </span> --}}
            <span class="menu-title">Kasus</span>
        </a>
    </div>

    {{-- PENGAMATAN MENU --}}
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         data-kt-menu-offset="12,0"
         class="menu-item {{ menuHere(['close/observation*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        {{-- MAIN MENU --}}
        <span class="menu-link">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span> --}}
            <span class="menu-title">Pengamatan</span>
        </span>

        {{-- SUB MENU --}}
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
            <div class="menu-item">
                <div class="menu-link">
                    <span class="menu-subtitle">Pengumpulan data observasional terkait kasus dan pihak terkait</span>
                </div>
            </div>

            {{-- SUB MENU PENGAMATAN DIRECTIVE MODULE --}}
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/observation/directive*']) }}" href="{{ route('close.observation.directive.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-some-files fs-2"></i></span>
                    <span class="menu-title">Surat Perintah</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
            {{-- SUB MENU PENGAMATAN INFORMATION COLLECT MODULE --}}
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/observation/collect-info*']) }}"
                   href="{{ route('close.observation.collect-info.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-files-tablet fs-2"></i></span>
                    <span class="menu-title">Pengumpulan Informasi</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
            {{-- SUB MENU ADVICE & FOLLOW-UP MEASURE MODULE --}}
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/observation/threat*']) }}"
                   href="{{ route('close.observation.threat.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-people fs-2"></i></span>
                    <span class="menu-title">AGHT</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
            {{-- SUB MENU RESEARCH THREATS, INTERFERENCE, BARRIER, CHALLENGES MODULE --}}
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/observation/connected-identity*']) }}" href="{{ route('close.observation.connected-identity.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-theta fs-2"></i></span>
                    <span class="menu-title">Identitas Terhubung</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
            {{-- SUB MENU RESEARCH REPORT MODULE --}}
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/observation/report*']) }}" href="{{ route('close.observation.report.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-file-sheet fs-2"></i></span>
                    <span class="menu-title">Laporan</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
        </div>
    </div>

    {{-- DELINEATION MENU --}}
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         data-kt-menu-offset="12,0"
         class="menu-item {{ menuHere(['close/delineation*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        {{-- MAIN MENU --}}
        <span class="menu-link">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span> --}}
            <span class="menu-title">Penggambaran</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>

        {{-- SUB MENU --}}
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
            <div class="menu-item">
                <div class="menu-link">
                    <span class="menu-subtitle">Verifikasi dan validasi hasil pengumpulan informasi</span>
                </div>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/delineation/information-verification*']) }}" href="{{ route('close.delineation.information-verification.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">Verifikasi Informasi</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/delineation/information-validation*']) }}" href="{{ route('close.delineation.information-validation.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">Validasi Informasi</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/delineation/scenario-relation*']) }}" href="{{ route('close.delineation.scenario-relation.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">Skenario Terhubung</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/delineation/report*']) }}" href="{{ route('close.delineation.report.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">Laporan</span></a>
            </div>
        </div>
    </div>

    {{-- exploration MENU --}}
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         data-kt-menu-offset="12,0"
         class="menu-item {{ menuHere(['close/exploration*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        {{-- MAIN MENU --}}
        <span class="menu-link">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span> --}}
            <span class="menu-title">Penjajakan</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>

        {{-- SUB MENU --}}
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
            <div class="menu-item">
                <div class="menu-link">
                    <span class="menu-subtitle">Mitigasi risiko dan perencanaan aksi yang diperlukan</span>
                </div>
            </div>
    
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/exploration/rencana-aksi*']) }}" href="{{ route('close.exploration.rencana-aksi.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">Rencana Aksi</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/exploration/identitas-target*']) }}" href="{{ route('close.exploration.identitas-target.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">Identitas Target</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/exploration/hasil-pencapaian*']) }}" href="{{ route('close.exploration.hasil-pencapaian.index') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">Hasil yang Dicapai</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/exploration/']) }}" href="{{ route('close.exploration.report') }}"><span class="menu-icon"><i
                            class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span class="menu-title">Laporan</span></a>
            </div>
        </div>
    </div>

    {{-- tailing MENU --}}
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         data-kt-menu-offset="12,0"
         class="menu-item {{ menuHere(['close/tailing*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        {{-- MAIN MENU --}}
        <span class="menu-link">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span> --}}
            <span class="menu-title">Pembuntutan</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>

        {{-- SUB MENU --}}
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
            <div class="menu-item">
                <div class="menu-link">
                    <span class="menu-subtitle">Mengikuti pergerakan target untuk mendapatkan informasi lebih lanjut</span>
                </div>
            </div>

            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/tailing/pemahaman-perilaku*']) }}" href="{{ route('close.tailing.pemahaman-perilaku.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Pembuntutan Pemahaman Perilaku</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/tailing/target-operasi*']) }}" href="{{ route('close.tailing.target-operasi.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Pembuntutan Target Operasi</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/tailing/result-achievement*']) }}" href="{{ route('close.tailing.result-achievement.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Pembuntutan Hasil Pencapaian</span></a>
            </div>

            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/tailing/report*']) }}" href="{{ route('close.tailing.report.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Pembuntutan Laporan</span></a>
            </div>
        </div>
    </div>


    {{-- INFILTRATION MENU --}}
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         data-kt-menu-offset="12,0"
         class="menu-item {{ menuHere(['close/infiltration*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        {{-- MAIN MENU --}}
        <span class="menu-link">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span> --}}
            <span class="menu-title">Penyusupan</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>

        {{-- SUB MENU --}}
         <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
            <div class="menu-item">
                <div class="menu-link">
                    <span class="menu-subtitle">Melakukan penyusupan untuk mendapatkan informasi lebih lanjut</span>
                </div>
            </div>

            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/infiltration/secret-operation*']) }}" href="{{ route('close.infiltration.secret-operation.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Operasi Rahasia</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/infiltration/target-dynamics*']) }}" href="{{ route('close.infiltration.target-dynamics.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Dinamika Target</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/infiltration/result-achievement*']) }}" href="{{ route('close.infiltration.result-achievement.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Hasil yang Dicapai</span></a>
            </div>

            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/infiltration/report*']) }}" href="{{ route('close.infiltration.report.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Laporan</span></a>
            </div>
        </div>
    </div>

    {{-- intrusion MENU --}}
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         data-kt-menu-offset="12,0"
         class="menu-item {{ menuHere(['close/intrusion*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        {{-- MAIN MENU --}}
        <span class="menu-link">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span> --}}
            <span class="menu-title">Penyurupan</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>

        {{-- SUB MENU --}}
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
            <div class="menu-item">
                <div class="menu-link">
                    <span class="menu-subtitle">Melakukan intrusi lingkungan target untuk mendapatkan informasi lebih lanjut</span>
                </div>
            </div>

            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/intrusion/target-loc*']) }}" href="{{ route('close.intrusion.target-loc.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Lokasi Target</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/intrusion/target-env*']) }}" href="{{ route('close.intrusion.target-env.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Lingkungan Target</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/intrusion/result*']) }}" href="{{ route('close.intrusion.result.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Hasil yang Dicapai</span></a>
            </div>

            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/intrusion/report*']) }}" href="{{ route('close.intrusion.report.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Laporan</span></a>
            </div>
        </div>
    </div>

    {{-- tapping MENU --}}
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         data-kt-menu-offset="12,0"
         class="menu-item {{ menuHere(['close/tapping*']) }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        {{-- MAIN MENU --}}
        <span class="menu-link">
            {{-- <span class="menu-icon">
                <i class="ki-duotone ki-tablet-book fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span> --}}
            <span class="menu-title">Penyadapan</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>

        {{-- SUB MENU --}}
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" style="">
            <div class="menu-item">
                <div class="menu-link">
                    <span class="menu-subtitle">Melakukan penyadapan perangkat target untuk mendapatkan informasi lebih lanjut</span>
                </div>
            </div>
            
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/tapping/electronic_device*']) }}" href="{{ route('close.tapping.electronic_device.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Data Peralatan Elektronik</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/tapping/intelligent_signal*']) }}" href="{{ route('close.tapping.intelligent_signal.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Sinyal Data</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/tapping/result_achievement*']) }}" href="{{ route('close.tapping.result_achievement.index') }}"><span
                        class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span><span
                        class="menu-title">Hasil yang Dicapai</span></a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ menuLinkHere(['close/tapping/report*']) }}"
                   href="{{ route('close.tapping.report.index') }}">
                    <span class="menu-icon"><i class="ki-outline ki-duotone ki-user-tick  fs-2"></i></span>
                    <span class="menu-title">Laporan</span>
                    <span class="menu-arrow d-none"></span>
                </a>
            </div>
        </div>
    </div>

    <div class="menu-item">
        <a class="menu-link {{ menuLinkHere(['close/summary*']) }}" href="{{ route('close.summary') }}">
            {{-- <span class="menu-icon">
                <i class="ki-duotone k
                i-abstract-26 fs-2"><span class="path1"></span><span class="path2"></span></i>
            </span> --}}
            <span class="menu-title">Ringkasan</span>
        </a>
    </div>

@elseif((\Str::startsWith(\Request::path(), 'dashboard')))
@else
    <div class="menu-item">
        <a class="menu-link {{ menuLinkHere(['dashboard*']) }}" href="{{ route('dashboard') }}">
            <span class="menu-icon">
                <i class="ki-duotone ki-abstract-23 fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span>
            <span class="menu-title">Home</span>
        </a>
    </div>
@endif


@push('js')
@endpush