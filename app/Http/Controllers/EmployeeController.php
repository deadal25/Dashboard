<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\CareerHistory;
use App\Models\JobClassPlan;
use App\Models\SuccessionPosition;
use App\Models\KeyStrength;
use App\Models\TalentAssessment;
use App\Models\SuccessionCandidate;
use App\Models\CompetencyGap;
use App\Models\IdpActionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\EmployeeExcelService;
use App\Services\TalentCalculatorService;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EmployeeController extends Controller
{
    /**
     * Display executive dashboard overview (Dashboard Karyawan).
     */
    public function index(Request $request)
    {
        $stats = [
            'total' => Employee::count(),
            'talent_pool' => Employee::where('talent_pool_status', 'YA')->count(),
            'high_risk' => Employee::whereIn('flying_risk', ['HIGH', 'High Risk'])->count(),
            'medium_risk' => Employee::whereIn('flying_risk', ['MEDIUM', 'MODERATE', 'Moderate Risk'])->count(),
        ];

        // Hitung distribusi seluruh karyawan di 16 HAV Box
        $havBoxCounts = array_fill(1, 16, 0);
        $allEmployees = Employee::select('id', 'nik', 'name', 'hav_box_current', 'potass_score_last', 'talent_pool_status')->get();
        foreach ($allEmployees as $emp) {
            if (!empty($emp->hav_box_current)) {
                $boxNum = (int) str_replace('Box ', '', $emp->hav_box_current);
                if ($boxNum >= 1 && $boxNum <= 16) {
                    $havBoxCounts[$boxNum]++;
                    continue;
                }
            }
            $details = $emp->getHavBoxDetails();
            $boxNum = $details['box_number'] ?? 15;
            if ($boxNum >= 1 && $boxNum <= 16) {
                $havBoxCounts[$boxNum]++;
            }
        }
        $havMatrixMap = TalentCalculatorService::getHavMatrixMap();

        // 5 Karyawan terbaru untuk pratinjau ringkas di Dashboard
        $recentEmployees = Employee::latest()->take(5)->get();

        return view('karyawan.index', compact('stats', 'havBoxCounts', 'havMatrixMap', 'recentEmployees'));
    }

    /**
     * Display the Master Employee Data page (Data Karyawan) with Search, Filters, and CRUD Modals.
     */
    public function dataKaryawan(Request $request)
    {
        $query = Employee::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($dept = $request->input('department')) {
            $query->where('department', $dept);
        }

        if ($pool = $request->input('talent_pool')) {
            $query->where('talent_pool_status', $pool);
        }

        if ($risk = $request->input('flying_risk')) {
            if ($risk === 'HIGH') {
                $query->whereIn('flying_risk', ['HIGH', 'High Risk']);
            } elseif ($risk === 'MEDIUM') {
                $query->whereIn('flying_risk', ['MEDIUM', 'MODERATE', 'Moderate Risk']);
            } elseif ($risk === 'LOW') {
                $query->whereIn('flying_risk', ['LOW', 'Low Risk']);
            } else {
                $query->where('flying_risk', $risk);
            }
        }

        if ($box = $request->input('hav_box')) {
            $formattedBox = str_starts_with($box, 'Box ') ? $box : 'Box ' . $box;
            $query->where(function ($q) use ($box, $formattedBox) {
                $q->where('hav_box_current', $formattedBox)
                  ->orWhere('hav_box_current', $box);
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $employees = $query->paginate($perPage)->withQueryString();
        $departments = Employee::select('department')->distinct()->whereNotNull('department')->pluck('department');

        $stats = [
            'total' => Employee::count(),
            'talent_pool' => Employee::where('talent_pool_status', 'YA')->count(),
            'high_risk' => Employee::whereIn('flying_risk', ['HIGH', 'High Risk'])->count(),
            'medium_risk' => Employee::whereIn('flying_risk', ['MEDIUM', 'MODERATE', 'Moderate Risk'])->count(),
        ];

        // Distribusi 16 HAV Box untuk pilihan filter
        $havBoxCounts = array_fill(1, 16, 0);
        $allEmployees = Employee::select('id', 'nik', 'hav_box_current', 'potass_score_last', 'talent_pool_status')->get();
        foreach ($allEmployees as $emp) {
            if (!empty($emp->hav_box_current)) {
                $boxNum = (int) str_replace('Box ', '', $emp->hav_box_current);
                if ($boxNum >= 1 && $boxNum <= 16) {
                    $havBoxCounts[$boxNum]++;
                    continue;
                }
            }
            $details = $emp->getHavBoxDetails();
            $boxNum = $details['box_number'] ?? 15;
            if ($boxNum >= 1 && $boxNum <= 16) {
                $havBoxCounts[$boxNum]++;
            }
        }

        return view('karyawan.data-karyawan', compact('employees', 'departments', 'stats', 'havBoxCounts'));
    }

    /**
     * Handle NIK search submission from sidebar or search form.
     */
    public function search(Request $request)
    {
        $nik = trim($request->input('nik', ''));
        $currentTab = $request->input('tab', 'profil-individu');

        if (empty($nik)) {
            return redirect()->back()->with('error', 'Masukkan NIK untuk melakukan pencarian.');
        }

        // Exact NIK match
        $employee = Employee::where('nik', $nik)->first();

        // If not found by exact NIK, check partial NIK or Name
        if (!$employee) {
            $employee = Employee::where('nik', 'like', "%{$nik}%")
                ->orWhere('name', 'like', "%{$nik}%")
                ->first();
        }

        if (!$employee) {
            return redirect()->back()->with('error', "Karyawan dengan NIK \"{$nik}\" tidak ditemukan.");
        }

        return redirect()->route('karyawan.show', ['nik' => $employee->nik, 'tab' => $currentTab]);
    }

    /**
     * API for live NIK autocomplete / suggestion dropdown in sidebar.
     */
    public function apiSearch(Request $request)
    {
        $query = trim($request->input('query', ''));

        if (strlen($query) === 0) {
            $employees = Employee::select('id', 'nik', 'name', 'position', 'department', 'avatar')
                ->limit(6)
                ->get();
        } else {
            $employees = Employee::select('id', 'nik', 'name', 'position', 'department', 'avatar')
                ->where('nik', 'like', "%{$query}%")
                ->orWhere('name', 'like', "%{$query}%")
                ->limit(6)
                ->get();
        }

        return response()->json($employees);
    }

    /**
     * Display the specified employee profile with the active tab.
     */
    public function show(string $nik, string $tab = 'profil-individu')
    {
        // Redirect legacy tab route slugs to new official slugs
        if ($tab === 'career-plan') {
            return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'individual-careerplan']);
        }
        if ($tab === 'idp') {
            return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'individual-developmentplan']);
        }

        $validTabs = [
            'profil-individu',
            'riwayat-karir',
            'talent-snapshot',
            'individual-careerplan',
            'career-plan',
            'status-suksesi',
            'development-gap',
            'individual-developmentplan',
            'idp',
            'review-pengembangan',
            'riwayat-pelatihan',
        ];

        if (!in_array($tab, $validTabs)) {
            $tab = 'profil-individu';
        }

        $employee = Employee::where('nik', $nik)->firstOrFail();
        $this->syncIdpAndReviews($employee);

        $employee->load([
            'careerHistories',
            'talentAssessments',
            'keyStrengths',
            'careerPlan',
            'jobClassPlans',
            'successionPositions',
            'successionCandidates',
            'competencyGaps',
            'idpActionPlans',
            'developmentReviews',
            'trainingHistories',
            'certifications',
            'performanceAppraisals',
        ]);

        // Calculate specific stats for tabs
        $allGaps = $employee->competencyGaps;
        $totalGapCount = $allGaps->count();
        $rendahGapCount = $allGaps->filter(fn($g) => strtolower($g->gap_severity) === 'rendah')->count();
        $sedangGapCount = $allGaps->filter(fn($g) => strtolower($g->gap_severity) === 'sedang')->count();
        $tinggiGapCount = $allGaps->filter(fn($g) => strtolower($g->gap_severity) === 'tinggi')->count();

        $gapSummary = [
            'rendah' => $rendahGapCount,
            'sedang' => $sedangGapCount,
            'tinggi' => $tinggiGapCount,
            'total'  => $totalGapCount,
            'pct_rendah' => $totalGapCount > 0 ? round(($rendahGapCount / $totalGapCount) * 100, 1) : 0,
            'pct_sedang' => $totalGapCount > 0 ? round(($sedangGapCount / $totalGapCount) * 100, 1) : 0,
            'pct_tinggi' => $totalGapCount > 0 ? round(($tinggiGapCount / $totalGapCount) * 100, 1) : 0,
            'tinggi_manajerial' => $allGaps->filter(fn($g) => (empty($g->competency_type) || strtolower($g->competency_type) === 'manajerial') && strtolower($g->gap_severity) === 'tinggi')->count(),
            'tinggi_technical' => $allGaps->filter(fn($g) => strtolower($g->competency_type) === 'technical' && strtolower($g->gap_severity) === 'tinggi')->count(),
        ];

        // Dynamic Training Summary (Connected to database records)
        $allTrainings = $employee->trainingHistories;
        $totalPelatihan = $allTrainings->count();
        $totalJam = (float)$allTrainings->sum('duration_hours');

        // Tahun target terbaru diambil dinamis dari riwayat pelatihan karyawan (misal: 2027), atau tahun kalender jika belum ada
        $maxTrainingYear = $allTrainings->map(fn($t) => $t->year)->filter()->max();
        $latestYear = $maxTrainingYear ? (int)$maxTrainingYear : (int)date('Y');

        $thisYearTrainings = $allTrainings->filter(function ($t) use ($latestYear) {
            return $t->year === $latestYear;
        });

        // Pelatihan tahun ini yang riil dihitung: hanya yang sudah mengupload dokumentasi bukti
        $thisYearDocumentedTrainings = $thisYearTrainings->filter(function ($t) {
            return !empty($t->documentation);
        });

        $tahunIniCount = $thisYearDocumentedTrainings->count();
        $tahunIniJam = (float)$thisYearDocumentedTrainings->sum('duration_hours');

        $totalSertifikasi = $employee->certifications->where('is_active', true)->count();

        $trainingSummary = [
            'current_year'          => $latestYear,
            'current_year_fy'       => 'FY' . substr((string)$latestYear, -2),
            'total_pelatihan'       => $totalPelatihan,
            'total_jam'             => (fmod($totalJam, 1) === 0.0) ? (int)$totalJam : $totalJam,
            'tahun_ini_count'       => $tahunIniCount, // Riil dihitung: hanya yang sudah upload dokumentasi
            'tahun_ini_jam'         => (fmod($tahunIniJam, 1) === 0.0) ? (int)$tahunIniJam : $tahunIniJam,
            'tahun_ini_total_count' => $thisYearTrainings->count(), // Total seluruh pelatihan tahun ini
            'sertifikasi'           => $totalSertifikasi,
        ];

        $trainingYears = $allTrainings
            ->map(fn($t) => $t->year)
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        return view('karyawan.show', compact('employee', 'tab', 'gapSummary', 'trainingSummary', 'trainingYears'));
    }

    /**
     * Store new Career History record.
     */
    public function storeCareerHistory(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'effective_date' => 'required|string|max:50',
            'department_section' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'job_class' => 'required_without:job_class_grade|nullable|string|max:50',
            'grade' => 'required_without:job_class_grade|nullable|string|max:50',
            'job_class_grade' => 'nullable|string|max:50',
            'change_type' => 'required|string|max:100',
            'notes' => 'nullable|string',
        ]);

        if (!empty($validated['job_class']) && !empty($validated['grade'])) {
            $validated['job_class_grade'] = trim($validated['job_class'] . ' / ' . $validated['grade']);
        } elseif (!empty($validated['job_class_grade'])) {
            $parts = explode('/', $validated['job_class_grade']);
            $validated['job_class'] = trim($parts[0] ?? '');
            $validated['grade'] = trim($parts[1] ?? '');
        }

        $maxOrder = $employee->careerHistories()->max('order_no') ?? 0;
        $validated['order_no'] = $maxOrder + 1;

        $employee->careerHistories()->create($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'riwayat-karir'])
            ->with('success', 'Riwayat karir berhasil ditambahkan.');
    }

    /**
     * Update Career History record.
     */
    public function updateCareerHistory(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $history = $employee->careerHistories()->findOrFail($id);

        $validated = $request->validate([
            'effective_date' => 'required|string|max:50',
            'department_section' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'job_class' => 'required_without:job_class_grade|nullable|string|max:50',
            'grade' => 'required_without:job_class_grade|nullable|string|max:50',
            'job_class_grade' => 'nullable|string|max:50',
            'change_type' => 'required|string|max:100',
            'notes' => 'nullable|string',
        ]);

        if (!empty($validated['job_class']) && !empty($validated['grade'])) {
            $validated['job_class_grade'] = trim($validated['job_class'] . ' / ' . $validated['grade']);
        } elseif (!empty($validated['job_class_grade'])) {
            $parts = explode('/', $validated['job_class_grade']);
            $validated['job_class'] = trim($parts[0] ?? '');
            $validated['grade'] = trim($parts[1] ?? '');
        }

        $history->update($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'riwayat-karir'])
            ->with('success', 'Data riwayat karir berhasil diperbarui.');
    }

    /**
     * Delete Career History record.
     */
    public function destroyCareerHistory(string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $history = $employee->careerHistories()->findOrFail($id);
        $history->delete();

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'riwayat-karir'])
            ->with('success', 'Data riwayat karir berhasil dihapus.');
    }

    /**
     * Update Talent Snapshot (POTASS score, Flying Risk, etc.)
     */
    public function updateTalentSnapshot(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'potass_current' => 'required|string|max:50',
            'performance_current' => 'required|string|max:20',
            'flying_risk' => 'required|string|in:LOW,MEDIUM,HIGH,Low Risk,Moderate Risk,High Risk',
            'flying_risk_reason' => 'nullable|string|max:255',
            'talent_pool_status' => 'required|string|in:YA,TIDAK',
        ]);

        $employee->update($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'talent-snapshot'])
            ->with('success', 'Data Talent Snapshot berhasil diperbarui.');
    }

    /**
     * C1. Update Performance 3 Tahun Terakhir (Dynamic Years / Legacy Batch Update)
     */
    public function updateTalentPerformance(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $notes = $request->input('performance_notes', $employee->performance_notes);
        if ($notes !== null) {
            $employee->performance_notes = $notes;
        }

        // Jika dikirim dari form dinamis 3 tahun terakhir (years & ratings)
        if ($request->has('years') && is_array($request->input('years'))) {
            $years = $request->input('years');
            $ratings = $request->input('ratings', []);

            $latestYear = 0;
            $latestRating = $employee->performance_current;

            foreach ($years as $idx => $yearVal) {
                $yr = (int) $yearVal;
                $rtg = strtoupper(trim($ratings[$idx] ?? '-'));

                $employee->performanceAppraisals()->updateOrCreate(
                    ['year' => $yr],
                    ['rating' => $rtg, 'notes' => $notes]
                );

                if ($yr === 2024) $employee->performance_fy24 = $rtg;
                elseif ($yr === 2025) $employee->performance_fy25 = $rtg;
                elseif ($yr === 2026) $employee->performance_fy26 = $rtg;

                if ($yr >= $latestYear) {
                    $latestYear = $yr;
                    $latestRating = $rtg;
                }
            }

            if ($latestRating) {
                $employee->performance_current = $latestRating;
            }
        } else {
            // Fallback input langsung
            if ($request->has('performance_fy24')) {
                $employee->performance_fy24 = $request->input('performance_fy24');
                $employee->performanceAppraisals()->updateOrCreate(['year' => 2024], ['rating' => $request->input('performance_fy24'), 'notes' => $notes]);
            }
            if ($request->has('performance_fy25')) {
                $employee->performance_fy25 = $request->input('performance_fy25');
                $employee->performanceAppraisals()->updateOrCreate(['year' => 2025], ['rating' => $request->input('performance_fy25'), 'notes' => $notes]);
            }
            if ($request->has('performance_fy26')) {
                $employee->performance_fy26 = $request->input('performance_fy26');
                $employee->performanceAppraisals()->updateOrCreate(['year' => 2026], ['rating' => $request->input('performance_fy26'), 'notes' => $notes]);
            }
            if ($request->has('performance_current')) {
                $employee->performance_current = $request->input('performance_current');
            }
        }

        $employee->save();

        // Otomatis sinkronisasi C1 poin, Baris HAV, dan C3 HAV Box
        TalentCalculatorService::syncEmployeeTalentSnapshot($employee);

        $tab = $request->input('tab', 'talent-snapshot');

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => $tab])
            ->with('success', 'Data Performance 3 Tahun Terakhir berhasil diperbarui.');
    }

    /**
     * Simpan atau perbarui nilai Performance Appraisal per tahun secara fleksibel (misal tahun 2027, 2028, dst.)
     */
    public function storeOrUpdatePerformance(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2099',
            'rating' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'tab' => 'nullable|string',
        ]);

        $year = (int) $validated['year'];
        $rating = strtoupper(trim($validated['rating']));
        $notes = $validated['notes'] ?? $employee->performance_notes;

        $employee->performanceAppraisals()->updateOrCreate(
            ['year' => $year],
            [
                'rating' => $rating,
                'notes' => $notes,
            ]
        );

        // Jika data tahun yang diinput merupakan tahun tertinggi, perbarui rating terkini karyawan
        $maxYear = $employee->performanceAppraisals()->max('year');
        if ($year >= $maxYear) {
            $employee->performance_current = $rating;
        }

        // Sinkronkan legacy kolom jika 2024, 2025, atau 2026
        if ($year === 2024) {
            $employee->performance_fy24 = $rating;
        } elseif ($year === 2025) {
            $employee->performance_fy25 = $rating;
        } elseif ($year === 2026) {
            $employee->performance_fy26 = $rating;
        }

        if ($notes) {
            $employee->performance_notes = $notes;
        }

        $employee->save();

        // Otomatis sinkronisasi C1 & C3
        TalentCalculatorService::syncEmployeeTalentSnapshot($employee);

        $tab = $request->input('tab', 'talent-snapshot');

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => $tab])
            ->with('success', "Data Performance Tahun {$year} (Rating: {$rating}) berhasil disimpan.");
    }

    /**
     * Hapus record performance appraisal tahun tertentu
     */
    public function destroyPerformance(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $record = $employee->performanceAppraisals()->findOrFail($id);
        $deletedYear = $record->year;
        $record->delete();

        // Update performance_current ke tahun terbaru yang masih ada
        $latestRecord = $employee->performanceAppraisals()->orderBy('year', 'desc')->first();
        if ($latestRecord) {
            $employee->performance_current = $latestRecord->rating;
            $employee->save();
        }

        // Otomatis sinkronisasi C1 & C3
        TalentCalculatorService::syncEmployeeTalentSnapshot($employee);

        $tab = $request->input('tab', 'talent-snapshot');

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => $tab])
            ->with('success', "Data Performance Tahun {$deletedYear} berhasil dihapus.");
    }

    /**
     * C2. Update Potential Assessment (POTASS)
     */
    public function updateTalentPotass(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'potass_score_prev' => 'required|string|max:50',
            'potass_period_prev' => 'nullable|string|max:50',
            'potass_position_prev' => 'required|string|max:100',
            'potass_category_prev' => 'required|string|max:50',
            'potass_assessor_prev' => 'required|string|max:100',

            'potass_score_last' => 'required|string|max:50',
            'potass_period_last' => 'nullable|string|max:50',
            'potass_position_last' => 'required|string|max:100',
            'potass_category_last' => 'required|string|max:50',
            'potass_assessor_last' => 'required|string|max:100',
        ]);

        $validated['potass_current'] = $validated['potass_score_last'] . ' (' . $validated['potass_category_last'] . ')';

        $employee->update($validated);

        // Otomatis sinkronisasi C2 ke Riwayat C6 (talent_assessments)
        TalentCalculatorService::syncPotassToTalentAssessments($employee, $validated);

        // Sinkronisasi ulang C3 HAV 16 Box
        TalentCalculatorService::syncEmployeeTalentSnapshot($employee);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'talent-snapshot'])
            ->with('success', 'Data Potential Assessment POTASS (C2) berhasil diperbarui dan otomatis memperbarui riwayat C6 & C3.');
    }

    /**
     * C3. Update HAV 16 Box & Talent Pool
     */
    public function updateTalentHavBox(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'hav_box_current' => 'required|string|max:50',
            'hav_box_category' => 'required|string|max:150',
            'talent_pool_status' => 'required|string|in:YA,TIDAK',
        ]);

        $employee->update($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'talent-snapshot'])
            ->with('success', 'Data HAV 16 Box & Status Talent Pool (C3) berhasil diperbarui.');
    }

    /**
     * C4. Store Key Strength
     */
    public function storeKeyStrength(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'strength' => 'required|string|max:255',
            'short_description' => 'required|string',
            'source' => 'required|string|max:255',
            'documentation' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,pdf|max:10240',
        ]);

        if ($request->hasFile('documentation')) {
            $file = $request->file('documentation');
            $cleanNik = preg_replace('/[^a-zA-Z0-9_-]/', '', $employee->nik);
            $filename = 'c4_' . $cleanNik . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/key_strengths');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $validated['documentation'] = '/uploads/key_strengths/' . $filename;
        }

        $maxOrder = $employee->keyStrengths()->max('order_no') ?? 0;
        $validated['order_no'] = $maxOrder + 1;

        $employee->keyStrengths()->create($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'talent-snapshot'])
            ->with('success', 'Data Kekuatan Utama (C4) berhasil ditambahkan.');
    }

    /**
     * C4. Update Key Strength
     */
    public function updateKeyStrength(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $strength = $employee->keyStrengths()->findOrFail($id);

        $validated = $request->validate([
            'strength' => 'required|string|max:255',
            'short_description' => 'required|string',
            'source' => 'required|string|max:255',
            'documentation' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,pdf|max:10240',
            'remove_documentation' => 'nullable|boolean',
        ]);

        if ($request->boolean('remove_documentation')) {
            if ($strength->documentation && file_exists(public_path($strength->documentation))) {
                @unlink(public_path($strength->documentation));
            }
            $validated['documentation'] = null;
        } elseif ($request->hasFile('documentation')) {
            if ($strength->documentation && file_exists(public_path($strength->documentation))) {
                @unlink(public_path($strength->documentation));
            }
            $file = $request->file('documentation');
            $cleanNik = preg_replace('/[^a-zA-Z0-9_-]/', '', $employee->nik);
            $filename = 'c4_' . $cleanNik . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/key_strengths');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $validated['documentation'] = '/uploads/key_strengths/' . $filename;
        } else {
            unset($validated['documentation']);
        }

        unset($validated['remove_documentation']);

        $strength->update($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'talent-snapshot'])
            ->with('success', 'Data Kekuatan Utama (C4) berhasil diperbarui.');
    }

    /**
     * C4. Destroy Key Strength
     */
    public function destroyKeyStrength(string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $strength = $employee->keyStrengths()->findOrFail($id);

        if ($strength->documentation && file_exists(public_path($strength->documentation))) {
            @unlink(public_path($strength->documentation));
        }

        $strength->delete();

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'talent-snapshot'])
            ->with('success', 'Data Kekuatan Utama (C4) berhasil dihapus.');
    }

    /**
     * C5. Update Flying Risk Assessment berdasarkan C5flyrisk.png
     */
    public function updateTalentFlyingRisk(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $growth = $request->input('flying_risk_career_growth', $employee->flying_risk_career_growth);
        $market = $request->input('flying_risk_job_market', $employee->flying_risk_job_market);
        $comp = $request->input('flying_risk_compensation', $employee->flying_risk_compensation);

        $calc = TalentCalculatorService::calculateFlyingRisk($growth, $market, $comp);

        $employee->update([
            'flying_risk' => $calc['risk_level'],
            'flying_risk_score' => $calc['total_score'],
            'flying_risk_career_growth' => $calc['career_growth'],
            'flying_risk_career_growth_pts' => $calc['career_growth_pts'],
            'flying_risk_job_market' => $calc['job_market'],
            'flying_risk_job_market_pts' => $calc['job_market_pts'],
            'flying_risk_compensation' => $calc['compensation'],
            'flying_risk_compensation_pts' => $calc['compensation_pts'],
            'flying_risk_reason' => $calc['interpretation'],
            'flying_risk_interpretation' => $calc['interpretation'],
        ]);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'talent-snapshot'])
            ->with('success', 'Data Flying Risk Assessment (C5) berhasil diperbarui (' . $calc['risk_level'] . ' - Skor: ' . $calc['total_score'] . '/6).');
    }

    /**
     * C6. Store Talent Assessment (Riwayat POTASS & 8 Behavior Competencies)
     * Otomatis sinkronisasi ke C2 (POTASS) dan C3 (HAV 16 Box)
     */
    public function storeTalentAssessment(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'assessment_date' => 'required|string|max:50',
            'position_standard' => 'required|string|max:100',
            'assessor' => 'required|string|max:100',
            'b1_vision_business' => 'nullable|numeric|min:1|max:5',
            'b2_customer_focus' => 'nullable|numeric|min:1|max:5',
            'b3_interpersonal_skill' => 'nullable|numeric|min:1|max:5',
            'b4_analysis_judgment' => 'nullable|numeric|min:1|max:5',
            'b5_planning_driving' => 'nullable|numeric|min:1|max:5',
            'b6_leading_motivating' => 'nullable|numeric|min:1|max:5',
            'b7_teamwork' => 'nullable|numeric|min:1|max:5',
            'b8_drive_courage_integrity' => 'nullable|numeric|min:1|max:5',
            'potass_score' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
        ]);

        $comp = TalentCalculatorService::calculateBehaviorCompetency($validated);

        $assessmentData = [
            'assessment_date' => $validated['assessment_date'],
            'position_standard' => $validated['position_standard'],
            'assessor' => $validated['assessor'],
            'b1_vision_business' => $comp['scores']['b1'],
            'b2_customer_focus' => $comp['scores']['b2'],
            'b3_interpersonal_skill' => $comp['scores']['b3'],
            'b4_analysis_judgment' => $comp['scores']['b4'],
            'b5_planning_driving' => $comp['scores']['b5'],
            'b6_leading_motivating' => $comp['scores']['b6'],
            'b7_teamwork' => $comp['scores']['b7'],
            'b8_drive_courage_integrity' => $comp['scores']['b8'],
            'weighted_score' => $comp['weighted_score'],
            'score_percentage' => $comp['percentage'],
            'kolom_hav' => $comp['kolom_hav'],
            'potass_score' => $comp['percentage'] . '%',
            'category' => $comp['category'],
        ];

        $employee->talentAssessments()->create($assessmentData);

        // Otomatis sinkronisasi ke C2 dan C3
        TalentCalculatorService::syncEmployeeTalentSnapshot($employee);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'talent-snapshot'])
            ->with('success', 'Data Riwayat POTASS Assessment (C6) berhasil ditambahkan dan otomatis memperbarui C2 & C3.');
    }

    /**
     * C6. Update Talent Assessment (Riwayat POTASS & 8 Behavior Competencies)
     */
    public function updateTalentAssessment(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $assessment = $employee->talentAssessments()->findOrFail($id);

        $validated = $request->validate([
            'assessment_date' => 'required|string|max:50',
            'position_standard' => 'required|string|max:100',
            'assessor' => 'required|string|max:100',
            'b1_vision_business' => 'nullable|numeric|min:1|max:5',
            'b2_customer_focus' => 'nullable|numeric|min:1|max:5',
            'b3_interpersonal_skill' => 'nullable|numeric|min:1|max:5',
            'b4_analysis_judgment' => 'nullable|numeric|min:1|max:5',
            'b5_planning_driving' => 'nullable|numeric|min:1|max:5',
            'b6_leading_motivating' => 'nullable|numeric|min:1|max:5',
            'b7_teamwork' => 'nullable|numeric|min:1|max:5',
            'b8_drive_courage_integrity' => 'nullable|numeric|min:1|max:5',
            'potass_score' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
        ]);

        $comp = TalentCalculatorService::calculateBehaviorCompetency($validated);

        $assessment->update([
            'assessment_date' => $validated['assessment_date'],
            'position_standard' => $validated['position_standard'],
            'assessor' => $validated['assessor'],
            'b1_vision_business' => $comp['scores']['b1'],
            'b2_customer_focus' => $comp['scores']['b2'],
            'b3_interpersonal_skill' => $comp['scores']['b3'],
            'b4_analysis_judgment' => $comp['scores']['b4'],
            'b5_planning_driving' => $comp['scores']['b5'],
            'b6_leading_motivating' => $comp['scores']['b6'],
            'b7_teamwork' => $comp['scores']['b7'],
            'b8_drive_courage_integrity' => $comp['scores']['b8'],
            'weighted_score' => $comp['weighted_score'],
            'score_percentage' => $comp['percentage'],
            'kolom_hav' => $comp['kolom_hav'],
            'potass_score' => $comp['percentage'] . '%',
            'category' => $comp['category'],
        ]);

        // Otomatis sinkronisasi ke C2 dan C3
        TalentCalculatorService::syncEmployeeTalentSnapshot($employee);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'talent-snapshot'])
            ->with('success', 'Data Riwayat POTASS Assessment (C6) berhasil diperbarui dan otomatis memperbarui C2 & C3.');
    }

    /**
     * C6. Destroy Talent Assessment (Riwayat POTASS)
     */
    public function destroyTalentAssessment(string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $assessment = $employee->talentAssessments()->findOrFail($id);
        $assessment->delete();

        TalentCalculatorService::syncEmployeeTalentSnapshot($employee);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'talent-snapshot'])
            ->with('success', 'Data Riwayat POTASS Assessment (C6) berhasil dihapus.');
    }

    /**
     * Update Individual Career Plan (Arah Karir)
     */
    public function updateCareerPlan(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'interested_area' => 'nullable|string|max:255',
            'recommended_career_path' => 'nullable|string|max:100',
            'next_possible_position' => 'nullable|string|max:255',
            'next_possible_department' => 'nullable|string|max:255',
            'career_projection' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'career_path_choice' => 'nullable|string|max:100',
            'target_position_title' => 'nullable|string|max:255',
            'target_job_class_grade' => 'nullable|string|max:100',
            'target_timeframe' => 'nullable|string|max:100',
            'target_readiness' => 'nullable|string|max:100',
        ]);

        $careerPlanData = array_filter([
            'interested_area' => $request->input('interested_area'),
            'recommended_career_path' => $request->input('recommended_career_path'),
            'next_possible_position' => $request->input('next_possible_position'),
            'next_possible_department' => $request->input('next_possible_department'),
            'career_projection' => $request->input('career_projection'),
            'notes' => $request->input('notes'),
        ], fn($v) => !is_null($v));

        if (!empty($careerPlanData)) {
            $employee->careerPlan()->updateOrCreate(['employee_id' => $employee->id], $careerPlanData);
        }

        // Update employee model fields
        $empUpdates = [];
        foreach (['career_path_choice', 'target_position_title', 'target_job_class_grade', 'target_timeframe', 'target_readiness', 'next_possible_position', 'career_projection'] as $field) {
            if ($request->has($field)) {
                $empUpdates[$field] = $request->input($field);
            }
        }
        if (!empty($empUpdates)) {
            $employee->update($empUpdates);
        }

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'individual-careerplan'])
            ->with('success', 'Informasi Arah Karir (D1) berhasil diperbarui.');
    }

    /**
     * Store new Job Class Plan.
     */
    public function storeJobClassPlan(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'plan_year' => 'required|string|max:10',
            'projected_age' => 'required|integer',
            'job_class' => 'required|string|max:20',
            'grade' => 'required|string|max:20',
            'change_type' => 'required|string|max:100',
            'target_position' => 'nullable|string|max:255',
            'target_department' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $maxOrder = $employee->jobClassPlans()->max('order_no') ?? 0;
        $validated['order_no'] = $maxOrder + 1;

        $employee->jobClassPlans()->create($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'individual-careerplan'])
            ->with('success', 'Rencana Job Class & Grade (D2) berhasil ditambahkan.');
    }

    /**
     * Update Job Class Plan.
     */
    public function updateJobClassPlan(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $plan = $employee->jobClassPlans()->findOrFail($id);

        $validated = $request->validate([
            'plan_year' => 'required|string|max:10',
            'projected_age' => 'required|integer',
            'job_class' => 'required|string|max:20',
            'grade' => 'required|string|max:20',
            'change_type' => 'required|string|max:100',
            'target_position' => 'nullable|string|max:255',
            'target_department' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $plan->update($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'individual-careerplan'])
            ->with('success', 'Rencana Job Class & Grade (D2) berhasil diperbarui.');
    }

    /**
     * Delete Job Class Plan.
     */
    public function destroyJobClassPlan(string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $plan = $employee->jobClassPlans()->findOrFail($id);
        $plan->delete();

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'individual-careerplan'])
            ->with('success', 'Rencana Job Class & Grade (D2) berhasil dihapus.');
    }

    /**
     * Store new Succession Position (E1).
     */
    public function storeSuccessionPosition(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'target_position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'position_level' => 'required|string|max:100',
            'reason' => 'nullable|string',
            'needed_at' => 'required|string|max:50',
            'readiness' => 'nullable|string|max:50',
        ]);

        $maxOrder = $employee->successionPositions()->max('order_no') ?? 0;
        $validated['order_no'] = $maxOrder + 1;
        $validated['is_active'] = true;

        $employee->successionPositions()->create($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'status-suksesi'])
            ->with('success', 'Posisi jabatan suksesi (E1) berhasil ditambahkan.');
    }

    /**
     * Update Succession Position (E1).
     */
    public function updateSuccessionPosition(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $pos = $employee->successionPositions()->findOrFail($id);

        $validated = $request->validate([
            'target_position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'position_level' => 'required|string|max:100',
            'reason' => 'nullable|string',
            'needed_at' => 'required|string|max:50',
            'readiness' => 'nullable|string|max:50',
        ]);

        $pos->update($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'status-suksesi'])
            ->with('success', 'Posisi jabatan suksesi (E1) berhasil diperbarui.');
    }

    /**
     * Delete Succession Position (E1).
     */
    public function destroySuccessionPosition(string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $pos = $employee->successionPositions()->findOrFail($id);
        $pos->delete();

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'status-suksesi'])
            ->with('success', 'Posisi jabatan suksesi (E1) berhasil dihapus.');
    }

    /**
     * Store new Succession Candidate (E2).
     */
    public function storeSuccessionCandidate(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'candidate_name' => 'required|string|max:255',
            'current_department' => 'required|string|max:255',
            'current_position' => 'required|string|max:255',
            'readiness' => 'required|string|max:50',
            'needed_at' => 'required|string|max:50',
            'ranking' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $maxOrder = $employee->successionCandidates()->max('order_no') ?? 0;
        $validated['order_no'] = $maxOrder + 1;

        $employee->successionCandidates()->create($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'status-suksesi'])
            ->with('success', 'Calon pengganti suksesi (E2) berhasil ditambahkan.');
    }

    /**
     * Update Succession Candidate (E2).
     */
    public function updateSuccessionCandidate(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $cand = $employee->successionCandidates()->findOrFail($id);

        $validated = $request->validate([
            'candidate_name' => 'required|string|max:255',
            'current_department' => 'required|string|max:255',
            'current_position' => 'required|string|max:255',
            'readiness' => 'required|string|max:50',
            'needed_at' => 'required|string|max:50',
            'ranking' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $cand->update($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'status-suksesi'])
            ->with('success', 'Data calon pengganti suksesi (E2) berhasil diperbarui.');
    }

    /**
     * Delete Succession Candidate (E2).
     */
    public function destroySuccessionCandidate(string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $cand = $employee->successionCandidates()->findOrFail($id);
        $cand->delete();

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'status-suksesi'])
            ->with('success', 'Calon pengganti suksesi (E2) berhasil dihapus.');
    }

    /**
     * Development Gap: Update Target Position & Method
     */
    public function updateDevelopmentGapTarget(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'target_position_gap' => 'required|string|max:255',
            'target_department_gap' => 'required|string|max:255',
            'gap_method' => 'required|string|max:255',
        ]);

        $employee->update($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'development-gap'])
            ->with('success', 'Informasi Target Posisi & Metode Gap berhasil diperbarui.');
    }

    /**
     * Development Gap: Store Competency Gap
     */
    public function storeCompetencyGap(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'competency' => 'required|string|max:255',
            'competency_type' => 'nullable|string|in:Manajerial,Technical',
            'current_level' => 'required|integer|min:1|max:5',
            'current_desc' => 'nullable|string|max:255',
            'standard_level' => 'required|integer|min:1|max:5',
            'standard_desc' => 'nullable|string|max:255',
            'expected_improvement' => 'nullable|string',
        ]);

        if (empty($validated['competency_type'])) {
            $validated['competency_type'] = 'Manajerial';
        }

        $gap = max(0, $validated['standard_level'] - $validated['current_level']);
        $validated['gap'] = $gap;
        $validated['gap_severity'] = $gap >= 2 ? 'Tinggi' : ($gap === 1 ? 'Sedang' : 'Rendah');

        $maxOrder = $employee->competencyGaps()->max('order_no') ?? 0;
        $validated['order_no'] = $maxOrder + 1;

        $employee->competencyGaps()->create($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'development-gap'])
            ->with('success', 'Gap kompetensi baru berhasil ditambahkan.');
    }

    /**
     * Development Gap: Update Competency Gap
     */
    public function updateCompetencyGap(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $gapRow = $employee->competencyGaps()->findOrFail($id);

        $validated = $request->validate([
            'competency' => 'required|string|max:255',
            'competency_type' => 'nullable|string|in:Manajerial,Technical',
            'current_level' => 'required|integer|min:1|max:5',
            'current_desc' => 'nullable|string|max:255',
            'standard_level' => 'required|integer|min:1|max:5',
            'standard_desc' => 'nullable|string|max:255',
            'expected_improvement' => 'nullable|string',
        ]);

        if (empty($validated['competency_type'])) {
            $validated['competency_type'] = $gapRow->competency_type ?: 'Manajerial';
        }

        $gap = max(0, $validated['standard_level'] - $validated['current_level']);
        $validated['gap'] = $gap;
        $validated['gap_severity'] = $gap >= 2 ? 'Tinggi' : ($gap === 1 ? 'Sedang' : 'Rendah');

        $gapRow->update($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'development-gap'])
            ->with('success', 'Data gap kompetensi berhasil diperbarui.');
    }

    /**
     * Development Gap: Delete Competency Gap
     */
    public function destroyCompetencyGap(string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $gapRow = $employee->competencyGaps()->findOrFail($id);
        $gapRow->delete();

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'development-gap'])
            ->with('success', 'Data gap kompetensi berhasil dihapus.');
    }

    /**
     * IDP: Update Readiness Summary
     */
    public function updateIdpSummary(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'readiness_level' => 'required|string|max:20',
            'idp_readiness_desc' => 'required|string|max:100',
            'retirement_year' => 'nullable|string|max:20',
            'idp_primary_goal' => 'nullable|string',
        ]);

        $employee->update($validated);

        $msg = ($validated['readiness_level'] === '-' || empty($validated['readiness_level']))
            ? 'Ringkasan Kesiapan & Fokus IDP berhasil dihapus/direset.'
            : 'Ringkasan Kesiapan & Fokus IDP berhasil diperbarui.';

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'individual-developmentplan'])
            ->with('success', $msg);
    }

    /**
     * Sinkronisasi dua arah antara IDP Action Plans dan Development Reviews.
     * Memastikan jumlah dan daftar kompetensi di IDP dan Review Pengembangan selalu sama persis
     * (1 baris Review per 1 Kompetensi unik di IDP).
     */
    private function syncIdpAndReviews(Employee $employee): void
    {
        $idpPlans = $employee->idpActionPlans()->get();
        $reviews = $employee->developmentReviews()->get();

        // Jika IDP kosong tetapi ada Review, buatkan IDP dari Review
        if ($idpPlans->isEmpty() && $reviews->isNotEmpty()) {
            foreach ($reviews as $rev) {
                $isDone = ($rev->status === 'Meningkat' || (int)$rev->current_level >= (int)$rev->target_level);
                $employee->idpActionPlans()->create([
                    'order_no' => $rev->order_no ?? 1,
                    'competency' => $rev->competency,
                    'competency_type' => $rev->competency_type ?: 'Manajerial',
                    'specific_goal' => 'Pengembangan kompetensi ' . $rev->competency,
                    'development_methods' => 'Training, On the Job',
                    'activity_program' => 'Pelatihan dan implementasi ' . $rev->competency,
                    'pic_supporter' => 'Atasan Langsung',
                    'start_date' => 'Jun 2026',
                    'end_date' => 'Mei 2027',
                    'success_indicator' => 'Tingkat kompetensi ' . $rev->competency . ' mencapai target.',
                    'status' => $isDone ? 'Selesai' : 'On Progress',
                    'progress_percent' => $isDone ? 100 : 70,
                ]);
            }
            return;
        }

        // Kelompokkan IDP berdasarkan nama kompetensi unik (case-insensitive)
        $idpGroupedByComp = [];
        foreach ($idpPlans as $p) {
            $key = strtolower(trim($p->competency));
            if (!isset($idpGroupedByComp[$key])) {
                $idpGroupedByComp[$key] = collect();
            }
            $idpGroupedByComp[$key]->push($p);
        }

        // 1. Bersihkan Review: hapus kompetensi yang tidak ada di IDP, dan hapus duplikat review untuk kompetensi yang sama
        $reviewCompMap = [];
        foreach ($reviews as $rev) {
            $key = strtolower(trim($rev->competency));
            if (!isset($idpGroupedByComp[$key])) {
                $rev->delete();
            } elseif (isset($reviewCompMap[$key])) {
                // Duplikat untuk kompetensi yang sama -> hapus baris duplikat
                $rev->delete();
            } else {
                $reviewCompMap[$key] = $rev;
            }
        }

        // 2. Tambah Review untuk setiap kompetensi unik IDP yang belum ada di Review
        $currentMaxReviewOrder = $employee->developmentReviews()->max('order_no') ?? 0;
        foreach ($idpGroupedByComp as $key => $plansForComp) {
            $firstPlan = $plansForComp->first();
            if (!isset($reviewCompMap[$key])) {
                $currentMaxReviewOrder++;
                $isDone = $plansForComp->every(function($plan) {
                    return in_array(strtolower(trim($plan->status ?? '')), ['selesai', 'completed', 'done']) || (int)($plan->progress_percent ?? 0) >= 100;
                });
                
                $gap = $employee->competencyGaps()->whereRaw('LOWER(competency) = ?', [$key])->first();
                $prevLevel = $gap ? (int)$gap->current_level : 3;
                $targetLevel = $gap ? (int)$gap->standard_level : 5;
                $avgProg = (int) round($plansForComp->avg('progress_percent') ?? 0);
                $currLevel = $isDone ? $targetLevel : ($prevLevel + ($avgProg >= 60 ? 1 : 0));
                $currLevel = min($targetLevel, $currLevel);
                $growth = $currLevel - $prevLevel;
                $status = $growth > 0 ? 'Meningkat' : ($growth === 0 ? 'Stabil' : 'Belum Meningkat');

                $newRev = $employee->developmentReviews()->create([
                    'order_no' => $currentMaxReviewOrder,
                    'period' => 'Juni 2026 – Mei 2027',
                    'competency' => $firstPlan->competency,
                    'competency_type' => $firstPlan->competency_type ?: 'Manajerial',
                    'previous_level' => $prevLevel,
                    'current_level' => $currLevel,
                    'target_level' => $targetLevel,
                    'growth' => $growth,
                    'status' => $status,
                    'reviewer_notes' => 'Tersinkronisasi otomatis dari rencana aksi IDP.',
                ]);
                $reviewCompMap[$key] = $newRev;
            } else {
                // Pastikan competency_type sama jika ada perbedaan
                $existingRev = $reviewCompMap[$key];
                if (!empty($firstPlan->competency_type) && $existingRev->competency_type !== $firstPlan->competency_type) {
                    $existingRev->update(['competency_type' => $firstPlan->competency_type]);
                }
            }
        }
    }

    /**
     * Format date input from HTML datepicker (YYYY-MM-DD) to IDP table format (e.g. Jun 2026 or 15 Jun 2026)
     */
    private function formatIdpDate(?string $dateStr): ?string
    {
        if (empty($dateStr)) return null;
        $dateStr = trim($dateStr);
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $dateStr, $m)) {
            $y = $m[1];
            $mo = (int)$m[2];
            $d = (int)$m[3];
            $months = [
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
            ];
            $moName = $months[$mo] ?? 'Jan';
            return $d === 1 ? "{$moName} {$y}" : "{$d} {$moName} {$y}";
        }
        return $dateStr;
    }

    /**
     * IDP: Store Action Plan
     */
    public function storeIdpActionPlan(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $pics = $request->input('pic_supporter');
        if (is_array($pics)) {
            $pics = implode(', ', array_filter(array_map('trim', $pics)));
        } else {
            $pics = trim((string)$pics);
        }

        if (empty($pics)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal satu PIC / Pendukung.')->withInput();
        }

        $request->merge(['pic_supporter' => $pics]);

        $validated = $request->validate([
            'competency' => 'required|string|max:255',
            'competency_type' => 'nullable|string|max:50',
            'specific_goal' => 'nullable|string',
            'development_methods' => 'required|string|max:255',
            'activity_program' => 'required|string',
            'pic_supporter' => 'required|string|max:255',
            'start_date' => 'required|string|max:50',
            'end_date' => 'required|string|max:50',
            'success_indicator' => 'nullable|string',
            'success_indicator_custom' => 'nullable|string',
            'status' => 'required|string|max:50',
            'progress_percent' => 'required|integer|min:0|max:100',
        ]);

        if ($request->input('success_indicator') === 'custom' && $request->filled('success_indicator_custom')) {
            $validated['success_indicator'] = $request->input('success_indicator_custom');
        }
        unset($validated['success_indicator_custom']);

        $validated['start_date'] = $this->formatIdpDate($validated['start_date']);
        $validated['end_date'] = $this->formatIdpDate($validated['end_date']);

        if (empty($validated['competency_type'])) {
            $validated['competency_type'] = 'Manajerial';
        }

        $maxOrder = $employee->idpActionPlans()->max('order_no') ?? 0;
        $validated['order_no'] = $maxOrder + 1;

        $employee->idpActionPlans()->create($validated);
        $this->syncIdpAndReviews($employee);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'individual-developmentplan'])
            ->with('success', 'Rencana Aksi IDP berhasil ditambahkan dan disinkronkan ke Review Pengembangan.');
    }

    /**
     * IDP: Update Action Plan
     */
    public function updateIdpActionPlan(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $plan = $employee->idpActionPlans()->findOrFail($id);
        $oldCompetency = $plan->competency;

        $pics = $request->input('pic_supporter');
        if (is_array($pics)) {
            $pics = implode(', ', array_filter(array_map('trim', $pics)));
        } else {
            $pics = trim((string)$pics);
        }

        if (empty($pics)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal satu PIC / Pendukung.')->withInput();
        }

        $request->merge(['pic_supporter' => $pics]);

        $validated = $request->validate([
            'competency' => 'required|string|max:255',
            'competency_type' => 'nullable|string|max:50',
            'specific_goal' => 'nullable|string',
            'development_methods' => 'required|string|max:255',
            'activity_program' => 'required|string',
            'pic_supporter' => 'required|string|max:255',
            'start_date' => 'required|string|max:50',
            'end_date' => 'required|string|max:50',
            'success_indicator' => 'nullable|string',
            'success_indicator_custom' => 'nullable|string',
            'status' => 'required|string|max:50',
            'progress_percent' => 'required|integer|min:0|max:100',
        ]);

        if ($request->input('success_indicator') === 'custom' && $request->filled('success_indicator_custom')) {
            $validated['success_indicator'] = $request->input('success_indicator_custom');
        }
        unset($validated['success_indicator_custom']);

        $validated['start_date'] = $this->formatIdpDate($validated['start_date']);
        $validated['end_date'] = $this->formatIdpDate($validated['end_date']);

        if (empty($validated['competency_type'])) {
            $validated['competency_type'] = $plan->competency_type ?: 'Manajerial';
        }

        $plan->update($validated);

        // Update review yang bersangkutan jika ada
        $existingReview = $employee->developmentReviews()
            ->whereRaw('LOWER(competency) = ?', [strtolower(trim($oldCompetency))])
            ->first();

        if ($existingReview) {
            $existingReview->update([
                'competency' => $validated['competency'],
                'competency_type' => $validated['competency_type'],
            ]);
        }

        $this->syncIdpAndReviews($employee);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'individual-developmentplan'])
            ->with('success', 'Rencana Aksi IDP berhasil diperbarui dan disinkronkan ke Review Pengembangan.');
    }

    /**
     * IDP: Delete Action Plan
     */
    public function destroyIdpActionPlan(string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $plan = $employee->idpActionPlans()->findOrFail($id);
        $compName = $plan->competency;
        $plan->delete();

        // Hapus juga dari development reviews HANYA JIKA tidak ada action plan lain dengan kompetensi yang sama
        $remainingWithComp = $employee->idpActionPlans()
            ->whereRaw('LOWER(competency) = ?', [strtolower(trim($compName))])
            ->count();

        if ($remainingWithComp === 0) {
            $employee->developmentReviews()
                ->whereRaw('LOWER(competency) = ?', [strtolower(trim($compName))])
                ->delete();
        }

        $this->syncIdpAndReviews($employee);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'individual-developmentplan'])
            ->with('success', "Rencana Aksi IDP berhasil dihapus.");
    }

    /**
     * Riwayat Pelatihan - Store Training History
     */
    public function storeTrainingHistory(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'training_name' => 'required|string|max:255',
            'start_date' => 'nullable|string|max:50',
            'end_date' => 'nullable|string|max:50',
            'training_date' => 'nullable|string|max:100',
            'category' => 'required|string|max:50',
            'training_type' => 'required|string|max:50',
            'organizer' => 'nullable|string|max:255',
            'duration_hours' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'documentation' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,pdf|max:10240',
        ]);

        if (empty($validated['training_date']) && !empty($validated['start_date'])) {
            $validated['training_date'] = \App\Models\TrainingHistory::formatDateRange($validated['start_date'], $validated['end_date'] ?? null);
        }

        if ($request->hasFile('documentation')) {
            $file = $request->file('documentation');
            $cleanNik = preg_replace('/[^a-zA-Z0-9_-]/', '', $employee->nik);
            $filename = 'tr_' . $cleanNik . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/training_histories');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $validated['documentation'] = '/uploads/training_histories/' . $filename;
        }

        $maxOrder = $employee->trainingHistories()->max('order_no') ?? 0;
        $validated['order_no'] = $maxOrder + 1;

        $employee->trainingHistories()->create($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'riwayat-pelatihan'])
            ->with('success', 'Riwayat pelatihan berhasil ditambahkan.');
    }

    /**
     * Riwayat Pelatihan - Update Training History
     */
    public function updateTrainingHistory(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $training = $employee->trainingHistories()->findOrFail($id);

        $validated = $request->validate([
            'training_name' => 'required|string|max:255',
            'start_date' => 'nullable|string|max:50',
            'end_date' => 'nullable|string|max:50',
            'training_date' => 'nullable|string|max:100',
            'category' => 'required|string|max:50',
            'training_type' => 'required|string|max:50',
            'organizer' => 'nullable|string|max:255',
            'duration_hours' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'documentation' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,pdf|max:10240',
            'remove_documentation' => 'nullable|boolean',
        ]);

        if (empty($validated['training_date']) && !empty($validated['start_date'])) {
            $validated['training_date'] = \App\Models\TrainingHistory::formatDateRange($validated['start_date'], $validated['end_date'] ?? null);
        }

        if ($request->boolean('remove_documentation')) {
            if ($training->documentation && file_exists(public_path($training->documentation))) {
                @unlink(public_path($training->documentation));
            }
            $validated['documentation'] = null;
        } elseif ($request->hasFile('documentation')) {
            if ($training->documentation && file_exists(public_path($training->documentation))) {
                @unlink(public_path($training->documentation));
            }
            $file = $request->file('documentation');
            $cleanNik = preg_replace('/[^a-zA-Z0-9_-]/', '', $employee->nik);
            $filename = 'tr_' . $cleanNik . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/training_histories');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $validated['documentation'] = '/uploads/training_histories/' . $filename;
        } else {
            unset($validated['documentation']);
        }

        unset($validated['remove_documentation']);

        $training->update($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'riwayat-pelatihan'])
            ->with('success', 'Data riwayat pelatihan berhasil diperbarui.');
    }

    /**
     * Riwayat Pelatihan - Destroy Training History
     */
    public function destroyTrainingHistory(string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $training = $employee->trainingHistories()->findOrFail($id);

        if ($training->documentation && file_exists(public_path($training->documentation))) {
            @unlink(public_path($training->documentation));
        }

        $training->delete();

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'riwayat-pelatihan'])
            ->with('success', 'Data riwayat pelatihan berhasil dihapus.');
    }

    /**
     * Riwayat Pelatihan (Bagian C) - Store Certification
     */
    public function storeCertification(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'issuer' => 'nullable|string|max:255',
            'obtained_date' => 'nullable|string|max:100',
            'valid_until' => 'nullable|string|max:100',
            'is_lifetime' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'documentation' => 'required|file|mimes:jpeg,png,jpg,webp,gif,pdf|max:10240',
        ], [
            'name.required' => 'Nama sertifikasi wajib diisi.',
            'documentation.required' => 'Dokumen bukti sertifikasi wajib diunggah sebelum menyimpan sertifikasi.',
            'documentation.file' => 'Dokumen bukti sertifikasi harus berupa berkas file.',
            'documentation.mimes' => 'Format berkas dokumen sertifikasi harus berupa PDF, PNG, JPG, JPEG, atau WEBP.',
            'documentation.max' => 'Ukuran berkas dokumen sertifikasi maksimal 10 MB.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        if ($request->boolean('is_lifetime')) {
            $validated['valid_until'] = 'Seumur Hidup';
        } elseif (!empty($validated['valid_until'])) {
            $validated['valid_until'] = \App\Models\Certification::formatIndonesianDate($validated['valid_until']);
        }

        if (!empty($validated['obtained_date'])) {
            $validated['obtained_date'] = \App\Models\Certification::formatIndonesianDate($validated['obtained_date']);
        }

        unset($validated['is_lifetime']);

        if ($request->hasFile('documentation')) {
            $file = $request->file('documentation');
            $cleanNik = preg_replace('/[^a-zA-Z0-9_-]/', '', $employee->nik);
            $filename = 'cert_' . $cleanNik . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/certifications');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $validated['documentation'] = '/uploads/certifications/' . $filename;
        }

        $maxOrder = $employee->certifications()->max('order_no') ?? 0;
        $validated['order_no'] = $maxOrder + 1;

        $employee->certifications()->create($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'riwayat-pelatihan'])
            ->with('success', 'Data sertifikasi berhasil ditambahkan.');
    }

    /**
     * Riwayat Pelatihan (Bagian C) - Update Certification
     */
    public function updateCertification(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $cert = $employee->certifications()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'issuer' => 'nullable|string|max:255',
            'obtained_date' => 'nullable|string|max:100',
            'valid_until' => 'nullable|string|max:100',
            'is_lifetime' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'documentation' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,pdf|max:10240',
            'remove_documentation' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama sertifikasi wajib diisi.',
            'documentation.file' => 'Dokumen bukti sertifikasi harus berupa berkas file.',
            'documentation.mimes' => 'Format berkas dokumen sertifikasi harus berupa PDF, PNG, JPG, JPEG, atau WEBP.',
            'documentation.max' => 'Ukuran berkas dokumen sertifikasi maksimal 10 MB.',
        ]);

        // Validasi wajib dokumen: sertifikasi tidak boleh disimpan jika saat ini belum punya dokumen dan tidak mengupload
        if (empty($cert->documentation) && !$request->hasFile('documentation')) {
            return redirect()->back()
                ->withErrors(['documentation' => 'Dokumen bukti sertifikasi wajib diunggah sebelum menyimpan sertifikasi.'])
                ->with('error', 'Dokumen bukti sertifikasi wajib diunggah sebelum menyimpan sertifikasi.')
                ->withInput();
        }

        // Tidak boleh menghapus dokumen tanpa mengunggah dokumen pengganti baru
        if ($request->boolean('remove_documentation') && !$request->hasFile('documentation')) {
            return redirect()->back()
                ->withErrors(['documentation' => 'Dokumen bukti sertifikasi tidak boleh dihapus tanpa mengunggah dokumen pengganti.'])
                ->with('error', 'Dokumen bukti sertifikasi wajib ada. Tidak boleh dihapus tanpa mengunggah berkas dokumen pengganti.')
                ->withInput();
        }

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        if ($request->boolean('is_lifetime')) {
            $validated['valid_until'] = 'Seumur Hidup';
        } elseif (!empty($validated['valid_until'])) {
            $validated['valid_until'] = \App\Models\Certification::formatIndonesianDate($validated['valid_until']);
        }

        if (!empty($validated['obtained_date'])) {
            $validated['obtained_date'] = \App\Models\Certification::formatIndonesianDate($validated['obtained_date']);
        }

        unset($validated['is_lifetime']);

        if ($request->hasFile('documentation')) {
            if ($cert->documentation && file_exists(public_path($cert->documentation))) {
                @unlink(public_path($cert->documentation));
            }
            $file = $request->file('documentation');
            $cleanNik = preg_replace('/[^a-zA-Z0-9_-]/', '', $employee->nik);
            $filename = 'cert_' . $cleanNik . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/certifications');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $validated['documentation'] = '/uploads/certifications/' . $filename;
        } else {
            unset($validated['documentation']);
        }

        unset($validated['remove_documentation']);

        $cert->update($validated);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'riwayat-pelatihan'])
            ->with('success', 'Data sertifikasi berhasil diperbarui.');
    }

    /**
     * Riwayat Pelatihan (Bagian C) - Destroy Certification
     */
    public function destroyCertification(string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $cert = $employee->certifications()->findOrFail($id);

        if ($cert->documentation && file_exists(public_path($cert->documentation))) {
            @unlink(public_path($cert->documentation));
        }

        $cert->delete();

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'riwayat-pelatihan'])
            ->with('success', 'Data sertifikasi berhasil dihapus.');
    }

    /**
     * Store Review Hasil Pengembangan (Manajerial / Technical)
     */
    public function storeDevelopmentReview(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'competency' => 'required|string|max:255',
            'competency_type' => 'nullable|string|in:Manajerial,Technical',
            'period' => 'nullable|string|max:100',
            'previous_level' => 'required|integer|min:0|max:10',
            'current_level' => 'required|integer|min:0|max:10',
            'target_level' => 'required|integer|min:0|max:10',
            'status' => 'nullable|string|max:50',
            'reviewer_notes' => 'nullable|string',
        ]);

        if (empty($validated['competency_type'])) {
            $validated['competency_type'] = 'Manajerial';
        }

        if (empty($validated['period'])) {
            $validated['period'] = 'Juni 2026 – Mei 2027';
        }

        $growth = (int)$validated['current_level'] - (int)$validated['previous_level'];
        $validated['growth'] = $growth;

        if (empty($validated['status'])) {
            if ($growth > 0) {
                $validated['status'] = 'Meningkat';
            } elseif ($growth === 0) {
                $validated['status'] = 'Stabil';
            } else {
                $validated['status'] = 'Belum Meningkat';
            }
        }

        $maxOrder = $employee->developmentReviews()->max('order_no') ?? 0;
        $validated['order_no'] = $maxOrder + 1;

        $employee->developmentReviews()->create($validated);

        // Sinkronisasi otomatis ke IDP Action Plan jika belum ada rencana aksi untuk kompetensi ini
        $existingIdp = $employee->idpActionPlans()->whereRaw('LOWER(competency) = ?', [strtolower(trim($validated['competency']))])->first();
        if (!$existingIdp) {
            $isDone = ($validated['status'] === 'Meningkat' || (int)$validated['current_level'] >= (int)$validated['target_level']);
            $maxIdpOrder = $employee->idpActionPlans()->max('order_no') ?? 0;
            $employee->idpActionPlans()->create([
                'order_no' => $maxIdpOrder + 1,
                'competency' => $validated['competency'],
                'competency_type' => $validated['competency_type'] ?? 'Manajerial',
                'specific_goal' => 'Pengembangan kompetensi ' . $validated['competency'],
                'development_methods' => 'Training, On the Job',
                'activity_program' => 'Pelatihan dan implementasi ' . $validated['competency'],
                'pic_supporter' => 'Atasan Langsung',
                'start_date' => 'Jun 2026',
                'end_date' => 'Mei 2027',
                'success_indicator' => 'Tingkat kompetensi ' . $validated['competency'] . ' mencapai target.',
                'status' => $isDone ? 'Selesai' : 'On Progress',
                'progress_percent' => $isDone ? 100 : min(90, max(50, (int)round(((int)$validated['current_level'] / max(1, (int)$validated['target_level'])) * 100))),
            ]);
        }

        $this->syncIdpAndReviews($employee);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'review-pengembangan'])
            ->with('success', "Review kompetensi {$validated['competency_type']} berhasil ditambahkan dan disinkronkan ke IDP.");
    }

    /**
     * Update Review Hasil Pengembangan (Manajerial / Technical)
     */
    public function updateDevelopmentReview(Request $request, string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $review = $employee->developmentReviews()->findOrFail($id);

        $validated = $request->validate([
            'competency' => 'required|string|max:255',
            'competency_type' => 'nullable|string|in:Manajerial,Technical',
            'period' => 'nullable|string|max:100',
            'previous_level' => 'required|integer|min:0|max:10',
            'current_level' => 'required|integer|min:0|max:10',
            'target_level' => 'required|integer|min:0|max:10',
            'status' => 'nullable|string|max:50',
            'reviewer_notes' => 'nullable|string',
        ]);

        if (empty($validated['competency_type'])) {
            $validated['competency_type'] = $review->competency_type ?: 'Manajerial';
        }

        $growth = (int)$validated['current_level'] - (int)$validated['previous_level'];
        $validated['growth'] = $growth;

        if (empty($validated['status'])) {
            if ($growth > 0) {
                $validated['status'] = 'Meningkat';
            } elseif ($growth === 0) {
                $validated['status'] = 'Stabil';
            } else {
                $validated['status'] = 'Belum Meningkat';
            }
        }

        $oldCompetency = $review->competency;
        $review->update($validated);

        // Update IDP action plan yang bersangkutan jika ada
        $existingIdp = $employee->idpActionPlans()->whereRaw('LOWER(competency) = ?', [strtolower(trim($oldCompetency))])->first();
        if ($existingIdp) {
            $isDone = ($validated['status'] === 'Meningkat' || (int)$validated['current_level'] >= (int)$validated['target_level']);
            $existingIdp->update([
                'competency' => $validated['competency'],
                'competency_type' => $validated['competency_type'],
                'status' => $isDone ? 'Selesai' : 'On Progress',
                'progress_percent' => $isDone ? 100 : min(90, max(50, (int)round(((int)$validated['current_level'] / max(1, (int)$validated['target_level'])) * 100))),
            ]);
        }

        $this->syncIdpAndReviews($employee);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'review-pengembangan'])
            ->with('success', "Review kompetensi '{$review->competency}' berhasil diperbarui dan disinkronkan ke IDP.");
    }

    /**
     * Delete Review Hasil Pengembangan
     */
    public function destroyDevelopmentReview(string $nik, int $id)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $review = $employee->developmentReviews()->findOrFail($id);
        $compName = $review->competency;
        $review->delete();

        // Hapus juga IDP action plan yang bersangkutan jika ada
        $employee->idpActionPlans()->whereRaw('LOWER(competency) = ?', [strtolower(trim($compName))])->delete();

        $this->syncIdpAndReviews($employee);

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'review-pengembangan'])
            ->with('success', "Review kompetensi '{$compName}' dan Rencana Aksi IDP terkait berhasil dihapus.");
    }

    /**
     * Update Feedback Atasan Langsung & Rekomendasi Tindak Lanjut
     */
    public function updateReviewFeedback(Request $request, string $nik)
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        // Helper untuk memparsing daftar feedback (bisa 1 atau banyak komentar)
        $getFeedbackList = function($emp) {
            $decoded = json_decode($emp->review_feedback_text ?? '', true);
            if (is_array($decoded) && !empty($decoded) && isset($decoded[0]['text'])) {
                return $decoded;
            }
            if (!empty($emp->review_feedback_text)) {
                return [
                    [
                        'text' => $emp->review_feedback_text,
                        'reviewer_name' => $emp->review_reviewer_name ?: 'Andi Wijaya',
                        'reviewer_title' => $emp->review_reviewer_title ?: 'Engineering Division Head',
                        'date' => $emp->review_date ?: '20 Mei 2027',
                    ]
                ];
            }
            return [
                [
                    'text' => 'Budi Santosoo menunjukkan perkembangan yang baik selama periode ini. Terlihat peningkatan dalam kepemimpinan, komunikasi, dan kemampuan eksekusi. Fokus selanjutnya adalah memperkuat kemampuan analisis strategis dan pengambilan keputusan berbasis data untuk siap menempati posisi Engineering Manager saat penugasan berikutnya.',
                    'reviewer_name' => 'Andi Wijaya',
                    'reviewer_title' => 'Engineering Division Head',
                    'date' => '20 Mei 2027',
                ]
            ];
        };

        // Helper untuk memformat tanggal YYYY-MM-DD menjadi format teks Indonesia (contoh: 20 Mei 2027)
        $formatDateIndo = function($val) {
            if (empty($val)) return date('d F Y');
            $val = trim($val);
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
                $months = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                $parts = explode('-', $val);
                $d = ltrim($parts[2], '0');
                $m = $parts[1];
                $y = $parts[0];
                return $d . ' ' . ($months[$m] ?? $m) . ' ' . $y;
            }
            return $val;
        };

        // 1. Aksi Khusus Bagian D (Feedback)
        if ($request->filled('feedback_action')) {
            $action = $request->input('feedback_action');
            $feedbacks = $getFeedbackList($employee);

            if ($action === 'add_new') {
                $newComment = [
                    'text' => $request->input('review_feedback_text', ''),
                    'reviewer_name' => $request->input('review_reviewer_name', 'Atasan Langsung'),
                    'reviewer_title' => $request->input('review_reviewer_title', 'Department Head'),
                    'date' => $formatDateIndo($request->input('review_date', date('Y-m-d'))),
                ];
                $feedbacks[] = $newComment;
                $employee->review_feedback_text = json_encode(array_values($feedbacks));
                $employee->review_reviewer_name = $feedbacks[0]['reviewer_name'];
                $employee->review_reviewer_title = $feedbacks[0]['reviewer_title'];
                $employee->review_date = $feedbacks[0]['date'];
                $employee->save();

                return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'review-pengembangan'])
                    ->with('success', 'Komentar feedback baru berhasil ditambahkan (kini terdapat ' . count($feedbacks) . ' komentar).');
            }

            if ($action === 'delete_item') {
                $idx = (int) $request->input('feedback_index', 0);
                if (isset($feedbacks[$idx])) {
                    array_splice($feedbacks, $idx, 1);
                }
                if (empty($feedbacks)) {
                    $employee->review_feedback_text = null;
                } else {
                    $employee->review_feedback_text = json_encode(array_values($feedbacks));
                    $employee->review_reviewer_name = $feedbacks[0]['reviewer_name'];
                    $employee->review_reviewer_title = $feedbacks[0]['reviewer_title'];
                    $employee->review_date = $feedbacks[0]['date'];
                }
                $employee->save();

                return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'review-pengembangan'])
                    ->with('success', 'Komentar feedback berhasil dihapus.');
            }

            if ($action === 'update_item') {
                $idx = (int) $request->input('feedback_index', 0);
                if (!isset($feedbacks[$idx])) {
                    $idx = 0;
                }
                $feedbacks[$idx] = [
                    'text' => $request->input('review_feedback_text', ''),
                    'reviewer_name' => $request->input('review_reviewer_name', 'Atasan Langsung'),
                    'reviewer_title' => $request->input('review_reviewer_title', 'Department Head'),
                    'date' => $formatDateIndo($request->input('review_date', date('Y-m-d'))),
                ];
                $employee->review_feedback_text = json_encode(array_values($feedbacks));
                $employee->review_reviewer_name = $feedbacks[0]['reviewer_name'];
                $employee->review_reviewer_title = $feedbacks[0]['reviewer_title'];
                $employee->review_date = $feedbacks[0]['date'];
                $employee->save();

                return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'review-pengembangan'])
                    ->with('success', 'Komentar feedback berhasil diperbarui.');
            }
        }

        // 2. Aksi Khusus Bagian E (Rekomendasi Tindak Lanjut)
        if ($request->filled('recommendation_action')) {
            $recAction = $request->input('recommendation_action');

            if ($recAction === 'delete_item') {
                $delIdx = $request->input('delete_recommendation_index');
                $rawRecs = $employee->review_recommendations;
                $lines = array_values(array_filter(array_map('trim', explode("\n", $rawRecs ?: ''))));
                if (empty($lines)) {
                    $lines = [
                        'Lanjutkan program pengembangan sesuai IDP dengan fokus pada Analysis & Judgement.',
                        'Berikan kesempatan memimpin proyek strategis yang berdampak lintas departemen.',
                        'Coaching/mentoring dengan Engineering Manager untuk mempercepat kesiapan.',
                        'Review berikutnya dilakukan pada Mei 2028.'
                    ];
                }

                if ($delIdx !== null && $delIdx !== '' && isset($lines[(int)$delIdx])) {
                    array_splice($lines, (int)$delIdx, 1);
                    $employee->review_recommendations = implode("\n", $lines);
                    $employee->save();
                    return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'review-pengembangan'])
                        ->with('success', 'Poin rekomendasi tindak lanjut terpilih berhasil dihapus.');
                } else {
                    $employee->review_recommendations = '';
                    $employee->save();
                    return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'review-pengembangan'])
                        ->with('success', 'Seluruh rekomendasi tindak lanjut berhasil dihapus.');
                }
            }

            if ($recAction === 'update_all') {
                $employee->review_recommendations = $request->input('review_recommendations', '');
                $employee->save();
                return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'review-pengembangan'])
                    ->with('success', 'Daftar rekomendasi tindak lanjut berhasil diperbarui.');
            }
        }

        // Tambah rekomendasi tindak lanjut baru jika ada input item baru dari mode Tambah
        if ($request->filled('new_recommendation_item')) {
            $current = trim($employee->review_recommendations ?: '');
            $newItem = trim($request->input('new_recommendation_item'));
            $employee->review_recommendations = $current ? ($current . "\n" . $newItem) : $newItem;
            $employee->save();

            return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'review-pengembangan'])
                ->with('success', 'Rekomendasi tindak lanjut baru berhasil ditambahkan.');
        }

        // Update default jika ada request generik
        $updateData = [];
        foreach (['review_feedback_text', 'review_reviewer_name', 'review_reviewer_title', 'review_date', 'review_recommendations'] as $field) {
            if ($request->has($field)) {
                if ($field === 'review_date') {
                    $updateData[$field] = $formatDateIndo($request->input($field));
                } else {
                    $updateData[$field] = $request->input($field);
                }
            }
        }

        if (!empty($updateData)) {
            $employee->update($updateData);
        }

        return redirect()->route('karyawan.show', ['nik' => $nik, 'tab' => 'review-pengembangan'])
            ->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Export data to CSV format.
     */
    public function export(string $nik, string $type): StreamedResponse
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();

        $filename = "{$type}_{$employee->nik}_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($employee, $type) {
            $handle = fopen('php://output', 'w');
            // Write BOM for Excel UTF-8 compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            switch ($type) {
                case 'riwayat-karir':
                    fputcsv($handle, ['No', 'Tanggal Efektif', 'Departemen / Seksi', 'Jabatan', 'Job Class', 'Grade', 'Jenis Perubahan', 'Keterangan']);
                    foreach ($employee->careerHistories()->orderBy('order_no')->get() as $idx => $row) {
                        fputcsv($handle, [
                            $idx + 1,
                            $row->effective_date,
                            $row->department_section,
                            $row->position,
                            $row->job_class,
                            $row->grade,
                            $row->change_type,
                            $row->notes ?? '-',
                        ]);
                    }
                    break;

                case 'riwayat-pelatihan':
                    fputcsv($handle, ['No', 'Tanggal', 'Nama Pelatihan', 'Kategori', 'Jenis Pelatihan', 'Penyelenggara', 'Durasi (Jam)', 'Dokumentasi', 'Catatan']);
                    foreach ($employee->trainingHistories()->orderBy('order_no')->get() as $idx => $row) {
                        fputcsv($handle, [
                            $idx + 1,
                            $row->training_date,
                            $row->training_name,
                            $row->category,
                            $row->training_type,
                            $row->organizer,
                            $row->duration_hours,
                            $row->documentation ? url($row->documentation) : '-',
                            $row->notes ?? '-',
                        ]);
                    }
                    break;

                case 'development-gap':
                    fputcsv($handle, ['No', 'Kategori', 'Kompetensi', 'Level Saat Ini', 'Deskripsi Level Saat Ini', 'Target Level', 'Deskripsi Target Level', 'Gap', 'Tingkat Gap', 'Peningkatan yang Diharapkan']);
                    foreach ($employee->competencyGaps()->orderBy('order_no')->get() as $idx => $row) {
                        fputcsv($handle, [
                            $idx + 1,
                            $row->competency_type ?: 'Manajerial',
                            $row->competency,
                            $row->current_level,
                            $row->current_desc,
                            $row->standard_level,
                            $row->standard_desc,
                            $row->gap,
                            $row->gap_severity,
                            $row->expected_improvement,
                        ]);
                    }
                    break;

                case 'status-suksesi':
                    fputcsv($handle, ['Tipe', 'Nama / Jabatan', 'Departemen', 'Level / Posisi Saat Ini', 'Kesiapan', 'Dibutuhkan Pada', 'Peringkat / Alasan']);
                    foreach ($employee->successionPositions as $pos) {
                        fputcsv($handle, ['Posisi Suksesi', $pos->target_position, $pos->department, $pos->position_level, $pos->readiness ?? '-', $pos->needed_at, $pos->reason]);
                    }
                    foreach ($employee->successionCandidates as $cand) {
                        fputcsv($handle, ['Calon Pengganti', $cand->candidate_name, $cand->current_department, $cand->current_position, $cand->readiness, $cand->needed_at, 'Peringkat ' . $cand->ranking]);
                    }
                    break;

                case 'review-pengembangan':
                    fputcsv($handle, ['No', 'Kategori', 'Periode', 'Kompetensi', 'Level Sebelumnya', 'Level Terkini', 'Level Target', 'Peningkatan', 'Status', 'Catatan Reviewer']);
                    foreach ($employee->developmentReviews()->orderBy('order_no')->get() as $idx => $row) {
                        fputcsv($handle, [
                            $idx + 1,
                            $row->competency_type ?: 'Manajerial',
                            $row->period,
                            $row->competency,
                            $row->previous_level,
                            $row->current_level,
                            $row->target_level,
                            $row->growth > 0 ? '+' . $row->growth : '0',
                            $row->status,
                            $row->reviewer_notes,
                        ]);
                    }
                    break;

                case 'idp':
                case 'individual-developmentplan':
                    fputcsv($handle, ['No', 'Kategori', 'Kompetensi yang Dikembangkan', 'Tujuan Spesifik', 'Metode Pengembangan', 'Aktivitas / Program', 'PIC / Pendukung', 'Mulai', 'Selesai', 'Indikator Keberhasilan', 'Status', 'Progres (%)']);
                    foreach ($employee->idpActionPlans()->orderBy('order_no')->get() as $idx => $row) {
                        fputcsv($handle, [
                            $idx + 1,
                            $row->competency_type ?: 'Manajerial',
                            $row->competency,
                            $row->specific_goal,
                            $row->development_methods,
                            $row->activity_program,
                            $row->pic_supporter,
                            $row->start_date,
                            $row->end_date,
                            $row->success_indicator,
                            $row->status,
                            $row->progress_percent . '%',
                        ]);
                    }
                    break;

                default:
                    fputcsv($handle, ['Karyawan', 'NIK', 'Departemen', 'Jabatan']);
                    fputcsv($handle, [$employee->name, $employee->nik, $employee->department, $employee->position]);
                    break;
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Super Admin: Store new Employee
     */
    public function storeEmployee(Request $request)
    {
        // Authorization check
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Hanya Super Admin yang memiliki hak akses untuk menambah karyawan baru.');
        }

        $validated = $request->validate([
            'nik' => 'required|string|max:50|unique:employees,nik',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'age' => 'required|integer|min:18|max:65',
            'tenure_years' => 'required|integer|min:0',
            'education' => 'nullable|string|max:255',
            'current_job_class' => 'required|string|max:20',
            'current_grade' => 'required|string|max:20',
            'grade_since' => 'nullable|string|max:50',
            'position_since' => 'nullable|string|max:50',
        ]);

        $validated['education'] = $validated['education'] ?: '-';
        $validated['avatar'] = '/images/avatar-budi.png';
        $validated['performance_current'] = 'A';
        $validated['potass_current'] = '100%';
        $validated['hav_box_current'] = 'Box 15';
        $validated['talent_pool_status'] = 'YA';
        $validated['flying_risk'] = 'MEDIUM';
        $validated['flying_risk_reason'] = 'Career Progression';
        $validated['next_possible_position'] = $validated['position'] . ' Manager';
        $validated['career_projection'] = $validated['department'] . ' Division Head';
        $validated['readiness_level'] = '65%';
        $validated['retirement_year'] = (string)(date('Y') + (55 - (int)$validated['age']));
        $validated['profile_notes'] = 'Profil karyawan baru berhasil didaftarkan ke sistem.';

        $employee = Employee::create($validated);

        // 1. Seed initial basic career plan
        $employee->careerPlan()->create([
            'interested_area' => $employee->department,
            'recommended_career_path' => 'Managerial',
            'next_possible_position' => $employee->next_possible_position,
            'next_possible_department' => $employee->department,
            'career_projection' => $employee->career_projection,
            'notes' => 'Profil karyawan baru berhasil didaftarkan oleh Super Admin.',
        ]);

        // 2. Hubungkan Riwayat Karir awal (Penempatan Awal) terkoneksi langsung dengan NIK
        $effectiveDate = $employee->position_since ?: ($employee->grade_since ?: date('M Y'));
        $deptSection = $employee->department . ($employee->section ? ' / ' . $employee->section : '');
        $employee->careerHistories()->create([
            'order_no' => 1,
            'effective_date' => $effectiveDate,
            'department_section' => $deptSection,
            'position' => $employee->position,
            'job_class_grade' => $employee->current_job_class . ' / ' . $employee->current_grade,
            'change_type' => 'Penempatan Awal',
            'notes' => 'Penempatan posisi awal karyawan.',
        ]);

        return redirect()->route('karyawan.show', ['nik' => $employee->nik, 'tab' => 'profil-individu'])
            ->with('success', "Karyawan baru {$employee->name} (NIK: {$employee->nik}) berhasil ditambahkan dan terhubung dengan sistem.");
    }

    /**
     * Super Admin: Update existing Employee data pokok
     */
    public function updateEmployee(Request $request, string $nik)
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Hanya Super Admin yang memiliki hak akses untuk mengedit data karyawan.');
        }

        $employee = Employee::where('nik', $nik)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'age' => 'required|integer|min:18|max:65',
            'tenure_years' => 'required|integer|min:0',
            'education' => 'required|string|max:255',
            'current_job_class' => 'required|string|max:20',
            'current_grade' => 'required|string|max:20',
            'grade_since' => 'nullable|string|max:50',
            'position_since' => 'nullable|string|max:50',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($request->filled('cropped_avatar')) {
            $base64Data = $request->input('cropped_avatar');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $typeMatches)) {
                $rawImage = base64_decode(substr($base64Data, strpos($base64Data, ',') + 1));
                if ($rawImage !== false) {
                    $ext = strtolower($typeMatches[1]) === 'jpeg' ? 'jpg' : strtolower($typeMatches[1]);
                    $cleanNik = preg_replace('/[^a-zA-Z0-9_-]/', '', $employee->nik);
                    $filename = 'avatar_' . $cleanNik . '_' . time() . '.' . $ext;
                    $destPath = public_path('uploads/avatars');
                    if (!file_exists($destPath)) {
                        mkdir($destPath, 0755, true);
                    }
                    file_put_contents($destPath . '/' . $filename, $rawImage);
                    $validated['avatar'] = '/uploads/avatars/' . $filename;
                }
            }
        } elseif ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $cleanNik = preg_replace('/[^a-zA-Z0-9_-]/', '', $employee->nik);
            $filename = 'avatar_' . $cleanNik . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/avatars');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $validated['avatar'] = '/uploads/avatars/' . $filename;
        }

        $employee->update($validated);

        return redirect()->back()->with('success', "Data karyawan {$employee->name} berhasil diperbarui oleh Super Admin.");
    }

    /**
     * Super Admin: Delete Employee
     */
    public function destroyEmployee(string $nik)
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Hanya Super Admin yang memiliki hak akses untuk menghapus karyawan.');
        }

        $employee = Employee::where('nik', $nik)->firstOrFail();
        $name = $employee->name;
        $employee->delete();

        return redirect()->route('data-karyawan')
            ->with('success', "Karyawan {$name} (NIK: {$nik}) berhasil dihapus dari sistem oleh Super Admin.");
    }

    /**
     * Download Excel Template (.xlsx) for Employee Import
     */
    public function downloadExcelTemplate(EmployeeExcelService $excelService)
    {
        $spreadsheet = $excelService->generateTemplate();
        $filename = 'MAP-IN_Template_Impor_Karyawan.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Super Admin: Import Employees from Excel (.xlsx, .xls, .csv)
     */
    public function importExcel(Request $request, EmployeeExcelService $excelService)
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Hanya Super Admin yang memiliki hak akses untuk mengimpor data karyawan.');
        }

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ], [
            'excel_file.required' => 'Silakan pilih file Excel (.xlsx, .xls) atau .csv yang ingin diimpor.',
            'excel_file.mimes' => 'Format file yang didukung adalah .xlsx, .xls, atau .csv.',
            'excel_file.max' => 'Ukuran file maksimal yang diperbolehkan adalah 10 MB.',
        ]);

        $updateExisting = $request->boolean('update_existing', true);
        $file = $request->file('excel_file');

        $result = $excelService->importEmployees($file, $updateExisting);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        $redirect = redirect()->route('data-karyawan')->with('success', $result['message']);

        if (!empty($result['errors'])) {
            $errorSummary = implode('; ', array_slice($result['errors'], 0, 4));
            if (count($result['errors']) > 4) {
                $errorSummary .= ' (dan ' . (count($result['errors']) - 4) . ' baris lainnya)';
            }
            $redirect->with('warning', 'Catatan baris impor: ' . $errorSummary);
        }

        return $redirect;
    }

    /**
     * Export All Registered Employees to Excel (.xlsx)
     */
    public function exportAllExcel(EmployeeExcelService $excelService)
    {
        $spreadsheet = $excelService->exportAllEmployees();
        $filename = 'MAP-IN_Data_Karyawan_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Update Employee Avatar / Photo (Super Admin & HR Admin)
     */
    public function updateAvatar(Request $request, string $nik)
    {
        if (!Auth::check() || (!Auth::user()->isSuperAdmin() && !Auth::user()->isHrAdmin())) {
            return redirect()->back()->with('error', 'Anda harus login sebagai Super Admin atau HR Admin untuk mengubah foto profil.');
        }

        $employee = Employee::where('nik', $nik)->firstOrFail();

        $request->validate([
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'preset_avatar' => 'nullable|string',
        ]);

        if ($request->filled('cropped_avatar')) {
            $base64Data = $request->input('cropped_avatar');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $typeMatches)) {
                $rawImage = base64_decode(substr($base64Data, strpos($base64Data, ',') + 1));
                if ($rawImage !== false) {
                    $ext = strtolower($typeMatches[1]) === 'jpeg' ? 'jpg' : strtolower($typeMatches[1]);
                    $cleanNik = preg_replace('/[^a-zA-Z0-9_-]/', '', $employee->nik);
                    $filename = 'avatar_' . $cleanNik . '_' . time() . '.' . $ext;
                    $destPath = public_path('uploads/avatars');
                    if (!file_exists($destPath)) {
                        mkdir($destPath, 0755, true);
                    }
                    file_put_contents($destPath . '/' . $filename, $rawImage);
                    $employee->avatar = '/uploads/avatars/' . $filename;
                    $employee->save();

                    $roleText = Auth::user()->role_name;
                    return redirect()->back()->with('success', "Foto profil {$employee->name} berhasil disesuaikan & diperbarui oleh {$roleText}.");
                }
            }
        }

        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $cleanNik = preg_replace('/[^a-zA-Z0-9_-]/', '', $employee->nik);
            $filename = 'avatar_' . $cleanNik . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/avatars');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $employee->avatar = '/uploads/avatars/' . $filename;
            $employee->save();

            $roleText = Auth::user()->role_name;
            return redirect()->back()->with('success', "Foto profil {$employee->name} berhasil diperbarui oleh {$roleText}.");
        }

        if ($request->filled('preset_avatar')) {
            $employee->avatar = $request->input('preset_avatar');
            $employee->save();

            $roleText = Auth::user()->role_name;
            return redirect()->back()->with('success', "Foto profil {$employee->name} berhasil diperbarui oleh {$roleText}.");
        }

        return redirect()->back()->with('error', 'Silakan pilih file foto atau salah satu foto preset.');
    }
}


