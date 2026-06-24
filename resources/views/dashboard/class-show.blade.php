@extends('layouts.app')
@section('title', 'KPI Kelas ' . $class->class_name)
@section('page-title', 'Dashboard Kelas ' . $class->class_name)
@section('page-subtitle', 'Analitik perilaku siswa kelas ' . $class->class_name)

@section('back-url', route('dashboard'))

@section('content')
{{-- Avg KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php $overall = $avgKpi['overall_score']; @endphp
    <div class="glass-card p-5 lg:col-span-1 flex items-center gap-4">
        <div class="relative w-16 h-16 flex-shrink-0">
            <svg class="w-16 h-16 -rotate-90" viewBox="0 0 36 36">
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#1f2937" stroke-width="3"/>
                <circle cx="18" cy="18" r="15.9" fill="none"
                    stroke="{{ $overall <= 40 ? '#eab308' : '#ef4444' }}"
                    stroke-width="3" stroke-dasharray="{{ $overall }},100" stroke-linecap="round"/>
            </svg>
            <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-slate-800">{{ $overall }}</span>
        </div>
        <div>
            <p class="text-xs text-slate-400">Rata-rata KPI</p>
            <p class="font-semibold text-white">{{ $class->class_name }}</p>
            <p class="text-xs {{ $overall <= 40 ? 'text-amber-400' : 'text-red-400' }}">
                {{ $overall <= 40 ? 'Perlu Pembinaan' : 'Risiko Tinggi' }}
            </p>
        </div>
    </div>
    @foreach([
        ['label'=>'Kehadiran','key'=>'attendance_score','icon'=>'🕐'],
        ['label'=>'Kedisiplinan','key'=>'discipline_score','icon'=>'📋'],
        ['label'=>'Etika Sosial','key'=>'social_ethics_score','icon'=>'🤝'],
    ] as $dim)
    <div class="glass-card p-4">
        <p class="text-xs text-slate-400 mb-1">{{ $dim['icon'] }} {{ $dim['label'] }}</p>
        <p class="text-2xl font-bold {{ $avgKpi[$dim['key']] <= 40 ? 'text-amber-400' : 'text-red-400' }}">
            {{ $avgKpi[$dim['key']] }}
        </p>
        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
            <div class="h-1.5 rounded-full {{ $avgKpi[$dim['key']] <= 40 ? 'bg-amber-500' : 'bg-red-500' }}"
                 style="width:{{ $avgKpi[$dim['key']] }}%"></div>
        </div>
    </div>
    @endforeach
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 glass-card p-6">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">📈 Tren Pelanggaran 6 Bulan</h3>
        <div id="chartTrend" class="h-52"></div>
    </div>
    <div class="glass-card p-6">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">📊 Kategori Dominan</h3>
        <div id="chartCat" class="h-52"></div>
    </div>
</div>

{{-- Student Ranking --}}
<div class="glass-card p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-slate-800">👥 Ranking Siswa berdasarkan KPI</h3>
        <a href="{{ route('violations.create') }}" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition-colors">+ Input Pelanggaran</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs text-slate-400 uppercase tracking-wider">
                    <th class="pb-3 text-left">No</th>
                    <th class="pb-3 text-left">Nama Siswa</th>
                    <th class="pb-3 text-left">NIS</th>
                    <th class="pb-3 text-center">Overall</th>
                    <th class="pb-3 text-center">Status</th>
                    <th class="pb-3 text-center">Pelanggaran</th>
                    <th class="pb-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @foreach($studentRanking as $i => $student)
                <tr class="hover:bg-slate-100/30 transition-colors">
                    <td class="py-3 text-slate-400 text-xs">{{ $i+1 }}</td>
                    <td class="py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $student->kpi?->warning_status === 'red' ? 'bg-red-500/20 text-red-400' : 'bg-amber-500/20 text-amber-400' }}">
                                {{ substr($student->full_name, 0, 1) }}
                            </div>
                            <span class="font-medium text-slate-700">{{ $student->full_name }}</span>
                        </div>
                    </td>
                    <td class="py-3 text-slate-500 font-mono text-xs">{{ $student->nis }}</td>
                    <td class="py-3 text-center">
                        <span class="font-bold {{ ($student->kpi?->overall_score ?? 0) <= 40 ? 'text-amber-400' : 'text-red-400' }}">
                            {{ $student->kpi?->overall_score ?? 0 }}
                        </span>
                    </td>
                    <td class="py-3 text-center">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $student->kpi?->warning_status === 'red' ? 'bg-red-500/20 text-red-400' : 'bg-amber-500/20 text-amber-400' }}">
                            {{ $student->kpi?->status_label ?? 'Perlu Pembinaan' }}
                        </span>
                    </td>
                    <td class="py-3 text-center text-slate-500">{{ $student->violations->count() }}</td>
                    <td class="py-3 text-right">
                        <a href="{{ route('students.show', $student) }}" class="text-xs text-indigo-400 hover:text-indigo-300">Detail →</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .apexcharts-canvas .apexcharts-series path,
    .apexcharts-canvas .apexcharts-series circle,
    .apexcharts-canvas .apexcharts-pie-series path {
        cursor: pointer !important;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const classId = "{{ $class->id }}";
    const monthDates = @json($monthDates);
    const base = { chart: { background:'transparent', toolbar:{show:false}, fontFamily:'Inter' }, theme:{mode:'dark'}, tooltip:{theme:'dark'}, grid:{borderColor:'#1f2937'} };
    
    new ApexCharts(document.getElementById('chartTrend'), {
        ...base,
        chart: { 
            ...base.chart, 
            type:'line', 
            height:200,
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
                        window.location.href = `{{ route('violations.index') }}?class_id=${classId}&severity=${severity}&date_from=${dateFrom}&date_to=${dateTo}`;
                    }
                }
            }
        },
        series: [
            { name: 'Ringan', data: @json($trendRingan) },
            { name: 'Sedang', data: @json($trendSedang) },
            { name: 'Berat', data: @json($trendBerat) }
        ],
        xaxis: { categories: @json(array_values($months)), labels:{style:{colors:'#64748b',fontSize:'11px'}} },
        yaxis: { labels:{style:{colors:'#64748b'}} },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#22c55e', '#eab308', '#ef4444'],
        legend: { position: 'top', fontSize: '11px', labels: { colors: '#475569' } },
    }).render();

    const cats = @json($categoryData);
    const catKeys = Object.keys(cats);
    const catLabels = {kehadiran:'Kehadiran',kedisiplinan:'Kedisiplinan',etika_sosial:'Etika Sosial',agresivitas:'Agresivitas',integritas:'Integritas',risiko_tinggi:'Risiko Tinggi'};
    new ApexCharts(document.getElementById('chartCat'), {
        ...base,
        chart: { 
            ...base.chart, 
            type:'donut', 
            height:200,
            events: {
                dataPointSelection: function(event, chartContext, config) {
                    if (config.dataPointIndex === undefined || config.dataPointIndex === -1) return;
                    const categoryKey = catKeys[config.dataPointIndex];
                    if (categoryKey) {
                        window.location.href = `{{ route('violations.index') }}?class_id=${classId}&category=${categoryKey}`;
                    }
                }
            }
        },
        series: Object.values(cats).length ? Object.values(cats) : [1],
        labels: Object.keys(cats).length ? Object.keys(cats).map(k=>catLabels[k]||k) : ['Belum ada data'],
        colors: ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#ef4444'],
        plotOptions: { pie:{ donut:{ size:'65%' } } },
        legend: { position:'bottom', fontSize:'11px', labels:{colors:'#475569'} },
    }).render();
});
</script>
@endpush
