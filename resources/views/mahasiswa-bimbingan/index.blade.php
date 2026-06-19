<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Mahasiswa Bimbingan</h1>
            <p class="text-sm text-slate-500">{{ $monitoringRows->count() }} mahasiswa di bawah bimbingan Anda</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-5xl">
        <x-dosen.subnav active="mahasiswa" :pending-count="$pendingCount" />

        @if ($monitoringRows->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-12 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <p class="font-medium text-slate-700">Belum ada mahasiswa bimbingan</p>
                <p class="mt-1 text-sm text-slate-500">Mahasiswa akan muncul setelah profil bimbingan ditetapkan.</p>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($monitoringRows as $row)
                    @php $profile = $row['profile']; @endphp
                    <div @class([
                        'rounded-2xl border bg-white p-5 shadow-soft transition hover:shadow-card',
                        'border-red-200 ring-1 ring-red-100' => $row['is_kritis'],
                        'border-slate-100' => ! $row['is_kritis'],
                    ])>
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand-600 text-lg font-bold text-white shadow-sm">
                                {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $profile->user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $profile->user->nomor_induk }}</p>
                                    </div>
                                    @if ($row['is_kritis'])
                                        <span class="badge badge-amber shrink-0">Kritis</span>
                                    @endif
                                </div>
                                <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $profile->judul_skripsi ?? 'Judul belum diisi' }}</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="mb-1 flex justify-between text-xs">
                                <span class="text-slate-500">Progress</span>
                                <span class="font-semibold text-brand-700">{{ $profile->total_progress }}%</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-brand-600" style="width: {{ $profile->total_progress }}%"></div>
                            </div>
                            <p class="mt-2 text-xs font-medium text-slate-600">{{ $row['progres_label'] }}</p>
                        </div>

                        <a href="{{ route('mahasiswa-bimbingan.show', $profile) }}"
                           class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl border border-brand-200 bg-brand-50 py-2.5 text-sm font-semibold text-brand-700 transition hover:bg-brand-100">
                            Lihat Detail
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
