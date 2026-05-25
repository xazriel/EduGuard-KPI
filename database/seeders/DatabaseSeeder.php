<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use App\Models\Violation;
use App\Models\StudentKpi;
use App\Models\ActivityLog;
use App\Services\KpiEngine;
use App\Services\PdfGeneratorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Guru BK user
        User::create([
            'name'     => 'Guru BK Admin',
            'email'    => 'gurubk@eduguard.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'guru_bk',
        ]);

        // 2. Create school classes (7-1 to 7-9, 8-1 to 8-9, 9-1 to 9-9 for Junior High School/SMP)
        $classData = [];
        foreach (['7', '8', '9'] as $grade) {
            for ($i = 1; $i <= 9; $i++) {
                $classData[] = [
                    'class_name' => "{$grade}-{$i}",
                    'grade_level' => $grade,
                ];
            }
        }
        foreach ($classData as $c) {
            SchoolClass::create($c);
        }

        $classes = SchoolClass::all();
        $user    = User::first();
        $kpiEngine = app(KpiEngine::class);

        $firstNames = ['Ahmad','Budi','Citra','Dian','Eko','Fitri','Gilang','Hana','Indra','Joko',
                       'Karin','Luki','Maya','Nanda','Oki','Putri','Rizky','Sari','Tono','Umi',
                       'Vina','Wahyu','Xena','Yoga','Zahra','Bagas','Cindi','Dafa','Elsa','Farel'];
        $lastNames  = ['Pratama','Susanto','Wijaya','Santoso','Rahayu','Nugroho','Sari','Putri',
                       'Hidayat','Kurniawan','Lestari','Dewi','Saputra','Permata','Utama'];

        $studentIndex = 1;

        foreach ($classes as $class) {
            $studentCount = rand(25, 32);

            for ($i = 0; $i < $studentCount; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName  = $lastNames[array_rand($lastNames)];
                $gender    = rand(0,1) ? 'L' : 'P';
                $nis  = str_pad($studentIndex, 8, '0', STR_PAD_LEFT);
                $nisn = '00' . str_pad($studentIndex, 8, '0', STR_PAD_LEFT);

                $student = Student::create([
                    'nis'         => $nis,
                    'nisn'        => $nisn,
                    'full_name'   => $firstName . ' ' . $lastName,
                    'gender'      => $gender,
                    'birth_place' => 'Jakarta',
                    'birth_date'  => Carbon::now()->subYears(rand(14,18))->subDays(rand(0,365)),
                    'parent_name' => 'Orang Tua ' . $lastName,
                    'address'     => 'Jl. Pendidikan No. ' . rand(1,100) . ', Jakarta',
                    'class_id'    => $class->id,
                    'status'      => 'active',
                ]);

                // Seed random violations (some students have more than others)
                $numViolations = $this->getRandomViolationCount();
                $categories = $this->getViolationData();

                for ($v = 0; $v < $numViolations; $v++) {
                    $catKey  = array_rand($categories);
                    $cat     = $categories[$catKey];
                    $subKey  = array_rand($cat['subs']);
                    $subData = $cat['subs'][$subKey];

                    Violation::create([
                        'student_id'     => $student->id,
                        'category'       => $catKey,
                        'sub_category'   => $subKey,
                        'description'    => $subData['desc'],
                        'severity'       => $subData['severity'],
                        'violation_date' => Carbon::now()->subDays(rand(0, 180)),
                        'follow_up'      => 'Siswa dipanggil dan diberi pembinaan oleh Guru BK.',
                        'created_by'     => $user->id,
                    ]);
                }

                // Calculate KPI
                $kpiEngine->recalculate($student);

                $studentIndex++;
            }
        }
    }

    protected function getRandomViolationCount(): int
    {
        $rand = rand(1, 100);
        if ($rand <= 40) return 0;       // 40% no violations
        if ($rand <= 65) return rand(1,2); // 25% 1-2 violations
        if ($rand <= 80) return rand(3,5); // 15% 3-5 violations
        if ($rand <= 92) return rand(6,9); // 12% 6-9 violations
        return rand(10, 15);               // 8% heavy violators
    }

    protected function getViolationData(): array
    {
        return [
            'kehadiran' => ['subs' => [
                'telat'  => ['desc' => 'Siswa terlambat masuk sekolah.', 'severity' => 'ringan'],
                'bolos'  => ['desc' => 'Siswa tidak hadir tanpa keterangan (bolos).', 'severity' => 'sedang'],
                'alpa'   => ['desc' => 'Siswa alpha tidak ada keterangan.', 'severity' => 'sedang'],
            ]],
            'kedisiplinan' => ['subs' => [
                'atribut'       => ['desc' => 'Tidak memakai seragam lengkap.', 'severity' => 'ringan'],
                'aturan'        => ['desc' => 'Melanggar aturan sekolah.', 'severity' => 'sedang'],
                'administratif' => ['desc' => 'Tidak mengumpulkan tugas administratif.', 'severity' => 'ringan'],
            ]],
            'etika_sosial' => ['subs' => [
                'menghina'       => ['desc' => 'Menghina teman sekelas.', 'severity' => 'sedang'],
                'bullying_verbal'=> ['desc' => 'Melakukan bullying verbal terhadap teman.', 'severity' => 'berat'],
                'konflik_sosial' => ['desc' => 'Terlibat konflik dengan siswa lain.', 'severity' => 'sedang'],
            ]],
            'agresivitas' => ['subs' => [
                'provokasi'  => ['desc' => 'Memprovokasi teman hingga terjadi keributan.', 'severity' => 'sedang'],
                'ancaman'    => ['desc' => 'Mengancam siswa lain.', 'severity' => 'berat'],
                'berkelahi'  => ['desc' => 'Berkelahi dengan siswa lain di lingkungan sekolah.', 'severity' => 'berat'],
            ]],
            'integritas' => ['subs' => [
                'berbohong'        => ['desc' => 'Berbohong kepada guru.', 'severity' => 'sedang'],
                'mengambil_barang' => ['desc' => 'Mengambil barang milik teman.', 'severity' => 'berat'],
            ]],
            'risiko_tinggi' => ['subs' => [
                'rokok_vape'        => ['desc' => 'Kedapatan merokok/menggunakan vape di sekolah.', 'severity' => 'berat'],
                'pelanggaran_berat' => ['desc' => 'Pelanggaran berat lainnya.', 'severity' => 'berat'],
            ]],
        ];
    }
}
