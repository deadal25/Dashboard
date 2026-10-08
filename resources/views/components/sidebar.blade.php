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
            'title' => 'Profil Individu',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
            'badge' => null
        ],
        [
            'slug' => 'riwayat-karir',
            'title' => 'Riwayat Karir',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>',
            'badge' => null
        ],
        [
            'slug' => 'talent-snapshot',
            'title' => 'Talent Snapshot',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="22" y1="12" x2="18" y2="12"></line><line x1="6" y1="12" x2="2" y2="12"></line><line x1="12" y1="6" x2="12" y2="2"></line><line x1="12" y1="22" x2="12" y2="18"></line></svg>',
            'badge' => null
        ],
        [
            'slug' => 'career-plan',
            'title' => 'Individual Career Plan',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>',
            'badge' => null
        ],
        [
            'slug' => 'status-suksesi',
            'title' => 'Status Suksesi',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
            'badge' => null
        ],
        [
            'slug' => 'development-gap',
            'title' => 'Development Gap',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>',
            'badge' => null
        ],
        [
            'slug' => 'idp',
            'title' => 'Individual Development Plan',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polygon points="12 8 8 12 12 16 16 12 12 8"></polygon></svg>',
            'badge' => null
        ],
        [
            'slug' => 'review-pengembangan',
            'title' => 'Review Hasil Pengembangan',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
            'badge' => null
        ],
        [
            'slug' => 'riwayat-pelatihan',
            'title' => 'Riwayat Pelatihan',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
            'badge' => 'BARU'
        ],
    ];
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
        {{-- Navigation Menu Utama (Beranda & Data Karyawan) --}}
        <nav class="sidebar-nav" style="padding-top:2px; padding-bottom:2px;">
            {{-- 0. Beranda (Dashboard Karyawan) --}}
            <a href="{{ route('beranda') }}" 
               class="nav-item {{ $isBeranda ? 'active' : '' }}"
               title="Dashboard Karyawan"
               data-title="Beranda">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </span>
                <span>Beranda</span>
            </a>

            {{-- 0b. Data Karyawan --}}
            <a href="{{ route('data-karyawan') }}" 
               class="nav-item {{ $isDataKaryawan ? 'active' : '' }}"
               title="Data Karyawan"
               data-title="Data Karyawan">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </span>
                <span>Data Karyawan</span>
            </a>
        </nav>

        {{-- Search NIK Box (Tepat Di Bawah Data Karyawan) --}}
        <div class="sidebar-search-box" style="margin:3px 0 5px 0; padding:8px 12px 10px 12px; border-top:1px solid rgba(255, 255, 255, 0.08); border-bottom:1px solid rgba(255, 255, 255, 0.08);">
            <div class="search-label" style="font-size:10px; margin-bottom:5px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                Pencarian Profil
            </div>
            <form action="{{ route('karyawan.search') }}" method="GET">
                <input type="hidden" name="tab" value="{{ $activeTab ?? 'profil-individu' }}">
                <div class="search-input-wrap">
                    <input 
                        type="text" 
                        id="sidebar-nik-search" 
                        name="nik" 
                        value="{{ request('nik', ($hasSelectedEmployee ? $employee->nik : '')) }}" 
                        placeholder="Ketik NIK / Nama..." 
                        autocomplete="off"
                        required
                        style="height:31px; font-size:12px;"
                    >
                    <button type="submit" class="search-btn" title="Cari NIK / Nama">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
                {{-- Live suggestion box --}}
                <div id="search-suggestions" class="search-suggestions"></div>
            </form>
        </div>

        {{-- Informasi Status Karyawan Terpilih / Peringatan Belum Dipilih --}}
        @if($hasSelectedEmployee)
            <div class="sidebar-employee-badge" style="margin:2px 10px 4px 10px; padding:7px 10px; background:rgba(15, 53, 92, 0.7); border:1px solid rgba(59, 130, 246, 0.4); border-radius:6px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <img src="{{ $employee->avatar ?: '/images/avatar-budi.png' }}" alt="{{ $employee->name }}" style="width:28px; height:28px; border-radius:50%; object-fit:cover; border:1.5px solid #38bdf8; flex-shrink:0;">
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:11.5px; font-weight:700; color:#ffffff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $employee->name }}">
                            {{ $employee->name }}
                        </div>
                        <div style="font-size:10px; color:#93c5fd; font-weight:600;">
                            NIK: {{ $employee->nik }}
                        </div>
                    </div>
                    <a href="{{ route('data-karyawan') }}" title="Ganti karyawan / kembali ke tabel" style="color:#cbd5e1; font-size:10px; padding:2px 6px; border-radius:4px; background:rgba(255,255,255,0.12); text-decoration:none; font-weight:600; white-space:nowrap;">
                        Ganti
                    </a>
                </div>
            </div>

            <div class="sidebar-section-header" style="padding:6px 14px 2px 14px; font-size:9.5px; font-weight:800; color:#8da4c4; letter-spacing:0.8px; text-transform:uppercase;">
                Detail Talenta (9 Tab)
            </div>
        @else
            <div class="sidebar-no-emp-box" style="margin:2px 10px 4px 10px; padding:6px 10px; background:rgba(30, 41, 59, 0.5); border:1px dashed rgba(245, 158, 11, 0.4); border-radius:6px;">
                <div style="display:flex; align-items:center; gap:7px;">
                    <div style="width:20px; height:20px; border-radius:50%; background:rgba(245, 158, 11, 0.18); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:10.5px; font-weight:700; color:#fbbf24;">Belum Ada Karyawan</div>
                        <div style="font-size:9px; color:#94a3b8; line-height:1.2;">Pilih dari tabel / cari NIK</div>
                    </div>
                </div>
            </div>

            <div class="sidebar-section-header" style="padding:6px 14px 2px 14px; font-size:9.5px; font-weight:800; color:#64748b; letter-spacing:0.8px; text-transform:uppercase; display:flex; align-items:center; justify-content:space-between;">
                <span>Detail Talenta (9 Tab)</span>
                <span style="font-size:8.5px; padding:1px 4px; border-radius:3px; background:rgba(245, 158, 11, 0.15); color:#fbbf24; border:1px solid rgba(245, 158, 11, 0.3); font-weight:700;">TERKUNCI</span>
            </div>
        @endif

        {{-- 9 Navigation Menu Items (Profil Talenta) --}}
        <nav class="sidebar-nav" style="padding-top:2px; padding-bottom:6px;">
            @foreach($talentTabs as $tabItem)
                @if($hasSelectedEmployee)
                    {{-- Kondisi 1: Karyawan sudah dipilih -> Tab Aktif Normal --}}
                    <a href="{{ route('karyawan.show', ['nik' => $currentNik, 'tab' => $tabItem['slug']]) }}" 
                       class="nav-item {{ $activeTab === $tabItem['slug'] ? 'active' : '' }}"
                       title="{{ $tabItem['title'] }}"
                       data-title="{{ $tabItem['title'] }}">
                        <span class="nav-item-icon">
                            {!! $tabItem['icon'] !!}
                        </span>
                        <span>{{ $tabItem['title'] }}</span>
                        @if(!empty($tabItem['badge']))
                            <span class="nav-item-badge">{{ $tabItem['badge'] }}</span>
                        @endif
                    </a>
                @else
                    {{-- Kondisi 2: Karyawan belum dipilih -> Tab Terkunci dengan Alert saat diklik --}}
                    <a href="javascript:void(0);" 
                       onclick="showNoEmployeeAlert(event, '{{ $tabItem['title'] }}')"
                       class="nav-item nav-item-locked"
                       title="{{ $tabItem['title'] }} (Pilih karyawan terlebih dahulu)"
                       data-title="{{ $tabItem['title'] }}">
                        <span class="nav-item-icon" style="opacity:0.7;">
                            {!! $tabItem['icon'] !!}
                        </span>
                        <span>{{ $tabItem['title'] }}</span>
                        <span class="nav-item-lock-icon" style="margin-left:auto; color:#f59e0b; opacity:0.85; display:flex; align-items:center;" title="Terkunci: Pilih karyawan terlebih dahulu">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>

    {{-- Bottom Footer Button --}}
    <div class="sidebar-footer">
        @if($hasSelectedEmployee)
            <a href="{{ route('data-karyawan') }}" class="btn-back-daftar" title="Kembali ke Data Karyawan" data-title="Data Karyawan">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Ganti Karyawan</span>
            </a>
        @else
            <a href="{{ route('data-karyawan') }}" class="btn-back-daftar" title="Buka Data Karyawan" data-title="Data Karyawan">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
                <span>Buka Data Karyawan</span>
            </a>
        @endif
    </div>
</aside>

{{-- Modal Interaktif: Alert Peringatan Karyawan Belum Dipilih --}}
<div id="modal-no-employee-alert" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(15,23,42,0.65); backdrop-filter:blur(3px); z-index:999999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#ffffff; width:100%; max-width:440px; border-radius:12px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.35); overflow:hidden; border:1px solid #e2e8f0;">
        {{-- Modal Header --}}
        <div style="background:linear-gradient(135deg, #0b2545 0%, #1e3a63 100%); padding:20px 22px; color:#ffffff; display:flex; align-items:center; gap:14px; position:relative;">
            <div style="width:42px; height:42px; border-radius:10px; background:rgba(245, 158, 11, 0.2); border:1.5px solid #f59e0b; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
            <div>
                <h3 style="margin:0; font-size:16px; font-weight:800; letter-spacing:-0.2px;">Pilih Karyawan Terlebih Dahulu</h3>
                <p style="margin:2px 0 0 0; font-size:12px; color:#cbd5e1;">Akses menu detail talenta memerlukan karyawan aktif</p>
            </div>
            <button type="button" onclick="closeNoEmployeeAlert()" style="position:absolute; right:14px; top:14px; background:transparent; border:none; color:#cbd5e1; cursor:pointer; padding:4px;" title="Tutup">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        
        {{-- Modal Body --}}
        <div style="padding:22px; color:#334155; font-size:13.5px; line-height:1.55;">
            <p style="margin:0 0 12px 0;">
                Anda mengklik menu <strong id="alert-tab-name-target" style="color:#0b2545; background:#eff6ff; padding:2px 8px; border-radius:4px; border:1px solid #bfdbfe;">Profil Individu</strong>.
            </p>
            <p style="margin:0 0 8px 0; color:#475569;">
                Untuk melihat <strong>9 tab detail talenta</strong> (Profil, Karir, Talent Snapshot, dsb.), Anda harus menentukan profil karyawan yang ingin dilihat terlebih dahulu.
            </p>
            <div style="background:#f8fafc; border-left:3px solid #f59e0b; padding:10px 12px; border-radius:0 6px 6px 0; font-size:12px; color:#64748b; margin-top:10px;">
                💡 <strong>Cara membuka:</strong> Klik tombol <em>Profil</em> pada salah satu baris di halaman <strong>Data Karyawan</strong>, atau ketik NIK/Nama pada kotak pencarian di sidebar.
            </div>
        </div>
        
        {{-- Modal Footer --}}
        <div style="padding:14px 22px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px; flex-wrap:wrap;">
            <button type="button" onclick="focusSidebarSearch()" class="btn btn-outline" style="font-size:12px; padding:7px 13px; border:1px solid #cbd5e1; background:#ffffff; color:#334155; display:inline-flex; align-items:center; gap:5px; cursor:pointer; border-radius:6px; font-weight:600;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <span>Cari NIK / Nama</span>
            </button>
            <a href="{{ route('data-karyawan') }}" class="btn btn-primary" style="font-size:12px; padding:7px 15px; background:#0b2545; color:#ffffff; text-decoration:none; display:inline-flex; align-items:center; gap:6px; border-radius:6px; font-weight:700;">
                <span>Buka Data Karyawan &rarr;</span>
            </a>
        </div>
    </div>
</div>

<script>
    function showNoEmployeeAlert(e, tabName) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        var modal = document.getElementById('modal-no-employee-alert');
        var targetEl = document.getElementById('alert-tab-name-target');
        if (targetEl) targetEl.textContent = tabName || 'Detail Talenta';
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
                searchInput.style.boxShadow = '0 0 0 3px rgba(37, 99, 235, 0.4)';
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
