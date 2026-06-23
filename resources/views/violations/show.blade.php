@extends('layouts.app')
@section('title', 'Detail Pelanggaran')
@section('page-title', 'Detail Pelanggaran')
@section('page-subtitle', 'Informasi lengkap dan surat pernyataan')

@section('back-url', route('violations.index'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Main Card --}}
    <div class="glass-card p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-800">{{ $violation->category_label }} — {{ $violation->sub_category_label }}</h2>
                <p class="text-sm text-slate-400 mt-1">{{ $violation->violation_date->format('d/m/Y') }} · Dicatat oleh {{ $violation->createdBy?->name }}</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                {{ $violation->severity === 'berat' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : ($violation->severity === 'sedang' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30') }}">
                {{ $violation->severity_label }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Siswa</p>
                    <a href="{{ route('students.show', $violation->student) }}" class="text-indigo-400 hover:text-indigo-300 font-medium">
                        {{ $violation->student?->full_name }}
                    </a>
                    <p class="text-xs text-slate-300">NIS: {{ $violation->student?->nis }} · {{ $violation->student?->schoolClass?->class_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Kategori</p>
                    <p class="text-slate-700">{{ $violation->category_label }}</p>
                </div>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Deskripsi</p>
                    <p class="text-slate-600 text-sm">{{ $violation->description ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Tindak Lanjut</p>
                    <p class="text-slate-600 text-sm">{{ $violation->follow_up ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(str_starts_with($violation->follow_up ?? '', 'Pembinaan'))
    {{-- Statement Letter --}}
    <div class="glass-card p-6">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">📄 Surat Pernyataan</h3>
        @if($violation->statementLetter?->generated_pdf)
        <div class="flex items-center gap-4 p-4 bg-indigo-500/10 border border-indigo-500/30 rounded-xl mb-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-slate-700">Surat Pernyataan Digital</p>
                <p class="text-xs text-slate-400">Generate otomatis oleh sistem</p>
            </div>
            <a href="{{ asset('storage/' . $violation->statementLetter->generated_pdf) }}" target="_blank"
               class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl transition-colors">
                Unduh PDF
            </a>
        </div>
        @else
        <p class="text-sm text-slate-400 mb-4">Surat belum digenerate.</p>
        @endif

        {{-- Upload Scan --}}
        <div class="border-t border-slate-200 pt-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Upload Surat Bertanda Tangan</p>
            @if($violation->statementLetter?->scanned_signed_file)
            <div class="flex items-center gap-3 p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl mb-3">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xs text-emerald-400">Scan surat sudah diupload</p>
                <a href="{{ asset('storage/' . $violation->statementLetter->scanned_signed_file) }}" target="_blank" class="ml-auto text-xs text-indigo-400 hover:text-indigo-300">Lihat File</a>
            </div>
            @endif
            <form method="POST" action="{{ route('violations.upload-scan', $violation) }}" enctype="multipart/form-data" class="flex items-center gap-3">
                @csrf
                <input type="file" name="scanned_file" accept=".jpg,.jpeg,.png,.pdf"
                       class="flex-1 text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer">
                <button type="submit" class="bg-slate-200 hover:bg-gray-600 text-slate-700 text-xs px-4 py-2 rounded-xl transition-colors whitespace-nowrap">Upload</button>
            </form>
            <p class="text-xs text-slate-300 mt-2">Format: JPG, PNG, PDF. Maks 5MB.</p>
        </div>
    </div>
    @endif

    <div class="flex gap-3">
        <a href="{{ route('violations.index') }}" class="text-sm text-slate-500 hover:text-slate-600 flex items-center gap-1">← Kembali ke Daftar</a>
        <a href="{{ route('students.show', $violation->student) }}" class="ml-auto text-sm text-indigo-400 hover:text-indigo-300">Lihat Profil Siswa →</a>
    </div>
</div>
@endsection
