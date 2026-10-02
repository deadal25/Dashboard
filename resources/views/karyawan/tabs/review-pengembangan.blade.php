{{-- Tab 8: Review Hasil Pengembangan --}}
<div class="tab-pane-content">
    {{-- Header Filters & Actions Bar --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:12px;">
        <h2 style="font-size:15px; font-weight:800; color:#0b2545; text-transform:uppercase;">Review Hasil Pengembangan</h2>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:11.5px; font-weight:600; color:#475569;">Periode Review</span>
                <select class="filter-select">
                    <option selected>Juni 2026 – Mei 2027</option>
                    <option>Juni 2025 – Mei 2026</option>
                </select>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:11.5px; font-weight:600; color:#475569;">Sumber Data</span>
                <select class="filter-select">
                    <option selected>Semua</option>
                    <option>IDP Internal</option>
                    <option>Assessment External</option>
                </select>
            </div>
            <div class="card-actions">
                <a href="{{ route('karyawan.export', ['nik' => $employee->nik, 'type' => 'review-pengembangan']) }}" class="btn btn-outline">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Unduh Laporan
                </a>
                <button type="button" class="btn btn-outline" onclick="window.print()">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    Cetak
                </button>
            </div>
        </div>
    </div>

    {{-- A. RINGKASAN CAPAIAN PENGEMBANGAN --}}
    @php
        $totalIdpPlans = $employee->idpActionPlans ? $employee->idpActionPlans->count() : 0;
        $completedIdpPlans = $employee->idpActionPlans ? $employee->idpActionPlans->filter(function($p) {
            return in_array(strtolower(trim($p->status ?? '')), ['selesai', 'completed', 'done']) || (int)($p->progress_percent ?? 0) >= 100;
        })->count() : 0;
        $idpCompletionPercent = $totalIdpPlans > 0 ? (int) round(($completedIdpPlans / $totalIdpPlans) * 100) : 0;

        $reviews = $employee->developmentReviews ?: collect();
        $totalReviews = $reviews->count();
        $meningkatCount = $reviews->where('status', 'Meningkat')->count();
        $stabilCount = $reviews->where('status', 'Stabil')->count();
        $belumCount = $reviews->where('status', 'Belum Meningkat')->count();
    @endphp
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">A. Ringkasan Capaian Pengembangan</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.2fr 1fr 1fr 1.2fr; gap: 20px; align-items: center;">
            {{-- Circular Ring IDP Completion --}}
            <div style="display:flex; align-items:center; gap:16px;">
                <div style="position:relative; width:85px; height:85px;">
                    <svg viewBox="0 0 36 36" style="width:100%; height:100%; transform: rotate(-90deg);">
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e2e8f0" stroke-width="4.5" />
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#16a34a" stroke-width="4.5" stroke-dasharray="{{ $idpCompletionPercent }}, {{ max(0, 100 - $idpCompletionPercent) }}" stroke-dashoffset="0" />
                    </svg>
                    <div style="position:absolute; top:0; left:0; right:0; bottom:0; display:flex; align-items:center; justify-content:center;">
                        <span style="font-size:17px; font-weight:800; color:#0f172a;">{{ $idpCompletionPercent }}%</span>
                    </div>
                </div>
                <div>
                    <div style="font-size:12px; font-weight:700; color:#0f172a;">Tingkat Penyelesaian IDP</div>
                    <div style="font-size:11.5px; color:#64748b;">({{ $completedIdpPlans }} dari {{ $totalIdpPlans }} rencana)</div>
                </div>
            </div>

            {{-- Stat 1: Kompetensi Meningkat --}}
            <div style="border-left:1px solid #e2e8f0; padding-left:20px; text-align:center;">
                <div style="font-size:11.5px; font-weight:700; color:#1e293b; margin-bottom:4px;">Kompetensi dengan Peningkatan</div>
                <div style="font-size:26px; font-weight:800; color:#16a34a; line-height:1.2;">{{ $meningkatCount }}</div>
                <div style="font-size:11px; color:#64748b;">dari {{ $totalReviews }} kompetensi</div>
            </div>

            {{-- Stat 2: Kompetensi Stabil --}}
            <div style="border-left:1px solid #e2e8f0; padding-left:20px; text-align:center;">
                <div style="font-size:11.5px; font-weight:700; color:#1e293b; margin-bottom:4px;">Kompetensi Stabil</div>
                <div style="font-size:26px; font-weight:800; color:#2563eb; line-height:1.2;">{{ $stabilCount }}</div>
                <div style="font-size:11px; color:#64748b;">dari {{ $totalReviews }} kompetensi</div>
            </div>

            {{-- Stat 3: Kompetensi Belum Meningkat --}}
            <div style="border-left:1px solid #e2e8f0; padding-left:20px; text-align:center;">
                <div style="font-size:11.5px; font-weight:700; color:#1e293b; margin-bottom:4px;">Kompetensi Belum Meningkat</div>
                <div style="font-size:26px; font-weight:800; color:#ea580c; line-height:1.2;">{{ $belumCount }}</div>
                <div style="font-size:11px; color:#64748b;">dari {{ $totalReviews }} kompetensi</div>
            </div>
        </div>
    </div>

    {{-- B. REVIEW PER KOMPETENSI (PERBANDINGAN LEVEL) --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">B. Review per Kompetensi <span style="font-weight:500; text-transform:none; color:#64748b;">(Perbandingan Level)</span></span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="text-center" style="width:4%;">No.</th>
                        <th style="width:18%;">Kompetensi ({{ $totalReviews }} Kompetensi)</th>
                        <th class="text-center" style="width:12%;">Level Saat Ini<br><small style="font-weight:400; color:#64748b;">(Periode Sebelumnya)</small></th>
                        <th class="text-center" style="width:12%;">Level Saat Ini<br><small style="font-weight:400; color:#64748b;">(Periode Review)</small></th>
                        <th class="text-center" style="width:12%;">Level Target<br><small style="font-weight:400; color:#64748b;">(Engineering Manager)</small></th>
                        <th class="text-center" style="width:10%;">Peningkatan</th>
                        <th class="text-center" style="width:12%;">Status</th>
                        <th style="width:20%;">Catatan Reviewer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employee->developmentReviews as $rev)
                        <tr>
                            <td class="text-center">{{ $rev->order_no }}</td>
                            <td style="font-weight:700;">{{ $rev->competency }}</td>
                            <td class="text-center font-bold">{{ $rev->previous_level }}</td>
                            <td class="text-center font-bold">{{ $rev->current_level }}</td>
                            <td class="text-center font-bold">{{ $rev->target_level }}</td>
                            <td class="text-center font-bold" style="color: {{ $rev->growth > 0 ? '#16a34a' : '#475569' }};">
                                {{ $rev->growth > 0 ? '+' . $rev->growth : '0' }}
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
                            <td style="font-size:11px; color:#334155;">{{ $rev->reviewer_notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bottom Row: C. Feedback Atasan Langsung & D. Rekomendasi Tindak Lanjut --}}
    <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 20px;">
        {{-- C. Feedback Atasan Langsung --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <span class="card-title" style="font-size:12.5px;">C. Feedback Atasan Langsung</span>
                </div>
            </div>
            <div style="display:flex; align-items:flex-start; gap:14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:var(--radius-md); padding:14px;">
                <div style="width:32px; height:32px; border-radius:50%; background:#eff6ff; color:#1d4ed8; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <div style="font-size:11.5px; color:#334155; line-height:1.6;">
                    "{{ $employee->name }} menunjukkan perkembangan yang baik selama periode ini. Terlihat peningkatan dalam kepemimpinan, komunikasi, dan kemampuan eksekusi. Fokus selanjutnya adalah memperkuat kemampuan analisis strategis dan pengambilan keputusan berbasis data untuk siap menempati posisi Engineering Manager saat penugasan berikutnya."
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:10px; padding-top:8px; border-top:1px solid #e2e8f0; font-size:11px; color:#64748b;">
                        <div>
                            <strong style="color:#0f172a;">Andi Wijaya</strong><br>
                            <span>Engineering Division Head</span>
                        </div>
                        <div>Tanggal Review: <strong>20 Mei 2027</strong></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- D. Rekomendasi Tindak Lanjut --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <span class="card-title" style="font-size:12.5px;">D. Rekomendasi Tindak Lanjut</span>
                </div>
            </div>
            <div style="display:flex; flex-direction:column; gap:10px; font-size:11.5px; color:#334155;">
                <div style="display:flex; align-items:flex-start; gap:10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>Lanjutkan program pengembangan sesuai IDP dengan fokus pada <strong>Analysis & Judgement</strong>.</span>
                </div>
                <div style="display:flex; align-items:flex-start; gap:10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>Berikan kesempatan memimpin proyek strategis yang berdampak lintas departemen.</span>
                </div>
                <div style="display:flex; align-items:flex-start; gap:10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>Coaching/mentoring dengan Engineering Manager untuk mempercepat kesiapan.</span>
                </div>
                <div style="display:flex; align-items:flex-start; gap:10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>Review berikutnya dilakukan pada <strong>Mei 2028</strong>.</span>
                </div>
            </div>
        </div>
    </div>
</div>
