<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Tambah Mahasiswa Bimbingan</h1>
            <p class="text-sm text-slate-500">Pilih mahasiswa yang belum memiliki dosen pembimbing</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-3xl space-y-4">
        <a href="{{ route('mahasiswa-bimbingan.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-brand-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke daftar mahasiswa
        </a>

        <div class="dosen-panel">
            <div class="dosen-panel__header">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="font-semibold text-slate-900">Mahasiswa Belum Punya Dosen</h2>
                        <p class="text-sm text-slate-500">{{ $unassigned->count() }} mahasiswa tersedia</p>
                    </div>
                </div>
            </div>

            @if ($unassigned->isEmpty())
                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="font-medium text-slate-700">Tidak ada mahasiswa yang bisa ditambahkan</p>
                    <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                        Semua mahasiswa sudah memiliki dosen pembimbing. Mahasiswa baru harus mendaftar sendiri di sistem terlebih dahulu.
                    </p>
                    <a href="{{ route('mahasiswa-bimbingan.index') }}"
                       class="mt-6 inline-flex items-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Kembali
                    </a>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($unassigned as $profile)
                        <div class="flex flex-wrap items-center gap-4 px-5 py-4 transition hover:bg-slate-50/80 sm:px-6">
                            <div class="dosen-avatar h-11 w-11">
                                {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-slate-900">{{ $profile->user->name }}</p>
                                <p class="text-sm text-slate-500">{{ $profile->user->nomor_induk }} · {{ $profile->user->email }}</p>
                                @if ($profile->judul_skripsi)
                                    <p class="mt-1 line-clamp-1 text-xs text-slate-400">{{ $profile->judul_skripsi }}</p>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('mahasiswa-bimbingan.assign', $profile->id) }}">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Tambahkan
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
