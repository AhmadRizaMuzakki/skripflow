<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Notifikasi</h1>
            <p class="text-sm text-slate-500">Semua pesan dan pembaruan terkait skripsi Anda</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-2xl space-y-4">
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
            <div class="flex flex-wrap items-center justify-between gap-3">
                @if ($unreadCount > 0)
                    <p class="text-sm text-slate-600">
                        <span class="font-semibold text-brand-600">{{ $unreadCount }}</span> belum dibaca
                    </p>
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                            Tandai semua dibaca
                        </button>
                    </form>
                @else
                    <p class="text-sm text-slate-500">Semua notifikasi sudah dibaca</p>
                @endif
            </div>

            <div class="space-y-3">
                @foreach ($notifications as $notification)
                    @php $isUnread = is_null($notification->read_at); @endphp
                    <a
                        href="{{ route('notifications.read', $notification->id) }}"
                        @class([
                            'group flex items-start gap-4 rounded-2xl border bg-white p-4 shadow-soft transition',
                            'border-brand-200 bg-brand-50/40 hover:border-brand-300 hover:bg-brand-50/60' => $isUnread,
                            'border-slate-100 hover:border-slate-200 hover:bg-slate-50' => ! $isUnread,
                        ])
                    >
                        <div @class([
                            'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl',
                            'bg-brand-100 text-brand-600' => $isUnread,
                            'bg-slate-100 text-slate-500' => ! $isUnread,
                        ])>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p @class([
                                    'text-sm text-slate-900',
                                    'font-semibold' => $isUnread,
                                    'font-medium' => ! $isUnread,
                                ])>
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </p>
                                @if ($isUnread)
                                    <span class="inline-flex rounded-full bg-brand-100 px-2 py-0.5 text-xs font-medium text-brand-700">Baru</span>
                                @endif
                            </div>
                            <p class="mt-1 break-words text-sm leading-relaxed text-slate-600">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <time class="mt-2 block text-xs text-slate-400">
                                {{ $notification->created_at->locale('id')->diffForHumans() }}
                            </time>
                        </div>

                        <svg class="mt-2 h-5 w-5 shrink-0 text-slate-300 transition group-hover:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                @endforeach
            </div>

            <div class="pt-2">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
