{{-- Tab 4: Individual Career Plan (ICP) --}}
<div class="tab-pane-content">
    {{-- Header Navigation Tabs & Actions (Matching Mockup Card Bar) --}}
    <div class="tab-header-flex-bar">
        <div class="subtab-nav-group" style="display:flex; align-items:center; gap:24px; flex-wrap:wrap;">
            <div data-subtab="d1" style="padding:4px 0 6px 0; font-weight:700; font-size:13px; color:#0056b3; border-bottom:3px solid #0056b3; cursor:pointer;">
                D1. ARAH KARIR
            </div>
            <div data-subtab="d2" style="padding:4px 0 6px 0; font-weight:600; font-size:13px; color:#64748b; cursor:pointer;">
                D2. RENCANA JOB CLASS & GRADE
            </div>
        </div>
        <div class="card-actions">
            <button type="button" class="btn btn-primary" data-modal-target="modal-tambah-icp">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-edit-icp">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-riwayat-perubahan">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                Riwayat Perubahan
            </button>
        </div>
    </div>

    {{-- D1: Top Row --}}
    <div data-subtab-content="d1" class="career-d1-grid">
        {{-- Informasi Arah Karir --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <span class="card-title" style="font-size:12.5px;">Informasi Arah Karir</span>
                </div>
                <div class="quick-action-btn-group">
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-edit-icp" data-preselect-section="edit-d1-plan">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit D1
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table-custom" style="font-size:11.5px; min-width:320px;">
                <tbody>
                    <tr>
                        <td style="font-weight:600; width:45%;">Area yang Diminati Karyawan</td>
                        <td>{{ $employee->careerPlan->interested_area ?? 'Engineering / Manufacturing' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;">Jalur Karir yang Direkomendasikan</td>
                        <td style="color:#0056b3; font-weight:700;">{{ $employee->careerPlan->recommended_career_path ?? 'Managerial' }}</td>
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
                    <tr>
                        <td style="font-weight:600;">Catatan</td>
                        <td style="color:#475569;">{{ $employee->careerPlan->notes ?? 'Karyawan berminat terus berkembang di bidang Engineering dengan fokus pada pengelolaan tim dan strategi operasi.' }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

        {{-- Keterangan Jalur Karir --}}
        <div class="dashboard-card" style="margin-bottom:0;">
            <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
                <div class="card-title-wrap">
                    <span class="card-title" style="font-size:12.5px;">Keterangan Jalur Karir</span>
                </div>
            </div>
            <div style="display:flex; flex-direction:column; gap:16px; font-size:11.5px;">
                <div style="display:flex; align-items:flex-start; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:6px; background:#eff6ff; color:#1d4ed8; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div>
                        <div style="font-weight:700; color:#0f172a; margin-bottom:2px;">Managerial</div>
                        <div style="color:#475569; line-height:1.4;">Berfokus pada pengelolaan tim, pengambilan keputusan, dan pencapaian target organisasi.</div>
                    </div>
                </div>

                <div style="display:flex; align-items:flex-start; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:6px; background:#f0fdf4; color:#16a34a; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                    </div>
                    <div>
                        <div style="font-weight:700; color:#0f172a; margin-bottom:2px;">Specialist</div>
                        <div style="color:#475569; line-height:1.4;">Berfokus pada pendalaman keahlian teknis/fungsional dan menjadi rujukan utama di bidangnya.</div>
                    </div>
                </div>

                <div style="display:flex; align-items:flex-start; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:6px; background:#faf5ff; color:#9333ea; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                    </div>
                    <div>
                        <div style="font-weight:700; color:#0f172a; margin-bottom:2px;">Both (Managerial & Specialist)</div>
                        <div style="color:#475569; line-height:1.4;">Kombinasi antara pengelolaan organisasi dan pendalaman keahlian spesifik.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- D2: Rencana Job Class & Grade --}}
    <div data-subtab-content="d2" class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">D2. Rencana Job Class & Grade</span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-icp">
                    + Tambah
                </button>
                <button type="button" class="btn-mini" data-modal-target="modal-edit-icp" data-preselect-section="edit-d2-jobclass">
                    Edit
                </button>
                <button type="button" class="btn-mini btn-mini-danger" data-modal-target="modal-hapus-icp">
                    Hapus
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table-custom" style="min-width:760px;">
            <thead>
                <tr>
                    <th class="text-center" style="width:4%;">No.</th>
                    <th class="text-center" style="width:10%;">Tahun Rencana</th>
                    <th class="text-center" style="width:10%;">Usia (Proyeksi)</th>
                    <th class="text-center" style="width:12%;">Job Class Rencana</th>
                    <th class="text-center" style="width:10%;">Grade Rencana</th>
                    <th class="text-center" style="width:16%;">Jenis Perubahan</th>
                    <th style="width:16%;">Target Jabatan</th>
                    <th style="width:18%;">Target Departemen / Fungsi</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employee->jobClassPlans as $plan)
                    <tr>
                        <td class="text-center">{{ $plan->order_no }}</td>
                        <td class="text-center" style="font-weight:700;">{{ $plan->plan_year }}</td>
                        <td class="text-center">{{ $plan->projected_age }}</td>
                        <td class="text-center font-semibold">{{ $plan->job_class }}</td>
                        <td class="text-center font-semibold">{{ $plan->grade }}</td>
                        <td class="text-center">
                            @if($plan->change_type === 'Saat Ini')
                                <span class="badge-table-green">Saat Ini</span>
                            @elseif($plan->change_type === 'Promosi')
                                <span class="badge-table-blue">Promosi</span>
                            @elseif($plan->change_type === 'Pensiun')
                                <span class="badge-table-gray">Pensiun</span>
                            @else
                                <span class="badge-table-green">Kenaikan Pangkat Reguler</span>
                            @endif
                        </td>
                        <td style="font-weight:600;">{{ $plan->target_position ?? '-' }}</td>
                        <td>{{ $plan->target_department ?? '-' }}</td>
                        <td style="font-size:11px; color:#475569;">{{ $plan->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div style="font-size:10.5px; color:#64748b; margin-top:10px;">
            <strong>Catatan:</strong> Usia dihitung otomatis berdasarkan tanggal lahir yang tersimpan pada data profil.
        </div>
    </div>
</div>
