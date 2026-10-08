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
                {{-- Tanggal Efektif (Bulan & Tahun Dropdown) --}}
                <div class="form-group">
                    <label class="form-label">Tanggal Efektif <span style="color:#ef4444;">*</span></label>
                    <div style="display: flex; gap: 8px;">
                        <select id="add-karir-month" class="form-control" style="flex: 1.2;" required>
                            <option value="">-- Pilih Bulan --</option>
                            <option value="Januari">Januari</option>
                            <option value="Februari">Februari</option>
                            <option value="Maret">Maret</option>
                            <option value="April">April</option>
                            <option value="Mei">Mei</option>
                            <option value="Juni">Juni</option>
                            <option value="Juli">Juli</option>
                            <option value="Agustus">Agustus</option>
                            <option value="September">September</option>
                            <option value="Oktober">Oktober</option>
                            <option value="November">November</option>
                            <option value="Desember">Desember</option>
                        </select>
                        <select id="add-karir-year" class="form-control" style="flex: 1;" required>
                            <option value="">-- Pilih Tahun --</option>
                            @for($y = (int)date('Y') + 5; $y >= 1990; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <input type="hidden" id="add-karir-date" name="effective_date" value="" required>
                </div>

                {{-- Job Class & Grade Dropdown --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Job Class <span style="color:#ef4444;">*</span></label>
                        <select name="job_class" id="add-karir-jobclass" class="form-control select-jobclass" required>
                            <option value="">-- Pilih Job Class --</option>
                            @foreach(\App\Services\TalentCalculatorService::getJobClassList() as $kj)
                                <option value="{{ $kj }}">{{ $kj }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grade <span style="color:#ef4444;">*</span></label>
                        <select name="grade" id="add-karir-grade" class="form-control select-grade" required>
                            <option value="">-- Pilih Grade --</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Departemen / Seksi</label>
                    <input type="text" name="department_section" class="form-control" placeholder="Contoh: Manufacturing Engineering / Process Eng." required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jabatan</label>
                        <select name="position" class="form-control" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach(\App\Services\TalentCalculatorService::getPositionStandards() as $pos)
                                <option value="{{ $pos }}">{{ $pos }}</option>
                            @endforeach
                        </select>
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
                                    data-jobclass="{{ $ch->job_class }}"
                                    data-grade="{{ $ch->grade }}"
                                    data-gradefull="{{ $ch->job_class_grade }}"
                                    data-type="{{ $ch->change_type }}"
                                    data-notes="{{ $ch->notes }}">
                                {{ $ch->effective_date }} - {{ $ch->position }} ({{ $ch->job_class }} / {{ $ch->grade }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal Efektif (Bulan & Tahun Dropdown) --}}
                <div class="form-group">
                    <label class="form-label">Tanggal Efektif <span style="color:#ef4444;">*</span></label>
                    <div style="display: flex; gap: 8px;">
                        <select id="edit-karir-month" class="form-control" style="flex: 1.2;" required>
                            <option value="">-- Pilih Bulan --</option>
                            <option value="Januari">Januari</option>
                            <option value="Februari">Februari</option>
                            <option value="Maret">Maret</option>
                            <option value="April">April</option>
                            <option value="Mei">Mei</option>
                            <option value="Juni">Juni</option>
                            <option value="Juli">Juli</option>
                            <option value="Agustus">Agustus</option>
                            <option value="September">September</option>
                            <option value="Oktober">Oktober</option>
                            <option value="November">November</option>
                            <option value="Desember">Desember</option>
                        </select>
                        <select id="edit-karir-year" class="form-control" style="flex: 1;" required>
                            <option value="">-- Pilih Tahun --</option>
                            @for($y = (int)date('Y') + 5; $y >= 1990; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <input type="hidden" id="edit-karir-date" name="effective_date" value="" required>
                </div>

                {{-- Job Class & Grade Dropdown --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Job Class <span style="color:#ef4444;">*</span></label>
                        <select name="job_class" id="edit-karir-jobclass" class="form-control select-jobclass" required>
                            <option value="">-- Pilih Job Class --</option>
                            @foreach(\App\Services\TalentCalculatorService::getJobClassList() as $kj)
                                <option value="{{ $kj }}">{{ $kj }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grade <span style="color:#ef4444;">*</span></label>
                        <select name="grade" id="edit-karir-grade" class="form-control select-grade" required>
                            <option value="">-- Pilih Grade --</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Departemen / Seksi</label>
                    <input type="text" id="edit-dept" name="department_section" class="form-control" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jabatan</label>
                        <select id="edit-pos" name="position" class="form-control" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach(\App\Services\TalentCalculatorService::getPositionStandards() as $pos)
                                <option value="{{ $pos }}">{{ $pos }}</option>
                            @endforeach
                        </select>
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
                                {{ $ch->effective_date }} - {{ $ch->position }} ({{ $ch->job_class }} / {{ $ch->grade }})
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
     MODAL GRUP: RIWAYAT PELATIHAN (TAMBAH, EDIT, HAPUS)
   ========================================================================= --}}

{{-- Modal Tambah Riwayat Pelatihan --}}
<div id="modal-tambah-pelatihan" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Riwayat Pelatihan
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form action="{{ route('karyawan.training-history.store', ['nik' => $employee->nik]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                {{-- Nama Pelatihan --}}
                <div class="form-group">
                    <label class="form-label">Nama Pelatihan <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="training_name" class="form-control" placeholder="Contoh: Problem Solving & Decision Making (PSDM)" required>
                </div>

                {{-- Rentang Tanggal Pelatihan --}}
                <div class="form-group">
                    <label class="form-label">Rentang Tanggal Pelatihan <span style="color:#ef4444;">*</span></label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <span style="font-size:11px; color:#64748b; display:block; margin-bottom:3px;">Dari Tanggal:</span>
                            <input type="date" id="add-training-start-date" name="start_date" class="form-control" required>
                        </div>
                        <div>
                            <span style="font-size:11px; color:#64748b; display:block; margin-bottom:3px;">Sampai Tanggal (Opsional):</span>
                            <input type="date" id="add-training-end-date" name="end_date" class="form-control">
                        </div>
                    </div>
                    <div id="add-training-date-preview" style="font-size:11.5px; color:#2563eb; font-weight:600; margin-top:5px; display:none;">
                        📅 Format Tampilan: <span id="add-training-date-preview-text"></span>
                    </div>
                    <input type="hidden" id="add-training-date" name="training_date" value="">
                </div>

                {{-- Kategori & Jenis Pelatihan --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                        <select name="category" class="form-control" required>
                            <option value="Functional">Functional</option>
                            <option value="Managerial">Managerial</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Pelatihan <span style="color:#ef4444;">*</span></label>
                        <select name="training_type" class="form-control" required>
                            <option value="Classroom">Classroom</option>
                            <option value="On the Job">On the Job</option>
                            <option value="E-Learning">E-Learning</option>
                            <option value="Workshop">Workshop</option>
                            <option value="Seminar">Seminar</option>
                        </select>
                    </div>
                </div>

                {{-- Penyelenggara & Durasi --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Penyelenggara</label>
                        <input type="text" name="organizer" class="form-control" placeholder="Contoh: Toyota Institute Indonesia">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durasi (Jam) <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.5" min="0.5" name="duration_hours" class="form-control" placeholder="Contoh: 16" required>
                    </div>
                </div>

                {{-- Dokumentasi Bukti Pelatihan --}}
                <div class="form-group">
                    <label class="form-label">Dokumentasi Bukti (Sertifikat / Foto / Dokumen)</label>
                    <input type="file" name="documentation" class="form-control" accept=".pdf,.png,.jpg,.jpeg,.webp">
                    <span style="font-size:11px; color:#64748b; margin-top:4px; display:block;">
                        Format yang didukung: <strong>PDF, PNG, JPG, JPEG, WEBP</strong> (Maksimal 10 MB).
                    </span>
                </div>

                {{-- Catatan --}}
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="notes" class="form-control" placeholder="Contoh: Sertifikat Kelulusan / Nilai A / Grade Sangat Baik">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Pelatihan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Riwayat Pelatihan --}}
<div id="modal-edit-pelatihan" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Riwayat Pelatihan
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form id="form-edit-pelatihan" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Pilih Data yang Ingin Diedit</label>
                    <select id="edit-select-pelatihan" class="form-control">
                        @foreach($employee->trainingHistories as $th)
                            <option value="{{ $th->id }}"
                                    data-name="{{ $th->training_name }}"
                                    data-start="{{ $th->start_date }}"
                                    data-end="{{ $th->end_date }}"
                                    data-date="{{ $th->training_date }}"
                                    data-cat="{{ $th->category }}"
                                    data-type="{{ $th->training_type }}"
                                    data-org="{{ $th->organizer }}"
                                    data-dur="{{ $th->duration_hours }}"
                                    data-notes="{{ $th->notes }}"
                                    data-doc="{{ $th->documentation }}"
                                    data-is-image="{{ $th->is_image ? '1' : '0' }}"
                                    data-is-pdf="{{ $th->is_pdf ? '1' : '0' }}">
                                {{ $th->training_date ?: '-' }} - {{ $th->training_name }} ({{ $th->category }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Pelatihan --}}
                <div class="form-group">
                    <label class="form-label">Nama Pelatihan <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="edit-training-name" name="training_name" class="form-control" required>
                </div>

                {{-- Rentang Tanggal Pelatihan --}}
                <div class="form-group">
                    <label class="form-label">Rentang Tanggal Pelatihan <span style="color:#ef4444;">*</span></label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <span style="font-size:11px; color:#64748b; display:block; margin-bottom:3px;">Dari Tanggal:</span>
                            <input type="date" id="edit-training-start-date" name="start_date" class="form-control">
                        </div>
                        <div>
                            <span style="font-size:11px; color:#64748b; display:block; margin-bottom:3px;">Sampai Tanggal:</span>
                            <input type="date" id="edit-training-end-date" name="end_date" class="form-control">
                        </div>
                    </div>
                    <div id="edit-training-date-preview" style="font-size:11.5px; color:#2563eb; font-weight:600; margin-top:5px; display:none;">
                        📅 Format Tampilan: <span id="edit-training-date-preview-text"></span>
                    </div>
                    <input type="hidden" id="edit-training-date" name="training_date" value="">
                </div>

                {{-- Kategori & Jenis Pelatihan --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                        <select id="edit-training-category" name="category" class="form-control" required>
                            <option value="Functional">Functional</option>
                            <option value="Managerial">Managerial</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Pelatihan <span style="color:#ef4444;">*</span></label>
                        <select id="edit-training-type" name="training_type" class="form-control" required>
                            <option value="Classroom">Classroom</option>
                            <option value="On the Job">On the Job</option>
                            <option value="E-Learning">E-Learning</option>
                            <option value="Workshop">Workshop</option>
                            <option value="Seminar">Seminar</option>
                        </select>
                    </div>
                </div>

                {{-- Penyelenggara & Durasi --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Penyelenggara</label>
                        <input type="text" id="edit-training-organizer" name="organizer" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durasi (Jam) <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.5" min="0.5" id="edit-training-duration" name="duration_hours" class="form-control" required>
                    </div>
                </div>

                {{-- Dokumentasi Bukti Pelatihan --}}
                <div class="form-group">
                    <label class="form-label">Dokumentasi Bukti (Sertifikat / Foto / Dokumen)</label>
                    <div id="edit-training-doc-current" style="margin-bottom:8px; font-size:12px; padding:8px 10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; display:none;">
                    </div>
                    <input type="file" id="edit-training-doc-file" name="documentation" class="form-control" accept=".pdf,.png,.jpg,.jpeg,.webp">
                    <span style="font-size:11px; color:#64748b; margin-top:4px; display:block;">
                        Pilih file baru jika ingin mengganti file bukti (PDF, PNG, JPG, JPEG, WEBP - Maks. 10 MB).
                    </span>
                    <label id="edit-training-remove-doc-wrap" style="display:none; align-items:center; gap:6px; margin-top:6px; font-size:12px; color:#dc2626; cursor:pointer;">
                        <input type="checkbox" name="remove_documentation" value="1" id="edit-training-remove-doc">
                        <span>Hapus file dokumentasi saat ini</span>
                    </label>
                </div>

                {{-- Catatan --}}
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <input type="text" id="edit-training-notes" name="notes" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui Pelatihan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus Riwayat Pelatihan --}}
<div id="modal-hapus-pelatihan" class="modal-backdrop">
    <div class="modal-card" style="max-width:460px;">
        <div class="modal-header" style="background:#b91c1c;">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Riwayat Pelatihan
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form id="form-hapus-pelatihan" action="" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Pilih Data yang Akan Dihapus</label>
                    <select id="delete-select-pelatihan" class="form-control">
                        @foreach($employee->trainingHistories as $th)
                            <option value="{{ $th->id }}">
                                {{ $th->training_date ?: '-' }} - {{ $th->training_name }} ({{ $th->category }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size:12px; color:#b91c1c; margin-top:8px;">
                    Perhatian: Data pelatihan beserta berkas dokumentasi yang dihapus tidak dapat dikembalikan.
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
     MODAL GRUP: SERTIFIKASI (BAGIAN C RIWAYAT PELATIHAN)
   ========================================================================= --}}

{{-- Modal Tambah Sertifikasi --}}
<div id="modal-tambah-sertifikasi" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Sertifikasi
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form action="{{ route('karyawan.certification.store', ['nik' => $employee->nik]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Sertifikasi <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Certified Supply Chain Professional (CSCP)" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Lembaga Penyelenggara / Penerbit</label>
                    <input type="text" name="issuer" class="form-control" placeholder="Contoh: APICS / BNSP / PMI">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Diperoleh</label>
                        <input type="text" name="obtained_date" class="form-control" placeholder="Contoh: 20 Mar 2025">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Masa Berlaku Hingga</label>
                        <input type="text" name="valid_until" class="form-control" placeholder="Contoh: 20 Mar 2027 atau Seumur Hidup">
                    </div>
                </div>

                {{-- Dokumen Bukti Sertifikasi --}}
                <div class="form-group">
                    <label class="form-label">Dokumen Bukti Sertifikasi (Sertifikat / Piagam / Berkas)</label>
                    <input type="file" name="documentation" class="form-control" accept=".pdf,.png,.jpg,.jpeg,.webp">
                    <span style="font-size:11px; color:#64748b; margin-top:4px; display:block;">
                        Format yang didukung: <strong>PDF, PNG, JPG, JPEG, WEBP</strong> (Maksimal 10 MB).
                    </span>
                </div>

                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:8px; font-size:12.5px; color:#1e293b; cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>Sertifikat masih aktif / berlaku</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Sertifikasi</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Sertifikasi --}}
<div id="modal-edit-sertifikasi" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Sertifikasi
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form id="form-edit-sertifikasi" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Pilih Sertifikasi yang Ingin Diedit</label>
                    <select id="edit-select-sertifikasi" class="form-control">
                        @foreach($employee->certifications as $cert)
                            <option value="{{ $cert->id }}"
                                    data-name="{{ $cert->name }}"
                                    data-issuer="{{ $cert->issuer }}"
                                    data-obtained="{{ $cert->obtained_date }}"
                                    data-valid="{{ $cert->valid_until }}"
                                    data-active="{{ $cert->is_active ? '1' : '0' }}"
                                    data-doc="{{ $cert->documentation }}"
                                    data-is-image="{{ $cert->is_image ? '1' : '0' }}"
                                    data-is-pdf="{{ $cert->is_pdf ? '1' : '0' }}">
                                {{ $cert->name }} ({{ $cert->issuer ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Sertifikasi <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="edit-cert-name" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Lembaga Penyelenggara / Penerbit</label>
                    <input type="text" id="edit-cert-issuer" name="issuer" class="form-control">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Diperoleh</label>
                        <input type="text" id="edit-cert-obtained" name="obtained_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Masa Berlaku Hingga</label>
                        <input type="text" id="edit-cert-valid" name="valid_until" class="form-control">
                    </div>
                </div>

                {{-- Dokumentasi Bukti Sertifikasi --}}
                <div class="form-group">
                    <label class="form-label">Dokumen Bukti Sertifikasi (Sertifikat / Piagam / Berkas)</label>
                    <div id="edit-cert-doc-current" style="margin-bottom:8px; font-size:12px; padding:8px 10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; display:none;">
                    </div>
                    <input type="file" id="edit-cert-doc-file" name="documentation" class="form-control" accept=".pdf,.png,.jpg,.jpeg,.webp">
                    <span style="font-size:11px; color:#64748b; margin-top:4px; display:block;">
                        Pilih file baru jika ingin mengganti dokumen bukti sertifikasi (PDF, PNG, JPG, JPEG, WEBP - Maks. 10 MB).
                    </span>
                    <label id="edit-cert-remove-doc-wrap" style="display:none; align-items:center; gap:6px; margin-top:6px; font-size:12px; color:#dc2626; cursor:pointer;">
                        <input type="checkbox" name="remove_documentation" value="1" id="edit-cert-remove-doc">
                        <span>Hapus dokumen sertifikasi saat ini</span>
                    </label>
                </div>

                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:8px; font-size:12.5px; color:#1e293b; cursor:pointer;">
                        <input type="checkbox" id="edit-cert-active" name="is_active" value="1">
                        <span>Sertifikat masih aktif / berlaku</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui Sertifikasi</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus Sertifikasi --}}
<div id="modal-hapus-sertifikasi" class="modal-backdrop">
    <div class="modal-card" style="max-width:460px;">
        <div class="modal-header" style="background:#b91c1c;">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Sertifikasi
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form id="form-hapus-sertifikasi" action="" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Pilih Sertifikasi yang Akan Dihapus</label>
                    <select id="delete-select-sertifikasi" class="form-control">
                        @foreach($employee->certifications as $cert)
                            <option value="{{ $cert->id }}">
                                {{ $cert->name }} ({{ $cert->issuer ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size:12px; color:#b91c1c; margin-top:8px;">
                    Perhatian: Sertifikasi beserta berkas dokumen yang dihapus tidak dapat dikembalikan dan akan mengurangi total sertifikasi.
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

{{-- 1.1 Modal TAMBAH Talent Snapshot (Mendukung Lengkap C1 sampai C6) --}}
<div id="modal-tambah-talent-snapshot" class="modal-backdrop">
    <div class="modal-card modal-lg">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Data Talent Snapshot
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
            {{-- Pilihan Bagian yang Ingin Ditambah --}}
            <div class="section-selector-box">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Pilih Bagian yang Ingin Ditambah:
                </label>
                <select id="select-tambah-talent-section" class="form-control section-switcher" data-container="container-tambah-talent">
                    <option value="c1-perf" selected>C1. Performance 3 Tahun Terakhir</option>
                    <option value="c2-potass">C2. Potential Assessment (POTASS)</option>
                    <option value="c3-hav">C3. HAV 16 Box & Talent Pool</option>
                    <option value="c4-strength">C4. Kekuatan Utama (Key Strength)</option>
                    <option value="c5-risk">C5. Flying Risk Assessment</option>
                    <option value="c6-potass">C6. Riwayat POTASS Assessment</option>
                </select>
            </div>

            {{-- Form C1: Tambah / Input Nilai Performance Appraisal Tahunan --}}
            <form id="form-tambah-c1" class="dynamic-subform" data-section="c1-perf" action="{{ route('karyawan.performance-appraisal.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <input type="hidden" name="tab" value="{{ $tab ?? 'talent-snapshot' }}">

                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px; display:flex; align-items:center; gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="#000000" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.707l-4.146 4.147a.5.5 0 0 1-.708 0L7 6.707l-5.146 5.147a.5.5 0 0 1-.708-.708l5.5-5.5a.5.5 0 0 1 .708 0L9.5 7.793 13.293 4H10.5a.5.5 0 0 1-.5-.5"/>
                    </svg>
                    <span>Formulir C1: Tambah Nilai Performance Appraisal Tahunan</span>
                </div>

                <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:6px; padding:10px 14px; font-size:11.5px; color:#0369a1; margin-bottom:14px;">
                    📊 <strong>Sinkronisasi Otomatis:</strong> Nilai yang Anda input akan disimpan ke database riwayat kinerja karyawan, otomatis menjadi bagian dari penilaian 3 tahun terakhir (C1), dan memperbarui baris matriks HAV 16 Box (C3).
                </div>

                @php
                    $suggestedYear = (int)($employee->performanceAppraisals->max('year') ?: 2026) + 1;
                @endphp

                <div class="form-row" style="grid-template-columns: 160px 1fr; gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Tahun Penilaian <span style="color:#ef4444;">*</span></label>
                        <select name="year" id="add-c1-year" class="form-control" required style="font-weight:700;">
                            @for($y = 2035; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $y == $suggestedYear ? 'selected' : '' }}>
                                    {{ $y }} (FY{{ substr((string)$y, -2) }})
                                </option>
                            @endfor
                        </select>
                        <small style="color:#64748b; font-size:10.5px; display:block; margin-top:3px;">Pilih tahun evaluasi</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Predikat / Nilai Kinerja <span style="color:#ef4444;">*</span></label>
                        <select name="rating" id="add-c1-rating" class="form-control" required style="font-weight:700;">
                            <option value="S">S – Istimewa (8 Poin)</option>
                            <option value="AS">AS – Amat Sangat Baik (7 Poin)</option>
                            <option value="A" selected>A – Sangat Baik (6 Poin)</option>
                            <option value="B+">B+ – Baik Plus (5 Poin)</option>
                            <option value="B">B – Baik (4 Poin)</option>
                            <option value="C">C – Cukup (2 Poin)</option>
                            <option value="K">K – Kurang (1 Poin)</option>
                        </select>
                        <small style="color:#64748b; font-size:10.5px; display:block; margin-top:3px;">Pilih skala predikat kinerja</small>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan / Keterangan Penilaian</label>
                    <input type="text" name="notes" id="add-c1-notes" class="form-control" placeholder="Contoh: Pencapaian target KPI tahunan 105%, evaluasi Q4 sangat memuaskan...">
                    <small style="color:#64748b; font-size:10.5px; display:block; margin-top:3px;">Opsional</small>
                </div>

                {{-- Ringkasan 3 Tahun Terkini yang Sedang Aktif --}}
                @php
                    $threeYearsC1 = $employee->getThreeYearPerformances();
                @endphp
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:10px 12px; margin-bottom:14px;">
                    <span style="font-size:11px; font-weight:700; color:#475569; display:block; margin-bottom:6px;">Data 3 Tahun Aktif Saat Ini di Dashboard:</span>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        @foreach($threeYearsC1 as $itemC1)
                            <div style="background:#ffffff; border:1px solid #cbd5e1; border-radius:4px; padding:4px 10px; font-size:11px; display:flex; align-items:center; gap:6px;">
                                <span style="color:#64748b;">{{ $itemC1['year'] }}:</span>
                                <strong style="color:#0b2545;">{{ $itemC1['rating'] }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Nilai Performance (C1)</button>
                </div>
            </form>

            {{-- Form C2: Tambah / Input Potential Assessment (POTASS) --}}
            <form id="form-tambah-c2" class="dynamic-subform" data-section="c2-potass" style="display:none;" action="{{ route('karyawan.talent-snapshot.potass.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px; display:flex; align-items:center; gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="#000000" class="bi bi-bullseye" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M8 13A5 5 0 1 1 8 3a5 5 0 0 1 0 10m0 1A6 6 0 1 0 8 2a6 6 0 0 0 0 12"/>
                        <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6m0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8"/>
                        <path d="M9.5 8a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                    </svg>
                    <span>Formulir C2: Input Potential Assessment (POTASS)</span>
                </div>

                <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:6px; padding:10px 14px; font-size:11.5px; color:#0369a1; margin-bottom:14px;">
                    🔗 <strong>Terhubung Otomatis dengan C3:</strong> Mengisi data asesmen ini akan langsung memperbarui kartu C2 dan kolom kuadran pada matriks 16 HAV Box (C3).
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:12px;">
                    {{-- Kolom Asesmen Sebelumnya --}}
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:12px;">
                        <div style="font-weight:700; font-size:12px; color:#475569; margin-bottom:8px;">ASESMEN SEBELUMNYA (Pembanding)</div>
                        <div class="form-group">
                            <label class="form-label">Periode <span style="color:#ef4444;">*</span></label>
                            @php
                                $addPrevParts = explode('-', $employee->potass_period_prev ?: 'Aug-24');
                                $addPrevM = $addPrevParts[0] ?? 'Aug';
                                $addPrevY = substr($addPrevParts[1] ?? '24', -2);
                            @endphp
                            <div style="display:flex; gap:6px; align-items:center;">
                                <select class="form-control period-month-select" id="add-c2-prev-month" style="flex:1;">
                                    @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $m)
                                        <option value="{{ $m }}" {{ $m === $addPrevM ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                                <span style="color:#94a3b8; font-weight:700;">-</span>
                                <select class="form-control period-year-select" id="add-c2-prev-year" style="width:85px;">
                                    @for($y = 20; $y <= 35; $y++)
                                        @php $yStr = sprintf('%02d', $y); @endphp
                                        <option value="{{ $yStr }}" {{ $yStr === $addPrevY ? 'selected' : '' }}>{{ $yStr }}</option>
                                    @endfor
                                </select>
                            </div>
                            <input type="hidden" name="potass_period_prev" id="add-c2-prev-val" value="{{ $employee->potass_period_prev ?: 'Aug-24' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Score POTASS <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="potass_score_prev" class="form-control" value="{{ $employee->potass_score_prev ?: '94%' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Standar Jabatan <span style="color:#ef4444;">*</span></label>
                            <select name="potass_position_prev" class="form-control" required>
                                @php
                                    $curPrevPosAdd = $employee->potass_position_prev ?: 'SECTION HEAD';
                                    $posListAdd = \App\Services\TalentCalculatorService::getPositionStandards();
                                @endphp
                                @foreach($posListAdd as $pos)
                                    <option value="{{ $pos }}" {{ strcasecmp(trim($pos), trim($curPrevPosAdd)) === 0 ? 'selected' : '' }}>{{ $pos }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                            <select name="potass_category_prev" class="form-control" required>
                                <option value="High" {{ ($employee->potass_category_prev ?: 'Average') === 'High' ? 'selected' : '' }}>High</option>
                                <option value="Average" {{ ($employee->potass_category_prev ?: 'Average') === 'Average' ? 'selected' : '' }}>Average</option>
                                <option value="Below Average" {{ ($employee->potass_category_prev ?: 'Average') === 'Below Average' ? 'selected' : '' }}>Below Average</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Assessor <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="potass_assessor_prev" class="form-control" value="{{ $employee->potass_assessor_prev ?: 'HR Development' }}" required>
                        </div>
                    </div>

                    {{-- Kolom Asesmen Terakhir --}}
                    <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:6px; padding:12px;">
                        <div style="font-weight:700; font-size:12px; color:#1d4ed8; margin-bottom:8px;">ASESMEN TERAKHIR (Data Baru)</div>
                        <div class="form-group">
                            <label class="form-label">Periode <span style="color:#ef4444;">*</span></label>
                            @php
                                $addLastParts = explode('-', $employee->potass_period_last ?: 'Aug-26');
                                $addLastM = $addLastParts[0] ?? 'Aug';
                                $addLastY = substr($addLastParts[1] ?? '26', -2);
                            @endphp
                            <div style="display:flex; gap:6px; align-items:center;">
                                <select class="form-control period-month-select" id="add-c2-last-month" style="flex:1;">
                                    @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $m)
                                        <option value="{{ $m }}" {{ $m === $addLastM ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                                <span style="color:#94a3b8; font-weight:700;">-</span>
                                <select class="form-control period-year-select" id="add-c2-last-year" style="width:85px;">
                                    @for($y = 20; $y <= 35; $y++)
                                        @php $yStr = sprintf('%02d', $y); @endphp
                                        <option value="{{ $yStr }}" {{ $yStr === $addLastY ? 'selected' : '' }}>{{ $yStr }}</option>
                                    @endfor
                                </select>
                            </div>
                            <input type="hidden" name="potass_period_last" id="add-c2-last-val" value="{{ $employee->potass_period_last ?: 'Aug-26' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Score POTASS <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="potass_score_last" class="form-control" value="{{ $employee->potass_score_last ?: '106%' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Standar Jabatan <span style="color:#ef4444;">*</span></label>
                            <select name="potass_position_last" class="form-control" required>
                                @php
                                    $curLastPosAdd = $employee->potass_position_last ?: 'MANAGER';
                                @endphp
                                @foreach($posListAdd as $pos)
                                    <option value="{{ $pos }}" {{ strcasecmp(trim($pos), trim($curLastPosAdd)) === 0 ? 'selected' : '' }}>{{ $pos }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                            <select name="potass_category_last" class="form-control" required>
                                <option value="High" {{ ($employee->potass_category_last ?: 'High') === 'High' ? 'selected' : '' }}>High</option>
                                <option value="Average" {{ ($employee->potass_category_last ?: 'High') === 'Average' ? 'selected' : '' }}>Average</option>
                                <option value="Below Average" {{ ($employee->potass_category_last ?: 'High') === 'Below Average' ? 'selected' : '' }}>Below Average</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Assessor <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="potass_assessor_last" class="form-control" value="{{ $employee->potass_assessor_last ?: 'HR Development' }}" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data POTASS (C2)</button>
                </div>
            </form>

            {{-- Form C3: Tambah / Atur HAV 16 Box & Talent Pool --}}
            <form id="form-tambah-c3" class="dynamic-subform" data-section="c3-hav" style="display:none;" action="{{ route('karyawan.talent-snapshot.hav-box.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px; display:flex; align-items:center; gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="#000000" class="bi bi-grid-3x3-gap-fill" viewBox="0 0 16 16">
                        <path d="M1 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 12a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/>
                    </svg>
                    <span>Formulir C3: Atur Posisi HAV 16 Box & Talent Pool</span>
                </div>

                @php
                    $havCalcAdd = $employee->getHavBoxDetails();
                    $matrixMapAdd = \App\Services\TalentCalculatorService::getHavMatrixMap();
                    $flatBoxesAdd = [];
                    foreach($matrixMapAdd as $rKey => $cols) {
                        foreach($cols as $cKey => $cell) {
                            $flatBoxesAdd[$cell['box']] = $cell;
                        }
                    }
                    ksort($flatBoxesAdd);
                @endphp

                <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; padding:10px 14px; margin-bottom:14px; font-size:11.5px; color:#166534;">
                    🎯 <strong>Rekomendasi Rumus HAV (C1 & C6):</strong> {{ $havCalcAdd['box_label'] }} – {{ $havCalcAdd['category'] }} 
                    (Talent Pool: <strong>{{ $havCalcAdd['talent_pool'] }}</strong>)
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Posisi HAV 16 Box <span style="color:#ef4444;">*</span></label>
                        <select name="hav_box_current" id="select-hav-box-add" class="form-control" required>
                            @foreach($flatBoxesAdd as $bNum => $bInfo)
                                <option value="Box {{ $bNum }}" 
                                        data-name="{{ $bInfo['name'] }}" 
                                        data-tp="{{ $bInfo['talent_pool'] }}"
                                        {{ ($employee->hav_box_current ?: $havCalcAdd['box_label']) === 'Box ' . $bNum ? 'selected' : '' }}>
                                    Box {{ $bNum }} – {{ $bInfo['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Talent Pool <span style="color:#ef4444;">*</span></label>
                        <select name="talent_pool_status" id="select-hav-tp-add" class="form-control" required>
                            <option value="YA" {{ ($employee->talent_pool_status ?: $havCalcAdd['talent_pool']) === 'YA' ? 'selected' : '' }}>YA (Masuk Talent Pool)</option>
                            <option value="TIDAK" {{ ($employee->talent_pool_status ?: $havCalcAdd['talent_pool']) === 'TIDAK' ? 'selected' : '' }}>TIDAK</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Label / Kategori Box <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="hav_box_category" id="input-hav-cat-add" class="form-control" value="{{ $employee->hav_box_category ?: $havCalcAdd['category'] }}" required>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Posisi HAV Box (C3)</button>
                </div>
            </form>

            {{-- Form C4: Tambah Kekuatan Utama --}}
            <form id="form-tambah-c4" class="dynamic-subform" data-section="c4-strength" style="display:none;" action="{{ route('karyawan.key-strength.store', ['nik' => $employee->nik]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px; display:flex; align-items:center; gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="#000000" class="bi bi-star-fill" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg>
                    <span>Formulir C4: Tambah Data Kekuatan Utama</span>
                </div>

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
                <div class="form-group">
                    <label class="form-label">Dokumentasi (Foto / Gambar / PDF)</label>
                    <input type="file" name="documentation" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif,.pdf">
                    <small style="display:block; color:#64748b; font-size:11px; margin-top:3px;">Format yang didukung: JPG, PNG, WEBP, GIF, PDF (Maksimal 10MB)</small>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kekuatan Utama (C4)</button>
                </div>
            </form>

            {{-- Form C5: Tambah / Input Flying Risk Assessment --}}
            <form id="form-tambah-c5" class="dynamic-subform" data-section="c5-risk" style="display:none;" action="{{ route('karyawan.talent-snapshot.flying-risk.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px; display:flex; align-items:center; gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="#000000" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg>
                    <span>Formulir C5: Input Flying Risk Assessment (C5flyrisk.png)</span>
                </div>

                @php
                    $riskDetailsAdd = $employee->getFlyingRiskDetails();
                @endphp

                <div class="form-group">
                    <label class="form-label">1. Factor: Career Growth Potential <span style="color:#ef4444;">*</span></label>
                    <select name="flying_risk_career_growth" id="add-c5-growth" class="form-control c5-factor-select-add" required>
                        <option value="No clear advancement path" data-pts="2" {{ $riskDetailsAdd['career_growth'] === 'No clear advancement path' ? 'selected' : '' }}>
                            No clear advancement path (2 Poin)
                        </option>
                        <option value="Some opportunities, but limited" data-pts="1" {{ $riskDetailsAdd['career_growth'] === 'Some opportunities, but limited' ? 'selected' : '' }}>
                            Some opportunities, but limited (1 Poin)
                        </option>
                        <option value="Clear advancement opportunities" data-pts="0" {{ $riskDetailsAdd['career_growth'] === 'Clear advancement opportunities' ? 'selected' : '' }}>
                            Clear advancement opportunities (0 Poin)
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">2. Factor: Job Market Demand for Role <span style="color:#ef4444;">*</span></label>
                    <select name="flying_risk_job_market" id="add-c5-market" class="form-control c5-factor-select-add" required>
                        <option value="High demand for similar roles in industry" data-pts="2" {{ $riskDetailsAdd['job_market'] === 'High demand for similar roles in industry' ? 'selected' : '' }}>
                            High demand for similar roles in industry (2 Poin)
                        </option>
                        <option value="Moderate demand" data-pts="1" {{ $riskDetailsAdd['job_market'] === 'Moderate demand' ? 'selected' : '' }}>
                            Moderate demand (1 Poin)
                        </option>
                        <option value="Low demand" data-pts="0" {{ $riskDetailsAdd['job_market'] === 'Low demand' ? 'selected' : '' }}>
                            Low demand (0 Poin)
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">3. Factor: Compensation Competitiveness <span style="color:#ef4444;">*</span></label>
                    <select name="flying_risk_compensation" id="add-c5-comp" class="form-control c5-factor-select-add" required>
                        <option value="Below industry standard" data-pts="2" {{ $riskDetailsAdd['compensation'] === 'Below industry standard' ? 'selected' : '' }}>
                            Below industry standard (2 Poin)
                        </option>
                        <option value="At industry standard" data-pts="1" {{ $riskDetailsAdd['compensation'] === 'At industry standard' ? 'selected' : '' }}>
                            At industry standard (1 Poin)
                        </option>
                        <option value="Above industry standard" data-pts="0" {{ $riskDetailsAdd['compensation'] === 'Above industry standard' ? 'selected' : '' }}>
                            Above industry standard (0 Poin)
                        </option>
                    </select>
                </div>

                {{-- Live Flying Risk Preview Box --}}
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <span style="font-size:11.5px; font-weight:700; color:#334155;">Hasil Perhitungan Otomatis:</span>
                        <span id="add-c5-preview-badge" class="{{ $riskDetailsAdd['badge_class'] }}" style="font-size:11.5px; padding:3px 12px; font-weight:800;">
                            {{ $riskDetailsAdd['risk_level'] }}
                        </span>
                    </div>
                    <div style="font-size:11px; color:#475569; margin-bottom:6px;">
                        Total Skor: <strong id="add-c5-preview-total" style="color:#0b2545; font-size:13px;">{{ $riskDetailsAdd['total_score'] }}</strong> / 6 Poin
                    </div>
                    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:6px; padding:8px 10px; font-size:11px; color:#1e293b; font-style:italic;">
                        <span style="font-weight:700; font-style:normal; color:#475569; display:block; margin-bottom:2px;">Alasan Utama (Interpretasi):</span>
                        "<span id="add-c5-preview-interpretation">{{ $riskDetailsAdd['interpretation'] }}</span>"
                    </div>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Flying Risk (C5)</button>
                </div>
            </form>

            {{-- Form C6: Tambah Riwayat POTASS dengan 8 Kompetensi Perilaku (C6.xlsx) --}}
            <form id="form-tambah-c6" class="dynamic-subform" data-section="c6-potass" style="display:none;" action="{{ route('karyawan.talent-assessment.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px; display:flex; align-items:center; gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="#000000" class="bi bi-clock-history" viewBox="0 0 16 16">
                        <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/>
                        <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/>
                        <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/>
                    </svg>
                    <span>Formulir C6: Tambah Riwayat POTASS Assessment (C6.xlsx)</span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal / Periode Asesmen <span style="color:#ef4444;">*</span></label>
                        <div style="display:flex; gap:6px; align-items:center;">
                            <select class="form-control period-month-select" id="add-c6-month" style="flex:1;">
                                @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $m)
                                    <option value="{{ $m }}" {{ $m === 'Aug' ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                            <span style="color:#94a3b8; font-weight:700;">-</span>
                            <select class="form-control period-year-select" id="add-c6-year" style="width:85px;">
                                @for($y = 20; $y <= 35; $y++)
                                    @php $yStr = sprintf('%02d', $y); @endphp
                                    <option value="{{ $yStr }}" {{ $yStr === '26' ? 'selected' : '' }}>{{ $yStr }}</option>
                                @endfor
                            </select>
                        </div>
                        <input type="hidden" name="assessment_date" id="add-c6-date-val" value="Aug-26" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Standar Jabatan <span style="color:#ef4444;">*</span></label>
                        <select name="position_standard" class="form-control" required>
                            <option value="">-- Pilih Standar Jabatan --</option>
                            @foreach(\App\Services\TalentCalculatorService::getPositionStandards() as $pos)
                                <option value="{{ $pos }}" {{ $pos === 'MANAGER' ? 'selected' : '' }}>{{ $pos }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assessor / Penilai <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="assessor" class="form-control" value="HR Development" required>
                    </div>
                </div>

                {{-- 8 Input Kompetensi Perilaku (Sesuai C6.xlsx) --}}
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:14px; margin-bottom:14px;">
                    <div style="font-weight:700; color:#0b2545; font-size:12px; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center;">
                        <span>8 Penilaian Kompetensi Perilaku (Bobot Sesuai C6.xlsx):</span>
                        <span style="font-size:11px; color:#64748b; font-weight:400;">Skala nilai: 1 – 5</span>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:10px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">1. Vision & Bus. Sense <span style="color:#0284c7; font-weight:700;">(15%)</span></label>
                            <input type="number" step="0.1" min="1" max="5" name="b1_vision_business" class="form-control c6-calc-input-add" value="4.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">2. Cust. Focus <span style="color:#0284c7; font-weight:700;">(15%)</span></label>
                            <input type="number" step="0.1" min="1" max="5" name="b2_customer_focus" class="form-control c6-calc-input-add" value="4.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">3. Interpers. Skill <span style="color:#0284c7; font-weight:700;">(10%)</span></label>
                            <input type="number" step="0.1" min="1" max="5" name="b3_interpersonal_skill" class="form-control c6-calc-input-add" value="4.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">4. Analysis & Judgment <span style="color:#0284c7; font-weight:700;">(10%)</span></label>
                            <input type="number" step="0.1" min="1" max="5" name="b4_analysis_judgment" class="form-control c6-calc-input-add" value="3.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">5. Plan. & Drvg Act. <span style="color:#0284c7; font-weight:700;">(10%)</span></label>
                            <input type="number" step="0.1" min="1" max="5" name="b5_planning_driving" class="form-control c6-calc-input-add" value="3.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">6. Leading & Motivating <span style="color:#0284c7; font-weight:700;">(15%)</span></label>
                            <input type="number" step="0.1" min="1" max="5" name="b6_leading_motivating" class="form-control c6-calc-input-add" value="4.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">7. Teamwork <span style="color:#0284c7; font-weight:700;">(10%)</span></label>
                            <input type="number" step="0.1" min="1" max="5" name="b7_teamwork" class="form-control c6-calc-input-add" value="4.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">8. Drive, Courg & Integ. <span style="color:#0284c7; font-weight:700;">(15%)</span></label>
                            <input type="number" step="0.1" min="1" max="5" name="b8_drive_courage_integrity" class="form-control c6-calc-input-add" value="4.0" required>
                        </div>
                    </div>
                </div>

                {{-- Live Calculation Preview Box --}}
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:12px; margin-bottom:14px;">
                    <div style="font-weight:700; color:#1e40af; font-size:11.5px; margin-bottom:6px;">
                        Hasil Perhitungan Otomatis (C6.xlsx):
                    </div>
                    <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:8px; text-align:center;">
                        <div style="background:#ffffff; border:1px solid #dbeafe; border-radius:6px; padding:6px;">
                            <small style="color:#64748b; font-size:10px; display:block;">Skor Tertimbang</small>
                            <strong id="add-c6-preview-weighted" style="font-size:13px; color:#0b2545;">3.70</strong>
                        </div>
                        <div style="background:#ffffff; border:1px solid #dbeafe; border-radius:6px; padding:6px;">
                            <small style="color:#64748b; font-size:10px; display:block;">Skor POTASS (%)</small>
                            <strong id="add-c6-preview-percentage" style="font-size:13px; color:#16a34a;">74.0%</strong>
                        </div>
                        <div style="background:#ffffff; border:1px solid #dbeafe; border-radius:6px; padding:6px;">
                            <small style="color:#64748b; font-size:10px; display:block;">Kolom HAV</small>
                            <strong id="add-c6-preview-kolom" style="font-size:13px; color:#0284c7;">C3</strong>
                        </div>
                        <div style="background:#ffffff; border:1px solid #dbeafe; border-radius:6px; padding:6px;">
                            <small style="color:#64748b; font-size:10px; display:block;">Kategori</small>
                            <strong id="add-c6-preview-category" style="font-size:13px; color:#16a34a;">High</strong>
                        </div>
                    </div>
                    <div style="font-size:10.5px; color:#1d4ed8; margin-top:8px;">
                        ✨ <em>Hasil ini otomatis disimpan ke riwayat C6, menjadi asesmen terakhir di C2, dan memperbarui kuadran C3 (16 HAV Box).</em>
                    </div>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:10px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan & Sinkronkan C6 ke C2 & C3</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 1.0 Modal KELOLA & INPUT PERFORMANCE APPRAISAL TAHUNAN --}}
<div id="modal-kelola-performance" class="modal-backdrop" style="z-index: 2500;">
    <div class="modal-card modal-lg" style="box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45); border: 1px solid #cbd5e1;">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                Tambah & Riwayat Data Performance Appraisal
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
            {{-- Form Tambah / Perbarui Nilai Per Tahun --}}
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:16px; margin-bottom:20px;">
                <div style="font-weight:700; color:#0b2545; font-size:12.5px; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>Form Input / Perbarui Nilai Performance</span>
                </div>
                
                <form action="{{ route('karyawan.performance-appraisal.store', ['nik' => $employee->nik]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="{{ $tab ?? 'talent-snapshot' }}">

                    <div style="display:grid; grid-template-columns: 160px 140px 1fr; gap:12px; align-items:flex-start;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-weight:700;">Tahun <span style="color:#ef4444;">*</span></label>
                            @php
                                $suggestedYear = (int)($employee->performanceAppraisals->max('year') ?: 2026) + 1;
                            @endphp
                            <select name="year" id="input-modal-perf-year" class="form-control" required style="font-weight:700;">
                                @for($y = 2035; $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $y == $suggestedYear ? 'selected' : '' }}>
                                        {{ $y }} (FY{{ substr((string)$y, -2) }})
                                    </option>
                                @endfor
                            </select>
                            <small style="color:#64748b; font-size:10.5px; display:block; margin-top:3px;">Pilih tahun penilaian</small>
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-weight:700;">Nilai / Rating <span style="color:#ef4444;">*</span></label>
                            <select name="rating" id="input-modal-perf-rating" class="form-control" required style="font-weight:700;">
                                @php
                                    $perfRatingsList = ['S', 'AS', 'A', 'B+', 'B', '-', 'C', 'K'];
                                @endphp
                                @foreach($perfRatingsList as $rVal)
                                    <option value="{{ $rVal }}" {{ $rVal === 'A' ? 'selected' : '' }}>{{ $rVal }}</option>
                                @endforeach
                            </select>
                            <small style="color:#64748b; font-size:10.5px; display:block; margin-top:3px;">Pilih predikat kinerja</small>
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-weight:700;">Catatan / Keterangan Penilaian</label>
                            <input type="text" name="notes" id="input-modal-perf-notes" class="form-control" placeholder="Contoh: Evaluasi tahunan pencapaian target..." value="{{ $employee->performance_notes }}">
                            <small style="color:#64748b; font-size:10.5px; display:block; margin-top:3px;">Opsional</small>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:14px;">
                        <button type="submit" class="btn btn-primary" style="padding:6px 16px; font-size:12px; display:inline-flex; align-items:center; gap:6px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            <span>Simpan Nilai Tahun Ini</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Riwayat Data yang Tersimpan --}}
            @php
                $appraisals = $employee->performanceAppraisals()->orderBy('year', 'desc')->get();
                $threeYears = $employee->getThreeYearPerformances();
                $displayedYearList = $threeYears->pluck('year')->toArray();
            @endphp
            <div>
                <div style="font-weight:700; color:#0b2545; font-size:12.5px; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center;">
                    <span>Daftar Riwayat Data Performance ({{ $appraisals->count() }} Tahun)</span>
                    <span style="font-size:11px; font-weight:500; color:#64748b;">
                        Tahun aktif di frontend: 
                        <strong style="color:#0b2545;">{{ implode(', ', $displayedYearList) }}</strong>
                    </span>
                </div>

                <table class="table-custom" style="font-size:11.5px; width:100%;">
                    <thead>
                        <tr style="background:#f1f5f9;">
                            <th style="width:16%;">Tahun</th>
                            <th class="text-center" style="width:14%;">Rating</th>
                            <th class="text-center" style="width:12%;">Skor / Poin</th>
                            <th style="width:20%;">Status</th>
                            <th>Catatan</th>
                            <th class="text-center" style="width:16%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appraisals as $appr)
                            @php
                                $isDisplayed = in_array($appr->year, $displayedYearList);
                                $pts = \App\Services\TalentCalculatorService::ratingToPoints($appr->rating);
                            @endphp
                            <tr>
                                <td style="font-weight:700; color:#0b2545;">
                                    {{ $appr->year }} <span style="color:#64748b; font-weight:500;">(FY{{ substr((string)$appr->year, -2) }})</span>
                                </td>
                                <td class="text-center">
                                    <span class="{{ $isDisplayed && $appr->year == max($displayedYearList) ? 'badge-green' : 'badge-table-blue' }}" style="padding:2px 8px; font-weight:700; font-size:11px;">
                                        {{ $appr->rating }}
                                    </span>
                                </td>
                                <td class="text-center" style="font-weight:800; color:#0284c7; font-size:12px;">
                                    {{ $pts }}
                                </td>
                                <td>
                                    @if($isDisplayed)
                                        <span style="color:#15803d; font-weight:600; display:inline-flex; align-items:center; gap:4px; font-size:11px;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            Aktif (3 Tahun Terakhir)
                                        </span>
                                    @else
                                        <span style="color:#64748b; font-size:11px;">Arsip Historis</span>
                                    @endif
                                </td>
                                <td style="color:#475569;">
                                    {{ $appr->notes ?: '-' }}
                                </td>
                                <td class="text-center">
                                    <div style="display:flex; justify-content:center; gap:6px;">
                                        <button type="button" class="btn-mini btn-mini-primary" title="Edit tahun ini" 
                                            onclick="prefillPerfModal('{{ $appr->year }}', '{{ $appr->rating }}', '{{ addslashes($appr->notes ?? '') }}')">
                                            Edit
                                        </button>
                                        <form action="{{ route('karyawan.performance-appraisal.destroy', ['nik' => $employee->nik, 'id' => $appr->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data performance tahun {{ $appr->year }}?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="tab" value="{{ $tab ?? 'profil-individu' }}">
                                            <button type="submit" class="btn-mini btn-mini-danger" title="Hapus tahun ini">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:16px; color:#64748b;">
                                    Belum ada data performance tersimpan. Silakan isi form di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal-footer" style="padding:12px 16px;">
            <button type="button" class="btn btn-outline" data-modal-close>Tutup</button>
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

            {{-- Form C1: Performance 3 Tahun Terakhir Berdasarkan C1.xlsx --}}
            <form id="form-edit-c1" class="dynamic-subform" data-section="edit-c1-perf" action="{{ route('karyawan.talent-snapshot.performance.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <input type="hidden" name="tab" value="{{ $tab ?? 'talent-snapshot' }}">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <div style="font-weight:700; color:#0b2545; font-size:12.5px;">Formulir C1: Performance Appraisal 3 Tahun Terakhir</div>
                    <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-kelola-performance" style="font-size:11px; display:inline-flex; align-items:center; gap:5px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Tambah Performance</span>
                    </button>
                </div>

                @php
                    $threeYearItems = $employee->getThreeYearPerformances();
                    $ratingsList = ['S', 'AS', 'A', 'B+', 'B', '-', 'C', 'K'];
                @endphp
                <div class="form-row" style="grid-template-columns: repeat(3, 1fr); gap:12px;">
                    @foreach($threeYearItems as $idx => $perfItem)
                        <div class="form-group">
                            <label class="form-label">Tahun {{ $perfItem['label'] }} ({{ $perfItem['year'] }}) <span style="color:#ef4444;">*</span></label>
                            <input type="hidden" name="years[{{ $idx }}]" value="{{ $perfItem['year'] }}">
                            <select name="ratings[{{ $idx }}]" id="input-c1-yr-{{ $perfItem['year'] }}" class="form-control c1-rating-select" required>
                                @foreach($ratingsList as $val)
                                    <option value="{{ $val }}" {{ strtoupper(trim($perfItem['rating'])) === $val ? 'selected' : '' }}>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <input type="hidden" name="performance_current" id="input-c1-current" value="{{ $employee->performance_current ?: 'A' }}">

                {{-- Live C1 Calculation Summary --}}
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:12px; margin-bottom:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; background:#ffffff; border:1px solid #dbeafe; border-radius:6px; padding:10px 14px;">
                        <div>
                            <span style="font-size:11px; color:#64748b; display:block;">Jumlah Skor:</span>
                            <strong id="c1-preview-total" style="font-size:16px; color:#0b2545;">17</strong>
                        </div>
                        <div style="text-align:right;">
                            <span style="font-size:11px; color:#64748b; display:block;">Hasil:</span>
                            <span id="c1-preview-baris" class="badge-gold" style="font-size:13px; padding:3px 12px; font-weight:800;">R2</span>
                        </div>
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
                
                <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:6px; padding:8px 12px; font-size:11px; color:#0369a1; margin-bottom:14px;">
                    🔗 <strong>Terhubung Otomatis dengan C6:</strong> Ketika Anda menambah atau mengedit data riwayat C6 (8 Kompetensi Perilaku), asesmen terakhir pada C2 ini akan terisi otomatis. Anda juga dapat menyuntingnya manual di bawah jika diperlukan.
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:12px;">
                    {{-- Kolom Sebelumnya --}}
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:12px;">
                        <div style="font-weight:700; font-size:12px; color:#475569; margin-bottom:8px;">ASESMEN SEBELUMNYA</div>
                        <div class="form-group">
                            <label class="form-label">Periode</label>
                            @php
                                $prevParts = explode('-', $employee->potass_period_prev ?: 'Aug-24');
                                $prevM = $prevParts[0] ?? 'Aug';
                                $prevY = substr($prevParts[1] ?? '24', -2);
                            @endphp
                            <div style="display:flex; gap:6px; align-items:center;">
                                <select class="form-control period-month-select" id="edit-c2-prev-month" style="flex:1;">
                                    @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $m)
                                        <option value="{{ $m }}" {{ $m === $prevM ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                                <span style="color:#94a3b8; font-weight:700;">-</span>
                                <select class="form-control period-year-select" id="edit-c2-prev-year" style="width:85px;">
                                    @for($y = 20; $y <= 35; $y++)
                                        @php $yStr = sprintf('%02d', $y); @endphp
                                        <option value="{{ $yStr }}" {{ $yStr === $prevY ? 'selected' : '' }}>{{ $yStr }}</option>
                                    @endfor
                                </select>
                            </div>
                            <input type="hidden" name="potass_period_prev" id="edit-c2-prev-val" value="{{ $employee->potass_period_prev ?: 'Aug-24' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Score POTASS</label>
                            <input type="text" name="potass_score_prev" class="form-control" value="{{ $employee->potass_score_prev ?: '94%' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Standar Jabatan</label>
                            <select name="potass_position_prev" class="form-control" required>
                                @php
                                    $curPrevPos = $employee->potass_position_prev ?: 'SECTION HEAD';
                                    $posList = \App\Services\TalentCalculatorService::getPositionStandards();
                                    $hasMatchPrev = false;
                                    foreach($posList as $p) {
                                        if (strcasecmp(trim($p), trim($curPrevPos)) === 0) {
                                            $hasMatchPrev = true;
                                            break;
                                        }
                                    }
                                @endphp
                                @if(!$hasMatchPrev && $curPrevPos)
                                    <option value="{{ $curPrevPos }}" selected>{{ $curPrevPos }}</option>
                                @endif
                                @foreach($posList as $pos)
                                    <option value="{{ $pos }}" {{ strcasecmp(trim($pos), trim($curPrevPos)) === 0 ? 'selected' : '' }}>{{ $pos }}</option>
                                @endforeach
                            </select>
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
                        <div style="font-weight:700; font-size:12px; color:#1d4ed8; margin-bottom:8px;">ASESMEN TERAKHIR (Hasil dari C6)</div>
                        <div class="form-group">
                            <label class="form-label">Periode</label>
                            @php
                                $lastParts = explode('-', $employee->potass_period_last ?: 'Aug-26');
                                $lastM = $lastParts[0] ?? 'Aug';
                                $lastY = substr($lastParts[1] ?? '26', -2);
                            @endphp
                            <div style="display:flex; gap:6px; align-items:center;">
                                <select class="form-control period-month-select" id="edit-c2-last-month" style="flex:1;">
                                    @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $m)
                                        <option value="{{ $m }}" {{ $m === $lastM ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                                <span style="color:#94a3b8; font-weight:700;">-</span>
                                <select class="form-control period-year-select" id="edit-c2-last-year" style="width:85px;">
                                    @for($y = 20; $y <= 35; $y++)
                                        @php $yStr = sprintf('%02d', $y); @endphp
                                        <option value="{{ $yStr }}" {{ $yStr === $lastY ? 'selected' : '' }}>{{ $yStr }}</option>
                                    @endfor
                                </select>
                            </div>
                            <input type="hidden" name="potass_period_last" id="edit-c2-last-val" value="{{ $employee->potass_period_last ?: 'Aug-26' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Score POTASS</label>
                            <input type="text" name="potass_score_last" class="form-control" value="{{ $employee->potass_score_last ?: '106%' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Standar Jabatan</label>
                            <select name="potass_position_last" class="form-control" required>
                                @php
                                    $curLastPos = $employee->potass_position_last ?: 'MANAGER';
                                    $posList = \App\Services\TalentCalculatorService::getPositionStandards();
                                    $hasMatchLast = false;
                                    foreach($posList as $p) {
                                        if (strcasecmp(trim($p), trim($curLastPos)) === 0) {
                                            $hasMatchLast = true;
                                            break;
                                        }
                                    }
                                @endphp
                                @if(!$hasMatchLast && $curLastPos)
                                    <option value="{{ $curLastPos }}" selected>{{ $curLastPos }}</option>
                                @endif
                                @foreach($posList as $pos)
                                    <option value="{{ $pos }}" {{ strcasecmp(trim($pos), trim($curLastPos)) === 0 ? 'selected' : '' }}>{{ $pos }}</option>
                                @endforeach
                            </select>
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
                
                @php
                    $havCalc = $employee->getHavBoxDetails();
                    $matrixMap = \App\Services\TalentCalculatorService::getHavMatrixMap();
                    $flatBoxes = [];
                    foreach($matrixMap as $rKey => $cols) {
                        foreach($cols as $cKey => $cell) {
                            $flatBoxes[$cell['box']] = $cell;
                        }
                    }
                    ksort($flatBoxes);
                @endphp
                <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; padding:10px 14px; margin-bottom:14px; font-size:11.5px; color:#166534;">
                    🎯 <strong>Rekomendasi Rumus HAV (C1 & C6):</strong> {{ $havCalc['box_label'] }} – {{ $havCalc['category'] }} 
                    (Talent Pool: <strong>{{ $havCalc['talent_pool'] }}</strong>)
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Posisi HAV 16 Box <span style="color:#ef4444;">*</span></label>
                        <select name="hav_box_current" id="select-hav-box-edit" class="form-control" required>
                            @foreach($flatBoxes as $bNum => $bInfo)
                                <option value="Box {{ $bNum }}" 
                                        data-name="{{ $bInfo['name'] }}" 
                                        data-tp="{{ $bInfo['talent_pool'] }}"
                                        {{ ($employee->hav_box_current ?: $havCalc['box_label']) === 'Box ' . $bNum ? 'selected' : '' }}>
                                    Box {{ $bNum }} – {{ $bInfo['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Talent Pool <span style="color:#ef4444;">*</span></label>
                        <select name="talent_pool_status" id="select-hav-tp-edit" class="form-control" required>
                            <option value="YA" {{ ($employee->talent_pool_status ?: $havCalc['talent_pool']) === 'YA' ? 'selected' : '' }}>YA (Masuk Talent Pool)</option>
                            <option value="TIDAK" {{ ($employee->talent_pool_status ?: $havCalc['talent_pool']) === 'TIDAK' ? 'selected' : '' }}>TIDAK</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Label / Kategori Box <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="hav_box_category" id="input-hav-cat-edit" class="form-control" value="{{ $employee->hav_box_category ?: $havCalc['category'] }}" required>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Data HAV Box (C3)</button>
                </div>
            </form>

            {{-- Form C4: Edit Kekuatan Utama (Pilih item yang diedit) --}}
            <form id="form-edit-c4" class="dynamic-subform" data-section="edit-c4-strength" style="display:none;" action="" method="POST" enctype="multipart/form-data">
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
                                    data-source="{{ $st->source }}"
                                    data-doc="{{ $st->documentation ?? '' }}"
                                    data-doc-url="{{ $st->documentation ? asset($st->documentation) : '' }}"
                                    data-doc-ext="{{ $st->file_extension ?? '' }}">
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
                <div class="form-group">
                    <label class="form-label">Dokumentasi (Foto / Gambar / PDF)</label>
                    <div id="edit-c4-doc-current-wrap" style="margin-bottom:8px; display:none; padding:8px 10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px;">
                        <div style="font-size:11px; color:#64748b; margin-bottom:4px; font-weight:600;">File dokumentasi saat ini:</div>
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                            <a id="edit-c4-doc-link" href="#" target="_blank" style="font-size:12px; color:#2563eb; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:5px;">
                                <span>Lihat Dokumen</span>
                            </a>
                            <label style="font-size:11px; color:#dc2626; margin:0; display:inline-flex; align-items:center; gap:4px; cursor:pointer;">
                                <input type="checkbox" name="remove_documentation" id="edit-c4-remove-doc" value="1">
                                <span>Hapus File</span>
                            </label>
                        </div>
                    </div>
                    <input type="file" id="edit-c4-doc" name="documentation" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif,.pdf">
                    <small style="display:block; color:#64748b; font-size:11px; margin-top:3px;">Biarkan kosong jika tidak ingin mengubah dokumentasi. Format: JPG, PNG, WEBP, GIF, PDF (Maks. 10MB)</small>
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" id="btn-delete-c4-from-edit" class="btn btn-danger" style="background:#dc2626; color:#ffffff; font-size:12px; padding:7px 14px; display:inline-flex; align-items:center; gap:6px; border:none; border-radius:6px; cursor:pointer;" title="Hapus item kekuatan yang dipilih">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        <span>Hapus Kekuatan Ini</span>
                    </button>
                    <div style="display:flex; gap:8px;">
                        <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                        <button type="submit" class="btn btn-primary">Perbarui Kekuatan Utama (C4)</button>
                    </div>
                </div>
            </form>

            {{-- Hidden Form Delete C4 Direct from Edit --}}
            <form id="form-delete-c4-direct" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>

            {{-- Form C5: Flying Risk Assessment Berdasarkan C5flyrisk.png --}}
            <form id="form-edit-c5" class="dynamic-subform" data-section="edit-c5-risk" style="display:none;" action="{{ route('karyawan.talent-snapshot.flying-risk.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir C5: Flying Risk Assessment (C5flyrisk.png)</div>
                
                @php
                    $riskDetails = $employee->getFlyingRiskDetails();
                @endphp

                <div class="form-group">
                    <label class="form-label">1. Factor: Career Growth Potential <span style="color:#ef4444;">*</span></label>
                    <select name="flying_risk_career_growth" id="edit-c5-growth" class="form-control c5-factor-select" required>
                        <option value="No clear advancement path" data-pts="2" {{ $riskDetails['career_growth'] === 'No clear advancement path' ? 'selected' : '' }}>
                            No clear advancement path (2 Poin)
                        </option>
                        <option value="Some opportunities, but limited" data-pts="1" {{ $riskDetails['career_growth'] === 'Some opportunities, but limited' ? 'selected' : '' }}>
                            Some opportunities, but limited (1 Poin)
                        </option>
                        <option value="Clear advancement opportunities" data-pts="0" {{ $riskDetails['career_growth'] === 'Clear advancement opportunities' ? 'selected' : '' }}>
                            Clear advancement opportunities (0 Poin)
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">2. Factor: Job Market Demand for Role <span style="color:#ef4444;">*</span></label>
                    <select name="flying_risk_job_market" id="edit-c5-market" class="form-control c5-factor-select" required>
                        <option value="High demand for similar roles in industry" data-pts="2" {{ $riskDetails['job_market'] === 'High demand for similar roles in industry' ? 'selected' : '' }}>
                            High demand for similar roles in industry (2 Poin)
                        </option>
                        <option value="Moderate demand" data-pts="1" {{ $riskDetails['job_market'] === 'Moderate demand' ? 'selected' : '' }}>
                            Moderate demand (1 Poin)
                        </option>
                        <option value="Low demand" data-pts="0" {{ $riskDetails['job_market'] === 'Low demand' ? 'selected' : '' }}>
                            Low demand (0 Poin)
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">3. Factor: Compensation Competitiveness <span style="color:#ef4444;">*</span></label>
                    <select name="flying_risk_compensation" id="edit-c5-comp" class="form-control c5-factor-select" required>
                        <option value="Below industry standard" data-pts="2" {{ $riskDetails['compensation'] === 'Below industry standard' ? 'selected' : '' }}>
                            Below industry standard (2 Poin)
                        </option>
                        <option value="At industry standard" data-pts="1" {{ $riskDetails['compensation'] === 'At industry standard' ? 'selected' : '' }}>
                            At industry standard (1 Poin)
                        </option>
                        <option value="Above industry standard" data-pts="0" {{ $riskDetails['compensation'] === 'Above industry standard' ? 'selected' : '' }}>
                            Above industry standard (0 Poin)
                        </option>
                    </select>
                </div>

                {{-- Live Flying Risk Preview Box --}}
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <span style="font-size:11.5px; font-weight:700; color:#334155;">Hasil Perhitungan Otomatis:</span>
                        <span id="c5-preview-badge" class="{{ $riskDetails['badge_class'] }}" style="font-size:11.5px; padding:3px 12px; font-weight:800;">
                            {{ $riskDetails['risk_level'] }}
                        </span>
                    </div>
                    <div style="font-size:11px; color:#475569; margin-bottom:6px;">
                        Total Skor: <strong id="c5-preview-total" style="color:#0b2545; font-size:13px;">{{ $riskDetails['total_score'] }}</strong> / 6 Poin
                    </div>
                    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:6px; padding:8px 10px; font-size:11px; color:#1e293b; font-style:italic;">
                        <span style="font-weight:700; font-style:normal; color:#475569; display:block; margin-bottom:2px;">Alasan Utama (Interpretasi):</span>
                        "<span id="c5-preview-interpretation">{{ $riskDetails['interpretation'] }}</span>"
                    </div>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Flying Risk (C5)</button>
                </div>
            </form>

            {{-- Form C6: Edit Riwayat POTASS dengan 8 Kompetensi Perilaku --}}
            <form id="form-edit-c6" class="dynamic-subform" data-section="edit-c6-history" style="display:none;" action="" method="POST">
                @csrf
                @method('PUT')
                <div style="font-weight:700; color:#0b2545; margin-bottom:12px; font-size:12.5px;">Formulir C6: Edit Riwayat POTASS Assessment (C6.xlsx)</div>
                <div class="form-group">
                    <label class="form-label">Pilih Baris Riwayat yang Ingin Diedit:</label>
                    <select id="select-edit-c6-item" class="form-control">
                        @foreach($employee->getSortedTalentAssessments() as $ta)
                            <option value="{{ $ta->id }}" 
                                    data-date="{{ $ta->assessment_date }}" 
                                    data-pos="{{ $ta->position_standard }}" 
                                    data-score="{{ $ta->potass_score }}" 
                                    data-cat="{{ $ta->category }}" 
                                    data-assessor="{{ $ta->assessor }}"
                                    data-b1="{{ $ta->b1_vision_business ?? 4.0 }}"
                                    data-b2="{{ $ta->b2_customer_focus ?? 4.0 }}"
                                    data-b3="{{ $ta->b3_interpersonal_skill ?? 4.0 }}"
                                    data-b4="{{ $ta->b4_analysis_judgment ?? 3.0 }}"
                                    data-b5="{{ $ta->b5_planning_driving ?? 3.0 }}"
                                    data-b6="{{ $ta->b6_leading_motivating ?? 4.0 }}"
                                    data-b7="{{ $ta->b7_teamwork ?? 4.0 }}"
                                    data-b8="{{ $ta->b8_drive_courage_integrity ?? 4.0 }}">
                                {{ $ta->assessment_date }} - {{ $ta->position_standard }} ({{ $ta->potass_score }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Asesmen <span style="color:#ef4444;">*</span></label>
                        <div style="display:flex; gap:6px; align-items:center;">
                            <select class="form-control period-month-select" id="edit-c6-month" style="flex:1;">
                                @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                            <span style="color:#94a3b8; font-weight:700;">-</span>
                            <select class="form-control period-year-select" id="edit-c6-year" style="width:85px;">
                                @for($y = 20; $y <= 35; $y++)
                                    @php $yStr = sprintf('%02d', $y); @endphp
                                    <option value="{{ $yStr }}">{{ $yStr }}</option>
                                @endfor
                            </select>
                        </div>
                        <input type="hidden" id="edit-c6-date" name="assessment_date" value="" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Standar Jabatan <span style="color:#ef4444;">*</span></label>
                        <select id="edit-c6-pos" name="position_standard" class="form-control" required>
                            <option value="">-- Pilih Standar Jabatan --</option>
                            @foreach(\App\Services\TalentCalculatorService::getPositionStandards() as $pos)
                                <option value="{{ $pos }}">{{ $pos }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assessor <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-c6-assessor" name="assessor" class="form-control" required>
                    </div>
                </div>

                {{-- 8 Input Kompetensi Perilaku --}}
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:12px;">
                    <div style="font-weight:700; color:#0b2545; font-size:11.5px; margin-bottom:8px;">
                        8 Penilaian Kompetensi Perilaku (Bobot Sesuai C6.xlsx):
                    </div>
                    <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:10px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">1. Vision & Bus. Sense (15%)</label>
                            <input type="number" step="0.1" min="1" max="5" id="edit-c6-b1" name="b1_vision_business" class="form-control c6-calc-input-edit" value="4.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">2. Cust. Focus (15%)</label>
                            <input type="number" step="0.1" min="1" max="5" id="edit-c6-b2" name="b2_customer_focus" class="form-control c6-calc-input-edit" value="4.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">3. Interpers. Skill (10%)</label>
                            <input type="number" step="0.1" min="1" max="5" id="edit-c6-b3" name="b3_interpersonal_skill" class="form-control c6-calc-input-edit" value="4.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">4. Analysis & Judgment (10%)</label>
                            <input type="number" step="0.1" min="1" max="5" id="edit-c6-b4" name="b4_analysis_judgment" class="form-control c6-calc-input-edit" value="3.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">5. Plan. & Drvg Act. (10%)</label>
                            <input type="number" step="0.1" min="1" max="5" id="edit-c6-b5" name="b5_planning_driving" class="form-control c6-calc-input-edit" value="3.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">6. Leading & Motivating (15%)</label>
                            <input type="number" step="0.1" min="1" max="5" id="edit-c6-b6" name="b6_leading_motivating" class="form-control c6-calc-input-edit" value="4.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">7. Teamwork (10%)</label>
                            <input type="number" step="0.1" min="1" max="5" id="edit-c6-b7" name="b7_teamwork" class="form-control c6-calc-input-edit" value="4.0" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">8. Drive, Courg & Integ. (15%)</label>
                            <input type="number" step="0.1" min="1" max="5" id="edit-c6-b8" name="b8_drive_courage_integrity" class="form-control c6-calc-input-edit" value="4.0" required>
                        </div>
                    </div>
                </div>

                {{-- Live Edit C6 Calculation Box --}}
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:12px; margin-bottom:14px;">
                    <div style="font-weight:700; color:#1e40af; font-size:11.5px; margin-bottom:6px;">
                        Hasil Perhitungan Otomatis:
                    </div>
                    <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:8px; text-align:center;">
                        <div style="background:#ffffff; border:1px solid #dbeafe; border-radius:6px; padding:6px;">
                            <small style="color:#64748b; font-size:10px; display:block;">Skor Tertimbang</small>
                            <strong id="edit-c6-preview-weighted" style="font-size:13px; color:#0b2545;">3.70</strong>
                        </div>
                        <div style="background:#ffffff; border:1px solid #dbeafe; border-radius:6px; padding:6px;">
                            <small style="color:#64748b; font-size:10px; display:block;">Skor POTASS (%)</small>
                            <strong id="edit-c6-preview-percentage" style="font-size:13px; color:#16a34a;">74.0%</strong>
                        </div>
                        <div style="background:#ffffff; border:1px solid #dbeafe; border-radius:6px; padding:6px;">
                            <small style="color:#64748b; font-size:10px; display:block;">Kolom HAV</small>
                            <strong id="edit-c6-preview-kolom" style="font-size:13px; color:#0284c7;">C3</strong>
                        </div>
                        <div style="background:#ffffff; border:1px solid #dbeafe; border-radius:6px; padding:6px;">
                            <small style="color:#64748b; font-size:10px; display:block;">Kategori</small>
                            <strong id="edit-c6-preview-category" style="font-size:13px; color:#16a34a;">High</strong>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" id="btn-delete-c6-from-edit" class="btn btn-danger" style="background:#dc2626; color:#ffffff; font-size:12px; padding:7px 14px; display:inline-flex; align-items:center; gap:6px; border:none; border-radius:6px; cursor:pointer;" title="Hapus riwayat asesmen yang dipilih">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        <span>Hapus Riwayat Ini</span>
                    </button>
                    <div style="display:flex; gap:8px;">
                        <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                        <button type="submit" class="btn btn-primary">Perbarui & Sinkronkan C6 ke C2 & C3</button>
                    </div>
                </div>
            </form>

            {{-- Hidden Form Delete C6 Direct from Edit --}}
            <form id="form-delete-c6-direct" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
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
                        @foreach($employee->getSortedTalentAssessments() as $ta)
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
                    @foreach($employee->getSortedTalentAssessments() as $ta)
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
                        <select name="job_class" id="add-d2-jobclass" class="form-control select-jobclass" required>
                            <option value="">-- Pilih Job Class --</option>
                            @foreach(\App\Services\TalentCalculatorService::getJobClassList() as $kj)
                                <option value="{{ $kj }}">{{ $kj }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grade <span style="color:#ef4444;">*</span></label>
                        <select name="grade" id="add-d2-grade" class="form-control select-grade" required>
                            <option value="">-- Pilih Grade --</option>
                        </select>
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
                        <select id="edit-d2-class" name="job_class" class="form-control select-jobclass" required>
                            <option value="">-- Pilih Job Class --</option>
                            @foreach(\App\Services\TalentCalculatorService::getJobClassList() as $kj)
                                <option value="{{ $kj }}">{{ $kj }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grade <span style="color:#ef4444;">*</span></label>
                        <select id="edit-d2-grade" name="grade" class="form-control select-grade" required>
                            <option value="">-- Pilih Grade --</option>
                        </select>
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
                    <option value="gap-detail">Detail Baris Gap Kompetensi Baru (B. Manajerial / C. Technical)</option>
                    <option value="gap-target">A. Posisi Target & Metode Asesmen Gap</option>
                </select>
            </div>

            {{-- Form Gap Detail --}}
            <form id="form-tambah-gap-detail" class="dynamic-subform" data-section="gap-detail" action="{{ route('karyawan.competency-gap.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group" style="flex:2;">
                        <label class="form-label">Nama Kompetensi <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="competency" class="form-control" placeholder="Contoh: Digital Transformation / PLC Automation" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Kategori Kompetensi <span style="color:#ef4444;">*</span></label>
                        <select name="competency_type" id="tambah-gap-type" class="form-control" required>
                            <option value="Manajerial">B. Manajerial</option>
                            <option value="Technical">C. Technical</option>
                        </select>
                    </div>
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
                        <label class="form-label">Target Level (1 - 5) <span style="color:#ef4444;">*</span></label>
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
                    <label class="form-label">Deskripsi Target Level</label>
                    <input type="text" name="standard_desc" class="form-control" placeholder="Deskripsi tuntutan target level posisi target">
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
                    <option value="edit-gap-detail">Detail Baris Gap Kompetensi (B. Manajerial / C. Technical)</option>
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
                                    data-type="{{ $gap->competency_type ?: 'Manajerial' }}"
                                    data-curr="{{ $gap->current_level }}"
                                    data-currdesc="{{ $gap->current_desc }}"
                                    data-std="{{ $gap->standard_level }}"
                                    data-stddesc="{{ $gap->standard_desc }}"
                                    data-improvement="{{ $gap->expected_improvement }}">
                                [{{ $gap->competency_type ?: 'Manajerial' }}] {{ $gap->competency }} (Gap: {{ $gap->gap }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex:2;">
                        <label class="form-label">Nama Kompetensi <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-gap-comp" name="competency" class="form-control" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Kategori Kompetensi <span style="color:#ef4444;">*</span></label>
                        <select id="edit-gap-type" name="competency_type" class="form-control" required>
                            <option value="Manajerial">B. Manajerial</option>
                            <option value="Technical">C. Technical</option>
                        </select>
                    </div>
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
                        <label class="form-label">Target Level (1 - 5) <span style="color:#ef4444;">*</span></label>
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
                    <label class="form-label">Deskripsi Target Level</label>
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
                    <option value="del-gap-detail">Detail Baris Gap Kompetensi (B. Manajerial / C. Technical)</option>
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
                                [{{ $gap->competency_type ?: 'Manajerial' }}] {{ $gap->competency }} (Gap: {{ $gap->gap }})
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
                    <option value="idp-action">B/C. Detail Baris Rencana Aksi (Action Plan)</option>
                    <option value="idp-summary">A. Ringkasan Kesiapan & Incumbent</option>
                </select>
            </div>

            {{-- Form IDP Action Plan --}}
            <form id="form-tambah-idp-action" class="dynamic-subform" data-section="idp-action" action="{{ route('karyawan.idp-action-plan.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Kategori Rencana Pengembangan <span style="color:#ef4444;">*</span></label>
                    <select name="competency_type" id="tambah-idp-competency-type" class="form-control" required>
                        <option value="Manajerial">B. Rencana Pengembangan Manajerial</option>
                        <option value="Technical">C. Rencana Pengembangan Technical</option>
                    </select>
                </div>

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
                    <option value="edit-idp-action">B/C. Detail Baris Rencana Aksi (Action Plan)</option>
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
                                    data-type="{{ $plan->competency_type ?? 'Manajerial' }}"
                                    data-goal="{{ $plan->specific_goal }}"
                                    data-methods="{{ $plan->development_methods }}"
                                    data-program="{{ $plan->activity_program }}"
                                    data-pic="{{ $plan->pic_supporter }}"
                                    data-start="{{ $plan->start_date }}"
                                    data-end="{{ $plan->end_date }}"
                                    data-indicator="{{ $plan->success_indicator }}"
                                    data-status="{{ $plan->status }}"
                                    data-progress="{{ $plan->progress_percent }}">
                                #{{ $plan->order_no }} - {{ $plan->competency }} [{{ $plan->competency_type ?? 'Manajerial' }}] ({{ $plan->status }} - {{ $plan->progress_percent }}%)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori Rencana Pengembangan <span style="color:#ef4444;">*</span></label>
                    <select id="edit-idp-competency-type" name="competency_type" class="form-control" required>
                        <option value="Manajerial">B. Rencana Pengembangan Manajerial</option>
                        <option value="Technical">C. Rencana Pengembangan Technical</option>
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
                    <option value="del-idp-action">B/C. Detail Baris Rencana Aksi (Hapus Baris)</option>
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
                                #{{ $plan->order_no }} - {{ $plan->competency }} [{{ $plan->competency_type ?? 'Manajerial' }}] ({{ $plan->status }})
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

{{-- 6.1 Modal Tambah Review Hasil Pengembangan --}}
<div id="modal-tambah-review" class="modal-backdrop">
    <div class="modal-card" style="max-width:560px;">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Review Hasil Pengembangan
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
            <form id="form-tambah-review" action="{{ route('karyawan.development-review.store', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Kategori Kompetensi <span style="color:#ef4444;">*</span></label>
                    <select name="competency_type" id="tambah-review-competency-type" class="form-control" required>
                        <option value="Manajerial">B. Review per Kompetensi Manajerial</option>
                        <option value="Technical">C. Review per Kompetensi Technical</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Kompetensi <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="competency" class="form-control" placeholder="Contoh: Problem Analysis, Leadership, Cloud Architecture" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Periode Review</label>
                    <input type="text" name="period" class="form-control" value="Juni 2026 – Mei 2027" placeholder="Contoh: Juni 2026 – Mei 2027">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Level Sebelumnya <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="previous_level" class="form-control" min="0" max="10" value="2" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Level Saat Ini <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="current_level" class="form-control" min="0" max="10" value="3" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Level Target <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="target_level" class="form-control" min="0" max="10" value="4" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Perkembangan</label>
                    <select name="status" class="form-control">
                        <option value="">Otomatis (Sesuai Peningkatan)</option>
                        <option value="Meningkat">Meningkat</option>
                        <option value="Stabil">Stabil</option>
                        <option value="Belum Meningkat">Belum Meningkat</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan Reviewer</label>
                    <textarea name="reviewer_notes" class="form-control" rows="3" placeholder="Catatan evaluasi perkembangan kompetensi ini..."></textarea>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 6.2 Modal Edit Review Hasil Pengembangan --}}
<div id="modal-edit-review" class="modal-backdrop">
    <div class="modal-card" style="max-width:560px;">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Review Hasil Pengembangan
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
            <form id="form-edit-review" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Pilih Kompetensi yang Ingin Diedit:</label>
                    <select id="select-edit-review-item" class="form-control">
                        @foreach($employee->developmentReviews as $rev)
                            <option value="{{ $rev->id }}"
                                    data-comp="{{ $rev->competency }}"
                                    data-type="{{ $rev->competency_type ?? 'Manajerial' }}"
                                    data-period="{{ $rev->period }}"
                                    data-prev="{{ $rev->previous_level }}"
                                    data-curr="{{ $rev->current_level }}"
                                    data-target="{{ $rev->target_level }}"
                                    data-status="{{ $rev->status }}"
                                    data-notes="{{ $rev->reviewer_notes }}">
                                #{{ $rev->order_no }} - {{ $rev->competency }} [{{ $rev->competency_type ?? 'Manajerial' }}] (Level {{ $rev->previous_level }} &rarr; {{ $rev->current_level }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori Kompetensi <span style="color:#ef4444;">*</span></label>
                    <select id="edit-review-competency-type" name="competency_type" class="form-control" required>
                        <option value="Manajerial">B. Review per Kompetensi Manajerial</option>
                        <option value="Technical">C. Review per Kompetensi Technical</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Kompetensi <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="edit-review-comp" name="competency" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Periode Review</label>
                    <input type="text" id="edit-review-period" name="period" class="form-control">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Level Sebelumnya <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="edit-review-prev" name="previous_level" class="form-control" min="0" max="10" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Level Saat Ini <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="edit-review-curr" name="current_level" class="form-control" min="0" max="10" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Level Target <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="edit-review-target" name="target_level" class="form-control" min="0" max="10" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Perkembangan</label>
                    <select id="edit-review-status" name="status" class="form-control">
                        <option value="">Otomatis (Sesuai Peningkatan)</option>
                        <option value="Meningkat">Meningkat</option>
                        <option value="Stabil">Stabil</option>
                        <option value="Belum Meningkat">Belum Meningkat</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan Reviewer</label>
                    <textarea id="edit-review-notes" name="reviewer_notes" class="form-control" rows="3"></textarea>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 6.3 Modal Hapus Review Hasil Pengembangan --}}
<div id="modal-hapus-review" class="modal-backdrop">
    <div class="modal-card" style="max-width:500px;">
        <div class="modal-header" style="background:#b91c1c;">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Review Hasil Pengembangan
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body">
            <form id="form-hapus-review" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Pilih Review yang Akan Dihapus:</label>
                    <select id="select-del-review-item" class="form-control">
                        @foreach($employee->developmentReviews as $rev)
                            <option value="{{ $rev->id }}">
                                #{{ $rev->order_no }} - {{ $rev->competency }} [{{ $rev->competency_type ?? 'Manajerial' }}] ({{ $rev->status }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="font-size:12px; color:#b91c1c; margin-top:10px;">
                    Perhatian: Data review kompetensi yang dihapus tidak dapat dipulihkan.
                </div>
                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-danger-outline" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">Hapus Review Ini</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 6.4 Modal Edit Feedback & Rekomendasi Review --}}
<div id="modal-edit-feedback-review" class="modal-backdrop">
    <div class="modal-card" style="max-width:600px;">
        <div class="modal-header">
            <div class="modal-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                Edit Feedback Atasan & Rekomendasi Tindak Lanjut
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>

        <div class="modal-body" style="max-height:75vh; overflow-y:auto;">
            <form id="form-edit-feedback-review" action="{{ route('karyawan.development-review.feedback.update', ['nik' => $employee->nik]) }}" method="POST">
                @csrf
                <div style="font-size:12px; font-weight:700; color:#0b2545; text-transform:uppercase; margin-bottom:8px; border-bottom:1px solid #e2e8f0; padding-bottom:4px;">
                    D. Feedback Atasan Langsung
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan Umpan Balik (Feedback) <span style="color:#ef4444;">*</span></label>
                    <textarea name="review_feedback_text" class="form-control" rows="4" placeholder="Tulis catatan evaluasi dan apresiasi atasan terhadap capaian karyawan...">{{ $employee->review_feedback_text ?: 'Budi Santosoo menunjukkan perkembangan yang baik selama periode ini. Terlihat peningkatan dalam kepemimpinan, komunikasi, dan kemampuan eksekusi. Fokus selanjutnya adalah memperkuat kemampuan analisis strategis dan pengambilan keputusan berbasis data untuk siap menempati posisi Engineering Manager saat penugasan berikutnya.' }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Reviewer / Atasan</label>
                        <input type="text" name="review_reviewer_name" class="form-control" value="{{ $employee->review_reviewer_name ?: 'Andi Wijaya' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jabatan Reviewer</label>
                        <input type="text" name="review_reviewer_title" class="form-control" value="{{ $employee->review_reviewer_title ?: 'Engineering Division Head' }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Review</label>
                    <input type="text" name="review_date" class="form-control" value="{{ $employee->review_date ?: '20 Mei 2027' }}" placeholder="Contoh: 20 Mei 2027">
                </div>

                <div style="font-size:12px; font-weight:700; color:#0b2545; text-transform:uppercase; margin:16px 0 8px; border-bottom:1px solid #e2e8f0; padding-bottom:4px;">
                    E. Rekomendasi Tindak Lanjut
                </div>

                <div class="form-group">
                    <label class="form-label">Daftar Rekomendasi Tindak Lanjut</label>
                    <textarea name="review_recommendations" class="form-control" rows="5" placeholder="Tulis setiap rekomendasi pada baris baru (tiap baris otomatis menjadi poin bercentang hijau)...">{{ $employee->review_recommendations ?: "Lanjutkan program pengembangan sesuai IDP dengan fokus pada Analysis & Judgement.\nBerikan kesempatan memimpin proyek strategis yang berdampak lintas departemen.\nCoaching/mentoring dengan Engineering Manager untuk mempercepat kesiapan.\nReview berikutnya dilakukan pada Mei 2028." }}</textarea>
                    <span style="font-size:11px; color:#64748b; margin-top:4px; display:block;">Tips: Tekan Enter untuk menambah poin rekomendasi baru.</span>
                </div>

                <div class="modal-footer" style="padding:12px 0 0; margin-top:16px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Feedback & Rekomendasi</button>
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

