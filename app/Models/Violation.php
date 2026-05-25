<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Violation extends Model
{
    protected $fillable = [
        'student_id', 'category', 'sub_category', 'description',
        'severity', 'violation_date', 'follow_up', 'created_by',
    ];

    protected $casts = [
        'violation_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function statementLetter(): HasOne
    {
        return $this->hasOne(StatementLetter::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'kehadiran'     => 'Kehadiran',
            'kedisiplinan'  => 'Kedisiplinan',
            'etika_sosial'  => 'Etika Sosial',
            'agresivitas'   => 'Agresivitas',
            'integritas'    => 'Integritas',
            'risiko_tinggi' => 'Risiko Tinggi',
            default         => ucfirst($this->category),
        };
    }

    public function getSeverityLabelAttribute(): string
    {
        return match ($this->severity) {
            'ringan' => 'Ringan',
            'sedang' => 'Sedang',
            'berat'  => 'Berat',
            default  => ucfirst($this->severity),
        };
    }

    public function getSeverityColorAttribute(): string
    {
        return match ($this->severity) {
            'ringan' => 'yellow',
            'sedang' => 'orange',
            'berat'  => 'red',
            default  => 'gray',
        };
    }
}
