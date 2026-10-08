<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingHistory extends Model
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->training_date) && !empty($model->start_date)) {
                $model->training_date = self::formatDateRange($model->start_date, $model->end_date);
            }
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getIsImageAttribute(): bool
    {
        if (!$this->documentation) return false;
        $ext = strtolower(pathinfo($this->documentation, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
    }

    public function getIsPdfAttribute(): bool
    {
        if (!$this->documentation) return false;
        $ext = strtolower(pathinfo($this->documentation, PATHINFO_EXTENSION));
        return $ext === 'pdf';
    }

    public function getFileExtensionAttribute(): ?string
    {
        if (!$this->documentation) return null;
        return strtolower(pathinfo($this->documentation, PATHINFO_EXTENSION));
    }

    public function getYearAttribute(): ?int
    {
        if (!empty($this->start_date) && preg_match('/^(19\d\d|20\d\d)/', $this->start_date, $m)) {
            return (int)$m[1];
        }
        if (!empty($this->training_date) && preg_match('/\b(19\d\d|20\d\d)\b/', $this->training_date, $m)) {
            return (int)$m[1];
        }
        return null;
    }

    public static function formatDateRange(?string $start, ?string $end): string
    {
        if (empty($start)) return '-';
        if (empty($end) || $start === $end) {
            $ts = strtotime($start);
            if (!$ts) return $start;
            return date('j', $ts) . ' ' . self::getIndonesianMonth(date('n', $ts)) . ' ' . date('Y', $ts);
        }

        $tsStart = strtotime($start);
        $tsEnd = strtotime($end);
        if (!$tsStart || !$tsEnd) return $start . ' – ' . $end;

        $yStart = date('Y', $tsStart);
        $yEnd = date('Y', $tsEnd);
        $mStart = date('n', $tsStart);
        $mEnd = date('n', $tsEnd);
        $dStart = date('j', $tsStart);
        $dEnd = date('j', $tsEnd);

        if ($yStart === $yEnd && $mStart === $mEnd) {
            return "{$dStart} – {$dEnd} " . self::getIndonesianMonth($mStart) . " {$yStart}";
        } elseif ($yStart === $yEnd) {
            return "{$dStart} " . self::getIndonesianMonth($mStart) . " – {$dEnd} " . self::getIndonesianMonth($mEnd) . " {$yStart}";
        }

        return "{$dStart} " . self::getIndonesianMonth($mStart) . " {$yStart} – {$dEnd} " . self::getIndonesianMonth($mEnd) . " {$yEnd}";
    }

    private static function getIndonesianMonth(int $monthNum): string
    {
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        return $months[$monthNum] ?? '';
    }
}
