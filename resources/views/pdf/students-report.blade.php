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
    td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
    tr:nth-child(even) { background: #f9fafb; }
    .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-weight: bold; font-size: 7.5px; }
    .badge-green  { background: #dcfce7; color: #15803d; }
    .badge-yellow { background: #fef3c7; color: #b45309; }
    .badge-red    { background: #fee2e2; color: #b91c1c; }
    .footer { margin-top: 16px; text-align: right; font-size: 8px; color: #475569; }
    .meta { display: flex; justify-content: space-between; font-size: 8.5px; color: #64748b; margin-bottom: 12px; }
</style>
</head>
<body>
<div class="header">
    <h1>LAPORAN KPI SISWA — EduGuard KPI</h1>
    <p>SMA/SMK Negeri — Sistem Monitoring Perilaku Siswa</p>
</div>
<h2>Laporan Data & Nilai KPI Siswa</h2>
<div class="meta">
    <span>Tanggal Cetak: {{ $date }}</span>
    <span>Total: {{ count($students) }} siswa terdaftar</span>
</div>
<table>
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 12%;">NIS</th>
            <th style="width: 25%;">Nama Siswa</th>
            <th style="width: 10%;">L/P</th>
            <th style="width: 15%;">Kelas</th>
            <th style="width: 10%; text-align: center;">Skor KPI</th>
            <th style="width: 23%;">Status Peringatan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($students as $i => $s)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $s->nis }}</td>
            <td style="font-weight: bold;">{{ $s->full_name }}</td>
            <td>{{ $s->gender }}</td>
            <td>{{ $s->schoolClass?->class_name ?? '-' }}</td>
            <td style="text-align: center; font-weight: bold;">{{ $s->kpi?->overall_score ?? 100 }}</td>
            <td>
                <span class="badge badge-{{ $s->kpi?->warning_status ?? 'green' }}">
                    {{ $s->kpi?->status_label ?? 'Baik' }}
                </span>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:16px;color:#475569">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>
<div class="footer">EduGuard KPI — {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
