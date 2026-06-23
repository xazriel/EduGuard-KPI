<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #1f2937; padding: 20px; }
    .header { text-align: center; border-bottom: 2px solid #1e3a5f; padding-bottom: 12px; margin-bottom: 16px; }
    .header h1 { font-size: 13px; font-weight: bold; color: #1e3a5f; text-transform: uppercase; }
    .header p { font-size: 9px; color: #64748b; margin-top: 2px; }
    h2 { font-size: 11px; text-align: center; margin-bottom: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
    table { width: 100%; border-collapse: collapse; font-size: 8.5px; }
    th { background: #1e3a5f; color: white; padding: 6px 8px; text-align: left; font-weight: bold; }
    td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
    tr:nth-child(even) { background: #f9fafb; }
    .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-weight: bold; font-size: 7.5px; }
    .badge-berat  { background: #fee2e2; color: #b91c1c; }
    .badge-sedang { background: #fef3c7; color: #b45309; }
    .badge-ringan { background: #fefce8; color: #a16207; }
    .footer { margin-top: 16px; text-align: right; font-size: 8px; color: #475569; }
    .meta { display: flex; justify-content: space-between; font-size: 8.5px; color: #64748b; margin-bottom: 12px; }
</style>
</head>
<body>
<div class="header">
    <h1>LAPORAN PELANGGARAN SISWA — Dashboard Pelanggaran Siswa</h1>
    <p>SMA/SMK Negeri — Sistem Monitoring Perilaku Siswa</p>
</div>
<h2>Laporan Data Pelanggaran</h2>
<div class="meta">
    <span>Tanggal Cetak: {{ $date }}</span>
    <span>Total: {{ count($violations) }} pelanggaran</span>
</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Siswa</th>
            <th>NIS</th>
            <th>Kelas</th>
            <th>Kategori</th>
            <th>Sub Kategori</th>
            <th>Keparahan</th>
            <th>Tindak Lanjut</th>
        </tr>
    </thead>
    <tbody>
        @forelse($violations as $i => $v)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $v->violation_date->format('d/m/Y') }}</td>
            <td>{{ $v->student?->full_name }}</td>
            <td>{{ $v->student?->nis }}</td>
            <td>{{ $v->student?->schoolClass?->class_name }}</td>
            <td>{{ $v->category_label }}</td>
            <td>{{ $v->sub_category_label }}</td>
            <td><span class="badge badge-{{ $v->severity }}">{{ strtoupper($v->severity) }}</span></td>
            <td>{{ Str::limit($v->follow_up ?? '-', 50) }}</td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:16px;color:#475569">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>
<div class="footer">Dashboard Pelanggaran Siswa — {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
