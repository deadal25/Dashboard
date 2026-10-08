{{-- Tab 3: Talent Snapshot --}}
<div class="tab-pane-content">
    <style>
        .talent-snapshot-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            align-items: stretch;
        }
        @media (max-width: 992px) {
            .talent-snapshot-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }
        }
        .talent-snapshot-card {
            margin-bottom: 0 !important;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            height: 100%;
            padding: 14px 16px !important;
            min-width: 0;
            overflow: hidden;
        }
        @media (max-width: 640px) {
            .talent-snapshot-card {
                padding: 12px 14px !important;
            }
        }
        .talent-header-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        @media (max-width: 640px) {
            .talent-header-actions {
                width: 100%;
                justify-content: stretch;
            }
            .talent-header-actions .btn {
                flex: 1 1 auto;
                justify-content: center;
                text-align: center;
            }
        }
        .talent-table-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            max-width: 100%;
            margin-bottom: 4px;
        }
        .talent-table-scroll::-webkit-scrollbar {
            height: 5px;
            width: 5px;
        }
        .talent-table-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .talent-table-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .talent-table-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .table-compact {
            font-size: 14px !important;
            width: 100%;
            border-collapse: collapse;
        }
        .table-compact th {
            padding: 5px 7px !important;
            font-size: 13.5px !important;
            line-height: 1.35 !important;
            background-color: #f8fafc;
            color: #334155;
            font-weight: 700;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .table-compact td {
            padding: 5px 7px !important;
            font-size: 13.5px !important;
            line-height: 1.4 !important;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            vertical-align: middle;
        }
        @media (max-width: 640px) {
            .table-compact th,
            .table-compact td {
                padding: 4px 6px !important;
                font-size: 12.5px !important;
            }
        }
    </style>

    {{-- Header Action Bar (Aligned to Right below Ringkasan Profil) --}}
    <div class="talent-header-actions">
        <button type="button" class="btn btn-primary" data-modal-target="modal-tambah-talent-snapshot" style="padding:6px 14px; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Data
        </button>
        <button type="button" class="btn btn-outline" data-modal-target="modal-edit-talent-snapshot" style="padding:6px 14px; font-size:13px; font-weight:600; background:#fff; display:inline-flex; align-items:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            Edit
        </button>
        <button type="button" class="btn btn-outline" data-modal-target="modal-riwayat-assessment" style="padding:6px 14px; font-size:13px; font-weight:600; background:#fff; display:inline-flex; align-items:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
            Riwayat Assessment
        </button>
    </div>

    {{-- Grid 6 Card: 2 Kolom per Baris (C1 & C2 sebaris, C3 & C4 sebaris, C5 & C6 sebaris) --}}
    <div class="talent-snapshot-grid">

        {{-- C1. Performance --}}
        <div class="dashboard-card talent-snapshot-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:10px; padding-bottom:6px;">
                    <div class="card-title-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="#000000" class="bi bi-graph-up-arrow" viewBox="0 0 16 16" style="flex-shrink:0;">
                            <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.707l-4.146 4.147a.5.5 0 0 1-.708 0L7 6.707l-5.146 5.147a.5.5 0 0 1-.708-.708l5.5-5.5a.5.5 0 0 1 .708 0L9.5 7.793 13.293 4H10.5a.5.5 0 0 1-.5-.5"/>
                        </svg>
                        <span class="card-title" style="font-size:15px;">C1. PERFORMANCE 3 TAHUN TERAKHIR</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:5px;">
                        <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-talent-snapshot" data-preselect-section="c1-perf" title="Tambah Data Performance" style="padding:3px 9px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            <span>Tambah</span>
                        </button>
                        <button type="button" class="btn-mini" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c1-perf" title="Edit Data Performance" style="padding:3px 8px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px; background:#f1f5f9; color:#334155; border:1px solid #cbd5e1;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            <span>Edit</span>
                        </button>
                    </div>
                </div>
                @php
                    $perfCalc = $employee->getPerformanceCalculation();
                @endphp
                <div class="talent-table-scroll">
                    <table class="table-custom table-compact text-center" style="min-width: 270px;">
                        <thead>
                            <tr>
                                <th style="font-size:13.5px; text-align:left; width:44%;">Tahun</th>
                                @foreach($perfCalc['items'] as $item)
                                    <th style="font-size:13.5px; width:18%;">{{ $item['label'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight:600; text-align:left; font-size:13.5px;">Performance Appraisal</td>
                                @foreach($perfCalc['items'] as $item)
                                    <td>
                                        @if($loop->last)
                                            <span class="badge-green" style="font-size:13.5px; padding:2px 8px; font-weight:800;">{{ $item['rating'] }}</span>
                                        @else
                                            <span style="font-weight:700; font-size:13.5px; color:#1e293b;">{{ $item['rating'] }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="font-size:12px; color:#64748b; margin-top:10px; line-height:1.45;">
                <strong style="color:#0b2545; display:block; margin-bottom:2px; font-size:12px;">Catatan:</strong>
                {{ $employee->performance_notes ?: 'Data berasal dari Hasil Penilaian Kinerja tahunan.' }}
            </div>
        </div>

        {{-- C2. Potential Assessment - POTASS --}}
        <div class="dashboard-card talent-snapshot-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:10px; padding-bottom:6px;">
                    <div class="card-title-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="#000000" class="bi bi-bullseye" viewBox="0 0 16 16" style="flex-shrink:0;">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M8 13A5 5 0 1 1 8 3a5 5 0 0 1 0 10m0 1A6 6 0 1 0 8 2a6 6 0 0 0 0 12"/>
                            <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6m0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8"/>
                            <path d="M9.5 8a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        </svg>
                        <span class="card-title" style="font-size:15px;">C2. POTENTIAL ASSESSMENT (POTASS)</span>
                    </div>
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c2-potass" title="Edit Data POTASS" style="padding:3px 9px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <span>Edit</span>
                    </button>
                </div>
                <div class="talent-table-scroll">
                    <table class="table-custom table-compact" style="min-width: 280px;">
                        <thead>
                            <tr>
                                <th style="width:44%;">Item</th>
                                <th class="text-center" style="font-size:13.5px; width:28%;">Sebelumnya<br><small style="color:#64748b; font-weight:400; font-size:11.5px;">({{ $employee->potass_period_prev ?: 'Aug-24' }})</small></th>
                                <th class="text-center" style="font-size:13.5px; width:28%;">Terakhir<br><small style="color:#64748b; font-weight:400; font-size:11.5px;">({{ $employee->potass_period_last ?: 'Aug-26' }})</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight:600;">Score POTASS</td>
                                <td class="text-center">{{ $employee->potass_score_prev ?: '94%' }}</td>
                                <td class="text-center text-success text-bold">{{ $employee->potass_score_last ?: ($employee->potass_current ?: '106%') }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;">Standar Jabatan yang Digunakan</td>
                                <td class="text-center">{{ $employee->potass_position_prev ?: 'SECTION HEAD' }}</td>
                                <td class="text-center">{{ $employee->potass_position_last ?: 'MANAGER' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;">Kategori</td>
                                <td class="text-center">{{ $employee->potass_category_prev ?: 'Average' }}</td>
                                <td class="text-center text-success text-bold">{{ $employee->potass_category_last ?: 'High' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;">Assessor</td>
                                <td class="text-center">{{ $employee->potass_assessor_prev ?: 'HR Development' }}</td>
                                <td class="text-center">{{ $employee->potass_assessor_last ?: 'HR Development' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="font-size:12px; color:#64748b; margin-top:10px; line-height:1.45;">
                <strong style="color:#0b2545; display:block; margin-bottom:2px; font-size:12px;">Catatan:</strong>
                POTASS menggunakan standar jabatan {{ $employee->potass_position_last ?: 'MANAGER' }} pada assessment terakhir.
            </div>
        </div>

        {{-- C3. HAV 16 Box & Talent Pool --}}
        <div class="dashboard-card talent-snapshot-card" id="card-c3-hav-box" style="display: flex; flex-direction: column; height: 100%;">
            <div class="card-header-bar" style="margin-bottom:10px; padding-bottom:6px; flex-shrink: 0;">
                <div class="card-title-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="#000000" class="bi bi-grid-3x3-gap-fill" viewBox="0 0 16 16" style="flex-shrink:0;">
                        <path d="M1 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 12a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/>
                    </svg>
                    <span class="card-title" style="font-size:15px;">C3. HAV 16 BOX & TALENT POOL</span>
                </div>
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c3-hav" title="Edit Posisi HAV Box" style="padding:3px 9px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <span>Edit</span>
                </button>
            </div>
            @php
                $hav = $employee->getHavBoxDetails();
                $activeBoxNum = $hav['active_box_number'];
                $matrixMap = \App\Services\TalentCalculatorService::getHavMatrixMap();
                $rows = ['R3', 'R2', 'R1', 'R0'];
                $cols = ['C0', 'C1', 'C2', 'C3'];
            @endphp
            {{-- Matriks 4x4 HAV 16 Box Terpusat & Responsif Scroll --}}
            <div class="hav-matrix-container talent-table-scroll" style="flex: 1 1 auto; min-height: 220px; display:flex; align-items:center; justify-content:center; padding:4px 0 8px 0; width:100%;">
                <div id="hav-matrix-grid-box" class="hav-matrix-grid" style="display:grid; grid-template-columns: repeat(4, 1fr); grid-template-rows: repeat(4, 1fr); border: 1.5px solid #cbd5e1; border-radius: 6px; overflow: hidden; position:relative; background:#ffffff; box-shadow:0 1px 3px rgba(0,0,0,0.05); aspect-ratio: 1 / 1; width: 220px; height: 220px; max-width: 100%; max-height: 100%; min-width: 208px; min-height: 208px; transition: width 0.15s ease, height 0.15s ease;">
                    @foreach($rows as $rKey)
                        @foreach($cols as $cKey)
                            @php
                                $cell = $matrixMap[$rKey][$cKey];
                                $isActive = ($cell['box'] == $activeBoxNum);
                                $isRedBorderRight = ($cKey === 'C0');
                                $isRedBorderBottom = ($rKey === 'R1');
                            @endphp
                            <div class="hav-box-cell"
                                 title="Box {{ $cell['box'] }}: {{ $cell['name'] }} - Talent Pool: {{ $cell['talent_pool'] }}"
                                 style="
                                    background-color: {{ $cell['color'] }};
                                    color: {{ $cell['textColor'] }};
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: var(--hav-box-font, 18px);
                                    font-weight: 800;
                                    cursor: default;
                                    position: relative;
                                    border: 0.5px solid rgba(0,0,0,0.08);
                                    width: 100%;
                                    height: 100%;
                                    {{ $isRedBorderRight ? 'border-right: 2.5px solid #dc2626 !important;' : '' }}
                                    {{ $isRedBorderBottom ? 'border-bottom: 2.5px solid #dc2626 !important;' : '' }}
                                    {{ $isActive ? 'box-shadow: inset 0 0 0 2.5px #0f172a, 0 0 0 2px #0f172a; z-index: 5;' : '' }}
                                 ">
                                {{ $cell['box'] }}
                                @if($isActive)
                                    <div style="position:absolute; top:-2px; right:-2px; width:9px; height:9px; background:#dc2626; border:1.5px solid #ffffff; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,0.4);" title="Posisi Fokus: Box {{ $cell['box'] }}"></div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            {{-- Keterangan Posisi di Bawah Matriks --}}
            <div style="flex-shrink: 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 14px; margin-top:6px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div>
                    <div style="font-size:12.5px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:2px;">Posisi pada HAV 16 Box</div>
                    <div style="display:flex; align-items:baseline; gap:8px;">
                        <span style="font-size:20px; font-weight:800; color:#0b2545;">Box {{ $activeBoxNum }}</span>
                        <span style="font-size:15px; font-weight:700; color:#0369a1;">• {{ $hav['category'] }}</span>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                    <span style="font-size:14.5px; font-weight:800; color:#0b2545; letter-spacing:0.2px;">Talent Pool:</span>
                    @if(($hav['talent_pool'] ?? 'YA') === 'YA')
                        <span style="background:#16a34a; color:#ffffff; font-size:12.5px; font-weight:800; padding:2.5px 10px; border-radius:4px; display:inline-block; box-shadow:0 1px 2px rgba(22,163,74,0.2);">YA</span>
                    @else
                        <span style="background:#dc2626; color:#ffffff; font-size:12.5px; font-weight:800; padding:2.5px 10px; border-radius:4px; display:inline-block; box-shadow:0 1px 2px rgba(220,38,38,0.2);">TIDAK</span>
                    @endif
                </div>
            </div>
            <div style="flex-shrink: 0; font-size:12px; color:#64748b; margin-top: auto; padding-top: 10px; line-height:1.45;">
                <strong style="color:#0b2545; display:block; margin-bottom:2px; font-size:12px;">Keterangan 16 Box:</strong>
                Sumbu X = Performance, Sumbu Y = Potential
            </div>
        </div>

        {{-- C4. Kekuatan Utama --}}
        <div class="dashboard-card talent-snapshot-card" id="card-c4-strength" style="display: flex; flex-direction: column; height: 100%;">
            <div style="flex: 1 1 auto; display: flex; flex-direction: column;">
                <div class="card-header-bar" style="margin-bottom:10px; padding-bottom:6px; flex-shrink: 0;">
                    <div class="card-title-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="#000000" class="bi bi-star-fill" viewBox="0 0 16 16" style="flex-shrink:0;">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                        </svg>
                        <span class="card-title" style="font-size:15px;">C4. KEKUATAN UTAMA</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:5px;">
                        <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-talent-snapshot" data-preselect-section="c4-strength" title="Tambah Kekuatan Utama" style="padding:3px 9px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            <span>Tambah</span>
                        </button>
                        <button type="button" class="btn-mini" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c4-strength" title="Edit Data Kekuatan Utama" style="padding:3px 8px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px; background:#f1f5f9; color:#334155; border:1px solid #cbd5e1;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            <span>Edit</span>
                        </button>
                    </div>
                </div>
                <div class="talent-table-scroll" style="flex: 1 1 auto;">
                    <table class="table-custom table-compact" style="min-width: 320px;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:6%;">No.</th>
                                <th style="width:23%;">Kekuatan</th>
                                <th>Deskripsi Singkat</th>
                                <th style="width:16%;">Sumber</th>
                                <th class="text-center" style="width:16%;">Dokumentasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->keyStrengths as $st)
                                <tr>
                                    <td class="text-center">{{ $st->order_no }}</td>
                                    <td style="font-weight:700;">{{ $st->strength }}</td>
                                    <td>{{ $st->short_description }}</td>
                                    <td>{{ $st->source }}</td>
                                    <td class="text-center">
                                        @if($st->documentation)
                                            @if($st->is_image)
                                                <a href="{{ asset($st->documentation) }}" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; gap:5px; text-decoration:none; padding:2px 7px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:5px; font-size:11px; font-weight:600; color:#1d4ed8; transition:all 0.15s ease;" title="Buka Gambar">
                                                    <img src="{{ asset($st->documentation) }}" alt="Preview" style="width:18px; height:18px; object-fit:cover; border-radius:3px; border:1px solid #93c5fd; display:inline-block;">
                                                    <span>Lihat Foto</span>
                                                </a>
                                            @elseif($st->is_pdf)
                                                <a href="{{ asset($st->documentation) }}" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; gap:5px; text-decoration:none; padding:3px 7px; background:#fef2f2; border:1px solid #fecaca; border-radius:5px; font-size:11px; font-weight:600; color:#dc2626; transition:all 0.15s ease;" title="Buka Dokumen PDF">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                                        <path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.143.244-.427.721-.698 1.155-.788.82-.17 1.66-.18 2.508-.106.305-.53.642-1.127.935-1.743.238-.5.418-1.02.502-1.554.08-.508.016-.928-.276-1.147-.282-.211-.706-.15-1.002.138-.344.337-.502.83-.53 1.349-.03.54.12 1.07.382 1.55.074.137.165.267.267.388-.344.757-.745 1.488-1.189 2.19-.66-.027-1.328-.027-1.892.143z"/>
                                                    </svg>
                                                    <span>Lihat PDF</span>
                                                </a>
                                            @else
                                                <a href="{{ asset($st->documentation) }}" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; gap:5px; text-decoration:none; padding:3px 7px; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:5px; font-size:11px; font-weight:600; color:#334155; transition:all 0.15s ease;" title="Buka File Dokumentasi">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                                    <span>Lihat File</span>
                                                </a>
                                            @endif
                                        @else
                                            <span style="color:#94a3b8; font-size:12px;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center" style="color:#94a3b8; padding:12px 8px; font-size:12px;">Belum ada data kekuatan utama. Silakan tambahkan melalui tombol Tambah Data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="flex-shrink: 0; font-size:12px; color:#64748b; margin-top: auto; padding-top: 10px; line-height:1.45;">
                <strong style="color:#0b2545; display:block; margin-bottom:2px; font-size:12px;">Catatan:</strong>
                Kekuatan utama ditentukan berdasarkan hasil PA, POTASS, 360 Feedback dan rekomendasi atasan.
            </div>
        </div>

        {{-- C5. Flying Risk Assessment --}}
        <div class="dashboard-card talent-snapshot-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:10px; padding-bottom:6px;">
                    <div class="card-title-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="#000000" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16" style="flex-shrink:0;">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>
                        <span class="card-title" style="font-size:15px;">C5. FLYING RISK ASSESSMENT</span>
                    </div>
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c5-risk" title="Edit Flying Risk Assessment" style="padding:3px 9px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <span>Edit</span>
                    </button>
                </div>
                @php
                    $risk = $employee->getFlyingRiskDetails();
                    $riskLevel = $employee->flying_risk_formatted ?: ($risk['risk_level'] ?? 'Moderate Risk');
                    $badgeBg = match($riskLevel) {
                        'Low Risk' => '#16a34a',
                        'High Risk' => '#dc2626',
                        default => '#ea580c',
                    };
                @endphp
                
                {{-- Tabel 2 Baris: Tingkat Flying Risk & Alasan Utama --}}
                <div class="talent-table-scroll">
                    <div style="border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; overflow: hidden; background:#ffffff; min-width: 270px;">
                        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                            <tbody>
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="width: 44%; padding: 6px 10px; text-align: center; font-weight: 700; color: #0b2545; font-size: 14px; border-right: 1px solid #e2e8f0;">
                                        Tingkat Flying Risk
                                    </td>
                                    <td style="width: 56%; padding: 6px 10px; text-align: center;">
                                        <span style="background: {{ $badgeBg }}; color: #ffffff; font-size: 14px; font-weight: 800; padding: 3px 14px; border-radius: 4px; display: inline-block; letter-spacing: 0.3px;">
                                            {{ $riskLevel }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 10px; text-align: center; font-weight: 700; color: #0b2545; font-size: 14px; border-right: 1px solid #e2e8f0;">
                                        Alasan Utama
                                    </td>
                                    <td style="padding: 6px 10px; text-align: left; font-size: 13.5px; color: #1e293b; font-weight: 600; line-height: 1.4;">
                                        {{ $employee->flying_risk_reason ?: ($risk['interpretation'] ?? 'Employee may have some concerns but is not actively looking to leave. Engagement and career development efforts recommended.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Interpretasi Tingkat Flying Risk --}}
                <div style="margin-bottom: 8px;">
                    <div style="font-size: 13px; font-weight: 700; color: #0b2545; margin-bottom: 5px;">
                        Interpretasi Tingkat Flying Risk:
                    </div>
                    <div style="display: grid; grid-template-columns: max-content 8px 1fr; row-gap: 5px; column-gap: 6px; align-items: center; font-size: 12.5px; line-height: 1.35;">
                        <span style="background: #16a34a; color: #ffffff; font-weight: 800; font-size: 11.5px; padding: 2px 8px; border-radius: 3px; text-align: center; display: inline-block; width: 100%; box-sizing: border-box;">Low Risk</span>
                        <span style="color: #64748b; font-weight: 700; text-align: center;">:</span>
                        <span style="color: #475569;">Risiko rendah, kecil kemungkinan pindah.</span>

                        <span style="background: #ea580c; color: #ffffff; font-weight: 800; font-size: 11.5px; padding: 2px 8px; border-radius: 3px; text-align: center; display: inline-block; width: 100%; box-sizing: border-box;">Moderate Risk</span>
                        <span style="color: #64748b; font-weight: 700; text-align: center;">:</span>
                        <span style="color: #475569;">Risiko sedang, perlu perhatian & pengembangan.</span>

                        <span style="background: #dc2626; color: #ffffff; font-weight: 800; font-size: 11.5px; padding: 2px 8px; border-radius: 3px; text-align: center; display: inline-block; width: 100%; box-sizing: border-box;">High Risk</span>
                        <span style="color: #64748b; font-weight: 700; text-align: center;">:</span>
                        <span style="color: #475569;">Risiko tinggi, kemungkinan pindah cukup besar.</span>
                    </div>
                </div>
            </div>

            {{-- Catatan --}}
            <div style="font-size: 12px; color: #64748b; margin-top: 10px; line-height: 1.45;">
                <strong style="color: #0b2545; display: block; margin-bottom: 2px; font-size: 12px;">Catatan:</strong>
                Flying Risk Assessment bersifat indikatif untuk memantau potensi perpindahan talenta.
            </div>
        </div>

        {{-- C6. Riwayat POTASS Assessment --}}
        <div class="dashboard-card talent-snapshot-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:10px; padding-bottom:6px;">
                    <div class="card-title-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="#000000" class="bi bi-clock-history" viewBox="0 0 16 16" style="flex-shrink:0;">
                            <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/>
                            <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/>
                            <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/>
                        </svg>
                        <span class="card-title" style="font-size:15px;">C6. RIWAYAT POTASS ASSESSMENT</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:5px;">
                        <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-talent-snapshot" data-preselect-section="c6-potass" title="Tambah Riwayat POTASS" style="padding:3px 9px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            <span>Tambah</span>
                        </button>
                        <button type="button" class="btn-mini" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c6-history" title="Edit Riwayat POTASS Assessment" style="padding:3px 8px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px; background:#f1f5f9; color:#334155; border:1px solid #cbd5e1;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            <span>Edit</span>
                        </button>
                    </div>
                </div>
                <div class="talent-table-scroll">
                    <table class="table-custom table-compact text-center" style="min-width: 360px;">
                        <thead>
                            <tr>
                                <th style="width: 18%;">Tanggal</th>
                                <th style="width: 30%;">Standar Jabatan</th>
                                <th style="width: 18%;">Score POTASS</th>
                                <th style="width: 16%;">Kategori</th>
                                <th style="width: 18%;">Assessor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->getSortedTalentAssessments() as $ta)
                                <tr>
                                    <td style="font-weight:600;">{{ $ta->assessment_date }}</td>
                                    <td>{{ $ta->position_standard }}</td>
                                    <td>{{ $ta->potass_score }}</td>
                                    <td>
                                        @if($ta->category === 'High')
                                            <span class="text-success text-bold">High</span>
                                        @elseif($ta->category === 'Below Average')
                                            <span class="text-danger text-bold">Below Average</span>
                                        @else
                                            <span>Average</span>
                                        @endif
                                    </td>
                                    <td>{{ $ta->assessor }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center" style="color:#94a3b8; padding:14px 8px; font-size:12px;">Belum ada riwayat asesmen POTASS.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="font-size:12px; color:#64748b; margin-top: 10px; line-height:1.45;">
                <strong style="color:#0b2545; display:block; margin-bottom:2px; font-size:12px;">Catatan:</strong>
                Riwayat digunakan untuk melihat trend potensi dari waktu ke waktu.
            </div>
        </div>

    </div>
</div>
