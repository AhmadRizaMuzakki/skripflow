<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Detail Mahasiswa</h1>
            <p class="text-sm text-slate-500">Review dan validasi progress skripsi</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-4xl">
        <x-dosen.breadcrumb :items="[
            ['label' => 'Ringkasan', 'url' => route('dashboard')],
            ['label' => 'Mahasiswa', 'url' => route('mahasiswa-bimbingan.index')],
            ['label' => $profile->user->name],
        ]" />

        {{-- Student header --}}
        <div class="mb-6 flex items-start gap-5 rounded-2xl border border-slate-100 bg-white p-6 shadow-soft">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-brand-600 text-2xl font-bold text-white shadow-sm">
                {{ strtoupper(substr($profile->user->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <h2 class="text-xl font-bold text-slate-900">{{ $profile->user->name }}</h2>
                <p class="text-sm text-slate-500">NIM: {{ $profile->user->nomor_induk }}</p>
                <p class="mt-3 break-words text-sm leading-relaxed text-slate-700">
                    <span class="font-medium text-slate-800">Judul:</span>
                    {{ $profile->judul_skripsi ?? 'Belum diisi' }}
                </p>
                <div class="mt-3 flex items-center gap-3">
                    <div class="h-2 flex-1 max-w-xs overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-brand-500" style="width: {{ $profile->total_progress }}%"></div>
                    </div>
                    <span class="text-sm font-bold text-brand-700">{{ $profile->total_progress }}%</span>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-5">
            {{-- Pending action --}}
            <div class="min-w-0 lg:col-span-3">
                @if ($pendingProgress)
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-soft">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <h3 class="font-semibold text-slate-900">Menunggu Tindakan Anda</h3>
                        </div>

                        <div class="mt-4 space-y-3 rounded-xl bg-white p-4 text-sm">
                            <div class="grid gap-1 border-b border-slate-100 pb-3 sm:grid-cols-[auto,1fr] sm:gap-4 sm:items-start">
                                <span class="text-slate-500">Tanggal Upload</span>
                                <span class="font-medium text-slate-800 sm:text-right">{{ $pendingProgress->updated_at?->translatedFormat('d F Y, H:i') }} WIB</span>
                            </div>
                            <div class="grid gap-1 border-b border-slate-100 pb-3 sm:grid-cols-[auto,1fr] sm:gap-4 sm:items-start">
                                <span class="text-slate-500">Tahapan</span>
                                <span class="break-words font-medium text-slate-800 sm:text-right">{{ $pendingProgress->bab->fullLabel() }}</span>
                            </div>
                            @if ($pendingProgress->file_path)
                                <div class="grid gap-1 sm:grid-cols-[auto,1fr] sm:gap-4 sm:items-start">
                                    <span class="text-slate-500">Berkas</span>
                                    <a href="{{ route('progress-skripsi.file', $pendingProgress) }}" target="_blank" rel="noopener" class="auth-link break-all font-medium sm:text-right">
                                        {{ basename($pendingProgress->file_path) }} ↗
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if ($pendingProgress->catatan_revisi)
                            <div class="mt-4 rounded-xl border border-slate-100 bg-white p-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Catatan Mahasiswa</p>
                                <p class="mt-2 break-words text-sm leading-relaxed text-slate-700">{{ $pendingProgress->catatan_revisi }}</p>
                            </div>
                        @endif

                        <div class="mt-5" x-data="{ showRevise: false }">
                            <div class="flex flex-wrap gap-3">
                                <button type="button" @click="showRevise = !showRevise"
                                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                    Kembalikan / Revisi
                                </button>
                                <form method="POST" action="{{ route('progress-skripsi.approve', $pendingProgress) }}"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menyetujui (ACC) {{ $pendingProgress->bab->fullLabel() }}?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        Setujui / ACC
                                    </button>
                                </form>
                            </div>

                            <div x-show="showRevise" x-cloak x-transition class="mt-4 rounded-xl border border-slate-200 bg-white p-5">
                                <p class="mb-3 text-sm font-medium text-slate-800">Form Revisi</p>
                                <form method="POST" action="{{ route('progress-skripsi.revise', $pendingProgress) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="catatan_revisi" class="auth-label">Catatan untuk Mahasiswa</label>
                                        <textarea id="catatan_revisi" name="catatan_revisi" rows="3" class="auth-input" required placeholder="Jelaskan bagian yang perlu diperbaiki...">{{ old('catatan_revisi') }}</textarea>
                                        <x-input-error :messages="$errors->get('catatan_revisi')" />
                                    </div>
                                    <div>
                                        <label for="deadline_revisi" class="auth-label">Deadline Revisi</label>
                                        <input id="deadline_revisi" name="deadline_revisi" type="date" class="auth-input" value="{{ old('deadline_revisi') }}" required />
                                        <x-input-error :messages="$errors->get('deadline_revisi')" />
                                    </div>
                                    <button type="submit" class="auth-btn w-auto px-6">Kirim Revisi</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center">
                        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <p class="font-medium text-slate-700">Tidak ada pengajuan baru</p>
                        <p class="mt-1 text-sm text-slate-500">Semua progress sudah ditinjau.</p>
                    </div>
                @endif
            </div>

            {{-- Riwayat ACC --}}
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-soft">
                    <h3 class="font-semibold text-slate-900">Riwayat ACC</h3>
                    @if ($approvedHistory->isEmpty())
                        <p class="mt-4 text-sm text-slate-500">Belum ada bab disetujui.</p>
                    @else
                        <div class="mt-4 space-y-3">
                            @foreach ($approvedHistory as $progress)
                                <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-3">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">{{ $progress->bab->label() }}</p>
                                            <p class="mt-0.5 text-xs text-slate-500">{{ $progress->updated_at?->translatedFormat('d M Y') }}</p>
                                        </div>
                                        <span class="badge badge-emerald">ACC</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
