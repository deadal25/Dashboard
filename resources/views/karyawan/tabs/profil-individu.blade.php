{{-- Tab 1: Profil Individu (Overview Summary - Terhubung dengan Talent Snapshot) --}}
<div class="tab-pane-content">
    <style>
        .profil-row-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 16px;
            align-items: stretch;
        }
        .profil-row-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 16px;
            align-items: stretch;
        }
        .profil-row-1 {
            width: 100%;
            margin-bottom: 16px;
        }
        @media (max-width: 1200px) {
            .profil-row-3 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
            }
        }
        @media (max-width: 900px) {
            .profil-row-3,
            .profil-row-2 {
                grid-template-columns: 1fr !important;
                gap: 14px;
            }
        }
        .profil-card {
            margin-bottom: 0 !important;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            min-width: 0;
            overflow: hidden;
            padding: 16px 18px !important;
        }
        @media (max-width: 640px) {
            .profil-card {
                padding: 14px 14px !important;
            }
        }
        .profil-table-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            max-width: 100%;
            margin-bottom: 4px;
        }
        .profil-table-scroll::-webkit-scrollbar {
            height: 5px;
            width: 5px;
        }
        .profil-table-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .profil-table-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .profil-table-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .profil-card-note {
            font-size: 11px;
            color: #64748b;
            margin-top: auto;
            padding-top: 8px;
            line-height: 1.4;
            border-top: 1px dashed #f1f5f9;
        }
        .profil-card-note strong {
            color: #0b2545;
        }
    </style>

    {{-- =========================================================================
         BARIS 1 (3 CARDS): C1 Performance, C2 POTASS, C3 HAV 16 Box & Talent Pool
         ========================================================================= --}}
    <div class="profil-row-3">

        {{-- 1. C1. Performance 3 Tahun Terakhir (Hasil Dinamis dari Talent Snapshot) --}}
        <div class="dashboard-card profil-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                    <div class="card-title-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" class="bi bi-graph-up-arrow" viewBox="0 0 16 16" style="flex-shrink:0;">
                            <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.707l-4.146 4.147a.5.5 0 0 1-.708 0L7 6.707l-5.146 5.147a.5.5 0 0 1-.708-.708l5.5-5.5a.5.5 0 0 1 .708 0L9.5 7.793 13.293 4H10.5a.5.5 0 0 1-.5-.5"/>
                        </svg>
                        <span class="card-title" style="font-size:14px; font-weight:800; letter-spacing:0.3px;">PERFORMANCE 3 TAHUN TERAKHIR</span>
                    </div>
                </div>

                @php
                    $perfCalc = $employee->getPerformanceCalculation();
                @endphp
                <div class="profil-table-scroll">
                    <table class="table-custom text-center" style="font-size:12px; width:100%; min-width:260px;">
                        <thead>
                            <tr>
                                <th style="font-size:11.5px; text-align:left; background:#f8fafc; width:44%;">Tahun</th>
                                @foreach($perfCalc['items'] as $item)
                                    <th style="font-size:11.5px; width:18%;">{{ $item['label'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight:600; text-align:left; font-size:11.5px;">Performance Appraisal</td>
                                @foreach($perfCalc['items'] as $item)
                                    <td>
                                        @if($loop->last)
                                            <span class="badge-green" style="font-size:11.5px; padding:2px 8px; font-weight:800;">{{ $item['rating'] }}</span>
                                        @else
                                            <span style="font-weight:700; font-size:11.5px; color:#1e293b;">{{ $item['rating'] }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="profil-card-note">
                <strong>Catatan:</strong> {{ $employee->performance_notes ?: 'Data berasal dari Hasil Penilaian Kinerja tahunan pada Talent Snapshot.' }}
            </div>
        </div>

        {{-- 2. C2. Potential Assessment (POTASS) (Hasil Dinamis dari Talent Snapshot) --}}
        <div class="dashboard-card profil-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                    <div class="card-title-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" class="bi bi-bullseye" viewBox="0 0 16 16" style="flex-shrink:0;">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M8 13A5 5 0 1 1 8 3a5 5 0 0 1 0 10m0 1A6 6 0 1 0 8 2a6 6 0 0 0 0 12"/>
                            <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6m0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8"/>
                            <path d="M9.5 8a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        </svg>
                        <span class="card-title" style="font-size:14px; font-weight:800; letter-spacing:0.3px;">POTENTIAL ASSESSMENT (POTASS)</span>
                    </div>
                </div>

                <div class="profil-table-scroll">
                    <table class="table-custom" style="font-size:12px; width:100%; min-width:270px;">
                        <thead>
                            <tr>
                                <th style="font-size:11.5px; width:44%;">Item</th>
                                <th class="text-center" style="font-size:11.5px; width:28%;">Sebelumnya<br><small style="color:#64748b; font-weight:400; font-size:10px;">({{ $employee->potass_period_prev ?: 'Aug-24' }})</small></th>
                                <th class="text-center" style="font-size:11.5px; width:28%;">Terakhir<br><small style="color:#64748b; font-weight:400; font-size:10px;">({{ $employee->potass_period_last ?: 'Aug-26' }})</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight:600;">Score POTASS</td>
                                <td class="text-center">{{ $employee->potass_score_prev ?: '94%' }}</td>
                                <td class="text-center text-success text-bold">{{ $employee->potass_score_last ?: ($employee->potass_current ?: '106%') }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;">Standar Jabatan</td>
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

            <div class="profil-card-note">
                <strong>Catatan:</strong> POTASS menggunakan standar jabatan {{ $employee->potass_position_last ?: 'MANAGER' }} pada assessment terakhir.
            </div>
        </div>

        {{-- 3. C3. HAV 16 Box & Talent Pool (Hasil Dinamis dari Talent Snapshot) --}}
        <div class="dashboard-card profil-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                    <div class="card-title-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" class="bi bi-grid-3x3-gap-fill" viewBox="0 0 16 16" style="flex-shrink:0;">
                            <path d="M1 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 12a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/>
                        </svg>
                        <span class="card-title" style="font-size:14px; font-weight:800; letter-spacing:0.3px;">HAV 16 BOX & TALENT POOL</span>
                    </div>
                </div>

                @php
                    $hav = $employee->getHavBoxDetails();
                    $activeBoxNum = $hav['active_box_number'];
                    $matrixMap = \App\Services\TalentCalculatorService::getHavMatrixMap();
                    $rows = ['R3', 'R2', 'R1', 'R0'];
                    $cols = ['C0', 'C1', 'C2', 'C3'];
                @endphp
                {{-- Matriks 4x4 HAV 16 Box Terpusat --}}
                <div style="display:flex; align-items:center; justify-content:center; padding:2px 0 6px 0; width:100%;">
                    <div style="display:grid; grid-template-columns: repeat(4, 1fr); grid-template-rows: repeat(4, 1fr); border: 1.5px solid #cbd5e1; border-radius: 6px; overflow: hidden; position:relative; background:#ffffff; box-shadow:0 1px 3px rgba(0,0,0,0.05); aspect-ratio: 1 / 1; width: 175px; height: 175px; min-width: 165px; min-height: 165px;">
                        @foreach($rows as $rKey)
                            @foreach($cols as $cKey)
                                @php
                                    $cell = $matrixMap[$rKey][$cKey];
                                    $isActive = ($cell['box'] == $activeBoxNum);
                                    $isRedBorderRight = ($cKey === 'C0');
                                    $isRedBorderBottom = ($rKey === 'R1');
                                @endphp
                                <div title="Box {{ $cell['box'] }}: {{ $cell['name'] }} - Talent Pool: {{ $cell['talent_pool'] }}"
                                     style="
                                        background-color: {{ $cell['color'] }};
                                        color: {{ $cell['textColor'] }};
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        font-size: 15px;
                                        font-weight: 800;
                                        cursor: default;
                                        position: relative;
                                        border: 0.5px solid rgba(0,0,0,0.08);
                                        width: 100%;
                                        height: 100%;
                                        {{ $isRedBorderRight ? 'border-right: 2px solid #dc2626 !important;' : '' }}
                                        {{ $isRedBorderBottom ? 'border-bottom: 2px solid #dc2626 !important;' : '' }}
                                        {{ $isActive ? 'box-shadow: inset 0 0 0 2.5px #0f172a, 0 0 0 1.5px #0f172a; z-index: 5;' : '' }}
                                     ">
                                    {{ $cell['box'] }}
                                    @if($isActive)
                                        <div style="position:absolute; top:-2px; right:-2px; width:8px; height:8px; background:#dc2626; border:1.5px solid #ffffff; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,0.4);" title="Posisi Fokus: Box {{ $cell['box'] }}"></div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                {{-- Keterangan Posisi di Bawah Matriks --}}
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:7px 10px; margin-top:6px; display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap;">
                    <div>
                        <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.3px;">Posisi HAV 16 Box</div>
                        <div style="display:flex; align-items:baseline; gap:6px;">
                            <span style="font-size:16px; font-weight:800; color:#0b2545;">Box {{ $activeBoxNum }}</span>
                            <span style="font-size:12px; font-weight:700; color:#0369a1;">• {{ $hav['category'] }}</span>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                        <span style="font-size:12px; font-weight:800; color:#0b2545;">Talent Pool:</span>
                        @if(($hav['talent_pool'] ?? 'YA') === 'YA')
                            <span style="background:#16a34a; color:#ffffff; font-size:11px; font-weight:800; padding:2px 8px; border-radius:4px; display:inline-block;">YA</span>
                        @else
                            <span style="background:#dc2626; color:#ffffff; font-size:11px; font-weight:800; padding:2px 8px; border-radius:4px; display:inline-block;">TIDAK</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="profil-card-note">
                <strong>Keterangan:</strong> Sumbu X = Performance, Sumbu Y = Potential
            </div>
        </div>

    </div>

    {{-- =========================================================================
         BARIS 2 (3 CARDS): C5 Flying Risk, Arah Karir, Rencana Job Class & Grade
         ========================================================================= --}}
    <div class="profil-row-3">

        {{-- 4. C5. Flying Risk Assessment (Hasil Dinamis dari Talent Snapshot) --}}
        <div class="dashboard-card profil-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                    <div class="card-title-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16" style="flex-shrink:0;">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>
                        <span class="card-title" style="font-size:14px; font-weight:800; letter-spacing:0.3px;">FLYING RISK ASSESSMENT</span>
                    </div>
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
                <div class="profil-table-scroll">
                    <div style="border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 8px; overflow: hidden; background:#ffffff; min-width:260px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tbody>
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="width: 44%; padding: 6px 9px; text-align: left; font-weight: 700; color: #0b2545; font-size: 11.5px; border-right: 1px solid #e2e8f0; background:#f8fafc;">
                                        Tingkat Flying Risk
                                    </td>
                                    <td style="width: 56%; padding: 6px 9px; text-align: center;">
                                        <span style="background: {{ $badgeBg }}; color: #ffffff; font-size: 11.5px; font-weight: 800; padding: 2.5px 10px; border-radius: 4px; display: inline-block;">
                                            {{ $riskLevel }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 9px; text-align: left; font-weight: 700; color: #0b2545; font-size: 11.5px; border-right: 1px solid #e2e8f0; background:#f8fafc;">
                                        Alasan Utama
                                    </td>
                                    <td style="padding: 6px 9px; text-align: left; font-size: 11px; color: #1e293b; font-weight: 600; line-height: 1.35;">
                                        {{ $employee->flying_risk_reason ?: ($risk['interpretation'] ?? 'Employee may have some concerns but is not actively looking to leave.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Interpretasi Tingkat Flying Risk --}}
                <div style="margin-top:4px;">
                    <div style="font-size: 11px; font-weight: 700; color: #0b2545; margin-bottom: 4px;">
                        Interpretasi Tingkat:
                    </div>
                    <div style="display: grid; grid-template-columns: max-content 6px 1fr; row-gap: 3px; column-gap: 4px; align-items: center; font-size: 10.5px; line-height: 1.3;">
                        <span style="background: #16a34a; color: #ffffff; font-weight: 800; font-size: 9.5px; padding: 1px 6px; border-radius: 3px; text-align: center;">Low</span>
                        <span style="color: #64748b; font-weight: 700;">:</span>
                        <span style="color: #475569;">Risiko rendah, kecil kemungkinan pindah.</span>

                        <span style="background: #ea580c; color: #ffffff; font-weight: 800; font-size: 9.5px; padding: 1px 6px; border-radius: 3px; text-align: center;">Moderate</span>
                        <span style="color: #64748b; font-weight: 700;">:</span>
                        <span style="color: #475569;">Risiko sedang, perlu perhatian & pengembangan.</span>

                        <span style="background: #dc2626; color: #ffffff; font-weight: 800; font-size: 9.5px; padding: 1px 6px; border-radius: 3px; text-align: center;">High</span>
                        <span style="color: #64748b; font-weight: 700;">:</span>
                        <span style="color: #475569;">Risiko tinggi, kemungkinan pindah cukup besar.</span>
                    </div>
                </div>
            </div>

            <div class="profil-card-note">
                <strong>Catatan:</strong> Flying Risk Assessment bersifat indikatif untuk pemantauan talenta.
            </div>
        </div>

        {{-- 5. Arah Karir --}}
        <div class="dashboard-card profil-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                    <div class="card-title-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                        <span class="card-title" style="font-size:14px; font-weight:800; letter-spacing:0.3px;">ARAH KARIR</span>
                    </div>
                </div>

                <div class="profil-table-scroll">
                    <table class="table-custom" style="font-size:11.5px; width:100%; min-width:270px;">
                        <tbody>
                            <tr>
                                <td style="font-weight:600; width:45%;">Area yang Diminati</td>
                                <td>{{ $employee->careerPlan->interested_area ?? 'Engineering / Manufacturing' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;">Jalur Direkomendasikan</td>
                                <td>{{ $employee->careerPlan->recommended_career_path ?? 'Managerial' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;">Kemungkinan Jabatan Berikutnya</td>
                                <td style="color:#0056b3; font-weight:700;">{{ $employee->careerPlan->next_possible_position ?? 'Engineering Manager' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;">Kemungkinan Dept / Fungsi</td>
                                <td>{{ $employee->careerPlan->next_possible_department ?? 'Manufacturing Engineering' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;">Proyeksi Puncak Karir</td>
                                <td style="color:#0056b3; font-weight:700;">{{ $employee->careerPlan->career_projection ?? 'Engineering Division Head' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="profil-card-note">
                <strong>Catatan:</strong> Proyeksi karir dapat ditinjau kembali pada sesi career discussion tahunan.
            </div>
        </div>

        {{-- 6. Rencana Job Class & Grade --}}
        <div class="dashboard-card profil-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                    <div class="card-title-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.5"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <span class="card-title" style="font-size:14px; font-weight:800; letter-spacing:0.3px;">RENCANA JOB CLASS & GRADE</span>
                    </div>
                </div>

                <div class="profil-table-scroll">
                    <table class="table-custom text-center" style="font-size:11.5px; width:100%; min-width:270px;">
                        <thead>
                            <tr>
                                <th style="font-size:11px;">Tahun</th>
                                <th style="font-size:11px;">Usia</th>
                                <th style="font-size:11px;">Job Class</th>
                                <th style="font-size:11px;">Grade</th>
                                <th style="font-size:11px;">Perubahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->jobClassPlans as $plan)
                                <tr>
                                    <td style="font-weight:700;">{{ $plan->plan_year }} {{ $loop->first ? '(Saat Ini)' : '' }}</td>
                                    <td>{{ $plan->projected_age }}</td>
                                    <td>{{ $plan->job_class }}</td>
                                    <td>{{ $plan->grade }}</td>
                                    <td>
                                        @if($plan->change_type === 'Saat Ini')
                                            <span class="badge-table-green">Saat Ini</span>
                                        @elseif($plan->change_type === 'Promosi')
                                            <span class="badge-table-blue">Promosi</span>
                                        @elseif($plan->change_type === 'Pensiun')
                                            <span class="badge-table-gray">Pensiun</span>
                                        @else
                                            <span style="font-size:11px; color:#2e7d32; font-weight:600;">{{ $plan->change_type ?: 'Reguler' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center" style="color:#94a3b8; padding:12px;">Belum ada rencana job class & grade.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="profil-card-note">
                <strong>Catatan:</strong> Proyeksi kenaikan pangkat mengikuti ketentuan standard operating procedure (SOP).
            </div>
        </div>

    </div>

    {{-- =========================================================================
         BARIS 3 (2 CARDS): Status Suksesi, Development Gap
         ========================================================================= --}}
    <div class="profil-row-2">

        {{-- 7. Status Suksesi --}}
        <div class="dashboard-card profil-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                    <div class="card-title-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span class="card-title" style="font-size:14px; font-weight:800; letter-spacing:0.3px;">STATUS SUKSESI</span>
                    </div>
                </div>

                <div class="profil-table-scroll">
                    <table class="table-custom text-center" style="font-size:11.5px; width:100%; min-width:320px;">
                        <thead>
                            <tr>
                                <th style="font-size:11px; text-align:left;">Kandidat Suksesor / Jabatan</th>
                                <th style="font-size:11px;">Departemen / Fungsi</th>
                                <th style="font-size:11px;">Kesiapan</th>
                                <th style="font-size:11px;">Peringkat Suksesor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($employee->successionCandidates->count() > 0)
                                @foreach($employee->successionCandidates as $cand)
                                    <tr>
                                        <td style="text-align:left; font-weight:700;">{{ $cand->candidate_name }} <span style="font-weight:400; color:#64748b; font-size:10.5px;">({{ $cand->current_position }})</span></td>
                                        <td>{{ $cand->current_department }}</td>
                                        <td>
                                            @if(str_contains($cand->readiness, '1–2') || str_contains($cand->readiness, '1-2'))
                                                <span style="color:#27ae60; font-weight:700;">{{ $cand->readiness }}</span>
                                            @elseif(str_contains($cand->readiness, '3–5') || str_contains($cand->readiness, '3-5'))
                                                <span style="color:#d35400; font-weight:700;">{{ $cand->readiness }}</span>
                                            @else
                                                <span style="color:#64748b; font-weight:700;">{{ $cand->readiness }}</span>
                                            @endif
                                        </td>
                                        <td><span class="rank-circle">{{ $cand->ranking }}</span></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td style="text-align:left; font-weight:700;">Engineering Manager</td>
                                    <td>Manufacturing Engineering</td>
                                    <td style="color:#27ae60; font-weight:700;">1–2 Tahun</td>
                                    <td><span class="rank-circle">1</span></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left; font-weight:700;">Manufacturing Manager</td>
                                    <td>Manufacturing</td>
                                    <td style="color:#d35400; font-weight:700;">3–5 Tahun</td>
                                    <td><span class="rank-circle">2</span></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="profil-card-note">
                <strong>Kesiapan:</strong> Siap Sekarang / 1–2 Tahun / 3–5 Tahun / >5 Tahun / Belum Siap<br>
                <strong>Peringkat:</strong> 1 / 2 / 3
            </div>
        </div>

        {{-- 8. Development Gap --}}
        <div class="dashboard-card profil-card">
            <div>
                <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                    <div class="card-title-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.5"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        <span class="card-title" style="font-size:14px; font-weight:800; letter-spacing:0.3px;">DEVELOPMENT GAP <span style="font-weight:500; font-size:11.5px; text-transform:none; color:#64748b;">(Acuan: {{ $employee->careerPlan->next_possible_position ?? 'Engineering Manager' }})</span></span>
                    </div>
                </div>

                <div class="profil-table-scroll">
                    <table class="table-custom" style="font-size:11.5px; width:100%; min-width:340px;">
                        <thead>
                            <tr>
                                <th style="width:24%;">Kompetensi</th>
                                <th class="text-center" style="width:12%;">Level Saat Ini</th>
                                <th class="text-center" style="width:16%;">Standar Tujuan</th>
                                <th class="text-center" style="width:10%;">Gap</th>
                                <th>Peningkatan yang Diharapkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($employee->competencyGaps->count() > 0)
                                @foreach($employee->competencyGaps->take(4) as $gap)
                                    <tr>
                                        <td style="font-weight:700;">{{ $gap->competency }}</td>
                                        <td class="text-center">{{ $gap->current_level }}</td>
                                        <td class="text-center">{{ $gap->standard_level }}</td>
                                        <td class="text-center text-danger text-bold">-{{ abs($gap->gap) }}</td>
                                        <td>{{ $gap->expected_improvement }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td style="font-weight:700;">People Development</td>
                                    <td class="text-center">2</td>
                                    <td class="text-center">4</td>
                                    <td class="text-center text-danger text-bold">-2</td>
                                    <td>Mampu melakukan coaching dan mengembangkan calon suksesor</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:700;">Strategic Thinking</td>
                                    <td class="text-center">3</td>
                                    <td class="text-center">4</td>
                                    <td class="text-center text-danger text-bold">-1</td>
                                    <td>Mampu menerjemahkan arah bisnis menjadi strategi departemen</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:700;">Cost Management</td>
                                    <td class="text-center">2</td>
                                    <td class="text-center">3</td>
                                    <td class="text-center text-danger text-bold">-1</td>
                                    <td>Mampu mengelola biaya dan budget departemen secara mandiri</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="profil-card-note">
                <strong>Standar Jabatan:</strong> Profil kompetensi target jabatan tujuan suksesi.
            </div>
        </div>

    </div>

    @php
        // IDP Action Plans (Bagian B & C)
        $allIdpPlans = $employee->idpActionPlans ?: collect();
        $profilManajerialIdp = $allIdpPlans->filter(function($p) {
            return strtolower($p->competency_type ?? 'manajerial') !== 'technical';
        });
        $profilTechnicalIdp = $allIdpPlans->filter(function($p) {
            return strtolower($p->competency_type ?? '') === 'technical';
        });

        $manajerialGrouped = $profilManajerialIdp->groupBy(function($item) {
            return trim($item->competency);
        });
        $technicalGrouped = $profilTechnicalIdp->groupBy(function($item) {
            return trim($item->competency);
        });

        $totalIdpPlans = $allIdpPlans->count();

        // Reviews (Bagian B & C)
        $profilReviews = $employee->developmentReviews ?: collect();
        $totalReviews = $profilReviews->count();
        $profilManajerialReviews = $profilReviews->filter(function($r) {
            return strtolower($r->competency_type ?? 'manajerial') !== 'technical';
        });
        $profilTechnicalReviews = $profilReviews->filter(function($r) {
            return strtolower($r->competency_type ?? '') === 'technical';
        });
    @endphp

    {{-- =========================================================================
         BARIS 4: Individual Development Plan (IDP) - Bagian B & C
         ========================================================================= --}}
    <div class="dashboard-card" style="margin-bottom:16px; padding:18px 20px;">
        <div class="card-header-bar" style="margin-bottom:16px; padding-bottom:8px; border-bottom:1.5px solid #e2e8f0;">
            <div class="card-title-wrap">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                <span class="card-title" style="font-size:14.5px; font-weight:800; letter-spacing:0.3px;">INDIVIDUAL DEVELOPMENT PLAN (IDP)</span>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <span class="badge-blue" style="font-size:11px; padding:3px 10px; font-weight:700;">{{ $totalIdpPlans }} Total Rencana Aksi</span>
                <a href="{{ route('karyawan.show', ['nik' => $employee->nik, 'tab' => 'individual-developmentplan']) }}" class="btn-mini btn-mini-primary" style="text-decoration:none; display:inline-flex; align-items:center; gap:5px; font-weight:700;">
                    Buka Tab IDP &rarr;
                </a>
            </div>
        </div>

        {{-- Bagian A. Rencana Pengembangan Manajerial --}}
        <div style="margin-bottom: 24px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; padding-bottom:6px; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-size:13px; font-weight:800; color:#0b2545;">A. Rencana Pengembangan Manajerial <span style="font-weight:500; text-transform:none; color:#64748b;">(IDP Action Plan)</span></span>
                    <span class="badge-outline-blue" style="font-size:10.5px; padding:1px 8px; font-weight:700;">{{ $manajerialGrouped->count() }} Kompetensi, {{ $profilManajerialIdp->count() }} Rencana</span>
                </div>
                <div style="font-size:11px; color:#64748b; font-weight:500;">
                    Tersinkronisasi otomatis dari halaman IDP
                </div>
            </div>

            <div class="profil-table-scroll">
                <table class="table-custom" style="font-size:11.5px; width:100%; min-width:980px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:4%;">No.</th>
                            <th style="width:14%;">Kompetensi yang Dikembangkan<br><small style="font-weight:400; color:#64748b;">({{ $manajerialGrouped->count() }} Kompetensi, {{ $profilManajerialIdp->count() }} Rencana)</small></th>
                            <th style="width:15%;">Tujuan Spesifik (Target)</th>
                            <th style="width:12%;">Metode Pengembangan</th>
                            <th style="width:16%;">Aktivitas / Program</th>
                            <th style="width:11%;">PIC / Pendukung</th>
                            <th class="text-center" style="width:6.5%;">Mulai</th>
                            <th class="text-center" style="width:6.5%;">Selesai</th>
                            <th style="width:14%;">Indikator Keberhasilan</th>
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
                                        @if(in_array($plan->status, ['Selesai', 'Completed', 'Done']) || (int)$plan->progress_percent >= 100)
                                            <span class="badge-outline-green">Selesai</span>
                                        @elseif($plan->status === 'On Progress' || $plan->status === 'Berjalan')
                                            <span class="badge-outline-blue">On Progress</span>
                                        @elseif($plan->status === 'Planning')
                                            <span class="badge-outline-orange">Planning</span>
                                        @elseif($plan->status === 'Dibatalkan')
                                            <span class="badge-outline-red">Dibatalkan</span>
                                        @else
                                            <span class="badge-outline-gray">{{ $plan->status }}</span>
                                        @endif
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <div class="progress-bar-wrap" style="flex:1;">
                                                <div class="progress-bar-fill {{ (int)$plan->progress_percent >= 100 ? 'fill-green' : ((int)$plan->progress_percent >= 60 ? 'fill-blue' : 'fill-orange') }}" style="width: {{ $plan->progress_percent }}%;"></div>
                                            </div>
                                            <span style="font-size:11px; font-weight:700; width:28px;">{{ $plan->progress_percent }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @php $compIndex++; @endphp
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted" style="padding: 20px; color:#64748b; font-size:11.5px;">
                                    Belum ada rencana pengembangan manajerial.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bagian B. Rencana Pengembangan Technical --}}
        <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; padding-bottom:6px; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-size:13px; font-weight:800; color:#0b2545;">B. Rencana Pengembangan Technical <span style="font-weight:500; text-transform:none; color:#64748b;">(IDP Action Plan)</span></span>
                    <span class="badge-outline-blue" style="font-size:10.5px; padding:1px 8px; font-weight:700;">{{ $technicalGrouped->count() }} Kompetensi, {{ $profilTechnicalIdp->count() }} Rencana</span>
                </div>
                <div style="font-size:11px; color:#64748b; font-weight:500;">
                    Tersinkronisasi otomatis dari halaman IDP
                </div>
            </div>

            <div class="profil-table-scroll">
                <table class="table-custom" style="font-size:11.5px; width:100%; min-width:980px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:4%;">No.</th>
                            <th style="width:14%;">Kompetensi yang Dikembangkan<br><small style="font-weight:400; color:#64748b;">({{ $technicalGrouped->count() }} Kompetensi, {{ $profilTechnicalIdp->count() }} Rencana)</small></th>
                            <th style="width:15%;">Tujuan Spesifik (Target)</th>
                            <th style="width:12%;">Metode Pengembangan</th>
                            <th style="width:16%;">Aktivitas / Program</th>
                            <th style="width:11%;">PIC / Pendukung</th>
                            <th class="text-center" style="width:6.5%;">Mulai</th>
                            <th class="text-center" style="width:6.5%;">Selesai</th>
                            <th style="width:14%;">Indikator Keberhasilan</th>
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
                                        @if(in_array($plan->status, ['Selesai', 'Completed', 'Done']) || (int)$plan->progress_percent >= 100)
                                            <span class="badge-outline-green">Selesai</span>
                                        @elseif($plan->status === 'On Progress' || $plan->status === 'Berjalan')
                                            <span class="badge-outline-blue">On Progress</span>
                                        @elseif($plan->status === 'Planning')
                                            <span class="badge-outline-orange">Planning</span>
                                        @elseif($plan->status === 'Dibatalkan')
                                            <span class="badge-outline-red">Dibatalkan</span>
                                        @else
                                            <span class="badge-outline-gray">{{ $plan->status }}</span>
                                        @endif
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <div class="progress-bar-wrap" style="flex:1;">
                                                <div class="progress-bar-fill {{ (int)$plan->progress_percent >= 100 ? 'fill-green' : ((int)$plan->progress_percent >= 60 ? 'fill-blue' : 'fill-orange') }}" style="width: {{ $plan->progress_percent }}%;"></div>
                                            </div>
                                            <span style="font-size:11px; font-weight:700; width:28px;">{{ $plan->progress_percent }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @php $techIndex++; @endphp
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted" style="padding: 20px; color:#64748b; font-size:11.5px;">
                                    Belum ada rencana pengembangan technical yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="font-size:10.5px; color:#64748b; margin-top:14px; padding-top:8px; border-top:1px dashed #e2e8f0;">
            <strong>Metode Pengembangan:</strong> OJT / Project Assignment / Job Rotation / Coaching / Mentoring / Training / Self Learning / Overseas Assignment
        </div>
    </div>

    {{-- =========================================================================
         BARIS 5: Review Hasil Pengembangan - Bagian A & B
         ========================================================================= --}}
    <div class="dashboard-card" style="margin-bottom:16px; padding:18px 20px;">
        <div class="card-header-bar" style="margin-bottom:16px; padding-bottom:8px; border-bottom:1.5px solid #e2e8f0;">
            <div class="card-title-wrap">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                <span class="card-title" style="font-size:14.5px; font-weight:800; letter-spacing:0.3px;">REVIEW HASIL PENGEMBANGAN</span>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <span class="badge-blue" style="font-size:11px; padding:3px 10px; font-weight:700;">{{ $totalReviews }} Total Kompetensi Direview</span>
                <a href="{{ route('karyawan.show', ['nik' => $employee->nik, 'tab' => 'review-pengembangan']) }}" class="btn-mini btn-mini-primary" style="text-decoration:none; display:inline-flex; align-items:center; gap:5px; font-weight:700;">
                    Buka Tab Review &rarr;
                </a>
            </div>
        </div>

        {{-- Bagian A. Review per Kompetensi Manajerial --}}
        <div style="margin-bottom: 24px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; padding-bottom:6px; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-size:13px; font-weight:800; color:#0b2545;">A. Review per Kompetensi Manajerial <span style="font-weight:500; text-transform:none; color:#64748b;">(Perbandingan Level)</span></span>
                    <span class="badge-outline-blue" style="font-size:10.5px; padding:1px 8px; font-weight:700;">{{ $profilManajerialReviews->count() }} Kompetensi</span>
                </div>
                <div style="font-size:11px; color:#64748b; font-weight:500;">
                    Tersinkronisasi otomatis dari halaman Review Pengembangan
                </div>
            </div>

            <div class="profil-table-scroll">
                <table class="table-custom" style="font-size:11.5px; width:100%; min-width:820px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:4%;">No.</th>
                            <th style="width:23%;">Kompetensi Manajerial</th>
                            <th class="text-center" style="width:11%;">Level Sebelumnya</th>
                            <th class="text-center" style="width:11%;">Level Saat Ini</th>
                            <th class="text-center" style="width:11%;">Level Target</th>
                            <th class="text-center" style="width:10%;">Peningkatan</th>
                            <th class="text-center" style="width:12%;">Status</th>
                            <th style="width:18%;">Catatan Reviewer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profilManajerialReviews as $rev)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td style="font-weight:700;">{{ $rev->competency }}</td>
                                <td class="text-center font-bold">{{ $rev->previous_level }}</td>
                                <td class="text-center font-bold">{{ $rev->current_level }}</td>
                                <td class="text-center font-bold">{{ $rev->target_level }}</td>
                                <td class="text-center font-bold" style="color: {{ $rev->growth > 0 ? '#16a34a' : ($rev->growth < 0 ? '#ea580c' : '#475569') }};">
                                    {{ $rev->growth > 0 ? '+' . $rev->growth : ($rev->growth < 0 ? $rev->growth : '0') }}
                                </td>
                                <td class="text-center">
                                    @if($rev->status === 'Meningkat')
                                        <span class="badge-table-green">Meningkat</span>
                                    @elseif($rev->status === 'Stabil')
                                        <span class="badge-table-blue">Stabil</span>
                                    @else
                                        <span class="badge-table-orange">Belum Meningkat</span>
                                    @endif
                                </td>
                                <td style="font-size:11.5px; color:#334155;">{{ $rev->reviewer_notes }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="padding: 18px; color:#64748b; font-size:11.5px;">
                                    Belum ada review kompetensi manajerial yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bagian B. Review per Kompetensi Technical --}}
        <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; padding-bottom:6px; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-size:13px; font-weight:800; color:#0b2545;">B. Review per Kompetensi Technical <span style="font-weight:500; text-transform:none; color:#64748b;">(Perbandingan Level)</span></span>
                    <span class="badge-outline-blue" style="font-size:10.5px; padding:1px 8px; font-weight:700;">{{ $profilTechnicalReviews->count() }} Kompetensi</span>
                </div>
                <div style="font-size:11px; color:#64748b; font-weight:500;">
                    Tersinkronisasi otomatis dari halaman Review Pengembangan
                </div>
            </div>

            <div class="profil-table-scroll">
                <table class="table-custom" style="font-size:11.5px; width:100%; min-width:820px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:4%;">No.</th>
                            <th style="width:23%;">Kompetensi Technical</th>
                            <th class="text-center" style="width:11%;">Level Sebelumnya</th>
                            <th class="text-center" style="width:11%;">Level Saat Ini</th>
                            <th class="text-center" style="width:11%;">Level Target</th>
                            <th class="text-center" style="width:10%;">Peningkatan</th>
                            <th class="text-center" style="width:12%;">Status</th>
                            <th style="width:18%;">Catatan Reviewer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profilTechnicalReviews as $rev)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td style="font-weight:700;">{{ $rev->competency }}</td>
                                <td class="text-center font-bold">{{ $rev->previous_level }}</td>
                                <td class="text-center font-bold">{{ $rev->current_level }}</td>
                                <td class="text-center font-bold">{{ $rev->target_level }}</td>
                                <td class="text-center font-bold" style="color: {{ $rev->growth > 0 ? '#16a34a' : ($rev->growth < 0 ? '#ea580c' : '#475569') }};">
                                    {{ $rev->growth > 0 ? '+' . $rev->growth : ($rev->growth < 0 ? $rev->growth : '0') }}
                                </td>
                                <td class="text-center">
                                    @if($rev->status === 'Meningkat')
                                        <span class="badge-table-green">Meningkat</span>
                                    @elseif($rev->status === 'Stabil')
                                        <span class="badge-table-blue">Stabil</span>
                                    @else
                                        <span class="badge-table-orange">Belum Meningkat</span>
                                    @endif
                                </td>
                                <td style="font-size:11.5px; color:#334155;">{{ $rev->reviewer_notes }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="padding: 18px; color:#64748b; font-size:11.5px;">
                                    Belum ada review kompetensi technical yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="font-size:10.5px; color:#64748b; margin-top:14px; padding-top:8px; border-top:1px dashed #e2e8f0;">
            <strong>Status Evaluasi:</strong> Meningkat (Level Saat Ini > Level Sebelumnya) / Stabil (Level Sama) / Belum Meningkat
        </div>
    </div>

    {{-- Bottom Disclaimer --}}
    <div style="font-size: 11px; color: #64748b; padding: 4px 0 10px 0;">
        Catatan: Proyeksi karir dan rencana pengembangan dapat berubah sesuai hasil talent review berikutnya.
    </div>

</div>
