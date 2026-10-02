@extends('layouts.app', ['title' => 'MAP-IN - Daftar Karyawan'])

@section('content')
<div class="directory-container">
    {{-- Top Overview Stats --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
        <div>
            <h1 style="font-size:22px; font-weight:800; color:#0b2545;">Daftar Profil Karyawan</h1>
            <p style="font-size:12.5px; color:#64748b; margin-top:2px;">Katalog data talenta dan profil karir terintegrasi MAP-IN.</p>
        </div>
        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            @if(Auth::check() && Auth::user()->isSuperAdmin())
                {{-- Tombol Tambah Manual --}}
                <button type="button" class="btn btn-primary" onclick="openModal('modal-tambah-karyawan')" id="btn-tambah-karyawan" style="display:flex; align-items:center; gap:6px; background:#0b233e; padding:8px 14px; font-weight:600; font-size:12px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Manual
                </button>

                {{-- Tombol Impor Excel --}}
                <button type="button" class="btn" onclick="openModal('modal-import-excel')" id="btn-import-excel" style="display:flex; align-items:center; gap:6px; background:#059669; color:#fff; padding:8px 14px; font-weight:600; font-size:12px; border:none; border-radius:4px; cursor:pointer;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="8" y1="13" x2="16" y2="13"></line><line x1="8" y1="17" x2="16" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg>
                    Impor dari Excel
                </button>
            @elseif(Auth::check())
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
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
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

    {{-- Search & Filter Bar --}}
    <div class="dashboard-card" style="margin-bottom:20px; padding:16px 20px;">
        <form action="{{ route('beranda') }}" method="GET" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; align-items:center; gap:12px; flex:1;">
                <div style="position:relative; flex:1; max-width:400px;">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari berdasarkan NIK atau Nama Karyawan..." 
                        style="width:100%; height:36px; padding:0 36px 0 12px; border:1px solid #cbd5e1; border-radius:4px; font-size:12.5px; outline:none;"
                    >
                    <button type="submit" style="position:absolute; right:6px; top:6px; background:transparent; border:none; color:#475569; cursor:pointer;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </div>

                <select name="department" style="height:36px; padding:0 12px; border:1px solid #cbd5e1; border-radius:4px; font-size:12px; color:#1e293b;" onchange="this.form.submit()">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>

                <div style="display:flex; align-items:center; gap:6px;">
                    <span style="font-size:12px; color:#64748b; white-space:nowrap;">Tampilkan:</span>
                    <select name="per_page" style="height:36px; padding:0 10px; border:1px solid #cbd5e1; border-radius:4px; font-size:12px; color:#1e293b; background:#fff; cursor:pointer;" onchange="this.form.submit()" title="Jumlah data per halaman">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 / Halaman</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 / Halaman</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / Halaman</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 / Halaman</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:8px;">
                @if(request('search') || request('department') || (request('per_page') && request('per_page') != 10))
                    <a href="{{ route('beranda') }}" class="btn btn-outline" style="height:36px;">Reset Filter</a>
                @endif
                <button type="submit" class="btn btn-primary" style="height:36px;">Terapkan Filter</button>
            </div>
        </form>
    </div>

    {{-- Employee Table List --}}
    <div class="dashboard-card">
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
                    @forelse($employees as $emp)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
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
                                <span class="{{ $emp->flying_risk === 'LOW' ? 'badge-green' : ($emp->flying_risk === 'HIGH' ? 'badge-red' : 'badge-orange') }}">
                                    {{ $emp->flying_risk }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div style="display:flex; align-items:center; justify-content:center; gap:5px;">
                                    <a href="{{ route('karyawan.show', ['nik' => $emp->nik, 'tab' => 'profil-individu']) }}" class="btn btn-primary" style="padding:4px 8px; font-size:11.5px;" title="Lihat Profil">
                                        Profil
                                    </a>
                                    @if(Auth::check() && Auth::user()->isSuperAdmin())
                                        <button 
                                            type="button" 
                                            class="btn btn-outline btn-edit-karyawan" 
                                            style="padding:4px 7px; font-size:11px; color:#2563eb; border-color:#93c5fd;" 
                                            title="Edit Data Karyawan (Super Admin)"
                                            data-nik="{{ $emp->nik }}"
                                            data-name="{{ $emp->name }}"
                                            data-position="{{ $emp->position }}"
                                            data-department="{{ $emp->department }}"
                                            data-section="{{ $emp->section }}"
                                            data-education="{{ $emp->education }}"
                                            data-age="{{ $emp->age }}"
                                            data-tenure="{{ $emp->tenure_years }}"
                                            data-jobclass="{{ $emp->current_job_class }}"
                                            data-grade="{{ $emp->current_grade }}"
                                            data-grade-since="{{ $emp->grade_since }}"
                                            data-position-since="{{ $emp->position_since }}"
                                        >
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button 
                                            type="button" 
                                            class="btn btn-outline btn-hapus-karyawan" 
                                            style="padding:4px 7px; font-size:11px; color:#dc2626; border-color:#fca5a5;" 
                                            title="Hapus Karyawan (Super Admin)"
                                            data-nik="{{ $emp->nik }}"
                                            data-name="{{ $emp->name }}"
                                        >
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding: 30px;">
                                Tidak ada data karyawan yang cocok dengan pencarian Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Custom Compact & Attractive Pagination --}}
        {{ $employees->links('components.pagination') }}
    </div>
</div>

{{-- Super Admin Modals --}}
@include('components.employee-crud-modals')
@endsection

