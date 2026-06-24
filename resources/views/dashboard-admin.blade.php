<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Dashboard Admin</h1>
            <p class="text-sm text-slate-500">Kelola sistem monitoring skripsi</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="dosen-stat-card">
            <div class="dosen-stat-icon bg-violet-50 text-violet-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $stats['total_dosen'] }}</p>
                <p class="text-sm text-slate-500">Total Dosen</p>
            </div>
        </div>

        <div class="dosen-stat-card">
            <div class="dosen-stat-icon bg-brand-50 text-brand-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-brand-700">{{ $stats['total_mahasiswa'] }}</p>
                <p class="text-sm text-slate-500">Total Mahasiswa</p>
            </div>
        </div>

        <div class="dosen-stat-card">
            <div class="dosen-stat-icon bg-emerald-50 text-emerald-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-emerald-700">{{ $stats['total_bimbingan'] }}</p>
                <p class="text-sm text-slate-500">Sudah Punya Dosen</p>
            </div>
        </div>

        <div class="dosen-stat-card">
            <div class="dosen-stat-icon bg-amber-50 text-amber-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-700">{{ $stats['dosen_tanpa_mahasiswa'] }}</p>
                <p class="text-sm text-slate-500">Dosen Tanpa Bimbingan</p>
            </div>
        </div>
    </div>

    <div class="mb-6 grid gap-4 sm:grid-cols-2">
        <a href="{{ route('admin.dosen.index') }}" class="dosen-menu-card group">
            <div class="dosen-menu-icon bg-violet-50 text-violet-600 group-hover:bg-violet-100">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-900">Kelola Dosen</p>
                <p class="mt-0.5 text-sm text-slate-500">Tambah, ubah, dan hapus akun dosen pembimbing</p>
            </div>
        </a>

        <a href="{{ route('admin.bimbingan.index') }}" class="dosen-menu-card group">
            <div class="dosen-menu-icon bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-900">Dosen dengan Mahasiswa</p>
                <p class="mt-0.5 text-sm text-slate-500">Atur penugasan dosen pembimbing untuk mahasiswa</p>
            </div>
        </a>
    </div>

    <div class="mb-4 flex items-center justify-between gap-4">
        <h2 class="text-base font-semibold text-slate-900">Dosen Terbaru</h2>
        <a href="{{ route('admin.dosen.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
            Lihat semua →
        </a>
    </div>

    @if ($recentDosen->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center">
            <p class="text-sm text-slate-500">Belum ada dosen terdaftar.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-soft">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">NIDN</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Mahasiswa</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentDosen as $dosen)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $dosen->name }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $dosen->nomor_induk }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $dosen->email }}</td>
                                <td class="px-6 py-4">{{ $dosen->mahasiswa_profiles_dibimbing_count }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.dosen.edit', $dosen) }}"
                                       class="text-sm font-medium text-brand-600 hover:text-brand-700">
                                        Ubah
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
