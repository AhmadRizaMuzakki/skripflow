<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Tambah Milestone</h1>
            <p class="text-sm text-slate-500">Catat jadwal seminar atau sidang skripsi.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl rounded-2xl border border-slate-100 bg-white p-6 shadow-soft sm:p-8">
        <form method="POST" action="{{ route('milestones.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="jenis_milestone" class="auth-label">Jenis Milestone</label>
                <select id="jenis_milestone" name="jenis_milestone" class="auth-input" required>
                    <option value="">Pilih jenis</option>
                    @foreach ($jenisOptions as $jenis)
                        <option value="{{ $jenis->value }}" @selected(old('jenis_milestone') === $jenis->value)>{{ $jenis->label() }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('jenis_milestone')" class="mt-2" />
            </div>

            <div>
                <label for="tanggal_pelaksanaan" class="auth-label">Tanggal Pelaksanaan</label>
                <input id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" type="date" value="{{ old('tanggal_pelaksanaan') }}" class="auth-input" />
                <x-input-error :messages="$errors->get('tanggal_pelaksanaan')" class="mt-2" />
            </div>

            <div>
                <label for="status" class="auth-label">Status</label>
                <select id="status" name="status" class="auth-input" required>
                    @foreach ($statusOptions as $status)
                        <option value="{{ $status->value }}" @selected(old('status', 'belum') === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div>
                <label for="keterangan" class="auth-label">Keterangan</label>
                <input id="keterangan" name="keterangan" type="text" value="{{ old('keterangan') }}" placeholder="Ruang sidang, catatan..." class="auth-input" />
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="auth-btn w-auto px-6">Simpan</button>
                <a href="{{ route('milestones.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-6 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
