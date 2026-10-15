@extends('layouts.app', ['title' => 'MAP-IN - ' . $employee->name . ' (' . $employee->nik . ')'])

@section('content')
    @php
        $tabHeaderTitles = [
            'profil-individu' => 'Profil Individu',
            'riwayat-karir' => 'Riwayat Karir',
            'talent-snapshot' => 'Talent Snapshot',
            'individual-careerplan' => 'Individual Career Plan (ICP)',
            'career-plan' => 'Individual Career Plan (ICP)',
            'status-suksesi' => 'Status Suksesi',
            'development-gap' => 'Development Gap',
            'individual-developmentplan' => 'Individual Development Plan (IDP)',
            'idp' => 'Individual Development Plan (IDP)',
            'review-pengembangan' => 'Review Hasil Pengembangan',
            'riwayat-pelatihan' => 'Riwayat Pelatihan',
        ];
        $headerTitle = $tabHeaderTitles[$tab] ?? ucwords(str_replace('-', ' ', $tab));

        // Map route slug to corresponding blade view file in karyawan.tabs
        $tabViewMap = [
            'individual-careerplan' => 'career-plan',
            'individual-developmentplan' => 'idp',
        ];
        $tabView = $tabViewMap[$tab] ?? $tab;
    @endphp

    {{-- Page Header Title (Matching Mockup) --}}
    <div class="page-header-title-bar">
        <h1 class="page-header-heading">{{ $headerTitle }}</h1>
    </div>

    {{-- Top Employee Profile Header Card --}}
    @include('components.employee-header', ['employee' => $employee])

    {{-- Active Tab View --}}
    @include('karyawan.tabs.' . $tabView, [
        'employee' => $employee,
        'gapSummary' => $gapSummary ?? null,
        'trainingSummary' => $trainingSummary ?? null,
        'trainingYears' => $trainingYears ?? collect()
    ])

    {{-- Interactive Modals --}}
    @include('components.modals', ['employee' => $employee])
    @include('components.employee-crud-modals')
@endsection

