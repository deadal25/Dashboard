@extends('layouts.app', ['title' => 'MAP-IN - Dashboard Karyawan'])

@section('content')
<div class="directory-container">
    {{-- Top Overview Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
        <div>
            <h1 style="font-size:22px; font-weight:800; color:#0b2545; margin:0;">Dashboard Karyawan</h1>
            <p style="font-size:12.5px; color:#64748b; margin-top:2px;">Ringkasan eksekutif pemetaan talenta dan monitoring risiko terbang karyawan.</p>
        </div>
        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            <a href="{{ route('data-karyawan') }}" class="btn btn-primary" style="display:flex; align-items:center; gap:7px; background:#0b233e; padding:8px 16px; font-weight:600; font-size:12.5px; text-decoration:none; border-radius:5px; box-shadow:0 2px 6px rgba(11,35,62,0.18);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <span>Buka Data Karyawan ({{ $stats['total'] }}) &rarr;</span>
            </a>
            @if(Auth::check() && !Auth::user()->isSuperAdmin())
                <span class="role-badge-topbar hr" style="padding:6px 12px; font-size:11px;">Mode HR Admin</span>
            @endif
        </div>
    </div>

    {{-- 4 Stat Metric Cards --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="stat-card-single">
            <div class="stat-icon-wrapper" style="background:#eff6ff; color:#1d4ed8;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-content">
                <span class="stat-title">Total Karyawan</span>
                <span class="stat-number">{{ $stats['total'] }}</span>
                <span style="font-size:10.5px; color:#64748b;">Karyawan Terdaftar</span>
            </div>
        </div>

        <div class="stat-card-single">
            <div class="stat-icon-wrapper" style="background:#f0fdf4; color:#16a34a;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-content">
                <span class="stat-title">Talent Pool</span>
                <span class="stat-number">{{ $stats['talent_pool'] }}</span>
                <span style="font-size:10.5px; color:#16a34a; font-weight:600;">Kandidat Unggulan</span>
            </div>
        </div>

        <div class="stat-card-single">
            <div class="stat-icon-wrapper" style="background:#fff7ed; color:#ea580c;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
            </div>
            <div class="stat-content">
                <span class="stat-title">Medium Flying Risk</span>
                <span class="stat-number">{{ $stats['medium_risk'] }}</span>
                <span style="font-size:10.5px; color:#ea580c;">Perlu Monitoring</span>
            </div>
        </div>

        <div class="stat-card-single">
            <div class="stat-icon-wrapper" style="background:#fef2f2; color:#dc2626;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <div class="stat-content">
                <span class="stat-title">High Flying Risk</span>
                <span class="stat-number">{{ $stats['high_risk'] }}</span>
                <span style="font-size:10.5px; color:#dc2626;">Perhatian Khusus</span>
            </div>
        </div>
    </div>

    {{-- 16 HAV Box Matrix Widget (Tampil di Beranda Super Admin & HR Admin) --}}
    @php
        $rows = ['R3', 'R2', 'R1', 'R0'];
        $cols = ['C0', 'C1', 'C2', 'C3'];
        $rowMeta = [
            'R3' => ['label' => 'Far Above Target', 'sub' => 'Kinerja Sangat Tinggi (≥ 21)'],
            'R2' => ['label' => 'Above Target', 'sub' => 'Kinerja di Atas Target (16–20)'],
            'R1' => ['label' => 'Meet Target', 'sub' => 'Kinerja Sesuai Target (12–15)'],
            'R0' => ['label' => 'Below Target', 'sub' => 'Kinerja di Bawah Target (1–11)'],
        ];
        $colMeta = [
            'C0' => ['label' => 'Low', 'sub' => '< 50%'],
            'C1' => ['label' => 'Medium', 'sub' => '50%–60%'],
            'C2' => ['label' => 'High', 'sub' => '61%–70%'],
            'C3' => ['label' => 'Very High', 'sub' => '≥ 71%'],
        ];
        $talentPoolBoxNumbers = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $totalTalentPool = 0;
        foreach ($talentPoolBoxNumbers as $b) {
            $totalTalentPool += ($havBoxCounts[$b] ?? 0);
        }
        $totalNonTalentPool = ($stats['total'] ?? 0) - $totalTalentPool;
    @endphp

    <div class="dashboard-card" id="card-superadmin-16havbox" style="margin-bottom:24px; padding:22px 24px; border-radius:10px; background:#ffffff; border:1px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        {{-- Header Bar Widget --}}
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
            <div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; border-radius:8px; background:#0b2545; color:#ffffff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    </div>
                    <div>
                        <h2 style="font-size:17px; font-weight:800; color:#0b2545; margin:0; letter-spacing:-0.2px;">
                            Pemetaan Talenta
                        </h2>
                        <p style="font-size:12px; color:#64748b; margin-top:2px;">
                            Distribusi seluruh karyawan berdasarkan matriks Kinerja (Sumbu Y) dan Potensi (Sumbu X) serta zonasi Talent Pool.
                        </p>
                    </div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <div style="display:inline-flex; align-items:center; gap:6px; background:#f0fdf4; border:1px solid #bbf7d0; padding:5px 12px; border-radius:6px; font-size:11.5px; font-weight:700; color:#15803d;">
                    <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#16a34a;"></span>
                    <span>Talent Pool: {{ $totalTalentPool }} Karyawan</span>
                </div>
                <div style="display:inline-flex; align-items:center; gap:6px; background:#f8fafc; border:1px solid #e2e8f0; padding:5px 12px; border-radius:6px; font-size:11.5px; font-weight:700; color:#475569;">
                    <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#94a3b8;"></span>
                    <span>Non-Talent Pool: {{ $totalNonTalentPool }} Karyawan</span>
                </div>
                <a href="{{ route('data-karyawan') }}" class="btn-mini" style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; font-size:11.5px; padding:5px 12px; border-radius:6px; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:4px;" title="Kelola data karyawan di tabel lengkap">
                    <span>Kelola di Data Karyawan &rarr;</span>
                </a>
            </div>
        </div>

        {{-- Container Grid 16 HAV Box yang Besar --}}
        <div style="overflow-x:auto; padding-bottom:6px;">
            <div style="min-width:820px; display:flex;">
                {{-- Sumbu Y: KINERJA Label --}}
                <div style="width:36px; display:flex; align-items:center; justify-content:center; writing-mode:vertical-rl; transform:rotate(180deg); font-weight:800; font-size:11px; color:#0b2545; letter-spacing:1px; background:#f1f5f9; border-radius:6px 0 0 6px; border:1.5px solid #cbd5e1; border-right:none; padding:10px 0;">
                    ▲ KINERJA (PERFORMANCE)
                </div>

                {{-- Matriks & Row Labels --}}
                <div style="flex:1; display:flex; flex-direction:column;">
                    <div style="display:grid; grid-template-columns: 140px repeat(4, 1fr); border:1.5px solid #cbd5e1; border-radius:0 6px 0 0; overflow:hidden;">
                        @foreach($rows as $rKey)
                            {{-- Row Label (Kiri) --}}
                            <div style="background:#f8fafc; border-bottom:1px solid #cbd5e1; border-right:1px solid #cbd5e1; padding:10px; display:flex; flex-direction:column; justify-content:center; text-align:right;">
                                <strong style="font-size:12px; color:#0b2545;">{{ $rowMeta[$rKey]['label'] }}</strong>
                                <small style="font-size:10px; color:#64748b;">{{ $rowMeta[$rKey]['sub'] }}</small>
                            </div>

                            {{-- 4 Kolom di Baris ini --}}
                            @foreach($cols as $cKey)
                                @php
                                    $cell = $havMatrixMap[$rKey][$cKey];
                                    $boxNumber = $cell['box'];
                                    $count = $havBoxCounts[$boxNumber] ?? 0;
                                    $isTalentPool = ($cell['talent_pool'] === 'YA');
                                    $isRedBorderRight = ($cKey === 'C0');
                                    $isRedBorderBottom = ($rKey === 'R1');
                                    
                                    // Kontras warna teks
                                    $darkTextColor = ($cell['color'] === '#ffffa5' || $cell['color'] === '#9dde58');
                                    $primaryTextColor = $darkTextColor ? '#0f172a' : '#ffffff';
                                @endphp
                                <a href="{{ route('data-karyawan', ['hav_box' => 'Box ' . $boxNumber]) }}" 
                                   title="Klik untuk membuka data karyawan di {{ $cell['name'] }}"
                                   style="
                                        display: flex;
                                        flex-direction: column;
                                        justify-content: center;
                                        align-items: center;
                                        padding: 16px 14px;
                                        min-height: 105px;
                                        text-decoration: none;
                                        background-color: {{ $cell['color'] }};
                                        color: {{ $primaryTextColor }};
                                        position: relative;
                                        border-right: {{ $isRedBorderRight ? '3px solid #dc2626 !important' : '1px solid rgba(0,0,0,0.1)' }};
                                        border-bottom: {{ $isRedBorderBottom ? '3px solid #dc2626 !important' : '1px solid rgba(0,0,0,0.1)' }};
                                        transition: transform 0.15s ease, box-shadow 0.15s ease;
                                   "
                                   onmouseover="this.style.transform='scale(1.02)'; this.style.zIndex='8'; this.style.boxShadow='0 6px 14px rgba(0,0,0,0.15)';"
                                   onmouseout="this.style.transform='none'; this.style.zIndex='1'; this.style.boxShadow='none';">
                                    
                                    {{-- Bagian Tengah: Nama / Kategori Status (STAR, FUTURE STAR, dll) --}}
                                    <div style="margin-bottom:10px; text-align:center;">
                                        <div style="font-size:13.5px; font-weight:800; line-height:1.25; text-transform:uppercase; letter-spacing:0.2px; text-shadow: {{ $darkTextColor ? 'none' : '0 1px 2px rgba(0,0,0,0.25)' }};">
                                            {{ $cell['name'] }}
                                        </div>
                                    </div>

                                    {{-- Baris Bawah: Jumlah Karyawan --}}
                                    <div style="display:flex; justify-content:center; align-items:center;">
                                        <div style="
                                            display: inline-flex;
                                            align-items: center;
                                            gap: 5px;
                                            padding: 4px 12px;
                                            border-radius: 12px;
                                            background: {{ $count > 0 ? '#ffffff' : 'rgba(255,255,255,0.75)' }};
                                            color: {{ $count > 0 ? '#0b2545' : '#64748b' }};
                                            font-size: 11.5px;
                                            font-weight: {{ $count > 0 ? '800' : '600' }};
                                            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                                        ">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                            <span><strong>{{ $count }}</strong> Karyawan</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endforeach
                    </div>

                    {{-- Sumbu X: Kolom Labels (Bawah) --}}
                    <div style="display:grid; grid-template-columns: 140px repeat(4, 1fr); border:1.5px solid #cbd5e1; border-top:none; border-radius:0 0 6px 0; background:#f8fafc;">
                        <div style="padding:8px 10px; border-right:1px solid #cbd5e1;"></div>
                        @foreach($cols as $cKey)
                            <div style="padding:8px 10px; text-align:center; border-right:{{ $cKey !== 'C3' ? '1px solid #cbd5e1' : 'none' }};">
                                <strong style="font-size:12px; color:#0b2545; display:block;">{{ $colMeta[$cKey]['label'] }}</strong>
                                <small style="font-size:10px; color:#64748b;">{{ $colMeta[$cKey]['sub'] }}</small>
                            </div>
                        @endforeach
                    </div>

                    {{-- Title Sumbu X --}}
                    <div style="text-align:center; padding:6px 0 0; font-size:12px; font-weight:800; color:#0b2545; letter-spacing:0.8px;">
                        POTENSI (POTENTIAL) ▶
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Legend & Info Navigasi --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:14px; padding-top:12px; border-top:1px solid #e2e8f0; flex-wrap:wrap; gap:10px; font-size:11.5px; color:#64748b;">
            <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                <div style="display:inline-flex; align-items:center; gap:6px;">
                    <span style="display:inline-block; width:16px; height:3px; background:#dc2626;"></span>
                    <strong style="color:#dc2626;">Garis Merah Tebal:</strong>
                    <span>Batas Minimum Kualifikasi Talent Pool</span>
                </div>
            </div>
            <div style="font-style:italic; color:#475569;">
                💡 <em>Klik pada kotak mana pun di atas untuk membuka dan memfilter daftar karyawan di menu <strong>Data Karyawan</strong>.</em>
            </div>
        </div>
    </div>

    {{-- Pratinjau Karyawan Terkini & Akses Cepat ke Data Karyawan --}}
    <div class="dashboard-card" style="margin-bottom:24px; padding:20px 24px; border-radius:10px; background:#ffffff; border:1px solid #e2e8f0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
            <div>
                <h3 style="font-size:16px; font-weight:800; color:#0b2545; margin:0;">Pratinjau Data Karyawan Terkini</h3>
                <p style="font-size:12px; color:#64748b; margin:2px 0 0 0;">Daftar 5 profil karyawan terbaru. Pengelolaan master data, filter pencarian, tambah manual, dan impor excel tersedia di menu Data Karyawan.</p>
            </div>
            <a href="{{ route('data-karyawan') }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:6px; font-size:12px; padding:7px 14px; text-decoration:none;">
                <span>Buka Seluruh Data Karyawan ({{ $stats['total'] }}) &rarr;</span>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%;">No.</th>
                        <th style="width:25%;">Karyawan</th>
                        <th class="text-center" style="width:11%;">NIK</th>
                        <th style="width:18%;">Departemen / Seksi</th>
                        <th class="text-center" style="width:12%;">Grade Saat Ini</th>
                        <th class="text-center" style="width:9%;">Talent Pool</th>
                        <th class="text-center" style="width:9%;">Flying Risk</th>
                        <th class="text-center" style="width:11%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEmployees as $emp)
                        <tr>
                            <td class="text-center" style="font-weight:600; color:#64748b;">{{ $loop->iteration }}</td>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <img src="{{ $emp->avatar ?: '/images/avatar-budi.png' }}" alt="{{ $emp->name }}" style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:2px solid #2563eb;">
                                    <div>
                                        <div style="font-weight:700; color:#0f172a; font-size:13px;">{{ $emp->name }}</div>
                                        <div style="font-size:11px; color:#64748b;">{{ $emp->position }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center font-bold" style="color:#0056b3;">{{ $emp->nik }}</td>
                            <td>
                                <div style="font-weight:600;">{{ $emp->department }}</div>
                                <div style="font-size:11px; color:#64748b;">{{ $emp->section ?? '-' }}</div>
                            </td>
                            <td class="text-center font-bold">{{ $emp->current_job_class }} / {{ $emp->current_grade }}</td>
                            <td class="text-center">
                                @if($emp->talent_pool_status === 'YA')
                                    <span class="badge-green">YA</span>
                                @else
                                    <span class="badge-table-gray">TIDAK</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="{{ $emp->flying_risk_badge_class }}">
                                    {{ $emp->flying_risk_formatted }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('karyawan.show', ['nik' => $emp->nik, 'tab' => 'profil-individu']) }}" class="btn btn-primary" style="padding:4px 10px; font-size:11.5px;" title="Lihat Profil">
                                    Profil
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding: 24px;">
                                Belum ada data karyawan yang terdaftar di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="text-align:center; padding:12px; margin-top:12px; background:#f8fafc; border-radius:6px; border:1px solid #e2e8f0;">
            <a href="{{ route('data-karyawan') }}" style="font-size:12.5px; font-weight:700; color:#0056b3; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                <span>Kelola Data Lengkap, Tambah Manual & Impor Excel di Menu Data Karyawan</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </div>
    </div>
</div>
@endsection
