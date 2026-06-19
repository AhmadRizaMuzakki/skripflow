<x-guest-layout
    title="Buat akun baru"
    subtitle="Daftar sebagai mahasiswa untuk mulai monitoring skripsi."
    heading="Mulai perjalanan skripsi Anda hari ini."
    tagline="Daftar, unggah progress, dan pantau milestone dengan mudah."
>
    <x-slot name="footer">
        <p class="text-sm text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="auth-link">Masuk di sini</a>
        </p>
    </x-slot>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="auth-label">Nama Lengkap</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Ahmad Fauzi"
                class="auth-input @error('name') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm" />
        </div>

        <div>
            <label for="nomor_induk" class="auth-label">Nomor Induk (NIM/NIDN)</label>
            <input
                id="nomor_induk"
                type="text"
                name="nomor_induk"
                value="{{ old('nomor_induk') }}"
                required
                placeholder="2211501001"
                class="auth-input @error('nomor_induk') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror"
            />
            <x-input-error :messages="$errors->get('nomor_induk')" class="mt-2 text-sm" />
        </div>

        <div>
            <label for="email" class="auth-label">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                placeholder="nama@email.com"
                class="auth-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm" />
        </div>

        <div>
            <label for="password" class="auth-label">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
                class="auth-input @error('password') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm" />
        </div>

        <div>
            <label for="password_confirmation" class="auth-label">Konfirmasi Password</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Ulangi password"
                class="auth-input"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm" />
        </div>

        <button type="submit" class="auth-btn">
            Daftar
        </button>
    </form>
</x-guest-layout>
