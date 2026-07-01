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
        return [
            ['LAPORAN MONITORING PELANGGARAN SISWA SMPN 216 JAKARTA'],
            ['Sistem Monitoring Perilaku dan Pelanggaran Siswa'],
            [],
            ['No','NIS','Nama Lengkap','L/P','Kelas','Skor Keseluruhan','Status','Kehadiran','Disiplin','Etika Sosial','Agresivitas','Integritas','Risiko Tinggi']
        ];
    }

    public function map($student): array
    {
        static $i = 0;
        $i++;
        $kpi = $student->kpi;
        return [
            $i,
            $student->nis,
            $student->full_name,
            $student->gender === 'L' ? 'Laki-laki' : 'Perempuan',
            $student->schoolClass?->class_name,
            $kpi?->overall_score ?? 0,
            $kpi?->status_label ?? 'Perlu Pembinaan',
            $kpi?->attendance_score ?? 0,
            $kpi?->discipline_score ?? 0,
            $kpi?->social_ethics_score ?? 0,
            $kpi?->aggression_score ?? 0,
            $kpi?->integrity_score ?? 0,
            $kpi?->high_risk_score ?? 0,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            4 => ['font' => ['bold' => true]],
        ];
    }
}
