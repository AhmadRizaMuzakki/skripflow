<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Kelola Dosen</h1>
            <p class="text-sm text-slate-500">Tambah, ubah, dan hapus akun dosen pembimbing.</p>
        </div>
    </x-slot>

    <x-alert />

    @if ($errors->has('dosen'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $errors->first('dosen') }}
        </div>
    @endif

    @if ($dosens->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-12 text-center">
            <p class="font-medium text-slate-700">Belum ada dosen</p>
            <p class="mt-1 text-sm text-slate-500">Tambahkan dosen pembimbing pertama untuk sistem bimbingan.</p>
            <a href="{{ route('admin.dosen.create') }}" class="auth-btn mt-6 w-auto px-6">Tambah Dosen</a>
        </div>
    @else
        <div class="dosen-panel">
            <div class="dosen-panel__header">
                <p class="text-sm text-slate-600">
                    <span class="font-semibold text-slate-900">{{ $dosens->count() }}</span> dosen terdaftar
                </p>
                <a href="{{ route('admin.dosen.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Dosen
                </a>
            </div>
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
                        @foreach ($dosens as $dosen)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $dosen->name }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $dosen->nomor_induk }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $dosen->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="badge badge-blue">{{ $dosen->mahasiswa_profiles_dibimbing_count }} mahasiswa</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <a href="{{ route('admin.dosen.edit', $dosen) }}"
                                           class="text-sm font-medium text-brand-600 hover:text-brand-700">
                                            Ubah
                                        </a>
                                        <form method="POST" action="{{ route('admin.dosen.destroy', $dosen) }}"
                                              onsubmit="return confirm('Hapus dosen {{ $dosen->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>