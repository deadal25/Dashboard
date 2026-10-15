{{-- Tab 6: Development Gap --}}
@php
    $manajerialGaps = $employee->competencyGaps->filter(function($g) {
        return empty($g->competency_type) || strtolower($g->competency_type) === 'manajerial';
    })->values();

    $technicalGaps = $employee->competencyGaps->filter(function($g) {
        return strtolower($g->competency_type) === 'technical';
    })->values();

    $totalGaps = $employee->competencyGaps->count();
    $rendahGaps = $employee->competencyGaps->filter(fn($g) => strtolower($g->gap_severity) === 'rendah');
    $sedangGaps = $employee->competencyGaps->filter(fn($g) => strtolower($g->gap_severity) === 'sedang');
    $tinggiGaps = $employee->competencyGaps->filter(fn($g) => strtolower($g->gap_severity) === 'tinggi');

    $rendahCount = $rendahGaps->count();
    $sedangCount = $sedangGaps->count();
    $tinggiCount = $tinggiGaps->count();

    $pctRendah = $totalGaps > 0 ? round(($rendahCount / $totalGaps) * 100, 1) : 0;
    $pctSedang = $totalGaps > 0 ? round(($sedangCount / $totalGaps) * 100, 1) : 0;
    $pctTinggi = $totalGaps > 0 ? round(($tinggiCount / $totalGaps) * 100, 1) : 0;

    // SVG Donut geometry
    $cx = 100;
    $cy = 100;
    $r = 68;
    $circ = 2 * M_PI * $r; // ~427.2566

    // Arc angles (degrees)
    $degRendah = ($totalGaps > 0) ? ($rendahCount / $totalGaps) * 360 : 0;
    $degSedang = ($totalGaps > 0) ? ($sedangCount / $totalGaps) * 360 : 0;
    $degTinggi = ($totalGaps > 0) ? ($tinggiCount / $totalGaps) * 360 : 0;

    // Dash lengths
    $lenRendah = ($totalGaps > 0) ? ($rendahCount / $totalGaps) * $circ : 0;
    $lenSedang = ($totalGaps > 0) ? ($sedangCount / $totalGaps) * $circ : 0;
    $lenTinggi = ($totalGaps > 0) ? ($tinggiCount / $totalGaps) * $circ : 0;

    // Dash offsets (starting clockwise from top: 12 o'clock)
    $offsetRendah = 0;
    $offsetSedang = -$lenRendah;
    $offsetTinggi = -($lenRendah + $lenSedang);

    // Midpoint coordinates for text on each arc (unrotated)
    $midDegRendah = $degRendah / 2;
    $xRendah = round($cx + $r * sin(deg2rad($midDegRendah)), 1);
    $yRendah = round($cy - $r * cos(deg2rad($midDegRendah)), 1);

    $midDegSedang = $degRendah + ($degSedang / 2);
    $xSedang = round($cx + $r * sin(deg2rad($midDegSedang)), 1);
    $ySedang = round($cy - $r * cos(deg2rad($midDegSedang)), 1);

    $midDegTinggi = $degRendah + $degSedang + ($degTinggi / 2);
    $xTinggi = round($cx + $r * sin(deg2rad($midDegTinggi)), 1);
    $yTinggi = round($cy - $r * cos(deg2rad($midDegTinggi)), 1);

    $targetPosClean = trim(explode('(', $employee->target_position_gap ?: 'Engineering Manager')[0]);
@endphp

<div class="tab-pane-content">
    {{-- Header Meta Bar & Actions (Posisi Target, Departemen Target, Metode Asesmen Gap & Action Buttons) --}}
    <div class="devgap-meta-header-bar">
        {{-- Left: 3 meta columns --}}
        <div class="devgap-meta-items-wrap">
            {{-- Posisi Target --}}
            <div class="devgap-meta-item" style="padding-right:20px; cursor:pointer;" data-modal-target="modal-edit-gap" data-preselect-section="edit-gap-a" title="Klik untuk edit target gap">
                <div style="font-size:10.5px; font-weight:800; color:#0b2545; text-transform:uppercase; margin-bottom:4px; letter-spacing:0.4px;">
                    POSISI TARGET
                </div>
                <div style="font-size:12.5px; font-weight:700; color:#0f172a;">
                    {{ $employee->target_position_gap ?: 'Engineering Manager (JC5 / GS-1)' }}
                </div>
            </div>

            {{-- Departemen / Fungsi Target --}}
            <div class="devgap-meta-item devgap-meta-border" style="padding-left:20px; padding-right:20px; cursor:pointer;" data-modal-target="modal-edit-gap" data-preselect-section="edit-gap-a" title="Klik untuk edit target gap">
                <div style="font-size:10.5px; font-weight:800; color:#0b2545; text-transform:uppercase; margin-bottom:4px; letter-spacing:0.4px;">
                    DEPARTEMEN / FUNGSI TARGET
                </div>
                <div style="font-size:12.5px; font-weight:700; color:#0f172a;">
                    {{ $employee->target_department_gap ?: 'Manufacturing Engineering' }}
                </div>
            </div>

            {{-- Metode Asesmen Gap --}}
            <div class="devgap-meta-item devgap-meta-border" style="padding-left:20px; cursor:pointer;" data-modal-target="modal-edit-gap" data-preselect-section="edit-gap-a" title="Klik untuk edit target gap">
                <div style="font-size:10.5px; font-weight:800; color:#0b2545; text-transform:uppercase; margin-bottom:4px; letter-spacing:0.4px;">
                    METODE ASESMEN GAP
                </div>
                <div style="font-size:11.5px; font-weight:500; color:#334155;">
                    {{ $employee->gap_method ?: 'Perbandingan : Posisi Saat Ini vs Posisi Target' }}
                </div>
            </div>
        </div>

        {{-- Right: Action Buttons --}}
        <div class="card-actions" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin:0;">
            <button type="button" class="btn btn-outline" data-modal-target="modal-riwayat-assessment" style="display:inline-flex; align-items:center; gap:7px; font-size:12px; font-weight:600; padding:7px 14px; border:1px solid #cbd5e1; border-radius:6px; background:#fff; color:#0b2545; cursor:pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path><path d="M12 7v5l4 2"></path></svg>
                Riwayat Asesmen Gap
            </button>

            <button type="button" class="btn btn-outline" onclick="window.print()" style="display:inline-flex; align-items:center; gap:7px; font-size:12px; font-weight:600; padding:7px 14px; border:1px solid #cbd5e1; border-radius:6px; background:#fff; color:#0b2545; cursor:pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak
            </button>

            <a href="{{ route('karyawan.export', ['nik' => $employee->nik, 'type' => 'development-gap']) }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:7px; font-size:12px; font-weight:600; padding:7px 18px; border:1px solid #0056b3; border-radius:6px; background:#0056b3; color:#ffffff; text-decoration:none; cursor:pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Export
            </a>
        </div>
    </div>

    {{-- A. RINGKASAN GAP --}}
    <div class="dashboard-card" style="margin-bottom:20px;">
        <div class="card-header-bar" style="margin-bottom:14px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">A. Ringkasan Gap <span style="font-weight:500; text-transform:none; color:#64748b;">(Tingkat Gap, Interpretasi &amp; Rekomendasi)</span></span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-gap" data-preselect-section="tambah-gap-a" title="Atur Posisi Target &amp; Metode Gap">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Atur Target
                </button>
                <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-gap" data-preselect-section="edit-gap-a" title="Edit Posisi Target &amp; Metode Asesmen Gap">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </button>
            </div>
        </div>

        <div class="devgap-summary-grid">
            {{-- Card 1: RINGKASAN TINGKAT GAP --}}
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:18px 20px; display:flex; flex-direction:column; justify-content:space-between; margin:0;">
                <div style="font-size:11px; font-weight:800; color:#0b2545; text-transform:uppercase; margin-bottom:14px; letter-spacing:0.4px;">
                    RINGKASAN TINGKAT GAP
                </div>
                <div style="display:flex; align-items:center; gap:20px; flex:1;">
                    {{-- SVG Donut Chart with numbers and percentage labels inside slices --}}
                    <div style="position:relative; width:150px; height:150px; flex-shrink:0;">
                        <svg viewBox="0 0 200 200" style="width:100%; height:100%;">
                            {{-- Slices (rotated so 0 is at 12 o'clock) --}}
                            <g transform="rotate(-90 100 100)">
                                {{-- Base background ring --}}
                                <circle cx="100" cy="100" r="{{ $r }}" fill="none" stroke="#f1f5f9" stroke-width="34" />

                                @if($totalGaps > 0)
                                    {{-- 1. Rendah (Green) --}}
                                    @if($rendahCount > 0)
                                        <circle cx="100" cy="100" r="{{ $r }}" fill="none" stroke="#27ae60" stroke-width="34"
                                                stroke-dasharray="{{ $lenRendah }} {{ $circ - $lenRendah }}"
                                                stroke-dashoffset="{{ $offsetRendah }}" />
                                    @endif

                                    {{-- 2. Sedang (Yellow) --}}
                                    @if($sedangCount > 0)
                                        <circle cx="100" cy="100" r="{{ $r }}" fill="none" stroke="#f59e0b" stroke-width="34"
                                                stroke-dasharray="{{ $lenSedang }} {{ $circ - $lenSedang }}"
                                                stroke-dashoffset="{{ $offsetSedang }}" />
                                    @endif

                                    {{-- 3. Tinggi (Red) --}}
                                    @if($tinggiCount > 0)
                                        <circle cx="100" cy="100" r="{{ $r }}" fill="none" stroke="#e11d48" stroke-width="34"
                                                stroke-dasharray="{{ $lenTinggi }} {{ $circ - $lenTinggi }}"
                                                stroke-dashoffset="{{ $offsetTinggi }}" />
                                    @endif
                                @endif
                            </g>

                            {{-- Text labels inside donut slices --}}
                            @if($totalGaps > 0)
                                @if($rendahCount > 0)
                                    <text x="{{ $xRendah }}" y="{{ $yRendah - 3 }}" text-anchor="middle" dominant-baseline="central" fill="#ffffff" font-size="12" font-weight="800" font-family="system-ui, -apple-system, sans-serif">{{ $rendahCount }}</text>
                                    <text x="{{ $xRendah }}" y="{{ $yRendah + 9 }}" text-anchor="middle" dominant-baseline="central" fill="#ffffff" font-size="9" font-weight="600" font-family="system-ui, -apple-system, sans-serif">({{ $pctRendah }}%)</text>
                                @endif

                                @if($sedangCount > 0)
                                    <text x="{{ $xSedang }}" y="{{ $ySedang - 3 }}" text-anchor="middle" dominant-baseline="central" fill="#ffffff" font-size="12" font-weight="800" font-family="system-ui, -apple-system, sans-serif">{{ $sedangCount }}</text>
                                    <text x="{{ $xSedang }}" y="{{ $ySedang + 9 }}" text-anchor="middle" dominant-baseline="central" fill="#ffffff" font-size="9" font-weight="600" font-family="system-ui, -apple-system, sans-serif">({{ $pctSedang }}%)</text>
                                @endif

                                @if($tinggiCount > 0)
                                    <text x="{{ $xTinggi }}" y="{{ $yTinggi - 3 }}" text-anchor="middle" dominant-baseline="central" fill="#ffffff" font-size="12" font-weight="800" font-family="system-ui, -apple-system, sans-serif">{{ $tinggiCount }}</text>
                                    <text x="{{ $xTinggi }}" y="{{ $yTinggi + 9 }}" text-anchor="middle" dominant-baseline="central" fill="#ffffff" font-size="9" font-weight="600" font-family="system-ui, -apple-system, sans-serif">({{ $pctTinggi }}%)</text>
                                @endif
                            @endif

                            {{-- Center Hole Info --}}
                            <text x="100" y="86" text-anchor="middle" dominant-baseline="central" fill="#64748b" font-size="11" font-weight="600" font-family="system-ui, -apple-system, sans-serif">Total</text>
                            <text x="100" y="108" text-anchor="middle" dominant-baseline="central" fill="#0f172a" font-size="24" font-weight="800" font-family="system-ui, -apple-system, sans-serif">{{ $totalGaps }}</text>
                            <text x="100" y="126" text-anchor="middle" dominant-baseline="central" fill="#64748b" font-size="9.5" font-weight="500" font-family="system-ui, -apple-system, sans-serif">Kompetensi</text>
                        </svg>
                    </div>

                    {{-- Legend on the right --}}
                    <div style="display:flex; flex-direction:column; gap:12px; font-size:11.5px; color:#1e293b; font-weight:600; white-space:nowrap;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="width:10px; height:10px; border-radius:50%; background:#27ae60; display:inline-block; flex-shrink:0;"></span>
                            <span>Gap Rendah (0.00 &ndash; 0.99)</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="width:10px; height:10px; border-radius:50%; background:#f59e0b; display:inline-block; flex-shrink:0;"></span>
                            <span>Gap Sedang (1.00 &ndash; 1.99)</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="width:10px; height:10px; border-radius:50%; background:#e11d48; display:inline-block; flex-shrink:0;"></span>
                            <span>Gap Tinggi (&ge; 2.00)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: INTERPRETASI --}}
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:18px 20px; display:flex; flex-direction:column; margin:0;">
                <div style="font-size:11px; font-weight:800; color:#0b2545; text-transform:uppercase; margin-bottom:14px; letter-spacing:0.4px;">
                    INTERPRETASI
                </div>
                <div style="font-size:12px; color:#334155; line-height:1.75; flex:1;">
                    {{ $employee->name }} memiliki {{ $tinggiCount }} kompetensi dengan gap tinggi yang perlu menjadi fokus prioritas pengembangan untuk dipersiapkan menuju posisi {{ $targetPosClean }}.
                </div>
            </div>

            {{-- Card 3: REKOMENDASI UMUM --}}
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:18px 20px; display:flex; flex-direction:column; margin:0;">
                <div style="font-size:11px; font-weight:800; color:#0b2545; text-transform:uppercase; margin-bottom:14px; letter-spacing:0.4px;">
                    REKOMENDASI UMUM
                </div>
                <div style="display:flex; flex-direction:column; gap:14px; font-size:11.5px; color:#334155; line-height:1.55;">
                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <div style="width:34px; height:34px; border-radius:50%; background:#eff6ff; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#2563eb;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="6"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                            </svg>
                        </div>
                        <div style="padding-top:2px;">
                            Fokus pada kompetensi dengan gap tinggi terlebih dahulu. Susun rencana pengembangan melalui IDP.
                        </div>
                    </div>

                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <div style="width:34px; height:34px; border-radius:50%; background:#eff6ff; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#2563eb;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="7" r="4"></circle>
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            </svg>
                        </div>
                        <div style="padding-top:2px;">
                            Gunakan kombinasi metode: training, on the job assignment, coaching/mentoring.
                        </div>
                    </div>

                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <div style="width:34px; height:34px; border-radius:50%; background:#eff6ff; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#2563eb;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <div style="padding-top:2px;">
                            Lakukan monitoring dan evaluasi progress secara berkala (minimal setiap 6 bulan).
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- B. DETAIL GAP KOMPETENSI MANAJERIAL --}}
    <div class="dashboard-card" style="margin-bottom:20px;">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">B. Detail Gap Kompetensi Manajerial <span style="font-weight:500; text-transform:none; color:#64748b;">(Posisi Saat Ini vs Posisi Target)</span></span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-gap" data-preselect-section="tambah-gap-b" title="Tambah Gap Manajerial">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Gap
                </button>
                <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-gap" data-preselect-section="edit-gap-b" title="Edit Gap Manajerial">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center" style="width:4%; vertical-align:middle;">No.</th>
                        <th rowspan="2" style="width:16%; vertical-align:middle;">Kompetensi</th>
                        <th colspan="2" class="text-center" style="width:26%; background:#f1f5f9;">Level Saat Ini<br><small style="font-weight:400; color:#475569;">(Posisi Saat Ini: {{ $employee->position ?: 'Section Head' }})</small></th>
                        <th colspan="2" class="text-center" style="width:26%; background:#f1f5f9;">Target Level<br><small style="font-weight:400; color:#475569;">(Posisi Target: {{ $employee->target_position_gap ?: 'Engineering Manager' }})</small></th>
                        <th rowspan="2" class="text-center" style="width:8%; vertical-align:middle;">Gap<br><small style="font-weight:400; color:#64748b;">(Target - Saat Ini)</small></th>
                        <th rowspan="2" class="text-center" style="width:8%; vertical-align:middle;">Tingkat Gap</th>
                        <th rowspan="2" style="width:22%; vertical-align:middle;">Peningkatan yang Diharapkan</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="width:6%;">Level</th>
                        <th>Deskripsi Singkat</th>
                        <th class="text-center" style="width:6%;">Level</th>
                        <th>Deskripsi Singkat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($manajerialGaps as $gap)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td style="font-weight:700;">{{ $gap->competency }}</td>
                            <td class="text-center font-bold">{{ $gap->current_level }}</td>
                            <td style="font-size:11px; color:#475569;">{{ $gap->current_desc }}</td>
                            <td class="text-center font-bold">{{ $gap->standard_level }}</td>
                            <td style="font-size:11px; color:#475569;">{{ $gap->standard_desc }}</td>
                            <td class="text-center font-bold" style="font-size:13px;">{{ $gap->gap }}</td>
                            <td class="text-center">
                                @if(strtolower($gap->gap_severity) === 'tinggi')
                                    <span class="badge-red" style="font-size:10.5px; padding:2px 8px;">Tinggi</span>
                                @elseif(strtolower($gap->gap_severity) === 'sedang')
                                    <span class="badge-orange" style="font-size:10.5px; padding:2px 8px;">Sedang</span>
                                @else
                                    <span class="badge-green" style="font-size:10.5px; padding:2px 8px;">Rendah</span>
                                @endif
                            </td>
                            <td style="font-size:11.5px;">{{ $gap->expected_improvement }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding:24px; color:#94a3b8; font-style:italic;">
                                Belum ada data gap kompetensi manajerial. Silakan klik "Tambah Gap" untuk menambahkan data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- C. DETAIL GAP KOMPETENSI TECHNICAL --}}
    <div class="dashboard-card" style="margin-bottom:20px;">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">C. Detail Gap Kompetensi Technical <span style="font-weight:500; text-transform:none; color:#64748b;">(Posisi Saat Ini vs Posisi Target)</span></span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-gap" data-preselect-section="tambah-gap-c" title="Tambah Gap Technical">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Gap
                </button>
                <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-gap" data-preselect-section="edit-gap-c" title="Edit Gap Technical">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center" style="width:4%; vertical-align:middle;">No.</th>
                        <th rowspan="2" style="width:16%; vertical-align:middle;">Kompetensi</th>
                        <th colspan="2" class="text-center" style="width:26%; background:#f1f5f9;">Level Saat Ini<br><small style="font-weight:400; color:#475569;">(Posisi Saat Ini: {{ $employee->position ?: 'Section Head' }})</small></th>
                        <th colspan="2" class="text-center" style="width:26%; background:#f1f5f9;">Target Level<br><small style="font-weight:400; color:#475569;">(Posisi Target: {{ $employee->target_position_gap ?: 'Engineering Manager' }})</small></th>
                        <th rowspan="2" class="text-center" style="width:8%; vertical-align:middle;">Gap<br><small style="font-weight:400; color:#64748b;">(Target - Saat Ini)</small></th>
                        <th rowspan="2" class="text-center" style="width:8%; vertical-align:middle;">Tingkat Gap</th>
                        <th rowspan="2" style="width:22%; vertical-align:middle;">Peningkatan yang Diharapkan</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="width:6%;">Level</th>
                        <th>Deskripsi Singkat</th>
                        <th class="text-center" style="width:6%;">Level</th>
                        <th>Deskripsi Singkat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($technicalGaps as $gap)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td style="font-weight:700;">{{ $gap->competency }}</td>
                            <td class="text-center font-bold">{{ $gap->current_level }}</td>
                            <td style="font-size:11px; color:#475569;">{{ $gap->current_desc }}</td>
                            <td class="text-center font-bold">{{ $gap->standard_level }}</td>
                            <td style="font-size:11px; color:#475569;">{{ $gap->standard_desc }}</td>
                            <td class="text-center font-bold" style="font-size:13px;">{{ $gap->gap }}</td>
                            <td class="text-center">
                                @if(strtolower($gap->gap_severity) === 'tinggi')
                                    <span class="badge-red" style="font-size:10.5px; padding:2px 8px;">Tinggi</span>
                                @elseif(strtolower($gap->gap_severity) === 'sedang')
                                    <span class="badge-orange" style="font-size:10.5px; padding:2px 8px;">Sedang</span>
                                @else
                                    <span class="badge-green" style="font-size:10.5px; padding:2px 8px;">Rendah</span>
                                @endif
                            </td>
                            <td style="font-size:11.5px;">{{ $gap->expected_improvement }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding:24px; color:#94a3b8; font-style:italic;">
                                Belum ada data gap kompetensi technical. Silakan klik "Tambah Gap" untuk menambahkan data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
