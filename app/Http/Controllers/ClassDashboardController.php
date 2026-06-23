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
        $students = $class->students;
        $avgKpi = [
            'overall_score'        => $students->avg(fn($s) => $s->kpi?->overall_score ?? 0.0) ?? 0.0,
            'attendance_score'     => $students->avg(fn($s) => $s->kpi?->attendance_score ?? 0.0) ?? 0.0,
            'discipline_score'     => $students->avg(fn($s) => $s->kpi?->discipline_score ?? 0.0) ?? 0.0,
            'social_ethics_score'  => $students->avg(fn($s) => $s->kpi?->social_ethics_score ?? 0.0) ?? 0.0,
            'aggression_score'     => $students->avg(fn($s) => $s->kpi?->aggression_score ?? 0.0) ?? 0.0,
            'integrity_score'      => $students->avg(fn($s) => $s->kpi?->integrity_score ?? 0.0) ?? 0.0,
            'high_risk_score'      => $students->avg(fn($s) => $s->kpi?->high_risk_score ?? 0.0) ?? 0.0,
        ];
        $avgKpi = array_map(fn($v) => round($v, 1), $avgKpi);

        // Monthly trend for this class by severity
        $months = [];
        $monthDates = [];
        $trendRingan = [];
        $trendSedang = [];
        $trendBerat = [];
        $studentIds = $class->students->pluck('id');
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->locale('id')->isoFormat('MMM');
            $monthDates[] = $date->format('Y-m');
            
            $trendRingan[] = Violation::whereIn('student_id', $studentIds)
                ->whereYear('violation_date', $date->year)
                ->whereMonth('violation_date', $date->month)
                ->where('severity', 'ringan')
                ->count();
                
            $trendSedang[] = Violation::whereIn('student_id', $studentIds)
                ->whereYear('violation_date', $date->year)
                ->whereMonth('violation_date', $date->month)
                ->where('severity', 'sedang')
                ->count();
                
            $trendBerat[] = Violation::whereIn('student_id', $studentIds)
                ->whereYear('violation_date', $date->year)
                ->whereMonth('violation_date', $date->month)
                ->where('severity', 'berat')
                ->count();
        }

        // Category breakdown
        $categoryData = Violation::whereIn('student_id', $studentIds)
            ->select('category')->selectRaw('COUNT(*) as total')
            ->groupBy('category')->pluck('total', 'category')->toArray();

        // High risk students sorted by score descending (highest risk first)
        $highRiskStudents = $class->students
            ->filter(fn($s) => $s->kpi && $s->kpi->warning_status !== 'green')
            ->sortByDesc(fn($s) => $s->kpi->overall_score)
            ->values();

        // All students sorted by score descending
        $studentRanking = $class->students
            ->sortByDesc(fn($s) => $s->kpi?->overall_score ?? 0)
            ->values();

        return view('dashboard.class-show', compact(
            'class', 'avgKpi', 'months', 'monthDates', 'trendRingan', 'trendSedang', 'trendBerat',
            'categoryData', 'highRiskStudents', 'studentRanking'
        ));
    }
}
