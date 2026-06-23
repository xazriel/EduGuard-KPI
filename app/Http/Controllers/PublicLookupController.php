<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\KpiEngine;
use Illuminate\Http\Request;

class PublicLookupController extends Controller
{
    public function __construct(protected KpiEngine $kpiEngine) {}

    public function index()
    {
        return view('public.lookup');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3|max:30',
        ]);

        $term = trim($request->input('query'));

        $student = Student::with(['schoolClass', 'kpi', 'violations.statementLetter'])
            ->where('nis', $term)
            ->first();

        if (!$student) {
            return back()->withErrors(['query' => 'Siswa dengan NIS tersebut tidak ditemukan.'])
                         ->withInput();
        }

        $this->kpiEngine->recalculate($student);
        $student->load('kpi');

        $monthlyTrend = $this->kpiEngine->getMonthlyTrend($student, 6);
        $months       = collect($monthlyTrend)->pluck('month');
        $counts       = collect($monthlyTrend)->pluck('count');

        $recommendations = $this->getRecommendations($student);

        return view('public.student-result', compact('student', 'months', 'counts', 'recommendations'));
    }

    protected function getRecommendations(Student $student): array
    {
        $kpi  = $student->kpi;
        $recs = [];
        if (!$kpi) return ['Data KPI belum tersedia.'];

        if ($kpi->attendance_score > 20)   $recs[] = 'Tingkatkan kehadiran dan konsistensi hadir ke sekolah.';
        if ($kpi->discipline_score > 20)   $recs[] = 'Patuhi tata tertib sekolah dan aturan yang berlaku.';
        if ($kpi->social_ethics_score > 20) $recs[] = 'Jaga etika dan sopan santun dalam berinteraksi.';
        if ($kpi->aggression_score > 30)   $recs[] = 'Kelola emosi dengan baik, hindari konflik fisik.';
        if ($kpi->integrity_score > 20)    $recs[] = 'Bangun kejujuran dan integritas dalam setiap tindakan.';
        if ($kpi->high_risk_score > 40)    $recs[] = 'Segera konsultasi dengan Guru BK untuk penanganan khusus.';
        // No recommendations if student has good behavior

        return $recs;
    }
}
