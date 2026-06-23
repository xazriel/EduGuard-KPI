@extends('layouts.app')
@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')
@section('page-subtitle', 'Kelola dan pantau seluruh siswa aktif')

@section('content')
{{-- Filter Bar --}}
<div class="glass-card p-4 mb-6 flex flex-col sm:flex-row gap-3">
    <form method="GET" action="{{ route('students.index') }}" class="flex flex-wrap gap-3 flex-1">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama / NIS..."
               class="flex-1 min-w-48 bg-white border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-400">

        <select name="class_id" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            <option value="">Semua Kelas</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
            @endforeach
        </select>

        <select name="warning_status" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            <option value="">Semua Status</option>
            <option value="green"  {{ request('warning_status') === 'green'  ? 'selected' : '' }}>🟢 Baik</option>
            <option value="yellow" {{ request('warning_status') === 'yellow' ? 'selected' : '' }}>🟡 Perlu Pembinaan</option>
            <option value="red"    {{ request('warning_status') === 'red'    ? 'selected' : '' }}>🔴 Risiko Tinggi</option>
        </select>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-5 py-2.5 rounded-xl transition-colors font-medium">Filter</button>
        <a href="{{ route('students.index') }}" class="text-gray-500 hover:text-slate-800 text-sm px-3 py-2.5 rounded-xl transition-colors">Reset</a>
    </form>
    <div class="flex gap-2">
        <a href="{{ route('export.students.pdf') }}" class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm px-4 py-2.5 rounded-xl transition-colors font-medium flex items-center gap-1.5 hover:bg-red-500/20" title="Export PDF">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span>PDF</span>
        </a>
        <a href="{{ route('export.students.excel') }}" class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm px-4 py-2.5 rounded-xl transition-colors font-medium flex items-center gap-1.5 hover:bg-emerald-500/20" title="Export Excel">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Excel</span>
        </a>
        <a href="{{ route('students.create') }}" class="bg-violet-600 hover:bg-violet-700 text-white text-sm px-5 py-2.5 rounded-xl transition-colors font-medium flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Siswa
        </a>
    </div>
</div>

{{-- Student Table --}}
<div class="glass-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-100/50">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Siswa</th>
                    <th class="px-4 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">NIS</th>
                    <th class="px-4 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-4 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">KPI Score</th>
                    <th class="px-4 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Pelanggaran</th>
                    <th class="px-4 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($students as $student)
                @php
                    $score  = $student->kpi?->overall_score ?? 0;
                    $status = $student->kpi?->warning_status ?? 'green';
                    $colorClass = $status === 'red' ? 'red' : ($status === 'yellow' ? 'amber' : 'emerald');
                @endphp
                <tr class="hover:bg-slate-100/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0
                                bg-{{ $colorClass }}-500/20 text-{{ $colorClass }}-400">
                                {{ substr($student->full_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-700 group-hover:text-slate-900 transition-colors">{{ $student->full_name }}</p>
                                <p class="text-xs text-slate-400">{{ $student->gender_label }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-mono text-xs text-slate-600">{{ $student->nis }}</p>
                    </td>
                    <td class="px-4 py-4 text-slate-500 text-xs">{{ $student->schoolClass?->class_name }}</td>
                    <td class="px-4 py-4 text-center">
                        <div class="inline-flex flex-col items-center">
                            <span class="text-lg font-bold text-{{ $colorClass }}-400">{{ $score }}</span>
                            <div class="w-16 bg-slate-100 rounded-full h-1 mt-1">
                                <div class="h-1 rounded-full bg-{{ $colorClass }}-500" style="width:{{ $score }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full
                            bg-{{ $colorClass }}-500/15 text-{{ $colorClass }}-400 border border-{{ $colorClass }}-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-{{ $colorClass }}-400 inline-block"></span>
                            {{ $student->kpi?->status_label ?? 'Baik' }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center text-slate-500">
                        {{ $student->violations->count() ?? 0 }}
                    </td>
                    <td class="px-4 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('students.show', $student) }}" class="text-xs text-indigo-400 hover:text-indigo-300 px-2 py-1 rounded-lg hover:bg-indigo-500/10 transition-colors">Detail</a>
                            <a href="{{ route('students.edit', $student) }}" class="text-xs text-slate-500 hover:text-slate-600 px-2 py-1 rounded-lg hover:bg-slate-200 transition-colors">Edit</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        Tidak ada siswa ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
    <div class="px-6 py-2 border-t border-slate-200">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection
