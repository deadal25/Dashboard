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
}
