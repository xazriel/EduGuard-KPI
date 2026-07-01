<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use App\Models\Violation;
use App\Models\StudentKpi;
use App\Models\ActivityLog;
use App\Models\StatementLetter;
use App\Services\KpiEngine;
use App\Services\PdfGeneratorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset database tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        StudentKpi::truncate();
        StatementLetter::truncate();
        Violation::truncate();
        Student::truncate();
        SchoolClass::truncate();
        ActivityLog::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create BK Admin Users
        $user = User::create([
            'name'     => 'Guru BK Admin',
            'email'    => 'gurubk@pelanggaransiswa.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'guru_bk',
        ]);

        User::create([
            'name'     => 'Guru BK',
            'email'    => 'gurubk@a.com',
            'password' => Hash::make('password'),
            'role'     => 'guru_bk',
        ]);

        // 3. Define classes from dataset
        $classesData = [
            '7.1' => '7',
            '7.2' => '7',
            '7.3' => '7',
            '7.4' => '7',
            '7.6' => '7',
            '7.8' => '7',
            '8.2' => '8',
            '8.4' => '8',
            '8.5' => '8',
            '8.6' => '8',
            '8.7' => '8',
            '8.8' => '8',
            '8.9' => '8',
            '9.2' => '9',
            '9.3' => '9',
            '9.4' => '9',
            '9.6' => '9',
            '9.9' => '9',
            'Tanpa Kelas' => 'Lainnya',
        ];

        $classesMap = [];
        foreach ($classesData as $name => $grade) {
            $class = SchoolClass::create([
                'class_name' => $name,
                'grade_level' => $grade,
            ]);
            $classesMap[$name] = $class->id;
        }

        // 4. Define students from dataset
        $studentsData = [
            ['0123456001', 'Ezio Pramana Putra', '7.1', 'L'],
            ['0123456002', 'M. Fadli Yudithia', '7.2', 'L'],
            ['0123456003', 'Acfalah Zuhwan', '7.2', 'L'],
            ['0123456004', 'Muhammad Fathir', '8.9', 'L'],
            ['0123456005', 'Tengku Muhammad G', '8.8', 'L'],
            ['0123456006', 'Rasya Putra Mahendra', '7.3', 'L'],
            ['0123456007', 'Ragil Nuril Rajabi', '8.9', 'L'],
            ['0123456008', 'M. Syahiq Albani', '9.6', 'L'],
            ['0123456009', 'Rico Maritim', '7.2', 'L'],
            ['0123456010', 'Nasywa Aulia Zahra', '7.2', 'P'],
            ['0123456011', 'Julio Andika Syaban', '8.6', 'L'],
            ['0123456012', 'Gusti Zahir Rafa', '8.7', 'L'],
            ['0123456013', 'Windra Dian Saputra', '8.6', 'L'],
            ['0123456014', 'Hervahd Hooh', '8.9', 'L'],
            ['0123456015', 'Jani Pambengkas Afreen', '8.2', 'P'],
            ['0123456016', 'Khansa Nisrina', '9.9', 'P'],
            ['0123456017', 'Naura Aurilia', '9.6', 'P'],
            ['0123456018', 'M. Jafar Raehan', '7.4', 'L'],
            ['0123456019', 'Nasyra Shalima', '8.4', 'P'],
            ['0123456020', 'Azkana Kayyisa', '7.2', 'P'],
            ['0123456021', 'Amanda Dwitri', '7.1', 'P'],
            ['0123456022', 'Tegar Andhika Pratama', null, 'L'],
            ['0123456023', 'M. Dafa Syahreza', null, 'L'],
            ['0123456024', 'Melati Regina Putri', '7.6', 'P'],
            ['0123456025', 'Raissa Reinara', '9.2', 'P'],
            ['0123456026', 'Raffa Putra Ramadhan', '7.8', 'L'],
            ['0123456027', 'Arkana Ramadani', '7.8', 'L'],
            ['0123456028', 'Risya Ardiansyah', '7.2', 'L'],
            ['0123456029', 'Brayen Alfarez S.H', '7.1', 'L'],
            ['0123456030', 'M. Fabian Akbar', '7.1', 'L'],
            ['0123456031', 'Ahmad Rasyid', '7.4', 'L'],
            ['0123456032', 'Jesica Mawar Kuroni', '7.1', 'P'],
            ['0123456033', 'Akhyat M. Syatha', '9.3', 'L'],
            ['0123456034', 'Keysa Andrea', '9.2', 'P'],
            ['0123456035', 'Gusti Efry', '7.6', 'L'],
            ['0123456036', 'Farhan Adibussolah H', '7.6', 'L'],
            ['0123456037', 'Hildan Al Fatah', '7.6', 'L'],
            ['0123456038', 'M. Yudha Saputra', '7.6', 'L'],
            ['0123456039', 'Rivaldo Chaerul R.K', '7.1', 'L'],
            ['0123456040', 'Darren Arjein Geraldo', '8.5', 'L'],
            ['0123456041', 'Faviancarlen Juan R', '7.8', 'L'],
            ['0123456042', 'M. Rehadiansyah P.A', '7.8', 'L'],
            ['0123456043', 'Fitria Handayani', '7.8', 'P'],
            ['0123456044', 'Haikal Maulana Yusuf', '7.8', 'L'],
            ['0123456045', 'Aghnaya Hayuningtyas', '7.8', 'P'],
            ['0123456046', 'Syerifa Sheba A', '7.8', 'P'],
            ['0123456047', 'Mochamad Abrar', '7.8', 'L'],
            ['0123456048', 'Kagiesha Alyanda P', '7.8', 'P'],
            ['0123456049', 'Acmhad Sakha A.W', '7.1', 'L'],
        ];

        $studentsMap = [];
        foreach ($studentsData as [$nis, $name, $class, $gender]) {
            $classId = $classesMap[$class ?? 'Tanpa Kelas'];
            $student = Student::create([
                'nis'         => $nis,
                'full_name'   => $name,
                'gender'      => $gender,
                'birth_place' => 'Jakarta',
                'birth_date'  => Carbon::now()->subYears(14),
                'class_id'    => $classId,
                'status'      => 'active',
            ]);
            $studentsMap[$nis] = $student->id;
        }

        // 5. Define violations from dataset
        $violationsData = [
            ['2026-01-15', '0123456001', 'Etika Sosial', 'Verbal Negatif', 'Mengkatai teman', 'Ringan', 'Dilaporkan teman sekelas', 'Menerima Sanksi', 1],
            ['2026-01-15', '0123456002', 'Risiko Tinggi', 'Kekerasan Fisik', 'Melempar botol ke muka teman', 'Sedang', 'Terjadi saat jam istirahat', 'Menerima Sanksi', 1],
            ['2026-01-15', '0123456003', 'Etika Sosial', 'Penghinaan', 'Menghina orang dengan sebutan tidak pantas', 'Sedang', 'Korban melapor ke wali kelas', 'Menerima Sanksi', 1],
            ['2025-11-12', '0123456004', 'Integritas', 'Pengambilan Barang', 'Menyuruh orang ambil paket MBG milik orang lain', 'Sedang', 'CCTV kantin menunjukkan kejadian', 'Menerima Sanksi', 1],
            ['2025-03-21', '0123456005', 'Integritas', 'Pengambilan Barang', 'Mengambil paket MBG milik orang lain', 'Sedang', 'Saksi teman sekelas', 'Menerima Sanksi', 1],
            ['2025-03-26', '0123456006', 'Etika Sosial', 'Bullying', 'Membully peserta didik ABK', 'Berat', 'Dilaporkan guru pendamping ABK', 'Menerima Sanksi', 1, 'Korban ABK perlu pendampingan khusus'],
            ['2025-03-21', '0123456007', 'Integritas', 'Pengambilan Barang', 'Mengambil paket MBG milik orang lain', 'Sedang', 'Laporan kantin sekolah', 'Menerima Sanksi', 1],
            ['2024-01-20', '0123456008', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 4 hari berturut-turut', 'Sedang', 'Orang tua tidak memberi keterangan', 'Menerima Sanksi', 1],
            ['2024-02-24', '0123456008', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 9 hari berturut-turut', 'Berat', 'Kunjungan rumah oleh BK', 'Menerima Sanksi', 1],
            ['2024-03-30', '0123456008', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 14 hari berturut-turut', 'Berat', 'Koordinasi dengan orang tua', 'Menerima Sanksi', 1],
            ['2024-05-25', '0123456008', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 30 hari berturut-turut', 'Kritis', 'Rapat komite sekolah', 'Mengundurkan Diri', 1, '⚠️ PRIORITAS PEMBINAAN'],
            ['2025-06-05', '0123456009', 'Kehadiran', 'Bolos', 'Bolos sekolah sebanyak 6 kali', 'Sedang', 'Tidak masuk tanpa keterangan', 'Menerima Sanksi', 1],
            ['2025-06-19', '0123456009', 'Risiko Tinggi', 'Merokok', 'Merokok di kamar mandi sekolah', 'Berat', 'Tertangkap guru piket', 'Menerima Sanksi', 1],
            ['2025-06-21', '0123456009', 'Kehadiran', 'Bolos', 'Bolos saat pelajaran Bahasa Inggris', 'Sedang', 'Laporan guru mapel', 'Pemutusan KJP Plus', 1, '⚠️ PRIORITAS PEMBINAAN'],
            ['2026-02-21', '0123456010', 'Integritas', 'Fitnah/Hoaks', 'Memfitnah guru dengan menyebarkan foto tidak pantas', 'Berat', 'Foto tersebar di grup kelas', 'Menerima Sanksi', 1, 'Koordinasi dengan orang tua'],
            ['2025-06-19', '0123456011', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 16 kali', 'Berat', 'Surat teguran dikirim ke orang tua', 'Menerima Sanksi', 1],
            ['2026-02-21', '0123456011', 'Risiko Tinggi', 'Perkelahian', 'Berkelahi dengan teman satu sekolah', 'Berat', 'Terjadi di koridor sekolah', 'Menerima Sanksi', 1],
            ['2026-02-22', '0123456011', 'Kehadiran', 'Absensi', 'Sering tidak masuk sekolah', 'Sedang', 'Pola berulang sejak kelas 7', 'Menerima Sanksi', 1, '⚠️ PRIORITAS PEMBINAAN'],
            ['2026-02-11', '0123456012', 'Risiko Tinggi', 'Perkelahian', 'Berkelahi dengan teman di kelas dan toilet', 'Berat', 'Laporan guru kelas', 'Menerima Sanksi', 1],
            ['2026-02-21', '0123456012', 'Agresivitas', 'Provokasi', 'Mengajak teman menyaksikan duel, berkelahi lagi', 'Kritis', 'Terjadi di taman sekolah', 'Pemutusan KJP Plus', 1, '⚠️ PRIORITAS PEMBINAAN'],
            ['2026-02-21', '0123456013', 'Agresivitas', 'Provokasi', 'Mengajak/memprovokasi teman untuk berkelahi', 'Sedang', 'Saksi: beberapa siswa', 'Menerima Sanksi', 1],
            ['2025-04-15', '0123456014', 'Etika Sosial', 'Bullying', 'Membully peserta didik ABK', 'Berat', 'Laporan guru pendamping ABK', 'Menerima Sanksi', 1, 'Koordinasi dinas'],
            ['2025-04-21', '0123456015', 'Kedisiplinan', 'Atribut', 'Memakai gelang ke sekolah', 'Ringan', 'Ditemukan saat apel pagi', 'Menerima Sanksi', 1],
            ['2025-04-15', '0123456016', 'Kedisiplinan', 'Atribut', 'Membawa make up ke sekolah', 'Ringan', 'Ditemukan saat razia tas', 'Menerima Sanksi', 1],
            ['2025-04-15', '0123456017', 'Kedisiplinan', 'Atribut', 'Membawa make up ke sekolah', 'Ringan', 'Ditemukan saat razia tas', 'Menerima Sanksi', 1],
            ['2024-07-13', '0123456018', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 5 hari berturut-turut', 'Sedang', 'Orang tua dihubungi', 'Menerima Sanksi', 1],
            ['2024-07-13', '0123456018', 'Kedisiplinan', 'Tata Tertib', 'Tidak menaati peraturan sekolah', 'Ringan', 'Laporan wali kelas', 'Menerima Sanksi', 1],
            ['2024-08-09', '0123456018', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 11 hari berturut-turut', 'Berat', 'Home visit BK', 'Menerima Sanksi', 1],
            ['2024-09-04', '0123456018', 'Kehadiran', 'Absensi', 'Masalah absensi kehadiran', 'Kritis', 'Rapat orang tua-sekolah', 'Pemutusan KJP Plus', 1, '⚠️ PRIORITAS PEMBINAAN'],
            ['2024-09-13', '0123456018', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 30 hari berturut-turut', 'Kritis', 'Tidak ada respons dari keluarga', 'Mengundurkan Diri', 1, '⚠️ DROPOUT RISK'],
            ['2024-01-28', '0123456019', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 6 hari berturut-turut', 'Sedang', 'Surat izin tidak ada', 'Menerima Sanksi', 1],
            ['2024-03-18', '0123456019', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 8 hari berturut-turut', 'Sedang', 'Kunjungan rumah', 'Menerima Sanksi', 1],
            ['2024-05-21', '0123456019', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 21 hari berturut-turut', 'Berat', 'Koordinasi BK-Wali kelas', 'Menerima Sanksi', 1],
            ['2024-10-24', '0123456019', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 30 hari berturut-turut', 'Kritis', 'Rapat komite', 'Mengundurkan Diri', 1, '⚠️ DROPOUT'],
            ['2025-05-10', '0123456020', 'Kedisiplinan', 'Atribut', 'Menggunakan gelang ke sekolah', 'Ringan', 'Razia pagi', 'Menerima Sanksi', 1],
            ['2025-05-06', '0123456021', 'Kedisiplinan', 'Atribut', 'Memakai gelang ke sekolah', 'Ringan', 'Razia pagi', 'Menerima Sanksi', 1],
            ['2024-08-02', '0123456022', 'Kedisiplinan', 'Tata Tertib', 'Melanggar tata tertib sekolah', 'Sedang', 'Laporan guru', 'Pemutusan KJP Plus', 1, 'Data kelas tidak tersedia'],
            ['2024-08-16', '0123456023', 'Integritas', 'Pengambilan Uang', 'Mengambil uang teman', 'Berat', 'Laporan korban', 'Menerima Sanksi', 1],
            ['2024-08-17', '0123456023', 'Kehadiran', 'Bolos', 'Bolos sekolah', 'Sedang', 'Tidak ada keterangan', 'Menerima Sanksi', 1],
            ['2026-02-27', '0123456024', 'Integritas', 'Penipuan', 'Mengelabui guru BK dengan alasan palsu untuk pulang', 'Sedang', 'Orang tua tidak memberikan izin', 'Menerima Sanksi', 1],
            ['2026-02-27', '0123456025', 'Integritas', 'Pengambilan Barang', 'Mengambil HP tanpa izin dari petugas perpustakaan', 'Sedang', 'Laporan petugas perpustakaan', 'Menerima Sanksi', 1],
            ['2025-12-10', '0123456026', 'Risiko Tinggi', 'Kekerasan Fisik', 'Menarik rambut teman (Yuliana)', 'Sedang', 'Laporan korban langsung ke BK', 'Menerima Sanksi', 1, 'Kasus bullying kolektif kelas 7.8'],
            ['2026-01-06', '0123456026', 'Risiko Tinggi', 'Perkelahian', 'Berantem dengan Arkana', 'Berat', 'Terjadi di halaman sekolah', 'Menerima Sanksi', 1, '⚠️ PRIORITAS PEMBINAAN'],
            ['2025-12-10', '0123456027', 'Etika Sosial', 'Bullying', 'Ikut-ikutan membully Yuliana', 'Sedang', 'Kasus kolektif kelas 7.8', 'Menerima Sanksi', 1],
            ['2026-01-06', '0123456027', 'Etika Sosial', 'Verbal Negatif', 'Mengatai orang tua Raffa', 'Sedang', 'Laporan teman sekelas', 'Menerima Sanksi', 1],
            ['2026-02-16', '0123456028', 'Risiko Tinggi', 'Vape/Rokok', 'Menghisap vape di rumah teman sambil dibuat video', 'Kritis', 'Video tersebar di media sosial', 'Menerima Sanksi', 1, '⚠️ PRIORITAS PEMBINAAN'],
            ['2026-02-18', '0123456029', 'Risiko Tinggi', 'Privasi/Asusila', 'Memfoto siswa lain di kamar mandi', 'Kritis', 'Korban melaporkan ke guru', 'Menerima Sanksi', 1, '⚠️ KRITIS — koordinasi orang tua'],
            ['2026-02-20', '0123456030', 'Risiko Tinggi', 'Privasi/Asusila', 'Membantu memanjat untuk memfoto siswa di kamar mandi', 'Kritis', 'Kasus sama dengan Brayen', 'Menerima Sanksi', 1, '⚠️ KRITIS'],
            ['2026-03-07', '0123456031', 'Kehadiran', 'Absensi', 'Jarang masuk sekolah', 'Sedang', 'Laporan wali kelas', 'Menerima Sanksi', 1],
            ['2025-06-19', '0123456032', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 9 kali tanpa keterangan', 'Sedang', 'Orang tua dihubungi', 'Menerima Sanksi', 1],
            ['2025-06-20', '0123456032', 'Etika Sosial', 'Penolakan Tugas', 'Tidak mau terlibat proyek kelompok', 'Ringan', 'Laporan ketua kelompok', 'Menerima Sanksi', 1],
            ['2025-06-18', '0123456033', 'Etika Sosial', 'Bullying', 'Mengganggu teman yang sedang makan (bullying)', 'Sedang', 'Laporan korban', 'Menerima Sanksi', 1],
            ['2025-06-14', '0123456034', 'Kedisiplinan', 'Tata Tertib', 'Melanggar tata tertib sekolah', 'Ringan', 'Laporan guru', 'Menerima Sanksi', 1],
            ['2025-06-20', '0123456034', 'Risiko Tinggi', 'Merokok', 'Merokok di luar sekolah', 'Berat', 'Laporan masyarakat ke sekolah', 'Menerima Sanksi', 1],
            ['2025-06-20', '0123456034', 'Kedisiplinan', 'Tata Tertib', 'Pulang bermain hingga larut malam/pagi', 'Berat', 'Laporan orang tua tetangga', 'Menerima Sanksi', 1, '⚠️ PRIORITAS PEMBINAAN'],
            ['2025-06-19', '0123456035', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 16 kali (Juni–Oktober)', 'Berat', 'Rekap absensi wali kelas', 'Menerima Sanksi', 1],
            ['2025-06-19', '0123456036', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 24 kali (Juni–Oktober)', 'Berat', 'Rekap absensi wali kelas', 'Menerima Sanksi', 1],
            ['2025-06-19', '0123456037', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 16 kali (Juni–Oktober)', 'Berat', 'Rekap absensi wali kelas', 'Menerima Sanksi', 1],
            ['2025-06-19', '0123456038', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 12 kali (Juni–Oktober)', 'Sedang', 'Rekap absensi wali kelas', 'Menerima Sanksi', 1],
            ['2025-06-25', '0123456039', 'Integritas', 'Pengambilan Uang', 'Mengambil uang milik teman sekelas', 'Berat', 'Laporan korban dan saksi', 'Menerima Sanksi', 1],
            ['2024-02-18', '0123456040', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 11 hari berturut-turut', 'Berat', 'Surat teguran pertama', 'Menerima Sanksi', 1],
            ['2024-04-20', '0123456040', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 18 hari berturut-turut', 'Berat', 'Home visit', 'Menerima Sanksi', 1],
            ['2024-08-10', '0123456040', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 20 hari berturut-turut', 'Berat', 'Rapat orang tua', 'Menerima Sanksi', 1],
            ['2024-10-03', '0123456040', 'Kehadiran', 'Absensi', 'Tidak masuk sekolah 49 kali (Feb–Nov)', 'Kritis', 'Tidak ada respons keluarga', 'Mengundurkan Diri', 1, '⚠️ DROPOUT'],
            ['2025-12-10', '0123456041', 'Etika Sosial', 'Bullying', 'Mengatai dan membully teman', 'Sedang', 'Kasus kolektif Yuliana kelas 7.8', 'Menerima Sanksi', 1],
            ['2025-12-10', '0123456042', 'Etika Sosial', 'Bullying', 'Mengejek korban Yuliana (civic turbo)', 'Sedang', 'Kasus kolektif Yuliana kelas 7.8', 'Menerima Sanksi', 1],
            ['2025-12-10', '0123456043', 'Etika Sosial', 'Bullying', 'Ikut-ikutan mengejek Yuliana', 'Ringan', 'Kasus kolektif Yuliana kelas 7.8', 'Menerima Sanksi', 1],
            ['2025-12-10', '0123456044', 'Etika Sosial', 'Bullying', 'Mengejek Yuliana', 'Ringan', 'Kasus kolektif Yuliana kelas 7.8', 'Menerima Sanksi', 1],
            ['2025-12-10', '0123456045', 'Etika Sosial', 'Cyberbullying', 'Menyebarkan konten merendahkan Yuliana ke teman', 'Sedang', 'Kasus kolektif Yuliana kelas 7.8', 'Menerima Sanksi', 1],
            ['2025-12-10', '0123456046', 'Etika Sosial', 'Cyberbullying', 'Mengirim konten Yuliana ke Abrar', 'Ringan', 'Kasus kolektif Yuliana kelas 7.8', 'Menerima Sanksi', 1],
            ['2025-12-10', '0123456047', 'Etika Sosial', 'Bullying', 'Meledeki korban Yuliana dengan sengaja', 'Sedang', 'Kasus kolektif Yuliana kelas 7.8', 'Menerima Sanksi', 1],
            ['2025-12-10', '0123456048', 'Etika Sosial', 'Cyberbullying', 'Menyebarkan konten Yuliana ke grup', 'Sedang', 'Kasus kolektif Yuliana kelas 7.8', 'Menerima Sanksi', 1],
            ['2026-03-09', '0123456049', 'Agresivitas', 'Provokasi', 'Memancing keributan kelas 7.6 vs 7.1', 'Sedang', 'Laporan wali kelas 7.6', 'Menerima Sanksi', 1],
        ];

        // Mappings helper
        $categoryMap = [
            'Etika Sosial' => 'etika_sosial',
            'Agresivitas' => 'agresivitas',
            'Integritas' => 'integritas',
            'Kehadiran' => 'kehadiran',
            'Risiko Tinggi' => 'risiko_tinggi',
            'Kedisiplinan' => 'kedisiplinan',
        ];

        $subCategoryMap = [
            'Verbal Negatif'     => 'bullying_verbal',
            'Kekerasan Fisik'    => 'berkelahi',
            'Penghinaan'         => 'menghina',
            'Pengambilan Barang' => 'mengambil_barang',
            'Bullying'           => 'bullying_verbal',
            'Absensi'            => 'alpa',
            'Bolos'              => 'bolos',
            'Merokok'            => 'rokok_vape',
            'Fitnah/Hoaks'       => 'berbohong',
            'Perkelahian'        => 'berkelahi',
            'Provokasi'          => 'provokasi',
            'Atribut'            => 'atribut',
            'Tata Tertib'        => 'aturan',
            'Pengambilan Uang'   => 'mengambil_barang',
            'Penipuan'           => 'berbohong',
            'Vape/Rokok'         => 'rokok_vape',
            'Privasi/Asusila'    => 'pelanggaran_berat',
            'Penolakan Tugas'    => 'konflik_sosial',
            'Cyberbullying'      => 'bullying_verbal',
        ];

        $severityMap = [
            'Ringan' => 'ringan',
            'Sedang' => 'sedang',
            'Berat' => 'berat',
            'Kritis' => 'berat',
        ];

        $kpiEngine = app(KpiEngine::class);
        $pdfService = app(PdfGeneratorService::class);
        $studentViolationCounts = [];

        foreach ($violationsData as $v) {
            $date = Carbon::parse($v[0]);
            $nis = $v[1];
            $catLabel = $v[2];
            $subCat = $v[3];
            $detail = $v[4];
            $severityLabel = $v[5];
            $kronologi = $v[6];
            $tindakLanjut = $v[7];
            $suratDigital = $v[8];
            $catatanBk = isset($v[9]) ? $v[9] : null;

            $studentId = $studentsMap[$nis] ?? null;

            if ($studentId) {
                // Construct complete description to preserve kronologi and catatanBk
                $desc = $detail;
                if ($kronologi) {
                    $desc .= "\nKronologi: " . $kronologi;
                }
                if ($catatanBk) {
                    $desc .= "\nCatatan BK: " . $catatanBk;
                }

                // Determine follow_up dynamically based on sequence of violations
                if (!isset($studentViolationCounts[$studentId])) {
                    $studentViolationCounts[$studentId] = 0;
                }
                $studentViolationCounts[$studentId]++;
                $sequence = $studentViolationCounts[$studentId];

                if ($sequence === 1) {
                    $followUpValue = 'Pembinaan 1';
                } elseif ($sequence === 2) {
                    $followUpValue = 'Pembinaan 2';
                } elseif ($sequence === 3) {
                    $followUpValue = 'Pembinaan 3';
                } else {
                    $followUpValue = $tindakLanjut;
                }

                // Create Violation
                $violation = Violation::create([
                    'student_id' => $studentId,
                    'category' => $categoryMap[$catLabel] ?? 'kedisiplinan',
                    'sub_category' => $subCategoryMap[$subCat] ?? $subCat,
                    'description' => $desc,
                    'severity' => $severityMap[$severityLabel] ?? 'ringan',
                    'violation_date' => $date,
                    'follow_up' => $followUpValue,
                    'created_by' => $user->id,
                ]);

                // Create StatementLetter if requested and follow_up starts with 'Pembinaan'
                if ($suratDigital && str_starts_with($followUpValue ?? '', 'Pembinaan')) {
                    $pdfService->generateStatementLetter($violation);
                }
            }
        }

        // 6. Recalculate KPIs for all students (even those with 0 violations get KPI entry)
        $allStudents = Student::all();
        foreach ($allStudents as $student) {
            $kpiEngine->recalculate($student);
        }
    }
}
