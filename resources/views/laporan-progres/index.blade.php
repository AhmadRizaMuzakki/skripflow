<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Laporan Progres</h1>
            <p class="text-sm text-slate-500">Inbox pengumpulan laporan mahasiswa</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-4xl">
        {{-- Filter tabs --}}
        <div class="mb-6 flex flex-wrap gap-2">
            @foreach (['semua' => 'Semua', 'menunggu' => 'Menunggu', 'selesai' => 'Selesai'] as $value => $label)
                <a href="{{ route('laporan-progres.index', ['status' => $value]) }}"
                   @class([
                       'inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium transition',
                       'bg-brand-600 text-white shadow-sm' => $filter === $value,
                       'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' => $filter !== $value,
                   ])>
                    {{ $label }}
                    <span @class([
                        'rounded-full px-2 py-0.5 text-xs font-bold',
                        'bg-white/20 text-white' => $filter === $value,
                        'bg-slate-100 text-slate-600' => $filter !== $value,
                    ])>{{ $filterCounts[$value] }}</span>
                </a>
            @endforeach
        </div>

        @if ($submissions->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-12 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <p class="font-medium text-slate-700">Tidak ada laporan</p>
                <p class="mt-1 text-sm text-slate-500">Belum ada pengajuan untuk filter ini.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($submissions as $submission)
                    @php
                        $isWaiting = $submission->status->value === 'bimbingan';
                        $profile = $submission->mahasiswa->mahasiswaProfile;
                    @endphp
                    <div @class([
                        'rounded-2xl border bg-white p-5 shadow-soft',
                        'border-blue-200 ring-1 ring-blue-50' => $isWaiting,
                        'border-slate-100' => ! $isWaiting,
                    ])>
                        <div class="flex items-start gap-4">
                            <div @class([
                                'flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-sm font-bold',
                                'bg-blue-100 text-blue-700' => $isWaiting,
                                'bg-emerald-100 text-emerald-700' => ! $isWaiting,
                            ])>
                                {{ strtoupper(substr($submission->mahasiswa->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span @class(['badge', 'badge-blue' => $isWaiting, 'badge-emerald' => ! $isWaiting])>
                                        {{ $isWaiting ? 'Menunggu' : 'Selesai' }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        {{ $submission->updated_at?->translatedFormat('d F Y, H:i') }} WIB
                                    </span>
                                </div>
                                <p class="mt-2 font-semibold text-slate-900">{{ $submission->mahasiswa->name }}</p>
                                <p class="text-sm text-slate-600">{{ $submission->bab->fullLabel() }}</p>
                                @if ($submission->file_path)
                                    <a href="{{ route('progress-skripsi.file', $submission) }}" target="_blank" rel="noopener"
                                       class="mt-2 inline-flex items-center gap-1 text-sm text-brand-600 hover:text-brand-700">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ basename($submission->file_path) }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if ($isWaiting && $profile && auth()->user()->supervises($profile))
                            <a href="{{ route('mahasiswa-bimbingan.show', $profile) }}"
                               class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700 sm:w-auto sm:px-6">
                                Periksa & Validasi
                            </a>
                        @elseif (! $isWaiting)
                            <p class="mt-3 text-sm font-medium text-emerald-600">✓ Telah Disetujui / ACC</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
