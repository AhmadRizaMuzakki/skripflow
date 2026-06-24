<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-lg font-semibold text-slate-900">Notifikasi</h1>
                <p class="text-sm text-slate-500">Semua pesan dan pembaruan terkait skripsi Anda</p>
            </div>
            @if ($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-3xl">
        @if ($notifications->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-12 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </div>
                <p class="font-medium text-slate-700">Belum ada notifikasi</p>
                <p class="mt-1 text-sm text-slate-500">Notifikasi progress dan bimbingan akan muncul di sini.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-soft">
                <div class="divide-y divide-slate-100">
                    @foreach ($notifications as $notification)
                        <a
                            href="{{ route('notifications.read', $notification->id) }}"
                            @class([
                                'flex items-start gap-4 px-5 py-4 transition hover:bg-slate-50',
                                'bg-brand-50/30' => is_null($notification->read_at),
                            ])
                        >
                            <div @class([
                                'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl',
                                'bg-brand-100 text-brand-600' => is_null($notification->read_at),
                                'bg-slate-100 text-slate-500' => ! is_null($notification->read_at),
                            ])>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <p @class([
                                        'text-sm text-slate-900',
                                        'font-semibold' => is_null($notification->read_at),
                                        'font-medium' => ! is_null($notification->read_at),
                                    ])>
                                        {{ $notification->data['title'] ?? 'Notifikasi' }}
                                    </p>
                                    <time class="shrink-0 text-xs text-slate-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </time>
                                </div>
                                <p class="mt-1 text-sm leading-relaxed text-slate-600">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                            </div>
                            @if (is_null($notification->read_at))
                                <span class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-brand-500"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
