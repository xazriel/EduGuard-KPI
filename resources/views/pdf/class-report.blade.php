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
    table { width: 100%; border-collapse: collapse; font-size: 8.5px; margin-bottom: 20px; }
    th { background: #1e3a5f; color: white; padding: 6px 8px; text-align: left; font-weight: bold; }
    td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
    tr:nth-child(even) { background: #f9fafb; }
    .footer { margin-top: 16px; text-align: right; font-size: 8px; color: #475569; }
    .meta { display: flex; justify-content: space-between; font-size: 8.5px; color: #64748b; margin-bottom: 12px; }
    .violation-list { padding-left: 12px; }
    .violation-item { margin-bottom: 4px; }
</style>
</head>
<body>
<div class="header">
    <h1>LAPORAN MONITORING PELANGGARAN SISWA SMPN 216 JAKARTA</h1>
    <p>Sistem Monitoring Perilaku dan Pelanggaran Siswa</p>
</div>
<h2>Laporan Kelas: {{ $class->class_name }}</h2>
<div class="meta">
    <span>Tanggal Cetak: {{ $date }}</span>
    <span>Total: {{ count($students) }} siswa dengan catatan pelanggaran</span>
</div>
<table>
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 15%;">NIS / Nama Siswa</th>
            <th style="width: 10%; text-align: center;">Skor KPI</th>
            <th style="width: 70%;">Riwayat Pelanggaran</th>
        </tr>
    </thead>
    <tbody>
        @forelse($students as $i => $s)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>
                <div>{{ $s->nis }}</div>
                <div style="font-weight: bold;">{{ $s->full_name }}</div>
            </td>
            <td style="text-align: center; font-weight: bold;">{{ $s->kpi?->overall_score ?? 0 }}</td>
            <td>
                <ul class="violation-list">
                    @forelse($s->violations as $v)
                        <li class="violation-item">
                            <strong>{{ \Carbon\Carbon::parse($v->violation_date)->format('d/m/Y') }}</strong> - 
                            {{ $v->category_label }} ({{ $v->sub_category_label }}) <br>
                            <em>{{ $v->description }}</em>
                        </li>
                    @empty
                        <li>Tidak ada catatan detail.</li>
                    @endforelse
                </ul>
            </td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;padding:16px;color:#475569">Tidak ada siswa bermasalah di kelas ini.</td></tr>
        @endforelse
    </tbody>
</table>
<div class="footer">Dashboard Pelanggaran Siswa — {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
