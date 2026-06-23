<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentKpi;
use App\Models\Violation;

class EarlyWarningService
{
    public function checkAndAlert(Student $student): array
    {
        $alerts = [];
        $kpi    = $student->kpi;

        if (!$kpi) return [];

        // 1. Overall score dropped below 60 (red zone)
        if ($kpi->warning_status === 'red') {
            $alerts[] = [
                'type'    => 'danger',
                'message' => "Siswa {$student->full_name} masuk risiko TINGGI (skor: {$kpi->overall_score})",
            ];
        }

        // 2. Score dropped more than 15 points in last month
        $recentViolations = $student->violations()
            ->where('violation_date', '>=', now()->subMonth())
            ->count();
        if ($recentViolations >= 3) {
            $alerts[] = [
                'type'    => 'warning',
                'message' => "Siswa {$student->full_name} memiliki {$recentViolations} pelanggaran dalam 30 hari terakhir.",
            ];
        }

        // 3. Same violation 3+ times
        $repeatedViolations = $student->violations()
            ->select('sub_category')
            ->groupBy('sub_category')
            ->havingRaw('COUNT(*) >= 3')
            ->pluck('sub_category');

        foreach ($repeatedViolations as $sub) {
            $alerts[] = [
                'type'    => 'warning',
                'message' => "Pelanggaran '{$sub}' terjadi 3+ kali oleh {$student->full_name}.",
            ];
        }

        // 4. Heavy violation detected
        $heavyViolation = $student->violations()
            ->where('severity', 'berat')
            ->where('created_at', '>=', now()->subDays(7))
            ->first();

        if ($heavyViolation) {
            $alerts[] = [
                'type'    => 'danger',
                'message' => "Pelanggaran BERAT terdeteksi pada {$student->full_name}: {$heavyViolation->sub_category}.",
            ];
        }

        return $alerts;
    }

    public function getSchoolAlerts(): array
    {
        $redStudents = StudentKpi::where('warning_status', 'red')
            ->with('student.schoolClass')
            ->get();

        $alerts = [];
        foreach ($redStudents as $kpi) {
            $alerts[] = [
                'type'    => 'danger',
                'student' => $kpi->student,
                'score'   => $kpi->overall_score,
                'message' => "Risiko Tinggi — Skor: {$kpi->overall_score}",
            ];
        }

        // Students with 3+ violations this month
        $activeAlerts = \App\Models\Violation::select('student_id')
            ->where('violation_date', '>=', now()->subMonth())
            ->groupBy('student_id')
            ->havingRaw('COUNT(*) >= 3')
            ->with('student')
            ->get();

        foreach ($activeAlerts as $v) {
            if ($v->student && $v->student->kpi?->warning_status !== 'red') {
                $alerts[] = [
                    'type'    => 'warning',
                    'student' => $v->student,
                    'score'   => $v->student->kpi?->overall_score ?? 0,
                    'message' => '3+ Pelanggaran dalam 30 hari',
                ];
            }
        }

        return $alerts;
    }
}
