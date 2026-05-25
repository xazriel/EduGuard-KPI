@extends('layouts.app')
@section('title', 'Input Pelanggaran')
@section('page-title', 'Input Pelanggaran')
@section('page-subtitle', 'Catat pelanggaran siswa — KPI akan dihitung otomatis')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card p-8">
        <form method="POST" action="{{ route('violations.store') }}" id="violationForm" class="space-y-6">
            @csrf

            {{-- Student Search --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Siswa *</label>
                <div class="relative">
                    <input type="text" id="studentSearch" autocomplete="off"
                           placeholder="Ketik nama, NIS, atau NISN..."
                           class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-400">
                    <input type="hidden" name="student_id" id="studentId" value="{{ request('student_id') }}">
                    <div id="studentDropdown" class="absolute top-full left-0 right-0 mt-1 bg-slate-100 border border-slate-300 rounded-xl shadow-xl z-30 hidden max-h-56 overflow-y-auto"></div>
                </div>
                <div id="studentSelected" class="mt-2 hidden">
                    <div class="flex items-center gap-3 p-3 bg-indigo-500/10 border border-indigo-500/30 rounded-xl">
                        <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold text-sm" id="studentAvatar"></div>
                        <div>
                            <p class="text-sm font-medium text-white" id="studentName"></p>
                            <p class="text-xs text-slate-400" id="studentInfo"></p>
                        </div>
                        <button type="button" onclick="clearStudent()" class="ml-auto text-slate-400 hover:text-red-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Category --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Kategori *</label>
                    <select name="category" id="categorySelect" required onchange="updateSubCategories()"
                            class="w-full bg-white border border-slate-300 text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($subCategories as $catKey => $subs)
                        <option value="{{ $catKey }}" {{ old('category') === $catKey ? 'selected' : '' }}>
                            {{ ['kehadiran'=>'🕐 Kehadiran','kedisiplinan'=>'📋 Kedisiplinan','etika_sosial'=>'🤝 Etika Sosial','agresivitas'=>'💢 Agresivitas','integritas'=>'⚖️ Integritas','risiko_tinggi'=>'🚨 Risiko Tinggi'][$catKey] ?? $catKey }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Sub Kategori *</label>
                    <select name="sub_category" id="subCategorySelect" required
                            class="w-full bg-white border border-slate-300 text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Pilih kategori dulu</option>
                    </select>
                </div>
            </div>

            {{-- Severity + Date --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tingkat Keparahan *</label>
                    <select name="severity" required class="w-full bg-white border border-slate-300 text-slate-700 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="ringan" {{ old('severity') === 'ringan' ? 'selected' : '' }}>🟡 Ringan</option>
                        <option value="sedang" {{ old('severity') === 'sedang' ? 'selected' : '' }}>🟠 Sedang</option>
                        <option value="berat"  {{ old('severity') === 'berat'  ? 'selected' : '' }}>🔴 Berat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tanggal Pelanggaran *</label>
                    <input type="date" name="violation_date" value="{{ old('violation_date', date('Y-m-d')) }}" required
                           max="{{ date('Y-m-d') }}"
                           class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Kejadian</label>
                <textarea name="description" rows="3"
                          class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none placeholder-slate-400"
                          placeholder="Deskripsikan kronologi kejadian secara singkat...">{{ old('description') }}</textarea>
            </div>

            {{-- Follow Up --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tindak Lanjut</label>
                <textarea name="follow_up" rows="2"
                          class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none placeholder-slate-400"
                          placeholder="Tindakan pembinaan yang diberikan...">{{ old('follow_up') }}</textarea>
            </div>

            {{-- Info box --}}
            <div class="p-4 bg-indigo-500/10 border border-indigo-500/30 rounded-xl text-xs text-indigo-300 space-y-1">
                <p class="font-semibold">ℹ️ Proses Otomatis Setelah Submit:</p>
                <p>✅ Data tersimpan → 📄 Surat pernyataan digenerate → 📊 KPI dihitung ulang → ⚠️ Warning dicek → 📋 Log dibuat</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-indigo-500/20">
                    Simpan & Proses Otomatis
                </button>
                <a href="{{ route('violations.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors text-sm font-medium">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const subCategories = @json($subCategories);

function updateSubCategories() {
    const cat = document.getElementById('categorySelect').value;
    const sel = document.getElementById('subCategorySelect');
    sel.innerHTML = '<option value="">Pilih sub kategori</option>';
    if (cat && subCategories[cat]) {
        Object.entries(subCategories[cat]).forEach(([key, label]) => {
            const opt = document.createElement('option');
            opt.value = key;
            opt.textContent = label;
            sel.appendChild(opt);
        });
    }
}

// AJAX Student Search
let searchTimeout;
document.getElementById('studentSearch').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    if (q.length < 2) { document.getElementById('studentDropdown').classList.add('hidden'); return; }
    searchTimeout = setTimeout(() => {
        fetch(`/siswa/search?q=${encodeURIComponent(q)}`, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => {
            const dd = document.getElementById('studentDropdown');
            dd.innerHTML = '';
            if (!data.length) {
                dd.innerHTML = '<p class="px-4 py-3 text-sm text-slate-400">Tidak ditemukan</p>';
            } else {
                data.forEach(s => {
                    const d = document.createElement('div');
                    d.className = 'px-4 py-3 hover:bg-slate-200 cursor-pointer transition-colors flex items-center gap-3';
                    d.innerHTML = `<div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold text-sm flex-shrink-0">${s.full_name[0]}</div>
                        <div><p class="text-sm font-medium text-slate-700">${s.full_name}</p><p class="text-xs text-slate-400">NIS: ${s.nis} | ${s.class_name || ''}</p></div>`;
                    d.onclick = () => selectStudent(s);
                    dd.appendChild(d);
                });
            }
            dd.classList.remove('hidden');
        });
    }, 300);
});

function selectStudent(s) {
    document.getElementById('studentId').value = s.id;
    document.getElementById('studentSearch').value = '';
    document.getElementById('studentDropdown').classList.add('hidden');
    document.getElementById('studentAvatar').textContent = s.full_name[0];
    document.getElementById('studentName').textContent = s.full_name;
    document.getElementById('studentInfo').textContent = `NIS: ${s.nis} | ${s.class_name || ''}`;
    document.getElementById('studentSelected').classList.remove('hidden');
    document.getElementById('studentSearch').classList.add('hidden');
}

function clearStudent() {
    document.getElementById('studentId').value = '';
    document.getElementById('studentSelected').classList.add('hidden');
    document.getElementById('studentSearch').classList.remove('hidden');
    document.getElementById('studentSearch').value = '';
    document.getElementById('studentSearch').focus();
}

document.addEventListener('click', e => {
    if (!e.target.closest('#studentSearch') && !e.target.closest('#studentDropdown')) {
        document.getElementById('studentDropdown').classList.add('hidden');
    }
});

// Pre-fill if student_id in query
@if(request('student_id'))
fetch(`/siswa/search?q={{ request('student_id') }}`, {headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}})
.then(r=>r.json()).then(data=>{if(data.length) selectStudent(data[0]);});
@endif
</script>
@endpush
