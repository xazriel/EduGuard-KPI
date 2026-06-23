<?php

namespace App\Services;

use App\Models\Violation;
use App\Models\StatementLetter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfGeneratorService
{
    public function generateStatementLetter(Violation $violation): StatementLetter
    {
        $violation->load(['student.schoolClass', 'createdBy']);

        $violations = $violation->student->violations()
            ->get()
            ->map(function($v) {
                return $v->category_label . ' — ' . $v->sub_category_label . ($v->description ? ' (' . $v->description . ')' : '');
            })->toArray();

        $pdf = Pdf::loadView('pdf.statement-letter', [
            'violation'  => $violation,
            'student'    => $violation->student,
            'class'      => $violation->student->schoolClass,
            'date'       => now()->locale('id')->isoFormat('D MMMM Y'),
            'violations' => $violations,
        ])->setPaper('a4');

        $filename = 'letters/surat-pernyataan-' . $violation->student->nis . '-' . $violation->id . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        $letter = StatementLetter::updateOrCreate(
            ['violation_id' => $violation->id],
            ['generated_pdf' => $filename]
        );

        return $letter;
    }

    public function generateStudentReport(array $students): string
    {
        $pdf = Pdf::loadView('pdf.students-report', [
            'students' => $students,
            'date'     => now()->locale('id')->isoFormat('D MMMM Y'),
        ])->setPaper('a4', 'landscape');

        $filename = 'reports/laporan-siswa-' . now()->format('Ymd-His') . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }
}
