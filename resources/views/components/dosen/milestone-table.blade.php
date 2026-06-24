@props(['milestones', 'showActions' => false, 'filters' => []])

@php
    $hasFilters = collect($filters)->filter()->isNotEmpty();
@endphp

@if ($milestones->isEmpty())
    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 px-6 py-12 text-center">
        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-white text-slate-400 shadow-sm">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
        </div>
        <p class="font-medium text-slate-700">
            @if ($hasFilters)
                Tidak ada milestone yang cocok
            @else
                Belum ada data milestone
            @endif
        </p>
        <p class="mt-1 text-sm text-slate-500">
            @if ($hasFilters)
                Coba ubah filter atau reset untuk melihat semua data.
            @else
                Milestone mahasiswa bimbingan akan muncul di sini.
            @endif
        </p>
    </div>
@else
    <div class="overflow-hidden rounded-xl border border-slate-100">
        <div class="overflow-x-auto">
            <table class="dosen-table w-full">
                <thead class="bg-slate-50/90">
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        @if ($showActions)
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($milestones as $milestone)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-100 text-xs font-bold text-brand-700">
                                        {{ strtoupper(substr($milestone->mahasiswa->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-slate-900">{{ $milestone->mahasiswa->name }}</span>
                                </div>
                            </td>
                            <td class="text-slate-600">{{ $milestone->jenis_milestone?->label() ?? '-' }}</td>
                            <td>
                                @if ($milestone->status)
                                    <span class="badge badge-{{ $milestone->status->color() }}">{{ $milestone->status->label() }}</span>
                                @endif
                            </td>
                            <td class="text-slate-600">{{ $milestone->tanggal_pelaksanaan?->format('d M Y') ?? '-' }}</td>
                            <td class="max-w-xs truncate text-slate-600" title="{{ $milestone->keterangan }}">{{ $milestone->keterangan ?? '-' }}</td>
                            @if ($showActions)
                                <td>
                                    <a href="{{ route('milestones.edit', $milestone) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-700 transition hover:bg-brand-100">
                                        Ubah
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
