<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Ringkasan</h1>
            <p class="text-sm text-slate-500">Pantau bimbingan skripsi Anda dengan mudah</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-5xl">
        <x-dosen.subnav active="dashboard" :pending-count="$pendingCount" />

        {{-- Welcome --}}
        <div class="dosen-hero mb-6">
            <div class="flex items-start gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-brand-600 text-xl font-bold text-white shadow-lg shadow-brand-600/20">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-brand-600">Halo, Dosen Pembimbing 👋</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                    <p class="mt-1 text-sm text-slate-500">Kelola dan pantau progress skripsi mahasiswa bimbingan Anda.</p>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="mb-6 grid gap-4 sm:grid-cols-3">
            <div class="dosen-stat-card">
                <div class="dosen-stat-icon bg-slate-100 text-slate-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
                    <p class="text-sm text-slate-500">Total Bimbingan</p>
                </div>
            </div>
            <div class="dosen-stat-card">
                <div class="dosen-stat-icon bg-brand-50 text-brand-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-brand-700">{{ $stats['aktif'] }}</p>
                    <p class="text-sm text-slate-500">Sedang Aktif</p>
                </div>
            </div>
            <div class="dosen-stat-card">
                <div class="dosen-stat-icon bg-red-50 text-red-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['kritis'] }}</p>
                    <p class="text-sm text-slate-500">Perlu Perhatian</p>
                </div>
            </div>
        </div>

        {{-- Perlu perhatian --}}
        @if ($attentionItems->isNotEmpty())
            <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50/60 p-5 shadow-soft">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900">Perlu Ditinjau</h3>
                    <a href="{{ route('laporan-progres.index', ['status' => 'menunggu']) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
                        Lihat semua →
                    </a>
                </div>
                <div class="space-y-3">
                    @foreach ($attentionItems->take(3) as $item)
                        <a href="{{ route('mahasiswa-bimbingan.show', $item['profile']) }}"
                           class="flex items-center gap-4 rounded-xl bg-white p-4 transition hover:shadow-md">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-100 text-sm font-bold text-brand-700">
                                {{ strtoupper(substr($item['profile']->user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-slate-900">{{ $item['profile']->user->name }}</p>
                                <p class="text-xs text-slate-500">
                                    @if ($item['pending'])
                                        Menunggu review — {{ $item['pending']->bab->label() }}
                                    @elseif ($item['is_kritis'])
                                        Deadline revisi mendekat
                                    @endif
                                </p>
                            </div>
                            @if ($item['pending'])
                                <span class="badge badge-blue">Baru</span>
                            @elseif ($item['is_kritis'])
                                <span class="badge badge-amber">Kritis</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Quick menu --}}
        <div class="grid gap-4 sm:grid-cols-2">
            <a href="{{ route('mahasiswa-bimbingan.index') }}" class="dosen-menu-card group">
                <div class="dosen-menu-icon bg-violet-50 text-violet-600 group-hover:bg-violet-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-900">Mahasiswa Bimbingan</p>
                    <p class="mt-0.5 text-sm text-slate-500">Lihat daftar & detail progress tiap mahasiswa</p>
                </div>
            </a>
            <a href="{{ route('laporan-progres.index') }}" class="dosen-menu-card group">
                <div class="dosen-menu-icon bg-sky-50 text-sky-600 group-hover:bg-sky-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-slate-900">Laporan Progres</p>
                        @if ($pendingCount > 0)
                            <span class="rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">{{ $pendingCount }}</span>
                        @endif
                    </div>
                    <p class="mt-0.5 text-sm text-slate-500">Inbox pengajuan yang perlu divalidasi</p>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
