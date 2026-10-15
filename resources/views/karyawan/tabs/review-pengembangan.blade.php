{{-- Tab 8: Review Hasil Pengembangan --}}
<div class="tab-pane-content">
    @php
        $idpPlans = $employee->idpActionPlans ?: collect();
        $reviews = $employee->developmentReviews ?: collect();
        $totalReviews = $reviews->count();
        $meningkatCount = $reviews->where('status', 'Meningkat')->count();
        $stabilCount = $reviews->where('status', 'Stabil')->count();
        $belumCount = $reviews->where('status', 'Belum Meningkat')->count();

        $availablePeriods = $reviews->pluck('period')->filter()->unique()->values();
        if ($availablePeriods->isEmpty()) {
            $availablePeriods = collect(['Juni 2026 – Mei 2027']);
        }

        $manajerialReviews = $reviews->filter(function($r) {
            return strtolower($r->competency_type ?? 'manajerial') !== 'technical';
        });

        $technicalReviews = $reviews->filter(function($r) {
            return strtolower($r->competency_type ?? '') === 'technical';
        });

        // Hitung rencana aksi IDP yang selesai
        $idpCompletedCount = $idpPlans->filter(function($p) {
            return in_array(strtolower(trim($p->status ?? '')), ['selesai', 'completed', 'done']) || (int)($p->progress_percent ?? 0) >= 100;
        })->count();

        $totalIdpPlans = $idpPlans->count();
        $completedIdpPlans = $idpCompletedCount;
        $idpCompletionPercent = $totalIdpPlans > 0 ? (int) round(($completedIdpPlans / $totalIdpPlans) * 100) : 0;

        $recommendationItems = array_filter(array_map('trim', explode("\n", $employee->review_recommendations ?: '')));
        if (empty($recommendationItems)) {
            $recommendationItems = [
                'Lanjutkan program pengembangan sesuai IDP dengan fokus pada Analysis & Judgement.',
                'Berikan kesempatan memimpin proyek strategis yang berdampak lintas departemen.',
                'Coaching/mentoring dengan Engineering Manager untuk mempercepat kesiapan.',
                'Review berikutnya dilakukan pada Mei 2028.'
            ];
        }

        // Parse Feedback Comments (Mendukung 1, 2, atau lebih komentar atasan)
        $feedbackList = [];
        $decodedFeedback = json_decode($employee->review_feedback_text ?? '', true);
        if (is_array($decodedFeedback) && !empty($decodedFeedback) && isset($decodedFeedback[0]['text'])) {
            $feedbackList = $decodedFeedback;
        } elseif (!empty($employee->review_feedback_text)) {
            $feedbackList = [
                [
                    'text' => $employee->review_feedback_text,
                    'reviewer_name' => $employee->review_reviewer_name ?: 'Andi Wijaya',
                    'reviewer_title' => $employee->review_reviewer_title ?: 'Engineering Division Head',
                    'date' => $employee->review_date ?: '20 Mei 2027',
                ]
            ];
        } else {
            $feedbackList = [
                [
                    'text' => 'Budi Santosoo menunjukkan perkembangan yang baik selama periode ini. Terlihat peningkatan dalam kepemimpinan, komunikasi, dan kemampuan eksekusi. Fokus selanjutnya adalah memperkuat kemampuan analisis strategis dan pengambilan keputusan berbasis data untuk siap menempati posisi Engineering Manager saat penugasan berikutnya.',
                    'reviewer_name' => 'Andi Wijaya',
                    'reviewer_title' => 'Engineering Division Head',
                    'date' => '20 Mei 2027',
                ]
            ];
        }

        if (!function_exists('formatReviewDateIndo')) {
            function formatReviewDateIndo($val) {
                if (empty($val)) return date('d F Y');
                $val = trim($val);
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
                    $months = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ];
                    $parts = explode('-', $val);
                    $d = ltrim($parts[2], '0');
                    $m = $parts[1];
                    $y = $parts[0];
                    return $d . ' ' . ($months[$m] ?? $m) . ' ' . $y;
                }
                return $val;
            }
        }
    @endphp

    {{-- Filter Toolbar & Actions (Matching Mockup Image 2) --}}
    <style>
        .filter-review-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 10px 16px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
        }
        .filter-review-group {
            display: flex;
            align-items: center;
            gap: 22px;
            flex-wrap: wrap;
        }
        .filter-review-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .filter-review-label {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            white-space: nowrap;
        }
        .filter-review-select {
            height: 34px;
            padding: 0 32px 0 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #0f172a;
            background-color: #ffffff;
            cursor: pointer;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .filter-review-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
        }

        /* Aturan Print: Pertahankan warna asli & sembunyikan toolbar filter, tombol Tambah, Edit, Cetak, dan seluruh kontrol aksi saat dicetak */
        @media print {
            *,
            *::before,
            *::after {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .filter-review-card,
            .quick-action-btn-group,
            .btn-mini,
            .card-actions,
            button,
            [data-modal-target],
            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="filter-review-card">
        {{-- Left Filters: Periode Review & Sumber Data --}}
        <div class="filter-review-group">
            <div class="filter-review-item">
                <label for="filter-periode-review" class="filter-review-label">Periode Review</label>
                <select id="filter-periode-review" class="filter-review-select" onchange="applyReviewFilters()" style="min-width: 195px;">
                    <option value="all">Semua Periode</option>
                    @foreach($availablePeriods as $p)
                        <option value="{{ $p }}" {{ ($loop->first || $p === 'Juni 2026 – Mei 2027') ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-review-item">
                <label for="filter-sumber-data" class="filter-review-label">Sumber Data</label>
                <select id="filter-sumber-data" class="filter-review-select" onchange="applyReviewFilters()" style="min-width: 135px;">
                    <option value="all" selected>Semua</option>
                    <option value="manajerial">Manajerial</option>
                    <option value="technical">Technical</option>
                </select>
            </div>
        </div>

        {{-- Right Actions: Tambah, Edit, Unduh Laporan, Cetak --}}
        <div class="card-actions" style="margin:0; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            <button type="button" class="btn btn-primary" data-modal-target="modal-tambah-review">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-edit-review">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
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

    {{-- A. RINGKASAN CAPAIAN PENGEMBANGAN --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">A. Ringkasan Capaian Pengembangan</span>
            </div>
        </div>

        <div class="review-summary-grid-responsive">
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
                <div style="font-size:26px; font-weight:800; color:#16a34a; line-height:1.2;" id="stat-meningkat-count">{{ $meningkatCount }}</div>
                <div style="font-size:11px; color:#64748b;">dari <span class="stat-total-reviews-count">{{ $totalReviews }}</span> kompetensi</div>
            </div>

            {{-- Stat 2: Kompetensi Stabil --}}
            <div style="border-left:1px solid #e2e8f0; padding-left:20px; text-align:center;">
                <div style="font-size:11.5px; font-weight:700; color:#1e293b; margin-bottom:4px;">Kompetensi Stabil</div>
                <div style="font-size:26px; font-weight:800; color:#2563eb; line-height:1.2;" id="stat-stabil-count">{{ $stabilCount }}</div>
                <div style="font-size:11px; color:#64748b;">dari <span class="stat-total-reviews-count">{{ $totalReviews }}</span> kompetensi</div>
            </div>

            {{-- Stat 3: Kompetensi Belum Meningkat --}}
            <div style="border-left:1px solid #e2e8f0; padding-left:20px; text-align:center;">
                <div style="font-size:11.5px; font-weight:700; color:#1e293b; margin-bottom:4px;">Kompetensi Belum Meningkat</div>
                <div style="font-size:26px; font-weight:800; color:#ea580c; line-height:1.2;" id="stat-belum-count">{{ $belumCount }}</div>
                <div style="font-size:11px; color:#64748b;">dari <span class="stat-total-reviews-count">{{ $totalReviews }}</span> kompetensi</div>
            </div>
        </div>
    </div>

    {{-- B. REVIEW PER KOMPETENSI MANAJERIAL --}}
    <div class="dashboard-card" id="section-review-manajerial">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">B. Review per Kompetensi Manajerial <span style="font-weight:500; text-transform:none; color:#64748b;">(Perbandingan Level)</span></span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-review" data-preselect-section="B" title="Tambah Kompetensi Manajerial">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah
                </button>
                <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-review" data-preselect-section="B" title="Edit Kompetensi Manajerial">
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
                        <th style="width:23%;">Kompetensi (<span id="title-count-manajerial">{{ $manajerialReviews->count() }}</span> Manajerial)</th>
                        <th class="text-center" style="width:11%;">Level Sebelumnya</th>
                        <th class="text-center" style="width:11%;">Level Saat Ini</th>
                        <th class="text-center" style="width:11%;">Level Target</th>
                        <th class="text-center" style="width:8%;">Peningkatan</th>
                        <th class="text-center" style="width:12%;">Status</th>
                        <th style="width:20%;">Catatan Reviewer</th>
                    </tr>
                </thead>
                <tbody id="tbody-review-manajerial">
                    @forelse($manajerialReviews as $rev)
                        <tr class="review-table-row review-row-manajerial" data-type="manajerial" data-period="{{ $rev->period }}" data-status="{{ strtolower($rev->status) }}">
                            <td class="text-center col-no">{{ $loop->iteration }}</td>
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
                        <tr class="no-data-initial-manajerial">
                            <td colspan="8" class="text-center text-muted" style="padding: 24px; color:#64748b; font-size:12px;">
                                Belum ada data kompetensi manajerial yang ditambahkan.<span class="no-print"> Silakan klik tombol <strong>Tambah</strong> di atas.</span>
                            </td>
                        </tr>
                    @endforelse
                    <tr id="empty-row-manajerial" style="display:none;">
                        <td colspan="8" class="text-center text-muted" style="padding: 24px; color:#64748b; font-size:12px;">
                            Tidak ada data review manajerial untuk filter periode yang dipilih.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- C. REVIEW PER KOMPETENSI TECHNICAL --}}
    <div class="dashboard-card" id="section-review-technical">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">C. Review per Kompetensi Technical <span style="font-weight:500; text-transform:none; color:#64748b;">(Perbandingan Level)</span></span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-review" data-preselect-section="C" title="Tambah Kompetensi Technical">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah
                </button>
                <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-review" data-preselect-section="C" title="Edit Kompetensi Technical">
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
                        <th style="width:23%;">Kompetensi (<span id="title-count-technical">{{ $technicalReviews->count() }}</span> Technical)</th>
                        <th class="text-center" style="width:11%;">Level Sebelumnya</th>
                        <th class="text-center" style="width:11%;">Level Saat Ini</th>
                        <th class="text-center" style="width:11%;">Level Target</th>
                        <th class="text-center" style="width:8%;">Peningkatan</th>
                        <th class="text-center" style="width:12%;">Status</th>
                        <th style="width:20%;">Catatan Reviewer</th>
                    </tr>
                </thead>
                <tbody id="tbody-review-technical">
                    @forelse($technicalReviews as $rev)
                        <tr class="review-table-row review-row-technical" data-type="technical" data-period="{{ $rev->period }}" data-status="{{ strtolower($rev->status) }}">
                            <td class="text-center col-no">{{ $loop->iteration }}</td>
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
                        <tr class="no-data-initial-technical">
                            <td colspan="8" class="text-center text-muted" style="padding: 24px; color:#64748b; font-size:12px;">
                                Belum ada data kompetensi technical yang ditambahkan.<span class="no-print"> Silakan klik tombol <strong>Tambah</strong> di atas.</span>
                            </td>
                        </tr>
                    @endforelse
                    <tr id="empty-row-technical" style="display:none;">
                        <td colspan="8" class="text-center text-muted" style="padding: 24px; color:#64748b; font-size:12px;">
                            Tidak ada data review technical untuk filter periode yang dipilih.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bottom Row: D. Feedback Atasan Langsung & E. Rekomendasi Tindak Lanjut --}}
    <div class="review-bottom-grid">
        {{-- D. Feedback Atasan Langsung --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <span class="card-title" style="font-size:12.5px;">D. Feedback Atasan Langsung</span>
                </div>
                <div class="quick-action-btn-group">
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-review" data-preselect-section="D" title="Tambah Feedback Atasan">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Tambah
                    </button>
                    <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-review" data-preselect-section="D" title="Edit Feedback Atasan">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit
                    </button>
                </div>
            </div>
            <div style="display:flex; flex-direction:column; gap:12px;">
                @forelse($feedbackList as $fb)
                    <div style="display:flex; align-items:flex-start; gap:14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:var(--radius-md); padding:14px;">
                        <div style="width:34px; height:34px; border-radius:50%; background:#eff6ff; color:#1d4ed8; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        </div>
                        <div style="font-size:12px; color:#334155; line-height:1.6; flex:1;">
                            "{!! nl2br(e($fb['text'])) !!}"
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; padding-top:10px; border-top:1px solid #e2e8f0; font-size:11.5px; color:#64748b; flex-wrap:wrap; gap:8px;">
                                <div>
                                    <strong style="color:#0f172a;">{{ $fb['reviewer_name'] }}</strong><br>
                                    <span>{{ $fb['reviewer_title'] }}</span>
                                </div>
                                <div>Tanggal Review: <strong style="color:#0f172a;">{{ formatReviewDateIndo($fb['date'] ?? '') }}</strong></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center; padding:24px; color:#64748b; font-size:12px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:var(--radius-md);">
                        Belum ada feedback atasan langsung.<span class="no-print"> Silakan klik tombol <strong>Tambah</strong> di atas.</span>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- E. Rekomendasi Tindak Lanjut --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <span class="card-title" style="font-size:12.5px;">E. Rekomendasi Tindak Lanjut</span>
                </div>
                <div class="quick-action-btn-group">
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-review" data-preselect-section="E" title="Tambah Rekomendasi Tindak Lanjut">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Tambah
                    </button>
                    <button type="button" class="btn-mini btn-mini-outline" data-modal-target="modal-edit-review" data-preselect-section="E" title="Edit Rekomendasi Tindak Lanjut">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit
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

<script>
    function applyReviewFilters() {
        const periodSelect = document.getElementById('filter-periode-review');
        const sourceSelect = document.getElementById('filter-sumber-data');
        if (!periodSelect || !sourceSelect) return;

        const selectedPeriod = periodSelect.value.trim();
        const selectedSource = sourceSelect.value.trim().toLowerCase();

        const secManajerial = document.getElementById('section-review-manajerial');
        const secTechnical = document.getElementById('section-review-technical');

        // Toggle container visibility based on Sumber Data
        if (secManajerial) {
            secManajerial.style.display = (selectedSource === 'all' || selectedSource === 'manajerial') ? '' : 'none';
        }
        if (secTechnical) {
            secTechnical.style.display = (selectedSource === 'all' || selectedSource === 'technical') ? '' : 'none';
        }

        // Helper to normalize period string (en-dash, em-dash, hyphens, and whitespace)
        const norm = (str) => (str || '').replace(/[\u2010-\u2015\u2212]/g, '-').replace(/\s+/g, ' ').trim().toLowerCase();
        const normSelectedPeriod = norm(selectedPeriod);

        let totalVisible = 0;
        let meningkatVisible = 0;
        let stabilVisible = 0;
        let belumVisible = 0;

        // 1. Filter Section B (Manajerial)
        const manajerialRows = document.querySelectorAll('.review-row-manajerial');
        let manajerialVisibleCount = 0;
        manajerialRows.forEach(row => {
            const rowPeriod = norm(row.getAttribute('data-period'));
            const periodMatch = (normSelectedPeriod === 'all' || rowPeriod === normSelectedPeriod);
            const shouldShow = (selectedSource === 'all' || selectedSource === 'manajerial') && periodMatch;

            if (shouldShow) {
                row.style.display = '';
                manajerialVisibleCount++;
                const noCell = row.querySelector('.col-no');
                if (noCell) noCell.textContent = manajerialVisibleCount;

                totalVisible++;
                const status = (row.getAttribute('data-status') || '').toLowerCase();
                if (status.includes('meningkat') && !status.includes('belum')) {
                    meningkatVisible++;
                } else if (status.includes('stabil')) {
                    stabilVisible++;
                } else if (status.includes('belum')) {
                    belumVisible++;
                }
            } else {
                row.style.display = 'none';
            }
        });

        const emptyRowM = document.getElementById('empty-row-manajerial');
        if (emptyRowM) {
            if (manajerialRows.length > 0 && manajerialVisibleCount === 0 && (selectedSource === 'all' || selectedSource === 'manajerial')) {
                emptyRowM.style.display = '';
            } else {
                emptyRowM.style.display = 'none';
            }
        }

        // 2. Filter Section C (Technical)
        const technicalRows = document.querySelectorAll('.review-row-technical');
        let technicalVisibleCount = 0;
        technicalRows.forEach(row => {
            const rowPeriod = norm(row.getAttribute('data-period'));
            const periodMatch = (normSelectedPeriod === 'all' || rowPeriod === normSelectedPeriod);
            const shouldShow = (selectedSource === 'all' || selectedSource === 'technical') && periodMatch;

            if (shouldShow) {
                row.style.display = '';
                technicalVisibleCount++;
                const noCell = row.querySelector('.col-no');
                if (noCell) noCell.textContent = technicalVisibleCount;

                totalVisible++;
                const status = (row.getAttribute('data-status') || '').toLowerCase();
                if (status.includes('meningkat') && !status.includes('belum')) {
                    meningkatVisible++;
                } else if (status.includes('stabil')) {
                    stabilVisible++;
                } else if (status.includes('belum')) {
                    belumVisible++;
                }
            } else {
                row.style.display = 'none';
            }
        });

        const emptyRowT = document.getElementById('empty-row-technical');
        if (emptyRowT) {
            if (technicalRows.length > 0 && technicalVisibleCount === 0 && (selectedSource === 'all' || selectedSource === 'technical')) {
                emptyRowT.style.display = '';
            } else {
                emptyRowT.style.display = 'none';
            }
        }

        // 3. Update Section A Stat Counters
        const elMeningkat = document.getElementById('stat-meningkat-count');
        const elStabil = document.getElementById('stat-stabil-count');
        const elBelum = document.getElementById('stat-belum-count');
        const elTotals = document.querySelectorAll('.stat-total-reviews-count');

        if (elMeningkat) elMeningkat.textContent = meningkatVisible;
        if (elStabil) elStabil.textContent = stabilVisible;
        if (elBelum) elBelum.textContent = belumVisible;
        elTotals.forEach(el => el.textContent = totalVisible);

        // 4. Update section table title counts
        const titleCountM = document.getElementById('title-count-manajerial');
        if (titleCountM) titleCountM.textContent = manajerialVisibleCount;

        const titleCountT = document.getElementById('title-count-technical');
        if (titleCountT) titleCountT.textContent = technicalVisibleCount;
    }

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        applyReviewFilters();
    } else {
        document.addEventListener('DOMContentLoaded', applyReviewFilters);
    }
</script>
