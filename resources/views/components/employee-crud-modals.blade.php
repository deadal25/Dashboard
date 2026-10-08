@if(Auth::check() && Auth::user()->isSuperAdmin())
{{-- Super Admin: Employee CRUD Modals --}}

{{-- 1. Modal Tambah Karyawan Baru --}}
<div id="modal-tambah-karyawan" class="modal-backdrop">

    <div class="modal-card" style="max-width: 620px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #0b233e 0%, #173860 100%);">
            <div class="modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Karyawan Baru (Super Admin)
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form action="{{ route('karyawan.store') }}" method="POST">
            @csrf
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:6px; padding:10px 14px; margin-bottom:16px; font-size:12px; color:#1e40af; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    <span>Input data pokok karyawan baru secara manual. NIK ini akan menjadi identitas utama yang terhubung ke seluruh riwayat dan modul karir.</span>
                </div>

                {{-- Row 1: NIK & Nama Karyawan --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">NIK <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="nik" class="form-control" placeholder="Contoh: 012349" required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Karyawan <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                    </div>
                </div>

                {{-- Row 2: Jabatan & Departemen --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jabatan / Posisi <span style="color:#ef4444;">*</span></label>
                        <select name="position" class="form-control" required>
                            <option value="">-- Pilih Jabatan / Posisi --</option>
                            @foreach(\App\Services\TalentCalculatorService::getPositionStandards() as $pos)
                                <option value="{{ $pos }}">{{ $pos }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Departemen <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="department" class="form-control" placeholder="Contoh: Manufacturing Engineering" required>
                    </div>
                </div>

                {{-- Row 3: Seksi / Unit Kerja & Usia --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Seksi / Unit Kerja</label>
                        <input type="text" name="section" class="form-control" placeholder="Contoh: Process Engineering">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Usia <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="age" class="form-control" placeholder="36" min="18" max="65" required>
                    </div>
                </div>

                {{-- Row 4: Masa Kerja (Tahun) & Pendidikan --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Masa Kerja (Tahun) <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="tenure_years" class="form-control" placeholder="6" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pendidikan</label>
                        <input type="text" name="education" class="form-control" placeholder="Contoh: S1 Teknik Mesin">
                    </div>
                </div>

                {{-- Row 5: Job Class & Grade --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Job Class <span style="color:#ef4444;">*</span></label>
                        <select name="current_job_class" id="add-emp-jobclass" class="form-control select-jobclass" required>
                            <option value="">-- Pilih Job Class --</option>
                            @foreach(\App\Services\TalentCalculatorService::getJobClassList() as $kj)
                                <option value="{{ $kj }}">{{ $kj }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grade Saat Ini <span style="color:#ef4444;">*</span></label>
                        <select name="current_grade" id="add-emp-grade" class="form-control select-grade" required>
                            <option value="">-- Pilih Grade --</option>
                        </select>
                    </div>
                </div>

                {{-- Row 6: Grade Sejak & Jabatan Sejak (Bulan & Tahun Dropdown) --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Grade Sejak</label>
                        <div style="display: flex; gap: 8px;">
                            <select id="add-emp-grade-month" class="form-control" style="flex: 1.2;">
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
                            <select id="add-emp-grade-year" class="form-control" style="flex: 1;">
                                <option value="">-- Pilih Tahun --</option>
                                @for($y = (int)date('Y') + 5; $y >= 1990; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <input type="hidden" id="add-emp-grade-since" name="grade_since" value="">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jabatan Sejak</label>
                        <div style="display: flex; gap: 8px;">
                            <select id="add-emp-pos-month" class="form-control" style="flex: 1.2;">
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
                            <select id="add-emp-pos-year" class="form-control" style="flex: 1;">
                                <option value="">-- Pilih Tahun --</option>
                                @for($y = (int)date('Y') + 5; $y >= 1990; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <input type="hidden" id="add-emp-position-since" name="position_since" value="">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary" style="background:#0b233e;">Simpan Karyawan Baru</button>
            </div>
        </form>

    </div>
</div>

{{-- 2. Modal Edit Karyawan (Super Admin) --}}
<div id="modal-edit-karyawan" class="modal-backdrop">
    <div class="modal-card" style="max-width: 620px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #0b233e 0%, #1d4ed8 100%);">
            <div class="modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Data Karyawan (Super Admin)
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form id="form-edit-karyawan" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">NIK (Nomor Induk)</label>
                        <input type="text" id="edit-emp-nik-display" class="form-control" readonly style="background:#f1f5f9; cursor:not-allowed;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-emp-name" name="name" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jabatan / Posisi <span style="color:#ef4444;">*</span></label>
                        <select id="edit-emp-position" name="position" class="form-control" required>
                            <option value="">-- Pilih Jabatan / Posisi --</option>
                            @foreach(\App\Services\TalentCalculatorService::getPositionStandards() as $pos)
                                <option value="{{ $pos }}">{{ $pos }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Departemen <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-emp-department" name="department" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Seksi / Unit Kerja</label>
                        <input type="text" id="edit-emp-section" name="section" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pendidikan Terakhir <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-emp-education" name="education" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Usia (Tahun) <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="edit-emp-age" name="age" class="form-control" min="18" max="65" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Masa Kerja (Tahun) <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="edit-emp-tenure" name="tenure_years" class="form-control" min="0" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Job Class Saat Ini <span style="color:#ef4444;">*</span></label>
                        <select id="edit-emp-jobclass" name="current_job_class" class="form-control select-jobclass" required>
                            <option value="">-- Pilih Job Class --</option>
                            @foreach(\App\Services\TalentCalculatorService::getJobClassList() as $kj)
                                <option value="{{ $kj }}">{{ $kj }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grade Saat Ini <span style="color:#ef4444;">*</span></label>
                        <select id="edit-emp-grade" name="current_grade" class="form-control select-grade" required>
                            <option value="">-- Pilih Grade --</option>
                        </select>
                    </div>
                </div>

                {{-- Row: Grade Sejak & Menjabat Sejak (Bulan & Tahun Dropdown) --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Grade Sejak</label>
                        <div style="display: flex; gap: 8px;">
                            <select id="edit-emp-grade-month" class="form-control" style="flex: 1.2;">
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
                            <select id="edit-emp-grade-year" class="form-control" style="flex: 1;">
                                <option value="">-- Pilih Tahun --</option>
                                @for($y = (int)date('Y') + 5; $y >= 1990; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <input type="hidden" id="edit-emp-grade-since" name="grade_since" value="">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Menjabat Sejak</label>
                        <div style="display: flex; gap: 8px;">
                            <select id="edit-emp-pos-month" class="form-control" style="flex: 1.2;">
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
                            <select id="edit-emp-pos-year" class="form-control" style="flex: 1;">
                                <option value="">-- Pilih Tahun --</option>
                                @for($y = (int)date('Y') + 5; $y >= 1990; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <input type="hidden" id="edit-emp-position-since" name="position_since" value="">
                    </div>
                </div>

                {{-- Optional Photo Upload during Edit --}}
                <div class="form-group" style="margin-top: 10px; padding: 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px;">
                    <label class="form-label" style="font-weight: 700; margin-bottom: 4px;">
                        Ganti Foto Profil Karyawan <span style="font-size: 11px; color: #64748b; font-weight: normal;">(Opsional - biarkan kosong jika tidak ingin mengubah)</span>
                    </label>
                    <input type="file" name="avatar_file" class="form-control" accept="image/jpeg,image/png,image/webp,image/jpg" id="input-edit-avatar" onchange="previewEditAvatar(this)">
                    <input type="hidden" name="cropped_avatar" id="edit-avatar-cropped-input" value="">
                    <div id="preview-edit-avatar-container" style="display: none; margin-top: 8px; align-items: center; gap: 10px;">
                        <img id="preview-edit-avatar-img" src="" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #2563eb;">
                        <span style="font-size: 11.5px; color: #16a34a; font-weight: 600;">Foto baru siap digunakan (telah disesuaikan)</span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui Data Karyawan</button>
            </div>
        </form>

    </div>
</div>

{{-- 3. Modal Hapus Karyawan (Super Admin) --}}
<div id="modal-hapus-karyawan" class="modal-backdrop">
    <div class="modal-card" style="max-width: 480px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%);">
            <div class="modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Konfirmasi Hapus Karyawan
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form id="form-hapus-karyawan" action="" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <div style="display:flex; gap:16px; align-items:flex-start;">
                    <div style="background:#fee2e2; color:#dc2626; width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:14px; color:#0f172a; margin-bottom:6px;">Hapus Data Karyawan?</div>
                        <p style="font-size:12.5px; color:#475569; line-height:1.5;">
                            Anda akan menghapus data karyawan <strong id="hapus-emp-name" style="color:#0f172a;"></strong> (NIK: <span id="hapus-emp-nik" style="font-family:monospace; font-weight:700;"></span>) beserta seluruh riwayat karir, asesmen, dan data talentanya.
                        </p>
                        <p style="font-size:11.5px; color:#ef4444; margin-top:8px; font-weight:600;">
                            Perhatian: Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary" style="background:#dc2626; border-color:#dc2626;">Hapus Permanen</button>
            </div>
        </form>
    </div>
</div>

{{-- 4. Modal Impor Data Karyawan dari Excel (Super Admin) --}}
<div id="modal-import-excel" class="modal-backdrop">
    <div class="modal-card" style="max-width: 680px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%);">
            <div class="modal-title" style="display:flex; align-items:center; gap:8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="8" y1="13" x2="16" y2="13"></line><line x1="8" y1="17" x2="16" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg>
                Impor Data Karyawan dari Excel (Super Admin)
            </div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form action="{{ route('karyawan.excel.import') }}" method="POST" enctype="multipart/form-data" id="form-import-excel">
            @csrf
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                
                {{-- Template Download Callout Card --}}
                <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #86efac; border-radius: 8px; padding: 14px 16px; margin-bottom: 18px; display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: flex-start; gap: 10px; flex: 1; min-width: 250px;">
                        <div style="background: #059669; color: #fff; width: 34px; height: 34px; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #064e3b; font-size: 13px;">Format Kolom Terintegrasi Excel</div>
                            <div style="font-size: 11.5px; color: #166534; margin-top: 2px;">
                                Unduh template resmi (.xlsx) yang telah dilengkapi contoh data riil dan sheet panduan kamus kolom.
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('karyawan.excel.template') }}" class="btn btn-outline" style="background: #ffffff; color: #059669; border-color: #059669; font-weight: 700; padding: 7px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Unduh Template (.xlsx)
                    </a>
                </div>

                {{-- File Upload Drop Area --}}
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 700; font-size: 13px; color: #0f172a; margin-bottom: 6px;">
                        Pilih File Excel / CSV <span style="color: #ef4444;">*</span>
                    </label>
                    <div id="excel-drop-zone" style="border: 2px dashed #94a3b8; border-radius: 8px; padding: 22px 16px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s;" onclick="document.getElementById('input-excel-file').click()">
                        <input type="file" name="excel_file" id="input-excel-file" accept=".xlsx,.xls,.csv" required style="display: none;" onchange="handleExcelFileSelected(this)">
                        
                        <div id="excel-upload-prompt">
                            <div style="background: #e2e8f0; width: 44px; height: 44px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #475569; margin-bottom: 8px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            </div>
                            <div style="font-weight: 600; font-size: 13px; color: #1e293b;">
                                Klik untuk memilih file Excel atau seret file ke sini
                            </div>
                            <div style="font-size: 11.5px; color: #64748b; margin-top: 4px;">
                                Mendukung format <strong>.xlsx</strong>, <strong>.xls</strong>, atau <strong>.csv</strong> (Maksimal 10 MB)
                            </div>
                        </div>

                        <div id="excel-file-info" style="display: none; align-items: center; justify-content: center; gap: 10px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <div style="text-align: left;">
                                <div id="excel-file-name" style="font-weight: 700; color: #064e3b; font-size: 13px;"></div>
                                <div id="excel-file-size" style="font-size: 11px; color: #64748b;"></div>
                            </div>
                            <button type="button" onclick="event.stopPropagation(); resetExcelFileInput();" style="margin-left: 10px; background: #fee2e2; color: #dc2626; border: none; border-radius: 4px; padding: 4px 8px; font-size: 11px; cursor: pointer; font-weight: 600;">
                                Ganti File
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Option: Update or Skip Existing NIK --}}
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px 14px; margin-bottom: 16px;">
                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; user-select: none;">
                        <input type="checkbox" name="update_existing" value="1" checked style="width: 17px; height: 17px; margin-top: 2px; accent-color: #059669;">
                        <div>
                            <span style="font-weight: 700; font-size: 12.5px; color: #0f172a;">Perbarui data jika NIK sudah ada di sistem (Upsert)</span>
                            <p style="font-size: 11px; color: #64748b; margin-top: 2px; line-height: 1.4;">
                                Jika dicentang, profil karyawan dengan NIK yang sama akan diperbarui dengan data terbaru dari Excel. Jika tidak dicentang, baris dengan NIK yang sudah ada akan dilewati.
                            </p>
                        </div>
                    </label>
                </div>

                {{-- Collapsible Column Guide --}}
                <div style="border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden;">
                    <div onclick="toggleExcelColumnGuide()" style="background: #f1f5f9; padding: 10px 14px; font-size: 12px; font-weight: 700; color: #334155; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                        <span style="display:flex; align-items:center; gap:6px;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            Daftar Kolom Excel yang Didukung & Terintegrasi
                        </span>
                        <span id="guide-toggle-icon" style="font-size: 11px; color: #64748b;">▼ Lihat Rincian</span>
                    </div>

                    <div id="excel-column-guide-content" style="display: none; padding: 12px 14px; background: #ffffff; max-height: 200px; overflow-y: auto; font-size: 11.5px;">
                        <div style="margin-bottom: 8px; font-weight: 700; color: #dc2626;">
                            Kolom Wajib (*):
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px;">
                            <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">NIK *</span>
                            <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Nama Karyawan *</span>
                            <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Jabatan *</span>
                            <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Departemen *</span>
                            <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Usia *</span>
                            <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Masa Kerja (Tahun) *</span>
                            <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Job Class *</span>
                            <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Grade *</span>
                        </div>

                        <div style="margin-bottom: 8px; font-weight: 700; color: #0284c7;">
                            Kolom Opsional (akan diisi nilai default bila kosong):
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Seksi / Unit Kerja</span>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Pendidikan</span>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Grade Sejak</span>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Jabatan Sejak</span>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Talent Pool (YA/TIDAK)</span>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Flying Risk (LOW/MEDIUM/HIGH)</span>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Performance Terakhir (A/B+)</span>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">POTASS Terakhir (%)</span>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">HAV Box (Box 15)</span>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Posisi Selanjutnya</span>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Proyeksi Karir</span>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Catatan Profil</span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-submit-import" style="background:#059669; border-color:#059669; display:flex; align-items:center; gap:6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    <span>Mulai Impor Karyawan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function handleExcelFileSelected(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('excel-upload-prompt').style.display = 'none';
        document.getElementById('excel-file-info').style.display = 'flex';
        document.getElementById('excel-file-name').textContent = file.name;
        document.getElementById('excel-file-size').textContent = (file.size / 1024).toFixed(1) + ' KB';
        document.getElementById('excel-drop-zone').style.borderColor = '#059669';
        document.getElementById('excel-drop-zone').style.background = '#f0fdf4';
    }
}

function resetExcelFileInput() {
    const input = document.getElementById('input-excel-file');
    input.value = '';
    document.getElementById('excel-upload-prompt').style.display = 'block';
    document.getElementById('excel-file-info').style.display = 'none';
    document.getElementById('excel-drop-zone').style.borderColor = '#94a3b8';
    document.getElementById('excel-drop-zone').style.background = '#f8fafc';
}

function toggleExcelColumnGuide() {
    const content = document.getElementById('excel-column-guide-content');
    const icon = document.getElementById('guide-toggle-icon');
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.textContent = '▲ Tutup Rincian';
    } else {
        content.style.display = 'none';
        icon.textContent = '▼ Lihat Rincian';
    }
}

// Inisialisasi Drag & Drop Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    const dropZone = document.getElementById('excel-drop-zone');
    const fileInput = document.getElementById('input-excel-file');

    if (dropZone && fileInput) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.style.borderColor = '#059669';
                dropZone.style.background = '#dcfce7';
                dropZone.style.transform = 'scale(1.01)';
            }, false);
        });

        ['dragleave', 'dragend'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.style.borderColor = '#94a3b8';
                dropZone.style.background = '#f8fafc';
                dropZone.style.transform = 'scale(1)';
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = '#059669';
            dropZone.style.background = '#f0fdf4';
            dropZone.style.transform = 'scale(1)';

            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                handleExcelFileSelected(fileInput);
            }
        }, false);
    }
});
</script>
@endif

