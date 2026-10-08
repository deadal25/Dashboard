{{-- Tab 8: Review Hasil Pengembangan --}}
<div class="tab-pane-content">
    {{-- Header Filters & Actions Bar --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:12px;">
        <div>
            <h2 style="font-size:15px; font-weight:800; color:#0b2545; text-transform:uppercase; margin-bottom:4px;">Review Hasil Pengembangan</h2>
            <div style="font-size:11.5px; color:#64748b;">Evaluasi pencapaian kompetensi manajerial & technical, umpan balik atasan langsung, dan rekomendasi tindak lanjut.</div>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <div class="card-actions">
                <button type="button" class="btn btn-primary" data-modal-target="modal-tambah-review">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Review
                </button>
                <button type="button" class="btn btn-outline" data-modal-target="modal-edit-review">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit Review
                </button>
                <button type="button" class="btn btn-outline text-danger" data-modal-target="modal-hapus-review">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Hapus Review
                </button>
                <button type="button" class="btn btn-outline" data-modal-target="modal-edit-feedback-review">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    Edit Feedback & Rekomendasi
                </button>
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

        $manajerialReviews = $reviews->filter(function($r) {
            return strtolower($r->competency_type ?? 'manajerial') !== 'technical';
        });

        $technicalReviews = $reviews->filter(function($r) {
            return strtolower($r->competency_type ?? '') === 'technical';
        });

        $recommendationItems = array_filter(array_map('trim', explode("\n", $employee->review_recommendations ?: '')));
        if (empty($recommendationItems)) {
            $recommendationItems = [
                'Lanjutkan program pengembangan sesuai IDP dengan fokus pada Analysis & Judgement.',
                'Berikan kesempatan memimpin proyek strategis yang berdampak lintas departemen.',
                'Coaching/mentoring dengan Engineering Manager untuk mempercepat kesiapan.',
                'Review berikutnya dilakukan pada Mei 2028.'
            ];
        }
    @endphp

    {{-- A. RINGKASAN CAPAIAN PENGEMBANGAN --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">A. Ringkasan Capaian Pengembangan</span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-review" title="Tambah Review Kompetensi Baru">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Review
                </button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.2fr 1fr 1fr 1.2fr; gap: 20px; align-items: center;">
            {{-- Circular Ring IDP Completion --}}
            <div style="display:flex; align-items:center; gap:16px;">
                <div style="position:relative; width:85px; height:85px; flex-shrink:0;">
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
                    <div style="font-size:11.5px; color:#64748b;">({{ $completedIdpPlans }} dari {{ $totalIdpPlans }} rencana aksi)</div>
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

    {{-- B. REVIEW PER KOMPETENSI MANAJERIAL --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">B. Review per Kompetensi Manajerial <span style="font-weight:500; text-transform:none; color:#64748b;">(Perbandingan Level)</span></span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-review" data-preselect-type="Manajerial" title="Tambah Review Kompetensi Manajerial">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Review
                </button>
                <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-review" data-preselect-type="Manajerial" title="Edit Review Kompetensi Manajerial">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </button>
                <button type="button" class="btn-mini btn-mini-danger" data-modal-target="modal-hapus-review" data-preselect-type="Manajerial" title="Hapus Review Kompetensi Manajerial">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Hapus
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="text-center" style="width:4%;">No.</th>
                        <th style="width:20%;">Kompetensi ({{ $manajerialReviews->count() }} Manajerial)</th>
                        <th class="text-center" style="width:11%;">Level Sebelumnya</th>
                        <th class="text-center" style="width:11%;">Level Saat Ini</th>
                        <th class="text-center" style="width:11%;">Level Target</th>
                        <th class="text-center" style="width:9%;">Peningkatan</th>
                        <th class="text-center" style="width:12%;">Status</th>
                        <th style="width:16%;">Catatan Reviewer</th>
                        <th class="text-center" style="width:6%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($manajerialReviews as $rev)
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
                            <td class="text-center">
                                <div style="display:inline-flex; align-items:center; gap:4px;">
                                    <button type="button" class="btn-action-icon" onclick="openEditReviewModal({{ $rev->id }})" title="Edit Review">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <button type="button" class="btn-action-icon text-danger" onclick="openDeleteReviewModal({{ $rev->id }})" title="Hapus Review">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted" style="padding: 24px; color:#64748b; font-size:12px;">
                                Belum ada review kompetensi manajerial yang ditambahkan. Silakan klik tombol <strong>Tambah Review</strong> di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- C. REVIEW PER KOMPETENSI TECHNICAL --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">C. Review per Kompetensi Technical <span style="font-weight:500; text-transform:none; color:#64748b;">(Perbandingan Level)</span></span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-review" data-preselect-type="Technical" title="Tambah Review Kompetensi Technical">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Review
                </button>
                <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-review" data-preselect-type="Technical" title="Edit Review Kompetensi Technical">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </button>
                <button type="button" class="btn-mini btn-mini-danger" data-modal-target="modal-hapus-review" data-preselect-type="Technical" title="Hapus Review Kompetensi Technical">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Hapus
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="text-center" style="width:4%;">No.</th>
                        <th style="width:20%;">Kompetensi ({{ $technicalReviews->count() }} Technical)</th>
                        <th class="text-center" style="width:11%;">Level Sebelumnya</th>
                        <th class="text-center" style="width:11%;">Level Saat Ini</th>
                        <th class="text-center" style="width:11%;">Level Target</th>
                        <th class="text-center" style="width:9%;">Peningkatan</th>
                        <th class="text-center" style="width:12%;">Status</th>
                        <th style="width:16%;">Catatan Reviewer</th>
                        <th class="text-center" style="width:6%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($technicalReviews as $rev)
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
                            <td class="text-center">
                                <div style="display:inline-flex; align-items:center; gap:4px;">
                                    <button type="button" class="btn-action-icon" onclick="openEditReviewModal({{ $rev->id }})" title="Edit Review">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <button type="button" class="btn-action-icon text-danger" onclick="openDeleteReviewModal({{ $rev->id }})" title="Hapus Review">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted" style="padding: 24px; color:#64748b; font-size:12px;">
                                Belum ada review kompetensi technical yang ditambahkan. Silakan klik tombol <strong>Tambah Review</strong> di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bottom Row: D. Feedback Atasan Langsung & E. Rekomendasi Tindak Lanjut --}}
    <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 20px;">
        {{-- D. Feedback Atasan Langsung --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <span class="card-title" style="font-size:12.5px;">D. Feedback Atasan Langsung</span>
                </div>
                <div class="quick-action-btn-group">
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-edit-feedback-review" title="Edit Feedback Atasan">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit Feedback
                    </button>
                </div>
            </div>
            <div style="display:flex; align-items:flex-start; gap:14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:var(--radius-md); padding:14px;">
                <div style="width:34px; height:34px; border-radius:50%; background:#eff6ff; color:#1d4ed8; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <div style="font-size:12px; color:#334155; line-height:1.6; flex:1;">
                    "{!! nl2br(e($employee->review_feedback_text ?: 'Budi Santosoo menunjukkan perkembangan yang baik selama periode ini. Terlihat peningkatan dalam kepemimpinan, komunikasi, dan kemampuan eksekusi. Fokus selanjutnya adalah memperkuat kemampuan analisis strategis dan pengambilan keputusan berbasis data untuk siap menempati posisi Engineering Manager saat penugasan berikutnya.')) !!}"
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; padding-top:10px; border-top:1px solid #e2e8f0; font-size:11.5px; color:#64748b; flex-wrap:wrap; gap:8px;">
                        <div>
                            <strong style="color:#0f172a;">{{ $employee->review_reviewer_name ?: 'Andi Wijaya' }}</strong><br>
                            <span>{{ $employee->review_reviewer_title ?: 'Engineering Division Head' }}</span>
                        </div>
                        <div>Tanggal Review: <strong style="color:#0f172a;">{{ $employee->review_date ?: '20 Mei 2027' }}</strong></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- E. Rekomendasi Tindak Lanjut --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <span class="card-title" style="font-size:12.5px;">E. Rekomendasi Tindak Lanjut</span>
                </div>
                <div class="quick-action-btn-group">
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-edit-feedback-review" title="Edit Rekomendasi Tindak Lanjut">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit Rekomendasi
                    </button>
                </div>
            </div>
            <div style="display:flex; flex-direction:column; gap:10px; font-size:12px; color:#334155;">
                @foreach($recommendationItems as $recItem)
                    <div style="display:flex; align-items:flex-start; gap:10px; line-height:1.5;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" style="flex-shrink:0; margin-top:1px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ $recItem }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
