@php
    $hasSelectedEmployee = isset($employee) && !empty($employee);
    $currentNik = $hasSelectedEmployee ? $employee->nik : null;
    $isBeranda = request()->routeIs('beranda');
    $isDataKaryawan = request()->routeIs('data-karyawan') || request()->routeIs('karyawan.index');
    // Tab hanya aktif jika berada di dalam halaman profil karyawan (bukan di beranda ataupun data karyawan)
    $activeTab = ($isBeranda || $isDataKaryawan) ? null : ($tab ?? null);

    $talentTabs = [
        [
            'slug' => 'profil-individu',
            'title' => 'Profil individu',
            'icon' => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        ],
        [
            'slug' => 'riwayat-karir',
            'title' => 'Riwayat karier',
            'icon' => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>',
        ],
        [
            'slug' => 'talent-snapshot',
            'title' => 'Talent snapshot',
            'icon' => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>',
        ],
        [
            'slug' => 'career-plan',
            'title' => 'Individual career plan',
            'icon' => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="7" cy="5" r="2"></circle><circle cx="17" cy="19" r="2"></circle><path d="M7 7a5 5 0 0 1 5 5 5 5 0 0 0 5 5"></path></svg>',
        ],
        [
            'slug' => 'status-suksesi',
            'title' => 'Status suksesi',
            'icon' => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
        ],
        [
            'slug' => 'development-gap',
            'title' => 'Development gap',
            'icon' => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>',
        ],
        [
            'slug' => 'idp',
            'title' => 'Individual development plan',
            'icon' => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>',
        ],
        [
            'slug' => 'review-pengembangan',
            'title' => 'Review hasil pengembangan',
            'icon' => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
        ],
        [
            'slug' => 'riwayat-pelatihan',
            'title' => 'Riwayat pelatihan',
            'icon' => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
        ],
    ];

    $initials = '??';
    if ($hasSelectedEmployee && !empty($employee->name)) {
        $words = preg_split('/\s+/', trim($employee->name));
        if (count($words) >= 2) {
            $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
        } else {
            $initials = mb_substr($employee->name, 0, 2);
        }
        $initials = strtoupper($initials);
    }
@endphp

<aside class="sidebar" id="app-sidebar">
    {{-- Sidebar Toggle Button (Panah ke samping kiri / kanan) --}}
    <button type="button" id="sidebar-toggle-btn" class="sidebar-toggle-btn" title="Ciutkan Sidebar (Geser ke Kiri)" aria-label="Toggle Sidebar">
        <svg id="sidebar-toggle-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>

    {{-- Drag Handle untuk Menggeser Ukuran Sidebar --}}
    <div id="sidebar-resizer" class="sidebar-resizer" title="Tarik / geser untuk mengubah lebar sidebar"></div>

    <div class="sidebar-main-content">
        {{-- Navigation Menu Utama (Beranda & Data karyawan) --}}
        <nav class="sidebar-top-nav">
            {{-- 1. Beranda --}}
            <a href="{{ route('beranda') }}" 
               class="sidebar-nav-item {{ $isBeranda ? 'active' : '' }}"
               title="Beranda"
               data-title="Beranda">
                <span class="sidebar-nav-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </span>
                <span>Beranda</span>
            </a>

            {{-- 2. Data karyawan --}}
            <a href="{{ route('data-karyawan') }}" 
               class="sidebar-nav-item {{ $isDataKaryawan ? 'active' : '' }}"
               title="Data karyawan"
               data-title="Data karyawan">
                <span class="sidebar-nav-icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </span>
                <span>Data karyawan</span>
            </a>
        </nav>

        @if(!$hasSelectedEmployee)
            {{-- ======================================================== --}}
            {{-- KONDISI 1: Belum ada karyawan dipilih (State 1 Mockup)   --}}
            {{-- ======================================================== --}}
            <div class="sidebar-section-container">
                <div class="sidebar-section-heading">PENCARIAN PROFIL</div>

                {{-- Search Box --}}
                <form action="{{ route('karyawan.search') }}" method="GET" id="sidebar-search-form" class="sidebar-search-form">
                    <input type="hidden" name="tab" value="profil-individu">
                    <div class="sidebar-search-input-wrap">
                        <input 
                            type="text" 
                            id="sidebar-nik-search" 
                            name="nik" 
                            placeholder="Ketik NIK / nama..." 
                            autocomplete="off"
                            required
                        >
                        <button type="submit" class="sidebar-search-btn" title="Cari karyawan">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </div>
                    {{-- Live suggestion box dropdown --}}
                    <div id="search-suggestions" class="search-suggestions"></div>
                </form>

                {{-- Helper Card --}}
                <div class="sidebar-helper-card">
                    <p class="sidebar-helper-text">
                        Pilih karyawan dari tabel atau cari NIK untuk membuka detail talenta.
                    </p>
                    <button type="button" class="sidebar-helper-btn" onclick="handleCariKaryawanClick()">
                        Cari karyawan
                    </button>
                </div>

                {{-- Single Locked Item Bar --}}
                <div class="sidebar-locked-row" onclick="showNoEmployeeAlert(event, 'Detail talenta')" title="Pilih karyawan terlebih dahulu untuk membuka detail talenta" data-title="Detail talenta (Terkunci)">
                    <div class="sidebar-locked-left">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <span>Detail talenta</span>
                    </div>
                    <div class="sidebar-locked-right">
                        <span class="sidebar-locked-badge">9 tab</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </div>

                <div class="sidebar-locked-note">
                    Terbuka setelah karyawan dipilih
                </div>
            </div>
        @else
            {{-- ======================================================== --}}
            {{-- KONDISI 2: Karyawan sudah dipilih (State 2 Mockup)       --}}
            {{-- ======================================================== --}}
            <div class="sidebar-section-container">
                <div class="sidebar-section-heading">KARYAWAN TERPILIH</div>

                {{-- Selected Employee Card --}}
                <div class="sidebar-selected-emp-card">
                    <div class="sidebar-selected-emp-left">
                        <div class="sidebar-emp-avatar-initials" title="{{ $employee->name }}">
                            {{ $initials }}
                        </div>
                        <div class="sidebar-emp-info">
                            <div class="sidebar-emp-name" title="{{ $employee->name }}">
                                {{ $employee->name }}
                            </div>
                            <div class="sidebar-emp-nik">
                                NIK {{ $employee->nik }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('data-karyawan') }}" class="sidebar-emp-close-btn" title="Ganti karyawan / kembali ke tabel" aria-label="Hapus pilihan karyawan">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </a>
                </div>

                <div class="sidebar-section-heading" style="margin-top:14px; margin-bottom:6px;">DETAIL TALENTA</div>

                {{-- 9 Navigation Tabs --}}
                <nav class="sidebar-tabs-list">
                    @foreach($talentTabs as $tabItem)
                        <a href="{{ route('karyawan.show', ['nik' => $currentNik, 'tab' => $tabItem['slug']]) }}" 
                           class="sidebar-tab-item {{ $activeTab === $tabItem['slug'] ? 'active' : '' }}"
                           title="{{ $tabItem['title'] }}"
                           data-title="{{ $tabItem['title'] }}">
                            <span class="sidebar-tab-icon">
                                {!! $tabItem['icon'] !!}
                            </span>
                            <span class="sidebar-tab-text">{{ $tabItem['title'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        @endif
    </div>
</aside>

{{-- Modal Interaktif: Alert Peringatan Karyawan Belum Dipilih --}}
<div id="modal-no-employee-alert" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(15,23,42,0.65); backdrop-filter:blur(3px); z-index:999999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#ffffff; width:100%; max-width:440px; border-radius:12px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.35); overflow:hidden; border:1px solid #e2e8f0;">
        {{-- Modal Header --}}
        <div style="background:linear-gradient(135deg, #081f38 0%, #173d6b 100%); padding:20px 22px; color:#ffffff; display:flex; align-items:center; gap:14px; position:relative;">
            <div style="width:40px; height:40px; border-radius:10px; background:rgba(59, 130, 246, 0.2); border:1.5px solid #60a5fa; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
            <div>
                <h3 style="margin:0; font-size:15px; font-weight:800; letter-spacing:-0.2px;">Pilih Karyawan Terlebih Dahulu</h3>
                <p style="margin:2px 0 0 0; font-size:11.5px; color:#cbd5e1;">Akses 9 menu detail talenta memerlukan karyawan aktif</p>
            </div>
            <button type="button" onclick="closeNoEmployeeAlert()" style="position:absolute; right:14px; top:14px; background:transparent; border:none; color:#cbd5e1; cursor:pointer; padding:4px;" title="Tutup">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        
        {{-- Modal Body --}}
        <div style="padding:22px; color:#334155; font-size:13px; line-height:1.55;">
            <p style="margin:0 0 10px 0;">
                Anda mengklik <strong id="alert-tab-name-target" style="color:#081f38; background:#eff6ff; padding:2px 8px; border-radius:4px; border:1px solid #bfdbfe;">Detail talenta</strong>.
            </p>
            <p style="margin:0 0 10px 0; color:#475569;">
                Untuk melihat <strong>9 tab detail talenta</strong> (Profil individu, Riwayat karier, Talent snapshot, dsb.), Anda harus memilih karyawan terlebih dahulu.
            </p>
            <div style="background:#f8fafc; border-left:3px solid #3b82f6; padding:10px 12px; border-radius:0 6px 6px 0; font-size:11.5px; color:#64748b;">
                💡 <strong>Cara membuka:</strong> Cari NIK / Nama di kotak pencarian sidebar, atau klik tombol <em>Profil</em> pada salah satu baris di <strong>Data Karyawan</strong>.
            </div>
        </div>
        
        {{-- Modal Footer --}}
        <div style="padding:14px 22px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px; flex-wrap:wrap;">
            <button type="button" onclick="focusSidebarSearch()" class="btn btn-outline" style="font-size:12px; padding:7px 13px; border:1px solid #cbd5e1; background:#ffffff; color:#334155; display:inline-flex; align-items:center; gap:5px; cursor:pointer; border-radius:6px; font-weight:600;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <span>Cari NIK / Nama</span>
            </button>
            <a href="{{ route('data-karyawan') }}" class="btn btn-primary" style="font-size:12px; padding:7px 15px; background:#081f38; color:#ffffff; text-decoration:none; display:inline-flex; align-items:center; gap:6px; border-radius:6px; font-weight:700;">
                <span>Buka Data Karyawan &rarr;</span>
            </a>
        </div>
    </div>
</div>

<script>
    function handleCariKaryawanClick() {
        var searchInput = document.getElementById('sidebar-nik-search');
        if (searchInput && searchInput.value.trim().length > 0) {
            document.getElementById('sidebar-search-form').submit();
        } else {
            @if(request()->routeIs('data-karyawan'))
                if (searchInput) {
                    searchInput.focus();
                    searchInput.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.4)';
                    setTimeout(function() {
                        searchInput.style.boxShadow = '';
                    }, 1500);
                }
            @else
                window.location.href = "{{ route('data-karyawan') }}";
            @endif
        }
    }

    function showNoEmployeeAlert(e, tabName) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        var modal = document.getElementById('modal-no-employee-alert');
        var targetEl = document.getElementById('alert-tab-name-target');
        if (targetEl) targetEl.textContent = tabName || 'Detail talenta';
        if (modal) modal.style.display = 'flex';
    }

    function closeNoEmployeeAlert() {
        var modal = document.getElementById('modal-no-employee-alert');
        if (modal) modal.style.display = 'none';
    }

    function focusSidebarSearch() {
        closeNoEmployeeAlert();
        var searchInput = document.getElementById('sidebar-nik-search');
        if (searchInput) {
            var sidebar = document.getElementById('app-sidebar');
            if (sidebar && sidebar.classList.contains('collapsed')) {
                var toggleBtn = document.getElementById('sidebar-toggle-btn');
                if (toggleBtn) toggleBtn.click();
            }
            setTimeout(function() {
                searchInput.focus();
                searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                searchInput.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.4)';
                setTimeout(function() {
                    searchInput.style.boxShadow = '';
                }, 2000);
            }, 120);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('modal-no-employee-alert');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeNoEmployeeAlert();
                }
            });
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeNoEmployeeAlert();
            }
        });
    });
</script>
