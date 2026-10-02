<div class="employee-header-card">
    {{-- Left: Avatar & Basic Information --}}
    <div class="emp-profile-left">
        <div class="emp-avatar-wrapper">
            <img src="{{ $employee->avatar ?: '/images/avatar-budi.png' }}" alt="{{ $employee->name }}" class="emp-avatar-img">
            @if(Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->isHrAdmin()))
                <button type="button" class="btn-change-avatar" onclick="openModal('modal-ubah-foto')" title="Ubah Foto Profil (Super Admin & HR Admin)">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                        <circle cx="12" cy="13" r="4"></circle>
                    </svg>
                    <span>Ubah Foto</span>
                </button>
            @endif
        </div>

        <div class="emp-main-info">
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <h1 class="emp-name">{{ $employee->name }}</h1>
                @if(Auth::check() && Auth::user()->isSuperAdmin())
                    <button 
                        type="button" 
                        class="btn btn-outline btn-edit-karyawan" 
                        style="padding:3px 8px; font-size:11px; color:#2563eb; border-color:#bfdbfe; background:#eff6ff; display:inline-flex; align-items:center; gap:4px;" 
                        title="Edit Data Karyawan (Super Admin)"
                        data-nik="{{ $employee->nik }}"
                        data-name="{{ $employee->name }}"
                        data-position="{{ $employee->position }}"
                        data-department="{{ $employee->department }}"
                        data-section="{{ $employee->section }}"
                        data-education="{{ $employee->education }}"
                        data-age="{{ $employee->age }}"
                        data-tenure="{{ $employee->tenure_years }}"
                        data-jobclass="{{ $employee->current_job_class }}"
                        data-grade="{{ $employee->current_grade }}"
                        data-grade-since="{{ $employee->grade_since }}"
                        data-position-since="{{ $employee->position_since }}"
                    >
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit Karyawan
                    </button>
                @endif
            </div>
            <div class="emp-title">{{ $employee->position }}</div>
            <div class="emp-section">{{ $employee->section }}</div>

            <div class="emp-specs-row">
                <div class="spec-item">
                    <span class="spec-label">NIK</span>
                    <span class="spec-val">{{ $employee->nik }}</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Usia</span>
                    <span class="spec-val">{{ $employee->age }} Tahun</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Masa Kerja</span>
                    <span class="spec-val">{{ $employee->tenure_years }} Tahun</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Pendidikan</span>
                    <span class="spec-val">{{ $employee->education }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Center: Job Class & Tenure Info --}}
    <div class="emp-jobclass-center">
        <div>
            <div class="jobclass-title">Job Class & Grade Saat Ini</div>
            <div class="jobclass-code">{{ $employee->current_job_class }} / {{ $employee->current_grade }}</div>
            <div class="jobclass-date"><small>Sejak</small> <strong>{{ $employee->grade_since ?? 'April 2026' }}</strong></div>
        </div>
        <div style="margin-top: 6px;">
            <div class="jobclass-title">Menjabat Sejak</div>
            <div class="jobclass-code" style="font-size: 13px; font-weight: 700;">{{ $employee->position_since ?? 'April 2023' }}</div>
        </div>
    </div>

    {{-- Right: Ringkasan Profil Card --}}
    <div class="emp-ringkasan-box">
        <div class="ringkasan-header">Ringkasan Profil</div>
        <div class="ringkasan-list">
            <div class="ringkasan-item">
                <span class="ringkasan-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                    Performance Terakhir (FY26)
                </span>
                <span class="badge-green">{{ $employee->performance_current }}</span>
            </div>

            <div class="ringkasan-item">
                <span class="ringkasan-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    POTASS Terakhir
                </span>
                <span class="badge-green">{{ $employee->potass_current }}</span>
            </div>

            <div class="ringkasan-item">
                <span class="ringkasan-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                    HAV 16 Box
                </span>
                <span class="ringkasan-val">{{ $employee->hav_box_current }}</span>
            </div>

            <div class="ringkasan-item">
                <span class="ringkasan-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    Talent Pool
                </span>
                <span class="ringkasan-val">{{ $employee->talent_pool_status }}</span>
            </div>

            <div class="ringkasan-item">
                <span class="ringkasan-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                    Flying Risk
                </span>
                <span class="{{ $employee->flying_risk === 'LOW' ? 'badge-green' : ($employee->flying_risk === 'HIGH' ? 'badge-red' : 'badge-orange') }}">
                    {{ $employee->flying_risk }}
                </span>
            </div>

            <div class="ringkasan-item">
                <span class="ringkasan-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>
                    Next Possible Position
                </span>
                <span class="ringkasan-val" style="color:#0056b3;">{{ $employee->next_possible_position }}</span>
            </div>

            <div class="ringkasan-item">
                <span class="ringkasan-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Proyeksi Puncak Karir
                </span>
                <span class="ringkasan-val">{{ $employee->career_projection }}</span>
            </div>
        </div>
    </div>
</div>
