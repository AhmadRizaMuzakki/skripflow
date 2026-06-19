<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Data Milestone</h1>
            <p class="text-sm text-slate-500">Semua record milestone mahasiswa (tampilan admin).</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mb-6 rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4 text-sm text-amber-900">
        Mahasiswa melihat <strong>timeline 5 tahap</strong> yang digabung dari profil, progress skripsi, dan tabel milestones.
        Halaman ini menampilkan raw data dari database.
    </div>

    @if ($milestones->isEmpty())
        <div class="rounded-2xl border border-slate-100 bg-white p-10 text-center shadow-soft">
            <p class="text-sm text-slate-500">Belum ada data milestone.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-soft">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Mahasiswa</th>
                            <th class="px-6 py-3">Jenis</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($milestones as $milestone)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $milestone->mahasiswa->name }}</td>
                                <td class="px-6 py-4">{{ $milestone->jenis_milestone?->label() ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if ($milestone->status)
                                        <span class="badge badge-{{ $milestone->status->color() }}">{{ $milestone->status->label() }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $milestone->tanggal_pelaksanaan?->format('d M Y') ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $milestone->keterangan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
