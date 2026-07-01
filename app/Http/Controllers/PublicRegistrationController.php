<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Services\KpiEngine;
use Illuminate\Http\Request;

class PublicRegistrationController extends Controller
{
    public function __construct(protected KpiEngine $kpiEngine) {}

    public function create()
    {
        $classes = SchoolClass::orderBy('grade_level')->orderBy('class_name')->get();
        return view('public.register', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis'         => 'required|string|max:20|unique:students',
            'full_name'   => 'required|string|max:255',
            'gender'      => 'required|in:L,P',
            'birth_place' => 'nullable|string|max:100',
            'birth_date'  => 'nullable|date',
            'class_id'    => 'required|exists:classes,id',
        ]);

        $validated['status'] = 'active';

        $student = Student::create($validated);

        // Initialize KPI
        $this->kpiEngine->recalculate($student);

        return redirect()->route('public.lookup')
            ->with('success', 'Pendaftaran berhasil! Silakan masukkan NIS Anda untuk mengecek status.');
    }
}
