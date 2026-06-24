<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Data Milestone</h1>
            <p class="text-sm text-slate-500">
                @if ($isDosenView ?? false)
                    Milestone mahasiswa bimbingan Anda.
                @else
                    Semua record milestone mahasiswa (tampilan admin).
                @endif
            </p>
        </div>
    </x-slot>

    <x-alert />

    @if ($isDosenView ?? false)
        <x-dosen.dashboard-filters
            :profiles="$profiles"
            :filters="$filters"
            :action="route('milestones.index')"
        />
    @endif

    <div class="mb-6 rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4 text-sm text-amber-900">
        Mahasiswa melihat <strong>timeline 5 tahap</strong> yang digabung dari profil, progress skripsi, dan tabel milestones.
        @if ($isDosenView ?? false)
            Anda dapat mengubah status milestone mahasiswa bimbingan dari kolom aksi.
        @else
            Halaman ini menampilkan raw data dari database.
        @endif
    </div>

    <x-dosen.milestone-table
        :milestones="$milestones"
        :filters="$filters ?? []"
        :show-actions="$showActions ?? false"
    />
</x-app-layout>
