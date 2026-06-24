@php
    $user = Auth::user();
@endphp

<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/80 backdrop-blur-md">
    <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        {{-- Left: mobile toggle + page title --}}
        <div class="flex min-w-0 flex-1 items-center gap-4">
            <button
                type="button"
                @click="sidebarOpen = !sidebarOpen"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 md:hidden"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                Menu
            </button>

            <div class="min-w-0">
                @isset($header)
                    {{ $header }}
                @else
                    <h1 class="text-lg font-semibold text-slate-900">Dashboard</h1>
                @endisset
            </div>
        </div>

        {{-- Right: actions + user menu --}}
        <div class="flex shrink-0 items-center gap-2 sm:gap-3">
            <x-notification-dropdown />

            {{-- User dropdown --}}
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white py-1.5 pl-1.5 pr-3 text-sm transition hover:bg-slate-50">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-xs font-semibold text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="hidden font-medium text-slate-700 sm:block">{{ $user->name }}</span>
                        <svg class="hidden h-4 w-4 text-slate-400 sm:block" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="border-b border-slate-100 px-4 py-3">
                        <p class="text-sm font-medium text-slate-900">{{ $user->name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                    </div>

                    <x-dropdown-link :href="route('profile.edit')">
                        Profil
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Keluar
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</header>
