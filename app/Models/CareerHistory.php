<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerHistory extends Model
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!empty($model->job_class) && !empty($model->grade)) {
                $model->job_class_grade = trim($model->job_class . ' / ' . $model->grade);
            } elseif (!empty($model->job_class_grade)) {
                $parts = explode('/', $model->job_class_grade);
                if (empty($model->job_class)) {
                    $model->job_class = trim($parts[0] ?? '');
                }
                if (empty($model->grade)) {
                    $model->grade = trim($parts[1] ?? '');
                }
            }
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getJobClassAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }
        if (!empty($this->job_class_grade)) {
            $parts = explode('/', $this->job_class_grade);
            return trim($parts[0] ?? '');
        }
        return '-';
    }

    public function getGradeAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }
        if (!empty($this->job_class_grade)) {
            $parts = explode('/', $this->job_class_grade);
            return trim($parts[1] ?? '');
        }
        return '-';
    }
}

