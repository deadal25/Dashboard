{{-- Tab 7: Individual Development Plan (IDP) --}}
<div class="tab-pane-content">
    {{-- Header Action Bar --}}
    <div class="tab-header-actions-bar">
        <div class="card-actions">
            <button type="button" class="btn btn-primary" data-modal-target="modal-tambah-idp" data-preselect-section="tambah-idp-b">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Data
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-edit-idp" data-preselect-section="edit-idp-summary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Data
            </button>
            <button type="button" class="btn btn-outline" onclick="window.print()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak
            </button>
            <a href="{{ route('karyawan.export', ['nik' => $employee->nik, 'type' => 'idp']) }}" class="btn btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Export
            </a>
        </div>
    </div>

    {{-- A. RINGKASAN KESIAPAN & FOKUS PENGEMBANGAN --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">A. Ringkasan Kesiapan & Fokus Pengembangan</span>
            </div>
        </div>

        <div class="idp-summary-grid-responsive">
            {{-- Gauge Chart: Tingkat Kesiapan --}}
            <div style="text-align:center; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                <div style="font-size:11.5px; font-weight:700; color:#1e293b; margin-bottom:8px; text-transform:uppercase;">
                    Tingkat Kesiapan
                </div>
                {{-- SVG Semi Circle Gauge --}}
                <div style="position:relative; width:130px; height:75px; overflow:hidden;">
                    <svg viewBox="0 0 100 50" style="width:100%; height:100%;">
                        {{-- Background arc --}}
                        <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="#e2e8f0" stroke-width="12" stroke-linecap="round" />
                        {{-- Filled arc --}}
                        <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="#f59e0b" stroke-width="12" stroke-linecap="round" stroke-dasharray="125.66" stroke-dashoffset="{{ 125.66 * (1 - (intval($employee->readiness_level ?: 68) / 100)) }}" />
                    </svg>
                    <div style="position:absolute; bottom:0; left:0; right:0; text-align:center;">
                        <span style="font-size:22px; font-weight:800; color:#0f172a; line-height:1;">{{ $employee->readiness_level ?: '68%' }}</span>
                    </div>
                </div>
                <div style="font-size:11px; color:#475569; font-weight:600; margin-top:4px;">
                    {{ $employee->idp_readiness_desc ?: 'Siap dalam 1–3 Tahun' }}
                </div>
            </div>

            {{-- Kompetensi Prioritas untuk Dikembangkan (Top 3) --}}
            <div style="border-left:1px solid #e2e8f0; padding-left:16px;">
                <div style="font-size:11px; font-weight:700; color:#1e293b; margin-bottom:10px; text-transform:uppercase;">
                    Kompetensi Prioritas untuk Dikembangkan (Top 3)
                </div>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div style="display:flex; align-items:center; gap:6px; font-size:11.5px;">
                            <span class="rank-circle" style="background:#e11d48; width:18px; height:18px; font-size:10px;">1</span>
                            <span style="font-weight:600;">Vision & Business Sense</span>
                        </div>
                        <span class="badge-red" style="font-size:9.5px; padding:1px 6px;">Gap Tinggi</span>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div style="display:flex; align-items:center; gap:6px; font-size:11.5px;">
                            <span class="rank-circle" style="background:#27ae60; width:18px; height:18px; font-size:10px;">2</span>
                            <span style="font-weight:600;">Leading & Motivating</span>
                        </div>
                        <span class="badge-red" style="font-size:9.5px; padding:1px 6px;">Gap Tinggi</span>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div style="display:flex; align-items:center; gap:6px; font-size:11.5px;">
                            <span class="rank-circle" style="background:#f59e0b; width:18px; height:18px; font-size:10px;">3</span>
                            <span style="font-weight:600;">Analysis & Judgement</span>
                        </div>
                        <span class="badge-orange" style="font-size:9.5px; padding:1px 6px;">Gap Sedang</span>
                    </div>
                </div>
            </div>

            {{-- Tujuan Pengembangan Utama --}}
            <div style="border-left:1px solid #e2e8f0; padding-left:16px;">
                <div style="font-size:11px; font-weight:700; color:#1e293b; margin-bottom:8px; text-transform:uppercase;">
                    Tujuan Pengembangan Utama
                </div>
                <div style="font-size:11.5px; color:#475569; line-height:1.6;">
                    {!! nl2br(e($employee->idp_primary_goal ?: ('Mempersiapkan ' . $employee->name . ' untuk mencapai kompetensi pada level yang diharapkan untuk posisi Engineering Manager, sehingga siap menggantikan posisi Section Head – Manufacturing Engineering saat incumbent pensiun (' . ($employee->retirement_year ?: 'April 2044') . ').'))) !!}
                </div>
            </div>

            {{-- Kapan Dibutuhkan (Pensiun Incumbent) --}}
            <div style="border-left:1px solid #e2e8f0; padding-left:16px; display:flex; flex-direction:column; justify-content:center;">
                <div style="font-size:11px; font-weight:700; color:#1e293b; margin-bottom:8px; text-transform:uppercase;">
                    Kapan Dibutuhkan<br><small style="color:#64748b; font-weight:400;">(Pensiun Incumbent)</small>
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:36px; height:36px; border-radius:6px; background:#eff6ff; color:#1d4ed8; display:flex; align-items:center; justify-content:center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <div>
                        <div style="font-size:13px; font-weight:800; color:#0f172a;">{{ $employee->retirement_year ?: 'April 2044' }}</div>
                        <div style="font-size:10.5px; color:#64748b;">(Target Penggantian)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $allIdpPlans = $employee->idpActionPlans;
        $manajerialPlans = $allIdpPlans->filter(function($p) {
            return strtolower($p->competency_type ?? 'manajerial') !== 'technical';
        });
        $technicalPlans = $allIdpPlans->filter(function($p) {
            return strtolower($p->competency_type ?? '') === 'technical';
        });

        $manajerialGrouped = $manajerialPlans->groupBy(function($item) {
            return trim($item->competency);
        });
        $technicalGrouped = $technicalPlans->groupBy(function($item) {
            return trim($item->competency);
        });
    @endphp

    {{-- B. RENCANA PENGEMBANGAN MANAJERIAL --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">B. Rencana Pengembangan Manajerial <span style="font-weight:500; text-transform:none; color:#64748b;">(IDP Action Plan)</span></span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-idp" data-preselect-section="tambah-idp-b" title="Tambah Rencana Aksi IDP Manajerial">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah
                </button>
                <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-idp" data-preselect-section="edit-idp-b" title="Edit Rencana Aksi IDP Manajerial">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="text-center" style="width:4%;">No.</th>
                        <th style="width:14%;">Kompetensi yang Dikembangkan<br><small style="font-weight:400; color:#64748b;">({{ $manajerialGrouped->count() }} Kompetensi, {{ $manajerialPlans->count() }} Rencana)</small></th>
                        <th style="width:16%;">Tujuan Spesifik (Target)</th>
                        <th style="width:11%;">Metode Pengembangan</th>
                        <th style="width:17%;">Aktivitas / Program</th>
                        <th style="width:10%;">PIC / Pendukung</th>
                        <th class="text-center" style="width:6.5%;">Mulai</th>
                        <th class="text-center" style="width:6.5%;">Selesai</th>
                        <th style="width:15%;">Indikator Keberhasilan</th>
                        <th class="text-center" style="width:7.5%;">Status</th>
                        <th class="text-center" style="width:8.5%;">Progres</th>
                    </tr>
                </thead>
                <tbody>
                    @php $compIndex = 1; @endphp
                    @forelse($manajerialGrouped as $compName => $items)
                        @php
                            $rowCount = $items->count();
                            $firstPlan = $items->first();
                        @endphp
                        @foreach($items as $idx => $plan)
                            <tr>
                                @if($idx === 0)
                                    <td rowspan="{{ $rowCount }}" class="text-center" style="vertical-align:middle; font-weight:700; background:#ffffff; border-right:1px solid #e2e8f0;">{{ $compIndex }}</td>
                                    <td rowspan="{{ $rowCount }}" style="vertical-align:middle; font-weight:700; color:#0f172a; background:#ffffff; border-right:1px solid #e2e8f0;">
                                        {{ $compName }}
                                    </td>
                                    <td rowspan="{{ $rowCount }}" style="vertical-align:middle; font-size:11px; color:#334155; line-height:1.55; background:#ffffff; border-right:1px solid #e2e8f0;">
                                        {{ $firstPlan->specific_goal }}
                                    </td>
                                @endif
                                <td style="font-size:11px; vertical-align:middle;">
                                    @php
                                        $methodStyle = match(trim($plan->development_methods)) {
                                            'Training' => 'background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;',
                                            'On the Job' => 'background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;',
                                            'Coaching' => 'background:#faf5ff; color:#7e22ce; border:1px solid #e9d5ff;',
                                            'Mentoring' => 'background:#fffbeb; color:#b45309; border:1px solid #fde68a;',
                                            'Exposure' => 'background:#ecfeff; color:#0e7490; border:1px solid #a5f3fc;',
                                            'Project / Assignment' => 'background:#fff7ed; color:#c2410c; border:1px solid #fed7aa;',
                                            'Workshop' => 'background:#fff1f2; color:#be123c; border:1px solid #fecdd3;',
                                            'Seminar' => 'background:#fdf2f8; color:#a21caf; border:1px solid #fbcfe8;',
                                            'E-Learning / Self Study' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;',
                                            default => 'background:#f1f5f9; color:#334155; border:1px solid #cbd5e1;'
                                        };
                                    @endphp
                                    <span style="display:inline-flex; align-items:center; gap:5px; font-weight:600; padding:3px 8px; border-radius:6px; font-size:11px; {{ $methodStyle }}">
                                        @if(trim($plan->development_methods) === 'Training')
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                                        @elseif(trim($plan->development_methods) === 'On the Job')
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                        @elseif(trim($plan->development_methods) === 'Coaching')
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                        @elseif(trim($plan->development_methods) === 'Mentoring')
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        @elseif(trim($plan->development_methods) === 'Exposure')
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                        @else
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                        @endif
                                        {{ $plan->development_methods }}
                                    </span>
                                </td>
                                <td style="font-size:11px; color:#1e293b; font-weight:600; vertical-align:middle; line-height:1.45;">
                                    {{ $plan->activity_program }}
                                </td>
                                <td style="font-size:11px; color:#475569; vertical-align:middle;">
                                    <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                        @foreach(array_filter(array_map('trim', explode(',', $plan->pic_supporter ?? ''))) as $picItem)
                                            <span style="display:inline-block; background:#f8fafc; border:1px solid #e2e8f0; padding:2px 8px; border-radius:12px; font-weight:600; color:#334155; font-size:10.5px; white-space:nowrap;">
                                                {{ $picItem }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-center" style="font-size:11px; white-space:nowrap; vertical-align:middle; color:#334155; font-weight:600;">{{ $plan->start_date }}</td>
                                <td class="text-center" style="font-size:11px; white-space:nowrap; vertical-align:middle; color:#334155; font-weight:600;">{{ $plan->end_date }}</td>
                                <td style="font-size:11px; color:#334155; vertical-align:middle; line-height:1.45;">{{ $plan->success_indicator }}</td>
                                <td class="text-center" style="vertical-align:middle;">
                                    @if($plan->status === 'On Progress')
                                        <span class="badge-outline-blue">On Progress</span>
                                    @elseif($plan->status === 'Planning')
                                        <span class="badge-outline-orange">Planning</span>
                                    @elseif($plan->status === 'Dibatalkan')
                                        <span class="badge-outline-red">Dibatalkan</span>
                                    @else
                                        <span class="badge-outline-green">{{ $plan->status }}</span>
                                    @endif
                                </td>
                                <td style="vertical-align:middle;">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <div class="progress-bar-wrap" style="flex:1;">
                                            <div class="progress-bar-fill {{ $plan->progress_percent >= 100 ? 'fill-green' : ($plan->progress_percent >= 60 ? 'fill-blue' : 'fill-orange') }}" style="width: {{ $plan->progress_percent }}%;"></div>
                                        </div>
                                        <span style="font-size:11px; font-weight:700; width:28px;">{{ $plan->progress_percent }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @php $compIndex++; @endphp
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted" style="padding: 24px; color:#64748b; font-size:12px;">
                                Belum ada rencana pengembangan manajerial yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- C. RENCANA PENGEMBANGAN TECHNICAL --}}
    <div class="dashboard-card" style="margin-top: 20px;">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">C. Rencana Pengembangan Technical <span style="font-weight:500; text-transform:none; color:#64748b;">(IDP Action Plan)</span></span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-idp" data-preselect-section="tambah-idp-c" title="Tambah Rencana Aksi IDP Technical">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah
                </button>
                <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-idp" data-preselect-section="edit-idp-c" title="Edit Rencana Aksi IDP Technical">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="text-center" style="width:4%;">No.</th>
                        <th style="width:14%;">Kompetensi yang Dikembangkan<br><small style="font-weight:400; color:#64748b;">({{ $technicalGrouped->count() }} Kompetensi, {{ $technicalPlans->count() }} Rencana)</small></th>
                        <th style="width:16%;">Tujuan Spesifik (Target)</th>
                        <th style="width:11%;">Metode Pengembangan</th>
                        <th style="width:17%;">Aktivitas / Program</th>
                        <th style="width:10%;">PIC / Pendukung</th>
                        <th class="text-center" style="width:6.5%;">Mulai</th>
                        <th class="text-center" style="width:6.5%;">Selesai</th>
                        <th style="width:15%;">Indikator Keberhasilan</th>
                        <th class="text-center" style="width:7.5%;">Status</th>
                        <th class="text-center" style="width:8.5%;">Progres</th>
                    </tr>
                </thead>
                <tbody>
                    @php $techIndex = 1; @endphp
                    @forelse($technicalGrouped as $compName => $items)
                        @php
                            $rowCount = $items->count();
                            $firstPlan = $items->first();
                        @endphp
                        @foreach($items as $idx => $plan)
                            <tr>
                                @if($idx === 0)
                                    <td rowspan="{{ $rowCount }}" class="text-center" style="vertical-align:middle; font-weight:700; background:#ffffff; border-right:1px solid #e2e8f0;">{{ $techIndex }}</td>
                                    <td rowspan="{{ $rowCount }}" style="vertical-align:middle; font-weight:700; color:#0f172a; background:#ffffff; border-right:1px solid #e2e8f0;">
                                        {{ $compName }}
                                    </td>
                                    <td rowspan="{{ $rowCount }}" style="vertical-align:middle; font-size:11px; color:#334155; line-height:1.55; background:#ffffff; border-right:1px solid #e2e8f0;">
                                        {{ $firstPlan->specific_goal }}
                                    </td>
                                @endif
                                <td style="font-size:11px; vertical-align:middle;">
                                    @php
                                        $methodStyle = match(trim($plan->development_methods)) {
                                            'Training' => 'background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;',
                                            'On the Job' => 'background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;',
                                            'Coaching' => 'background:#faf5ff; color:#7e22ce; border:1px solid #e9d5ff;',
                                            'Mentoring' => 'background:#fffbeb; color:#b45309; border:1px solid #fde68a;',
                                            'Exposure' => 'background:#ecfeff; color:#0e7490; border:1px solid #a5f3fc;',
                                            'Project / Assignment' => 'background:#fff7ed; color:#c2410c; border:1px solid #fed7aa;',
                                            'Workshop' => 'background:#fff1f2; color:#be123c; border:1px solid #fecdd3;',
                                            'Seminar' => 'background:#fdf2f8; color:#a21caf; border:1px solid #fbcfe8;',
                                            'E-Learning / Self Study' => 'background:#f8fafc; color:#475569; border:1px solid #e2e8f0;',
                                            default => 'background:#f1f5f9; color:#334155; border:1px solid #cbd5e1;'
                                        };
                                    @endphp
                                    <span style="display:inline-flex; align-items:center; gap:5px; font-weight:600; padding:3px 8px; border-radius:6px; font-size:11px; {{ $methodStyle }}">
                                        @if(trim($plan->development_methods) === 'Training')
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                                        @elseif(trim($plan->development_methods) === 'On the Job')
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                        @elseif(trim($plan->development_methods) === 'Coaching')
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                        @elseif(trim($plan->development_methods) === 'Mentoring')
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        @elseif(trim($plan->development_methods) === 'Exposure')
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                        @else
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                        @endif
                                        {{ $plan->development_methods }}
                                    </span>
                                </td>
                                <td style="font-size:11px; color:#1e293b; font-weight:600; vertical-align:middle; line-height:1.45;">
                                    {{ $plan->activity_program }}
                                </td>
                                <td style="font-size:11px; color:#475569; vertical-align:middle;">
                                    <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                        @foreach(array_filter(array_map('trim', explode(',', $plan->pic_supporter ?? ''))) as $picItem)
                                            <span style="display:inline-block; background:#f8fafc; border:1px solid #e2e8f0; padding:2px 8px; border-radius:12px; font-weight:600; color:#334155; font-size:10.5px; white-space:nowrap;">
                                                {{ $picItem }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-center" style="font-size:11px; white-space:nowrap; vertical-align:middle; color:#334155; font-weight:600;">{{ $plan->start_date }}</td>
                                <td class="text-center" style="font-size:11px; white-space:nowrap; vertical-align:middle; color:#334155; font-weight:600;">{{ $plan->end_date }}</td>
                                <td style="font-size:11px; color:#334155; vertical-align:middle; line-height:1.45;">{{ $plan->success_indicator }}</td>
                                <td class="text-center" style="vertical-align:middle;">
                                    @if($plan->status === 'On Progress')
                                        <span class="badge-outline-blue">On Progress</span>
                                    @elseif($plan->status === 'Planning')
                                        <span class="badge-outline-orange">Planning</span>
                                    @elseif($plan->status === 'Dibatalkan')
                                        <span class="badge-outline-red">Dibatalkan</span>
                                    @else
                                        <span class="badge-outline-green">{{ $plan->status }}</span>
                                    @endif
                                </td>
                                <td style="vertical-align:middle;">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <div class="progress-bar-wrap" style="flex:1;">
                                            <div class="progress-bar-fill {{ $plan->progress_percent >= 100 ? 'fill-green' : ($plan->progress_percent >= 60 ? 'fill-blue' : 'fill-orange') }}" style="width: {{ $plan->progress_percent }}%;"></div>
                                        </div>
                                        <span style="font-size:11px; font-weight:700; width:28px;">{{ $plan->progress_percent }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @php $techIndex++; @endphp
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted" style="padding: 24px; color:#64748b; font-size:12px;">
                                Belum ada rencana pengembangan technical yang ditambahkan. Klik tombol <strong>Tambah Rencana</strong> di atas untuk menambahkan rencana baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- D. MONITORING & REVIEW --}}
    <div class="dashboard-card" style="margin-top: 20px;">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">D. Monitoring & Review</span>
            </div>
        </div>
        <div class="idp-monitoring-grid">
            {{-- Rencana Review --}}
            <div style="display:flex; align-items:flex-start; gap:12px;">
                <div style="width:36px; height:36px; border-radius:6px; background:#eff6ff; color:#1d4ed8; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <div>
                    <div style="font-weight:700; font-size:12px; color:#0f172a; margin-bottom:4px;">Rencana Review</div>
                    <div style="font-size:11.5px; color:#475569; line-height:1.5;">Review dilakukan setiap 6 bulan bersama Atasan Langsung dan HR untuk memastikan kemajuan dan penyesuaian rencana.</div>
                </div>
            </div>

            {{-- Jadwal Review --}}
            <div style="border-left:1px solid #e2e8f0; padding-left:20px;">
                <div style="font-weight:700; font-size:12px; color:#0f172a; margin-bottom:6px;">Jadwal Review</div>
                <div style="font-size:11.5px; color:#334155; line-height:1.8;">
                    <div>• <strong>Review 1 :</strong> Desember 2026</div>
                    <div>• <strong>Review 2 :</strong> Mei 2027</div>
                </div>
            </div>

            {{-- Dokumentasi & Catatan --}}
            <div style="border-left:1px solid #e2e8f0; padding-left:20px; display:flex; align-items:flex-start; gap:12px;">
                <div style="width:36px; height:36px; border-radius:6px; background:#f0fdf4; color:#16a34a; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="9"></line><line x1="9" y1="13" x2="15" y2="13"></line><line x1="9" y1="17" x2="11" y2="17"></line></svg>
                </div>
                <div>
                    <div style="font-weight:700; font-size:12px; color:#0f172a; margin-bottom:4px;">Dokumentasi & Catatan</div>
                    <div style="font-size:11.5px; color:#475569; line-height:1.5;">Semua bukti pengembangan (sertifikat, laporan proyek, feedback, dll) didokumentasikan pada sistem.</div>
                </div>
            </div>
        </div>
    </div>
</div>
