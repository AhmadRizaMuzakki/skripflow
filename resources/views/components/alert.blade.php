@props(['type' => 'success'])

@php
    $classes = match ($type) {
        'success' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
        'error' => 'bg-red-50 text-red-800 ring-red-200',
        'warning' => 'bg-amber-50 text-amber-800 ring-amber-200',
        'info' => 'bg-blue-50 text-blue-800 ring-blue-200',
        default => 'bg-slate-50 text-slate-800 ring-slate-200',
    };
@endphp

@if (session('success'))
    <div {{ $attributes->merge(['class' => "mb-6 rounded-xl px-4 py-3 text-sm ring-1 {$classes}"]) }}>
        {{ session('success') }}
    </div>
@endif

@if (session('warning'))
    <div class="mb-6 rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-800 ring-1 ring-amber-200">
        {{ session('warning') }}
    </div>
@endif

@if (session('info'))
    <div class="mb-6 rounded-xl bg-blue-50 px-4 py-3 text-sm text-blue-800 ring-1 ring-blue-200">
        {{ session('info') }}
    </div>
@endif
