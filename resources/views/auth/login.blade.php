<x-guest-layout
    title="Selamat datang kembali"
    subtitle="Masuk ke akun Anda untuk melanjutkan."
    heading="Pantau skripsi, milestone, dan bimbingan — semua dalam satu tempat."
    tagline="Platform monitoring progress skripsi yang simpel dan terstruktur."
>
    <x-slot name="footer">
        <p class="text-sm text-slate-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="auth-link">Daftar sekarang</a>
        </p>
    </x-slot>

    <x-auth-session-status class="mb-4 rounded-xl bg-green-50 px-4 py-3 text-sm text-green-700" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="auth-label">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="nama@email.com"
                class="auth-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm" />
        </div>

        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label for="password" class="auth-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">
                        Lupa password?
                    </a>
                @endif
            </div>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="auth-input @error('password') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm" />
        </div>

        <label for="remember_me" class="flex cursor-pointer items-center gap-2.5">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500/30"
            />
            <span class="text-sm text-slate-600">Ingat saya</span>
        </label>

        <button type="submit" class="auth-btn">
            Masuk
        </button>
    </form>
</x-guest-layout>
