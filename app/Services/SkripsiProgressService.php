<?php

namespace App\Services;

use App\Enums\BabSkripsi;
use App\Enums\ProgressSkripsiStatus;
use App\Models\ProgressSkripsi;
use App\Models\User;
use Illuminate\Support\Collection;

class SkripsiProgressService
{
    public function getAllForUser(User $user): Collection
    {
        return ProgressSkripsi::query()
            ->where('mahasiswa_id', $user->id)
            ->orderByDesc('updated_at')
            ->get();
    }

    /**
     * @return Collection<string, ProgressSkripsi>
     */
    public function getLatestPerBab(User $user): Collection
    {
        return ProgressSkripsi::query()
            ->where('mahasiswa_id', $user->id)
            ->orderByDesc('updated_at')
            ->get()
            ->keyBy(fn (ProgressSkripsi $progress) => $progress->bab->value);
    }

    public function calculateTotalProgress(Collection $latestPerBab): int
    {
        $total = 0;

        foreach ($latestPerBab as $progress) {
            $total += match ($progress->status) {
                ProgressSkripsiStatus::Disetujui => 20,
                ProgressSkripsiStatus::PerluRevisi => 15,
                ProgressSkripsiStatus::Bimbingan => 10,
                ProgressSkripsiStatus::Draft => 5,
            };
        }

        return min(100, $total);
    }

    public function getCurrentBab(Collection $latestPerBab): ?BabSkripsi
    {
        $babOrder = collect(BabSkripsi::cases());

        $active = $latestPerBab
            ->filter(fn (ProgressSkripsi $p) => $p->status !== ProgressSkripsiStatus::Disetujui)
            ->sortByDesc(fn (ProgressSkripsi $p) => $babOrder->search($p->bab))
            ->first();

        if ($active) {
            return $active->bab;
        }

        return $latestPerBab
            ->filter(fn (ProgressSkripsi $p) => $p->status === ProgressSkripsiStatus::Disetujui)
            ->sortByDesc(fn (ProgressSkripsi $p) => $babOrder->search($p->bab))
            ->first()
            ?->bab;
    }

    public function getUpcomingDeadline(Collection $latestPerBab): ?ProgressSkripsi
    {
        return $latestPerBab
            ->filter(fn (ProgressSkripsi $p) => $p->status === ProgressSkripsiStatus::PerluRevisi
                && $p->deadline_revisi)
            ->sortBy('deadline_revisi')
            ->first();
    }

    /**
     * @return array{
     *     days_remaining: int,
     *     days_total: int,
     *     days_elapsed: int,
     *     progress_percent: int,
     *     remaining_percent: int,
     *     label: string,
     *     urgency: string,
     *     start_date: string,
     *     end_date: string,
     *     is_today: bool,
     *     is_overdue: bool,
     * }|null
     */
    public function getDeadlineInfo(?ProgressSkripsi $progress): ?array
    {
        if (! $progress?->deadline_revisi) {
            return null;
        }

        $deadline = $progress->deadline_revisi->copy()->startOfDay();
        $today = now()->startOfDay();
        $start = $progress->updated_at?->copy()->startOfDay() ?? $today;

        if ($start->gte($deadline)) {
            $start = $deadline->copy()->subDays(7);
        }

        $daysRemaining = (int) $today->diffInDays($deadline, false);
        $totalDays = max(1, (int) $start->diffInDays($deadline));
        $daysElapsed = max(0, min($totalDays, (int) $start->diffInDays($today)));
        $progressPercent = min(100, (int) round(($daysElapsed / $totalDays) * 100));
        $remainingPercent = max(0, min(100, (int) round(($daysRemaining / $totalDays) * 100)));

        $urgency = match (true) {
            $daysRemaining < 0 => 'overdue',
            $daysRemaining <= 3 => 'critical',
            $daysRemaining <= 7 => 'warning',
            default => 'normal',
        };

        return [
            'days_remaining' => $daysRemaining,
            'days_total' => $totalDays,
            'days_elapsed' => $daysElapsed,
            'progress_percent' => $progressPercent,
            'remaining_percent' => $remainingPercent,
            'label' => $this->deadlineDaysLabel($daysRemaining),
            'urgency' => $urgency,
            'start_date' => $start->translatedFormat('d M Y'),
            'end_date' => $deadline->translatedFormat('d M Y'),
            'is_today' => $daysRemaining === 0,
            'is_overdue' => $daysRemaining < 0,
        ];
    }

    private function deadlineDaysLabel(int $daysRemaining): string
    {
        if ($daysRemaining < 0) {
            return abs($daysRemaining).' hari terlambat';
        }

        if ($daysRemaining === 0) {
            return 'Hari ini';
        }

        if ($daysRemaining === 1) {
            return '1 hari lagi';
        }

        return $daysRemaining.' hari lagi';
    }

    public function getActivityRows(User $user): Collection
    {
        return $this->getLatestPerBab($user)
            ->sortByDesc(fn (ProgressSkripsi $p) => $p->updated_at)
            ->map(fn (ProgressSkripsi $progress) => [
                'tanggal' => $progress->updated_at?->format('d/m/Y') ?? '-',
                'deskripsi' => $progress->bab->label(),
                'catatan' => $progress->catatan_revisi ?: '-',
                'status' => $this->statusLabel($progress->status),
                'status_class' => $this->statusBadgeClass($progress->status),
                'file_url' => $progress->file_path ? asset('storage/'.$progress->file_path) : null,
                'file_name' => $progress->file_path ? basename($progress->file_path) : null,
            ])
            ->values();
    }

    public function syncMahasiswaProfile(User $user): void
    {
        $profile = $user->mahasiswaProfile;

        if (! $profile) {
            return;
        }

        $calculated = $this->calculateTotalProgress($this->getLatestPerBab($user));

        if ($profile->total_progress !== $calculated) {
            $profile->update(['total_progress' => $calculated]);
        }
    }

    public function upsertProgress(User $user, array $data): ProgressSkripsi
    {
        $existing = ProgressSkripsi::query()
            ->where('mahasiswa_id', $user->id)
            ->where('bab', $data['bab'])
            ->first();

        $status = $existing?->status === ProgressSkripsiStatus::Disetujui
            ? ProgressSkripsiStatus::Disetujui
            : ProgressSkripsiStatus::Bimbingan;

        if ($existing) {
            $payload = [
                'catatan_revisi' => $data['catatan_revisi'] ?? $existing->catatan_revisi,
                'status' => $status,
                'updated_at' => now(),
            ];

            if (! empty($data['file_path'])) {
                $payload['file_path'] = $data['file_path'];
            }

            $existing->update($payload);
            $this->syncMahasiswaProfile($user);

            return $existing->fresh();
        }

        $progress = ProgressSkripsi::create([
            'mahasiswa_id' => $user->id,
            'bab' => $data['bab'],
            'catatan_revisi' => $data['catatan_revisi'] ?? null,
            'file_path' => $data['file_path'] ?? null,
            'status' => ProgressSkripsiStatus::Bimbingan,
            'updated_at' => now(),
        ]);

        $this->syncMahasiswaProfile($user);

        return $progress;
    }

    public function approveByDosen(ProgressSkripsi $progress): ProgressSkripsi
    {
        $progress->update([
            'status' => ProgressSkripsiStatus::Disetujui,
            'deadline_revisi' => null,
            'updated_at' => now(),
        ]);

        $this->syncMahasiswaProfile($progress->mahasiswa);

        return $progress->fresh();
    }

    public function reviseByDosen(ProgressSkripsi $progress, string $catatan, ?string $deadline): ProgressSkripsi
    {
        $progress->update([
            'status' => ProgressSkripsiStatus::PerluRevisi,
            'catatan_revisi' => $catatan,
            'deadline_revisi' => $deadline,
            'updated_at' => now(),
        ]);

        $this->syncMahasiswaProfile($progress->mahasiswa);

        return $progress->fresh();
    }

    public function statusLabel(ProgressSkripsiStatus $status): string
    {
        return match ($status) {
            ProgressSkripsiStatus::Disetujui => 'ACC',
            ProgressSkripsiStatus::PerluRevisi => 'Revisi',
            ProgressSkripsiStatus::Bimbingan => 'Bimbingan',
            ProgressSkripsiStatus::Draft => 'Draft',
        };
    }

    public function statusBadgeClass(ProgressSkripsiStatus $status): string
    {
        return match ($status) {
            ProgressSkripsiStatus::Disetujui => 'badge-emerald',
            ProgressSkripsiStatus::PerluRevisi => 'badge-amber',
            ProgressSkripsiStatus::Bimbingan => 'badge-blue',
            ProgressSkripsiStatus::Draft => 'badge-slate',
        };
    }

    public function babStatusClass(?ProgressSkripsi $progress): string
    {
        if (! $progress) {
            return 'bg-slate-100 text-slate-500';
        }

        return match ($progress->status) {
            ProgressSkripsiStatus::Disetujui => 'bg-emerald-100 text-emerald-700',
            ProgressSkripsiStatus::PerluRevisi => 'bg-amber-100 text-amber-700',
            ProgressSkripsiStatus::Bimbingan => 'bg-blue-100 text-blue-700',
            ProgressSkripsiStatus::Draft => 'bg-slate-100 text-slate-600',
        };
    }
}
