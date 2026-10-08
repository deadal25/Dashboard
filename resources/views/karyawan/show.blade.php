@extends('layouts.app', ['title' => 'MAP-IN - ' . $employee->name . ' (' . $employee->nik . ')'])

@section('content')
    {{-- Page Header Title (Matching Mockup) --}}
    <div style="margin-bottom:14px;">
        <h1 style="font-size:16px; font-weight:800; color:#0b2545; letter-spacing:0.5px; text-transform:uppercase; margin:0;">
            {{ $tab === 'talent-snapshot' ? 'TALENT SNAPSHOT' : ucwords(str_replace('-', ' ', $tab)) }}
        </h1>
    </div>

    {{-- Top Employee Profile Header Card --}}
    @include('components.employee-header', ['employee' => $employee])

    {{-- Active Tab View --}}
    @include('karyawan.tabs.' . $tab, [
        'employee' => $employee,
        'gapSummary' => $gapSummary ?? null,
        'trainingSummary' => $trainingSummary ?? null,
        'trainingYears' => $trainingYears ?? collect()
    ])

    {{-- Interactive Modals --}}
    @include('components.modals', ['employee' => $employee])
    @include('components.employee-crud-modals')
@endsection

