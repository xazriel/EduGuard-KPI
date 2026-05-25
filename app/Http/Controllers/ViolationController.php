<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Violation;
use App\Services\ViolationService;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function __construct(protected ViolationService $violationService) {}

    public function index(Request $request)
    {
        $query = Violation::with(['student.schoolClass', 'createdBy']);

        if ($request->filled('student_id'))   $query->where('student_id', $request->student_id);
        if ($request->filled('category'))     $query->where('category', $request->category);
        if ($request->filled('severity'))     $query->where('severity', $request->severity);
        if ($request->filled('class_id')) {
            $query->whereHas('student', fn($q) => $q->where('class_id', $request->class_id));
        }
        if ($request->filled('date_from'))    $query->where('violation_date', '>=', $request->date_from);
        if ($request->filled('date_to'))      $query->where('violation_date', '<=', $request->date_to);

        $violations  = $query->orderByDesc('violation_date')->paginate(20)->withQueryString();
        $classes     = SchoolClass::orderBy('class_name')->get();
        $subCategories = $this->violationService->getSubCategories();

        return view('violations.index', compact('violations', 'classes', 'subCategories'));
    }

    public function create()
    {
        $classes       = SchoolClass::orderBy('grade_level')->get();
        $subCategories = $this->violationService->getSubCategories();
        return view('violations.create', compact('classes', 'subCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'     => 'required|exists:students,id',
            'category'       => 'required|string',
            'sub_category'   => 'required|string',
            'description'    => 'nullable|string|max:1000',
            'severity'       => 'required|in:ringan,sedang,berat',
            'violation_date' => 'required|date|before_or_equal:today',
            'follow_up'      => 'nullable|string|max:1000',
        ]);

        $result = $this->violationService->processViolation($validated, auth()->id());

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'violation'  => $result['violation'],
                'kpi'        => $result['kpi'],
                'alerts'     => $result['alerts'],
                'letter_url' => $result['letter']->generated_pdf
                    ? asset('storage/' . $result['letter']->generated_pdf)
                    : null,
            ]);
        }

        $alertCount = count($result['alerts']);
        $msg = 'Pelanggaran berhasil dicatat dan surat pernyataan telah dibuat.';
        if ($alertCount > 0) {
            $msg .= " ⚠ {$alertCount} peringatan dini aktif.";
        }

        return redirect()->route('violations.show', $result['violation'])
            ->with('success', $msg)
            ->with('alerts', $result['alerts']);
    }

    public function show(Violation $violation)
    {
        $violation->load(['student.schoolClass', 'statementLetter', 'createdBy']);
        return view('violations.show', compact('violation'));
    }

    public function uploadScan(Request $request, Violation $violation)
    {
        $request->validate([
            'scanned_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $path = $request->file('scanned_file')->store('scans', 'public');

        $violation->statementLetter()->updateOrCreate(
            ['violation_id' => $violation->id],
            ['scanned_signed_file' => $path]
        );

        return back()->with('success', 'Scan surat berhasil diunggah.');
    }
}
