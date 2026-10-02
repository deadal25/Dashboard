@php
    $currentNik = isset($employee) ? $employee->nik : '012345';
    $isBeranda = request()->routeIs('beranda') || request()->routeIs('karyawan.index');
    // Tab hanya aktif jika berada di dalam halaman profil karyawan (bukan di beranda)
    $activeTab = $isBeranda ? null : ($tab ?? null);
@endphp

<aside class="sidebar">
    <div>
        {{-- Search NIK Box --}}
        <div class="sidebar-search-box">
            <div class="search-label">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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
                        value="{{ request('nik', $currentNik) }}" 
                        placeholder="Ketik NIK / Nama..." 
                        autocomplete="off"
                        required
                    >
                    <button type="submit" class="search-btn" title="Cari NIK">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
                {{-- Live suggestion box --}}
                <div id="search-suggestions" class="search-suggestions"></div>
            </form>
        </div>

        {{-- 9 Navigation Menu Items --}}
        <nav class="sidebar-nav">
            {{-- 0. Beranda (Kembali ke Halaman Karyawan / Super Admin) --}}
            <a href="{{ route('beranda') }}" 
               class="nav-item {{ $isBeranda ? 'active' : '' }}"
               title="Kembali ke Beranda / Daftar Karyawan Super Admin">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </span>
                <span>Beranda</span>
            </a>

            {{-- 1. Profil Individu --}}
            <a href="{{ route('karyawan.show', ['nik' => $currentNik, 'tab' => 'profil-individu']) }}" 
               class="nav-item {{ $activeTab === 'profil-individu' ? 'active' : '' }}">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </span>
                <span>Profil Individu</span>
            </a>

            {{-- 2. Riwayat Karir --}}
            <a href="{{ route('karyawan.show', ['nik' => $currentNik, 'tab' => 'riwayat-karir']) }}" 
               class="nav-item {{ $activeTab === 'riwayat-karir' ? 'active' : '' }}">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </span>
                <span>Riwayat Karir</span>
            </a>

            {{-- 3. Talent Snapshot --}}
            <a href="{{ route('karyawan.show', ['nik' => $currentNik, 'tab' => 'talent-snapshot']) }}" 
               class="nav-item {{ $activeTab === 'talent-snapshot' ? 'active' : '' }}">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="22" y1="12" x2="18" y2="12"></line>
                        <line x1="6" y1="12" x2="2" y2="12"></line>
                        <line x1="12" y1="6" x2="12" y2="2"></line>
                        <line x1="12" y1="22" x2="12" y2="18"></line>
                    </svg>
                </span>
                <span>Talent Snapshot</span>
            </a>

            {{-- 4. Individual Career Plan --}}
            <a href="{{ route('karyawan.show', ['nik' => $currentNik, 'tab' => 'career-plan']) }}" 
               class="nav-item {{ $activeTab === 'career-plan' ? 'active' : '' }}">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </span>
                <span>Individual Career Plan</span>
            </a>

            {{-- 5. Status Suksesi --}}
            <a href="{{ route('karyawan.show', ['nik' => $currentNik, 'tab' => 'status-suksesi']) }}" 
               class="nav-item {{ $activeTab === 'status-suksesi' ? 'active' : '' }}">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </span>
                <span>Status Suksesi</span>
            </a>

            {{-- 6. Development Gap --}}
            <a href="{{ route('karyawan.show', ['nik' => $currentNik, 'tab' => 'development-gap']) }}" 
               class="nav-item {{ $activeTab === 'development-gap' ? 'active' : '' }}">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="4" y1="21" x2="4" y2="14"></line>
                        <line x1="4" y1="10" x2="4" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12" y2="3"></line>
                        <line x1="20" y1="21" x2="20" y2="16"></line>
                        <line x1="20" y1="12" x2="20" y2="3"></line>
                        <line x1="1" y1="14" x2="7" y2="14"></line>
                        <line x1="9" y1="8" x2="15" y2="8"></line>
                        <line x1="17" y1="16" x2="23" y2="16"></line>
                    </svg>
                </span>
                <span>Development Gap</span>
            </a>

            {{-- 7. Individual Development Plan --}}
            <a href="{{ route('karyawan.show', ['nik' => $currentNik, 'tab' => 'idp']) }}" 
               class="nav-item {{ $activeTab === 'idp' ? 'active' : '' }}">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polygon points="12 8 8 12 12 16 16 12 12 8"></polygon>
                    </svg>
                </span>
                <span>Individual Development Plan</span>
            </a>

            {{-- 8. Review Hasil Pengembangan --}}
            <a href="{{ route('karyawan.show', ['nik' => $currentNik, 'tab' => 'review-pengembangan']) }}" 
               class="nav-item {{ $activeTab === 'review-pengembangan' ? 'active' : '' }}">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </span>
                <span>Review Hasil Pengembangan</span>
            </a>

            {{-- 9. Riwayat Pelatihan [BARU] --}}
            <a href="{{ route('karyawan.show', ['nik' => $currentNik, 'tab' => 'riwayat-pelatihan']) }}" 
               class="nav-item {{ $activeTab === 'riwayat-pelatihan' ? 'active' : '' }}">
                <span class="nav-item-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                </span>
                <span>Riwayat Pelatihan</span>
                <span class="nav-item-badge">BARU</span>
            </a>
        </nav>
    </div>

    {{-- Bottom Footer Button: Kembali ke Beranda --}}
    <div class="sidebar-footer">
        <a href="{{ route('beranda') }}" class="btn-back-daftar">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali ke Beranda</span>
        </a>
    </div>
</aside>
