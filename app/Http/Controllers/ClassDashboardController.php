<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Violation;
use App\Models\StudentKpi;
use App\Services\KpiEngine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClassDashboardController extends Controller
{
    public function __construct(protected KpiEngine $kpiEngine) {}

    public function index()
    {
        $classes = SchoolClass::with(['students.kpi'])->withCount('students')->get();
        return view('dashboard.class-list', compact('classes'));
    }

    public function show(SchoolClass $class)
    {
        $class->load(['students.kpi', 'students.violations']);

        // KPI averages for the class
        $kpis = $class->students->filter(fn($s) => $s->kpi);
        $avgKpi = [
            'overall_score'        => $kpis->avg(fn($s) => $s->kpi->overall_score) ?? 100,
            'attendance_score'     => $kpis->avg(fn($s) => $s->kpi->attendance_score) ?? 100,
            'discipline_score'     => $kpis->avg(fn($s) => $s->kpi->discipline_score) ?? 100,
            'social_ethics_score'  => $kpis->avg(fn($s) => $s->kpi->social_ethics_score) ?? 100,
            'aggression_score'     => $kpis->avg(fn($s) => $s->kpi->aggression_score) ?? 100,
            'integrity_score'      => $kpis->avg(fn($s) => $s->kpi->integrity_score) ?? 100,
            'high_risk_score'      => $kpis->avg(fn($s) => $s->kpi->high_risk_score) ?? 100,
        ];
        $avgKpi = array_map(fn($v) => round($v, 1), $avgKpi);

        // Monthly trend for this class
        $months = [];
        $monthlyViolations = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->locale('id')->isoFormat('MMM');
            $monthlyViolations[] = Violation::whereIn('student_id', $class->students->pluck('id'))
                ->whereYear('violation_date', $date->year)
                ->whereMonth('violation_date', $date->month)
                ->count();
        }

        // Category breakdown
        $categoryData = Violation::whereIn('student_id', $class->students->pluck('id'))
            ->select('category')->selectRaw('COUNT(*) as total')
            ->groupBy('category')->pluck('total', 'category')->toArray();

        // High risk students sorted by score ascending
        $highRiskStudents = $class->students
            ->filter(fn($s) => $s->kpi && $s->kpi->warning_status !== 'green')
            ->sortBy(fn($s) => $s->kpi->overall_score)
            ->values();

        // All students sorted by score
        $studentRanking = $class->students
            ->sortBy(fn($s) => $s->kpi?->overall_score ?? 100)
            ->values();

        return view('dashboard.class-show', compact(
            'class', 'avgKpi', 'months', 'monthlyViolations',
            'categoryData', 'highRiskStudents', 'studentRanking'
        ));
    }
}
