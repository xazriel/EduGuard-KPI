<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru — Dashboard Pelanggaran Siswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glow { box-shadow: 0 0 60px rgba(99,102,241,0.15); }
        .fade-in { animation: fadeIn .6s ease-out; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="border-b border-slate-200 bg-white/80 backdrop-blur-md sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo-smpn216.png') }}" alt="Logo SMPN 216" class="w-10 h-10 object-contain">
                <div>
                    <p class="font-bold text-slate-800 text-sm">SMPN 216 JAKARTA</p>
                    <p class="text-xs text-indigo-400 font-semibold">Dashboard Monitoring Pelanggaran Siswa</p>
                </div>
            </div>
            <a href="{{ route('public.lookup') }}" class="text-xs text-gray-500 hover:text-slate-800 px-3 py-1.5 rounded-lg transition-colors">
                &larr; Kembali ke Pencarian
            </a>
        </div>
    </header>

    {{-- Form Section --}}
    <section class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="max-w-lg w-full fade-in">
            <div class="bg-white p-8 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100">
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Pendaftaran Siswa Baru</h1>
                <p class="text-sm text-slate-500 mb-6">Silakan lengkapi data diri Anda di bawah ini.</p>

                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-xl text-sm border border-red-100">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('public.register.store') }}" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">NIS (Nomor Induk Siswa)</label>
                        <input type="text" name="nis" value="{{ old('nis') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-sm"
                               placeholder="Contoh: 0123456789">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-sm"
                               placeholder="Nama Lengkap Sesuai Ijazah">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Kelamin</label>
                            <select name="gender" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-sm">
                                <option value="" disabled selected>Pilih...</option>
                                <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Kelas</label>
                            <select name="class_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-sm">
                                <option value="" disabled selected>Pilih Kelas...</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->grade_level }} - {{ $c->class_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tempat Lahir</label>
                            <input type="text" name="birth_place" value="{{ old('birth_place') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-sm"
                                   placeholder="Kota/Kab">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-sm text-slate-600">
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg shadow-indigo-500/30 transition-all active:scale-[0.98]">
                        Daftarkan Sekarang
                    </button>
                </form>
            </div>
            
            <p class="text-center text-xs text-slate-400 mt-8">&copy; {{ date('Y') }} SMPN 216 JAKARTA. Sistem EduGuard KPI.</p>
        </div>
    </section>

</body>
</html>
