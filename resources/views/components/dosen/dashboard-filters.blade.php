@props(['profiles', 'filters', 'action' => null])

@php
    $hasFilters = collect($filters)->filter()->isNotEmpty();
    $formAction = $action ?? route('dashboard');
@endphp

<div class="dosen-filter-panel" x-data="{ open: {{ $hasFilters ? 'true' : 'false' }} }">
    <button type="button" @click="open = !open"
            class="flex w-full items-center justify-between gap-3 text-left">
        <div class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                </svg>
            </span>
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Filter Data</h2>
                <p class="text-xs text-slate-500">Saring milestone berdasarkan mahasiswa, jenis, atau status</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if ($hasFilters)
                <span class="hidden rounded-full bg-brand-100 px-2.5 py-1 text-xs font-semibold text-brand-700 sm:inline-flex">
                    Aktif
                </span>
            @endif
            <svg class="h-5 w-5 text-slate-400 transition" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </div>
    </button>

    <form method="GET" action="{{ $formAction }}" x-show="open" x-transition x-cloak class="mt-5 border-t border-slate-100 pt-5">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label for="mahasiswa_id" class="auth-label">Mahasiswa</label>
                <select id="mahasiswa_id" name="mahasiswa_id" class="auth-input">
                    <option value="">Semua mahasiswa</option>
                    @foreach ($profiles as $profile)
                        <option value="{{ $profile->user_id }}"
                            @selected(($filters['mahasiswa_id'] ?? '') == $profile->user_id)>
                            {{ $profile->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="jenis" class="auth-label">Jenis Milestone</label>
                <select id="jenis" name="jenis" class="auth-input">
                    <option value="">Semua jenis</option>
                    @foreach (\App\Enums\JenisMilestone::cases() as $jenis)
                        <option value="{{ $jenis->value }}" @selected(($filters['jenis'] ?? '') === $jenis->value)>
                            {{ $jenis->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="auth-label">Status</label>
                <select id="status" name="status" class="auth-input">
                    <option value="">Semua status</option>
                    @foreach (\App\Enums\MilestoneStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-wrap items-end gap-2">
                <button type="submit" class="auth-btn w-full sm:flex-1 px-6">
                    Terapkan
                </button>
                @if ($hasFilters)
                    <a href="{{ $formAction }}"
                       class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 sm:w-auto">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>
