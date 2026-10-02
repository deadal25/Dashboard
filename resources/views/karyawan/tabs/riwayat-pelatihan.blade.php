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
                    <span class="stat-number">{{ $trainingSummary['total_pelatihan'] ?? 27 }}</span>
                    <span style="font-size:10.5px; color:#64748b;">Kelas / Program</span>
                </div>
            </div>

            {{-- Card 2: Total Jam --}}
            <div class="stat-card-single">
                <div class="stat-icon-wrapper" style="background:#f0fdf4; color:#16a34a;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-title">Total Jam Pelatihan</span>
                    <span class="stat-number">{{ $trainingSummary['total_jam'] ?? 186 }}</span>
                    <span style="font-size:10.5px; color:#64748b;">Jam</span>
                </div>
            </div>

            {{-- Card 3: Pelatihan Tahun Ini --}}
            <div class="stat-card-single">
                <div class="stat-icon-wrapper" style="background:#eff6ff; color:#2563eb;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-title">Pelatihan Tahun Ini (FY26)</span>
                    <span class="stat-number">{{ $trainingSummary['tahun_ini_count'] ?? 8 }}</span>
                    <span style="font-size:10.5px; color:#64748b;">Kelas / Program</span>
                </div>
            </div>

            {{-- Card 4: Jam Pelatihan Tahun Ini --}}
            <div class="stat-card-single">
                <div class="stat-icon-wrapper" style="background:#f0fdf4; color:#16a34a;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-title">Jam Pelatihan Tahun Ini</span>
                    <span class="stat-number">{{ $trainingSummary['tahun_ini_jam'] ?? 64 }}</span>
                    <span style="font-size:10.5px; color:#64748b;">Jam</span>
                </div>
            </div>

            {{-- Card 5: Sertifikasi --}}
            <div class="stat-card-single">
                <div class="stat-icon-wrapper" style="background:#faf5ff; color:#9333ea;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-title">Sertifikasi yang Dimiliki</span>
                    <span class="stat-number">{{ $trainingSummary['sertifikasi'] ?? 4 }}</span>
                    <span style="font-size:10.5px; color:#64748b;">Sertifikat Aktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- B. RIWAYAT PELATIHAN --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">B. Riwayat Pelatihan</span>
            </div>
        </div>

        {{-- Filters Bar --}}
        <div class="filter-bar">
            <div class="filter-group">
                <select class="filter-select" id="filter-year">
                    <option value="all">Semua Tahun</option>
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
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

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="text-center" style="width:4%;">No.</th>
                        <th class="text-center" style="width:12%;">Tanggal</th>
                        <th style="width:24%;">Nama Pelatihan</th>
                        <th class="text-center" style="width:10%;">Kategori</th>
                        <th class="text-center" style="width:12%;">Jenis Pelatihan</th>
                        <th style="width:18%;">Penyelenggara</th>
                        <th class="text-center" style="width:8%;">Durasi (Jam)</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody id="training-table-body">
                    @foreach($employee->trainingHistories as $training)
                        <tr data-category="{{ $training->category }}" data-type="{{ $training->training_type }}">
                            <td class="text-center">{{ $training->order_no }}</td>
                            <td class="text-center font-medium">{{ $training->training_date }}</td>
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
                            <td style="font-size:11.5px; color:#475569;">{{ $training->notes }}</td>
                        </tr>
                    @endforeach
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
    <div>
        <div style="font-size:13px; font-weight:800; color:#0b2545; text-transform:uppercase; margin-bottom:12px;">
            C. Sertifikasi yang Dimiliki
        </div>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
            @foreach($employee->certifications as $cert)
                <div class="dashboard-card" style="margin-bottom:0; padding:16px;">
                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <div style="width:36px; height:36px; border-radius:50%; background:#dcfce7; color:#16a34a; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:700; font-size:12px; color:#0f172a; margin-bottom:4px; line-height:1.3;">
                                {{ $cert->name }}
                            </div>
                            <div style="font-size:10.5px; color:#64748b; margin-bottom:2px;">
                                Diperoleh: <strong>{{ $cert->obtained_date }}</strong>
                            </div>
                            <div style="font-size:10.5px; color:#64748b; margin-bottom:8px;">
                                Penyelenggara: {{ $cert->issuer }}
                            </div>
                            <div style="font-size:11px; font-weight:700; color:#16a34a;">
                                Aktif (Berlaku s.d. {{ $cert->valid_until }})
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
