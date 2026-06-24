<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Dosen dengan Mahasiswa</h1>
            <p class="text-sm text-slate-500">Atur penugasan dosen pembimbing untuk setiap mahasiswa.</p>
        </div>
    </x-slot>

    <x-alert />

    @if ($mahasiswa->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-12 text-center">
            <p class="font-medium text-slate-700">Belum ada mahasiswa terdaftar</p>
            <p class="mt-1 text-sm text-slate-500">Mahasiswa akan muncul setelah mendaftar di sistem.</p>
        </div>
    @elseif ($dosens->isEmpty())
        <div class="rounded-2xl border border-dashed border-amber-200 bg-amber-50 p-12 text-center">
            <p class="font-medium text-amber-800">Belum ada dosen terdaftar</p>
            <p class="mt-1 text-sm text-amber-700">Tambahkan dosen pembimbing terlebih dahulu sebelum menugaskan bimbingan.</p>
            <a href="{{ route('admin.dosen.create') }}" class="auth-btn mt-6 w-auto px-6">Tambah Dosen</a>
        </div>
    @else
        <div class="dosen-panel">
            <div class="dosen-panel__header">
                <p class="text-sm text-slate-600">
                    <span class="font-semibold text-slate-900">{{ $mahasiswa->count() }}</span> mahasiswa terdaftar
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Mahasiswa</th>
                            <th class="px-6 py-3">NIM</th>
                            <th class="px-6 py-3">Judul Skripsi</th>
                            <th class="px-6 py-3">Dosen Pembimbing</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($mahasiswa as $profile)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $profile->user->name }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $profile->user->nomor_induk }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $profile->judul_skripsi ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('admin.bimbingan.update', $profile) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="dosen_pembimbing_id"
                                                onchange="this.form.submit()"
                                                class="w-full max-w-xs rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                            <option value="">— Belum ditugaskan —</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->id }}" @selected($profile->dosen_pembimbing_id === $dosen->id)>
                                                    {{ $dosen->name }} ({{ $dosen->nomor_induk }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
