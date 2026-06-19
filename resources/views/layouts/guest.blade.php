<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SkripsiFlow') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800">
        <div class="flex min-h-screen">
            {{-- Brand panel --}}
            <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-slate-900 p-12 lg:flex">
                <div>
                    <a href="/" class="inline-flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">{{ config('app.name', 'SkripsiFlow') }}</span>
                    </a>
                </div>

                <div class="max-w-md">
                    <h1 class="text-3xl font-bold leading-tight text-white">
                        {{ $heading ?? 'Kelola perjalanan skripsi Anda dengan mudah.' }}
                    </h1>
                    <p class="mt-4 text-base leading-relaxed text-slate-300">
                        {{ $tagline ?? 'Pantau progress, milestone, dan bimbingan dosen dalam satu platform.' }}
                    </p>
                </div>

                <p class="text-sm text-slate-400">
                    &copy; {{ date('Y') }} {{ config('app.name', 'SkripsiFlow') }}
                </p>
            </div>

            {{-- Form panel --}}
            <div class="flex w-full flex-col justify-center bg-slate-50 px-6 py-10 lg:w-1/2 lg:px-16 xl:px-24">
                <div class="mx-auto w-full max-w-md">
                    {{-- Mobile logo --}}
                    <div class="mb-8 text-center lg:hidden">
                        <a href="/" class="inline-flex items-center gap-2">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-white">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                            </div>
                            <span class="text-lg font-bold text-slate-900">{{ config('app.name', 'SkripsiFlow') }}</span>
                        </a>
                    </div>

                    @isset($title)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ $title }}</h2>
                            @isset($subtitle)
                                <p class="mt-2 text-sm text-slate-500">{{ $subtitle }}</p>
                            @endisset
                        </div>
                    @endisset

                    <div class="rounded-2xl bg-white p-8 shadow-card ring-1 ring-slate-100">
                        {{ $slot }}
                    </div>

                    @isset($footer)
                        <div class="mt-6 text-center">
                            {{ $footer }}
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </body>
</html>
