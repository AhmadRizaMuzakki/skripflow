@props(['type' => 'success'])

@php
    $classes = match ($type) {
        'success' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
        'error' => 'bg-red-50 text-red-800 ring-red-200',
        default => 'bg-slate-50 text-slate-800 ring-slate-200',
    };
@endphp

@if (session('success'))
    <div {{ $attributes->merge(['class' => "mb-6 rounded-xl px-4 py-3 text-sm ring-1 {$classes}"]) }}>
        {{ session('success') }}
    </div>
@endif
