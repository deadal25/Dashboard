{{-- Tab 2: Riwayat Karir --}}
<div class="tab-pane-content">
    <style>
        .table-karir {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px !important;
            text-align: left;
        }
        .table-karir th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: 700;
            font-size: 13.5px !important;
            line-height: 1.35 !important;
            padding: 9px 12px !important;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .table-karir td {
            padding: 9px 12px !important;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            font-size: 13.5px !important;
            line-height: 1.4 !important;
            vertical-align: middle;
        }
        .table-karir tr:nth-child(even) {
            background-color: #fbfcfe;
        }
        .table-karir tr:hover {
            background-color: #f1f5f9;
        }
        .table-karir .col-date {
            font-weight: 800 !important;
            color: #0b2545 !important;
            letter-spacing: 0.2px;
        }
        .table-karir .badge-table-green,
        .table-karir .badge-table-blue,
        .table-karir .badge-table-purple,
        .table-karir .badge-table-orange,
        .table-karir .badge-table-red,
        .table-karir .badge-table-gray {
            font-size: 12px !important;
            padding: 4px 10px !important;
            font-weight: 600 !important;
            border-radius: 5px;
        }
        .karir-info-section {
            font-size: 12.5px;
        }
        .karir-info-section h6 {
            font-size: 13px !important;
        }
        .karir-info-section span,
        .karir-info-section li {
            font-size: 12.5px;
        }
        @media (max-width: 640px) {
            .table-karir th,
            .table-karir td {
                padding: 6px 8px !important;
                font-size: 12.5px !important;
            }
        }
    </style>

    <div class="dashboard-card">
        <div class="card-header-bar">
            <div class="card-title-wrap">
                <span class="card-title" style="font-size:15px;">Riwayat Karir</span>
            </div>
            <div class="card-actions">
                <button type="button" class="btn btn-primary" data-modal-target="modal-tambah-karir">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah
                </button>
                <button type="button" class="btn btn-outline" data-modal-target="modal-edit-karir">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit
                </button>
                <button type="button" class="btn btn-outline" data-modal-target="modal-hapus-karir" style="color:#64748b;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Hapus
                </button>
                <a href="{{ route('karyawan.export', ['nik' => $employee->nik, 'type' => 'riwayat-karir']) }}" class="btn btn-outline">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Export
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table-custom table-karir">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">No.</th>
                        <th class="text-center" style="width: 12%;">Tanggal Efektif</th>
                        <th style="width: 23%;">Departemen / Seksi</th>
                        <th style="width: 15%;">Jabatan</th>
                        <th class="text-center" style="width: 9%;">Job Class</th>
                        <th class="text-center" style="width: 8%;">Grade</th>
                        <th class="text-center" style="width: 14%;">Jenis Perubahan</th>
                        <th style="width: 14%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employee->careerHistories as $history)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center col-date">{{ $history->effective_date }}</td>
                            <td>{{ $history->department_section }}</td>
                            <td style="font-weight:600;">{{ $history->position }}</td>
                            <td class="text-center font-semibold" style="color:#0f172a;">{{ $history->job_class }}</td>
                            <td class="text-center font-bold" style="color:#2563eb;">{{ $history->grade }}</td>
                            <td class="text-center">
                                @if($history->change_type === 'Kenaikan Pangkat Reguler')
                                    <span class="badge-table-green">Kenaikan Pangkat Reguler</span>
                                @elseif($history->change_type === 'Promosi')
                                    <span class="badge-table-blue">Promosi</span>
                                @elseif($history->change_type === 'Rotasi')
                                    <span class="badge-table-purple">Rotasi</span>
                                @elseif($history->change_type === 'Mutasi')
                                    <span class="badge-table-orange">Mutasi</span>
                                @elseif($history->change_type === 'Demosi')
                                    <span class="badge-table-red">Demosi</span>
                                @else
                                    <span class="badge-table-gray">{{ $history->change_type }}</span>
                                @endif
                            </td>
                            <td>{{ $history->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding: 24px;">Belum ada riwayat karir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="font-size: 12px; color: #64748b; margin-top: 10px; font-weight: 500;">
            Total {{ $employee->careerHistories->count() }} data
        </div>

        {{-- Bottom Details Cards --}}
        <div class="karir-info-section" style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 20px; margin-top: 16px;">
            {{-- Left: Legend of Changes --}}
            <div class="info-card-box">
                <h6 style="margin-bottom: 12px; font-size:13px; font-weight:700;">Keterangan Jenis Perubahan:</h6>
                <div style="display: flex; flex-direction: column; gap: 8px; font-size: 12.5px;">
                    <div style="display:flex; align-items:center; gap: 10px;">
                        <span class="badge-table-green" style="min-width: 140px; text-align: center;">Kenaikan Pangkat Reguler</span>
                        <span>: Kenaikan grade secara reguler sesuai kebijakan perusahaan</span>
                    </div>
                    <div style="display:flex; align-items:center; gap: 10px;">
                        <span class="badge-table-blue" style="min-width: 140px; text-align: center;">Promosi</span>
                        <span>: Perubahan jabatan ke level lebih tinggi</span>
                    </div>
                    <div style="display:flex; align-items:center; gap: 10px;">
                        <span class="badge-table-purple" style="min-width: 140px; text-align: center;">Rotasi</span>
                        <span>: Perpindahan antar fungsi/departemen dalam lingkup pengembangan karir</span>
                    </div>
                    <div style="display:flex; align-items:center; gap: 10px;">
                        <span class="badge-table-orange" style="min-width: 140px; text-align: center;">Mutasi</span>
                        <span>: Perpindahan antar fungsi/departemen tanpa perubahan level jabatan</span>
                    </div>
                    <div style="display:flex; align-items:center; gap: 10px;">
                        <span class="badge-table-red" style="min-width: 140px; text-align: center;">Demosi</span>
                        <span>: Perubahan jabatan ke level lebih rendah</span>
                    </div>
                    <div style="display:flex; align-items:center; gap: 10px;">
                        <span class="badge-table-gray" style="min-width: 140px; text-align: center;">Penempatan Awal</span>
                        <span>: Penempatan pertama kali di perusahaan</span>
                    </div>
                </div>
            </div>

            {{-- Right: Notes --}}
            <div class="info-card-box">
                <h6 style="margin-bottom: 10px; font-size:13px; font-weight:700;">Catatan:</h6>
                <ul style="line-height: 1.8; color: #475569; margin-left: 18px; font-size: 12.5px;">
                    <li>Riwayat karir mencakup seluruh perjalanan jabatan, promosi, rotasi, mutasi, kenaikan pangkat reguler maupun demosi.</li>
                    <li>Data ini digunakan sebagai referensi dalam perencanaan karir dan suksesi.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
