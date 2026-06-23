<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentKpi;
use App\Models\Violation;
use Carbon\Carbon;

class KpiEngine
{
    /**
     * KPI score deductions per sub_category
     */
    protected array $deductions = [
        // Kehadiran
        'telat'           => ['dimension' => 'attendance_score',    'points' => 5],
        'bolos'           => ['dimension' => 'attendance_score',    'points' => 15],
        'alpa'            => ['dimension' => 'attendance_score',    'points' => 20],
        // Kedisiplinan
        'atribut'         => ['dimension' => 'discipline_score',    'points' => 5],
        'aturan'          => ['dimension' => 'discipline_score',    'points' => 10],
        'administratif'   => ['dimension' => 'discipline_score',    'points' => 10],
        // Etika Sosial
        'menghina'        => ['dimension' => 'social_ethics_score', 'points' => 15],
        'bullying_verbal' => ['dimension' => 'social_ethics_score', 'points' => 20],
        'konflik_sosial'  => ['dimension' => 'social_ethics_score', 'points' => 25],
        // Agresivitas
        'provokasi'       => ['dimension' => 'aggression_score',    'points' => 20],
        'ancaman'         => ['dimension' => 'aggression_score',    'points' => 25],
        'berkelahi'       => ['dimension' => 'aggression_score',    'points' => 35],
        // Integritas
        'berbohong'       => ['dimension' => 'integrity_score',     'points' => 15],
        'mengambil_barang'=> ['dimension' => 'integrity_score',     'points' => 30],
        // Risiko Tinggi
        'rokok_vape'      => ['dimension' => 'high_risk_score',     'points' => 40],
        'pelanggaran_berat'=> ['dimension' => 'high_risk_score',    'points' => 50],
    ];

    /**
     * Recalculate all KPI dimensions for a student.
     */
    public function recalculate(Student $student): StudentKpi
    {
        $violations = $student->violations()->get();

        // Start all dimensions at 0
        $scores = [
            'attendance_score'     => 0.0,
            'discipline_score'     => 0.0,
            'social_ethics_score'  => 0.0,
            'aggression_score'     => 0.0,
            'integrity_score'      => 0.0,
            'high_risk_score'      => 0.0,
        ];

        foreach ($violations as $violation) {
            $key = strtolower(str_replace([' ', '-'], '_', $violation->sub_category));
            if (isset($this->deductions[$key])) {
                $dim = $this->deductions[$key]['dimension'];
                $pts = $this->deductions[$key]['points'];
                $scores[$dim] = min(100.0, $scores[$dim] + $pts);
            } else {
                // Fallback: use category to determine dimension
                $dim = $this->getCategoryDimension($violation->category);
                $pts = $this->getSeverityPoints($violation->severity);
                $scores[$dim] = min(100.0, $scores[$dim] + $pts);
            }
        }

        // Behavior trend: compare last 30 days vs previous 30 days
        $behaviorTrend = $this->calculateBehaviorTrend($student);
        $scores['behavior_trend_score'] = $behaviorTrend;

        // Overall = average of all 7 dimensions
        $overall = array_sum($scores) / count($scores);
        $overall = round($overall, 2);

        $maxCoreScore = max([
            $scores['attendance_score'],
            $scores['discipline_score'],
            $scores['social_ethics_score'],
            $scores['aggression_score'],
            $scores['integrity_score'],
            $scores['high_risk_score'],
        ]);

        $warningStatus = $this->determineWarningStatus($maxCoreScore);

        $kpi = StudentKpi::updateOrCreate(
            ['student_id' => $student->id],
            array_merge($scores, [
                'overall_score'  => $overall,
                'warning_status' => $warningStatus,
                'updated_at'     => now(),
            ])
        );

        return $kpi;
    }

    protected function getCategoryDimension(string $category): string
    {
        return match ($category) {
            'kehadiran'     => 'attendance_score',
            'kedisiplinan'  => 'discipline_score',
            'etika_sosial'  => 'social_ethics_score',
            'agresivitas'   => 'aggression_score',
            'integritas'    => 'integrity_score',
            'risiko_tinggi' => 'high_risk_score',
            default         => 'discipline_score',
        };
    }

    protected function getSeverityPoints(string $severity): int
    {
        return match ($severity) {
            'ringan' => 5,
            'sedang' => 15,
            'berat'  => 30,
            default  => 10,
        };
    }

    protected function calculateBehaviorTrend(Student $student): float
    {
        $now     = Carbon::now();
        $current = $student->violations()
            ->whereBetween('violation_date', [$now->copy()->subDays(30), $now])
            ->count();

        $previous = $student->violations()
            ->whereBetween('violation_date', [$now->copy()->subDays(60), $now->copy()->subDays(31)])
            ->count();

        if ($previous === 0 && $current === 0) return 0.0;
        if ($previous === 0) return min(100.0, $current * 10);

        $diff = $current - $previous;
        $base = $current * 10;
        return max(0.0, min(100.0, $base + ($diff * 10)));
    }

    public function determineWarningStatus(float $score): string
    {
        if ($score >= 40) return 'red';
        if ($score >= 20) return 'yellow';
        return 'green';
    }

    /**
     * Get monthly violation counts for the last N months (for charts)
     */
    public function getMonthlyTrend(Student $student, int $months = 6): array
    {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date  = Carbon::now()->subMonths($i);
            $count = $student->violations()
                ->whereYear('violation_date', $date->year)
                ->whereMonth('violation_date', $date->month)
                ->count();
            $data[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
            ];
        }
        return $data;
    }

    /**
     * School-wide aggregated KPI data
     */
    public function getSchoolAggregates(): array
    {
        $kpis = StudentKpi::all();
        if ($kpis->isEmpty()) {
            return [
                'avg_overall'   => 0,
                'green_count'   => 0,
                'yellow_count'  => 0,
                'red_count'     => 0,
                'total_students'=> 0,
            ];
        }

        return [
            'avg_overall'    => round($kpis->avg('overall_score'), 1),
            'green_count'    => $kpis->where('warning_status', 'green')->count(),
            'yellow_count'   => $kpis->where('warning_status', 'yellow')->count(),
            'red_count'      => $kpis->where('warning_status', 'red')->count(),
            'total_students' => $kpis->count(),
        ];
    }
}
