<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaProfile;
use App\Services\DosenDashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MahasiswaBimbinganController extends Controller
{
    public function __construct(
        private readonly DosenDashboardService $dosenService,
    ) {}

    public function index(Request $request): View
    {
        $this->authorizeAccess();

        $user = $request->user();
        $profiles = $this->dosenService->getSupervisedProfiles($user);
        $monitoringRows = $this->dosenService->getMonitoringRows($profiles);
        $pendingCount = $this->dosenService->getPendingCount($user);
        $stats = $this->dosenService->getStats($profiles);
        $unassignedCount = $this->unassignedQuery()->count();

        return view('mahasiswa-bimbingan.index', [
            'monitoringRows' => $monitoringRows,
            'pendingCount' => $pendingCount,
            'stats' => $stats,
            'unassignedCount' => $unassignedCount,
            'canAddMahasiswa' => $user->isDosen(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorizeDosen();

        $unassigned = $this->unassignedQuery()->get();

        return view('mahasiswa-bimbingan.create', compact('unassigned'));
    }

    public function assign(Request $request, string $mahasiswaBimbingan): RedirectResponse
    {
        $this->authorizeDosen();

        $profile = $this->unassignedQuery()->findOrFail($mahasiswaBimbingan);

        $profile->update([
            'dosen_pembimbing_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('mahasiswa-bimbingan.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan ke bimbingan Anda.');
    }

    public function show(Request $request, MahasiswaProfile $mahasiswaBimbingan): View
    {
        $this->authorizeAccess();

        $user = $request->user();

        $mahasiswaBimbingan->load(['user', 'dosenPembimbing']);

        $pendingProgress = $this->dosenService->getPendingProgress($mahasiswaBimbingan->user);
        $approvedHistory = $this->dosenService->getApprovedHistory($mahasiswaBimbingan->user);
        $pendingCount = $this->dosenService->getPendingCount($user);

        return view('mahasiswa-bimbingan.show', [
            'profile' => $mahasiswaBimbingan,
            'pendingProgress' => $pendingProgress,
            'approvedHistory' => $approvedHistory,
            'pendingCount' => $pendingCount,
        ]);
    }

    private function unassignedQuery()
    {
        return MahasiswaProfile::query()
            ->with('user')
            ->whereNull('dosen_pembimbing_id')
            ->latest();
    }

    private function authorizeAccess(): void
    {
        abort_unless(
            auth()->user()->isDosen() || auth()->user()->isAdmin(),
            403
        );
    }

    private function authorizeDosen(): void
    {
        abort_unless(auth()->user()->isDosen(), 403);
    }
}
