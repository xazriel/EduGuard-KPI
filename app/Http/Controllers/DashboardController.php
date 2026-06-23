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
        // KPI aggregates
        $aggregates = $this->kpiEngine->getSchoolAggregates();

        // Monthly violation trend (last 6 months) by severity
        $months = [];
        $monthDates = [];
        $trendRingan = [];
        $trendSedang = [];
        $trendBerat = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->locale('id')->isoFormat('MMM');
            $monthDates[] = $date->format('Y-m');
            
            $trendRingan[] = Violation::whereYear('violation_date', $date->year)
                ->whereMonth('violation_date', $date->month)
                ->where('severity', 'ringan')
                ->count();
                
            $trendSedang[] = Violation::whereYear('violation_date', $date->year)
                ->whereMonth('violation_date', $date->month)
                ->where('severity', 'sedang')
                ->count();
                
            $trendBerat[] = Violation::whereYear('violation_date', $date->year)
                ->whereMonth('violation_date', $date->month)
                ->where('severity', 'berat')
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
                $scores = $class->students->map(fn($s) => $s->kpi?->overall_score ?? 0.0);
                return [
                    'id'          => $class->id,
                    'name'        => $class->class_name,
                    'avg_score'   => $scores->isEmpty() ? 0.0 : round($scores->avg(), 1),
                    'red_count'   => $class->students->filter(fn($s) => $s->kpi?->warning_status === 'red')->count(),
                    'total'       => $class->students->count(),
                ];
            })
            ->sortByDesc('avg_score')
            ->values();

        // Top high-risk students
        $highRiskStudents = StudentKpi::where('warning_status', 'red')
            ->orWhere('warning_status', 'yellow')
            ->with(['student.schoolClass'])
            ->orderByDesc('overall_score')
            ->limit(10)
            ->get();

        // Total stats
        $totalStudents    = Student::active()->count();
        $totalViolations  = Violation::count();
        $totalThisMonth   = Violation::whereMonth('violation_date', now()->month)
            ->whereYear('violation_date', now()->year)
            ->count();

        $data = compact(
            'aggregates', 'months', 'monthDates', 'trendRingan', 'trendSedang', 'trendBerat',
            'categoryData', 'severityData', 'classRanking',
            'highRiskStudents', 'totalStudents', 'totalViolations', 'totalThisMonth'
        );

        $alerts = $this->warningService->getSchoolAlerts();

        return view('dashboard.school', array_merge($data, ['alerts' => $alerts]));
    }}
