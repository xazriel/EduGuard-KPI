@inject('warningService', 'App\Services\EarlyWarningService')
@php
    $alerts = $warningService->getSchoolAlerts();
@endphp
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Dashboard Pelanggaran Siswa</title>
    <meta name="description" content="@yield('meta_description', 'Sistem Business Intelligence monitoring perilaku siswa sekolah')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.min.js" defer></script>

    <style>
        :root { --sidebar-width: 260px; }
        body { font-family: 'Inter', sans-serif; }
        .sidebar { width: var(--sidebar-width); }
        .main-content { margin-left: var(--sidebar-width); }
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); position: fixed; z-index: 50; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
        .glass-card { background: rgba(255,255,255,0.8); border: 1px solid rgba(226,232,240,0.8); backdrop-filter: blur(12px); border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -2px rgba(0, 0, 0, 0.02), 0 10px 15px -3px rgba(0, 0, 0, 0.03); }
        .fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .nav-link.active { background: linear-gradient(135deg,rgba(99,102,241,.2),rgba(139,92,246,.2)); border-left: 3px solid #6366f1; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

<div id="sidebarOverlay" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed top-0 left-0 h-full bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 z-50">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo-smpn216.png') }}" alt="Logo SMPN 216" class="w-10 h-10 object-contain">
                <div>
                    <p class="font-bold text-slate-800 text-sm">SMPN 216 JAKARTA</p>
                    <p class="text-xs text-indigo-400 font-semibold">Dashboard Monitoring Pelanggaran Siswa</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest px-3 mb-2">Utama</p>
            <a href="{{ route('dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-slate-100 {{ request()->routeIs('dashboard') ? 'active text-indigo-400' : 'text-gray-500 hover:text-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard Sekolah
            </a>
            <a href="{{ route('classes.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-slate-100 {{ request()->routeIs('classes.*') ? 'active text-indigo-400' : 'text-gray-500 hover:text-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Dashboard Kelas
            </a>

            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest px-3 mb-2 mt-4">Data</p>
            <a href="{{ route('students.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-slate-100 {{ request()->routeIs('students.*') ? 'active text-indigo-400' : 'text-gray-500 hover:text-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Data Siswa
            </a>
            <a href="{{ route('violations.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-slate-100 {{ request()->routeIs('violations.index') ? 'active text-indigo-400' : 'text-gray-500 hover:text-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Pelanggaran
            </a>
            <a href="{{ route('violations.create') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-slate-100 {{ request()->routeIs('violations.create') ? 'active text-indigo-400' : 'text-gray-500 hover:text-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Input Pelanggaran
            </a>

            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest px-3 mb-2 mt-4">Export</p>
            <a href="{{ route('export.violations.pdf') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-slate-100 text-gray-500 hover:text-slate-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Export PDF
            </a>
            <a href="{{ route('export.violations.excel') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-slate-100 text-gray-500 hover:text-slate-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel
            </a>
        </nav>

        <div class="p-4 border-t border-slate-200">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-100/50">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-sm font-bold text-slate-800 flex-shrink-0">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-800 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400">Guru BK</p>
                </div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="text-slate-400 hover:text-red-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content flex-1 min-h-screen">
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-slate-800 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                @hasSection('back-url')
                <a href="@yield('back-url')" class="text-slate-400 hover:text-slate-700 transition-all hover:scale-110 flex items-center justify-center w-8 h-8 rounded-lg hover:bg-slate-100" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                @endif
                <div>
                    <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-400">@yield('page-subtitle', 'Sistem Monitoring Pelanggaran Siswa')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                {{-- Notification Dropdown --}}
                <div class="relative">
                    <button onclick="toggleNotifications()" class="relative p-2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if(count($alerts) > 0)
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                        @endif
                    </button>
                    
                    <div id="notificationsDropdown" class="absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded-xl shadow-xl z-50 hidden overflow-hidden">
                        <div class="border-b border-slate-100 bg-slate-50/80 flex items-center justify-between" style="padding: 10px 14px;">
                            <span class="text-slate-500 uppercase tracking-wider" style="font-size: 10px; font-weight: 700;">Peringatan Dini</span>
                            @if(count($alerts) > 0)
                            <span class="font-bold bg-red-500/10 text-red-500 rounded-full" style="font-size: 10px; padding: 2px 6px;">{{ count($alerts) }} Peringatan</span>
                            @endif
                        </div>
                        <div class="overflow-y-auto divide-y divide-slate-100" style="max-height: 280px;">
                            @forelse($alerts as $alert)
                            <a href="{{ isset($alert['student']) ? route('students.show', $alert['student']) : '#' }}" 
                               class="block hover:bg-slate-50 transition-colors border-l-4 {{ $alert['type'] === 'danger' ? 'border-red-500' : 'border-amber-500' }}"
                               style="padding: 10px 14px;">
                                <div class="flex flex-col" style="gap: 4px;">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-700 truncate max-w-[170px]" style="font-size: 13px; font-weight: 700; line-height: 1.2;">{{ $alert['student']?->full_name ?? 'Siswa' }}</span>
                                        <span class="bg-slate-100 text-slate-500 rounded" style="font-size: 10.5px; font-weight: 600; padding: 1px 5px; line-height: 1;">{{ $alert['student']?->schoolClass?->class_name ?? '-' }}</span>
                                    </div>
                                    <span class="text-slate-500" style="font-size: 11px; font-weight: 500; line-height: 1.2;">{{ $alert['message'] }}</span>
                                </div>
                            </a>
                            @empty
                            <div class="text-center text-slate-400" style="padding: 16px; font-size: 11px;">
                                Tidak ada peringatan dini.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <a href="{{ route('public.lookup') }}" target="_blank" class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors border border-indigo-500/30 px-3 py-1.5 rounded-lg hover:bg-indigo-500/10">
                    🔍 Portal Siswa
                </a>
                <span class="text-xs text-slate-300 hidden sm:block">{{ now()->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
            </div>
        </header>

        @if(session('success'))
        <div class="mx-6 mt-4 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm flex items-start gap-3 fade-in">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mx-6 mt-4 p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm fade-in">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="p-6 fade-in">@yield('content')</div>
    </main>
</div>

<script>
    function toggleSidebar(){
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('hidden');
    }
    function toggleNotifications() {
        const dd = document.getElementById('notificationsDropdown');
        dd.classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        const bell = e.target.closest('button[onclick="toggleNotifications()"]');
        const dd = e.target.closest('#notificationsDropdown');
        if (!bell && !dd) {
            const dropdown = document.getElementById('notificationsDropdown');
            if (dropdown) dropdown.classList.add('hidden');
        }
    });
</script>
@stack('scripts')
</body>
</html>
