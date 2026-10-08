<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function careerHistories(): HasMany
    {
        return $this->hasMany(CareerHistory::class)->orderBy('order_no');
    }

    public function talentAssessments(): HasMany
    {
        return $this->hasMany(TalentAssessment::class)->orderBy('id');
    }

    public function getSortedTalentAssessments()
    {
        return $this->talentAssessments()->get()->sort(function($a, $b) {
            $valA = \App\Services\TalentCalculatorService::parseAssessmentPeriod($a->assessment_date);
            $valB = \App\Services\TalentCalculatorService::parseAssessmentPeriod($b->assessment_date);
            if ($valA === $valB) return $b->id <=> $a->id;
            return $valB <=> $valA;
        })->values();
    }

    public function keyStrengths(): HasMany
    {
        return $this->hasMany(KeyStrength::class)->orderBy('order_no');
    }

    public function careerPlan(): HasOne
    {
        return $this->hasOne(CareerPlan::class);
    }

    public function jobClassPlans(): HasMany
    {
        return $this->hasMany(JobClassPlan::class)->orderBy('order_no');
    }

    public function successionPositions(): HasMany
    {
        return $this->hasMany(SuccessionPosition::class)->orderBy('order_no');
    }

    public function successionCandidates(): HasMany
    {
        return $this->hasMany(SuccessionCandidate::class)->orderBy('ranking');
    }

    public function competencyGaps(): HasMany
    {
        return $this->hasMany(CompetencyGap::class)->orderBy('order_no');
    }

    public function idpActionPlans(): HasMany
    {
        return $this->hasMany(IdpActionPlan::class)->orderBy('order_no');
    }

    public function developmentReviews(): HasMany
    {
        return $this->hasMany(DevelopmentReview::class)->orderBy('order_no');
    }

    public function trainingHistories(): HasMany
    {
        return $this->hasMany(TrainingHistory::class)->orderBy('order_no');
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class)->orderBy('order_no');
    }

    public function performanceAppraisals(): HasMany
    {
        return $this->hasMany(PerformanceAppraisal::class)->orderBy('year', 'asc');
    }

    /**
     * Mendapatkan 3 tahun terakhir data performance secara dinamis.
     * Jika ada data misal 2026, 2027, 2028 -> otomatis memunculkan 2026, 2027, 2028.
     */
    public function getThreeYearPerformances()
    {
        $records = $this->relationLoaded('performanceAppraisals') 
            ? $this->performanceAppraisals 
            : $this->performanceAppraisals()->get();

        if ($records->isEmpty()) {
            return collect([
                [
                    'year' => 2024, 
                    'label' => 'FY24', 
                    'rating' => $this->performance_fy24 ?: 'B+', 
                    'notes' => $this->performance_notes,
                    'record' => null
                ],
                [
                    'year' => 2025, 
                    'label' => 'FY25', 
                    'rating' => $this->performance_fy25 ?: 'A', 
                    'notes' => $this->performance_notes,
                    'record' => null
                ],
                [
                    'year' => 2026, 
                    'label' => 'FY26', 
                    'rating' => $this->performance_fy26 ?: ($this->performance_current ?: 'A'), 
                    'notes' => $this->performance_notes,
                    'record' => null
                ],
            ]);
        }

        $maxYear = (int) $records->max('year');
        if ($maxYear < 2026) {
            $maxYear = 2026;
        }

        $targetYears = [$maxYear - 2, $maxYear - 1, $maxYear];

        $result = collect();
        foreach ($targetYears as $yr) {
            $rec = $records->firstWhere('year', $yr);
            $label = 'FY' . substr((string)$yr, -2);
            $rating = '-';
            $notes = null;

            if ($rec) {
                $rating = $rec->rating;
                $notes = $rec->notes;
            } elseif ($yr === 2024 && !empty($this->performance_fy24)) {
                $rating = $this->performance_fy24;
            } elseif ($yr === 2025 && !empty($this->performance_fy25)) {
                $rating = $this->performance_fy25;
            } elseif ($yr === 2026 && !empty($this->performance_fy26)) {
                $rating = $this->performance_fy26;
            }

            $result->push([
                'year' => $yr,
                'label' => $label,
                'rating' => $rating,
                'notes' => $notes,
                'record' => $rec,
            ]);
        }

        return $result;
    }

    /**
     * C1. Perhitungan Performance 3 Tahun Terakhir berdasarkan C1.xlsx
     */
    public function getPerformanceCalculation(): array
    {
        $performances = $this->getThreeYearPerformances();
        $items = [];
        $totalScore = 0;

        foreach ($performances as $perf) {
            $pts = \App\Services\TalentCalculatorService::ratingToPoints($perf['rating'] ?? '-');
            $totalScore += $pts;
            $items[] = [
                'year' => $perf['year'],
                'label' => $perf['label'],
                'rating' => $perf['rating'],
                'points' => $pts,
                'notes' => $perf['notes'],
            ];
        }

        $baris = \App\Services\TalentCalculatorService::calculateBarisHav($totalScore);

        return [
            'items' => $items,
            'total_score' => $totalScore,
            'baris_hav' => $baris['code'],
            'baris_label' => $baris['label'],
        ];
    }

    /**
     * C5. Detail Flying Risk Assessment berdasarkan C5flyrisk.png
     */
    public function getFlyingRiskDetails(): array
    {
        return \App\Services\TalentCalculatorService::calculateFlyingRisk(
            $this->flying_risk_career_growth,
            $this->flying_risk_job_market,
            $this->flying_risk_compensation
        );
    }

    public function getFlyingRiskFormattedAttribute(): string
    {
        $val = trim((string)$this->flying_risk);
        $upper = strtoupper($val);
        if ($upper === 'LOW' || $upper === 'LOW RISK') {
            return 'Low Risk';
        }
        if ($upper === 'HIGH' || $upper === 'HIGH RISK') {
            return 'High Risk';
        }
        if ($upper === 'MEDIUM' || $upper === 'MODERATE' || $upper === 'MODERATE RISK') {
            return 'Moderate Risk';
        }
        return $val ?: 'Low Risk';
    }

    public function getFlyingRiskBadgeClassAttribute(): string
    {
        return match ($this->flying_risk_formatted) {
            'High Risk' => 'badge-red',
            'Moderate Risk' => 'badge-orange',
            default => 'badge-green',
        };
    }

    /**
     * C3. Detail HAV 16-Box
     */
    public function getHavBoxDetails(): array
    {
        // 1. Hitung default dari C1 dan C6
        $perf = $this->getPerformanceCalculation();
        $baris = $perf['baris_hav'] ?? 'R2';

        // Determine Kolom from latest C6 assessment or C2
        $latestAssessment = $this->getSortedTalentAssessments()->first();
        $kolom = 'C3';
        if ($latestAssessment && $latestAssessment->kolom_hav) {
            $kolom = $latestAssessment->kolom_hav;
        } elseif (!empty($this->potass_score_last)) {
            $num = (float) str_replace('%', '', $this->potass_score_last);
            if ($num >= 71) $kolom = 'C3';
            elseif ($num >= 61) $kolom = 'C2';
            elseif ($num >= 50) $kolom = 'C1';
            else $kolom = 'C0';
        }

        $hav = \App\Services\TalentCalculatorService::calculateHavBox($baris, $kolom);

        // 2. Cek jika pengguna memilih/mengedit posisi HAV Box secara manual
        if (!empty($this->hav_box_current)) {
            $overrideBoxNum = (int) str_replace('Box ', '', $this->hav_box_current);
            if ($overrideBoxNum >= 1 && $overrideBoxNum <= 16) {
                $boxInfo = \App\Services\TalentCalculatorService::getBoxInfoByNumber($overrideBoxNum);
                if ($boxInfo) {
                    $hav['box_number'] = $overrideBoxNum;
                    $hav['active_box_number'] = $overrideBoxNum;
                    $hav['box_label'] = 'Box ' . $overrideBoxNum;
                    $hav['category'] = $this->hav_box_category ?: $boxInfo['name'];
                    $hav['talent_pool'] = $this->talent_pool_status ?: $boxInfo['talent_pool'];
                    $hav['color'] = $boxInfo['color'];
                    $hav['textColor'] = $boxInfo['textColor'];
                    $hav['row'] = $boxInfo['row'];
                    $hav['col'] = $boxInfo['col'];
                }
            }
        } else {
            $hav['active_box_number'] = $hav['box_number'];
        }

        // 3. Prioritaskan pilihan manual pengguna untuk status talent pool jika diedit
        if (!empty($this->talent_pool_status)) {
            $hav['talent_pool'] = $this->talent_pool_status;
        }

        return $hav;
    }
}

