<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certification extends Model
{
    protected $guarded = ['id'];

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

    /**
     * Get obtained_date as ISO date string (YYYY-MM-DD) for HTML5 date inputs
     */
    public function getObtainedDateIsoAttribute(): ?string
    {
        return self::parseIndonesianDateToIso($this->obtained_date);
    }

    /**
     * Get valid_until as ISO date string (YYYY-MM-DD) for HTML5 date inputs
     */
    public function getValidUntilIsoAttribute(): ?string
    {
        return self::parseIndonesianDateToIso($this->valid_until);
    }

    /**
     * Convert Indonesian date string (or other date format) to YYYY-MM-DD
     */
    public static function parseIndonesianDateToIso(?string $dateStr): ?string
    {
        if (empty($dateStr)) return null;
        $dateStr = trim($dateStr);

        // Already YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
            return $dateStr;
        }

        if (strtolower($dateStr) === 'seumur hidup' || strtolower($dateStr) === 'tanpa batas waktu' || strtolower($dateStr) === 'lifetime') {
            return null;
        }

        $months = [
            'jan' => '01', 'januari' => '01',
            'feb' => '02', 'februari' => '02',
            'mar' => '03', 'maret' => '03',
            'apr' => '04', 'april' => '04',
            'mei' => '05', 'may' => '05',
            'jun' => '06', 'juni' => '06',
            'jul' => '07', 'juli' => '07',
            'agu' => '08', 'agt' => '08', 'agustus' => '08', 'aug' => '08',
            'sep' => '09', 'september' => '09',
            'okt' => '10', 'oktober' => '10', 'oct' => '10',
            'nov' => '11', 'november' => '11',
            'des' => '12', 'desember' => '12', 'dec' => '12',
        ];

        // Match "20 Mar 2025" or "5 Jul 2024"
        if (preg_match('/^(\d{1,2})\s+([a-zA-Z]+)\s+(\d{4})$/', $dateStr, $m)) {
            $day = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $monthKey = strtolower($m[2]);
            $month = $months[$monthKey] ?? null;
            $year = $m[3];
            if ($month) {
                return "{$year}-{$month}-{$day}";
            }
        }

        $ts = strtotime($dateStr);
        if ($ts && $ts > 0) {
            return date('Y-m-d', $ts);
        }

        return null;
    }

    /**
     * Convert ISO date (YYYY-MM-DD) or other date string to Indonesian formatted date (e.g. 20 Mar 2025)
     */
    public static function formatIndonesianDate(?string $dateStr): ?string
    {
        if (empty($dateStr)) return null;
        $dateStr = trim($dateStr);

        if (strtolower($dateStr) === 'seumur hidup' || strtolower($dateStr) === 'tanpa batas waktu' || strtolower($dateStr) === 'lifetime') {
            return 'Seumur Hidup';
        }

        // Check if YYYY-MM-DD
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $dateStr, $m)) {
            $year = $m[1];
            $monthNum = (int)$m[2];
            $day = (int)$m[3];
            $months = [
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
            ];
            $monthName = $months[$monthNum] ?? sprintf('%02d', $monthNum);
            return "{$day} {$monthName} {$year}";
        }

        return $dateStr;
    }
}
