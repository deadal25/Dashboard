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
}
