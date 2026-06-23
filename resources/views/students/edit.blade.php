@extends('layouts.app')
@section('title', 'Edit Siswa')
@section('page-title', 'Edit Data Siswa')
@section('page-subtitle', $student->full_name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card p-8">
        <form method="POST" action="{{ route('students.update', $student) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">NIS *</label>
                <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" required
                       class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap *</label>
                <input type="text" name="full_name" value="{{ old('full_name', $student->full_name) }}" required
                       class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Jenis Kelamin *</label>
                    <select name="gender" required class="w-full bg-white border border-slate-300 text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="L" {{ $student->gender === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $student->gender === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Kelas *</label>
                    <select name="class_id" required class="w-full bg-white border border-slate-300 text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $student->class_id == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Status</label>
                    <select name="status" class="w-full bg-white border border-slate-300 text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="active"    {{ $student->status === 'active'    ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive"  {{ $student->status === 'inactive'  ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="graduated" {{ $student->status === 'graduated' ? 'selected' : '' }}>Lulus</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tempat Lahir</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}"
                           class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}"
                           class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nama Orang Tua</label>
                <input type="text" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}"
                       class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Alamat</label>
                <textarea name="address" rows="2" class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none">{{ old('address', $student->address) }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition-colors">Simpan Perubahan</button>
                <a href="{{ route('students.show', $student) }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors text-sm font-medium">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
