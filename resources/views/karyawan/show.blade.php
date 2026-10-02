@extends('layouts.app', ['title' => 'MAP-IN - ' . $employee->name . ' (' . $employee->nik . ')'])

@section('content')
    {{-- Breadcrumb Navigation (Beranda > Daftar Karyawan > Profil Karyawan) --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:8px;">
        <div style="display:flex; align-items:center; gap:8px; font-size:12px; color:#64748b;">
            <a href="{{ route('beranda') }}" style="display:inline-flex; align-items:center; gap:5px; color:#0b2545; font-weight:700; text-decoration:none; transition:color 0.15s;" title="Ke Beranda Karyawan">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                <span>Beranda</span>
            </a>
            <span>/</span>
            <a href="{{ route('beranda') }}" style="color:#475569; text-decoration:none; font-weight:500;">Daftar Karyawan</a>
            <span>/</span>
            <span style="color:#0f172a; font-weight:700;">{{ $employee->name }} (NIK: {{ $employee->nik }})</span>
        </div>

        <div>
            <a href="{{ route('beranda') }}" class="btn btn-outline" style="padding:5px 12px; font-size:11.5px; display:inline-flex; align-items:center; gap:6px; background:#fff; text-decoration:none;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                <span>Kembali ke Beranda Karyawan</span>
            </a>
        </div>
    </div>

    {{-- Top Employee Profile Header Card --}}
    @include('components.employee-header', ['employee' => $employee])

    {{-- Active Tab View --}}
    @include('karyawan.tabs.' . $tab, [
        'employee' => $employee,
        'gapSummary' => $gapSummary ?? null,
        'trainingSummary' => $trainingSummary ?? null
    ])

    {{-- Interactive Modals --}}
    @include('components.modals', ['employee' => $employee])
    @include('components.employee-crud-modals')
@endsection

