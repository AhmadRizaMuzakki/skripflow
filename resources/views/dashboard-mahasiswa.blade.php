<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Dashboard</h1>
            <p class="text-sm text-slate-500">Ringkasan progress skripsi Anda</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-5xl space-y-6">
        {{-- Welcome --}}
        <div class="rounded-2xl border border-brand-100 bg-brand-50 p-6 shadow-soft">
            <h2 class="text-xl font-semibold text-slate-900">
                Selamat Datang, {{ $user->name }}!
            </h2>
            @if ($profile?->dosenPembimbing)
                <p class="mt-2 text-sm text-slate-600">
                    Dosen Pembimbing:
                    <span class="font-medium text-brand-700">{{ $profile->dosenPembimbing->name }}</span>
                </p>
                <p class="mt-1 text-xs text-slate-500">Penugasan dosen pembimbing bersifat permanen.</p>
            @elseif ($profile)
                <p class="mt-2 text-sm text-amber-600">Dosen pembimbing belum ditetapkan.</p>
            @else
                <p class="mt-2 text-sm text-amber-600">Profil skripsi belum tersedia. Hubungi admin.</p>
            @endif
        </div>

        {{-- Reminder --}}
        @if ($upcomingDeadline && $deadlineInfo)
            <x-deadline-reminder :progress="$upcomingDeadline" :info="$deadlineInfo" />
        @endif

        {{-- Progress --}}
        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-soft">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                <h2 class="text-sm font-semibold text-slate-800">Indikator Progres Skripsi Saat Ini</h2>
                <span class="text-sm font-bold text-brand-700">
                    {{ $totalProgress }}%
                    @if ($currentBab)
                        ({{ $currentBab->label() }})
                    @endif
                </span>
            </div>

            <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                <div
                    class="h-full rounded-full bg-brand-600 transition-all duration-500"
                    style="width: {{ min($totalProgress, 100) }}%"
                ></div>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-5">
                @foreach ($babStatuses as $bab)
                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-2.5 text-center">
                        <p class="text-xs font-semibold text-slate-700">{{ $bab['label'] }}</p>
                        <span class="mt-1 inline-block rounded-full px-2 py-0.5 text-xs font-medium {{ $bab['status_class'] }}">
                            {{ $bab['status_label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Activity table --}}
        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-soft">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-slate-800">Riwayat Aktivitas Pengumpulan Terakhir</h2>
            </div>

            @if ($activities->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="text-sm text-slate-500">Belum ada progress skripsi.</p>
                    <a href="{{ route('progress-skripsi.create') }}" class="auth-link mt-2 inline-block">
                        Kirim progress pertama
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="whitespace-nowrap px-6 py-3 text-left font-semibold text-slate-600">Tanggal</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left font-semibold text-slate-600">Deskripsi</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left font-semibold text-slate-600">Catatan</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left font-semibold text-slate-600">File</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left font-semibold text-slate-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($activities as $activity)
                                <tr class="hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $activity['tanggal'] }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium text-slate-900">{{ $activity['deskripsi'] }}</td>
                                    <td class="max-w-[200px] truncate px-6 py-4 text-slate-500" title="{{ $activity['catatan'] }}">
                                        {{ $activity['catatan'] }}
                                    </td>
                                    <td class="max-w-xs px-6 py-4">
                                        @if ($activity['file_url'])
                                            <a href="{{ $activity['file_url'] }}" target="_blank" rel="noopener" class="auth-link break-all text-xs">
                                                {{ $activity['file_name'] }}
                                            </a>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="badge {{ $activity['status_class'] }}">{{ $activity['status'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
