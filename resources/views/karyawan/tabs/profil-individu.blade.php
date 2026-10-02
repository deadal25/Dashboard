{{-- Tab 1: Profil Individu (Overview Summary) --}}
<div class="tab-pane-content">

    {{-- Row 1: Performance, POTASS, HAV 16-Box, Flying Risk --}}
    <div style="display: grid; grid-template-columns: 1.1fr 1.2fr 1.1fr 1fr; gap: 16px; margin-bottom: 16px;">
        
        {{-- 1. Performance 3 Tahun Terakhir --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span class="card-title">Performance – 3 Tahun Terakhir</span>
                </div>
            </div>
            <table class="table-custom text-center">
                <thead>
                    <tr>
                        <th style="background:#f8fafc; font-size:11px;"></th>
                        <th style="font-size:11px;">FY24</th>
                        <th style="font-size:11px;">FY25</th>
                        <th style="font-size:11px;">FY26</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight:600; text-align:left; font-size:11.5px;">Performance Appraisal</td>
                        <td style="font-weight:700;">B+</td>
                        <td style="font-weight:700;">A</td>
                        <td><span class="badge-green" style="font-size:12px; padding:3px 10px;">A</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- 2. Potential Assessment - POTASS --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span class="card-title">Potential Assessment – POTASS</span>
                </div>
            </div>
            <table class="table-custom" style="font-size:11.5px;">
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-center" style="font-size:11px;">Sebelumnya<br><small style="color:#64748b; font-weight:400;">(Aug-24)</small></th>
                        <th class="text-center" style="font-size:11px;">Terakhir<br><small style="color:#64748b; font-weight:400;">(Aug-26)</small></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight:600;">Score POTASS</td>
                        <td class="text-center">94%</td>
                        <td class="text-center text-success text-bold">106%</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">Standar Jabatan</td>
                        <td class="text-center">Section Head</td>
                        <td class="text-center">Manager</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">Kategori</td>
                        <td class="text-center">Average</td>
                        <td class="text-center text-success text-bold">High</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- 3. HAV 16 Box & Talent Pool --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <span class="card-title">HAV 16 Box & Talent Pool</span>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:16px;">
                <div class="matrix-16-grid">
                    @for($i = 1; $i <= 16; $i++)
                        <div class="matrix-cell {{ $i == 15 ? 'active-highlight' : '' }}">
                            {{ $i }}
                        </div>
                    @endfor
                </div>
                <div style="font-size:11.5px; line-height:1.4;">
                    <div style="font-size:13px; font-weight:800; color:#0f172a;">Box 15</div>
                    <div style="color:#0056b3; font-weight:600; margin-bottom:6px;">High Performance / High Potential</div>
                    <div>Talent Pool: <span class="badge-green" style="font-size:10px; padding:1px 6px;">YA</span></div>
                </div>
            </div>
        </div>

        {{-- 4. Flying Risk --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                    <span class="card-title">Flying Risk</span>
                </div>
            </div>
            <div style="padding: 10px 0;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                    <span style="font-size:12px; font-weight:600; color:#475569;">Tingkat Flying Risk</span>
                    <span class="badge-orange" style="font-size:12px; padding:3px 12px;">{{ $employee->flying_risk }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:12px; font-weight:600; color:#475569;">Alasan Utama</span>
                    <span style="font-size:12.5px; font-weight:700; color:#0f172a;">{{ $employee->flying_risk_reason }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Row 2: Arah Karir, Rencana Job Class & Grade, Status Suksesi --}}
    <div style="display: grid; grid-template-columns: 1.1fr 1.4fr 1.3fr; gap: 16px; margin-bottom: 16px;">

        {{-- Arah Karir --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                    <span class="card-title">Arah Karir</span>
                </div>
            </div>
            <table class="table-custom" style="font-size:11.5px;">
                <tbody>
                    <tr>
                        <td style="font-weight:600; width:45%;">Area yang Diminati Karyawan</td>
                        <td>{{ $employee->careerPlan->interested_area ?? 'Engineering / Manufacturing' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">Jalur Karir yang Direkomendasikan</td>
                        <td>{{ $employee->careerPlan->recommended_career_path ?? 'Managerial' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">Kemungkinan Jabatan Berikutnya</td>
                        <td style="color:#0056b3; font-weight:700;">{{ $employee->careerPlan->next_possible_position ?? 'Engineering Manager' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">Kemungkinan Departemen / Fungsi Berikutnya</td>
                        <td>{{ $employee->careerPlan->next_possible_department ?? 'Manufacturing Engineering' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">Proyeksi Puncak Karir</td>
                        <td style="color:#0056b3; font-weight:700;">{{ $employee->careerPlan->career_projection ?? 'Engineering Division Head' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Rencana Job Class & Grade --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    <span class="card-title">Rencana Job Class & Grade</span>
                </div>
            </div>
            <table class="table-custom text-center" style="font-size:11.5px;">
                <thead>
                    <tr>
                        <th style="font-size:11px;">Tahun Rencana</th>
                        <th style="font-size:11px;">Usia</th>
                        <th style="font-size:11px;">Job Class</th>
                        <th style="font-size:11px;">Grade</th>
                        <th style="font-size:11px;">Jenis Perubahan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employee->jobClassPlans as $plan)
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
                                    <span style="font-size:11px; color:#2e7d32; font-weight:600;">Kenaikan Pangkat Reguler</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Status Suksesi --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span class="card-title">Status Suksesi</span>
                </div>
            </div>
            <table class="table-custom text-center" style="font-size:11.5px;">
                <thead>
                    <tr>
                        <th style="font-size:11px; text-align:left;">Kandidat Suksesor untuk Jabatan</th>
                        <th style="font-size:11px;">Departemen / Fungsi</th>
                        <th style="font-size:11px;">Kesiapan</th>
                        <th style="font-size:11px;">Peringkat Suksesor</th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
            <div style="font-size:10.5px; color:#64748b; margin-top:8px;">
                <strong>Kesiapan:</strong> Siap Sekarang / 1–2 Tahun / 3–5 Tahun / >5 Tahun / Belum Siap<br>
                <strong>Peringkat:</strong> 1 / 2 / 3
            </div>
        </div>

    </div>

    {{-- Row 3: Development Gap (Acuan: Engineering Manager) --}}
    <div class="dashboard-card">
        <div class="card-header-bar">
            <div class="card-title-wrap">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                <span class="card-title">Development Gap <span style="font-weight:400; text-transform:none; color:#64748b;">(Acuan: Engineering Manager)</span></span>
            </div>
        </div>
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width:20%;">Kompetensi</th>
                    <th class="text-center" style="width:10%;">Level Saat Ini</th>
                    <th class="text-center" style="width:15%;">Standar Jabatan Tujuan</th>
                    <th class="text-center" style="width:10%;">Gap</th>
                    <th>Peningkatan yang Diharapkan</th>
                </tr>
            </thead>
            <tbody>
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
            </tbody>
        </table>
    </div>

    {{-- Row 4: Individual Development Plan (IDP) --}}
    <div class="dashboard-card">
        <div class="card-header-bar">
            <div class="card-title-wrap">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                <span class="card-title">Individual Development Plan (IDP)</span>
            </div>
        </div>
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width:15%;">Kompetensi</th>
                    <th style="width:25%;">Aktivitas Pengembangan</th>
                    <th style="width:10%;">Metode</th>
                    <th style="width:12%;">Pendukung</th>
                    <th class="text-center" style="width:8%;">Target</th>
                    <th style="width:22%;">Indikator Keberhasilan</th>
                    <th class="text-center" style="width:8%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight:700;">People Development</td>
                    <td>Mengembangkan 1 Supervisor sebagai calon pengganti</td>
                    <td>Coaching / OJT</td>
                    <td>Engineering Manager</td>
                    <td class="text-center">Mar-27</td>
                    <td>Supervisor mampu menjalankan tanggung jawab Section Head yang disepakati secara mandiri</td>
                    <td class="text-center"><span class="badge-table-green">Berjalan</span></td>
                </tr>
                <tr>
                    <td style="font-weight:700;">Strategic Thinking</td>
                    <td>Memimpin proyek peningkatan produktivitas lintas fungsi</td>
                    <td>Project Assignment</td>
                    <td>Division Head</td>
                    <td class="text-center">Jun-27</td>
                    <td>Target proyek tercapai dan hasil dipresentasikan kepada Management</td>
                    <td class="text-center"><span class="badge-table-green">Berjalan</span></td>
                </tr>
                <tr>
                    <td style="font-weight:700;">Cost Management</td>
                    <td>Terlibat dalam penyusunan budget FY27 dan monthly cost review</td>
                    <td>OJT</td>
                    <td>Engineering / Finance</td>
                    <td class="text-center">Dec-26</td>
                    <td>Mampu menyiapkan dan menjelaskan performa biaya departemen secara mandiri</td>
                    <td class="text-center"><span class="badge-table-blue">Selesai</span></td>
                </tr>
            </tbody>
        </table>
        <div style="font-size:11px; color:#64748b; margin-top:8px;">
            <strong>Metode:</strong> OJT / Project Assignment / Job Rotation / Coaching / Mentoring / Training / Self Learning / Overseas Assignment<br>
            <strong>Status:</strong> Direncanakan / Berjalan / Selesai / Dibatalkan
        </div>
    </div>

    {{-- Row 5: Review Hasil Pengembangan --}}
    <div class="dashboard-card">
        <div class="card-header-bar">
            <div class="card-title-wrap">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                <span class="card-title">Review Hasil Pengembangan</span>
            </div>
        </div>
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width:12%;">Kompetensi</th>
                    <th style="width:20%;">Aktivitas Pengembangan</th>
                    <th class="text-center" style="width:9%;">Tanggal Review</th>
                    <th class="text-center" style="width:10%;">Pelaksanaan</th>
                    <th style="width:25%;">Hasil Review / Penyebab Tidak Terlaksana</th>
                    <th class="text-center" style="width:9%;">Level Setelahnya</th>
                    <th style="width:15%;">Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight:700;">Cost Management</td>
                    <td>Terlibat dalam budgeting & monthly cost review</td>
                    <td class="text-center">Jan-27</td>
                    <td class="text-center text-success text-bold">Terlaksana</td>
                    <td>Sudah mampu menyiapkan monthly cost review dan menjelaskan major variance secara mandiri</td>
                    <td class="text-center text-bold">3</td>
                    <td><span style="color:#475569; font-weight:600;">Tidak Perlu Tindak Lanjut</span></td>
                </tr>
                <tr>
                    <td style="font-weight:700;">People Development</td>
                    <td>Mengembangkan 1 Supervisor</td>
                    <td class="text-center">Apr-27</td>
                    <td class="text-center text-success text-bold">Terlaksana</td>
                    <td>Supervisor sudah mampu menangani operasional harian secara mandiri, tetapi masih membutuhkan support untuk isu manpower dan performance</td>
                    <td class="text-center text-bold">3</td>
                    <td><span style="color:#d97706; font-weight:700;">Lanjutkan</span></td>
                </tr>
                <tr>
                    <td style="font-weight:700;">Strategic Thinking</td>
                    <td>Memimpin proyek peningkatan produktivitas lintas fungsi</td>
                    <td class="text-center">Jul-27</td>
                    <td class="text-center text-danger text-bold">Tidak Terlaksana</td>
                    <td>Proyek ditunda karena perubahan prioritas produksi</td>
                    <td class="text-center text-muted">Belum Dinilai</td>
                    <td><span style="color:#0056b3; font-weight:700;">Jadwalkan Ulang</span></td>
                </tr>
            </tbody>
        </table>
        <div style="font-size:11px; color:#64748b; margin-top:8px;">
            <strong>Pelaksanaan:</strong> Terlaksana / Tidak Terlaksana<br>
            <strong>Tindak Lanjut:</strong> Tidak Perlu Tindak Lanjut / Lanjutkan / Jadwalkan Ulang / Ubah Aktivitas Pengembangan
        </div>
    </div>

    {{-- Bottom Disclaimer --}}
    <div style="font-size: 11px; color: #64748b; padding: 4px 0 10px 0;">
        Catatan: Proyeksi karir dan rencana pengembangan dapat berubah sesuai hasil talent review berikutnya.
    </div>

</div>
