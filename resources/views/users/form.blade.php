@extends('layouts.app')
@section('title', isset($user) ? 'Edit Guru BK' : 'Tambah Guru BK')
@section('page-title', isset($user) ? 'Edit Guru BK' : 'Tambah Guru BK')
@section('page-subtitle', isset($user) ? 'Ubah informasi akun Guru BK' : 'Buat akun Guru BK baru')

@section('content')
<div class="max-w-2xl">
    <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST" class="glass-card p-8">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="space-y-6">
            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" 
                       class="w-full bg-slate-50/50 border {{ $errors->has('name') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-slate-200 focus:border-indigo-500 focus:ring-indigo-500' }} text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-1 transition-colors"
                       placeholder="Contoh: Budi Santoso, S.Pd">
                @error('name')
                <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" 
                       class="w-full bg-slate-50/50 border {{ $errors->has('email') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-slate-200 focus:border-indigo-500 focus:ring-indigo-500' }} text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-1 transition-colors"
                       placeholder="Contoh: budi.bk@sekolah.sch.id">
                @error('email')
                <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password {{ isset($user) ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                    <input type="password" name="password" 
                           class="w-full bg-slate-50/50 border {{ $errors->has('password') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-slate-200 focus:border-indigo-500 focus:ring-indigo-500' }} text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-1 transition-colors"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                    <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" 
                           class="w-full bg-slate-50/50 border border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-1 transition-colors"
                           placeholder="Ulangi password">
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('users.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2.5 rounded-xl transition-colors shadow-lg shadow-indigo-500/30 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
