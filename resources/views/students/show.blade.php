@extends('layouts.app')
@section('title', $student->full_name)
@section('page-title', 'Profil Siswa')
@section('page-subtitle', $student->full_name . ' — ' . $student->schoolClass?->class_name)

@php
    $backUrl = route('students.index');
    if ($student->class_id) {
        $backUrl = route('classes.show', $student->class_id);
    }
@endphp
@section('back-url', $backUrl)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Profile + KPI --}}
    <div class="space-y-6">
        {{-- Profile Card --}}
        <div class="glass-card p-6">
            @php
                $score  = $student->kpi?->overall_score ?? 0;
                $status = $student->kpi?->warning_status ?? 'green';
                $colorClass = $status === 'red' ? 'red' : 'amber';
            @endphp
            <div class="flex flex-col items-center text-center mb-6">
                <div class="relative mb-3">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-3xl font-bold text-slate-800 shadow-lg shadow-indigo-500/30">
                        {{ substr($student->full_name, 0, 1) }}
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-2 border-slate-100
                        {{ $status === 'red' ? 'bg-red-500' : 'bg-amber-500' }}"></span>
                </div>
                <h2 class="text-lg font-bold text-slate-800">{{ $student->full_name }}</h2>
                <p class="text-sm text-slate-500">{{ $student->schoolClass?->class_name }}</p>
                <span class="mt-2 text-xs px-3 py-1 rounded-full font-semibold
                    bg-{{ $colorClass }}-500/20 text-{{ $colorClass }}-400 border border-{{ $colorClass }}-500/30">
                    {{ $student->kpi?->status_label ?? 'Perlu Pembinaan' }}
                </span>
            </div>

            <div class="space-y-2 text-sm">
                @foreach([
                    ['NIS', $student->nis],
                    ['Jenis Kelamin', $student->gender_label],
                    ['Tempat Lahir', $student->birth_place ?? '-'],
                    ['Tanggal Lahir', $student->birth_date?->format('d/m/Y') ?? '-'],
                    ['Nama Orang Tua', $student->parent_name ?? '-'],
                ] as [$label, $value])
                <div class="flex justify-between py-2 border-b border-slate-200/50">
                    <span class="text-slate-400">{{ $label }}</span>
                    <span class="text-slate-700 font-medium text-right">{{ $value }}</span>
                </div>
                @endforeach
            </div>

            <div class="mt-4 flex gap-2">
                <a href="{{ route('students.edit', $student) }}"
                   class="flex-1 text-center text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-xl transition-colors">Edit Data</a>
                <a href="{{ route('violations.create') }}?student_id={{ $student->id }}"
                   class="flex-1 text-center text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-xl transition-colors">+ Pelanggaran</a>
            </div>
        </div>

        {{-- KPI Dimensions --}}
        <div class="glass-card p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4">📊 KPI Dimensi</h3>
            @php
                $dims = [
                    ['Kehadiran', $student->violations->where('category', 'kehadiran')->isEmpty() ? 0 : ($student->kpi?->attendance_score ?? 0), '🕐'],
                    ['Kedisiplinan', $student->violations->where('category', 'kedisiplinan')->isEmpty() ? 0 : ($student->kpi?->discipline_score ?? 0), '📋'],
                    ['Etika Sosial', $student->violations->where('category', 'etika_sosial')->isEmpty() ? 0 : ($student->kpi?->social_ethics_score ?? 0), '🤝'],
                    ['Agresivitas', $student->violations->where('category', 'agresivitas')->isEmpty() ? 0 : ($student->kpi?->aggression_score ?? 0), '💢'],
                    ['Integritas', $student->violations->where('category', 'integritas')->isEmpty() ? 0 : ($student->kpi?->integrity_score ?? 0), '⚖️'],
                    ['Risiko Tinggi', $student->violations->where('category', 'risiko_tinggi')->isEmpty() ? 0 : ($student->kpi?->high_risk_score ?? 0), '🚨'],
                    ['Tren Perilaku', $student->kpi?->behavior_trend_score ?? 0, '📈'],
                ];
            @endphp
            <div class="space-y-3">
                @foreach($dims as [$name, $val, $icon])
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-500">{{ $icon }} {{ $name }}</span>
                        <span class="font-semibold {{ $val <= 20 ? 'text-emerald-400' : ($val <= 40 ? 'text-amber-400' : 'text-red-400') }}">{{ round($val) }}/100</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-700"
                              style="width:{{ $val }}%; background: {{ $val <= 20 ? 'linear-gradient(90deg, #10b981, #059669)' : ($val <= 40 ? 'linear-gradient(90deg, #f59e0b, #d97706)' : 'linear-gradient(90deg, #f43f5e, #dc2626)') }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 pt-4 border-t border-slate-200 flex items-center justify-between">
                <span class="text-xs text-slate-400">Overall KPI Score</span>
                <span class="text-2xl font-bold text-{{ $colorClass }}-400">{{ $score }}</span>
            </div>
        </div>

        {{-- Recommendations --}}
        @if(count($recommendations) > 0)
        <div class="glass-card p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-3">💡 Rekomendasi Pembinaan</h3>
            <ul class="space-y-2">
                @foreach($recommendations as $rec)
                <li class="flex items-start gap-2 text-xs text-slate-600">
                    <span class="text-indigo-400 mt-0.5 flex-shrink-0">→</span>
                    {{ $rec }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    {{-- RIGHT: Charts + Violations --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Behavior Trend Chart --}}
        <div class="glass-card p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4">📈 Tren Pelanggaran Bulanan</h3>
            <div id="chartTrend" class="h-52"></div>
        </div>

        {{-- Category Breakdown --}}
        @if($categoryBreakdown->count())
        <div class="glass-card p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4">📊 Kategori Pelanggaran</h3>
            <div id="chartCat" class="h-44"></div>
        </div>
        @endif

        {{-- Violations Timeline --}}
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-800">📜 Riwayat Pelanggaran</h3>
                <span class="text-xs text-slate-400">{{ $student->violations->count() }} total</span>
            </div>
            <div class="space-y-4 max-h-96 overflow-y-auto pr-1">
                @forelse($student->violations->sortByDesc('violation_date') as $v)
                <div class="relative pl-6 border-l-2 {{ $v->severity === 'berat' ? 'border-red-500' : ($v->severity === 'sedang' ? 'border-amber-500' : 'border-gray-600') }}">
                    <div class="absolute -left-1.5 top-1 w-3 h-3 rounded-full {{ $v->severity === 'berat' ? 'bg-red-500' : ($v->severity === 'sedang' ? 'bg-amber-500' : 'bg-gray-500') }}"></div>
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-semibold text-slate-700">{{ $v->category_label }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $v->severity === 'berat' ? 'bg-red-500/20 text-red-400' : ($v->severity === 'sedang' ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-200 text-slate-500') }}">
                                    {{ $v->severity_label }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">{{ $v->sub_category_label }} — {{ $v->description }}</p>
                            @if($v->statementLetter)
                            <a href="{{ asset('storage/' . $v->statementLetter->generated_pdf) }}" target="_blank"
                               class="inline-flex items-center gap-1 text-xs text-indigo-400 hover:text-indigo-300 mt-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Unduh Surat
                            </a>
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="text-xs text-slate-400 font-medium whitespace-nowrap">{{ $v->violation_date->format('d/m/Y') }}</span>
                            <form action="{{ route('violations.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pelanggaran ini? Poin KPI siswa akan otomatis dihitung ulang.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[10px] bg-red-500/10 hover:bg-red-500/20 text-red-500 px-2 py-1 rounded transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-400 text-center py-8">Tidak ada riwayat pelanggaran. 🎉</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const base = { chart:{background:'transparent',toolbar:{show:false},fontFamily:'Inter'}, theme:{mode:'dark'}, tooltip:{theme:'dark'}, grid:{borderColor:'#1f2937'} };
    new ApexCharts(document.getElementById('chartTrend'), {
        ...base,
        chart: { ...base.chart, type:'bar', height:200 },
        series: [{ name:'Pelanggaran', data: @json($counts) }],
        xaxis: { categories: @json($months), labels:{style:{colors:'#64748b',fontSize:'11px'}} },
        yaxis: { labels:{style:{colors:'#64748b'}}, min:0, forceNiceScale:true },
        colors: ['#6366f1'],
        plotOptions: { bar:{borderRadius:4, columnWidth:'50%'} },
    }).render();

    @if($categoryBreakdown->count())
    const cats = @json($categoryBreakdown);
    const catLabels = {kehadiran:'Kehadiran',kedisiplinan:'Kedisiplinan',etika_sosial:'Etika Sosial',agresivitas:'Agresivitas',integritas:'Integritas',risiko_tinggi:'Risiko Tinggi'};
    new ApexCharts(document.getElementById('chartCat'), {
        ...base,
        chart: { ...base.chart, type:'bar', height:160 },
        series: [{ name:'Total', data:Object.values(cats) }],
        xaxis: { categories:Object.keys(cats).map(k=>catLabels[k]||k), labels:{style:{colors:'#64748b',fontSize:'10px'}} },
        yaxis: { labels:{style:{colors:'#64748b'}}, min:0 },
        colors: ['#8b5cf6','#6366f1','#ec4899','#f59e0b','#10b981','#ef4444'],
        plotOptions: { bar:{borderRadius:4, distributed:true} },
        legend: { show:false },
    }).render();
    @endif
});
</script>
@endpush
