<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-semibold text-slate-900">Ubah Dosen</h1>
            <p class="text-sm text-slate-500">{{ $dosen->name }}</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="mx-auto max-w-xl">
        <form method="POST" action="{{ route('admin.dosen.update', $dosen) }}"
              class="rounded-2xl border border-slate-100 bg-white p-6 shadow-soft">
            @csrf
            @method('PATCH')
            @include('admin.dosen._form', ['dosen' => $dosen])

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" class="auth-btn w-auto px-6">Simpan Perubahan</button>
                <a href="{{ route('admin.dosen.index') }}"
                   class="inline-flex items-center rounded-xl border border-slate-200 px-6 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
