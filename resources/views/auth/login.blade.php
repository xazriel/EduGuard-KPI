<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Masuk Guru BK</h2>
        <p class="text-sm text-slate-500 mt-1">Silakan masuk menggunakan akun Guru BK Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@pelanggaransiswa.sch.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-400" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <div class="flex justify-between items-center">
                <x-input-label for="password" value="Kata Sandi" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-400" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded bg-slate-50 border-slate-200 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-gray-900 focus:ring-2 w-4 h-4 transition duration-150" name="remember">
                <span class="ms-2 text-sm text-slate-500 hover:text-slate-600 transition-colors select-none">Ingat saya</span>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button>
                Masuk ke Dashboard
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
