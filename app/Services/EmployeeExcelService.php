<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class EmployeeExcelService
{
    /**
     * Definisi kolom-kolom Excel dan pemetaannya ke kolom database.
     */
    public static function getColumnDefinitions(): array
    {
        return [
            [
                'field' => 'nik',
                'header' => 'NIK',
                'required' => true,
                'type' => 'Teks / Angka',
                'example' => '012349',
                'description' => 'Nomor Induk Karyawan (unik, tidak boleh kosong)',
                'aliases' => ['nik', 'no_induk', 'nomor_induk', 'no_karyawan', 'nomor_karyawan', 'employee_id', 'id_karyawan']
            ],
            [
                'field' => 'name',
                'header' => 'Nama Karyawan',
                'required' => true,
                'type' => 'Teks',
                'example' => 'Budi Santoso',
                'description' => 'Nama lengkap karyawan',
                'aliases' => ['nama_karyawan', 'nama', 'nama_lengkap', 'name', 'employee_name', 'full_name']
            ],
            [
                'field' => 'position',
                'header' => 'Jabatan',
                'required' => true,
                'type' => 'Teks',
                'example' => 'Engineering Section Head',
                'description' => 'Posisi / jabatan saat ini',
                'aliases' => ['jabatan', 'posisi', 'position', 'job_title', 'title']
            ],
            [
                'field' => 'department',
                'header' => 'Departemen',
                'required' => true,
                'type' => 'Teks',
                'example' => 'Manufacturing Engineering',
                'description' => 'Nama divisi / departemen',
                'aliases' => ['departemen', 'department', 'dept', 'divisi']
            ],
            [
                'field' => 'section',
                'header' => 'Seksi / Unit Kerja',
                'required' => false,
                'type' => 'Teks',
                'example' => 'Process Engineering',
                'description' => 'Unit kerja / bagian di bawah departemen',
                'aliases' => ['seksi', 'unit_kerja', 'seksi_unit_kerja', 'section', 'bagian', 'sub_dept']
            ],
            [
                'field' => 'age',
                'header' => 'Usia',
                'required' => true,
                'type' => 'Angka',
                'example' => '36',
                'description' => 'Usia karyawan dalam tahun (18-65)',
                'aliases' => ['usia', 'umur', 'age']
            ],
            [
                'field' => 'tenure_years',
                'header' => 'Masa Kerja (Tahun)',
                'required' => true,
                'type' => 'Angka',
                'example' => '6',
                'description' => 'Total masa kerja dalam tahun',
                'aliases' => ['masa_kerja', 'masa_kerja_tahun', 'tenure', 'tenure_years', 'lama_kerja']
            ],
            [
                'field' => 'education',
                'header' => 'Pendidikan',
                'required' => false,
                'type' => 'Teks',
                'example' => 'S1 Teknik Mesin',
                'description' => 'Pendidikan terakhir karyawan',
                'aliases' => ['pendidikan', 'pendidikan_terakhir', 'education', 'tingkat_pendidikan']
            ],
            [
                'field' => 'current_job_class',
                'header' => 'Job Class',
                'required' => true,
                'type' => 'Teks',
                'example' => 'JC4',
                'description' => 'Job class karyawan (contoh: JC3, JC4, JC5)',
                'aliases' => ['job_class', 'jc', 'current_job_class', 'job_class_saat_ini', 'jobclass']
            ],
            [
                'field' => 'current_grade',
                'header' => 'Grade',
                'required' => true,
                'type' => 'Teks',
                'example' => 'G4-1',
                'description' => 'Golongan grade saat ini (contoh: G3-2, G4-1, G5-1)',
                'aliases' => ['grade', 'current_grade', 'grade_saat_ini', 'golongan']
            ],
            [
                'field' => 'grade_since',
                'header' => 'Grade Sejak',
                'required' => false,
                'type' => 'Teks',
                'example' => 'April 2026',
                'description' => 'Waktu perolehan grade (contoh: April 2026)',
                'aliases' => ['grade_sejak', 'grade_since', 'tmt_grade']
            ],
            [
                'field' => 'position_since',
                'header' => 'Jabatan Sejak',
                'required' => false,
                'type' => 'Teks',
                'example' => 'April 2023',
                'description' => 'Waktu mulai menjabat posisi saat ini',
                'aliases' => ['jabatan_sejak', 'posisi_sejak', 'position_since', 'tmt_jabatan']
            ],
            [
                'field' => 'talent_pool_status',
                'header' => 'Talent Pool',
                'required' => false,
                'type' => 'Pilihan (YA/TIDAK)',
                'example' => 'YA',
                'description' => 'Status masuk talent pool: YA atau TIDAK (default: YA)',
                'aliases' => ['talent_pool', 'talent_pool_status', 'status_talent_pool']
            ],
            [
                'field' => 'flying_risk',
                'header' => 'Flying Risk',
                'required' => false,
                'type' => 'Pilihan (LOW/MEDIUM/HIGH)',
                'example' => 'MEDIUM',
                'description' => 'Tingkat risiko turn-over: LOW, MEDIUM, atau HIGH (default: MEDIUM)',
                'aliases' => ['flying_risk', 'risk_level', 'tingkat_risiko', 'risk']
            ],
            [
                'field' => 'flying_risk_reason',
                'header' => 'Alasan Flying Risk',
                'required' => false,
                'type' => 'Teks',
                'example' => 'Career Progression',
                'description' => 'Faktor pemicu flying risk',
                'aliases' => ['alasan_flying_risk', 'flying_risk_reason', 'keterangan_risiko']
            ],
            [
                'field' => 'performance_current',
                'header' => 'Performance Terakhir',
                'required' => false,
                'type' => 'Teks',
                'example' => 'A',
                'description' => 'Hasil penilaian kinerja terkini (A, B+, B, C)',
                'aliases' => ['performance_terakhir', 'performance', 'kinerja', 'performance_current']
            ],
            [
                'field' => 'potass_current',
                'header' => 'POTASS Terakhir',
                'required' => false,
                'type' => 'Teks',
                'example' => '100%',
                'description' => 'Nilai asesmen potensi terkini (contoh: 100%, 94%)',
                'aliases' => ['potass_terakhir', 'potass', 'potass_current', 'skor_potass']
            ],
            [
                'field' => 'hav_box_current',
                'header' => 'HAV Box',
                'required' => false,
                'type' => 'Teks',
                'example' => 'Box 15',
                'description' => 'Klasifikasi 16 Box Talenta (contoh: Box 15, Box 16)',
                'aliases' => ['hav_box', 'hav_box_current', '16_box', 'box_hav']
            ],
            [
                'field' => 'next_possible_position',
                'header' => 'Posisi Selanjutnya',
                'required' => false,
                'type' => 'Teks',
                'example' => 'Engineering Manager',
                'description' => 'Proyeksi jabatan suksesi berikutnya',
                'aliases' => ['posisi_selanjutnya', 'next_possible_position', 'proyeksi_posisi', 'target_posisi']
            ],
            [
                'field' => 'career_projection',
                'header' => 'Proyeksi Karir',
                'required' => false,
                'type' => 'Teks',
                'example' => 'Manufacturing Engineering Division Head',
                'description' => 'Target jenjang karir jangka panjang',
                'aliases' => ['proyeksi_karir', 'career_projection', 'tujuan_karir']
            ],
            [
                'field' => 'readiness_level',
                'header' => 'Kesiapan Promosi',
                'required' => false,
                'type' => 'Teks',
                'example' => 'Siap dalam 1-2 Tahun',
                'description' => 'Status kesiapan suksesi (contoh: Siap Sekarang, Siap dalam 1-2 Tahun)',
                'aliases' => ['kesiapan_promosi', 'readiness_level', 'tingkat_kesiapan', 'kesiapan']
            ],
            [
                'field' => 'retirement_year',
                'header' => 'Tahun Pensiun',
                'required' => false,
                'type' => 'Angka',
                'example' => '2045',
                'description' => 'Estimasi tahun pensiun (usia 55 tahun)',
                'aliases' => ['tahun_pensiun', 'retirement_year', 'pensiun']
            ],
            [
                'field' => 'profile_notes',
                'header' => 'Catatan Profil',
                'required' => false,
                'type' => 'Teks',
                'example' => 'Kandidat talent pool dengan dedikasi tinggi dan kepemimpinan solid.',
                'description' => 'Catatan ringkas mengenai karyawan',
                'aliases' => ['catatan_profil', 'catatan', 'profile_notes', 'keterangan', 'notes']
            ],
        ];
    }

    /**
     * Generate template Excel (.xlsx) dengan 2 sheet:
     * Sheet 1: Template data dengan contoh data riil
     * Sheet 2: Petunjuk dan kamus kolom lengkap
     */
    public function generateTemplate(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $definitions = self::getColumnDefinitions();

        // -------------------------------------------------------------
        // SHEET 1: Data Karyawan (Template & Sample Data)
        // -------------------------------------------------------------
        $sheetData = $spreadsheet->getActiveSheet();
        $sheetData->setTitle('Data Karyawan');

        // Header Row
        $colIndex = 1;
        foreach ($definitions as $def) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            $headerText = $def['header'] . ($def['required'] ? ' *' : '');
            $sheetData->setCellValue("{$colLetter}1", $headerText);
            $colIndex++;
        }

        // Style Header Row
        $highestCol = Coordinate::stringFromColumnIndex(count($definitions));
        $headerRange = "A1:{$highestCol}1";

        $sheetData->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0B233E'], // Dark Navy Theme MAP-IN
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => false,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '1E3A5F'],
                ],
            ],
        ]);
        $sheetData->getRowDimension(1)->setRowHeight(28);

        // Baris Contoh Data Riil (3 Karyawan Representatif)
        $sampleRows = [
            [
                'nik' => '012349',
                'name' => 'Budi Santoso',
                'position' => 'Engineering Section Head',
                'department' => 'Manufacturing Engineering',
                'section' => 'Process Engineering',
                'age' => 36,
                'tenure_years' => 6,
                'education' => 'S1 Teknik Mesin',
                'current_job_class' => 'JC4',
                'current_grade' => 'G4-1',
                'grade_since' => 'April 2026',
                'position_since' => 'April 2023',
                'talent_pool_status' => 'YA',
                'flying_risk' => 'MEDIUM',
                'flying_risk_reason' => 'Career Progression',
                'performance_current' => 'A',
                'potass_current' => '100%',
                'hav_box_current' => 'Box 15',
                'next_possible_position' => 'Engineering Manager',
                'career_projection' => 'Manufacturing Engineering Division Head',
                'readiness_level' => 'Siap dalam 1-2 Tahun',
                'retirement_year' => 2045,
                'profile_notes' => 'Karyawan berkinerja unggul dengan keahlian teknis otomotif tinggi.',
            ],
            [
                'nik' => '012350',
                'name' => 'Siti Rahmawati',
                'position' => 'Quality Control Section Head',
                'department' => 'Quality Assurance',
                'section' => 'In-Process Quality',
                'age' => 34,
                'tenure_years' => 5,
                'education' => 'S1 Teknik Industri',
                'current_job_class' => 'JC4',
                'current_grade' => 'G4-1',
                'grade_since' => 'Oktober 2025',
                'position_since' => 'Oktober 2023',
                'talent_pool_status' => 'YA',
                'flying_risk' => 'LOW',
                'flying_risk_reason' => 'Stable Career',
                'performance_current' => 'A',
                'potass_current' => '96%',
                'hav_box_current' => 'Box 15',
                'next_possible_position' => 'QA / QC Manager',
                'career_projection' => 'Quality Division Head',
                'readiness_level' => 'Siap dalam 1-2 Tahun',
                'retirement_year' => 2047,
                'profile_notes' => 'Sangat teliti dalam audit ISO 9001 dan implementasi Kaizen 5S.',
            ],
            [
                'nik' => '012351',
                'name' => 'Hendro Wicaksono',
                'position' => 'Production Supervisor',
                'department' => 'Production Assembly',
                'section' => 'Main Line 2',
                'age' => 32,
                'tenure_years' => 4,
                'education' => 'D4 Teknik Otomotif',
                'current_job_class' => 'JC3',
                'current_grade' => 'G3-2',
                'grade_since' => 'Januari 2025',
                'position_since' => 'Januari 2024',
                'talent_pool_status' => 'YA',
                'flying_risk' => 'HIGH',
                'flying_risk_reason' => 'Permintaan Spesialis di Industri Luar',
                'performance_current' => 'B+',
                'potass_current' => '92%',
                'hav_box_current' => 'Box 12',
                'next_possible_position' => 'Production Section Head',
                'career_projection' => 'Plant Operations Manager',
                'readiness_level' => 'Siap dalam 2-3 Tahun',
                'retirement_year' => 2049,
                'profile_notes' => 'Kandidat fast-track dengan potensi leadership lapangan yang kuat.',
            ],
        ];

        $rowIndex = 2;
        foreach ($sampleRows as $sample) {
            $colIndex = 1;
            foreach ($definitions as $def) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                $val = $sample[$def['field']] ?? '';
                $sheetData->setCellValueExplicit("{$colLetter}{$rowIndex}", $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $colIndex++;
            }
            $sheetData->getRowDimension($rowIndex)->setRowHeight(22);
            $rowIndex++;
        }

        // Style Sample Rows
        $dataRange = "A2:{$highestCol}" . ($rowIndex - 1);
        $sheetData->getStyle($dataRange)->applyFromArray([
            'font' => ['size' => 10, 'name' => 'Calibri'],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        // Auto size columns
        foreach (range(1, count($definitions)) as $col) {
            $sheetData->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        // Freeze pane pada header row (A2)
        $sheetData->freezePane('A2');

        // -------------------------------------------------------------
        // SHEET 2: Petunjuk & Kamus Kolom
        // -------------------------------------------------------------
        $sheetGuide = $spreadsheet->createSheet();
        $sheetGuide->setTitle('Kamus Kolom & Petunjuk');

        // Header Petunjuk
        $sheetGuide->setCellValue('A1', 'PANDUAN & STRUKTUR KOLOM IMPORT EXCEL KARYAWAN (MAP-IN)');
        $sheetGuide->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0B233E'));
        $sheetGuide->mergeCells('A1:E1');

        $sheetGuide->setCellValue('A2', 'Gunakan sheet "Data Karyawan" untuk mengisi data. Tanda bintang (*) menandakan kolom Wajib diisi.');
        $sheetGuide->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'));
        $sheetGuide->mergeCells('A2:E2');

        // Table Header
        $tableHeaders = ['No.', 'Nama Kolom di Excel', 'Status', 'Tipe Data', 'Contoh Nilai', 'Keterangan & Nilai yang Diterima'];
        $colIndex = 1;
        foreach ($tableHeaders as $th) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            $sheetGuide->setCellValue("{$colLetter}4", $th);
            $colIndex++;
        }

        $sheetGuide->getStyle('A4:F4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '173860'],
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        ]);
        $sheetGuide->getRowDimension(4)->setRowHeight(24);

        $guideRow = 5;
        foreach ($definitions as $idx => $def) {
            $sheetGuide->setCellValue("A{$guideRow}", $idx + 1);
            $sheetGuide->setCellValue("B{$guideRow}", $def['header']);
            $sheetGuide->setCellValue("C{$guideRow}", $def['required'] ? 'WAJIB (*)' : 'OPSIONAL');
            $sheetGuide->setCellValue("D{$guideRow}", $def['type']);
            $sheetGuide->setCellValue("E{$guideRow}", $def['example']);
            $sheetGuide->setCellValue("F{$guideRow}", $def['description']);

            // Style status
            if ($def['required']) {
                $sheetGuide->getStyle("C{$guideRow}")->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC2626'));
            } else {
                $sheetGuide->getStyle("C{$guideRow}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'));
            }

            $guideRow++;
        }

        $sheetGuide->getStyle("A5:F" . ($guideRow - 1))->applyFromArray([
            'font' => ['size' => 10],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
        ]);

        foreach (range(1, 6) as $c) {
            $sheetGuide->getColumnDimension(Coordinate::stringFromColumnIndex($c))->setAutoSize(true);
        }

        // Set active sheet back to Data Karyawan
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * Export seluruh data karyawan ke format Excel yang sama dengan template
     */
    public function exportAllEmployees(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Karyawan');

        $definitions = self::getColumnDefinitions();

        // Header
        $colIndex = 1;
        foreach ($definitions as $def) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue("{$colLetter}1", $def['header']);
            $colIndex++;
        }

        $highestCol = Coordinate::stringFromColumnIndex(count($definitions));
        $headerRange = "A1:{$highestCol}1";

        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0B233E'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '1E3A5F']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(26);

        // Fetch all employees
        $employees = Employee::orderBy('id')->get();
        $rowIndex = 2;

        foreach ($employees as $emp) {
            $colIndex = 1;
            foreach ($definitions as $def) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                $field = $def['field'];
                $val = $emp->{$field} ?? '';

                $sheet->setCellValueExplicit("{$colLetter}{$rowIndex}", (string)$val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $colIndex++;
            }
            $sheet->getRowDimension($rowIndex)->setRowHeight(20);
            $rowIndex++;
        }

        if ($employees->isNotEmpty()) {
            $dataRange = "A2:{$highestCol}" . ($rowIndex - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'font' => ['size' => 10],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            ]);
        }

        foreach (range(1, count($definitions)) as $col) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        $sheet->freezePane('A2');

        return $spreadsheet;
    }

    /**
     * Memproses file upload Excel (.xlsx, .xls, .csv) dan menyimpan / memperbarui data karyawan.
     *
     * @param UploadedFile $file
     * @param bool $updateExisting Jika true, jika NIK sudah ada maka data diperbarui. Jika false, NIK yang sudah ada dilewati.
     * @return array
     */
    public function importEmployees(UploadedFile $file, bool $updateExisting = true): array
    {
        $realPath = $file->getRealPath();
        $definitions = self::getColumnDefinitions();

        // Build alias map: normalized string => field name
        $aliasMap = [];
        foreach ($definitions as $def) {
            $field = $def['field'];
            $normalizedHeader = $this->normalizeString($def['header']);
            $aliasMap[$normalizedHeader] = $field;

            foreach ($def['aliases'] as $alias) {
                $normalizedAlias = $this->normalizeString($alias);
                $aliasMap[$normalizedAlias] = $field;
            }
        }

        try {
            $reader = IOFactory::createReaderForFile($realPath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($realPath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
        } catch (\Throwable $e) {
            Log::error('Import Excel Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal membaca file Excel. Pastikan format file valid (.xlsx, .xls, atau .csv). Error: ' . $e->getMessage(),
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ['File tidak dapat dibaca atau rusak.'],
            ];
        }

        if (empty($rows)) {
            return [
                'success' => false,
                'message' => 'File Excel kosong atau tidak memiliki baris data.',
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ['File tidak memiliki konten baris data.'],
            ];
        }

        // Ambil header di baris 1
        $headerRow = array_shift($rows);
        $columnIndexToField = [];

        foreach ($headerRow as $colLetter => $rawHeader) {
            if (empty($rawHeader)) {
                continue;
            }
            $normalized = $this->normalizeString((string)$rawHeader);
            if (isset($aliasMap[$normalized])) {
                $columnIndexToField[$colLetter] = $aliasMap[$normalized];
            }
        }

        // Cek kolom wajib yang harus ada di header
        $requiredFields = ['nik', 'name', 'position', 'department'];
        $foundFields = array_values($columnIndexToField);
        $missingFields = array_diff($requiredFields, $foundFields);

        if (!empty($missingFields)) {
            $missingNames = array_map(function ($f) use ($definitions) {
                foreach ($definitions as $d) {
                    if ($d['field'] === $f) return $d['header'];
                }
                return $f;
            }, $missingFields);

            return [
                'success' => false,
                'message' => 'Kolom wajib tidak ditemukan di file Excel: ' . implode(', ', $missingNames) . '. Silakan gunakan format template resmi.',
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ['Header kolom wajib belum sesuai: ' . implode(', ', $missingNames)],
            ];
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $rowErrors = [];
        $rowNumber = 1; // Baris 1 adalah header

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $rowNumber++;

                // Ekstrak data per baris
                $rowData = [];
                $hasAnyData = false;

                foreach ($columnIndexToField as $colLetter => $field) {
                    $rawVal = isset($row[$colLetter]) ? trim((string)$row[$colLetter]) : '';
                    if ($rawVal !== '') {
                        $hasAnyData = true;
                    }
                    $rowData[$field] = $rawVal;
                }

                // Lewati baris kosong
                if (!$hasAnyData || empty($rowData['nik']) && empty($rowData['name'])) {
                    continue;
                }

                // Validasi NIK
                $nik = trim($rowData['nik'] ?? '');
                if ($nik === '') {
                    $rowErrors[] = "Baris {$rowNumber}: NIK wajib diisi.";
                    continue;
                }

                // Validasi Nama
                $name = trim($rowData['name'] ?? '');
                if ($name === '') {
                    $rowErrors[] = "Baris {$rowNumber} (NIK: {$nik}): Nama Karyawan wajib diisi.";
                    continue;
                }

                // Validasi Jabatan & Departemen
                $position = trim($rowData['position'] ?? '');
                if ($position === '') {
                    $rowErrors[] = "Baris {$rowNumber} (NIK: {$nik}): Jabatan wajib diisi.";
                    continue;
                }

                $department = trim($rowData['department'] ?? '');
                if ($department === '') {
                    $rowErrors[] = "Baris {$rowNumber} (NIK: {$nik}): Departemen wajib diisi.";
                    continue;
                }

                // Format & default values
                $age = isset($rowData['age']) && is_numeric($rowData['age']) ? (int)$rowData['age'] : 35;
                if ($age < 18 || $age > 65) {
                    $age = 35;
                }

                $tenure = isset($rowData['tenure_years']) && is_numeric($rowData['tenure_years']) ? (int)$rowData['tenure_years'] : 5;
                if ($tenure < 0) {
                    $tenure = 0;
                }

                $section = !empty($rowData['section']) ? $rowData['section'] : null;
                $education = !empty($rowData['education']) ? $rowData['education'] : 'S1';
                $jobClass = !empty($rowData['current_job_class']) ? strtoupper($rowData['current_job_class']) : 'JC4';
                $grade = !empty($rowData['current_grade']) ? strtoupper($rowData['current_grade']) : 'G4-1';
                $gradeSince = !empty($rowData['grade_since']) ? $rowData['grade_since'] : 'April 2026';
                $positionSince = !empty($rowData['position_since']) ? $rowData['position_since'] : 'April 2023';

                // Talent badges
                $talentPool = strtoupper(trim($rowData['talent_pool_status'] ?? ''));
                $talentPool = in_array($talentPool, ['YA', 'TIDAK', 'YES', 'NO']) ? ($talentPool === 'TIDAK' || $talentPool === 'NO' ? 'TIDAK' : 'YA') : 'YA';

                $flyingRisk = strtoupper(trim($rowData['flying_risk'] ?? ''));
                if (!in_array($flyingRisk, ['LOW', 'MEDIUM', 'HIGH'])) {
                    $flyingRisk = 'MEDIUM';
                }

                $flyingRiskReason = !empty($rowData['flying_risk_reason']) ? $rowData['flying_risk_reason'] : 'Career Progression';
                $performance = !empty($rowData['performance_current']) ? strtoupper($rowData['performance_current']) : 'A';
                $potass = !empty($rowData['potass_current']) ? $rowData['potass_current'] : '100%';
                if (!str_contains($potass, '%') && is_numeric($potass)) {
                    $potass = $potass . '%';
                }

                $havBox = !empty($rowData['hav_box_current']) ? $rowData['hav_box_current'] : 'Box 15';
                $nextPosition = !empty($rowData['next_possible_position']) ? $rowData['next_possible_position'] : ($position . ' Manager');
                $careerProj = !empty($rowData['career_projection']) ? $rowData['career_projection'] : ($department . ' Division Head');
                $readiness = !empty($rowData['readiness_level']) ? $rowData['readiness_level'] : 'Siap dalam 1-2 Tahun';

                $retYear = !empty($rowData['retirement_year']) && is_numeric($rowData['retirement_year'])
                    ? (string)$rowData['retirement_year']
                    : (string)(date('Y') + (55 - $age));

                $notes = !empty($rowData['profile_notes']) ? $rowData['profile_notes'] : 'Data karyawan diimpor dari Excel.';

                // Cek apakah karyawan sudah ada berdasarkan NIK
                $employee = Employee::where('nik', $nik)->first();

                $employeePayload = [
                    'nik' => $nik,
                    'name' => $name,
                    'position' => $position,
                    'department' => $department,
                    'section' => $section,
                    'age' => $age,
                    'tenure_years' => $tenure,
                    'education' => $education,
                    'current_job_class' => $jobClass,
                    'current_grade' => $grade,
                    'grade_since' => $gradeSince,
                    'position_since' => $positionSince,
                    'talent_pool_status' => $talentPool,
                    'flying_risk' => $flyingRisk,
                    'flying_risk_reason' => $flyingRiskReason,
                    'performance_current' => $performance,
                    'potass_current' => $potass,
                    'hav_box_current' => $havBox,
                    'next_possible_position' => $nextPosition,
                    'career_projection' => $careerProj,
                    'readiness_level' => $readiness,
                    'retirement_year' => $retYear,
                    'profile_notes' => $notes,
                ];

                if ($employee) {
                    if ($updateExisting) {
                        $employee->update($employeePayload);
                        $updated++;
                    } else {
                        $skipped++;
                    }
                } else {
                    $employeePayload['avatar'] = '/images/avatar-budi.png';
                    $newEmp = Employee::create($employeePayload);

                    // Buat initial career plan
                    $newEmp->careerPlan()->create([
                        'interested_area' => $department,
                        'recommended_career_path' => 'Managerial',
                        'next_possible_position' => $nextPosition,
                        'next_possible_department' => $department,
                        'career_projection' => $careerProj,
                        'notes' => 'Profil awal karir dibuat otomatis saat impor Excel oleh Super Admin.',
                    ]);

                    $imported++;
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => "Proses impor selesai! {$imported} data baru ditambahkan, {$updated} data diperbarui" . ($skipped > 0 ? ", {$skipped} data dilewati" : '') . '.',
                'imported' => $imported,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => $rowErrors,
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Import Excel Transaction Failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses data ke database: ' . $e->getMessage(),
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => [$e->getMessage()],
            ];
        }
    }

    /**
     * Normalisasi string untuk pencocokan header fleksibel (lowercase, tanpa simbol & spasi)
     */
    private function normalizeString(string $str): string
    {
        // Hapus karakter bintang, tanda kurung, garis miring, strip, titik, koma
        $clean = str_replace(['*', '(', ')', '/', '\\', '-', '.', ','], ' ', $str);
        // Trim whitespace dulu sebelum convert spaces to underscore
        $clean = trim($clean);
        // Ganti spasi berlebih atau underscore dengan underscore tunggal
        $clean = preg_replace('/[\s_]+/', '_', $clean);
        return strtolower(trim($clean, '_'));
    }
}
