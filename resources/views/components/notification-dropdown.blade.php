@php
    $latestNotifications = Auth::user()->notifications()->latest()->limit(3)->get();
    $unreadCount = Auth::user()->unreadNotifications()->count();
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button
        type="button"
        @click="open = !open"
        class="relative inline-flex rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
        title="Notifikasi"
        aria-label="Notifikasi"
        :aria-expanded="open"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        @if ($unreadCount > 0)
            <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
        @endif
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute end-0 z-50 mt-2 w-[calc(100vw-2rem)] max-w-sm origin-top-right sm:w-96"
    >
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl ring-1 ring-black/5">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/80 px-4 py-3">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Notifikasi</h3>
                    @if ($unreadCount > 0)
                        <p class="text-xs text-slate-500">{{ $unreadCount }} belum dibaca</p>
                    @endif
                </div>
                @if ($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-brand-600 hover:text-brand-700">
                            Tandai dibaca
                        </button>
                    </form>
                @endif
            </div>

            <div class="max-h-80 divide-y divide-slate-100 overflow-y-auto">
                @forelse ($latestNotifications as $notification)
                    <a
                        href="{{ route('notifications.read', $notification->id) }}"
                        @class([
                            'block px-4 py-3.5 transition hover:bg-slate-50',
                            'bg-brand-50/40' => is_null($notification->read_at),
                        ])
                    >
                        <div class="flex items-start gap-3">
                            <div @class([
                                'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg',
                                'bg-brand-100 text-brand-600' => is_null($notification->read_at),
                                'bg-slate-100 text-slate-500' => ! is_null($notification->read_at),
                            ])>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p @class([
                                    'text-sm text-slate-800',
                                    'font-semibold' => is_null($notification->read_at),
                                    'font-medium' => ! is_null($notification->read_at),
                                ])>
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </p>
                                <p class="mt-0.5 line-clamp-2 text-sm text-slate-500">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <p class="mt-1.5 text-xs text-slate-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if (is_null($notification->read_at))
                                <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-brand-500"></span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-10 text-center">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-slate-700">Belum ada notifikasi</p>
                        <p class="mt-1 text-xs text-slate-500">Pesan terbaru akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 bg-slate-50/50 px-4 py-2.5 text-center">
                <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
                    Lihat semua notifikasi
                </a>
            </div>
        </div>
    </div>
</div>
