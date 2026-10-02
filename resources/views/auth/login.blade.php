<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MAP-IN: Talent & Career Management</title>
    <link rel="stylesheet" href="/css/map-in.css">
    <style>
        .login-page-body {
            background: linear-gradient(135deg, #0d1b2e 0%, #1a2a44 50%, #243b5e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 460px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-header {
            background: linear-gradient(135deg, #1a2a44 0%, #2b456e 100%);
            color: #ffffff;
            padding: 32px 28px;
            text-align: center;
            position: relative;
        }

        .login-logo {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 6px;
        }

        .login-logo-tag {
            background: #38bdf8;
            color: #0f172a;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .login-sub {
            font-size: 13px;
            color: #cbd5e1;
            margin-top: 4px;
        }

        .login-body {
            padding: 28px;
        }

        .login-group {
            margin-bottom: 18px;
        }

        .login-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .login-input {
            width: 100%;
            padding: 10px 14px;
            font-size: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .login-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .login-btn {
            width: 100%;
            background: #1a2a44;
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-btn:hover {
            background: #25406b;
        }

        .quick-login-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 24px 0 16px;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .quick-login-divider::before,
        .quick-login-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .quick-login-divider:not(:empty)::before {
            margin-right: 12px;
        }

        .quick-login-divider:not(:empty)::after {
            margin-left: 12px;
        }

        .quick-role-cards {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .quick-role-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #1e293b;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .quick-role-btn:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
            transform: translateY(-1px);
        }

        .role-badge-super {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 9999px;
        }

        .role-badge-hr {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 9999px;
        }

        .quick-role-info {
            text-align: left;
        }

        .quick-role-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .quick-role-desc {
            font-size: 11px;
            color: #64748b;
        }

        .login-footer-info {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #64748b;
        }

        .login-footer-info a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body class="login-page-body">
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">
                <span>MAP-IN</span>
                <span class="login-logo-tag">TALENT v1.0</span>
            </div>
            <div class="login-sub">Talent & Career Management System</div>
        </div>

        <div class="login-body">
            @if(session('error'))
                <div class="alert-box alert-error" style="margin-bottom: 16px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="alert-box alert-success" style="margin-bottom: 16px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="login-group">
                    <label class="login-label" for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" class="login-input" value="{{ old('email', 'superadmin@map-in.com') }}" required autofocus placeholder="nama@map-in.com">
                </div>

                <div class="login-group">
                    <label class="login-label" for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" class="login-input" value="admin123" required placeholder="Masukkan kata sandi">
                </div>

                <button type="submit" class="login-btn" id="btn-login-submit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                    Masuk ke Sistem
                </button>
            </form>

            <div class="quick-login-divider">Atau Masuk Cepat (Demo Mode)</div>

            <div class="quick-role-cards">
                <a href="{{ route('quick-login', ['role' => 'super_admin']) }}" class="quick-role-btn" id="btn-quick-superadmin">
                    <div class="quick-role-info">
                        <div class="quick-role-title">Super Admin</div>
                        <div class="quick-role-desc">Hak akses penuh (+ Tambah, Edit & Hapus Karyawan)</div>
                    </div>
                    <span class="role-badge-super">SUPER ADMIN</span>
                </a>

                <a href="{{ route('quick-login', ['role' => 'hr_admin']) }}" class="quick-role-btn" id="btn-quick-hradmin">
                    <div class="quick-role-info">
                        <div class="quick-role-title">HR Admin</div>
                        <div class="quick-role-desc">Kelola Karir, IDP, Asesmen, Suksesi & Pelatihan</div>
                    </div>
                    <span class="role-badge-hr">HR ADMIN</span>
                </a>
            </div>

            <div class="login-footer-info">
                <span>Ingin melihat tanpa login? </span>
                <a href="{{ route('karyawan.index') }}">Lanjutkan sebagai Tamu &rarr;</a>
            </div>

        </div>
    </div>
</body>
</html>
