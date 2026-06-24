<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;

$students = Student::with(['kpi', 'violations'])->get();

foreach ($students as $student) {
    if (!$student->kpi) continue;
    $kpi = $student->kpi;
    if ($kpi->aggression_score == 0 || $kpi->integrity_score == 0) {
        echo "Siswa: {$student->full_name} (ID: {$student->id})\n";
        echo "  Aggression score: {$kpi->aggression_score}\n";
        echo "  Integrity score: {$kpi->integrity_score}\n";
        echo "  Violations count: " . $student->violations->count() . "\n";
        foreach ($student->violations as $v) {
            echo "    - [{$v->violation_date->format('Y-m-d')}] Category: {$v->category}, Subcategory: {$v->sub_category_label}, Severity: {$v->severity}\n";
        }
        echo "--------------------------------------------------\n";
    }
}
