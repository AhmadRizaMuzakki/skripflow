<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Pilih Dosen Pembimbing</h1>
            <p class="text-sm text-slate-500">Langkah wajib sebelum menggunakan fitur bimbingan skripsi</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-3xl space-y-6">
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <div class="flex gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </span>
                <div>
                    <p class="font-semibold text-amber-900">Pilih sekali, tidak dapat diubah</p>
                    <p class="mt-1 text-sm leading-relaxed text-amber-800">
                        Pastikan pilihan Anda sudah benar. Jika Anda tidak dapat memilih sendiri, hubungi dosen pembimbing
                        atau admin agar ditugaskan melalui sistem.
                    </p>
                </div>
            </div>
        </div>

        @if ($dosens->isEmpty())
            <div class="dosen-panel">
                <div class="px-6 py-16 text-center">
                    <p class="font-medium text-slate-700">Belum ada dosen pembimbing terdaftar</p>
                    <p class="mt-1 text-sm text-slate-500">Hubungi admin agar dosen pembimbing ditambahkan ke sistem.</p>
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('dosen-pembimbing.store') }}" class="dosen-panel" x-data="{ selected: '{{ old('dosen_pembimbing_id') }}' }">
                @csrf

                <div class="dosen-panel__header">
                    <div>
                        <h2 class="font-semibold text-slate-900">Daftar Dosen Pembimbing</h2>
                        <p class="text-sm text-slate-500">{{ $dosens->count() }} dosen tersedia — pilih satu untuk melanjutkan</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-100 p-2">
                    @foreach ($dosens as $dosen)
                        <label class="flex cursor-pointer items-center gap-4 rounded-xl p-4 transition"
                               :class="selected == '{{ $dosen->id }}' ? 'bg-brand-50 ring-1 ring-brand-200' : 'hover:bg-slate-50'">
                            <input type="radio"
                                   name="dosen_pembimbing_id"
                                   value="{{ $dosen->id }}"
                                   class="h-4 w-4 border-slate-300 text-brand-600 focus:ring-brand-500"
                                   x-model="selected"
                                   @checked(old('dosen_pembimbing_id') == $dosen->id)
                                   required />
                            <div class="dosen-avatar h-11 w-11 shrink-0">
                                {{ strtoupper(substr($dosen->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-slate-900">{{ $dosen->name }}</p>
                                <p class="text-sm text-slate-500">NIDN {{ $dosen->nomor_induk }}</p>
                            </div>
                            <span class="badge badge-blue shrink-0">{{ $dosen->mahasiswa_profiles_dibimbing_count }} bimbingan</span>
                        </label>
                    @endforeach
                </div>

                <x-input-error :messages="$errors->get('dosen_pembimbing_id')" class="px-6 pb-2" />

                <div class="border-t border-slate-100 px-6 py-5">
                    <button type="submit"
                            class="auth-btn w-full sm:w-auto sm:px-8"
                            :disabled="!selected"
                            :class="{ 'opacity-50 cursor-not-allowed': !selected }">
                        Konfirmasi Pilihan Dosen
                    </button>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
