<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentKpi;
use App\Models\Violation;
use App\Services\KpiEngine;
use App\Services\EarlyWarningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(
        protected KpiEngine $kpiEngine,
        protected EarlyWarningService $warningService,
    ) {}

    public function index()
    {
        $data = Cache::remember('school_dashboard', 300, function () {
            // KPI aggregates
            $aggregates = $this->kpiEngine->getSchoolAggregates();

            // Monthly violation trend (last 6 months)
            $months = [];
            $monthlyViolations = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months[] = $date->locale('id')->isoFormat('MMM');
                $monthlyViolations[] = Violation::whereYear('violation_date', $date->year)
                    ->whereMonth('violation_date', $date->month)
                    ->count();
            }

            // Category distribution
            $categoryData = Violation::select('category')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('category')
                ->pluck('total', 'category')
                ->toArray();

            // Severity distribution
            $severityData = Violation::select('severity')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('severity')
                ->pluck('total', 'severity')
                ->toArray();

            // Class ranking by risk
            $classRanking = SchoolClass::with(['students.kpi'])
                ->get()
                ->map(function ($class) {
                    $kpis = $class->students->filter(fn($s) => $s->kpi)->map(fn($s) => $s->kpi->overall_score);
                    return [
                        'name'        => $class->class_name,
                        'avg_score'   => $kpis->isEmpty() ? 100 : round($kpis->avg(), 1),
                        'red_count'   => $class->students->filter(fn($s) => $s->kpi?->warning_status === 'red')->count(),
                        'total'       => $class->students->count(),
                    ];
                })
                ->sortBy('avg_score')
                ->values();

            // Top high-risk students
            $highRiskStudents = StudentKpi::where('warning_status', 'red')
                ->orWhere('warning_status', 'yellow')
                ->with(['student.schoolClass'])
                ->orderBy('overall_score')
                ->limit(10)
                ->get();

            // Total stats
            $totalStudents    = Student::active()->count();
            $totalViolations  = Violation::count();
            $totalThisMonth   = Violation::whereMonth('violation_date', now()->month)
                ->whereYear('violation_date', now()->year)
                ->count();

            return compact(
                'aggregates', 'months', 'monthlyViolations',
                'categoryData', 'severityData', 'classRanking',
                'highRiskStudents', 'totalStudents', 'totalViolations', 'totalThisMonth'
            );
        });

        $alerts = $this->warningService->getSchoolAlerts();

        return view('dashboard.school', array_merge($data, ['alerts' => $alerts]));
    }
}
