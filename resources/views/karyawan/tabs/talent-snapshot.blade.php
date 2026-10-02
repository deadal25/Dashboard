{{-- Tab 3: Talent Snapshot --}}
<div class="tab-pane-content">
    {{-- Header Action Bar --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="font-size:15px; font-weight:800; color:#0b2545; text-transform:uppercase;">Talent Snapshot</h2>
        <div class="card-actions">
            <button type="button" class="btn btn-primary" data-modal-target="modal-tambah-talent-snapshot">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Data
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-edit-talent-snapshot">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Data
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-hapus-talent-snapshot" style="color:#b91c1c; border-color:#fca5a5;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Data
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-riwayat-assessment">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                Riwayat Assessment
            </button>
        </div>
    </div>

    {{-- Row 1: C1, C2, C3 --}}
    <div style="display: grid; grid-template-columns: 1fr 1.3fr 1.3fr; gap: 16px; margin-bottom: 20px;">

        {{-- C1. Performance --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                    <span class="card-title" style="font-size:12px;">C1. Performance – 3 Tahun Terakhir</span>
                </div>
                <div class="quick-action-btn-group">
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c1-perf">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit
                    </button>
                </div>
            </div>
            <table class="table-custom text-center">
                <thead>
                    <tr>
                        <th style="font-size:11px;">Tahun</th>
                        <th style="font-size:11px;">FY24</th>
                        <th style="font-size:11px;">FY25</th>
                        <th style="font-size:11px;">FY26</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight:600; text-align:left; font-size:11.5px;">Performance Appraisal</td>
                        <td style="font-weight:700;">{{ $employee->performance_fy24 ?: 'B+' }}</td>
                        <td style="font-weight:700;">{{ $employee->performance_fy25 ?: 'A' }}</td>
                        <td><span class="badge-green" style="font-size:12px; padding:3px 10px;">{{ $employee->performance_fy26 ?: ($employee->performance_current ?: 'A') }}</span></td>
                    </tr>
                </tbody>
            </table>
            <div style="font-size:10.5px; color:#64748b; margin-top:12px;">
                <strong>Catatan:</strong><br>
                • {{ $employee->performance_notes ?: 'Data berasal dari Hasil Penilaian Kinerja tahunan.' }}
            </div>
        </div>

        {{-- C2. Potential Assessment - POTASS --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span class="card-title" style="font-size:12px;">C2. Potential Assessment – POTASS</span>
                </div>
                <div class="quick-action-btn-group">
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c2-potass">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit
                    </button>
                </div>
            </div>
            <table class="table-custom" style="font-size:11.5px;">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-center" style="font-size:11px;">Sebelumnya<br><small style="color:#64748b; font-weight:400;">({{ $employee->potass_period_prev ?: 'Aug-24' }})</small></th>
                        <th class="text-center" style="font-size:11px;">Terakhir<br><small style="color:#64748b; font-weight:400;">({{ $employee->potass_period_last ?: 'Aug-26' }})</small></th>
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
                        <td class="text-center">{{ $employee->potass_position_prev ?: 'Section Head' }}</td>
                        <td class="text-center">{{ $employee->potass_position_last ?: 'Manager' }}</td>
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
            <div style="font-size:10.5px; color:#64748b; margin-top:10px;">
                <strong>Catatan:</strong><br>
                • POTASS menggunakan standar jabatan {{ $employee->potass_position_last ?: 'Manager' }} pada assessment terakhir.
            </div>
        </div>

        {{-- C3. HAV 16 Box & Talent Pool --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                    <span class="card-title" style="font-size:12px;">C3. HAV 16 Box & Talent Pool</span>
                </div>
                <div class="quick-action-btn-group">
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c3-hav">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit
                    </button>
                </div>
            </div>
            @php
                $activeBoxNum = (int) str_replace('Box ', '', $employee->hav_box_current ?: '15');
            @endphp
            <div style="display:flex; align-items:center; gap:20px;">
                <div class="matrix-16-grid">
                    @for($i = 1; $i <= 16; $i++)
                        <div class="matrix-cell {{ $i == $activeBoxNum ? 'active-highlight' : '' }}">
                            {{ $i }}
                        </div>
                    @endfor
                </div>
                <div style="font-size:11.5px; line-height:1.5;">
                    <div style="font-size:11px; color:#64748b; font-weight:600;">Posisi pada HAV 16 Box</div>
                    <div style="font-size:15px; font-weight:800; color:#0f172a;">{{ $employee->hav_box_current ?: 'Box 15' }}</div>
                    <div style="color:#0056b3; font-weight:600; margin-bottom:8px;">{{ $employee->hav_box_category ?: 'High Performance / High Potential' }}</div>
                    <div>Talent Pool</div>
                    <div>
                        @if(($employee->talent_pool_status ?: 'YA') === 'YA')
                            <span class="badge-green" style="font-size:11px; padding:2px 8px;">YA</span>
                        @else
                            <span class="badge-red" style="font-size:11px; padding:2px 8px;">TIDAK</span>
                        @endif
                    </div>
                </div>
            </div>
            <div style="font-size:10.5px; color:#64748b; margin-top:12px;">
                <strong>Keterangan 16 Box:</strong><br>
                Sumbu X = Performance, Sumbu Y = Potential
            </div>
        </div>

    </div>

    {{-- Row 2: C4, C5, C6 --}}
    <div style="display: grid; grid-template-columns: 1.2fr 1fr 1.3fr; gap: 16px;">

        {{-- C4. Kekuatan Utama --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    <span class="card-title" style="font-size:12px;">C4. Kekuatan Utama</span>
                </div>
                <div class="quick-action-btn-group">
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-talent-snapshot" data-preselect-section="c4-strength">
                        + Tambah
                    </button>
                    <button type="button" class="btn-mini" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c4-strength">
                        Edit
                    </button>
                    <button type="button" class="btn-mini btn-mini-danger" data-modal-target="modal-hapus-talent-snapshot" data-preselect-section="del-c4-strength">
                        Hapus
                    </button>
                </div>
            </div>
            <table class="table-custom" style="font-size:11px;">
                <thead>
                    <tr>
                        <th class="text-center" style="width:10%;">No.</th>
                        <th style="width:28%;">Kekuatan</th>
                        <th>Deskripsi Singkat</th>
                        <th style="width:25%;">Sumber</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employee->keyStrengths as $st)
                        <tr>
                            <td class="text-center">{{ $st->order_no }}</td>
                            <td style="font-weight:700;">{{ $st->strength }}</td>
                            <td>{{ $st->short_description }}</td>
                            <td>{{ $st->source }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center" style="color:#94a3b8; padding:16px;">Belum ada data kekuatan utama. Silakan klik + Tambah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="font-size:10.5px; color:#64748b; margin-top:10px;">
                <strong>Catatan:</strong><br>
                • Kekuatan utama ditentukan berdasarkan hasil PA, POTASS, 360 Feedback dan rekomendasi atasan.
            </div>
        </div>

        {{-- C5. Flying Risk Assessment --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                    <span class="card-title" style="font-size:12px;">C5. Flying Risk Assessment</span>
                </div>
                <div class="quick-action-btn-group">
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c5-risk">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit
                    </button>
                </div>
            </div>
            <div style="border: 1px solid #e2e8f0; border-radius: var(--radius-sm); margin-bottom: 12px; overflow: hidden;">
                <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; border-bottom:1px solid #e2e8f0; background:#fafafa;">
                    <span style="font-size:11.5px; font-weight:700; color:#334155;">Tingkat Flying Risk</span>
                    @if($employee->flying_risk === 'HIGH')
                        <span class="badge-red" style="font-size:12px; padding:3px 12px;">HIGH</span>
                    @elseif($employee->flying_risk === 'LOW')
                        <span class="badge-green" style="font-size:12px; padding:3px 12px;">LOW</span>
                    @else
                        <span class="badge-orange" style="font-size:12px; padding:3px 12px;">{{ $employee->flying_risk ?: 'MEDIUM' }}</span>
                    @endif
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px;">
                    <span style="font-size:11.5px; font-weight:700; color:#334155;">Alasan Utama</span>
                    <span style="font-size:12px; font-weight:700; color:#0f172a;">{{ $employee->flying_risk_reason ?: 'Career Progression' }}</span>
                </div>
            </div>
            
            <div style="font-size:10.5px; color:#475569; line-height:1.7;">
                <strong style="color:#1e293b;">Interpretasi Tingkat Flying Risk:</strong><br>
                <div style="display:flex; align-items:center; gap:6px; margin-top:2px;">
                    <span class="badge-green" style="font-size:9.5px; padding:1px 5px;">LOW</span>
                    <span>: Risiko rendah, kecil kemungkinan mencari peluang di luar.</span>
                </div>
                <div style="display:flex; align-items:center; gap:6px; margin-top:2px;">
                    <span class="badge-orange" style="font-size:9.5px; padding:1px 5px;">MEDIUM</span>
                    <span>: Risiko sedang, perlu perhatian dan pengembangan lebih lanjut.</span>
                </div>
                <div style="display:flex; align-items:center; gap:6px; margin-top:2px;">
                    <span class="badge-red" style="font-size:9.5px; padding:1px 5px;">HIGH</span>
                    <span>: Risiko tinggi, kemungkinan mencari peluang di luar cukup besar.</span>
                </div>
            </div>

            <div style="font-size:10.5px; color:#64748b; margin-top:10px;">
                <strong>Catatan:</strong><br>
                • Flying Risk Assessment bersifat indikatif untuk memantau potensi perpindahan talenta.
            </div>
        </div>

        {{-- C6. Riwayat POTASS Assessment --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                    <span class="card-title" style="font-size:12px;">C6. Riwayat POTASS Assessment</span>
                </div>
                <div class="quick-action-btn-group">
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-talent-snapshot" data-preselect-section="c6-potass">
                        + Tambah
                    </button>
                    <button type="button" class="btn-mini" data-modal-target="modal-edit-talent-snapshot" data-preselect-section="edit-c6-history">
                        Edit
                    </button>
                    <button type="button" class="btn-mini btn-mini-danger" data-modal-target="modal-hapus-talent-snapshot" data-preselect-section="del-c6-history">
                        Hapus
                    </button>
                </div>
            </div>
            <table class="table-custom text-center" style="font-size:11px;">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Standar Jabatan</th>
                        <th>Score POTASS</th>
                        <th>Kategori</th>
                        <th>Assessor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employee->talentAssessments as $ta)
                        <tr>
                            <td style="font-weight:600;">{{ $ta->assessment_date }}</td>
                            <td>{{ $ta->position_standard }}</td>
                            <td class="{{ (int)$ta->potass_score >= 100 ? 'text-success text-bold' : '' }}">{{ $ta->potass_score }}</td>
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
                            <td colspan="5" class="text-center" style="color:#94a3b8; padding:16px;">Belum ada riwayat asesmen POTASS. Silakan klik + Tambah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="font-size:10.5px; color:#64748b; margin-top:10px;">
                <strong>Catatan:</strong><br>
                • Riwayat digunakan untuk melihat trend potensi dari waktu ke waktu.
            </div>
        </div>

    </div>
</div>
