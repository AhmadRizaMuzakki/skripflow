@props(['pendingCount' => 0])

<div class="dosen-panel h-full">
    <div class="dosen-panel__header">
        <h3 class="text-sm font-semibold text-slate-900">Akses Cepat</h3>
    </div>
    <div class="space-y-3 p-5">
        <a href="{{ route('laporan-progres.index', ['status' => 'menunggu']) }}" class="dosen-quick-link group">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-sky-600 group-hover:bg-sky-100">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <p class="font-medium text-slate-900">Laporan Progres</p>
                    @if ($pendingCount > 0)
                        <span class="rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">{{ $pendingCount }}</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500">Review pengajuan mahasiswa</p>
            </div>
        </a>

        <a href="{{ route('mahasiswa-bimbingan.index') }}" class="dosen-quick-link group">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600 group-hover:bg-violet-100">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="font-medium text-slate-900">Mahasiswa Bimbingan</p>
                <p class="text-xs text-slate-500">Daftar & detail progress</p>
            </div>
        </a>

        <a href="{{ route('milestones.index') }}" class="dosen-quick-link group">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-100">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </div>
            <div>
                <p class="font-medium text-slate-900">Semua Milestone</p>
                <p class="text-xs text-slate-500">Kelola status seminar & sidang</p>
            </div>
        </a>
    </div>
</div>
