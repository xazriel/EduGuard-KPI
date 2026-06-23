<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentKpi extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'student_id', 'attendance_score', 'discipline_score', 'social_ethics_score',
        'aggression_score', 'integrity_score', 'high_risk_score',
        'behavior_trend_score', 'overall_score', 'warning_status', 'updated_at',
    ];

    protected $casts = [
        'attendance_score'     => 'float',
        'discipline_score'     => 'float',
        'social_ethics_score'  => 'float',
        'aggression_score'     => 'float',
        'integrity_score'      => 'float',
        'high_risk_score'      => 'float',
        'behavior_trend_score' => 'float',
        'overall_score'        => 'float',
        'updated_at'           => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->warning_status) {
            'green'  => '#22c55e',
            'yellow' => '#eab308',
            'red'    => '#ef4444',
            default  => '#6b7280',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->warning_status) {
            'green'  => 'Baik',
            'yellow' => 'Perlu Pembinaan',
            'red'    => 'Risiko Tinggi',
            default  => '-',
        };
    }

    public function getDimensionsAttribute(): array
    {
        return [
            'Kehadiran'    => $this->attendance_score,
            'Kedisiplinan' => $this->discipline_score,
            'Etika Sosial' => $this->social_ethics_score,
            'Agresivitas'  => $this->aggression_score,
            'Integritas'   => $this->integrity_score,
            'Risiko Tinggi'=> $this->high_risk_score,
            'Tren Perilaku'=> $this->behavior_trend_score,
        ];
    }
}
