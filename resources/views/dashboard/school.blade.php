@extends('layouts.app')

@section('title', 'Dashboard Sekolah')
@section('page-title', 'Dashboard KPI Sekolah')
@section('page-subtitle', 'Ringkasan perilaku & pelanggaran seluruh siswa')

@section('content')

{{-- KPI Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('students.index') }}" class="glass-card p-5 flex items-center gap-4 hover:border-indigo-500/40 hover:bg-indigo-500/5 transition-all block">
        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalStudents }}</p>
            <p class="text-xs text-slate-400">Total Siswa Aktif</p>
        </div>
    </a>
    <div class="glass-card p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-violet-500/20 flex items-center justify-center">
            <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $aggregates['avg_overall'] }}</p>
            <p class="text-xs text-slate-400">Rata-rata KPI Sekolah</p>
        </div>
    </div>
    <a href="{{ route('violations.index') }}" class="glass-card p-5 flex items-center gap-4 hover:border-amber-500/40 hover:bg-amber-500/5 transition-all block">
        <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center">
            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalThisMonth }}</p>
            <p class="text-xs text-slate-400">Pelanggaran Bulan Ini</p>
        </div>
    </a>
    <a href="{{ route('students.index', ['warning_status' => 'red']) }}" class="glass-card p-5 flex items-center gap-4 hover:border-red-500/40 hover:bg-red-500/5 transition-all block">
        <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center">
            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $aggregates['red_count'] }}</p>
            <p class="text-xs text-slate-400">Siswa Risiko Tinggi</p>
        </div>
    </a>
</div>

{{-- Warning Status Distribution --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <a href="{{ route('students.index', ['warning_status' => 'yellow']) }}" class="glass-card p-4 text-center border-t-2 border-amber-500 hover:border-indigo-500/40 hover:bg-indigo-500/5 transition-all block">
        <p class="text-3xl font-bold text-amber-400">{{ $aggregates['yellow_count'] }}</p>
        <p class="text-xs text-slate-400 mt-1">🟡 Perlu Pembinaan</p>
        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
            <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $aggregates['total_students'] > 0 ? round($aggregates['yellow_count']/$aggregates['total_students']*100) : 0 }}%"></div>
        </div>
    </a>
    <a href="{{ route('students.index', ['warning_status' => 'red']) }}" class="glass-card p-4 text-center border-t-2 border-red-500 hover:border-indigo-500/40 hover:bg-indigo-500/5 transition-all block">
        <p class="text-3xl font-bold text-red-400">{{ $aggregates['red_count'] }}</p>
        <p class="text-xs text-slate-400 mt-1">🔴 Risiko Tinggi</p>
        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
            <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ $aggregates['total_students'] > 0 ? round($aggregates['red_count']/$aggregates['total_students']*100) : 0 }}%"></div>
        </div>
    </a>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Monthly Trend --}}
    <div class="lg:col-span-2 glass-card p-6">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">
            📈 Tren Pelanggaran 6 Bulan Terakhir
            <span class="text-[10px] text-indigo-400 font-normal block mt-0.5">💡 Klik titik grafik untuk drill-down data</span>
        </h3>
        <div id="chartMonthly" class="h-56"></div>
    </div>
    {{-- Category Pie --}}
    <div class="glass-card p-6">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">
            📊 Kategori Pelanggaran
            <span class="text-[10px] text-indigo-400 font-normal block mt-0.5">💡 Klik bagian diagram untuk drill-down data</span>
        </h3>
        <div id="chartCategory" class="h-56"></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Class Ranking --}}
    <div class="glass-card p-6">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">🏫 Ranking Kelas (Risiko Tertinggi)</h3>
        <div class="space-y-3">
            @foreach($classRanking->take(6) as $i => $class)
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold w-5 text-slate-400">{{ $i+1 }}</span>
                <div class="flex-1">
                    <div class="flex justify-between text-xs mb-1">
                        <a href="{{ route('classes.show', $class['id']) }}" class="text-slate-600 font-medium hover:text-indigo-400 transition-colors">{{ $class['name'] }}</a>
                        <span class="{{ $class['avg_score'] <= 10 ? 'text-emerald-400' : ($class['avg_score'] <= 20 ? 'text-indigo-400' : ($class['avg_score'] <= 40 ? 'text-amber-400' : 'text-rose-400')) }} font-semibold">
                            {{ $class['avg_score'] }}
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-500"
                             style="width: {{ $class['avg_score'] }}%; background: {{ $class['avg_score'] <= 10 ? 'linear-gradient(90deg, #10b981, #059669)' : ($class['avg_score'] <= 20 ? 'linear-gradient(90deg, #6366f1, #8b5cf6)' : ($class['avg_score'] <= 40 ? 'linear-gradient(90deg, #f59e0b, #d97706)' : 'linear-gradient(90deg, #f43f5e, #dc2626)')) }}"></div>
                    </div>
                </div>
                @if($class['red_count'] > 0)
                <span class="text-xs bg-red-500/20 text-red-400 px-2 py-0.5 rounded-full">{{ $class['red_count'] }} merah</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- High Risk Students --}}
    <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-800">⚠️ Siswa Risiko Tinggi</h3>
            <a href="{{ route('students.index', ['warning_status' => 'red']) }}" class="text-xs text-indigo-400 hover:text-indigo-300">Lihat Semua →</a>
        </div>
        <div class="space-y-3">
            @forelse($highRiskStudents->take(6) as $kpi)
            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-100/80 transition-colors">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $kpi->warning_status === 'red' ? 'bg-red-500/20 text-red-400' : 'bg-amber-500/20 text-amber-400' }}">
                    {{ substr($kpi->student->full_name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('students.show', $kpi->student) }}" class="text-sm text-slate-700 font-medium hover:text-slate-900 truncate block">{{ $kpi->student->full_name }}</a>
                    <p class="text-xs text-slate-400">{{ $kpi->student->schoolClass?->class_name }} — NIS: {{ $kpi->student->nis }}</p>
                </div>
                <span class="text-sm font-bold {{ $kpi->warning_status === 'red' ? 'text-red-400' : 'text-amber-400' }}">{{ $kpi->overall_score }}</span>
            </div>
            @empty
            <p class="text-sm text-slate-400 text-center py-4">Tidak ada siswa berisiko.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Severity Distribution --}}
<div class="glass-card p-6">
    <h3 class="text-sm font-semibold text-slate-800 mb-4">
        📋 Distribusi Tingkat Keparahan Pelanggaran
        <span class="text-[10px] text-indigo-400 font-normal block mt-0.5">💡 Klik batang diagram untuk drill-down data</span>
    </h3>
    <div id="chartSeverity" class="h-48"></div>
</div>

@endsection

@push('scripts')
<style>
    .apexcharts-canvas .apexcharts-series path,
    .apexcharts-canvas .apexcharts-series circle,
    .apexcharts-canvas .apexcharts-bar-series rect,
    .apexcharts-canvas .apexcharts-pie-series path {
        cursor: pointer !important;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartOpts = {
        chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'Inter' },
        theme: { mode: 'light' },
        tooltip: { theme: 'light' },
    };

    // Monthly Trend
    const monthDates = @json($monthDates);
    new ApexCharts(document.getElementById('chartMonthly'), {
        ...chartOpts,
        chart: { 
            ...chartOpts.chart, 
            type: 'line', 
            height: 220,
            events: {
                dataPointSelection: function(event, chartContext, config) {
                    if (config.dataPointIndex === undefined || config.dataPointIndex === -1) return;
                    const monthDate = monthDates[config.dataPointIndex];
                    const severities = ['ringan', 'sedang', 'berat'];
                    const severity = severities[config.seriesIndex];
                    if (monthDate && severity) {
                        const dateFrom = monthDate + '-01';
                        const parts = monthDate.split('-');
                        const year = parseInt(parts[0], 10);
                        const month = parseInt(parts[1], 10);
                        const lastDay = new Date(year, month, 0).getDate();
                        const dateTo = monthDate + '-' + String(lastDay).padStart(2, '0');
                        window.location.href = `{{ route('violations.index') }}?severity=${severity}&date_from=${dateFrom}&date_to=${dateTo}`;
                    }
                }
            }
        },
        series: [
            { name: 'Ringan', data: @json($trendRingan) },
            { name: 'Sedang', data: @json($trendSedang) },
            { name: 'Berat', data: @json($trendBerat) }
        ],
        xaxis: { categories: @json(array_values($months)), labels: { style: { colors: '#64748b', fontSize: '11px' } } },
        yaxis: { labels: { style: { colors: '#64748b' } } },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#22c55e', '#eab308', '#ef4444'],
        grid: { borderColor: '#cbd5e1' },
        legend: { position: 'top', fontSize: '11px', labels: { colors: '#475569' } },
    }).render();

    // Category Pie
    const cats = @json($categoryData);
    const catKeys = Object.keys(cats);
    const catLabels = { kehadiran:'Kehadiran', kedisiplinan:'Kedisiplinan', etika_sosial:'Etika Sosial', agresivitas:'Agresivitas', integritas:'Integritas', risiko_tinggi:'Risiko Tinggi' };
    new ApexCharts(document.getElementById('chartCategory'), {
        ...chartOpts,
        chart: { 
            ...chartOpts.chart, 
            type: 'donut', 
            height: 220,
            events: {
                dataPointSelection: function(event, chartContext, config) {
                    if (config.dataPointIndex === undefined || config.dataPointIndex === -1) return;
                    const categoryKey = catKeys[config.dataPointIndex];
                    if (categoryKey) {
                        window.location.href = "{{ route('violations.index') }}?category=" + categoryKey;
                    }
                }
            }
        },
        series: Object.values(cats).length ? Object.values(cats) : [1],
        labels: Object.keys(cats).length ? Object.keys(cats).map(k => catLabels[k] || k) : ['Belum ada data'],
        colors: ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#ef4444'],
        plotOptions: { pie: { donut: { size: '65%' } } },
        legend: { position: 'bottom', fontSize: '11px', labels: { colors: '#475569' } },
    }).render();

    // Severity Bar
    const sev = @json($severityData);
    new ApexCharts(document.getElementById('chartSeverity'), {
        ...chartOpts,
        chart: { 
            ...chartOpts.chart, 
            type: 'bar', 
            height: 180,
            events: {
                dataPointSelection: function(event, chartContext, config) {
                    if (config.dataPointIndex === undefined || config.dataPointIndex === -1) return;
                    const severities = ['ringan', 'sedang', 'berat'];
                    const selectedSeverity = severities[config.dataPointIndex];
                    if (selectedSeverity) {
                        window.location.href = "{{ route('violations.index') }}?severity=" + selectedSeverity;
                    }
                }
            }
        },
        series: [{ name: 'Jumlah', data: [sev.ringan||0, sev.sedang||0, sev.berat||0] }],
        xaxis: { categories: ['Ringan', 'Sedang', 'Berat'], labels: { style: { colors: '#64748b' } } },
        yaxis: { labels: { style: { colors: '#64748b' } } },
        colors: ['#f59e0b', '#f97316', '#ef4444'],
        plotOptions: { bar: { borderRadius: 6, distributed: true } },
        legend: { show: false },
        grid: { borderColor: '#cbd5e1' },
    }).render();
});
</script>
@endpush
