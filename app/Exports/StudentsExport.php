<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Student::with(['schoolClass', 'kpi'])->active()->orderBy('full_name')->get();
    }

    public function headings(): array
    {
        return ['No','NIS','NISN','Nama Lengkap','L/P','Kelas','Skor Keseluruhan','Status','Kehadiran','Disiplin','Etika Sosial','Agresivitas','Integritas','Risiko Tinggi'];
    }

    public function map($student): array
    {
        static $i = 0;
        $i++;
        $kpi = $student->kpi;
        return [
            $i,
            $student->nis,
            $student->nisn,
            $student->full_name,
            $student->gender === 'L' ? 'Laki-laki' : 'Perempuan',
            $student->schoolClass?->class_name,
            $kpi?->overall_score ?? 100,
            $kpi ? strtoupper($kpi->warning_status) : 'GREEN',
            $kpi?->attendance_score ?? 100,
            $kpi?->discipline_score ?? 100,
            $kpi?->social_ethics_score ?? 100,
            $kpi?->aggression_score ?? 100,
            $kpi?->integrity_score ?? 100,
            $kpi?->high_risk_score ?? 100,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
