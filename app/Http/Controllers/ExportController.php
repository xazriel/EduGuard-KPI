<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Violation;
use App\Models\StudentKpi;
use App\Models\SchoolClass;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ViolationsExport;
use App\Exports\StudentsExport;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function violationsPdf(Request $request)
    {
        $query = Violation::with(['student.schoolClass', 'createdBy']);
        $this->applyFilters($query, $request);
        $violations = $query->orderByDesc('violation_date')->get();

        $pdf = Pdf::loadView('pdf.violations-report', [
            'violations' => $violations,
            'date'       => now()->locale('id')->isoFormat('D MMMM Y'),
            'filters'    => $request->all(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pelanggaran-' . now()->format('Ymd') . '.pdf');
    }

    public function violationsExcel(Request $request)
    {
        return Excel::download(new ViolationsExport($request->all()), 'laporan-pelanggaran-' . now()->format('Ymd') . '.xlsx');
    }

    public function studentsPdf(Request $request)
    {
        $students = Student::with(['schoolClass', 'kpi'])->active()->get();
        $pdf = Pdf::loadView('pdf.students-report', [
            'students' => $students,
            'date'     => now()->locale('id')->isoFormat('D MMMM Y'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-siswa-' . now()->format('Ymd') . '.pdf');
    }

    public function studentsExcel(Request $request)
    {
        return Excel::download(new StudentsExport(), 'laporan-siswa-' . now()->format('Ymd') . '.xlsx');
    }

    protected function applyFilters($query, Request $request): void
    {
        if ($request->filled('category'))  $query->where('category', $request->category);
        if ($request->filled('severity'))  $query->where('severity', $request->severity);
        if ($request->filled('date_from')) $query->where('violation_date', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->where('violation_date', '<=', $request->date_to);
        if ($request->filled('class_id'))  $query->whereHas('student', fn($q) => $q->where('class_id', $request->class_id));
    }
}
