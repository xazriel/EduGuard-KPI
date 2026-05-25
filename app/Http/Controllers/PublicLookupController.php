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
            ->orWhere('nisn', $term)
            ->first();

        if (!$student) {
            return back()->withErrors(['query' => 'Siswa dengan NIS/NISN tersebut tidak ditemukan.'])
                         ->withInput();
        }

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

        if ($kpi->attendance_score < 80)   $recs[] = 'Tingkatkan kehadiran dan konsistensi hadir ke sekolah.';
        if ($kpi->discipline_score < 80)   $recs[] = 'Patuhi tata tertib sekolah dan aturan yang berlaku.';
        if ($kpi->social_ethics_score < 80) $recs[] = 'Jaga etika dan sopan santun dalam berinteraksi.';
        if ($kpi->aggression_score < 70)   $recs[] = 'Kelola emosi dengan baik, hindari konflik fisik.';
        if ($kpi->integrity_score < 80)    $recs[] = 'Bangun kejujuran dan integritas dalam setiap tindakan.';
        if ($kpi->high_risk_score < 60)    $recs[] = 'Segera konsultasi dengan Guru BK untuk penanganan khusus.';
        if (empty($recs))                  $recs[] = 'Perilaku siswa sangat baik. Terus pertahankan!';

        return $recs;
    }
}
