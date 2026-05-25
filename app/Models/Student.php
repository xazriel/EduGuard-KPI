<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Student extends Model
{
    protected $fillable = [
        'nis', 'nisn', 'full_name', 'gender', 'birth_place',
        'birth_date', 'parent_name', 'address', 'class_id', 'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function violations(): HasMany
    {
        return $this->hasMany(Violation::class);
    }

    public function kpi(): HasOne
    {
        return $this->hasOne(StudentKpi::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nis', 'like', "%{$term}%")
              ->orWhere('nisn', 'like', "%{$term}%")
              ->orWhere('full_name', 'like', "%{$term}%");
        });
    }

    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getWarningStatusAttribute(): string
    {
        return $this->kpi?->warning_status ?? 'green';
    }
}
