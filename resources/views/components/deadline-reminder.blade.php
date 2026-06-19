@props([
    'progress',
    'info',
])

@php
    $urgency = $info['urgency'];
@endphp

<div class="deadline-card deadline-card--{{ $urgency }}">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
        {{-- Countdown --}}
        <div class="deadline-countdown deadline-countdown--{{ $urgency }}">
            @if ($info['is_today'])
                <span class="deadline-countdown__today">Hari<br>Ini</span>
            @else
                <span class="deadline-countdown__number">{{ abs($info['days_remaining']) }}</span>
                <span class="deadline-countdown__unit">
                    @if ($info['is_overdue'])
                        hari<br>terlambat
                    @else
                        hari<br>lagi
                    @endif
                </span>
            @endif
        </div>

        {{-- Detail --}}
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <span class="deadline-badge deadline-badge--{{ $urgency }}">
                    @if ($info['is_overdue'])
                        Terlambat
                    @elseif ($info['is_today'])
                        Deadline Hari Ini
                    @elseif ($urgency === 'critical')
                        Mendesak
                    @elseif ($urgency === 'warning')
                        Segera
                    @else
                        Aktif
                    @endif
                </span>
                <span class="text-xs font-medium text-slate-500">{{ $progress->bab->label() }}</span>
            </div>

            <h3 class="mt-2 text-base font-semibold text-slate-900">
                Deadline Revisi {{ $progress->bab->label() }}
            </h3>
            <p class="mt-1 text-sm text-slate-600">
                Target selesai:
                <span class="font-medium text-slate-800">{{ $progress->deadline_revisi->translatedFormat('d F Y') }}</span>
            </p>

            {{-- Progress bar --}}
            <div class="mt-4">
                <div class="mb-2 flex items-center justify-between gap-2 text-xs">
                    <span class="font-medium text-slate-600">Progress menuju deadline</span>
                    <span class="font-semibold text-slate-800">
                        @if ($info['is_overdue'])
                            Melewati {{ abs($info['days_remaining']) }} hari
                        @else
                            Hari ke-{{ $info['days_elapsed'] }} dari {{ $info['days_total'] }}
                        @endif
                    </span>
                </div>

                <div class="h-3 w-full overflow-hidden rounded-full bg-red-100">
                    <div
                        class="h-full rounded-full transition-all duration-700 ease-out"
                        style="width: {{ $info['is_overdue'] ? 100 : max($info['progress_percent'], 5) }}%; background-color: #dc2626;"
                    ></div>
                </div>

                <div class="mt-1.5 flex justify-between text-[11px] text-slate-400">
                    <span>{{ $info['start_date'] }}</span>
                    <span>{{ $info['end_date'] }}</span>
                </div>
            </div>

            @if ($progress->catatan_revisi)
                <div class="mt-4 flex gap-2.5 rounded-xl border border-slate-100 bg-slate-50/80 px-3.5 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>
                    <p class="text-sm leading-relaxed text-slate-600">{{ $progress->catatan_revisi }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
