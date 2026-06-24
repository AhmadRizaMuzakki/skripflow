@props([
    'value',
    'label',
    'accent' => 'brand',
    'icon' => 'users',
])

@php
    $accents = [
        'slate' => ['bar' => 'bg-slate-400', 'icon' => 'bg-slate-100 text-slate-600', 'value' => 'text-slate-900'],
        'brand' => ['bar' => 'bg-brand-500', 'icon' => 'bg-brand-50 text-brand-600', 'value' => 'text-brand-700'],
        'emerald' => ['bar' => 'bg-emerald-500', 'icon' => 'bg-emerald-50 text-emerald-600', 'value' => 'text-emerald-700'],
        'amber' => ['bar' => 'bg-amber-500', 'icon' => 'bg-amber-50 text-amber-600', 'value' => 'text-amber-700'],
        'red' => ['bar' => 'bg-red-500', 'icon' => 'bg-red-50 text-red-500', 'value' => 'text-red-600'],
        'violet' => ['bar' => 'bg-violet-500', 'icon' => 'bg-violet-50 text-violet-600', 'value' => 'text-violet-700'],
    ];
    $tone = $accents[$accent] ?? $accents['brand'];
@endphp

<div class="dosen-stat-card">
    <div class="dosen-stat-card__accent {{ $tone['bar'] }}"></div>
    <div class="flex items-start justify-between gap-4 pt-1">
        <div>
            <p class="text-3xl font-bold tracking-tight {{ $tone['value'] }}">{{ $value }}</p>
            <p class="mt-1 text-sm font-medium text-slate-500">{{ $label }}</p>
        </div>
        <div class="dosen-stat-icon {{ $tone['icon'] }}">
            @if ($icon === 'users')
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            @elseif ($icon === 'check')
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @elseif ($icon === 'alert')
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            @elseif ($icon === 'inbox')
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                </svg>
            @endif
        </div>
    </div>
</div>
