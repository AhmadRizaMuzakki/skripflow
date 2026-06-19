<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Input Progres</h1>
            <p class="text-sm text-slate-500">Kirim update progress skripsi ke dosen pembimbing Anda.</p>
        </div>
    </x-slot>

    <x-alert />

    {{-- Welcome banner --}}
    <div class="progress-hero mb-6 overflow-hidden rounded-2xl border border-brand-200/50 shadow-card">
        <div class="progress-hero-bg px-6 py-6 sm:px-8 sm:py-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white shadow-sm text-2xl">
                        📝
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xl font-bold text-white sm:text-2xl">
                            Halo, {{ Auth::user()->name }}!
                        </h2>
                        <p class="mt-1.5 text-sm leading-relaxed text-white sm:text-base">
                            @if ($profile?->judul_skripsi)
                                {{ Str::limit($profile->judul_skripsi, 100) }}
                            @else
                                Yuk update progress skripsi Anda hari ini.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex shrink-0 gap-3 sm:gap-4">
                    <div class="progress-hero-stat">
                        <p class="text-2xl font-bold text-brand-700">{{ $approvedCount }}/5</p>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">Bab disetujui</p>
                    </div>
                    <div class="progress-hero-stat">
                        <p class="text-2xl font-bold text-brand-700">{{ $profile?->total_progress ?? 0 }}%</p>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">Progress total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-5">
        {{-- Sidebar: status bab --}}
        <div class="space-y-4 lg:col-span-2">
            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-soft">
                <h3 class="flex items-center gap-2 font-semibold text-slate-900">
                    <svg class="h-5 w-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Status Bab Anda
                </h3>
                <p class="mt-1 text-sm text-slate-500">Ringkasan progress per bab skripsi.</p>

                <div class="mt-4 space-y-2">
                    @foreach ($babOptions as $bab)
                        @php
                            $progress = $existingProgress->get($bab->value);
                            $status = $progress?->status;
                        @endphp
                        <div @class([
                            'flex items-center gap-3 rounded-xl border px-3 py-3 transition',
                            'border-emerald-100 bg-emerald-50/50' => $status?->value === 'disetujui',
                            'border-amber-100 bg-amber-50/50' => $status?->value === 'perlu_revisi',
                            'border-blue-100 bg-blue-50/50' => in_array($status?->value, ['bimbingan', 'draft']),
                            'border-slate-100 bg-slate-50/50' => ! $progress,
                        ])>
                            <div @class([
                                'flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xs font-bold',
                                'bg-emerald-100 text-emerald-700' => $status?->value === 'disetujui',
                                'bg-amber-100 text-amber-700' => $status?->value === 'perlu_revisi',
                                'bg-blue-100 text-blue-700' => in_array($status?->value, ['bimbingan', 'draft']),
                                'bg-slate-100 text-slate-400' => ! $progress,
                            ])>
                                {{ $loop->iteration }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-slate-800">{{ $bab->fullLabel() }}</p>
                                @if ($progress)
                                    <p class="text-xs text-slate-500">{{ $progress->updated_at?->format('d M Y') }}</p>
                                @else
                                    <p class="text-xs text-slate-400">Belum diunggah</p>
                                @endif
                            </div>
                            @if ($status)
                                <span class="badge badge-{{ $status->color() }} shrink-0">{{ $status->label() }}</span>
                            @else
                                <span class="badge badge-slate shrink-0">Kosong</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tips card --}}
            <div class="rounded-2xl border border-violet-100 bg-violet-50 p-5">
                <h4 class="flex items-center gap-2 text-sm font-semibold text-violet-900">
                    <span>💡</span> Tips
                </h4>
                <ul class="mt-3 space-y-2 text-sm text-violet-800/80">
                    <li class="flex gap-2">
                        <span class="text-violet-400">•</span>
                        Pilih bab yang ingin Anda update atau kirim ulang.
                    </li>
                    <li class="flex gap-2">
                        <span class="text-violet-400">•</span>
                        Jelaskan perbaikan yang sudah dilakukan di catatan.
                    </li>
                    <li class="flex gap-2">
                        <span class="text-violet-400">•</span>
                        Upload file PDF/DOCX maksimal 10 MB.
                    </li>
                </ul>
            </div>
        </div>

        {{-- Form --}}
        <div class="lg:col-span-3">
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-soft sm:p-8">
                <div class="mb-6 border-b border-slate-100 pb-5">
                    <h3 class="text-base font-semibold text-slate-900">Formulir Update Progress</h3>
                    <p class="mt-1 text-sm text-slate-500">Isi form di bawah lalu klik kirim. Dosen akan meninjau submission Anda.</p>
                </div>

                <form method="POST" action="{{ route('progress-skripsi.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="bab" class="auth-label">
                            Tahapan / Bab <span class="text-red-400">*</span>
                        </label>
                        <select id="bab" name="bab" class="auth-input text-base" required>
                            <option value="">— Pilih bab yang ingin diupdate —</option>
                            @foreach ($babOptions as $bab)
                                @php $existing = $existingProgress->get($bab->value); @endphp
                                <option value="{{ $bab->value }}" @selected(old('bab') === $bab->value)>
                                    {{ $bab->fullLabel() }}
                                    @if ($existing)
                                        ({{ $existing->status->label() }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-slate-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            Jika bab sudah pernah dikirim, data akan diperbarui otomatis.
                        </p>
                        <x-input-error :messages="$errors->get('bab')" class="mt-2" />
                    </div>

                    <div>
                        <label for="catatan_revisi" class="auth-label">Catatan Kemajuan</label>
                        <textarea
                            id="catatan_revisi"
                            name="catatan_revisi"
                            rows="5"
                            class="auth-input resize-y text-base leading-relaxed"
                            placeholder="Contoh: Sudah memperbaiki kutipan di sub-bab 2.3 dan menambahkan tabel perbandingan metode..."
                        >{{ old('catatan_revisi') }}</textarea>
                        <x-input-error :messages="$errors->get('catatan_revisi')" class="mt-2" />
                    </div>

                    <div x-data="{ fileName: '' }">
                        <label class="auth-label">File Pendukung</label>
                        <label
                            for="file"
                            class="group mt-1 flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-brand-200 bg-brand-50 px-6 py-8 text-center transition hover:border-brand-400 hover:bg-brand-100/50"
                        >
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-100 text-brand-600 transition group-hover:scale-105">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                            </div>
                            <p class="mt-3 text-sm font-medium text-slate-700">
                                <span class="text-brand-600">Klik untuk upload</span> atau seret file ke sini
                            </p>
                            <p class="mt-1 text-xs text-slate-400">PDF atau DOCX · Maks. 10 MB</p>
                            <p x-show="fileName" x-text="fileName" class="mt-3 rounded-lg bg-brand-100 px-3 py-1.5 text-sm font-medium text-brand-700"></p>
                            <input
                                id="file"
                                name="file"
                                type="file"
                                accept=".pdf,.doc,.docx"
                                class="sr-only"
                                @change="fileName = $event.target.files[0]?.name ?? ''"
                            />
                        </label>
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-6 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-8 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 hover:shadow-md active:scale-[0.98]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            Kirim Progress
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
