<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Mahasiswa Bimbingan</h1>
            <p class="text-sm text-slate-500">Kelola dan pantau mahasiswa di bawah bimbingan Anda</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-6xl space-y-6">
        <div class="grid gap-4 sm:grid-cols-3">
            <x-dosen.stat-card :value="$stats['total']" label="Total Bimbingan" accent="brand" icon="users" />
            <x-dosen.stat-card :value="$stats['aktif']" label="Sedang Aktif" accent="emerald" icon="check" />
            <x-dosen.stat-card :value="$stats['kritis']" label="Perlu Perhatian" accent="amber" icon="alert" />
        </div>

        <div class="dosen-panel">
            <div class="dosen-panel__header">
                <div>
                    <h2 class="font-semibold text-slate-900">Daftar Mahasiswa</h2>
                    <p class="mt-0.5 text-sm text-slate-500">{{ $monitoringRows->count() }} mahasiswa di bawah bimbingan Anda</p>
                </div>
                @if ($canAddMahasiswa)
                    @if ($unassignedCount > 0)
                        <a href="{{ route('mahasiswa-bimbingan.create') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Mahasiswa
                            <span class="rounded-full bg-white/20 px-2 py-0.5 text-xs">{{ $unassignedCount }}</span>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-400"
                              title="Tidak ada mahasiswa tanpa dosen pembimbing">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Mahasiswa
                        </span>
                    @endif
                @endif
            </div>

            @if ($monitoringRows->isEmpty())
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <p class="font-medium text-slate-700">Belum ada mahasiswa bimbingan</p>
                    <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">
                        @if ($canAddMahasiswa && $unassignedCount > 0)
                            Tambahkan mahasiswa yang sudah terdaftar dan belum memiliki dosen pembimbing.
                        @else
                            Mahasiswa akan muncul setelah Anda menambahkan mahasiswa yang belum punya dosen, atau setelah admin menugaskan bimbingan.
                        @endif
                    </p>
                    @if ($canAddMahasiswa && $unassignedCount > 0)
                        <a href="{{ route('mahasiswa-bimbingan.create') }}" class="auth-btn mt-6 w-auto px-6">Tambah Mahasiswa</a>
                    @endif
                </div>
            @else
                <div class="grid gap-4 p-5 sm:grid-cols-2 lg:p-6 xl:grid-cols-3">
                    @foreach ($monitoringRows as $row)
                        @php $profile = $row['profile']; @endphp
                        <article @class([
                            'flex flex-col rounded-2xl border bg-white p-5 transition hover:shadow-card',
                            'border-amber-200 bg-amber-50/20 ring-1 ring-amber-100' => $row['is_kritis'],
                            'border-slate-100 shadow-sm' => ! $row['is_kritis'],
                        ])>
                            <div class="flex items-start gap-3">
                                <div class="dosen-avatar h-12 w-12 rounded-2xl text-base">
                                    {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h3 class="truncate font-semibold text-slate-900">{{ $profile->user->name }}</h3>
                                            <p class="text-xs font-medium text-slate-500">{{ $profile->user->nomor_induk }}</p>
                                        </div>
                                        @if ($row['is_kritis'])
                                            <span class="badge badge-amber shrink-0">Kritis</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <p class="mt-3 line-clamp-2 min-h-[2.5rem] text-sm leading-relaxed text-slate-600">
                                {{ $profile->judul_skripsi ?? 'Judul skripsi belum diisi' }}
                            </p>

                            <div class="mt-4 rounded-xl bg-slate-50 p-3">
                                <div class="mb-1.5 flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-500">Progress Skripsi</span>
                                    <span class="font-bold text-brand-700">{{ $profile->total_progress }}%</span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-slate-200/80">
                                    <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-600 transition-all"
                                         style="width: {{ max($profile->total_progress, 2) }}%"></div>
                                </div>
                                <p class="mt-2 text-xs font-medium text-slate-600">{{ $row['progres_label'] }}</p>
                            </div>

                            <a href="{{ route('mahasiswa-bimbingan.show', $profile) }}"
                               class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">
                                Lihat Detail
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
