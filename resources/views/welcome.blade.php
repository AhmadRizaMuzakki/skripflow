<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ config('app.name', 'SkripsiFlow') }} — platform monitoring skripsi terstruktur untuk mahasiswa dan dosen pembimbing.">

        <title>{{ config('app.name', 'SkripsiFlow') }} — Monitoring Skripsi</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900">
        <div
            x-data="{ mobileOpen: false }"
            x-effect="document.body.classList.toggle('overflow-hidden', mobileOpen)"
            class="bg-slate-50"
        >
            {{-- Navbar --}}
            <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
                    <a href="#hero" class="group flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-lg shadow-brand-600/25 transition group-hover:bg-brand-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-slate-900">{{ config('app.name', 'SkripsiFlow') }}</span>
                    </a>

                    <nav class="hidden items-center gap-8 text-sm font-medium text-slate-600 md:flex">
                        <a href="#fitur" class="transition hover:text-brand-600">Fitur</a>
                        <a href="#manfaat" class="transition hover:text-brand-600">Manfaat</a>
                        <a href="#alur" class="transition hover:text-brand-600">Alur Sistem</a>
                    </nav>

                    <div class="hidden items-center gap-3 md:flex">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition hover:bg-brand-700">
                                Dashboard
                            </a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-medium text-slate-600 transition hover:text-brand-600">
                                    Daftar
                                </a>
                            @endif
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-accent-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-200/60 transition hover:bg-accent-600">
                                Masuk ke Sistem
                            </a>
                        @endauth
                    </div>

                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 p-2 text-slate-600 md:hidden"
                        @click="mobileOpen = !mobileOpen"
                        :aria-expanded="mobileOpen"
                        aria-label="Menu navigasi"
                    >
                        <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div
                    x-show="mobileOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="border-t border-slate-200 bg-white px-6 py-4 md:hidden"
                >
                    <nav class="flex flex-col gap-1 text-sm font-medium">
                        <a href="#fitur" @click="mobileOpen = false" class="rounded-xl px-3 py-2.5 text-slate-700 hover:bg-slate-50">Fitur</a>
                        <a href="#manfaat" @click="mobileOpen = false" class="rounded-xl px-3 py-2.5 text-slate-700 hover:bg-slate-50">Manfaat</a>
                        <a href="#alur" @click="mobileOpen = false" class="rounded-xl px-3 py-2.5 text-slate-700 hover:bg-slate-50">Alur Sistem</a>
                    </nav>
                    <div class="mt-4 flex flex-col gap-2 border-t border-slate-100 pt-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-accent-500 px-4 py-3 text-sm font-semibold text-white">
                                Masuk ke Sistem
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </header>

            <main>
                {{-- Hero --}}
                <section id="hero" class="hero-gradient-bg relative overflow-hidden">
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-brand-500/10 via-transparent to-accent-500/10"></div>
                    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,rgba(79,70,229,0.15),transparent_50%)]"></div>
                    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,rgba(251,191,36,0.1),transparent_45%)]"></div>

                    <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-6 py-16 sm:py-24 lg:grid-cols-2 lg:px-8">
                        <div class="space-y-8">
                            <div class="inline-flex items-center gap-2 rounded-full border border-brand-300/40 bg-brand-500/10 px-4 py-2 text-sm font-semibold text-brand-700 backdrop-blur-sm">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                                </svg>
                                Solusi Monitoring Skripsi Modern
                            </div>

                            <div class="space-y-5">
                                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl lg:leading-[1.1]">
                                    Akhiri kekacauan skripsi dengan platform
                                    <span class="bg-gradient-to-r from-brand-600 to-brand-800 bg-clip-text text-transparent">terstruktur & real-time</span>
                                </h1>
                                <p class="max-w-2xl text-lg leading-8 text-slate-600">
                                    {{ config('app.name', 'SkripsiFlow') }} membantu mahasiswa mengelola tahapan skripsi per bab secara terstruktur dan memudahkan dosen pembimbing memantau bimbingan dalam satu dashboard terpusat.
                                </p>
                            </div>

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex w-full items-center justify-center rounded-full bg-brand-600 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-brand-600/30 transition hover:bg-brand-700 sm:w-auto">
                                        Buka Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-full bg-accent-500 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-amber-200/60 transition hover:bg-accent-600 sm:w-auto">
                                        Mulai Pantau Skripsi
                                    </a>
                                @endauth
                                <span class="hero-glass inline-flex items-center gap-2 rounded-full px-4 py-3 text-sm text-slate-600">
                                    <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Siap pakai untuk Mahasiswa & Dosen
                                </span>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div class="hero-glass rounded-2xl p-5 transition hover:-translate-y-0.5 hover:bg-white/60">
                                    <p class="text-sm font-semibold text-brand-700">Mahasiswa</p>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">Pantau progress bab dengan visual timeline.</p>
                                </div>
                                <div class="hero-glass rounded-2xl p-5 transition hover:-translate-y-0.5 hover:bg-white/60">
                                    <p class="text-sm font-semibold text-brand-700">Dosen</p>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">Lihat ringkasan bimbingan setiap mahasiswa.</p>
                                </div>
                                <div class="hero-glass rounded-2xl p-5 transition hover:-translate-y-0.5 hover:bg-white/60 sm:col-span-1">
                                    <p class="text-sm font-semibold text-brand-700">Notifikasi</p>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">Reminder otomatis agar tidak melewatkan deadline.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Dashboard preview (contoh fitur untuk pengunjung) --}}
                        <div class="relative mx-auto w-full max-w-xl lg:max-w-none">
                            <div class="absolute -inset-4 rounded-[2.5rem] bg-gradient-to-br from-brand-500/20 via-transparent to-accent-500/20 blur-2xl"></div>
                            <div class="relative rounded-[2rem] border border-slate-200 bg-gradient-to-br from-slate-950 via-brand-900 to-brand-700 p-1 shadow-2xl shadow-slate-900/20">
                                <div class="overflow-hidden rounded-[1.75rem] bg-slate-950 px-6 py-6 sm:px-8 sm:py-8">
                                    <div class="mb-6 flex items-center justify-between text-white">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Contoh Tampilan</p>
                                            <h2 class="text-xl font-semibold">Satu Platform, Alur Jelas</h2>
                                        </div>
                                        <div class="flex items-center gap-2 rounded-full bg-brand-500/20 px-3 py-1.5 text-xs font-medium text-brand-200 ring-1 ring-brand-400/30">
                                            Preview Fitur
                                        </div>
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="rounded-2xl bg-slate-900/90 p-5 text-white">
                                            <p class="text-sm text-slate-400">Timeline Skripsi</p>
                                            <ul class="mt-4 space-y-2.5">
                                                @foreach ([
                                                    ['label' => 'Pengajuan Judul', 'status' => 'done'],
                                                    ['label' => 'Seminar Proposal', 'status' => 'active'],
                                                    ['label' => 'Penulisan Bab 1–5', 'status' => 'upcoming'],
                                                    ['label' => 'Sidang Skripsi', 'status' => 'upcoming'],
                                                ] as $step)
                                                    <li class="flex items-center gap-3 rounded-xl bg-slate-950/70 px-3 py-2.5">
                                                        @if ($step['status'] === 'done')
                                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                </svg>
                                                            </span>
                                                        @elseif ($step['status'] === 'active')
                                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-accent-500/20 text-accent-400">
                                                                <span class="h-2 w-2 rounded-full bg-accent-400"></span>
                                                            </span>
                                                        @else
                                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-800 text-slate-500">
                                                                <span class="h-2 w-2 rounded-full bg-slate-600"></span>
                                                            </span>
                                                        @endif
                                                        <span class="text-sm {{ $step['status'] === 'active' ? 'font-semibold text-white' : 'text-slate-400' }}">{{ $step['label'] }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="rounded-2xl bg-slate-900/90 p-5 text-white">
                                            <p class="text-sm text-slate-400">Akses Berdasarkan Peran</p>
                                            <div class="mt-4 space-y-3">
                                                <div class="rounded-xl border border-brand-500/20 bg-brand-900/30 p-3">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="h-4 w-4 text-brand-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                                                        </svg>
                                                        <p class="text-sm font-semibold">Mahasiswa</p>
                                                    </div>
                                                    <p class="mt-1.5 text-xs leading-5 text-slate-400">Update progress bab &amp; lihat jadwal milestone.</p>
                                                </div>
                                                <div class="rounded-xl border border-brand-500/20 bg-brand-900/30 p-3">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="h-4 w-4 text-brand-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 8.25 21 8.25M6.75 12 21 12m-13.5 3.75H21" />
                                                        </svg>
                                                        <p class="text-sm font-semibold">Dosen Pembimbing</p>
                                                    </div>
                                                    <p class="mt-1.5 text-xs leading-5 text-slate-400">Pantau status bimbingan seluruh mahasiswa.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 rounded-2xl bg-gradient-to-r from-brand-800/90 to-brand-900/90 p-5 text-white">
                                        <p class="text-sm font-medium text-slate-200">Yang bisa kamu lakukan setelah bergabung</p>
                                        <div class="mt-4 grid gap-2 sm:grid-cols-3">
                                            <div class="rounded-xl bg-white/5 px-3 py-2.5 text-center">
                                                <p class="text-lg font-bold text-accent-300">5 Bab</p>
                                                <p class="text-[11px] text-slate-400">Progress terstruktur</p>
                                            </div>
                                            <div class="rounded-xl bg-white/5 px-3 py-2.5 text-center">
                                                <p class="text-lg font-bold text-accent-300">Auto</p>
                                                <p class="text-[11px] text-slate-400">Reminder deadline</p>
                                            </div>
                                            <div class="rounded-xl bg-white/5 px-3 py-2.5 text-center">
                                                <p class="text-lg font-bold text-accent-300">1 Dashboard</p>
                                                <p class="text-[11px] text-slate-400">Monitoring terpusat</p>
                                            </div>
                                        </div>
                                        @guest
                                            <p class="mt-4 text-center text-xs text-slate-400">
                                                Daftar gratis untuk mulai mengelola skripsi secara digital.
                                            </p>
                                        @endguest
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Manfaat --}}
                <section id="manfaat" class="bg-brand-600 py-16 text-white sm:py-20">
                    <div class="mx-auto max-w-6xl px-6 lg:px-8">
                        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                            <div class="space-y-6">
                                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold uppercase tracking-[0.2em] text-white/90">
                                    Tantangan Lama
                                </span>
                                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">
                                    Bimbingan manual terasa usang, berantakan, dan susah dipantau.
                                </h2>
                                <p class="max-w-xl text-lg leading-8 text-indigo-100">
                                    Bimbingan via chat sering tertumpuk? Lupa deadline revisi? Dosen kesulitan memantau puluhan mahasiswa sekaligus? {{ config('app.name', 'SkripsiFlow') }} mendigitalisasi alur monitoring skripsi agar transparan, otomatis, dan terstruktur.
                                </p>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach ([
                                    ['title' => 'Komunikasi Terkonsolidasi', 'desc' => 'Semua catatan bimbingan dan revisi tercatat rapi di satu tempat.', 'icon' => 'M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z'],
                                    ['title' => 'Deadline Terjaga', 'desc' => 'Notifikasi dan reminder membuat mahasiswa tidak melewatkan jadwal penting.', 'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5'],
                                    ['title' => 'Monitoring Real-Time', 'desc' => 'Dosen mengecek status progres skripsi kapan saja tanpa menunggu tatap muka.', 'icon' => 'M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941'],
                                    ['title' => 'Keamanan Dokumen', 'desc' => 'Riwayat bimbingan dan revisi tersimpan aman di sistem digital.', 'icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'],
                                ] as $item)
                                    <div class="rounded-3xl bg-white/10 p-6 shadow-xl shadow-brand-900/20 backdrop-blur transition hover:bg-white/15">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                            </svg>
                                        </div>
                                        <h3 class="mt-4 text-lg font-semibold">{{ $item['title'] }}</h3>
                                        <p class="mt-2 text-sm leading-6 text-indigo-100">{{ $item['desc'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Fitur --}}
                <section id="fitur" class="bg-slate-50 py-16 sm:py-20">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <div class="mx-auto mb-12 max-w-3xl text-center">
                            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-accent-600">Fitur Unggulan</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                                Empat fitur inti agar skripsi berjalan lebih terarah dan profesional
                            </h2>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                            @foreach ([
                                ['title' => 'Pencatatan Progres Terstruktur', 'desc' => 'Input dan pantau kemajuan skripsi rapi per bab, dari Bab 1 hingga bab akhir.'],
                                ['title' => 'Timeline & Milestone Akademik', 'desc' => 'Lacak jadwal krusial dari proposal, seminar hasil, hingga sidang skripsi.'],
                                ['title' => 'Sistem Pengingat Otomatis', 'desc' => 'Notifikasi pengingat agar mahasiswa tidak melewatkan batas waktu bimbingan.'],
                                ['title' => 'Dashboard Monitoring Dosen', 'desc' => 'Ringkasan status semua mahasiswa bimbingan dalam satu tampilan instan.'],
                            ] as $index => $feature)
                                <article class="group rounded-3xl border border-slate-200 bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:border-brand-200 hover:shadow-xl">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white">
                                        <span class="text-sm font-bold">{{ $index + 1 }}</span>
                                    </span>
                                    <h3 class="mt-5 text-lg font-semibold text-slate-900">{{ $feature['title'] }}</h3>
                                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $feature['desc'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>

                {{-- Metrik --}}
                <section class="bg-brand-700 py-16 text-white sm:py-20">
                    <div class="mx-auto max-w-6xl px-6 lg:px-8">
                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="rounded-4xl border border-white/10 bg-white/10 p-10 shadow-2xl backdrop-blur">
                                <p class="text-5xl font-bold sm:text-6xl">100%</p>
                                <p class="mt-4 text-lg leading-8 text-indigo-100">Riwayat bimbingan & revisi tercatat aman secara digital.</p>
                            </div>
                            <div class="rounded-4xl border border-white/10 bg-white/10 p-10 shadow-2xl backdrop-blur">
                                <p class="text-5xl font-bold sm:text-6xl">&lt; 1 Menit</p>
                                <p class="mt-4 text-lg leading-8 text-indigo-100">Memangkas waktu pengecekan status mahasiswa oleh dosen dari proses manual yang lama.</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Alur --}}
                <section id="alur" class="bg-slate-50 py-16 sm:py-20">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <div class="mx-auto mb-12 max-w-3xl text-center">
                            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-accent-600">Alur Pengguna</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                                Hak akses jelas untuk Mahasiswa dan Dosen Pembimbing
                            </h2>
                        </div>

                        <div class="grid gap-8 lg:grid-cols-2">
                            <div class="rounded-4xl border border-slate-200 bg-white p-8 shadow-sm">
                                <div class="mb-6 flex items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-700">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-slate-900">Mahasiswa</h3>
                                        <p class="text-sm text-slate-500">Alur ringkas untuk peserta skripsi</p>
                                    </div>
                                </div>
                                <ol class="space-y-3">
                                    @foreach ([
                                        ['step' => 'Login', 'desc' => 'Masuk ke akun untuk memulai akses.'],
                                        ['step' => 'Lihat Timeline', 'desc' => 'Periksa urutan tahapan dan milestone skripsi.'],
                                        ['step' => 'Update Progres Bab', 'desc' => 'Unggah status pengerjaan setiap bab secara terstruktur.'],
                                        ['step' => 'Terima Reminder Otomatis', 'desc' => 'Dapatkan pengingat waktu revisi dan bimbingan.'],
                                    ] as $i => $step)
                                        <li class="flex gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-accent-500 text-sm font-bold text-white">{{ $i + 1 }}</span>
                                            <div>
                                                <p class="font-semibold text-slate-800">{{ $step['step'] }}</p>
                                                <p class="text-sm text-slate-500">{{ $step['desc'] }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>

                            <div class="rounded-4xl border border-slate-200 bg-white p-8 shadow-sm">
                                <div class="mb-6 flex items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-700">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 8.25 21 8.25M6.75 12 21 12m-13.5 3.75H21" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-slate-900">Dosen Pembimbing</h3>
                                        <p class="text-sm text-slate-500">Alur kontrol pembimbing akademik</p>
                                    </div>
                                </div>
                                <ol class="space-y-3">
                                    @foreach ([
                                        ['step' => 'Login', 'desc' => 'Masuk ke dashboard untuk akses dosen.'],
                                        ['step' => 'Akses Dashboard Terpusat', 'desc' => 'Lihat ringkasan status semua mahasiswa.'],
                                        ['step' => 'Evaluasi & Monitor', 'desc' => 'Analisa laporan progres mahasiswa secara real-time.'],
                                    ] as $i => $step)
                                        <li class="flex gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-100 text-sm font-bold text-brand-700">{{ $i + 1 }}</span>
                                            <div>
                                                <p class="font-semibold text-slate-800">{{ $step['step'] }}</p>
                                                <p class="text-sm text-slate-500">{{ $step['desc'] }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- CTA --}}
                <section class="relative overflow-hidden bg-slate-900 py-16 sm:py-20">
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-brand-600/30 via-transparent to-accent-500/20"></div>
                    <div class="relative mx-auto max-w-4xl px-6 text-center lg:px-8">
                        <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            Siap mengelola skripsi dengan lebih rapi?
                        </h2>
                        <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-300">
                            Gabung sekarang dan rasakan kemudahan monitoring progress, milestone, dan bimbingan dalam satu platform.
                        </p>
                        <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex w-full items-center justify-center rounded-full bg-white px-8 py-4 text-base font-semibold text-slate-900 transition hover:bg-slate-100 sm:w-auto">
                                    Buka Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-full bg-accent-500 px-8 py-4 text-base font-semibold text-white transition hover:bg-accent-600 sm:w-auto">
                                    Masuk ke Sistem
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center rounded-full border border-white/20 px-8 py-4 text-base font-semibold text-white transition hover:bg-white/10 sm:w-auto">
                                        Buat Akun Baru
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </section>
            </main>

            {{-- Footer --}}
            <footer class="border-t border-slate-200 bg-white py-12">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="grid gap-8 md:grid-cols-3">
                        <div class="space-y-3">
                            <p class="text-lg font-bold text-slate-900">{{ config('app.name', 'SkripsiFlow') }}</p>
                            <p class="max-w-xs text-sm leading-6 text-slate-600">
                                Sistem monitoring skripsi berbasis web untuk mahasiswa dan dosen pembimbing.
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Navigasi</p>
                            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                <li><a href="#fitur" class="transition hover:text-brand-600">Fitur</a></li>
                                <li><a href="#manfaat" class="transition hover:text-brand-600">Manfaat</a></li>
                                <li><a href="#alur" class="transition hover:text-brand-600">Alur Sistem</a></li>
                            </ul>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Bantuan</p>
                            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                <li><a href="{{ route('login') }}" class="transition hover:text-brand-600">Masuk ke Sistem</a></li>
                                @if (Route::has('register'))
                                    <li><a href="{{ route('register') }}" class="transition hover:text-brand-600">Daftar Akun</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="mt-10 border-t border-slate-100 pt-6 text-sm text-slate-500">
                        &copy; {{ date('Y') }} {{ config('app.name', 'SkripsiFlow') }} — Kelompok 12 Es Kelapa (Teknik Informatika STT-NF). All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
