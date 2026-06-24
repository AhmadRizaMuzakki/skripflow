@props(['dosen' => null])

<div class="space-y-5">
    <div>
        <label for="name" class="auth-label">Nama Lengkap</label>
        <input id="name" type="text" name="name" value="{{ old('name', $dosen?->name) }}" required
               placeholder="Dr. Budi Santoso, M.Kom."
               class="auth-input @error('name') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <label for="nomor_induk" class="auth-label">NIDN</label>
        <input id="nomor_induk" type="text" name="nomor_induk" value="{{ old('nomor_induk', $dosen?->nomor_induk) }}" required
               placeholder="198001012010011001"
               class="auth-input @error('nomor_induk') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror" />
        <x-input-error :messages="$errors->get('nomor_induk')" class="mt-2" />
    </div>

    <div>
        <label for="email" class="auth-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $dosen?->email) }}" required
               placeholder="dosen@universitas.ac.id"
               class="auth-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <label for="password" class="auth-label">
            Password
            @if ($dosen)
                <span class="font-normal text-slate-500">(kosongkan jika tidak diubah)</span>
            @endif
        </label>
        <input id="password" type="password" name="password"
               @unless($dosen) required @endunless
               autocomplete="new-password"
               class="auth-input @error('password') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div>
        <label for="password_confirmation" class="auth-label">Konfirmasi Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation"
               @unless($dosen) required @endunless
               autocomplete="new-password"
               class="auth-input" />
    </div>
</div>
