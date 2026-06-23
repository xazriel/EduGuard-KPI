<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Data Siswa — Dashboard Pelanggaran Siswa</title>
    <meta name="description" content="Portal publik pencarian data siswa berdasarkan NIS">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glow { box-shadow: 0 0 60px rgba(99,102,241,0.15); }
        .fade-in { animation: fadeIn .6s ease-out; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        .shimmer { background: linear-gradient(135deg, rgba(99,102,241,.1) 0%, rgba(139,92,246,.05) 50%, rgba(99,102,241,.1) 100%); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="border-b border-slate-200 bg-white/80 backdrop-blur-md sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <p class="font-bold text-slate-800 text-sm">Dashboard Pelanggaran Siswa</p>
                    <p class="text-xs text-indigo-400">Portal Siswa & Orang Tua</p>
                </div>
            </div>
            <a href="{{ route('login') }}" class="text-xs text-gray-500 hover:text-slate-800 border border-slate-300 hover:border-gray-500 px-3 py-1.5 rounded-lg transition-colors">
                Login Guru BK
            </a>
        </div>
    </header>

    {{-- Hero --}}
    <section class="flex-1 flex items-center justify-center px-6 py-16">
        <div class="max-w-lg w-full fade-in">
            {{-- Icon --}}
            <div class="flex justify-center mb-8">
                <div class="relative">
                    <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-2xl shadow-indigo-500/30 glow">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-xl bg-emerald-500 flex items-center justify-center shadow-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
            </div>

            <h1 class="text-3xl font-black text-slate-800 text-center mb-2 tracking-tight">Cek Data Siswa</h1>
            <p class="text-slate-500 text-center text-sm mb-8 leading-relaxed">
                Masukkan <span class="text-indigo-400 font-semibold">NIS</span> untuk melihat profil, riwayat pelanggaran, dan skor KPI siswa.
            </p>

            {{-- Search Form --}}
            <form method="POST" action="{{ route('public.lookup.search') }}" class="space-y-4">
                @csrf
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="query" id="queryInput"
                           value="{{ old('query') }}"
                           placeholder="Contoh: 00000001"
                           autocomplete="off"
                           class="w-full bg-white border {{ $errors->has('query') ? 'border-red-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500' }} text-slate-700 text-base rounded-2xl pl-12 pr-4 py-4 focus:outline-none placeholder-slate-400 transition-colors">
                </div>

                @if($errors->has('query'))
                <div class="flex items-center gap-2 text-red-400 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ $errors->first('query') }}
                </div>
                @endif

                <button type="submit" id="searchBtn"
                        class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold py-4 rounded-2xl transition-all shadow-xl shadow-indigo-500/20 text-base">
                    Cari Data Siswa
                </button>
            </form>

            {{-- Features --}}
            <div class="mt-10 grid grid-cols-3 gap-3">
                @foreach([
                    ['🎯','Tanpa Login','Akses langsung'],
                    ['📊','Skor KPI','Real-time'],
                    ['📄','Surat Digital','Unduh langsung'],
                ] as [$icon,$title,$sub])
                <div class="shimmer border border-slate-200 rounded-2xl p-4 text-center">
                    <p class="text-2xl mb-1">{{ $icon }}</p>
                    <p class="text-xs font-semibold text-slate-700">{{ $title }}</p>
                    <p class="text-xs text-slate-400">{{ $sub }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <footer class="border-t border-slate-200 py-4 text-center text-xs text-slate-400">
        Dashboard Pelanggaran Siswa — Sistem Monitoring Pelanggaran Siswa © {{ date('Y') }}
    </footer>

    <script>
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.getElementById('searchBtn');
            btn.textContent = 'Mencari...';
            setTimeout(() => {
                btn.disabled = true;
            }, 0);
        });
    </script>
</body>
</html>
