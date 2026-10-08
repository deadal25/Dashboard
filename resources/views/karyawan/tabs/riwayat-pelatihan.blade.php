{{-- Tab 9: Riwayat Pelatihan [BARU] --}}
<div class="tab-pane-content">
    <div style="margin-bottom:16px;">
        <h2 style="font-size:15px; font-weight:800; color:#0b2545; text-transform:uppercase;">Riwayat Pelatihan</h2>
    </div>

    {{-- A. RINGKASAN PELATIHAN (5 Stat Cards) --}}
    <div style="margin-bottom:20px;">
        <div style="font-size:13px; font-weight:800; color:#0b2545; text-transform:uppercase; margin-bottom:12px;">
            A. Ringkasan Pelatihan
        </div>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px;">
            {{-- Card 1: Total Pelatihan --}}
            <div class="stat-card-single">
                <div class="stat-icon-wrapper" style="background:#eff6ff; color:#1d4ed8;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-title">Total Pelatihan Diikuti</span>
                    <span class="stat-number" id="stat-total-pelatihan">{{ $trainingSummary['total_pelatihan'] ?? $employee->trainingHistories->count() }}</span>
                    <span style="font-size:10.5px; color:#64748b;">Semua Tahun</span>
                </div>
            </div>

            {{-- Card 2: Total Jam --}}
            <div class="stat-card-single">
                <div class="stat-icon-wrapper" style="background:#f0fdf4; color:#16a34a;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-title">Total Jam Pelatihan</span>
                    <span class="stat-number" id="stat-total-jam">{{ $trainingSummary['total_jam'] ?? $employee->trainingHistories->sum('duration_hours') }}</span>
                    <span style="font-size:10.5px; color:#64748b;">Jam (Semua Tahun)</span>
                </div>
            </div>

            {{-- Card 3: Pelatihan Tahun Ini (Hanya yang sudah upload dokumentasi) --}}
            <div class="stat-card-single">
                <div class="stat-icon-wrapper" style="background:#eff6ff; color:#2563eb;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-title">Pelatihan Tahun Ini ({{ $trainingSummary['current_year_fy'] ?? ('FY' . date('y')) }})</span>
                    <span class="stat-number" id="stat-tahun-ini-count">{{ $trainingSummary['tahun_ini_count'] ?? 0 }}</span>
                    <span style="font-size:10.5px; color:#64748b;" title="Hanya menghitung pelatihan tahun ini yang telah mengunggah bukti dokumentasi">
                        Ada Dokumentasi ({{ $trainingSummary['tahun_ini_count'] ?? 0 }}/{{ $trainingSummary['tahun_ini_total_count'] ?? 0 }})
                    </span>
                </div>
            </div>

            {{-- Card 4: Jam Pelatihan Tahun Ini --}}
            <div class="stat-card-single">
                <div class="stat-icon-wrapper" style="background:#f0fdf4; color:#16a34a;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-title">Jam Pelatihan Tahun Ini</span>
                    <span class="stat-number" id="stat-tahun-ini-jam">{{ $trainingSummary['tahun_ini_jam'] ?? 0 }}</span>
                    <span style="font-size:10.5px; color:#64748b;">Jam (Terdokumentasi)</span>
                </div>
            </div>

            {{-- Card 5: Sertifikasi --}}
            <div class="stat-card-single">
                <div class="stat-icon-wrapper" style="background:#faf5ff; color:#9333ea;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-title">Sertifikasi yang Dimiliki</span>
                    <span class="stat-number" id="stat-sertifikasi">{{ $trainingSummary['sertifikasi'] ?? $employee->certifications->count() }}</span>
                    <span style="font-size:10.5px; color:#64748b;">Sertifikat di Bagian C</span>
                </div>
            </div>
        </div>
    </div>

    {{-- B. RIWAYAT PELATIHAN --}}
    <style>
        .table-pelatihan {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.6px !important;
            text-align: left;
        }
        .table-pelatihan th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: 700;
            font-size: 12.6px !important;
            line-height: 1.35 !important;
            padding: 8px 10px !important;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .table-pelatihan td {
            padding: 8px 10px !important;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            font-size: 12.6px !important;
            line-height: 1.4 !important;
            vertical-align: middle;
        }
        .table-pelatihan tr:nth-child(even) {
            background-color: #fbfcfe;
        }
        .table-pelatihan tr:hover {
            background-color: #f1f5f9;
        }
        .table-pelatihan .col-date {
            font-weight: 700 !important;
            color: #0b2545 !important;
            white-space: nowrap;
            letter-spacing: 0.1px;
            padding: 8px 6px !important;
        }
        .table-pelatihan .badge-table-orange,
        .table-pelatihan .badge-table-green {
            font-size: 11.5px !important;
            padding: 3px 8px !important;
            border-radius: 4px;
            font-weight: 600;
        }
        .btn-doc-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.15s ease;
        }
        .btn-doc-link:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }
        @media (max-width: 640px) {
            .table-pelatihan th,
            .table-pelatihan td {
                padding: 6px 8px !important;
                font-size: 11.8px !important;
            }
        }
    </style>

    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:14px; font-weight:700;">B. Riwayat Pelatihan</span>
            </div>
            <div class="card-actions">
                <button type="button" class="btn btn-primary" data-modal-target="modal-tambah-pelatihan">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah
                </button>
                <button type="button" class="btn btn-outline" data-modal-target="modal-edit-pelatihan">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </button>
                <button type="button" class="btn btn-outline" data-modal-target="modal-hapus-pelatihan" style="color:#64748b;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Hapus
                </button>
            </div>
        </div>

        {{-- Filters Bar --}}
        <div class="filter-bar">
            <div class="filter-group">
                <select class="filter-select" id="filter-year">
                    <option value="all">Semua Tahun</option>
                    @php
                        $yearsList = (isset($trainingYears) && $trainingYears->isNotEmpty())
                            ? $trainingYears
                            : $employee->trainingHistories->map(fn($t) => $t->year)->filter()->unique()->sortDesc()->values();
                    @endphp
                    @foreach($yearsList as $yr)
                        <option value="{{ $yr }}">{{ $yr }}</option>
                    @endforeach
                </select>

                <select class="filter-select" id="filter-category">
                    <option value="all">Semua Kategori</option>
                    <option value="Functional">Functional</option>
                    <option value="Managerial">Managerial</option>
                </select>

                <select class="filter-select" id="filter-type">
                    <option value="all">Semua Jenis Pelatihan</option>
                    <option value="Classroom">Classroom</option>
                    <option value="On the Job">On the Job</option>
                    <option value="E-Learning">E-Learning</option>
                </select>
            </div>

            <div style="display:flex; align-items:center; gap:10px;">
                <div class="search-input-wrap">
                    <input type="text" id="table-search-training" placeholder="Cari pelatihan..." class="filter-search-input">
                </div>
                <a href="{{ route('karyawan.export', ['nik' => $employee->nik, 'type' => 'riwayat-pelatihan']) }}" class="btn btn-outline">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Export
                </a>
            </div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; font-size:12px; color:#64748b; font-weight:600;">
            <span id="training-table-count-info">Total {{ $employee->trainingHistories->count() }} pelatihan ({{ $trainingSummary['total_jam'] ?? $employee->trainingHistories->sum('duration_hours') }} jam)</span>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table-custom table-pelatihan">
                <thead>
                    <tr>
                        <th class="text-center" style="width:3.5%;">No.</th>
                        <th class="text-center" style="width:9.5%;">Tanggal</th>
                        <th style="width:19%;">Nama Pelatihan</th>
                        <th class="text-center" style="width:8%;">Kategori</th>
                        <th class="text-center" style="width:10%;">Jenis Pelatihan</th>
                        <th style="width:14%;">Penyelenggara</th>
                        <th class="text-center" style="width:7%;">Durasi (Jam)</th>
                        <th class="text-center" style="width:10%;">Dokumentasi</th>
                        <th style="width:19%;">Catatan</th>
                    </tr>
                </thead>
                <tbody id="training-table-body">
                    @forelse($employee->trainingHistories as $training)
                        <tr data-year="{{ $training->year }}" data-category="{{ $training->category }}" data-type="{{ $training->training_type }}" data-duration="{{ $training->duration_hours }}">
                            <td class="text-center">{{ $training->order_no ?? $loop->iteration }}</td>
                            <td class="text-center col-date">{{ $training->training_date }}</td>
                            <td style="font-weight:700;">{{ $training->training_name }}</td>
                            <td class="text-center">
                                @if($training->category === 'Functional')
                                    <span class="badge-table-orange">Functional</span>
                                @else
                                    <span class="badge-table-green">Managerial</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $training->training_type }}</td>
                            <td>{{ $training->organizer }}</td>
                            <td class="text-center font-bold">{{ $training->duration_hours }}</td>
                            <td class="text-center">
                                @if($training->documentation)
                                    @if($training->is_image)
                                        <a href="{{ asset($training->documentation) }}" target="_blank" rel="noopener noreferrer" class="btn-doc-link" style="background:#eff6ff; border:1px solid #bfdbfe; color:#1d4ed8;" title="Buka Gambar">
                                            <img src="{{ asset($training->documentation) }}" alt="Preview" style="width:18px; height:18px; object-fit:cover; border-radius:3px; border:1px solid #93c5fd; display:inline-block;">
                                            <span>Lihat Foto</span>
                                        </a>
                                    @elseif($training->is_pdf)
                                        <a href="{{ asset($training->documentation) }}" target="_blank" rel="noopener noreferrer" class="btn-doc-link" style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626;" title="Buka Dokumen PDF">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                                <path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.143.244-.427.721-.698 1.155-.788.82-.17 1.66-.18 2.508-.106.305-.53.642-1.127.935-1.743.238-.5.418-1.02.502-1.554.08-.508.016-.928-.276-1.147-.282-.211-.706-.15-1.002.138-.344.337-.502.83-.53 1.349-.03.54.12 1.07.382 1.55.074.137.165.267.267.388-.344.757-.745 1.488-1.189 2.19-.66-.027-1.328-.027-1.892.143z"/>
                                            </svg>
                                            <span>Lihat PDF</span>
                                        </a>
                                    @else
                                        <a href="{{ asset($training->documentation) }}" target="_blank" rel="noopener noreferrer" class="btn-doc-link" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155;" title="Buka Dokumen">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                            <span>Lihat File</span>
                                        </a>
                                    @endif
                                @else
                                    <span style="color:#94a3b8; font-weight:600;">-</span>
                                @endif
                            </td>
                            <td style="font-size:12px; color:#475569; line-height:1.45; word-break:break-word;">{{ $training->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted" style="padding: 24px;">Belum ada riwayat pelatihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="text-align:center; padding:12px 0 4px 0;">
            <span id="btn-show-more-trainings" style="font-size:11.5px; font-weight:700; color:#0056b3; cursor:pointer;">
                ︾ Tampilkan lebih banyak
            </span>
        </div>
    </div>

    {{-- C. SERTIFIKASI YANG DIMILIKI --}}
    <div style="margin-top: 24px;">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:15.1px; font-weight:700;">C. Sertifikasi yang Dimiliki</span>
            </div>
            <div class="card-actions">
                <button type="button" class="btn btn-primary" data-modal-target="modal-tambah-sertifikasi">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah
                </button>
                <button type="button" class="btn btn-outline" data-modal-target="modal-edit-sertifikasi">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </button>
                <button type="button" class="btn btn-outline" data-modal-target="modal-hapus-sertifikasi" style="color:#64748b;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Hapus
                </button>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px;">
            @forelse($employee->certifications as $cert)
                <div class="dashboard-card" style="margin-bottom:0; padding:16px;">
                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <div style="width:38px; height:38px; border-radius:50%; background:{{ $cert->is_active ? '#dcfce7' : '#f1f5f9' }}; color:{{ $cert->is_active ? '#16a34a' : '#64748b' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:700; font-size:13.1px; color:#0f172a; margin-bottom:4px; line-height:1.35;">
                                {{ $cert->name }}
                            </div>
                            <div style="font-size:11.6px; color:#64748b; margin-bottom:3px;">
                                Diperoleh: <strong>{{ $cert->obtained_date ?: '-' }}</strong>
                            </div>
                            <div style="font-size:11.6px; color:#64748b; margin-bottom:6px;">
                                Penyelenggara: {{ $cert->issuer ?: '-' }}
                            </div>
                            @if($cert->is_active)
                                <div style="font-size:12.1px; font-weight:700; color:#16a34a;">
                                    Aktif {{ $cert->valid_until ? '(Berlaku s.d. ' . $cert->valid_until . ')' : '' }}
                                </div>
                            @else
                                <div style="font-size:12.1px; font-weight:700; color:#94a3b8;">
                                    Tidak Aktif
                                </div>
                            @endif

                            @if($cert->documentation)
                                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px dashed #e2e8f0; display:flex; align-items:center; gap:6px;">
                                    @if($cert->is_image)
                                        <a href="{{ asset($cert->documentation) }}" target="_blank" rel="noopener noreferrer" class="btn-doc-link" style="background:#eff6ff; border:1px solid #bfdbfe; color:#1d4ed8; font-size:11.6px; padding:3px 8px;" title="Lihat Dokumen / Bukti Sertifikat">
                                            <img src="{{ asset($cert->documentation) }}" alt="Preview" style="width:16px; height:16px; object-fit:cover; border-radius:3px; border:1px solid #93c5fd; display:inline-block;">
                                            <span>Lihat Dokumen</span>
                                        </a>
                                    @elseif($cert->is_pdf)
                                        <a href="{{ asset($cert->documentation) }}" target="_blank" rel="noopener noreferrer" class="btn-doc-link" style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; font-size:11.6px; padding:3px 8px;" title="Buka Dokumen PDF Sertifikat">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                                <path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.143.244-.427.721-.698 1.155-.788.82-.17 1.66-.18 2.508-.106.305-.53.642-1.127.935-1.743.238-.5.418-1.02.502-1.554.08-.508.016-.928-.276-1.147-.282-.211-.706-.15-1.002.138-.344.337-.502.83-.53 1.349-.03.54.12 1.07.382 1.55.074.137.165.267.267.388-.344.757-.745 1.488-1.189 2.19-.66-.027-1.328-.027-1.892.143z"/>
                                            </svg>
                                            <span>Lihat PDF</span>
                                        </a>
                                    @else
                                        <a href="{{ asset($cert->documentation) }}" target="_blank" rel="noopener noreferrer" class="btn-doc-link" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; font-size:11.6px; padding:3px 8px;" title="Buka Berkas Sertifikat">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                            <span>Lihat Dokumen</span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="dashboard-card" style="grid-column: 1 / -1; text-align:center; padding: 28px; color: #64748b; font-size: 14.1px;">
                    Belum ada data sertifikasi yang ditambahkan. Klik tombol <strong>Tambah</strong> di atas untuk menambahkan sertifikasi baru.
                </div>
            @endforelse
        </div>
    </div>
</div>
