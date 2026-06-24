<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Dashboard</h1>
            <p class="text-sm text-slate-500">Pantau milestone dan progress mahasiswa bimbingan Anda</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-6xl space-y-6">
        {{-- Hero --}}
        <div class="dosen-dashboard-hero">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-4">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-brand-600 text-2xl font-bold text-white shadow-lg shadow-brand-600/25">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-brand-600">Selamat datang kembali 👋</p>
                        <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $user->name }}</h2>
                        <p class="mt-2 max-w-xl text-sm leading-relaxed text-slate-600">
                            Pantau progress skripsi, tinjau laporan, dan update milestone mahasiswa bimbingan Anda dari satu tempat.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 lg:justify-end">
                    @if ($pendingCount > 0)
                        <a href="{{ route('laporan-progres.index', ['status' => 'menunggu']) }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                            <span class="flex h-2 w-2 rounded-full bg-white animate-pulse"></span>
                            {{ $pendingCount }} menunggu review
                        </a>
                    @endif
                    <a href="{{ route('mahasiswa-bimbingan.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        Lihat Mahasiswa
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-dosen.stat-card :value="$stats['total']" label="Total Bimbingan" accent="slate" icon="users" />
            <x-dosen.stat-card :value="$stats['aktif']" label="Sedang Aktif" accent="emerald" icon="check" />
            <x-dosen.stat-card :value="$stats['kritis']" label="Perlu Perhatian" accent="amber" icon="alert" />
            <x-dosen.stat-card :value="$pendingCount" label="Menunggu Review" accent="violet" icon="inbox" />
        </div>

        {{-- Filter --}}
        <x-dosen.dashboard-filters :profiles="$profiles" :filters="$filters" />

        {{-- Middle section --}}
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                @if ($attentionItems->isNotEmpty())
                    <div class="dosen-panel overflow-hidden border-amber-200/80">
                        <div class="dosen-panel__header bg-amber-50/80">
                            <div class="flex items-center gap-2">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <h3 class="font-semibold text-slate-900">Perlu Ditinjau</h3>
                            </div>
                            <a href="{{ route('laporan-progres.index', ['status' => 'menunggu']) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
                                Lihat semua →
                            </a>
                        </div>
                        <div class="space-y-3 bg-gradient-to-b from-amber-50/40 to-white p-5">
                            @foreach ($attentionItems->take(3) as $item)
                                <a href="{{ route('mahasiswa-bimbingan.show', $item['profile']) }}" class="dosen-attention-item">
                                    <div class="dosen-avatar">
                                        {{ strtoupper(substr($item['profile']->user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-slate-900">{{ $item['profile']->user->name }}</p>
                                        <p class="mt-0.5 text-xs text-slate-500">
                                            @if ($item['pending'])
                                                Menunggu review — {{ $item['pending']->bab->label() }}
                                            @elseif ($item['is_kritis'])
                                                Deadline revisi mendekat
                                            @endif
                                        </p>
                                    </div>
                                    @if ($item['pending'])
                                        <span class="badge badge-blue shrink-0">Baru</span>
                                    @elseif ($item['is_kritis'])
                                        <span class="badge badge-amber shrink-0">Kritis</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="dosen-panel">
                        <div class="flex flex-col items-center justify-center px-6 py-12 text-center">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="font-medium text-slate-800">Semua sudah ditinjau</p>
                            <p class="mt-1 text-sm text-slate-500">Tidak ada pengajuan yang menunggu tindakan Anda saat ini.</p>
                        </div>
                    </div>
                @endif
            </div>

            <x-dosen.quick-actions :pending-count="$pendingCount" />
        </div>

        {{-- Milestone table --}}
        <div class="dosen-panel">
            <div class="dosen-panel__header">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Data Milestone</h2>
                    <p class="mt-0.5 text-xs text-slate-500">
                        @if (collect($filters)->filter()->isNotEmpty())
                            Menampilkan {{ $milestones->count() }} hasil sesuai filter
                        @else
                            Ringkasan milestone mahasiswa bimbingan Anda
                        @endif
                    </p>
                </div>
                <a href="{{ route('milestones.index', array_filter($filters)) }}"
                   class="inline-flex items-center gap-1 text-sm font-medium text-brand-600 hover:text-brand-700">
                    Lihat semua
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <div class="p-5 sm:p-6">
                <x-dosen.milestone-table
                    :milestones="$milestones"
                    :filters="$filters"
                    :show-actions="Auth::user()->isDosen()"
                />
            </div>
        </div>
    </div>
</x-app-layout>
