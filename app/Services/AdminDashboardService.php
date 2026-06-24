<?php

namespace App\Services;

use App\Models\MahasiswaProfile;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminDashboardService
{
    /**
     * @return array{
     *     total_dosen: int,
     *     total_mahasiswa: int,
     *     total_bimbingan: int,
     *     dosen_tanpa_mahasiswa: int
     * }
     */
    public function getStats(): array
    {
        $totalDosen = User::role('dosen')->count();
        $totalMahasiswa = User::role('mahasiswa')->count();
        $totalBimbingan = MahasiswaProfile::query()->whereNotNull('dosen_pembimbing_id')->count();
        $dosenTanpaMahasiswa = User::role('dosen')
            ->whereDoesntHave('mahasiswaProfilesDibimbing')
            ->count();

        return [
            'total_dosen' => $totalDosen,
            'total_mahasiswa' => $totalMahasiswa,
            'total_bimbingan' => $totalBimbingan,
            'dosen_tanpa_mahasiswa' => $dosenTanpaMahasiswa,
        ];
    }

    /**
     * @return Collection<int, User>
     */
    public function getRecentDosen(int $limit = 5): Collection
    {
        return User::role('dosen')
            ->withCount('mahasiswaProfilesDibimbing')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
