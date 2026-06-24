<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Services\KpiEngine;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(protected KpiEngine $kpiEngine) {}

    public function index(Request $request)
    {
        $query = Student::with(['schoolClass', 'kpi'])->active();

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('warning_status')) {
            if ($request->warning_status === 'pembinaan') {
                $query->whereHas('kpi', fn($q) => $q->whereIn('warning_status', ['green', 'yellow']));
            } else {
                $query->whereHas('kpi', fn($q) => $q->where('warning_status', $request->warning_status));
            }
        }

        $students = $query->orderBy('full_name')->paginate(20)->withQueryString();
        $classes  = SchoolClass::orderBy('grade_level')->orderBy('class_name')->get();

        return view('students.index', compact('students', 'classes'));
    }

    public function show(Student $student)
    {
        $this->kpiEngine->recalculate($student);
        $student->load(['schoolClass', 'kpi', 'violations.statementLetter', 'violations.createdBy']);

        $monthlyTrend = $this->kpiEngine->getMonthlyTrend($student, 6);

        $months      = collect($monthlyTrend)->pluck('month');
        $counts      = collect($monthlyTrend)->pluck('count');

        $categoryBreakdown = $student->violations()
            ->select('category')->selectRaw('COUNT(*) as total')
            ->groupBy('category')->pluck('total', 'category');

        $recommendations = $this->getRecommendations($student);

        return view('students.show', compact(
            'student', 'months', 'counts', 'categoryBreakdown', 'recommendations'
        ));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('grade_level')->orderBy('class_name')->get();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis'         => 'required|string|max:20|unique:students',
            'full_name'   => 'required|string|max:255',
            'gender'      => 'required|in:L,P',
            'birth_place' => 'nullable|string|max:100',
            'birth_date'  => 'nullable|date',
            'parent_name' => 'nullable|string|max:255',
            'address'     => 'nullable|string',
            'class_id'    => 'required|exists:classes,id',
            'status'      => 'required|in:active,inactive,graduated',
        ]);

        $student = Student::create($validated);

        // Initialize KPI
        $this->kpiEngine->recalculate($student);

        return redirect()->route('students.show', $student)
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::orderBy('grade_level')->orderBy('class_name')->get();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nis'         => 'required|string|max:20|unique:students,nis,' . $student->id,
            'full_name'   => 'required|string|max:255',
            'gender'      => 'required|in:L,P',
            'birth_place' => 'nullable|string|max:100',
            'birth_date'  => 'nullable|date',
            'parent_name' => 'nullable|string|max:255',
            'address'     => 'nullable|string',
            'class_id'    => 'required|exists:classes,id',
            'status'      => 'required|in:active,inactive,graduated',
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function search(Request $request)
    {
        $term = $request->get('q', '');
        $students = Student::with(['schoolClass', 'violations'])
            ->search($term)
            ->active()
            ->limit(10)
            ->get()
            ->map(fn($s) => [
                'id'         => $s->id,
                'nis'        => $s->nis,
                'full_name'  => $s->full_name,
                'class_name' => $s->schoolClass?->class_name,
                'follow_ups' => $s->violations->pluck('follow_up')->unique()->values(),
            ]);

        return response()->json($students);
    }

    protected function getRecommendations(Student $student): array
    {
        $kpi  = $student->kpi;
        $recs = [];

        if (!$kpi) return ['Lakukan evaluasi KPI siswa terlebih dahulu.'];

        if ($kpi->attendance_score > 20) {
            $recs[] = 'Tingkatkan kehadiran siswa melalui komunikasi dengan orang tua.';
        }
        if ($kpi->discipline_score > 20) {
            $recs[] = 'Berikan pembinaan disiplin dan konseling individu.';
        }
        if ($kpi->social_ethics_score > 20) {
            $recs[] = 'Ikutkan siswa dalam program pelatihan sosial dan empati.';
        }
        if ($kpi->aggression_score > 30) {
            $recs[] = 'Perlu penanganan khusus: konseling agresivitas dan mediasi.';
        }
        if ($kpi->integrity_score > 20) {
            $recs[] = 'Lakukan pembinaan karakter dan nilai integritas.';
        }
        if ($kpi->high_risk_score > 40) {
            $recs[] = 'Segera hubungi orang tua dan lakukan konferensi kasus.';
        }
        // No recommendations if student has good behavior

        return $recs;
    }
}
