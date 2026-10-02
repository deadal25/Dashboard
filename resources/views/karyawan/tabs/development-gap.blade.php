{{-- Tab 6: Development Gap --}}
<div class="tab-pane-content">
    {{-- Header Meta Bar & Actions --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:16px;">
        <div>
            <h2 style="font-size:15px; font-weight:800; color:#0b2545; text-transform:uppercase; margin-bottom:8px;">Development Gap</h2>
            <div style="display:flex; align-items:center; gap:20px; font-size:11.5px; color:#475569;">
                <div><strong>POSISI TARGET:</strong> <span style="color:#0f172a; font-weight:700;">{{ $employee->target_position_gap ?: 'Engineering Manager (JC5 / G5-1)' }}</span></div>
                <div style="border-left:1px solid #cbd5e1; padding-left:16px;"><strong>DEPARTEMEN / FUNGSI TARGET:</strong> <span style="color:#0f172a; font-weight:700;">{{ $employee->target_department_gap ?: 'Manufacturing Engineering' }}</span></div>
                <div style="border-left:1px solid #cbd5e1; padding-left:16px;"><strong>METODE ASESMEN GAP:</strong> {{ $employee->gap_method ?: 'Perbandingan Kompetensi: Posisi Saat Ini vs Posisi Target (Engineering Manager)' }}</div>
            </div>
        </div>
        <div class="card-actions">
            <button type="button" class="btn btn-primary" data-modal-target="modal-tambah-gap">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Data
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-edit-gap">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Data
            </button>
            <button type="button" class="btn btn-outline text-danger" data-modal-target="modal-hapus-gap">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Data
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-riwayat-assessment">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                Riwayat Asesmen Gap
            </button>
            <button type="button" class="btn btn-outline" onclick="window.print()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak
            </button>
            <a href="{{ route('karyawan.export', ['nik' => $employee->nik, 'type' => 'development-gap']) }}" class="btn btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Export
            </a>
        </div>
    </div>

    {{-- A. RINGKASAN GAP --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">A. Ringkasan & Posisi Target Gap</span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-edit-gap" data-preselect-section="edit-gap-target" title="Edit Posisi Target & Metode Gap">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit Target Gap
                </button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.4fr 1fr 1.4fr; gap: 24px; align-items: center;">
            {{-- Column 1: Donut Chart --}}
            <div>
                <div style="font-size:11.5px; font-weight:700; color:#1e293b; margin-bottom:12px; text-transform:uppercase;">
                    Ringkasan Tingkat Gap
                </div>
                <div style="display:flex; align-items:center; gap:20px;">
                    {{-- SVG Donut Chart --}}
                    <div style="position:relative; width:130px; height:130px;">
                        <svg viewBox="0 0 36 36" style="width:100%; height:100%; transform: rotate(-90deg);">
                            {{-- Background circle --}}
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#f1f5f9" stroke-width="5" />
                            {{-- Segment 1: Rendah (12.5% = 12.5 dash) -> Green #27ae60 --}}
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#27ae60" stroke-width="5" stroke-dasharray="12.5, 87.5" stroke-dashoffset="0" />
                            {{-- Segment 2: Sedang (50% = 50 dash) -> Yellow #f59e0b --}}
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#f59e0b" stroke-width="5" stroke-dasharray="50, 50" stroke-dashoffset="-12.5" />
                            {{-- Segment 3: Tinggi (37.5% = 37.5 dash) -> Red #e11d48 --}}
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e11d48" stroke-width="5" stroke-dasharray="37.5, 62.5" stroke-dashoffset="-62.5" />
                        </svg>
                        <div style="position:absolute; top:0; left:0; right:0; bottom:0; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center;">
                            <span style="font-size:10px; color:#64748b; font-weight:600;">Total</span>
                            <span style="font-size:18px; font-weight:800; color:#0f172a; line-height:1;">8</span>
                            <span style="font-size:9.5px; color:#64748b;">Kompetensi</span>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div style="display:flex; flex-direction:column; gap:8px; font-size:11.5px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="width:10px; height:10px; border-radius:50%; background:#27ae60; display:inline-block;"></span>
                            <span><strong>1</strong> (12.5%) Gap Rendah (0.00 – 0.99)</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="width:10px; height:10px; border-radius:50%; background:#f59e0b; display:inline-block;"></span>
                            <span><strong>4</strong> (50%) Gap Sedang (1.00 – 1.99)</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="width:10px; height:10px; border-radius:50%; background:#e11d48; display:inline-block;"></span>
                            <span><strong>3</strong> (37.5%) Gap Tinggi (≥ 2.00)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 2: Interpretasi --}}
            <div style="border-left: 1px solid #e2e8f0; padding-left: 20px;">
                <div style="font-size:11.5px; font-weight:700; color:#1e293b; margin-bottom:8px; text-transform:uppercase;">
                    Interpretasi
                </div>
                <div style="font-size:12px; color:#475569; line-height:1.6;">
                    <strong>{{ $employee->name }}</strong> memiliki <strong>3 kompetensi dengan gap tinggi</strong> yang perlu menjadi fokus prioritas pengembangan untuk dipersiapkan menuju posisi <strong>Engineering Manager</strong>.
                </div>
            </div>

            {{-- Column 3: Rekomendasi Umum --}}
            <div style="border-left: 1px solid #e2e8f0; padding-left: 20px;">
                <div style="font-size:11.5px; font-weight:700; color:#1e293b; margin-bottom:8px; text-transform:uppercase;">
                    Rekomendasi Umum
                </div>
                <div style="display:flex; flex-direction:column; gap:10px; font-size:11.5px; color:#475569;">
                    <div style="display:flex; align-items:flex-start; gap:8px;">
                        <span style="color:#0056b3; font-size:14px;">🎯</span>
                        <span>Fokus pada kompetensi dengan gap tinggi terlebih dahulu. Susun rencana pengembangan melalui IDP.</span>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:8px;">
                        <span style="color:#0056b3; font-size:14px;">👤</span>
                        <span>Gunakan kombinasi metode: training, on the job assignment, coaching/mentoring.</span>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:8px;">
                        <span style="color:#0056b3; font-size:14px;">📋</span>
                        <span>Lakukan monitoring dan evaluasi progress secara berkala (minimal setiap 6 bulan).</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- B. DETAIL GAP KOMPETENSI (POSISI SAAT INI VS POSISI TARGET) --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">B. Detail Gap Kompetensi <span style="font-weight:500; text-transform:none; color:#64748b;">(Posisi Saat Ini vs Posisi Target)</span></span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-gap" data-preselect-section="gap-detail" title="Tambah Baris Gap Kompetensi">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Gap
                </button>
                <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-gap" data-preselect-section="edit-gap-detail" title="Edit Gap Kompetensi">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </button>
                <button type="button" class="btn-mini btn-mini-danger" data-modal-target="modal-hapus-gap" data-preselect-section="del-gap-detail" title="Hapus Gap Kompetensi">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Hapus
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center" style="width:4%; vertical-align:middle;">No.</th>
                        <th rowspan="2" style="width:16%; vertical-align:middle;">Kompetensi</th>
                        <th colspan="2" class="text-center" style="width:26%; background:#f1f5f9;">Level Saat Ini<br><small style="font-weight:400; color:#475569;">(Posisi Saat Ini: Section Head)</small></th>
                        <th colspan="2" class="text-center" style="width:26%; background:#f1f5f9;">Level Standar<br><small style="font-weight:400; color:#475569;">(Posisi Target: Engineering Manager)</small></th>
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
                    @foreach($employee->competencyGaps as $gap)
                        <tr>
                            <td class="text-center">{{ $gap->order_no }}</td>
                            <td style="font-weight:700;">{{ $gap->competency }}</td>
                            <td class="text-center font-bold">{{ $gap->current_level }}</td>
                            <td style="font-size:11px; color:#475569;">{{ $gap->current_desc }}</td>
                            <td class="text-center font-bold">{{ $gap->standard_level }}</td>
                            <td style="font-size:11px; color:#475569;">{{ $gap->standard_desc }}</td>
                            <td class="text-center font-bold" style="font-size:13px;">{{ $gap->gap }}</td>
                            <td class="text-center">
                                @if($gap->gap_severity === 'Tinggi')
                                    <span class="badge-red" style="font-size:10.5px; padding:2px 8px;">Tinggi</span>
                                @elseif($gap->gap_severity === 'Sedang')
                                    <span class="badge-orange" style="font-size:10.5px; padding:2px 8px;">Sedang</span>
                                @else
                                    <span class="badge-green" style="font-size:10.5px; padding:2px 8px;">Rendah</span>
                                @endif
                            </td>
                            <td style="font-size:11.5px;">{{ $gap->expected_improvement }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
