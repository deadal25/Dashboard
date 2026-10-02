{{-- Tab 5: Status Suksesi --}}
<div class="tab-pane-content">
    {{-- Header Action Bar --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="font-size:15px; font-weight:800; color:#0b2545; text-transform:uppercase;">Status Suksesi</h2>
        <div class="card-actions">
            <button type="button" class="btn btn-primary" data-modal-target="modal-tambah-suksesi">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Data
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-edit-suksesi">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Data
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-hapus-suksesi" style="color:#b91c1c; border-color:#fca5a5;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Hapus Data
            </button>
            <button type="button" class="btn btn-outline" data-modal-target="modal-riwayat-perubahan">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                Riwayat Perubahan
            </button>
            <a href="{{ route('karyawan.export', ['nik' => $employee->nik, 'type' => 'status-suksesi']) }}" class="btn btn-outline">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Export
            </a>
        </div>
    </div>

    {{-- E1. POSISI JABATAN UNTUK SUKSESI --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">E1. Posisi Jabatan untuk Suksesi</span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-suksesi" data-preselect-section="e1-posisi">
                    + Tambah
                </button>
                <button type="button" class="btn-mini" data-modal-target="modal-edit-suksesi" data-preselect-section="edit-e1-posisi">
                    Edit
                </button>
                <button type="button" class="btn-mini btn-mini-danger" data-modal-target="modal-hapus-suksesi" data-preselect-section="del-e1-posisi">
                    Hapus
                </button>
            </div>
        </div>
        <table class="table-custom">
            <thead>
                <tr>
                    <th class="text-center" style="width:4%;">No.</th>
                    <th style="width:20%;">Jabatan yang Disiapkan untuk Suksesi</th>
                    <th style="width:18%;">Departemen / Fungsi</th>
                    <th class="text-center" style="width:12%;">Level Jabatan</th>
                    <th style="width:26%;">Alasan Disiapkan untuk Suksesi</th>
                    <th class="text-center" style="width:10%;">Dibutuhkan Pada</th>
                    <th class="text-center" style="width:10%;">Status Aktif</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employee->successionPositions as $pos)
                    <tr>
                        <td class="text-center">{{ $pos->order_no }}</td>
                        <td style="font-weight:700; color:#0b2545;">{{ $pos->target_position }}</td>
                        <td>{{ $pos->department }}</td>
                        <td class="text-center">{{ $pos->position_level }}</td>
                        <td style="color:#475569;">{{ $pos->reason }}</td>
                        <td class="text-center" style="font-weight:600;">{{ $pos->needed_at }}</td>
                        <td class="text-center">
                            <span class="badge-table-green">Aktif</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="color:#94a3b8; padding:16px;">Belum ada posisi suksesi. Silakan klik + Tambah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div style="font-size:11.5px; color:#64748b; margin-top:10px;">
            Total {{ $employee->successionPositions->count() }} data
        </div>
    </div>

    {{-- E2. CALON PENGGANTI --}}
    <div class="dashboard-card">
        <div class="card-header-bar" style="margin-bottom:12px; padding-bottom:8px;">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:13px;">E2. Calon Pengganti</span>
            </div>
            <div class="quick-action-btn-group">
                <button type="button" class="btn-mini btn-mini-primary" data-modal-target="modal-tambah-suksesi" data-preselect-section="e2-calon">
                    + Tambah
                </button>
                <button type="button" class="btn-mini" data-modal-target="modal-edit-suksesi" data-preselect-section="edit-e2-calon">
                    Edit
                </button>
                <button type="button" class="btn-mini btn-mini-danger" data-modal-target="modal-hapus-suksesi" data-preselect-section="del-e2-calon">
                    Hapus
                </button>
            </div>
        </div>
        <table class="table-custom">
            <thead>
                <tr>
                    <th class="text-center" style="width:4%;">No.</th>
                    <th style="width:18%;">Nama Kandidat</th>
                    <th style="width:20%;">Departemen / Fungsi Saat Ini</th>
                    <th style="width:16%;">Jabatan Saat Ini</th>
                    <th class="text-center" style="width:12%;">Kesiapan</th>
                    <th class="text-center" style="width:14%;">Kapan Dibutuhkan<br><small style="color:#64748b; font-weight:400;">(Saat {{ $employee->name }} Pensiun)</small></th>
                    <th class="text-center" style="width:10%;">Peringkat Suksesor</th>
                    <th style="width:16%;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employee->successionCandidates as $cand)
                    <tr>
                        <td class="text-center">{{ $cand->order_no }}</td>
                        <td style="font-weight:700;">{{ $cand->candidate_name }}</td>
                        <td>{{ $cand->current_department }}</td>
                        <td>{{ $cand->current_position }}</td>
                        <td class="text-center">
                            @if($cand->readiness === '3–5 Tahun' || $cand->readiness === '3-5 Tahun')
                                <span class="badge-table-green">{{ $cand->readiness }}</span>
                            @elseif($cand->readiness === '>5 Tahun')
                                <span class="badge-table-orange">{{ $cand->readiness }}</span>
                            @else
                                <span class="badge-table-blue">{{ $cand->readiness }}</span>
                            @endif
                        </td>
                        <td class="text-center font-medium">{{ $cand->needed_at }}</td>
                        <td class="text-center">
                            <span class="rank-circle">{{ $cand->ranking }}</span>
                        </td>
                        <td style="font-size:11.5px; color:#475569;">{{ $cand->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="font-size:11.5px; color:#64748b; margin-top:10px;">
            Total {{ $employee->successionCandidates->count() }} data
        </div>
    </div>

    {{-- Bottom Two Cards: Keterangan & Catatan --}}
    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 20px;">
        {{-- Keterangan Kesiapan --}}
        <div class="info-card-box">
            <h6 style="font-size:12px; font-weight:700; margin-bottom:10px;">Keterangan Kesiapan:</h6>
            <div style="display:flex; flex-direction:column; gap:8px; font-size:11.5px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="badge-table-green" style="min-width:100px; text-align:center;">Siap Sekarang</span>
                    <span>: Dapat langsung menduduki jabatan jika dibutuhkan</span>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="badge-table-green" style="min-width:100px; text-align:center;">1–2 Tahun</span>
                    <span>: Diperkirakan siap dalam 1–2 tahun</span>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="badge-table-green" style="min-width:100px; text-align:center;">3–5 Tahun</span>
                    <span>: Diperkirakan siap dalam 3–5 tahun</span>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="badge-table-orange" style="min-width:100px; text-align:center;">>5 Tahun</span>
                    <span>: Diperkirakan siap dalam lebih dari 5 tahun</span>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="badge-table-gray" style="min-width:100px; text-align:center;">Belum Siap</span>
                    <span>: Belum memenuhi kompetensi maupun pengalaman yang disyaratkan</span>
                </div>
            </div>
        </div>

        {{-- Catatan --}}
        <div class="info-card-box">
            <h6 style="font-size:12px; font-weight:700; margin-bottom:10px;">Catatan:</h6>
            <ul style="line-height:1.8; color:#475569; margin-left:18px;">
                <li>Pensiun {{ $employee->name }} diperkirakan pada {{ $employee->retirement_year ? 'April ' . $employee->retirement_year : 'April 2044' }}.</li>
                <li>Ranking didasarkan pada kombinasi kesiapan, potensi, dan kesesuaian dengan kebutuhan organisasi.</li>
                <li>Pastikan minimal terdapat 2 kandidat potensial yang disiapkan untuk setiap posisi kritikal.</li>
            </ul>
        </div>
    </div>
</div>
