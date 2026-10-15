<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MAP-IN: Talent & Career Management' }}</title>
    <link rel="stylesheet" href="{{ asset('css/map-in.css') }}?v={{ filemtime(public_path('css/map-in.css')) }}">
    <!-- Anti-FOUC for Sidebar State: Executes synchronously before paint to keep collapsed sidebar instant -->
    <script>
        (function() {
            try {
                var c = localStorage.getItem('mapin_sidebar_collapsed');
                if (c === 'true' || (window.innerWidth <= 850 && c !== 'false')) {
                    document.documentElement.classList.add('sidebar-is-collapsed');
                }
            } catch (e) {}
        })();
    </script>
    <!-- Bootstrap Icons support -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Global Print Color Preservation: Ensures backgrounds, badges, and charts print in full color across all pages -->
    <style>
        @media print {
            *, *::before, *::after {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            html, body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
        {{-- Topbar --}}
        @include('components.topbar')

        <div class="main-container">
            {{-- Mobile Sidebar Backdrop Overlay --}}
            <div id="sidebar-backdrop" class="sidebar-backdrop"></div>

            {{-- Sidebar with NIK search and navigation --}}
            @include('components.sidebar')

            {{-- Main Content --}}
            <main class="content-area">
                @if(session('error'))
                    <div class="alert-box alert-error">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-box alert-error" style="flex-direction: column; align-items: flex-start; gap: 4px;">
                        <div style="display:flex; align-items:center; gap:8px; font-weight:700;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <span>Terjadi kesalahan pengisian formulir:</span>
                        </div>
                        <ul style="margin:4px 0 0 26px; padding:0; font-size:12px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert-box alert-success">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert-box" style="background-color: #fef3c7; border: 1px solid #fde68a; color: #92400e;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

        {{-- Footer --}}
        <footer class="app-footer">
            <div>© 2026 MAP-IN Talent & Career Management System. All rights reserved.</div>
            <div>Versi 1.0.0</div>
        </footer>

        {{-- Global Interactive Avatar Cropper Modal --}}
        @include('components.avatar-cropper-modal')
    </div>

    <script src="{{ asset('js/map-in.js') }}"></script>

    @stack('scripts')
</body>
</html>
