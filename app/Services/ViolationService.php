<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Violation;
use App\Models\ActivityLog;
use App\Models\StatementLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViolationService
{
    public function __construct(
        protected KpiEngine $kpiEngine,
        protected EarlyWarningService $warningService,
        protected PdfGeneratorService $pdfService,
    ) {}

    /**
     * Full orchestration: save → PDF → KPI → warning → log → aggregate
     */
    public function processViolation(array $data, int $createdBy): array
    {
        return DB::transaction(function () use ($data, $createdBy) {
            // 1. Save violation
            $violation = Violation::create(array_merge($data, ['created_by' => $createdBy]));
            $student   = Student::find($data['student_id']);

            // 2. Generate statement letter PDF only if follow_up starts with 'Pembinaan'
            $letter = null;
            if ($violation->follow_up && str_starts_with($violation->follow_up, 'Pembinaan')) {
                $letter = $this->pdfService->generateStatementLetter($violation);
            }

            // 3. Recalculate KPI
            $kpi = $this->kpiEngine->recalculate($student);

            // 4. Check early warnings
            $alerts = $this->warningService->checkAndAlert($student);

            // 5. Activity log
            ActivityLog::create([
                'student_id' => $student->id,
                'activity'   => "Pelanggaran dicatat: {$violation->sub_category} ({$violation->severity})",
                'metadata'   => json_encode([
                    'violation_id' => $violation->id,
                    'kpi_after'    => $kpi->overall_score,
                    'alerts'       => count($alerts),
                ]),
                'created_at' => now(),
            ]);

            return [
                'violation' => $violation,
                'letter'    => $letter,
                'kpi'       => $kpi,
                'alerts'    => $alerts,
            ];
        });
    }

    public function deleteViolation(Violation $violation): array
    {
        return DB::transaction(function () use ($violation) {
            $student = $violation->student;

            if ($violation->statementLetter) {
                if ($violation->statementLetter->generated_pdf) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($violation->statementLetter->generated_pdf);
                }
                if ($violation->statementLetter->scanned_signed_file) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($violation->statementLetter->scanned_signed_file);
                }
                $violation->statementLetter()->delete();
            }

            ActivityLog::create([
                'student_id' => $student->id,
                'activity'   => "Pelanggaran dihapus: {$violation->sub_category} ({$violation->severity})",
                'metadata'   => json_encode(['violation_id' => $violation->id]),
                'created_at' => now(),
            ]);

            $violation->delete();

            $kpi = $this->kpiEngine->recalculate($student);
            $alerts = $this->warningService->checkAndAlert($student);

            return [
                'kpi' => $kpi,
                'alerts' => $alerts,
            ];
        });
    }

    public function getSubCategories(): array
    {
        return [
            'kehadiran' => [
                'telat'  => 'Terlambat (-5)',
                'bolos'  => 'Bolos (-15)',
                'alpa'   => 'Alpha/Tidak Hadir (-20)',
            ],
            'kedisiplinan' => [
                'atribut'       => 'Pelanggaran Atribut (-5)',
                'aturan'        => 'Melanggar Aturan Sekolah (-10)',
                'administratif' => 'Masalah Administratif (-10)',
            ],
            'etika_sosial' => [
                'menghina'       => 'Menghina/Merendahkan (-15)',
                'bullying_verbal'=> 'Bullying Verbal (-20)',
                'konflik_sosial' => 'Konflik Sosial (-25)',
            ],
            'agresivitas' => [
                'provokasi'  => 'Provokasi (-20)',
                'ancaman'    => 'Ancaman/Intimidasi (-25)',
            ],
            'integritas' => [
                'berbohong'        => 'Berbohong (-15)',
                'mengambil_barang' => 'Mengambil Barang Orang Lain (-30)',
            ],
            'risiko_tinggi' => [
                'berkelahi'         => 'Berkelahi (-35)',
                'rokok_vape'        => 'Merokok/Vape (-40)',
                'pelanggaran_berat' => 'Pelanggaran Berat Lainnya (-50)',
            ],
        ];
    }
}
