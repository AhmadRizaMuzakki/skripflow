@props(['items' => []])

@if (count($items))
    <nav class="mb-6 flex flex-wrap items-center gap-1.5 text-sm text-slate-500" aria-label="Breadcrumb">
        @foreach ($items as $index => $item)
            @if ($index > 0)
                <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            @endif

            @if (! empty($item['url']) && $index < count($items) - 1)
                <a href="{{ $item['url'] }}" class="font-medium text-brand-600 hover:text-brand-700">{{ $item['label'] }}</a>
            @else
                <span class="font-medium text-slate-700">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endif
