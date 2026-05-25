@extends('layouts.app')
@section('title', 'Dashboard Kelas')
@section('page-title', 'Dashboard KPI Kelas')
@section('page-subtitle', 'Pilih kelas untuk melihat detail analitik')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    @foreach($classes as $class)
    @php
        $students = $class->students ?? collect();
        $kpis = $students->filter(fn($s) => $s->kpi);
        $avgScore = $kpis->count() ? round($kpis->avg(fn($s) => $s->kpi->overall_score), 1) : 100;
        $redCount = $students->filter(fn($s) => $s->kpi?->warning_status === 'red')->count();
        $yellowCount = $students->filter(fn($s) => $s->kpi?->warning_status === 'yellow')->count();
        $color = $avgScore >= 80 ? 'emerald' : ($avgScore >= 60 ? 'amber' : 'red');
    @endphp
    <a href="{{ route('classes.show', $class) }}"
       class="glass-card p-6 hover:border-indigo-500/40 hover:bg-indigo-500/5 transition-all duration-300 group block">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                {{ $color === 'emerald' ? 'bg-emerald-500/20 text-emerald-400' : ($color === 'amber' ? 'bg-amber-500/20 text-amber-400' : 'bg-red-500/20 text-red-400') }}">
                Kelas {{ $class->grade_level }}
            </span>
        </div>
        <h3 class="text-lg font-bold text-slate-800 mb-1">{{ $class->class_name }}</h3>
        <p class="text-xs text-slate-400 mb-4">{{ $class->students_count }} siswa terdaftar</p>

        <div class="mb-3">
            <div class="flex justify-between text-xs mb-1">
                <span class="text-slate-500">Rata-rata KPI</span>
                <span class="font-bold text-{{ $color }}-400">{{ $avgScore }}</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2">
                <div class="h-2 rounded-full bg-{{ $color }}-500 transition-all duration-700"
                     style="width: {{ $avgScore }}%"></div>
            </div>
        </div>

        <div class="flex gap-3 text-xs">
            @if($redCount > 0)
            <span class="flex items-center gap-1 text-red-400">
                <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>{{ $redCount }} merah
            </span>
            @endif
            @if($yellowCount > 0)
            <span class="flex items-center gap-1 text-amber-400">
                <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>{{ $yellowCount }} kuning
            </span>
            @endif
        </div>
    </a>
    @endforeach
</div>
@endsection
