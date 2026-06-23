<?php

namespace App\Exports;

use App\Models\Violation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ViolationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(protected array $filters = []) {}

    public function collection()
    {
        $query = Violation::with(['student.schoolClass', 'createdBy']);
        if (!empty($this->filters['category']))  $query->where('category', $this->filters['category']);
        if (!empty($this->filters['severity']))  $query->where('severity', $this->filters['severity']);
        if (!empty($this->filters['date_from'])) $query->where('violation_date', '>=', $this->filters['date_from']);
        if (!empty($this->filters['date_to']))   $query->where('violation_date', '<=', $this->filters['date_to']);
        return $query->orderByDesc('violation_date')->get();
    }

    public function headings(): array
    {
        return ['No','Tanggal','NIS','Nama Siswa','Kelas','Kategori','Sub Kategori','Keterangan','Severity','Tindak Lanjut','Dicatat Oleh'];
    }

    public function map($violation): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $violation->violation_date->format('d/m/Y'),
            $violation->student?->nis,
            $violation->student?->full_name,
            $violation->student?->schoolClass?->class_name,
            $violation->category_label,
            $violation->sub_category_label,
            $violation->description,
            strtoupper($violation->severity),
            $violation->follow_up,
            $violation->createdBy?->name,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
