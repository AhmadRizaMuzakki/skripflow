<?php

namespace App\Services;

use App\Enums\ProgressSkripsiStatus;
use App\Models\MahasiswaProfile;
use App\Models\Milestone;
use App\Models\ProgressSkripsi;
use App\Models\User;
use Illuminate\Support\Collection;

class DosenDashboardService
{
    public function __construct(
        private readonly SkripsiProgressService $progressService,
    ) {}

    /**
     * @return Collection<int, MahasiswaProfile>
     */
    public function getSupervisedProfiles(User $dosen): Collection
    {
        return MahasiswaProfile::query()
            ->with(['user', 'dosenPembimbing'])
            ->supervisedBy($dosen)
            ->latest()
            ->get();
    }

    /**
     * @param  Collection<int, MahasiswaProfile>  $profiles
     * @return array{total: int, aktif: int, kritis: int}
     */
    public function getStats(Collection $profiles): array
    {
        $aktif = 0;
        $kritis = 0;

        foreach ($profiles as $profile) {
            $latestPerBab = $this->progressService->getLatestPerBab($profile->user);

            $hasActive = $latestPerBab->contains(
                fn (ProgressSkripsi $p) => in_array($p->status, [
                    ProgressSkripsiStatus::Bimbingan,
                    ProgressSkripsiStatus::PerluRevisi,
                ])
            );

            $isKritis = $latestPerBab->contains(function (ProgressSkripsi $p) {
                if ($p->status === ProgressSkripsiStatus::Bimbingan) {
                    return $p->updated_at && $p->updated_at->lt(now()->subDays(7));
                }

                if ($p->status === ProgressSkripsiStatus::PerluRevisi && $p->deadline_revisi) {
                    return $p->deadline_revisi->isPast() || $p->deadline_revisi->lte(now()->addDays(3));
                }

                return false;
            });

            if ($hasActive) {
                $aktif++;
            }

            if ($isKritis) {
                $kritis++;
            }
        }

        return [
            'total' => $profiles->count(),
            'aktif' => $aktif,
            'kritis' => $kritis,
        ];
    }

    /**
     * @param  Collection<int, MahasiswaProfile>  $profiles
     * @return Collection<int, array{profile: MahasiswaProfile, progres_label: string, is_kritis: bool}>
     */
    public function getMonitoringRows(Collection $profiles): Collection
    {
        return $profiles->map(function (MahasiswaProfile $profile) {
            $latestPerBab = $this->progressService->getLatestPerBab($profile->user);
            $currentBab = $this->progressService->getCurrentBab($latestPerBab);

            $babPercent = match ($latestPerBab->get($currentBab?->value)?->status) {
                ProgressSkripsiStatus::Disetujui => 100,
                ProgressSkripsiStatus::PerluRevisi => 80,
                ProgressSkripsiStatus::Bimbingan => 60,
                ProgressSkripsiStatus::Draft => 30,
                default => 0,
            };

            $progresLabel = $currentBab
                ? $currentBab->label().' ('.$babPercent.'%)'
                : 'Belum mulai';

            $isKritis = $latestPerBab->contains(function (ProgressSkripsi $p) {
                if ($p->status === ProgressSkripsiStatus::Bimbingan) {
                    return $p->updated_at && $p->updated_at->lt(now()->subDays(7));
                }

                if ($p->status === ProgressSkripsiStatus::PerluRevisi && $p->deadline_revisi) {
                    return $p->deadline_revisi->isPast() || $p->deadline_revisi->lte(now()->addDays(3));
                }

                return false;
            });

            return [
                'profile' => $profile,
                'progres_label' => $progresLabel,
                'is_kritis' => $isKritis,
            ];
        });
    }

    public function getPendingProgress(User $mahasiswa): ?ProgressSkripsi
    {
        return $this->progressService->getLatestPerBab($mahasiswa)
            ->filter(fn (ProgressSkripsi $p) => $p->status === ProgressSkripsiStatus::Bimbingan)
            ->sortByDesc(fn (ProgressSkripsi $p) => $p->bab->value)
            ->first();
    }

    /**
     * @return Collection<int, ProgressSkripsi>
     */
    public function getApprovedHistory(User $mahasiswa): Collection
    {
        return $this->progressService->getLatestPerBab($mahasiswa)
            ->filter(fn (ProgressSkripsi $p) => $p->status === ProgressSkripsiStatus::Disetujui)
            ->sortBy(fn (ProgressSkripsi $p) => $p->bab->value)
            ->values();
    }

    /**
     * @return Collection<int, ProgressSkripsi>
     */
    public function getSubmissionsForDosen(User $dosen, ?string $filter = null): Collection
    {
        $studentIds = MahasiswaProfile::query()
            ->supervisedBy($dosen)
            ->pluck('user_id');

        $query = ProgressSkripsi::query()
            ->with('mahasiswa')
            ->whereIn('mahasiswa_id', $studentIds)
            ->orderByDesc('updated_at');

        if ($filter === 'menunggu') {
            $query->where('status', ProgressSkripsiStatus::Bimbingan);
        } elseif ($filter === 'selesai') {
            $query->where('status', ProgressSkripsiStatus::Disetujui);
        }

        return $query->get();
    }

    public function getPendingCount(User $dosen): int
    {
        $studentIds = MahasiswaProfile::query()
            ->supervisedBy($dosen)
            ->pluck('user_id');

        return ProgressSkripsi::query()
            ->whereIn('mahasiswa_id', $studentIds)
            ->where('status', ProgressSkripsiStatus::Bimbingan)
            ->count();
    }

    /**
     * @return Collection<int, array{profile: MahasiswaProfile, pending: ?ProgressSkripsi, is_kritis: bool}>
     */
    public function getAttentionItems(Collection $profiles): Collection
    {
        return $profiles->map(function (MahasiswaProfile $profile) {
            $pending = $this->getPendingProgress($profile->user);
            $latestPerBab = $this->progressService->getLatestPerBab($profile->user);

            $isKritis = $latestPerBab->contains(function (ProgressSkripsi $p) {
                if ($p->status === ProgressSkripsiStatus::PerluRevisi && $p->deadline_revisi) {
                    return $p->deadline_revisi->isPast() || $p->deadline_revisi->lte(now()->addDays(3));
                }

                return false;
            });

            return [
                'profile' => $profile,
                'pending' => $pending,
                'is_kritis' => $isKritis,
            ];
        })->filter(fn ($item) => $item['pending'] || $item['is_kritis'])->values();
    }

    /**
     * @param  array{mahasiswa_id?: string|null, jenis?: string|null, status?: string|null}  $filters
     * @return Collection<int, Milestone>
     */
    public function getMilestones(User $viewer, array $filters = []): Collection
    {
        $profilesQuery = MahasiswaProfile::query()->supervisedBy($viewer);

        if (! empty($filters['mahasiswa_id'])) {
            $profilesQuery->where('user_id', $filters['mahasiswa_id']);
        }

        $studentIds = $profilesQuery->pluck('user_id');

        if ($studentIds->isEmpty()) {
            return collect();
        }

        $query = Milestone::query()
            ->with('mahasiswa')
            ->whereIn('mahasiswa_id', $studentIds)
            ->latest('tanggal_pelaksanaan');

        if (! empty($filters['jenis'])) {
            $query->where('jenis_milestone', $filters['jenis']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->get();
    }

    /**
     * @param  Collection<int, MahasiswaProfile>  $profiles
     * @return Collection<int, MahasiswaProfile>
     */
    public function filterProfiles(Collection $profiles, ?string $mahasiswaId): Collection
    {
        if (! $mahasiswaId) {
            return $profiles;
        }

        return $profiles
            ->where('user_id', (int) $mahasiswaId)
            ->values();
    }
}
