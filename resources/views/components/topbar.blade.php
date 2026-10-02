<header class="topbar">
    <div class="topbar-brand">
        <a href="{{ route('beranda') }}" style="display:flex; align-items:baseline; gap:12px;">
            <span class="brand-title">MAP-IN</span>
            <span class="brand-subtitle">TALENT & CAREER MANAGEMENT</span>
        </a>
    </div>

    <div class="topbar-right">
        @if(Auth::check())
            @php $user = Auth::user(); @endphp
            <div class="user-menu-wrapper">
                <div class="user-menu" id="user-menu-btn" title="Klik untuk menu akun">
                    <div class="user-icon-circle">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="user-info-text">
                        <span class="user-name">{{ $user->name }}</span>
                        <span class="role-badge-topbar {{ $user->isSuperAdmin() ? 'super' : 'hr' }}">
                            {{ $user->isSuperAdmin() ? 'SUPER ADMIN' : 'HR ADMIN' }}
                        </span>
                    </div>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>

                {{-- User Dropdown Menu --}}
                <div class="user-dropdown" id="user-dropdown-menu">
                    <div class="dropdown-user-header">
                        <div class="dropdown-user-name">{{ $user->name }}</div>
                        <div class="dropdown-user-email">{{ $user->email }}</div>
                        <span class="dropdown-role-label {{ $user->isSuperAdmin() ? 'super' : 'hr' }}">
                            Peran: {{ $user->role_name }}
                        </span>
                    </div>

                    <div class="dropdown-section-title">Ganti Peran Cepat</div>
                    <a href="{{ route('quick-login', ['role' => 'super_admin']) }}" class="dropdown-item {{ $user->isSuperAdmin() ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                        <span>Super Admin (Kelola Karyawan)</span>
                    </a>
                    <a href="{{ route('quick-login', ['role' => 'hr_admin']) }}" class="dropdown-item {{ $user->isHrAdmin() ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                        <span>HR Admin (Talent & IDP)</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dropdown-item logout">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="user-menu-wrapper">
                <a href="{{ route('login') }}" class="user-menu" style="text-decoration:none;">
                    <div class="user-icon-circle">?</div>
                    <div class="user-info-text">
                        <span class="user-name">Tamu</span>
                        <span class="role-badge-topbar guest">Klik untuk Login</span>
                    </div>
                </a>
            </div>
        @endif
    </div>
</header>

