<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Timeline & Milestone</h1>
            <p class="text-sm text-slate-500">Alur capaian skripsi Anda dari pengajuan hingga sidang akhir.</p>
        </div>
    </x-slot>

    <x-alert />

    {{-- Ringkasan progress --}}
    <div class="mb-6 grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-brand-100 bg-brand-50 p-5 shadow-soft lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Alur Capaian Skripsi</h2>
                    <p class="mt-1 text-sm text-slate-600">
                        {{ $summary['total_steps'] }} tahap alur
                        · {{ $summary['milestone_records'] }} data tersimpan di tabel
                        <code class="rounded bg-white/80 px-1 text-xs text-brand-700">milestones</code>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-brand-600">{{ $summary['progress_percent'] }}%</p>
                    <p class="text-xs text-slate-500">{{ $summary['completed_steps'] }}/{{ $summary['total_steps'] }} tahap selesai</p>
                </div>
            </div>
            <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-white/80">
                <div class="h-full rounded-full bg-brand-600 transition-all duration-500"
                     style="width: {{ $summary['progress_percent'] }}%"></div>
            </div>
            @if ($summary['active_step'])
                <p class="mt-3 text-sm text-slate-600">
                    <span class="font-medium text-brand-700">Tahap aktif:</span> {{ $summary['active_step'] }}
                </p>
            @endif
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-soft">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Legenda Status</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-slate-600">Selesai</span>
                </li>
                <li class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-brand-500"></span>
                    <span class="text-slate-600">Sedang Berjalan</span>
                </li>
                <li class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                    <span class="text-slate-600">Belum Dimulai</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="relative">
        <div class="absolute bottom-6 left-6 top-6 hidden w-0.5 bg-slate-200 sm:block" aria-hidden="true"></div>

        <div class="space-y-4">
            @foreach ($timelineSteps as $step)
                <div @class([
                    'relative flex gap-4 rounded-2xl border px-5 py-5 shadow-soft transition sm:pl-6',
                    'border-brand-300 bg-brand-50 ring-2 ring-brand-100' => $step['is_active'],
                    'border-emerald-100 bg-white' => ! $step['is_active'] && $step['status'] === 'selesai',
                    'border-slate-100 bg-white opacity-90' => ! $step['is_active'] && $step['status'] === 'belum_dimulai',
                    'border-slate-100 bg-white' => ! $step['is_active'] && $step['status'] === 'sedang_berjalan',
                ])>
                    {{-- Step number + icon --}}
                    <div class="relative z-10 shrink-0">
                        <div @class([
                            'flex h-12 w-12 items-center justify-center rounded-full border-2',
                            'border-emerald-500 bg-emerald-50 text-emerald-600' => $step['status'] === 'selesai',
                            'border-brand-500 bg-brand-50 text-brand-600' => $step['status'] === 'sedang_berjalan',
                            'border-slate-200 bg-slate-50 text-slate-400' => $step['status'] === 'belum_dimulai',
                        ])>
                            @if ($step['status'] === 'selesai')
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            @elseif ($step['status'] === 'sedang_berjalan')
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            @else
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            @endif
                        </div>
                        <span class="mt-1 block text-center text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            {{ $step['step_number'] }}/{{ $summary['total_steps'] }}
                        </span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <h3 @class([
                                'font-semibold',
                                'text-slate-900' => $step['status'] !== 'belum_dimulai',
                                'text-slate-500' => $step['status'] === 'belum_dimulai',
                            ])>{{ $step['label'] }}</h3>
                            <span @class([
                                'badge shrink-0',
                                'badge-emerald' => $step['status'] === 'selesai',
                                'badge-blue' => $step['status'] === 'sedang_berjalan',
                                'badge-slate' => $step['status'] === 'belum_dimulai',
                            ])>
                                {{ $step['status_label'] }}
                            </span>
                        </div>

                        @if ($step['tanggal'])
                            <p class="mt-2 flex items-center gap-1.5 text-sm text-slate-500">
                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                {{ $step['tanggal'] }}
                            </p>
                        @endif

                        @if ($step['keterangan'])
                            <p @class([
                                'mt-2 text-sm leading-relaxed',
                                'text-slate-600' => $step['status'] !== 'belum_dimulai',
                                'text-slate-400 italic' => $step['status'] === 'belum_dimulai',
                            ])>{{ $step['keterangan'] }}</p>
                        @endif

                        @if ($step['deadline'])
                            <p class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-amber-50 px-3 py-1.5 text-sm font-medium text-amber-800 ring-1 ring-amber-200">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Deadline: {{ $step['deadline'] }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
