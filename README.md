# EduGuard-KPI 🛡️📊
**Sistem Monitoring dan Evaluasi Kinerja Perilaku Siswa Berbasis KPI (Key Performance Indicator)**

EduGuard-KPI adalah aplikasi berbasis web yang dirancang khusus untuk memfasilitasi Guru Bimbingan dan Konseling (BK) dalam memantau, mencatat, dan menganalisis dinamika perilaku siswa secara terukur. Menggunakan mesin penilaian indeks prestasi perilaku (KPI), sistem ini secara otomatis mendeteksi penurunan nilai, memicu peringatan dini (early warning), serta menghasilkan surat pernyataan pelanggaran secara instan.

Aplikasi ini juga menyediakan portal publik yang memungkinkan wali murid atau pihak sekolah memantau status perkembangan perilaku siswa secara real-time hanya dengan menggunakan NIS atau NISN.

---

## 🚀 Fitur Utama

### 1. 📈 Dashboard & Analitik BK
* **Ringkasan Sekolah**: Statistik visual seputar rata-rata skor KPI sekolah, jumlah siswa di zona hijau (Baik), kuning (Perlu Perhatian), dan merah (Risiko Tinggi).
* **Tren Pelanggaran Bulanan**: Grafik analitik untuk memantau frekuensi pelanggaran sekolah dalam 6 bulan terakhir.
* **Early Warning Alerts**: Notifikasi instan bagi Guru BK untuk siswa yang terdeteksi berada di zona kritis.

### 2. 🗂️ Pemantauan Kelas & Siswa
* **Dashboard Kelas**: Informasi persentase status perilaku per kelas untuk membantu memetakan kelas-kelas yang memerlukan perhatian khusus.
* **Profil Siswa Komprehensif**: Data lengkap siswa beserta log aktivitas historis, daftar pelanggaran, dan chart perkembangan perilaku.

### 3. 📝 Pencatatan Pelanggaran Terpadu & Scoring Engine
Pencatatan pelanggaran dengan pembagian kategori terstruktur dan penalti skor otomatis terhadap dimensi KPI siswa:
* **Kehadiran**: Terlambat (-5), Bolos (-15), Alpa (-20).
* **Kedisiplinan**: Atribut (-5), Aturan (-10), Administratif (-10).
* **Etika Sosial**: Menghina (-15), Bullying Verbal (-20), Konflik Sosial (-25).
* **Agresivitas**: Provokasi (-20), Ancaman (-25), Berkelahi (-35).
* **Integritas**: Berbohong (-15), Mengambil Barang Orang Lain (-30).
* **Risiko Tinggi**: Rokok/Vape (-40), Pelanggaran Berat (-50).

### 4. ⚠️ Sistem Peringatan Dini (Early Warning System)
Mendeteksi secara otomatis 4 kondisi kritis siswa:
1. Skor KPI keseluruhan turun di bawah 60 (Zona Merah).
2. Memiliki $\ge$ 3 pelanggaran dalam waktu 30 hari terakhir.
3. Melakukan pelanggaran berulang yang sama sebanyak 3 kali atau lebih.
4. Terdeteksi melakukan pelanggaran dengan tingkat keparahan **Berat** dalam 7 hari terakhir.

### 5. 📄 Surat Pernyataan Digital & Upload Scan
* **Auto-Generated PDF**: Setiap pencatatan pelanggaran otomatis menghasilkan draf Surat Pernyataan resmi berformat PDF yang siap dicetak dan ditandatangani oleh siswa/orang tua.
* **Upload Bukti Fisik**: BK dapat mengunggah file hasil scan/foto surat pernyataan fisik yang telah ditandatangani untuk keperluan arsip digital.

### 6. 📊 Ekspor Data (Laporan)
* Unduh laporan data pelanggaran serta data kpi siswa ke dalam format **PDF** atau **Excel** secara dinamis berdasarkan filter tanggal, kategori, dan kelas.

### 7. 🔍 Portal Cek Siswa (Public Lookup)
* Halaman pencarian publik bebas login bagi orang tua/wali siswa. Cukup masukkan NIS atau NISN siswa untuk melihat status peringatan perilaku, tren pelanggaran, dan rekomendasi pembinaan mandiri secara transparan.

---

## 🛠️ Tech Stack & Kebutuhan Sistem

* **Framework**: [Laravel 12.x](https://laravel.com) & [Laravel Breeze](https://laravel.com/docs/12.x/breeze) (Auth)
* **PHP**: `>= 8.2`
* **Database**: SQLite (Default) / MySQL / PostgreSQL
* **Front-end**: HTML, Vanilla CSS & Tailwind CSS (dikelola via Vite)
* **Ekspor PDF**: `barryvdh/laravel-dompdf`
* **Ekspor Excel**: `maatwebsite/excel`

---

## 💾 Panduan Instalasi & Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan EduGuard-KPI di lingkungan lokal Anda:

### 1. Clone Repositori
```bash
git clone https://github.com/username/EduGuard-KPI.git
cd EduGuard-KPI
```

### 2. Pasang Dependensi (Composer & NPM)
```bash
# Dependensi PHP
composer install

# Dependensi Frontend
npm install
```

### 3. Konfigurasi Environment File
Salin file konfigurasi `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Secara default, Laravel menggunakan database **SQLite**. Jika Anda menggunakan SQLite, pastikan database kosong telah terbentuk di `database/database.sqlite` (atau sistem akan otomatis membuatnya ketika menjalankan migration).

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi & Database Seeding
Jalankan perintah ini untuk membangun tabel database beserta akun demo Guru BK, kelas (7-9), data siswa dummy, dan log contoh pelanggaran:
```bash
php artisan migrate --seed
```

### 6. Jalankan Link Storage
Agar file PDF surat pernyataan dan upload scan dapat diakses dengan baik oleh publik:
```bash
php artisan storage:link
```

### 7. Jalankan Server Lokal
Gunakan perintah custom composer dev untuk menjalankan server, antrean queue (untuk pemrosesan latar belakang), dan hot-reload asset Vite sekaligus:
```bash
composer dev
```
*Atau jalankan secara terpisah menggunakan dua terminal:*
* **Terminal 1**: `php artisan serve`
* **Terminal 2**: `npm run dev`

Aplikasi sekarang dapat diakses melalui browser di `http://localhost:8000` (atau port yang ditentukan).

---

## 🔑 Akun Demo (Default Credentials)

Gunakan akun berikut untuk masuk sebagai administrator/Guru BK:

* **Email**: `gurubk@eduguard.sch.id`
* **Password**: `password`

---

## 📂 Struktur Penting Aplikasi

* [DatabaseSeeder.php](file:///c:/Users/KIEL/eduguard-kpi/database/seeders/DatabaseSeeder.php) - Pengatur data dummy untuk kelas, siswa, dan sampel pelanggaran.
* [ViolationService.php](file:///c:/Users/KIEL/eduguard-kpi/app/Services/ViolationService.php) - Logika alur pencatatan pelanggaran, perhitungan KPI, early warning, dan generasi surat pernyataan.
* [KpiEngine.php](file:///c:/Users/KIEL/eduguard-kpi/app/Services/KpiEngine.php) - Mesin kalkulasi pengurangan skor KPI per pelanggaran dan penghitung skor tren perilaku.
* [EarlyWarningService.php](file:///c:/Users/KIEL/eduguard-kpi/app/Services/EarlyWarningService.php) - Logika pengecekan status kritis siswa sekolah.
* [PdfGeneratorService.php](file:///c:/Users/KIEL/eduguard-kpi/app/Services/PdfGeneratorService.php) - Pembuatan dokumen PDF Surat Pernyataan dan Laporan Siswa.
* [statement-letter.blade.php](file:///c:/Users/KIEL/eduguard-kpi/resources/views/pdf/statement-letter.blade.php) - Template HTML/Blade untuk cetak Surat Pernyataan resmi.
