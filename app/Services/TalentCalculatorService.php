<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\TalentAssessment;

class TalentCalculatorService
{
    /**
     * Daftar Standar Jabatan / Posisi resmi untuk POTASS (C2 & C6)
     */
    public static function getPositionStandards(): array
    {
        return [
            'ADMIN',
            'OPERATOR',
            'OPERATOR SENIOR',
            'LINEKEEPER (BANSER/TANOKO)',
            'DEPUTY LEADER',
            'LEADER',
            'FOREMAN',
            'DEPUTY SECTION HEAD',
            'SECTION HEAD',
            'DEPUTY DEPT HEAD',
            'MANAGER',
            'DEPUTY DIVISION HEAD',
            'DIVISION HEAD',
            'JUNIOR TECHNICIAN',
            'TECHNICIAN',
            'SENIOR TECHNICIAN',
            'JUNIOR STAFF',
            'STAFF',
            'SENIOR STAFF',
            'JUNIOR DESIGNER',
            'DESIGNER',
            'GOL 4 NON KASIE',
        ];
    }

    /**
     * Pemetaan Standar Klasifikasi Jabatan (KJ) & Pilihan Sub Golongan (Grade)
     */
    public static function getJobClassMap(): array
    {
        return [
            'KJ 1'  => ['start' => '1 A', 'end' => '2 A', 'grades' => ['1 A', '2 A']],
            'KJ 2'  => ['start' => '1 E', 'end' => '2 E', 'grades' => ['1 E', '2 E']],
            'KJ 3'  => ['start' => '2 B', 'end' => '3 B', 'grades' => ['2 B', '3 B']],
            'KJ 4'  => ['start' => '2 E', 'end' => '3 D', 'grades' => ['2 E', '3 D']],
            'KJ 5'  => ['start' => '3 B', 'end' => '3 F', 'grades' => ['3 B', '3 F']],
            'KJ 6'  => ['start' => '3 E', 'end' => '4 B', 'grades' => ['3 E', '4 B']],
            'KJ 7'  => ['start' => '4 A', 'end' => '4 D', 'grades' => ['4 A', '4 D']],
            'KJ 8'  => ['start' => '4 E', 'end' => '4 F', 'grades' => ['4 E', '4 F']],
            'KJ 9'  => ['start' => '5 A', 'end' => '5 B', 'grades' => ['5 A', '5 B']],
            'KJ 10' => ['start' => '5 C', 'end' => '5 D', 'grades' => ['5 C', '5 D']],
            'KJ 11' => ['start' => '6 A', 'end' => '6 B', 'grades' => ['6 A', '6 B']],
            'KJ 12' => ['start' => '6 C', 'end' => '6 D', 'grades' => ['6 C', '6 D']],
            'KJ 13' => ['start' => '7 A', 'end' => '7 B', 'grades' => ['7 A', '7 B']],
            'KJ 14' => ['start' => '7 C', 'end' => '7 D', 'grades' => ['7 C', '7 D']],
        ];
    }

    /**
     * Daftar Nama Job Class (KJ 1 s/d KJ 14)
     */
    public static function getJobClassList(): array
    {
        return array_keys(self::getJobClassMap());
    }

    /**
     * Daftar Semua Sub Golongan
     */
    public static function getAllSubGolList(): array
    {
        return [
            '1 A', '1 E',
            '2 A', '2 B', '2 E',
            '3 B', '3 D', '3 E', '3 F',
            '4 A', '4 B', '4 D', '4 E', '4 F',
            '5 A', '5 B', '5 C', '5 D',
            '6 A', '6 B', '6 C', '6 D',
            '7 A', '7 B', '7 C', '7 D',
        ];
    }

    /**
     * Map performance rating to points based on C1.xlsx / RUMUS HAV
     * S / IST: 8
     * AS / BS+: 7
     * A / BS: 6
     * B+: 5
     * B: 4
     * C+: 3
     * C: 2
     * K / D / E: 1
     * - / 0: 0
     */
    public static function ratingToPoints(?string $rating): int
    {
        if (!$rating) return 0;
        $r = strtoupper(trim($rating));

        return match ($r) {
            'S' => 8,
            'AS' => 7,
            'A' => 6,
            'B+' => 5,
            'B' => 4,
            '-' => 0,
            'C' => 2,
            'K' => 1,
            'IST', 'A+' => 8,
            'BS+' => 7,
            'BS' => 6,
            'C+' => 3,
            'D', 'E' => 1,
            default => 0,
        };
    }

    /**
     * Calculate Baris HAV (Row) from Total Score of 3 years performance
     * Total >= 21 : R3 (Far Above Target)
     * Total >= 16 : R2 (Above Target)
     * Total >= 12 : R1 (Meet Target)
     * Total >= 1  : R0 (Below & Far Below Target)
     */
    public static function calculateBarisHav(int $totalScore): array
    {
        if ($totalScore >= 21) {
            return ['code' => 'R3', 'label' => 'Far Above Target', 'min' => 21];
        } elseif ($totalScore >= 16) {
            return ['code' => 'R2', 'label' => 'Above Target', 'min' => 16];
        } elseif ($totalScore >= 12) {
            return ['code' => 'R1', 'label' => 'Meet Target', 'min' => 12];
        } else {
            return ['code' => 'R0', 'label' => 'Below & Far Below Target', 'min' => 1];
        }
    }

    /**
     * Calculate Behavior Competency Scoring (8 inputs) based on C6.xlsx
     * Weights:
     * 1. Vision & Bus Sense: 15% (0.15)
     * 2. Cust. Focus: 15% (0.15)
     * 3. Interpers. Skill: 10% (0.10)
     * 4. Analysis & Judgment: 10% (0.10)
     * 5. Plan. & Drvg Act.: 10% (0.10)
     * 6. Leading & Motivating: 15% (0.15)
     * 7. Teamwork: 10% (0.10)
     * 8. Drive, Courg & Integrity: 15% (0.15)
     */
    public static function calculateBehaviorCompetency(array $scores): array
    {
        $b1 = (float)($scores['b1_vision_business'] ?? 4.0);
        $b2 = (float)($scores['b2_customer_focus'] ?? 4.0);
        $b3 = (float)($scores['b3_interpersonal_skill'] ?? 4.0);
        $b4 = (float)($scores['b4_analysis_judgment'] ?? 3.0);
        $b5 = (float)($scores['b5_planning_driving'] ?? 3.0);
        $b6 = (float)($scores['b6_leading_motivating'] ?? 4.0);
        $b7 = (float)($scores['b7_teamwork'] ?? 4.0);
        $b8 = (float)($scores['b8_drive_courage_integrity'] ?? 4.0);

        $weighted = ($b1 * 0.15) + ($b2 * 0.15) + ($b3 * 0.10) + ($b4 * 0.10) 
                  + ($b5 * 0.10) + ($b6 * 0.15) + ($b7 * 0.10) + ($b8 * 0.15);

        $percentage = ($weighted / 5.0) * 100.0;

        // Kolom HAV: >=71% -> C3, >=61% -> C2, >=50% -> C1, <50% -> C0
        if ($percentage >= 71.0) {
            $kolom = 'C3';
            $kolomLabel = '≥ 71%';
            $category = 'High';
        } elseif ($percentage >= 61.0) {
            $kolom = 'C2';
            $kolomLabel = '61% - 70%';
            $category = 'Average';
        } elseif ($percentage >= 50.0) {
            $kolom = 'C1';
            $kolomLabel = '51% - 60%';
            $category = 'Average';
        } else {
            $kolom = 'C0';
            $kolomLabel = '< 50%';
            $category = 'Below Average';
        }

        return [
            'scores' => [
                'b1' => $b1, 'b2' => $b2, 'b3' => $b3, 'b4' => $b4,
                'b5' => $b5, 'b6' => $b6, 'b7' => $b7, 'b8' => $b8,
            ],
            'weighted_score' => round($weighted, 2),
            'percentage' => round($percentage, 1),
            'kolom_hav' => $kolom,
            'kolom_label' => $kolomLabel,
            'category' => $category,
        ];
    }

    /**
     * Map HAV 16-Box based on Row (R0-R3) and Column (C0-C3)
     */
    public static function getHavMatrixMap(): array
    {
        return [
            'R3' => [
                'C0' => ['box' => 13, 'name' => 'Maximal Contributor', 'talent_pool' => 'TIDAK', 'color' => '#ffc000', 'textColor' => '#ffffff'],
                'C1' => ['box' => 7,  'name' => 'Top Performers',       'talent_pool' => 'YA',    'color' => '#ffffa5', 'textColor' => '#1e293b'],
                'C2' => ['box' => 3,  'name' => 'Future Star',          'talent_pool' => 'YA',    'color' => '#9dde58', 'textColor' => '#ffffff'],
                'C3' => ['box' => 1,  'name' => 'STAR',                 'talent_pool' => 'YA',    'color' => '#00b0f0', 'textColor' => '#ffffff'],
            ],
            'R2' => [
                'C0' => ['box' => 14, 'name' => 'Contributor',         'talent_pool' => 'TIDAK', 'color' => '#ffc000', 'textColor' => '#ffffff'],
                'C1' => ['box' => 8,  'name' => 'Strong Performers',     'talent_pool' => 'YA',    'color' => '#ffffa5', 'textColor' => '#1e293b'],
                'C2' => ['box' => 4,  'name' => 'Potential Candidate',  'talent_pool' => 'YA',    'color' => '#9dde58', 'textColor' => '#ffffff'],
                'C3' => ['box' => 2,  'name' => 'Future Star',          'talent_pool' => 'YA',    'color' => '#9dde58', 'textColor' => '#ffffff'],
            ],
            'R1' => [
                'C0' => ['box' => 15, 'name' => 'Minimal Contributor', 'talent_pool' => 'TIDAK', 'color' => '#9090ef', 'textColor' => '#ffffff'],
                'C1' => ['box' => 9,  'name' => 'Career Person',        'talent_pool' => 'YA',    'color' => '#ffffa5', 'textColor' => '#1e293b'],
                'C2' => ['box' => 6,  'name' => 'Candidate',            'talent_pool' => 'YA',    'color' => '#ffffa5', 'textColor' => '#1e293b'],
                'C3' => ['box' => 5,  'name' => 'Raw Diamond',          'talent_pool' => 'YA',    'color' => '#ffffa5', 'textColor' => '#1e293b'],
            ],
            'R0' => [
                'C0' => ['box' => 16, 'name' => 'Deadwood',             'talent_pool' => 'TIDAK', 'color' => '#a505a5', 'textColor' => '#ffffff'],
                'C1' => ['box' => 12, 'name' => 'Problem Employee',     'talent_pool' => 'TIDAK', 'color' => '#9090ef', 'textColor' => '#ffffff'],
                'C2' => ['box' => 11, 'name' => 'Unfit Employee',       'talent_pool' => 'TIDAK', 'color' => '#ffc000', 'textColor' => '#ffffff'],
                'C3' => ['box' => 10, 'name' => 'Most Unfit Employee',  'talent_pool' => 'TIDAK', 'color' => '#ffc000', 'textColor' => '#ffffff'],
            ],
        ];
    }

    /**
     * Calculate HAV Box from Baris HAV and Kolom HAV
     */
    public static function calculateHavBox(string $row, string $col): array
    {
        $matrix = self::getHavMatrixMap();
        $r = in_array($row, ['R0', 'R1', 'R2', 'R3']) ? $row : 'R2';
        $c = in_array($col, ['C0', 'C1', 'C2', 'C3']) ? $col : 'C3';

        $cell = $matrix[$r][$c] ?? $matrix['R2']['C3'];

        return [
            'row' => $r,
            'col' => $c,
            'box_number' => $cell['box'],
            'box_label' => 'Box ' . $cell['box'],
            'category' => $cell['name'],
            'talent_pool' => $cell['talent_pool'],
            'color' => $cell['color'],
            'textColor' => $cell['textColor'],
        ];
    }

    /**
     * Get cell info by Box number (1-16)
     */
    public static function getBoxInfoByNumber(int $boxNumber): ?array
    {
        $matrix = self::getHavMatrixMap();
        foreach ($matrix as $rKey => $cols) {
            foreach ($cols as $cKey => $cell) {
                if ($cell['box'] === $boxNumber) {
                    $cell['row'] = $rKey;
                    $cell['col'] = $cKey;
                    $cell['box_number'] = $cell['box'];
                    $cell['box_label'] = 'Box ' . $cell['box'];
                    $cell['category'] = $cell['name'];
                    return $cell;
                }
            }
        }
        return null;
    }

    /**
     * Calculate Flying Risk based on C5flyrisk.png
     */
    public static function calculateFlyingRisk(?string $growth, ?string $market, ?string $compensation): array
    {
        // Factor 1: Career Growth Potential
        $growthPoints = match ($growth) {
            'No clear advancement path' => 2,
            'Some opportunities, but limited' => 1,
            'Clear advancement opportunities' => 0,
            default => 0,
        };

        // Factor 2: Job Market Demand for Role
        $marketPoints = match ($market) {
            'High demand for similar roles in industry' => 2,
            'Moderate demand' => 1,
            'Low demand' => 0,
            default => 1,
        };

        // Factor 3: Compensation Competitiveness
        $compPoints = match ($compensation) {
            'Below industry standard' => 2,
            'At industry standard' => 1,
            'Above industry standard' => 0,
            default => 0,
        };

        $totalScore = $growthPoints + $marketPoints + $compPoints;

        if ($totalScore <= 2) {
            $riskLevel = 'Low Risk';
            $interpretation = 'Employee is highly likely to stay. Minimal intervention needed.';
            $badgeClass = 'badge-green';
        } elseif ($totalScore <= 4) {
            $riskLevel = 'Moderate Risk';
            $interpretation = 'Employee may have some concerns but is not actively looking to leave. Engagement and career development efforts recommended.';
            $badgeClass = 'badge-orange';
        } else {
            $riskLevel = 'High Risk';
            $interpretation = 'Employee shows multiple risk factors and could leave within the next 6-12 months. Proactive retention strategies needed.';
            $badgeClass = 'badge-red';
        }

        return [
            'career_growth' => $growth ?: 'Clear advancement opportunities',
            'career_growth_pts' => $growthPoints,
            'job_market' => $market ?: 'Moderate demand',
            'job_market_pts' => $marketPoints,
            'compensation' => $compensation ?: 'Above industry standard',
            'compensation_pts' => $compPoints,
            'total_score' => $totalScore,
            'risk_level' => $riskLevel,
            'interpretation' => $interpretation,
            'badge_class' => $badgeClass,
        ];
    }

    /**
     * Parse period string like "Aug-26", "Aug-2026", "2026-08" into comparable integer YYYYMM (e.g. 202608)
     */
    public static function parseAssessmentPeriod(?string $period): int
    {
        if (!$period) return 0;
        $monthMap = [
            'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'mei' => 5,
            'jun' => 6, 'jul' => 7, 'aug' => 8, 'agu' => 8, 'sep' => 9, 'oct' => 10,
            'okt' => 10, 'nov' => 11, 'dec' => 12, 'des' => 12
        ];
        $trimmed = trim($period);
        if (preg_match('/^([a-zA-Z]+)-(\d+)$/', $trimmed, $m)) {
            $mStr = strtolower(substr($m[1], 0, 3));
            $month = $monthMap[$mStr] ?? 1;
            $y = (int)$m[2];
            if ($y < 100) {
                $y = 2000 + $y;
            }
            return ($y * 100) + $month;
        }
        if (preg_match('/^(\d{4})-(\d{1,2})/', $trimmed, $m)) {
            return ((int)$m[1] * 100) + (int)$m[2];
        }
        return 0;
    }

    /**
     * Synchronize and recalculate employee's Talent Snapshot:
     * - C1: Performance 3-Year points & Baris HAV
     * - C6 -> C2: Latest POTASS assessment feeds into C2 (Terakhir & Sebelumnya)
     * - C1 + C6 -> C3: Determines 16 HAV Box position & Talent Pool status
     */
    public static function syncEmployeeTalentSnapshot(Employee $employee): void
    {
        // 1. Calculate C1 Performance
        $performances = $employee->getThreeYearPerformances();
        $totalScore = 0;
        foreach ($performances as $perf) {
            $totalScore += self::ratingToPoints($perf['rating'] ?? '-');
        }
        $baris = self::calculateBarisHav($totalScore);

        // 2. Connect C6 -> C2
        // Get talent assessments ordered chronologically descending (newest first)
        $assessments = $employee->talentAssessments()->get()->sort(function($a, $b) {
            $valA = self::parseAssessmentPeriod($a->assessment_date);
            $valB = self::parseAssessmentPeriod($b->assessment_date);
            if ($valA === $valB) return $b->id <=> $a->id;
            return $valB <=> $valA;
        })->values();

        $latestAssessment = $assessments->first();
        $prevAssessment = $assessments->skip(1)->first();

        $kolomCode = 'C3'; // default

        if ($latestAssessment) {
            if (!empty($latestAssessment->kolom_hav)) {
                $kolomCode = $latestAssessment->kolom_hav;
                $category = match($kolomCode) {
                    'C3' => 'High',
                    'C2', 'C1' => 'Average',
                    default => 'Below Average',
                };
            } else {
                // Determine percentage & kolom from latest assessment
                $pct = (float)($latestAssessment->score_percentage ?: str_replace('%', '', $latestAssessment->potass_score));
                if ($pct <= 0 && (float)$latestAssessment->potass_score > 0) {
                    $pct = (float)$latestAssessment->potass_score;
                }

                if ($pct >= 71.0) {
                    $kolomCode = 'C3';
                    $category = 'High';
                } elseif ($pct >= 61.0) {
                    $kolomCode = 'C2';
                    $category = 'Average';
                } elseif ($pct >= 50.0) {
                    $kolomCode = 'C1';
                    $category = 'Average';
                } else {
                    $kolomCode = 'C0';
                    $category = 'Below Average';
                }
            }

            // Sync to C2 fields in Employee
            $employee->potass_score_last = ($latestAssessment->potass_score && str_contains($latestAssessment->potass_score, '%')) 
                ? $latestAssessment->potass_score 
                : round($pct) . '%';
            $employee->potass_position_last = $latestAssessment->position_standard;
            $employee->potass_category_last = $latestAssessment->category ?: $category;
            $employee->potass_assessor_last = $latestAssessment->assessor;
            $employee->potass_period_last = $latestAssessment->assessment_date;
            $employee->potass_current = $employee->potass_score_last . ' (' . $employee->potass_category_last . ')';

            if ($prevAssessment) {
                $employee->potass_score_prev = ($prevAssessment->potass_score && str_contains($prevAssessment->potass_score, '%'))
                    ? $prevAssessment->potass_score
                    : round((float)$prevAssessment->potass_score) . '%';
                $employee->potass_position_prev = $prevAssessment->position_standard;
                $employee->potass_category_prev = $prevAssessment->category;
                $employee->potass_assessor_prev = $prevAssessment->assessor;
                $employee->potass_period_prev = $prevAssessment->assessment_date;
            }
        } elseif (!empty($employee->potass_score_last)) {
            $num = (float) str_replace('%', '', $employee->potass_score_last);
            if ($num >= 71) $kolomCode = 'C3';
            elseif ($num >= 61) $kolomCode = 'C2';
            elseif ($num >= 50) $kolomCode = 'C1';
            else $kolomCode = 'C0';
        }

        // 3. Calculate C3 HAV 16-Box from Baris (C1) and Kolom (C6)
        $havResult = self::calculateHavBox($baris['code'], $kolomCode);
        $employee->hav_box_current = $havResult['box_label'];
        $employee->hav_box_category = $havResult['category'];
        $employee->talent_pool_status = $havResult['talent_pool'];

        // 4. Calculate C5 if not populated
        if ($employee->flying_risk_score === null) {
            $risk = self::calculateFlyingRisk(
                $employee->flying_risk_career_growth,
                $employee->flying_risk_job_market,
                $employee->flying_risk_compensation
            );
            $employee->flying_risk = $risk['risk_level'];
            $employee->flying_risk_score = $risk['total_score'];
            $employee->flying_risk_career_growth = $risk['career_growth'];
            $employee->flying_risk_career_growth_pts = $risk['career_growth_pts'];
            $employee->flying_risk_job_market = $risk['job_market'];
            $employee->flying_risk_job_market_pts = $risk['job_market_pts'];
            $employee->flying_risk_compensation = $risk['compensation'];
            $employee->flying_risk_compensation_pts = $risk['compensation_pts'];
            $employee->flying_risk_reason = $risk['interpretation'];
            $employee->flying_risk_interpretation = $risk['interpretation'];
        }

        $employee->save();
    }

    /**
     * Synchronize C2 updates to C6 (talent_assessments)
     */
    public static function syncPotassToTalentAssessments(Employee $employee, array $data): void
    {
        // Get existing assessments sorted chronologically descending
        $assessments = $employee->talentAssessments()->get()->sort(function($a, $b) {
            $valA = self::parseAssessmentPeriod($a->assessment_date);
            $valB = self::parseAssessmentPeriod($b->assessment_date);
            if ($valA === $valB) return $b->id <=> $a->id;
            return $valB <=> $valA;
        })->values();

        // 1. Sync "Terakhir" -> Updates latest assessment in C6
        $lastScore = $data['potass_score_last'] ?? '';
        $lastPct = (float)str_replace('%', '', $lastScore);
        $lastKolom = match($data['potass_category_last'] ?? 'High') {
            'High' => 'C3',
            'Average' => ($lastPct >= 61 ? 'C2' : 'C1'),
            default => 'C0',
        };

        $latestAssessment = $assessments->first();
        if ($latestAssessment) {
            $latestAssessment->update([
                'assessment_date' => $data['potass_period_last'] ?: $latestAssessment->assessment_date,
                'position_standard' => $data['potass_position_last'],
                'potass_score' => $lastScore,
                'category' => $data['potass_category_last'],
                'assessor' => $data['potass_assessor_last'],
                'score_percentage' => $lastPct,
                'kolom_hav' => $lastKolom,
            ]);
        } else {
            $employee->talentAssessments()->create([
                'assessment_date' => $data['potass_period_last'] ?: date('M-y'),
                'position_standard' => $data['potass_position_last'],
                'potass_score' => $lastScore,
                'category' => $data['potass_category_last'],
                'assessor' => $data['potass_assessor_last'],
                'score_percentage' => $lastPct,
                'kolom_hav' => $lastKolom,
                'b1_vision_business' => 4.0,
                'b2_customer_focus' => 4.0,
                'b3_interpersonal_skill' => 4.0,
                'b4_analysis_judgment' => 3.0,
                'b5_planning_driving' => 3.0,
                'b6_leading_motivating' => 4.0,
                'b7_teamwork' => 4.0,
                'b8_drive_courage_integrity' => 4.0,
                'weighted_score' => 3.8,
            ]);
        }

        // 2. Sync "Sebelumnya" -> Updates second latest assessment in C6
        if (!empty($data['potass_position_prev']) && !empty($data['potass_score_prev'])) {
            $prevScore = $data['potass_score_prev'];
            $prevPct = (float)str_replace('%', '', $prevScore);
            $prevKolom = match($data['potass_category_prev'] ?? 'Average') {
                'High' => 'C3',
                'Average' => ($prevPct >= 61 ? 'C2' : 'C1'),
                default => 'C0',
            };

            $prevAssessment = $assessments->skip(1)->first();
            if ($prevAssessment) {
                $prevAssessment->update([
                    'assessment_date' => $data['potass_period_prev'] ?: $prevAssessment->assessment_date,
                    'position_standard' => $data['potass_position_prev'],
                    'potass_score' => $prevScore,
                    'category' => $data['potass_category_prev'],
                    'assessor' => $data['potass_assessor_prev'],
                    'score_percentage' => $prevPct,
                    'kolom_hav' => $prevKolom,
                ]);
            } else {
                $employee->talentAssessments()->create([
                    'assessment_date' => $data['potass_period_prev'] ?: date('M-y', strtotime('-2 years')),
                    'position_standard' => $data['potass_position_prev'],
                    'potass_score' => $prevScore,
                    'category' => $data['potass_category_prev'],
                    'assessor' => $data['potass_assessor_prev'],
                    'score_percentage' => $prevPct,
                    'kolom_hav' => $prevKolom,
                    'b1_vision_business' => 3.5,
                    'b2_customer_focus' => 3.5,
                    'b3_interpersonal_skill' => 3.5,
                    'b4_analysis_judgment' => 3.0,
                    'b5_planning_driving' => 3.0,
                    'b6_leading_motivating' => 3.5,
                    'b7_teamwork' => 3.5,
                    'b8_drive_courage_integrity' => 3.5,
                    'weighted_score' => 3.4,
                ]);
            }
        }

        $employee->unsetRelation('talentAssessments');
    }
}
