<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-lg font-semibold text-slate-900">Riwayat Progress</h1>
                <p class="text-sm text-slate-500">Semua submission progress skripsi Anda.</p>
            </div>
            @if (Auth::user()->isMahasiswa())
                <a href="{{ route('progress-skripsi.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Input Progres
                </a>
            @endif
        </div>
    </x-slot>

    <x-alert />

    @if ($progressList->isEmpty())
        <div class="flex flex-col items-center rounded-2xl border border-dashed border-slate-200 bg-white px-6 py-16 text-center shadow-soft">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-3xl">📄</div>
            <h3 class="mt-4 font-semibold text-slate-900">Belum ada progress</h3>
            <p class="mt-2 max-w-sm text-sm text-slate-500">Mulai kirim progress bab skripsi pertama Anda ke dosen pembimbing.</p>
            @if (Auth::user()->isMahasiswa())
                <a href="{{ route('progress-skripsi.create') }}"
                   class="auth-btn mt-6 w-auto px-6">
                    Input Progres Pertama
                </a>
            @endif
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($progressList as $progress)
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-soft transition hover:shadow-card">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Bab</p>
                            <p class="mt-0.5 font-semibold text-slate-900">{{ $progress->bab?->fullLabel() ?? '-' }}</p>
                        </div>
                        @if ($progress->status)
                            <span class="badge badge-{{ $progress->status->color() }}">{{ $progress->status->label() }}</span>
                        @endif
                    </div>

                    @if (Auth::user()->isAdmin())
                        <p class="mt-3 text-sm text-slate-600">
                            <span class="font-medium">Mahasiswa:</span> {{ $progress->mahasiswa->name }}
                        </p>
                    @endif

                    <div class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm text-slate-600">
                        @if ($progress->file_path)
                            <p class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <span class="truncate">{{ basename($progress->file_path) }}</span>
                            </p>
                        @endif
                        @if ($progress->deadline_revisi)
                            <p class="flex items-center gap-2 text-amber-700">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Deadline: {{ $progress->deadline_revisi->format('d M Y') }}
                            </p>
                        @endif
                        <p class="text-xs text-slate-400">Diperbarui {{ $progress->updated_at?->format('d M Y, H:i') }}</p>
                    </div>

                    @if ($progress->catatan_revisi)
                        <div class="mt-4 rounded-xl bg-slate-50 p-3 text-sm text-slate-600">
                            {{ $progress->catatan_revisi }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
