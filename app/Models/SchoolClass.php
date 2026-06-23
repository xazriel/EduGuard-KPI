<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    protected $table = 'classes';

    protected $fillable = ['class_name', 'grade_level'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function getAverageKpiAttribute(): float
    {
        return $this->students()
            ->with('kpi')
            ->get()
            ->filter(fn($s) => $s->kpi)
            ->avg(fn($s) => $s->kpi->overall_score) ?? 0.0;
    }

    public function getHighRiskCountAttribute(): int
    {
        return $this->students()
            ->whereHas('kpi', fn($q) => $q->where('warning_status', 'red'))
            ->count();
    }
}
