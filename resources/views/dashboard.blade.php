<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Dashboard</h1>
            <p class="text-sm text-slate-500">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>
    </x-slot>

    @php
        $user = Auth::user();
    @endphp

    {{-- Stats cards --}}
    <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Role</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold capitalize text-slate-900">{{ $user->getRoleNames()->first() ?? '-' }}</p>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Nomor Induk</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-900">{{ $user->nomor_induk }}</p>
        </div>

        @if ($user->isMahasiswa() && $user->mahasiswaProfile)
            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-soft">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Progress Skripsi</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold text-slate-900">{{ $user->mahasiswaProfile->total_progress }}%</p>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-soft sm:col-span-2 xl:col-span-1">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Judul Skripsi</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 line-clamp-2 text-sm font-medium text-slate-900">{{ $user->mahasiswaProfile->judul_skripsi ?? '-' }}</p>
            </div>
        @else
            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-soft sm:col-span-2">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Status</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-50 text-sky-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-lg font-semibold text-slate-900">Anda berhasil masuk ke sistem.</p>
                <p class="mt-1 text-sm text-slate-500">Gunakan menu di sidebar untuk navigasi.</p>
            </div>
        @endif
    </div>

    {{-- Welcome card --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-soft">
        <h2 class="text-base font-semibold text-slate-900">Ringkasan</h2>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">
            @if ($user->isMahasiswa())
                Pantau progress skripsi, milestone seminar, dan catatan revisi dari dosen pembimbing Anda.
            @elseif ($user->isDosen())
                Kelola mahasiswa bimbingan dan berikan feedback progress skripsi.
            @else
                Kelola seluruh data skripsi, pengguna, dan sistem dari dashboard ini.
            @endif
        </p>
    </div>
</x-app-layout>
