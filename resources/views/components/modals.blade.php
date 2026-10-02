{{-- Modal Component Definitions --}}

{{-- 1. Modal Tambah Riwayat Karir --}}
<div id="modal-tambah-karir" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Riwayat Karir
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form action="{{ route('karyawan.career-history.store', ['nik' => $employee->nik]) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Efektif</label>
                        <input type="text" name="effective_date" class="form-control" placeholder="Contoh: Apr-26" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Class & Grade</label>
                        <input type="text" name="job_class_grade" class="form-control" placeholder="Contoh: JC4 / G4-2" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Departemen / Seksi</label>
                    <input type="text" name="department_section" class="form-control" placeholder="Contoh: Manufacturing Engineering / Process Eng." required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="position" class="form-control" placeholder="Contoh: Section Head" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Perubahan</label>
                        <select name="change_type" class="form-control" required>
                            <option value="Kenaikan Pangkat Reguler">Kenaikan Pangkat Reguler</option>
                            <option value="Promosi">Promosi</option>
                            <option value="Rotasi">Rotasi</option>
                            <option value="Mutasi">Mutasi</option>
                            <option value="Demosi">Demosi</option>
                            <option value="Penempatan Awal">Penempatan Awal</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="notes" class="form-control" placeholder="Contoh: Supervisor → Section Head atau -">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

{{-- 2. Modal Edit Riwayat Karir --}}
<div id="modal-edit-karir" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Riwayat Karir
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form id="form-edit-karir" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Pilih Data yang Ingin Diedit</label>
                    <select id="edit-select-karir" class="form-control">
                        @foreach($employee->careerHistories as $ch)
                            <option value="{{ $ch->id }}" 
                                    data-date="{{ $ch->effective_date }}"
                                    data-dept="{{ $ch->department_section }}"
                                    data-pos="{{ $ch->position }}"
                                    data-grade="{{ $ch->job_class_grade }}"
                                    data-type="{{ $ch->change_type }}"
                                    data-notes="{{ $ch->notes }}">
                                {{ $ch->effective_date }} - {{ $ch->position }} ({{ $ch->job_class_grade }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Efektif</label>
                        <input type="text" id="edit-date" name="effective_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Class & Grade</label>
                        <input type="text" id="edit-grade" name="job_class_grade" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Departemen / Seksi</label>
                    <input type="text" id="edit-dept" name="department_section" class="form-control" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jabatan</label>
                        <input type="text" id="edit-pos" name="position" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Perubahan</label>
                        <select id="edit-type" name="change_type" class="form-control" required>
                            <option value="Kenaikan Pangkat Reguler">Kenaikan Pangkat Reguler</option>
                            <option value="Promosi">Promosi</option>
                            <option value="Rotasi">Rotasi</option>
                            <option value="Mutasi">Mutasi</option>
                            <option value="Demosi">Demosi</option>
                            <option value="Penempatan Awal">Penempatan Awal</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <input type="text" id="edit-notes" name="notes" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>

{{-- 3. Modal Hapus Riwayat Karir --}}
<div id="modal-hapus-karir" class="modal-backdrop">
    <div class="modal-card" style="max-width:460px;">
        <div class="modal-header" style="background:#b91c1c;">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Riwayat Karir
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form id="form-hapus-karir" action="" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Pilih Data yang Akan Dihapus</label>
                    <select id="delete-select-karir" class="form-control">
                        @foreach($employee->careerHistories as $ch)
                            <option value="{{ $ch->id }}">
                                {{ $ch->effective_date }} - {{ $ch->position }} ({{ $ch->change_type }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size:12px; color:#b91c1c; margin-top:8px;">
                    Perhatian: Data yang dihapus tidak dapat dikembalikan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Hapus Permanen</button>
            </div>
        </form>
    </div>
</div>

{{-- =========================================================================
     MODAL GRUP 1: TALENT SNAPSHOT (6 FORMS DENGAN PILIHAN BAGIAN)
   ========================================================================= --}}

{{-- 1.1 Modal TAMBAH Talent Snapshot --}}
<div id="modal-tambah-talent-snapshot" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Data Talent Snapshot
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body">
            {{-- Pilihan Bagian yang Ingin Ditambah --}}
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian yang Ingin Ditambah:
                </label>
                <select id="select-tambah-talent-section" class="form-control section-switcher" data-container="container-tambah-talent">
                    <option value="c4-strength">C4. Kekuatan Utama (Key Strength)</option>
                    <option value="c6-potass">C6. Riwayat POTASS Assessment</option>
                </select>
            </div>

            {{-- Form C4: Tambah Kekuatan Utama --}}
            <form id="form-tambah-c4" class="dynamic-subform" data-section="c4-strength" action="{{ route('karyawan.key-strength.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Kekuatan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="strength" class="form-control" placeholder="Contoh: Strategic Thinking / Technical Problem Solving" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Singkat <span style="color:#ef4444;">*</span></label>
                    <textarea name="short_description" class="form-control" rows="2" placeholder="Jelaskan bukti atau manifestasi kekuatan ini..." required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Sumber Validasi <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="source" class="form-control" placeholder="Contoh: PA, POTASS, Rekomendasi Atasan" required>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kekuatan Utama</button>
                </div>
            </form>

            {{-- Form C6: Tambah Riwayat POTASS --}}
            <form id="form-tambah-c6" class="dynamic-subform" data-section="c6-potass" style="display:none;" action="{{ route('karyawan.talent-assessment.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal / Periode Asesmen <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="assessment_date" class="form-control" placeholder="Contoh: Aug-26" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Standar Jabatan <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="position_standard" class="form-control" placeholder="Contoh: Manager" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Score POTASS <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="potass_score" class="form-control" placeholder="Contoh: 106%" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                        <select name="category" class="form-control" required>
                            <option value="High">High</option>
                            <option value="Average">Average</option>
                            <option value="Below Average">Below Average</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Assessor / Penilai <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="assessor" class="form-control" value="HR Development" required>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Riwayat POTASS</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 1.2 Modal EDIT Talent Snapshot (Mendukung ke-6 Form C1, C2, C3, C4, C5, C6) --}}
<div id="modal-edit-talent-snapshot" class="modal-backdrop">
    <div class="modal-card modal-lg">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Data Talent Snapshot
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
            {{-- Dropdown Pilih Bagian yang Ingin Diedit --}}
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian / Form yang Ingin Diedit:
                </label>
                <select id="select-edit-talent-section" class="form-control section-switcher" data-container="container-edit-talent">
                    <option value="edit-c1-perf">C1. Performance – 3 Tahun Terakhir</option>
                    <option value="edit-c2-potass">C2. Potential Assessment – POTASS</option>
                    <option value="edit-c3-hav">C3. HAV 16 Box & Talent Pool</option>
                    <option value="edit-c4-strength">C4. Kekuatan Utama (Key Strengths)</option>
                    <option value="edit-c5-risk">C5. Flying Risk Assessment</option>
                    <option value="edit-c6-history">C6. Riwayat POTASS Assessment</option>
                </select>
            </div>

            {{-- Form C1: Performance 3 Tahun --}}
            <form id="form-edit-c1" class="dynamic-subform" data-section="edit-c1-perf" action="{{ route('karyawan.talent-snapshot.performance.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir C1: Performance Appraisal 3 Tahun Terakhir</div>
                <div class="form-row" style="grid-template-columns: repeat(4, 1fr);">
                    <div class="form-group">
                        <label class="form-label">Tahun FY24</label>
                        <input type="text" name="performance_fy24" class="form-control" value="{{ $employee->performance_fy24 ?: 'B+' }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun FY25</label>
                        <input type="text" name="performance_fy25" class="form-control" value="{{ $employee->performance_fy25 ?: 'A' }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun FY26</label>
                        <input type="text" name="performance_fy26" class="form-control" value="{{ $employee->performance_fy26 ?: 'A' }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rating Terkini</label>
                        <input type="text" name="performance_current" class="form-control" value="{{ $employee->performance_current ?: 'A' }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan Hasil Penilaian Kinerja</label>
                    <input type="text" name="performance_notes" class="form-control" value="{{ $employee->performance_notes ?: 'Data berasal dari Hasil Penilaian Kinerja tahunan.' }}">
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Data Performance (C1)</button>
                </div>
            </form>

            {{-- Form C2: Potential Assessment POTASS --}}
            <form id="form-edit-c2" class="dynamic-subform" data-section="edit-c2-potass" style="display:none;" action="{{ route('karyawan.talent-snapshot.potass.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir C2: Perbandingan Potential Assessment (POTASS)</div>
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:12px;">
                    {{-- Kolom Sebelumnya --}}
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:12px;">
                        <div style="font-weight:700; font-size:12px; color:#475569; margin-bottom:8px;">ASESMEN SEBELUMNYA</div>
                        <div class="form-group">
                            <label class="form-label">Periode</label>
                            <input type="text" name="potass_period_prev" class="form-control" value="{{ $employee->potass_period_prev ?: 'Aug-24' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Score POTASS</label>
                            <input type="text" name="potass_score_prev" class="form-control" value="{{ $employee->potass_score_prev ?: '94%' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Standar Jabatan</label>
                            <input type="text" name="potass_position_prev" class="form-control" value="{{ $employee->potass_position_prev ?: 'Section Head' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="potass_category_prev" class="form-control" value="{{ $employee->potass_category_prev ?: 'Average' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Assessor</label>
                            <input type="text" name="potass_assessor_prev" class="form-control" value="{{ $employee->potass_assessor_prev ?: 'HR Development' }}" required>
                        </div>
                    </div>

                    {{-- Kolom Terakhir --}}
                    <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:6px; padding:12px;">
                        <div style="font-weight:700; font-size:12px; color:#1d4ed8; margin-bottom:8px;">ASESMEN TERAKHIR</div>
                        <div class="form-group">
                            <label class="form-label">Periode</label>
                            <input type="text" name="potass_period_last" class="form-control" value="{{ $employee->potass_period_last ?: 'Aug-26' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Score POTASS</label>
                            <input type="text" name="potass_score_last" class="form-control" value="{{ $employee->potass_score_last ?: '106%' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Standar Jabatan</label>
                            <input type="text" name="potass_position_last" class="form-control" value="{{ $employee->potass_position_last ?: 'Manager' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="potass_category_last" class="form-control" value="{{ $employee->potass_category_last ?: 'High' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Assessor</label>
                            <input type="text" name="potass_assessor_last" class="form-control" value="{{ $employee->potass_assessor_last ?: 'HR Development' }}" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Data POTASS (C2)</button>
                </div>
            </form>

            {{-- Form C3: HAV 16 Box & Talent Pool --}}
            <form id="form-edit-c3" class="dynamic-subform" data-section="edit-c3-hav" style="display:none;" action="{{ route('karyawan.talent-snapshot.hav-box.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir C3: HAV 16 Box & Status Talent Pool</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Posisi HAV 16 Box <span style="color:#ef4444;">*</span></label>
                        <select name="hav_box_current" class="form-control" required>
                            @for($b = 1; $b <= 16; $b++)
                                <option value="Box {{ $b }}" {{ ($employee->hav_box_current ?: 'Box 15') === 'Box ' . $b ? 'selected' : '' }}>Box {{ $b }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Talent Pool <span style="color:#ef4444;">*</span></label>
                        <select name="talent_pool_status" class="form-control" required>
                            <option value="YA" {{ ($employee->talent_pool_status ?: 'YA') === 'YA' ? 'selected' : '' }}>YA (Masuk Talent Pool)</option>
                            <option value="TIDAK" {{ $employee->talent_pool_status === 'TIDAK' ? 'selected' : '' }}>TIDAK</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Label / Kategori Box</label>
                    <input type="text" name="hav_box_category" class="form-control" value="{{ $employee->hav_box_category ?: 'High Performance / High Potential' }}" required>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Data HAV Box (C3)</button>
                </div>
            </form>

            {{-- Form C4: Edit Kekuatan Utama (Pilih item yang diedit) --}}
            <form id="form-edit-c4" class="dynamic-subform" data-section="edit-c4-strength" style="display:none;" action="" method="POST">
                @csrf
                @method('PUT')
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir C4: Edit Data Kekuatan Utama</div>
                <div class="form-group">
                    <label class="form-label">Pilih Kekuatan yang Ingin Diedit:</label>
                    <select id="select-edit-c4-item" class="form-control">
                        @foreach($employee->keyStrengths as $st)
                            <option value="{{ $st->id }}" 
                                    data-strength="{{ $st->strength }}" 
                                    data-desc="{{ $st->short_description }}" 
                                    data-source="{{ $st->source }}">
                                #{{ $st->order_no }} - {{ $st->strength }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kekuatan <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="edit-c4-strength" name="strength" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Singkat <span style="color:#ef4444;">*</span></label>
                    <textarea id="edit-c4-desc" name="short_description" class="form-control" rows="2" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Sumber Validasi <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="edit-c4-source" name="source" class="form-control" required>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Kekuatan Utama (C4)</button>
                </div>
            </form>

            {{-- Form C5: Flying Risk Assessment --}}
            <form id="form-edit-c5" class="dynamic-subform" data-section="edit-c5-risk" style="display:none;" action="{{ route('karyawan.talent-snapshot.flying-risk.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir C5: Flying Risk Assessment</div>
                <div class="form-group">
                    <label class="form-label">Tingkat Flying Risk <span style="color:#ef4444;">*</span></label>
                    <select name="flying_risk" class="form-control" required>
                        <option value="LOW" {{ $employee->flying_risk === 'LOW' ? 'selected' : '' }}>LOW (Risiko Rendah)</option>
                        <option value="MEDIUM" {{ ($employee->flying_risk ?: 'MEDIUM') === 'MEDIUM' ? 'selected' : '' }}>MEDIUM (Risiko Sedang)</option>
                        <option value="HIGH" {{ $employee->flying_risk === 'HIGH' ? 'selected' : '' }}>HIGH (Risiko Tinggi)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Alasan Utama Flying Risk <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="flying_risk_reason" class="form-control" value="{{ $employee->flying_risk_reason ?: 'Career Progression' }}" required>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Flying Risk (C5)</button>
                </div>
            </form>

            {{-- Form C6: Edit Riwayat POTASS --}}
            <form id="form-edit-c6" class="dynamic-subform" data-section="edit-c6-history" style="display:none;" action="" method="POST">
                @csrf
                @method('PUT')
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir C6: Edit Riwayat POTASS Assessment</div>
                <div class="form-group">
                    <label class="form-label">Pilih Baris Riwayat yang Ingin Diedit:</label>
                    <select id="select-edit-c6-item" class="form-control">
                        @foreach($employee->talentAssessments as $ta)
                            <option value="{{ $ta->id }}" 
                                    data-date="{{ $ta->assessment_date }}" 
                                    data-pos="{{ $ta->position_standard }}" 
                                    data-score="{{ $ta->potass_score }}" 
                                    data-cat="{{ $ta->category }}" 
                                    data-assessor="{{ $ta->assessor }}">
                                {{ $ta->assessment_date }} - {{ $ta->position_standard }} ({{ $ta->potass_score }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Asesmen <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-c6-date" name="assessment_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Standar Jabatan <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-c6-pos" name="position_standard" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Score POTASS <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-c6-score" name="potass_score" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                        <select id="edit-c6-cat" name="category" class="form-control" required>
                            <option value="High">High</option>
                            <option value="Average">Average</option>
                            <option value="Below Average">Below Average</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Assessor <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="edit-c6-assessor" name="assessor" class="form-control" required>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Riwayat POTASS (C6)</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 1.3 Modal HAPUS Talent Snapshot (Pilihan C4 atau C6) --}}
<div id="modal-hapus-talent-snapshot" class="modal-backdrop">
    <div class="modal-card" style="max-width:480px;">
        <div class="modal-header" style="background:#b91c1c;">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Data Talent Snapshot
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body">
            {{-- Pilihan Bagian yang Ingin Dihapus --}}
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian yang Ingin Dihapus:
                </label>
                <select id="select-hapus-talent-section" class="form-control section-switcher" data-container="container-hapus-talent">
                    <option value="del-c4-strength">C4. Kekuatan Utama</option>
                    <option value="del-c6-history">C6. Riwayat POTASS Assessment</option>
                </select>
            </div>

            {{-- Form Hapus C4 --}}
            <form id="form-hapus-c4" class="dynamic-subform" data-section="del-c4-strength" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Pilih Data Kekuatan Utama yang Akan Dihapus:</label>
                    <select id="select-del-c4-item" class="form-control">
                        @foreach($employee->keyStrengths as $st)
                            <option value="{{ $st->id }}">
                                #{{ $st->order_no }} - {{ $st->strength }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size:12px; color:#b91c1c; margin-top:10px;">
                    Perhatian: Data kekuatan utama yang dihapus tidak dapat dipulihkan.
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Hapus Kekuatan Ini</button>
                </div>
            </form>

            {{-- Form Hapus C6 --}}
            <form id="form-hapus-c6" class="dynamic-subform" data-section="del-c6-history" style="display:none;" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Pilih Riwayat POTASS yang Akan Dihapus:</label>
                    <select id="select-del-c6-item" class="form-control">
                        @foreach($employee->talentAssessments as $ta)
                            <option value="{{ $ta->id }}">
                                {{ $ta->assessment_date }} - {{ $ta->position_standard }} ({{ $ta->potass_score }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size:12px; color:#b91c1c; margin-top:10px;">
                    Perhatian: Data asesmen yang dihapus tidak dapat dipulihkan.
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Hapus Asesmen Ini</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 1.4 Modal Riwayat Assessment POTASS (Viewer) --}}
<div id="modal-riwayat-assessment" class="modal-backdrop">
    <div class="modal-card modal-lg">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                Histori Riwayat Assessment POTASS – {{ $employee->name }}
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <div class="modal-body">
            <table class="table-custom text-center">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Standar Jabatan</th>
                        <th>Score POTASS</th>
                        <th>Kategori</th>
                        <th>Assessor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employee->talentAssessments as $ta)
                        <tr>
                            <td style="font-weight:600;">{{ $ta->assessment_date }}</td>
                            <td>{{ $ta->position_standard }}</td>
                            <td class="{{ (int)$ta->potass_score >= 100 ? 'text-success text-bold' : '' }}">{{ $ta->potass_score }}</td>
                            <td>
                                @if($ta->category === 'High')
                                    <span class="badge-table-green">High</span>
                                @elseif($ta->category === 'Below Average')
                                    <span class="badge-table-red">Below Average</span>
                                @else
                                    <span class="badge-table-blue">Average</span>
                                @endif
                            </td>
                            <td>{{ $ta->assessor }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-modal-close>Tutup</button>
        </div>
    </div>
</div>

{{-- =========================================================================
     MODAL GRUP 2: INDIVIDUAL CAREER PLAN (ICP) (D1 & D2)
   ========================================================================= --}}

{{-- 2.1 Modal Tambah ICP (D1 Arah Karir atau D2 Job Class Plan) --}}
<div id="modal-tambah-icp" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Data Individual Career Plan (ICP)
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body">
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian ICP yang Ingin Ditambah / Diatur:
                </label>
                <select id="select-tambah-icp-section" class="form-control section-switcher" data-container="container-tambah-icp">
                    <option value="d2-plan">D2. Rencana Job Class & Grade (Proyeksi)</option>
                    <option value="d1-career">D1. Arah Karir & Posisi Target</option>
                </select>
            </div>

            {{-- Form D2: Tambah Rencana Job Class --}}
            <form id="form-tambah-d2" class="dynamic-subform" data-section="d2-plan" action="{{ route('karyawan.job-class-plan.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tahun Rencana <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="plan_year" class="form-control" placeholder="Contoh: 2029" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Usia Proyeksi <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="projected_age" class="form-control" placeholder="Contoh: 44" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Job Class <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="job_class" class="form-control" placeholder="Contoh: JC4 atau JC5" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grade <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="grade" class="form-control" placeholder="Contoh: G4-3 atau G5-1" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Perubahan <span style="color:#ef4444;">*</span></label>
                    <select name="change_type" class="form-control" required>
                        <option value="Kenaikan Pangkat Reguler">Kenaikan Pangkat Reguler</option>
                        <option value="Promosi">Promosi</option>
                        <option value="Saat Ini">Saat Ini</option>
                        <option value="Rotasi">Rotasi</option>
                        <option value="Pensiun">Pensiun</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Target Jabatan</label>
                        <input type="text" name="target_position" class="form-control" placeholder="Contoh: Engineering Manager">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Target Departemen / Fungsi</label>
                        <input type="text" name="target_department" class="form-control" placeholder="Contoh: Manufacturing Engineering">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="notes" class="form-control" placeholder="Keterangan tambahan rencana karir">
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Rencana Job Class (D2)</button>
                </div>
            </form>

            {{-- Form D1: Tambah / Atur Arah Karir --}}
            <form id="form-tambah-d1" class="dynamic-subform" data-section="d1-career" style="display:none;" action="{{ route('karyawan.career-path.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Pilihan Jalur Karir <span style="color:#ef4444;">*</span></label>
                    <select name="career_path_choice" class="form-control" required>
                        <option value="Managerial" {{ ($employee->career_path_choice ?: 'Managerial') === 'Managerial' ? 'selected' : '' }}>Managerial (Jalur Struktural/Manajemen)</option>
                        <option value="Specialist" {{ $employee->career_path_choice === 'Specialist' ? 'selected' : '' }}>Specialist (Jalur Fungsional/Ahli)</option>
                        <option value="Cross Function" {{ $employee->career_path_choice === 'Cross Function' ? 'selected' : '' }}>Cross Function (Lintas Fungsi)</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Posisi Target Akhir <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="target_position_title" class="form-control" value="{{ $employee->target_position_title ?: 'Engineering Manager' }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Class & Grade Target <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="target_job_class_grade" class="form-control" value="{{ $employee->target_job_class_grade ?: 'JC5 / G5-1' }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jangka Waktu Pencapaian <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="target_timeframe" class="form-control" value="{{ $employee->target_timeframe ?: '3–5 Tahun' }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tingkat Kesiapan <span style="color:#ef4444;">*</span></label>
                        <select name="target_readiness" class="form-control" required>
                            <option value="Siap Sekarang">Siap Sekarang</option>
                            <option value="1–2 Tahun">1–2 Tahun</option>
                            <option value="3–5 Tahun" selected>3–5 Tahun</option>
                            <option value=">5 Tahun">>5 Tahun</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Arah Karir (D1)</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2.2 Modal Edit ICP (Pilihan D1 atau D2) --}}
<div id="modal-edit-icp" class="modal-backdrop">
    <div class="modal-card modal-lg">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Individual Career Plan (ICP)
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
            {{-- Pilihan Bagian yang Ingin Diedit --}}
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian yang Ingin Diedit:
                </label>
                <select id="select-edit-icp-section" class="form-control section-switcher" data-container="container-edit-icp">
                    <option value="edit-d1-plan">D1. Informasi Arah Karir</option>
                    <option value="edit-d2-jobclass">D2. Rencana Job Class & Grade</option>
                </select>
            </div>

            {{-- Form D1: Informasi Arah Karir --}}
            <form id="form-edit-d1" class="dynamic-subform" data-section="edit-d1-plan" action="{{ route('karyawan.career-plan.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir D1: Informasi Arah Karir</div>
                <div class="form-group">
                    <label class="form-label">Area yang Diminati Karyawan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="interested_area" class="form-control" value="{{ $employee->careerPlan->interested_area ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jalur Karir yang Direkomendasikan <span style="color:#ef4444;">*</span></label>
                    <select name="recommended_career_path" class="form-control" required>
                        <option value="Managerial" {{ ($employee->careerPlan->recommended_career_path ?? '') === 'Managerial' ? 'selected' : '' }}>Managerial</option>
                        <option value="Specialist" {{ ($employee->careerPlan->recommended_career_path ?? '') === 'Specialist' ? 'selected' : '' }}>Specialist</option>
                        <option value="Both (Managerial & Specialist)" {{ ($employee->careerPlan->recommended_career_path ?? '') === 'Both (Managerial & Specialist)' ? 'selected' : '' }}>Both (Managerial & Specialist)</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kemungkinan Jabatan Berikutnya <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="next_possible_position" class="form-control" value="{{ $employee->careerPlan->next_possible_position ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Departemen / Fungsi Berikutnya <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="next_possible_department" class="form-control" value="{{ $employee->careerPlan->next_possible_department ?? '' }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Proyeksi Puncak Karir <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="career_projection" class="form-control" value="{{ $employee->careerPlan->career_projection ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan Arah Karir</label>
                    <textarea name="notes" class="form-control" rows="2">{{ $employee->careerPlan->notes ?? '' }}</textarea>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Arah Karir (D1)</button>
                </div>
            </form>

            {{-- Form D2: Edit Rencana Job Class & Grade --}}
            <form id="form-edit-d2" class="dynamic-subform" data-section="edit-d2-jobclass" style="display:none;" action="" method="POST">
                @csrf
                @method('PUT')
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir D2: Edit Rencana Job Class & Grade</div>
                <div class="form-group">
                    <label class="form-label">Pilih Data Rencana yang Ingin Diedit:</label>
                    <select id="select-edit-d2-item" class="form-control">
                        @foreach($employee->jobClassPlans as $jcp)
                            <option value="{{ $jcp->id }}"
                                    data-year="{{ $jcp->plan_year }}"
                                    data-age="{{ $jcp->projected_age }}"
                                    data-class="{{ $jcp->job_class }}"
                                    data-grade="{{ $jcp->grade }}"
                                    data-type="{{ $jcp->change_type }}"
                                    data-targetpos="{{ $jcp->target_position }}"
                                    data-targetdept="{{ $jcp->target_department }}"
                                    data-notes="{{ $jcp->notes }}">
                                #{{ $jcp->order_no }} - {{ $jcp->plan_year }} ({{ $jcp->job_class }}/{{ $jcp->grade }} - {{ $jcp->change_type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tahun Rencana <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-d2-year" name="plan_year" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Usia Proyeksi <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="edit-d2-age" name="projected_age" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Job Class <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-d2-class" name="job_class" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grade <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-d2-grade" name="grade" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Perubahan <span style="color:#ef4444;">*</span></label>
                    <select id="edit-d2-type" name="change_type" class="form-control" required>
                        <option value="Kenaikan Pangkat Reguler">Kenaikan Pangkat Reguler</option>
                        <option value="Promosi">Promosi</option>
                        <option value="Saat Ini">Saat Ini</option>
                        <option value="Rotasi">Rotasi</option>
                        <option value="Pensiun">Pensiun</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Target Jabatan</label>
                        <input type="text" id="edit-d2-targetpos" name="target_position" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Target Departemen</label>
                        <input type="text" id="edit-d2-targetdept" name="target_department" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <input type="text" id="edit-d2-notes" name="notes" class="form-control">
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Rencana (D2)</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2.3 Modal Hapus ICP (D1 Arah Karir atau D2 Job Class Plan) --}}
<div id="modal-hapus-icp" class="modal-backdrop">
    <div class="modal-card" style="max-width:500px;">
        <div class="modal-header" style="background:#b91c1c;">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Data Individual Career Plan (ICP)
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body">
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian ICP yang Ingin Dihapus / Direset:
                </label>
                <select id="select-hapus-icp-section" class="form-control section-switcher" data-container="container-hapus-icp">
                    <option value="del-d2-plan">D2. Rencana Job Class & Grade (Hapus Baris)</option>
                    <option value="del-d1-target">D1. Arah Karir & Posisi Target (Reset Nilai)</option>
                </select>
            </div>

            {{-- Form Hapus D2 --}}
            <form id="form-hapus-d2" class="dynamic-subform" data-section="del-d2-plan" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Pilih Data Rencana yang Akan Dihapus:</label>
                    <select id="select-del-d2-item" class="form-control">
                        @foreach($employee->jobClassPlans as $jcp)
                            <option value="{{ $jcp->id }}">
                                #{{ $jcp->order_no }} - {{ $jcp->plan_year }} ({{ $jcp->job_class }}/{{ $jcp->grade }} - {{ $jcp->change_type }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size:12px; color:#b91c1c; margin-top:10px;">
                    Perhatian: Data rencana job class yang dihapus tidak dapat dipulihkan.
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Hapus Rencana Ini</button>
                </div>
            </form>

            {{-- Form Reset D1 --}}
            <form id="form-hapus-d1" class="dynamic-subform" data-section="del-d1-target" style="display:none;" action="{{ route('karyawan.career-path.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <input type="hidden" name="career_path_choice" value="-">
                <input type="hidden" name="target_position_title" value="-">
                <input type="hidden" name="target_job_class_grade" value="-">
                <input type="hidden" name="target_timeframe" value="-">
                <input type="hidden" name="target_readiness" value="Belum Siap">
                <div style="font-size:12.5px; color:#334155; line-height:1.6;">
                    Apakah Anda yakin ingin mereset data <strong>Arah Karir & Posisi Target (D1)</strong> menjadi kosong atau belum ditentukan?
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Reset Arah Karir</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =========================================================================
     MODAL GRUP 3: STATUS SUKSESI (E1 POSISI & E2 CALON PENGGANTI)
   ========================================================================= --}}

{{-- 3.1 Modal Tambah Status Suksesi --}}
<div id="modal-tambah-suksesi" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Data Status Suksesi
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body">
            {{-- Pilihan Bagian yang Ingin Ditambah --}}
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian Suksesi yang Ingin Ditambah:
                </label>
                <select id="select-tambah-suksesi-section" class="form-control section-switcher" data-container="container-tambah-suksesi">
                    <option value="e1-posisi">E1. Posisi Jabatan untuk Suksesi</option>
                    <option value="e2-calon">E2. Calon Pengganti (Suksesor)</option>
                </select>
            </div>

            {{-- Form E1: Tambah Posisi Suksesi --}}
            <form id="form-tambah-e1" class="dynamic-subform" data-section="e1-posisi" action="{{ route('karyawan.succession-position.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Jabatan yang Disiapkan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="target_position" class="form-control" placeholder="Contoh: Plant Manager" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Departemen / Fungsi <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="department" class="form-control" placeholder="Contoh: Operations" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Level Jabatan <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="position_level" class="form-control" placeholder="Contoh: Manager" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Dibutuhkan Pada <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="needed_at" class="form-control" placeholder="Contoh: Jul 2028" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kesiapan</label>
                        <select name="readiness" class="form-control">
                            <option value="Siap Sekarang">Siap Sekarang</option>
                            <option value="1–2 Tahun">1–2 Tahun</option>
                            <option value="3–5 Tahun" selected>3–5 Tahun</option>
                            <option value=">5 Tahun">>5 Tahun</option>
                            <option value="Belum Siap">Belum Siap</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Alasan Disiapkan untuk Suksesi</label>
                    <textarea name="reason" class="form-control" rows="2" placeholder="Jelaskan alasan strategis penyiapan posisi ini"></textarea>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Posisi Suksesi (E1)</button>
                </div>
            </form>

            {{-- Form E2: Tambah Calon Pengganti --}}
            <form id="form-tambah-e2" class="dynamic-subform" data-section="e2-calon" style="display:none;" action="{{ route('karyawan.succession-candidate.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Kandidat Suksesor <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="candidate_name" class="form-control" placeholder="Contoh: Andi Pratama" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Peringkat Suksesor <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="ranking" class="form-control" placeholder="1" min="1" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Departemen Saat Ini <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="current_department" class="form-control" placeholder="Contoh: Manufacturing Engineering" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jabatan Saat Ini <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="current_position" class="form-control" placeholder="Contoh: Supervisor" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kesiapan <span style="color:#ef4444;">*</span></label>
                        <select name="readiness" class="form-control" required>
                            <option value="Siap Sekarang">Siap Sekarang</option>
                            <option value="1–2 Tahun">1–2 Tahun</option>
                            <option value="3–5 Tahun" selected>3–5 Tahun</option>
                            <option value=">5 Tahun">>5 Tahun</option>
                            <option value="Belum Siap">Belum Siap</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kapan Dibutuhkan <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="needed_at" class="form-control" placeholder="Contoh: Apr 2044" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="notes" class="form-control" placeholder="Contoh: Kandidat internal utama / Perlu exposure">
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Calon Pengganti (E2)</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 3.2 Modal Edit Status Suksesi (E1 atau E2) --}}
<div id="modal-edit-suksesi" class="modal-backdrop">
    <div class="modal-card modal-lg">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Status Suksesi
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
            {{-- Pilihan Bagian Suksesi yang Ingin Diedit --}}
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian Suksesi yang Ingin Diedit:
                </label>
                <select id="select-edit-suksesi-section" class="form-control section-switcher" data-container="container-edit-suksesi">
                    <option value="edit-e1-posisi">E1. Posisi Jabatan untuk Suksesi</option>
                    <option value="edit-e2-calon">E2. Calon Pengganti (Suksesor)</option>
                </select>
            </div>

            {{-- Form E1: Edit Posisi Suksesi --}}
            <form id="form-edit-e1" class="dynamic-subform" data-section="edit-e1-posisi" action="" method="POST">
                @csrf
                @method('PUT')
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir E1: Edit Posisi Jabatan Suksesi</div>
                <div class="form-group">
                    <label class="form-label">Pilih Posisi yang Ingin Diedit:</label>
                    <select id="select-edit-e1-item" class="form-control">
                        @foreach($employee->successionPositions as $pos)
                            <option value="{{ $pos->id }}"
                                    data-target="{{ $pos->target_position }}"
                                    data-dept="{{ $pos->department }}"
                                    data-level="{{ $pos->position_level }}"
                                    data-needed="{{ $pos->needed_at }}"
                                    data-readiness="{{ $pos->readiness }}"
                                    data-reason="{{ $pos->reason }}">
                                #{{ $pos->order_no }} - {{ $pos->target_position }} ({{ $pos->department }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jabatan yang Disiapkan <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="edit-e1-target" name="target_position" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Departemen / Fungsi <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-e1-dept" name="department" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Level Jabatan <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-e1-level" name="position_level" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Dibutuhkan Pada <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-e1-needed" name="needed_at" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kesiapan</label>
                        <select id="edit-e1-readiness" name="readiness" class="form-control">
                            <option value="Siap Sekarang">Siap Sekarang</option>
                            <option value="1–2 Tahun">1–2 Tahun</option>
                            <option value="3–5 Tahun">3–5 Tahun</option>
                            <option value=">5 Tahun">>5 Tahun</option>
                            <option value="Belum Siap">Belum Siap</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Alasan Disiapkan</label>
                    <textarea id="edit-e1-reason" name="reason" class="form-control" rows="2"></textarea>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Posisi Suksesi (E1)</button>
                </div>
            </form>

            {{-- Form E2: Edit Calon Pengganti --}}
            <form id="form-edit-e2" class="dynamic-subform" data-section="edit-e2-calon" style="display:none;" action="" method="POST">
                @csrf
                @method('PUT')
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir E2: Edit Calon Pengganti (Suksesor)</div>
                <div class="form-group">
                    <label class="form-label">Pilih Calon yang Ingin Diedit:</label>
                    <select id="select-edit-e2-item" class="form-control">
                        @foreach($employee->successionCandidates as $cand)
                            <option value="{{ $cand->id }}"
                                    data-name="{{ $cand->candidate_name }}"
                                    data-ranking="{{ $cand->ranking }}"
                                    data-dept="{{ $cand->current_department }}"
                                    data-pos="{{ $cand->current_position }}"
                                    data-readiness="{{ $cand->readiness }}"
                                    data-needed="{{ $cand->needed_at }}"
                                    data-notes="{{ $cand->notes }}">
                                Rank {{ $cand->ranking }}: {{ $cand->candidate_name }} ({{ $cand->current_position }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Kandidat Suksesor <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-e2-name" name="candidate_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Peringkat Suksesor <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="edit-e2-ranking" name="ranking" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Departemen Saat Ini <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-e2-dept" name="current_department" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jabatan Saat Ini <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-e2-pos" name="current_position" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kesiapan <span style="color:#ef4444;">*</span></label>
                        <select id="edit-e2-readiness" name="readiness" class="form-control" required>
                            <option value="Siap Sekarang">Siap Sekarang</option>
                            <option value="1–2 Tahun">1–2 Tahun</option>
                            <option value="3–5 Tahun">3–5 Tahun</option>
                            <option value=">5 Tahun">>5 Tahun</option>
                            <option value="Belum Siap">Belum Siap</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kapan Dibutuhkan <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-e2-needed" name="needed_at" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <input type="text" id="edit-e2-notes" name="notes" class="form-control">
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Calon Pengganti (E2)</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 3.3 Modal Hapus Status Suksesi (Pilihan E1 atau E2) --}}
<div id="modal-hapus-suksesi" class="modal-backdrop">
    <div class="modal-card" style="max-width:480px;">
        <div class="modal-header" style="background:#b91c1c;">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Data Status Suksesi
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body">
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian yang Ingin Dihapus:
                </label>
                <select id="select-hapus-suksesi-section" class="form-control section-switcher" data-container="container-hapus-suksesi">
                    <option value="del-e1-posisi">E1. Posisi Jabatan untuk Suksesi</option>
                    <option value="del-e2-calon">E2. Calon Pengganti (Suksesor)</option>
                </select>
            </div>

            {{-- Form Hapus E1 --}}
            <form id="form-hapus-e1" class="dynamic-subform" data-section="del-e1-posisi" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Pilih Posisi Suksesi yang Akan Dihapus:</label>
                    <select id="select-del-e1-item" class="form-control">
                        @foreach($employee->successionPositions as $pos)
                            <option value="{{ $pos->id }}">
                                #{{ $pos->order_no }} - {{ $pos->target_position }} ({{ $pos->department }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size:12px; color:#b91c1c; margin-top:10px;">
                    Perhatian: Posisi suksesi yang dihapus tidak dapat dipulihkan.
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Hapus Posisi Ini</button>
                </div>
            </form>

            {{-- Form Hapus E2 --}}
            <form id="form-hapus-e2" class="dynamic-subform" data-section="del-e2-calon" style="display:none;" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Pilih Calon Pengganti yang Akan Dihapus:</label>
                    <select id="select-del-e2-item" class="form-control">
                        @foreach($employee->successionCandidates as $cand)
                            <option value="{{ $cand->id }}">
                                Rank {{ $cand->ranking }}: {{ $cand->candidate_name }} ({{ $cand->current_position }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size:12px; color:#b91c1c; margin-top:10px;">
                    Perhatian: Calon pengganti yang dihapus tidak dapat dipulihkan.
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Hapus Calon Ini</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =========================================================================
     MODAL GRUP 4: DEVELOPMENT GAP (TARGET POSISI & GAP KOMPETENSI)
   ========================================================================= --}}

{{-- 4.1 Modal Tambah Development Gap --}}
<div id="modal-tambah-gap" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Data Development Gap
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body">
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian yang Ingin Ditambah / Diatur:
                </label>
                <select id="select-tambah-gap-section" class="form-control section-switcher" data-container="container-tambah-gap">
                    <option value="gap-detail">B. Detail Baris Gap Kompetensi Baru</option>
                    <option value="gap-target">A. Posisi Target & Metode Asesmen Gap</option>
                </select>
            </div>

            {{-- Form Gap Detail --}}
            <form id="form-tambah-gap-detail" class="dynamic-subform" data-section="gap-detail" action="{{ route('karyawan.competency-gap.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Kompetensi <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="competency" class="form-control" placeholder="Contoh: Digital Transformation / Strategic Planning" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Level Saat Ini (1 - 5) <span style="color:#ef4444;">*</span></label>
                        <select name="current_level" class="form-control" required>
                            @for($l = 1; $l <= 5; $l++)
                                <option value="{{ $l }}" {{ $l == 3 ? 'selected' : '' }}>Level {{ $l }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Level Standar Target (1 - 5) <span style="color:#ef4444;">*</span></label>
                        <select name="standard_level" class="form-control" required>
                            @for($s = 1; $s <= 5; $s++)
                                <option value="{{ $s }}" {{ $s == 5 ? 'selected' : '' }}>Level {{ $s }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Level Saat Ini</label>
                    <input type="text" name="current_desc" class="form-control" placeholder="Deskripsi pemenuhan level saat ini">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Level Standar</label>
                    <input type="text" name="standard_desc" class="form-control" placeholder="Deskripsi tuntutan level target posisi">
                </div>

                <div class="form-group">
                    <label class="form-label">Peningkatan yang Diharapkan <span style="color:#ef4444;">*</span></label>
                    <textarea name="expected_improvement" class="form-control" rows="2" placeholder="Uraikan output peningkatan yang diharapkan dari karyawan..." required></textarea>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Gap Kompetensi</button>
                </div>
            </form>

            {{-- Form Gap Target --}}
            <form id="form-tambah-gap-target" class="dynamic-subform" data-section="gap-target" style="display:none;" action="{{ route('karyawan.development-gap.target.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Posisi Target <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="target_position_gap" class="form-control" value="{{ $employee->target_position_gap ?: 'Engineering Manager (JC5 / G5-1)' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Departemen / Fungsi Target <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="target_department_gap" class="form-control" value="{{ $employee->target_department_gap ?: 'Manufacturing Engineering' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Metode Asesmen Gap <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="gap_method" class="form-control" value="{{ $employee->gap_method ?: 'Perbandingan Kompetensi: Posisi Saat Ini vs Posisi Target (Engineering Manager)' }}" required>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Target Gap</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 4.2 Modal Edit Development Gap (Pilih Target Gap atau Baris Kompetensi) --}}
<div id="modal-edit-gap" class="modal-backdrop">
    <div class="modal-card modal-lg">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Data Development Gap
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian yang Ingin Diedit:
                </label>
                <select id="select-edit-gap-section" class="form-control section-switcher" data-container="container-edit-gap">
                    <option value="edit-gap-target">Informasi Posisi Target & Metode Gap</option>
                    <option value="edit-gap-detail">Detail Baris Gap Kompetensi</option>
                </select>
            </div>

            {{-- Form Target Gap --}}
            <form id="form-edit-gap-target" class="dynamic-subform" data-section="edit-gap-target" action="{{ route('karyawan.development-gap.target.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir Posisi Target & Metode Asesmen Gap</div>
                <div class="form-group">
                    <label class="form-label">Posisi Target <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="target_position_gap" class="form-control" value="{{ $employee->target_position_gap ?: 'Engineering Manager (JC5 / G5-1)' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Departemen / Fungsi Target <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="target_department_gap" class="form-control" value="{{ $employee->target_department_gap ?: 'Manufacturing Engineering' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Metode Asesmen Gap <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="gap_method" class="form-control" value="{{ $employee->gap_method ?: 'Perbandingan Kompetensi: Posisi Saat Ini vs Posisi Target (Engineering Manager)' }}" required>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Informasi Target Gap</button>
                </div>
            </form>

            {{-- Form Edit Gap Kompetensi --}}
            <form id="form-edit-gap-detail" class="dynamic-subform" data-section="edit-gap-detail" style="display:none;" action="" method="POST">
                @csrf
                @method('PUT')
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir Edit Baris Gap Kompetensi</div>
                <div class="form-group">
                    <label class="form-label">Pilih Kompetensi yang Ingin Diedit:</label>
                    <select id="select-edit-gap-item" class="form-control">
                        @foreach($employee->competencyGaps as $gap)
                            <option value="{{ $gap->id }}"
                                    data-comp="{{ $gap->competency }}"
                                    data-curr="{{ $gap->current_level }}"
                                    data-currdesc="{{ $gap->current_desc }}"
                                    data-std="{{ $gap->standard_level }}"
                                    data-stddesc="{{ $gap->standard_desc }}"
                                    data-improvement="{{ $gap->expected_improvement }}">
                                #{{ $gap->order_no }} - {{ $gap->competency }} (Gap: {{ $gap->gap }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Kompetensi <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="edit-gap-comp" name="competency" class="form-control" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Level Saat Ini (1 - 5) <span style="color:#ef4444;">*</span></label>
                        <select id="edit-gap-curr" name="current_level" class="form-control" required>
                            @for($l = 1; $l <= 5; $l++)
                                <option value="{{ $l }}">Level {{ $l }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Level Standar Target (1 - 5) <span style="color:#ef4444;">*</span></label>
                        <select id="edit-gap-std" name="standard_level" class="form-control" required>
                            @for($s = 1; $s <= 5; $s++)
                                <option value="{{ $s }}">Level {{ $s }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Level Saat Ini</label>
                    <input type="text" id="edit-gap-currdesc" name="current_desc" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Level Standar</label>
                    <input type="text" id="edit-gap-stddesc" name="standard_desc" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Peningkatan yang Diharapkan <span style="color:#ef4444;">*</span></label>
                    <textarea id="edit-gap-improvement" name="expected_improvement" class="form-control" rows="2" required></textarea>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Gap Kompetensi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 4.3 Modal Hapus Development Gap --}}
<div id="modal-hapus-gap" class="modal-backdrop">
    <div class="modal-card" style="max-width:500px;">
        <div class="modal-header" style="background:#b91c1c;">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Data Development Gap
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body">
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian Gap yang Ingin Dihapus / Direset:
                </label>
                <select id="select-hapus-gap-section" class="form-control section-switcher" data-container="container-hapus-gap">
                    <option value="del-gap-detail">B. Detail Baris Gap Kompetensi (Hapus Baris)</option>
                    <option value="del-gap-target">A. Posisi Target & Metode Gap (Reset Nilai)</option>
                </select>
            </div>

            {{-- Form Hapus Gap Baris --}}
            <form id="form-hapus-gap" class="dynamic-subform" data-section="del-gap-detail" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Pilih Gap Kompetensi yang Akan Dihapus:</label>
                    <select id="select-del-gap-item" class="form-control">
                        @foreach($employee->competencyGaps as $gap)
                            <option value="{{ $gap->id }}">
                                #{{ $gap->order_no }} - {{ $gap->competency }} (Gap: {{ $gap->gap }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size:12px; color:#b91c1c; margin-top:10px;">
                    Perhatian: Data gap kompetensi yang dihapus tidak dapat dipulihkan.
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Hapus Gap Ini</button>
                </div>
            </form>

            {{-- Form Reset Target Gap --}}
            <form id="form-reset-gap-target" class="dynamic-subform" data-section="del-gap-target" style="display:none;" action="{{ route('karyawan.development-gap.target.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <input type="hidden" name="target_position_gap" value="-">
                <input type="hidden" name="target_department_gap" value="-">
                <input type="hidden" name="gap_method" value="-">
                <div style="font-size:12.5px; color:#334155; line-height:1.6;">
                    Apakah Anda yakin ingin mereset informasi <strong>Posisi Target & Metode Gap</strong> menjadi default/kosong?
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Reset Target Gap</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =========================================================================
     MODAL GRUP 5: INDIVIDUAL DEVELOPMENT PLAN (IDP / INDIVIDUAL GAP)
   ========================================================================= --}}

{{-- 5.1 Modal Tambah IDP Action Plan & Ringkasan --}}
<div id="modal-tambah-idp" class="modal-backdrop">
    <div class="modal-card modal-lg">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Data Individual Development Plan (IDP)
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian IDP yang Ingin Ditambah / Diatur:
                </label>
                <select id="select-tambah-idp-section" class="form-control section-switcher" data-container="container-tambah-idp">
                    <option value="idp-action">B. Detail Baris Rencana Aksi (Action Plan)</option>
                    <option value="idp-summary">A. Ringkasan Kesiapan & Incumbent</option>
                </select>
            </div>

            {{-- Form IDP Action Plan --}}
            <form id="form-tambah-idp-action" class="dynamic-subform" data-section="idp-action" action="{{ route('karyawan.idp-action-plan.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Kompetensi yang Dikembangkan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="competency" class="form-control" placeholder="Contoh: Vision & Business Sense" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tujuan Spesifik (Target)</label>
                    <textarea name="specific_goal" class="form-control" rows="2" placeholder="Tujuan terukur yang ingin dicapai..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Metode Pengembangan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="development_methods" class="form-control" placeholder="Contoh: Training, On the Job, Coaching, Exposure" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Aktivitas / Program <span style="color:#ef4444;">*</span></label>
                    <textarea name="activity_program" class="form-control" rows="2" placeholder="Contoh: • Strategic Initiative Project&#10;• Mentoring Executive" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">PIC / Pendukung <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="pic_supporter" class="form-control" placeholder="Contoh: Division Head, HR Mentor" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span style="color:#ef4444;">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="On Progress">On Progress</option>
                            <option value="Planning">Planning</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="start_date" class="form-control" placeholder="Contoh: Jun 2026" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="end_date" class="form-control" placeholder="Contoh: Des 2026" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Indikator Keberhasilan</label>
                        <input type="text" name="success_indicator" class="form-control" placeholder="Contoh: Disetujuinya inisiatif strategis oleh atasan">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Progres (%) <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="progress_percent" class="form-control" min="0" max="100" value="0" required>
                    </div>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Rencana IDP</button>
                </div>
            </form>

            {{-- Form IDP Summary --}}
            <form id="form-tambah-idp-summary" class="dynamic-subform" data-section="idp-summary" style="display:none;" action="{{ route('karyawan.idp.summary.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Persentase Tingkat Kesiapan <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="readiness_level" class="form-control" value="{{ $employee->readiness_level ?: '68%' }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan Kesiapan <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="idp_readiness_desc" class="form-control" value="{{ $employee->idp_readiness_desc ?: 'Siap dalam 1–3 Tahun' }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Pensiun Incumbent / Kapan Dibutuhkan</label>
                    <input type="text" name="retirement_year" class="form-control" value="{{ $employee->retirement_year ?: 'April 2044' }}" placeholder="Contoh: April 2044">
                </div>
                <div class="form-group">
                    <label class="form-label">Tujuan Pengembangan Utama</label>
                    <textarea name="idp_primary_goal" class="form-control" rows="3" placeholder="Mempersiapkan karyawan untuk mencapai level kompetensi target...">{{ $employee->idp_primary_goal ?: 'Mempersiapkan ' . $employee->name . ' untuk mencapai kompetensi pada level yang diharapkan untuk posisi Engineering Manager, sehingga siap menggantikan posisi Section Head saat incumbent pensiun.' }}</textarea>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Ringkasan IDP</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 5.2 Modal Edit IDP (Pilih Ringkasan Kesiapan atau Baris Action Plan) --}}
<div id="modal-edit-idp" class="modal-backdrop">
    <div class="modal-card modal-lg">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Individual Development Plan (IDP)
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian yang Ingin Diedit:
                </label>
                <select id="select-edit-idp-section" class="form-control section-switcher" data-container="container-edit-idp">
                    <option value="edit-idp-summary">A. Ringkasan Kesiapan & Fokus Pengembangan</option>
                    <option value="edit-idp-action">B. Detail Baris Rencana Aksi (Action Plan)</option>
                </select>
            </div>

            {{-- Form Edit Ringkasan IDP --}}
            <form id="form-edit-idp-summary" class="dynamic-subform" data-section="edit-idp-summary" action="{{ route('karyawan.idp.summary.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir Ringkasan Kesiapan & Incumbent</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Persentase Tingkat Kesiapan <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="readiness_level" class="form-control" value="{{ $employee->readiness_level ?: '68%' }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan Kesiapan <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="idp_readiness_desc" class="form-control" value="{{ $employee->idp_readiness_desc ?: 'Siap dalam 1–3 Tahun' }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Pensiun Incumbent / Kapan Dibutuhkan</label>
                    <input type="text" name="retirement_year" class="form-control" value="{{ $employee->retirement_year ?: '2044' }}" placeholder="Contoh: 2044">
                </div>
                <div class="form-group">
                    <label class="form-label">Tujuan Pengembangan Utama</label>
                    <textarea name="idp_primary_goal" class="form-control" rows="3" placeholder="Mempersiapkan karyawan untuk mencapai level kompetensi posisi target...">{{ $employee->idp_primary_goal ?: 'Mempersiapkan ' . $employee->name . ' untuk mencapai kompetensi pada level yang diharapkan untuk posisi Engineering Manager, sehingga siap menggantikan posisi Section Head saat incumbent pensiun.' }}</textarea>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Ringkasan IDP</button>
                </div>
            </form>

            {{-- Form Edit Action Plan IDP --}}
            <form id="form-edit-idp-action" class="dynamic-subform" data-section="edit-idp-action" style="display:none;" action="" method="POST">
                @csrf
                @method('PUT')
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir Edit Baris Rencana Aksi IDP</div>
                <div class="form-group">
                    <label class="form-label">Pilih Rencana Aksi yang Ingin Diedit:</label>
                    <select id="select-edit-idp-item" class="form-control">
                        @foreach($employee->idpActionPlans as $plan)
                            <option value="{{ $plan->id }}"
                                    data-comp="{{ $plan->competency }}"
                                    data-goal="{{ $plan->specific_goal }}"
                                    data-methods="{{ $plan->development_methods }}"
                                    data-program="{{ $plan->activity_program }}"
                                    data-pic="{{ $plan->pic_supporter }}"
                                    data-start="{{ $plan->start_date }}"
                                    data-end="{{ $plan->end_date }}"
                                    data-indicator="{{ $plan->success_indicator }}"
                                    data-status="{{ $plan->status }}"
                                    data-progress="{{ $plan->progress_percent }}">
                                #{{ $plan->order_no }} - {{ $plan->competency }} ({{ $plan->status }} - {{ $plan->progress_percent }}%)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Kompetensi yang Dikembangkan <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="edit-idp-comp" name="competency" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tujuan Spesifik</label>
                    <textarea id="edit-idp-goal" name="specific_goal" class="form-control" rows="2"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Metode Pengembangan <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="edit-idp-methods" name="development_methods" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Aktivitas / Program <span style="color:#ef4444;">*</span></label>
                    <textarea id="edit-idp-program" name="activity_program" class="form-control" rows="2" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">PIC / Pendukung <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-idp-pic" name="pic_supporter" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span style="color:#ef4444;">*</span></label>
                        <select id="edit-idp-status" name="status" class="form-control" required>
                            <option value="On Progress">On Progress</option>
                            <option value="Planning">Planning</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-idp-start" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-idp-end" name="end_date" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Indikator Keberhasilan</label>
                        <input type="text" id="edit-idp-indicator" name="success_indicator" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Progres (%) <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="edit-idp-progress" name="progress_percent" class="form-control" min="0" max="100" required>
                    </div>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Rencana Aksi IDP</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 5.3 Modal Hapus IDP Action Plan & Summary --}}
<div id="modal-hapus-idp" class="modal-backdrop">
    <div class="modal-card" style="max-width:500px;">
        <div class="modal-header" style="background:#b91c1c;">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Data Individual Development Plan (IDP)
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body">
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian IDP yang Ingin Dihapus / Direset:
                </label>
                <select id="select-hapus-idp-section" class="form-control section-switcher" data-container="container-hapus-idp">
                    <option value="del-idp-action">B. Detail Baris Rencana Aksi (Hapus Baris)</option>
                    <option value="del-idp-summary">A. Ringkasan Kesiapan & Incumbent (Reset Nilai)</option>
                </select>
            </div>

            {{-- Form Hapus IDP Baris --}}
            <form id="form-hapus-idp" class="dynamic-subform" data-section="del-idp-action" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Pilih Rencana Aksi yang Akan Dihapus:</label>
                    <select id="select-del-idp-item" class="form-control">
                        @foreach($employee->idpActionPlans as $plan)
                            <option value="{{ $plan->id }}">
                                #{{ $plan->order_no }} - {{ $plan->competency }} ({{ $plan->status }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size:12px; color:#b91c1c; margin-top:10px;">
                    Perhatian: Data rencana aksi yang dihapus tidak dapat dipulihkan.
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Hapus Rencana Ini</button>
                </div>
            </form>

            {{-- Form Reset IDP Summary --}}
            <form id="form-reset-idp-summary" class="dynamic-subform" data-section="del-idp-summary" style="display:none;" action="{{ route('karyawan.idp.summary.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <input type="hidden" name="readiness_level" value="-">
                <input type="hidden" name="idp_readiness_desc" value="-">
                <input type="hidden" name="retirement_year" value="-">
                <input type="hidden" name="idp_primary_goal" value="-">
                <div style="font-size:12.5px; color:#334155; line-height:1.6;">
                    Apakah Anda yakin ingin mereset data <strong>Ringkasan Kesiapan & Fokus IDP</strong> menjadi default/kosong?
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Reset Ringkasan IDP</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- 9. Modal Riwayat Perubahan Umum --}}
<div id="modal-riwayat-perubahan" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                Log Riwayat Perubahan Terakhir
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <div class="modal-body">
            <div style="display:flex; flex-direction:column; gap:12px; font-size:12px;">
                <div style="padding:10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px;">
                    <div style="font-weight:700; color:#0f172a;">Pembaruan Profil & Grade</div>
                    <div style="font-size:11px; color:#64748b;">April 2026 oleh <strong>HR Admin</strong></div>
                    <div style="margin-top:4px; color:#334155;">Kenaikan grade reguler ke JC4 / G4-2 disetujui.</div>
                </div>
                <div style="padding:10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px;">
                    <div style="font-weight:700; color:#0f172a;">Evaluasi POTASS & Asesmen Talenta</div>
                    <div style="font-size:11px; color:#64748b;">Agustus 2026 oleh <strong>HR Development</strong></div>
                    <div style="margin-top:4px; color:#334155;">Skor POTASS meningkat ke 106% (Kategori High).</div>
                </div>
                <div style="padding:10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px;">
                    <div style="font-weight:700; color:#0f172a;">Penyusunan Individual Career Plan (ICP)</div>
                    <div style="font-size:11px; color:#64748b;">September 2026 oleh <strong>Division Head</strong></div>
                    <div style="margin-top:4px; color:#334155;">Jalur karir Managerial dikonfirmasi dengan target posisi Engineering Manager.</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-modal-close>Tutup</button>
        </div>
    </div>
</div>

{{-- 10. Modal Ubah Foto Profil (Super Admin & HR Admin) --}}
<div id="modal-ubah-foto" class="modal-backdrop">
    <div class="modal-card" style="max-width: 500px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #0b233e 0%, #1e3a8a 100%);">
            <div class="modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
                Ubah Foto Profil Karyawan
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form action="{{ route('karyawan.avatar.update', ['nik' => $employee->nik]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:6px; padding:10px 14px; margin-bottom:16px; font-size:12px; color:#1e40af; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    <span>Fitur ubah foto dapat digunakan oleh <strong>Super Admin</strong> dan <strong>HR Admin</strong>.</span>
                </div>

                {{-- Current Avatar Preview --}}
                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; margin-bottom:20px; gap:8px;">
                    <div style="position:relative;">
                        <img id="avatar-preview-display" src="{{ $employee->avatar ?: '/images/avatar-budi.png' }}" alt="{{ $employee->name }}" style="width:90px; height:90px; border-radius:50%; object-fit:cover; border:3px solid #2563eb; box-shadow:0 4px 10px rgba(0,0,0,0.15);">
                    </div>
                    <span style="font-size:12px; font-weight:600; color:#334155;">{{ $employee->name }} (NIK: {{ $employee->nik }})</span>
                </div>

                {{-- Option 1: File Upload --}}
                <div class="form-group" style="margin-bottom:18px;">
                    <label class="form-label" style="font-weight:700;">Unggah & Sesuaikan Foto Baru</label>
                    <input type="file" name="avatar_file" id="modal-avatar-file-input" class="form-control" accept="image/jpeg,image/png,image/webp,image/jpg" onchange="handleModalAvatarChange(this)">
                    <input type="hidden" name="cropped_avatar" id="modal-avatar-cropped-input" value="">
                    <span style="font-size:11px; color:#64748b; margin-top:4px; display:block;">Pilih foto untuk membuka alat pemotong & penyesuai posisi (zoom/pan) agar tidak terpotong.</span>
                </div>


                <div style="display:flex; align-items:center; text-align:center; margin:16px 0 12px; color:#94a3b8; font-size:11px; font-weight:700; text-transform:uppercase;">
                    <span style="flex:1; border-bottom:1px solid #e2e8f0;"></span>
                    <span style="padding:0 10px;">Atau Pilih Foto Preset</span>
                    <span style="flex:1; border-bottom:1px solid #e2e8f0;"></span>
                </div>

                {{-- Option 2: Preset Avatars --}}
                <input type="hidden" name="preset_avatar" id="input-preset-avatar" value="">
                <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:10px; margin-bottom:8px;">
                    @php
                        $presets = [
                            ['label' => 'Budi', 'path' => '/images/avatar-budi.png'],
                            ['label' => 'Siti', 'path' => '/images/avatar-siti.png'],
                            ['label' => 'Ahmad', 'path' => '/images/avatar-ahmad.png'],
                            ['label' => 'Dewi', 'path' => '/images/avatar-dewi.png'],
                        ];
                    @endphp
                    @foreach($presets as $p)
                        <div 
                            class="preset-avatar-option {{ $employee->avatar === $p['path'] ? 'active' : '' }}" 
                            onclick="selectPresetAvatar('{{ $p['path'] }}', this)"
                            style="border:2px solid #e2e8f0; border-radius:8px; padding:8px 4px; text-align:center; cursor:pointer; transition:all 0.2s;"
                        >
                            <img src="{{ $p['path'] }}" alt="{{ $p['label'] }}" style="width:46px; height:46px; border-radius:50%; object-fit:cover; margin:0 auto 4px; display:block;">
                            <div style="font-size:11px; font-weight:600; color:#334155;">{{ $p['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary" style="background:#0b233e;">Simpan Foto Profil</button>
            </div>
        </form>
    </div>
</div>

