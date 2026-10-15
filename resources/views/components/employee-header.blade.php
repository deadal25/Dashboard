<div class="employee-header-card">
    {{-- Left: Avatar & Basic Information --}}
    <div class="emp-profile-left">
        <div class="emp-avatar-wrapper">
            <img src="{{ asset($employee->avatar ?: 'images/avatar-budi.png') }}" alt="{{ $employee->name }}" class="emp-avatar-img">
            @if(Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->isHrAdmin()))
                <button type="button" class="btn-change-avatar" onclick="openModal('modal-ubah-foto')" title="Ubah Foto Profil (Super Admin & HR Admin)">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                        <circle cx="12" cy="13" r="4"></circle>
                    </svg>
                </button>
            @endif
        </div>

        <div class="emp-main-info">
            <div class="emp-name-row">
                <h1 class="emp-name">{{ $employee->name }}</h1>
                @if(Auth::check() && Auth::user()->isSuperAdmin())
                    <button 
                        type="button" 
                        class="btn-edit-karyawan-mini" 
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
                    </button>
                @endif
            </div>
            <div class="emp-title">{{ $employee->position }}</div>
            <div class="emp-section">{{ $employee->section }}</div>

            <div class="emp-specs-row">
                <div class="spec-item spec-item-first">
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
                <div class="spec-item spec-item-pendidikan">
                    <span class="spec-label">Pendidikan</span>
                    <span class="spec-val" title="{{ $employee->education }}">{{ $employee->education }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Center: Job Class & Tenure Info --}}
    <div class="emp-jobclass-center">
        <div>
            <div class="jobclass-title">Job Class & Grade Saat Ini</div>
            <div class="jobclass-code">{{ $employee->current_job_class }} / {{ $employee->current_grade }}</div>
            <div class="jobclass-since-block">
                <div class="jobclass-title">Sejak</div>
                <div class="jobclass-subval">{{ $employee->grade_since ?? 'April 2026' }}</div>
            </div>
        </div>
        <div class="jobclass-divider-line"></div>
        <div>
            <div class="jobclass-title">Menjabat Sejak</div>
            <div class="jobclass-code-sub">{{ $employee->position_since ?? 'April 2023' }}</div>
        </div>
    </div>

    {{-- Right: Ringkasan Profil Card --}}
    <div class="emp-ringkasan-box" id="emp-ringkasan-box">
        <div class="ringkasan-header" onclick="toggleRingkasanMobile()" title="Klik untuk membuka/menutup ringkasan profil">
            <div style="display:flex; align-items:center; gap:8px;">
                <span>Ringkasan Profil</span>
                <span class="ringkasan-mobile-chips">
                    <span class="badge-green" style="font-size:10px; padding:1px 5px;">{{ $employee->performance_current }}</span>
                    <span class="badge-green" style="font-size:10px; padding:1px 5px;">{{ $employee->potass_current }}</span>
                </span>
            </div>
            <div class="ringkasan-expand-btn">
                <svg class="ringkasan-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </div>
        <div class="ringkasan-content-collapsible">
            <div class="ringkasan-list">
                <div class="ringkasan-item">
                    <span class="ringkasan-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" style="flex-shrink:0;">
                            <circle cx="12" cy="12" r="11" fill="#0b2545"/>
                            <path d="M12 6.5v11M6.5 12h11" stroke="#ffffff" stroke-width="2.6" stroke-linecap="round"/>
                        </svg>
                        Performance Terakhir (FY26)
                    </span>
                    <span class="badge-green">{{ $employee->performance_current }}</span>
                </div>

                <div class="ringkasan-item">
                    <span class="ringkasan-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" style="flex-shrink:0;">
                            <circle cx="12" cy="12" r="11" fill="#0b2545"/>
                            <path d="M12 6.5v11M6.5 12h11" stroke="#ffffff" stroke-width="2.6" stroke-linecap="round"/>
                        </svg>
                        POTASS Terakhir
                    </span>
                    <span class="badge-green">{{ $employee->potass_current }}</span>
                </div>

                @php
                    $havHeader = $employee->getHavBoxDetails();
                @endphp
                <div class="ringkasan-item">
                    <span class="ringkasan-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#0b2545" style="flex-shrink:0;">
                            <rect x="2.5" y="2.5" width="8.5" height="8.5" rx="1.5" fill="#0b2545"/>
                            <rect x="13" y="2.5" width="8.5" height="8.5" rx="1.5" fill="#0b2545"/>
                            <rect x="2.5" y="13" width="8.5" height="8.5" rx="1.5" fill="#0b2545"/>
                            <rect x="13" y="13" width="8.5" height="8.5" rx="1.5" fill="#0b2545"/>
                        </svg>
                        HAV 16 Box
                    </span>
                    <span class="ringkasan-val">{{ $havHeader['box_label'] }}</span>
                </div>

                <div class="ringkasan-item">
                    <span class="ringkasan-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#0b2545" style="flex-shrink:0;">
                            <circle cx="12" cy="8" r="5.5" fill="#0b2545"/>
                            <path d="M8.5 12.5L6 22l6-3 6 3-2.5-9.5" fill="#0b2545"/>
                        </svg>
                        Talent Pool
                    </span>
                    <span class="ringkasan-val">{{ $havHeader['talent_pool'] }}</span>
                </div>

                <div class="ringkasan-item">
                    <span class="ringkasan-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#0b2545" style="flex-shrink:0;">
                            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="#0b2545"/>
                        </svg>
                        Flying Risk
                    </span>
                    <span class="{{ $employee->flying_risk_badge_class }}">
                        {{ $employee->flying_risk_formatted }}
                    </span>
                </div>

                <div class="ringkasan-item">
                    <span class="ringkasan-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0b2545" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                            <line x1="7" y1="17" x2="17" y2="7"/>
                            <polyline points="7 7 17 7 17 17"/>
                        </svg>
                        Next Possible Position
                    </span>
                    <span class="ringkasan-val" title="{{ $employee->next_possible_position }}">{{ $employee->next_possible_position }}</span>
                </div>

                <div class="ringkasan-item">
                    <span class="ringkasan-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" style="flex-shrink:0;">
                            <path d="M7 10V7a5 5 0 0 1 10 0v3" stroke="#0b2545" stroke-width="2.6" fill="none" stroke-linecap="round"/>
                            <rect x="4" y="10" width="16" height="12" rx="2.5" fill="#0b2545"/>
                        </svg>
                        Proyeksi Puncak Karir
                    </span>
                    <span class="ringkasan-val" title="{{ $employee->career_projection }}">{{ $employee->career_projection }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
