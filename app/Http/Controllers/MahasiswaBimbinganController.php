<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaProfile;
use App\Services\DosenDashboardService;
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

        return view('mahasiswa-bimbingan.index', [
            'monitoringRows' => $monitoringRows,
            'pendingCount' => $pendingCount,
        ]);
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

    private function authorizeAccess(): void
    {
        abort_unless(
            auth()->user()->isDosen() || auth()->user()->isAdmin(),
            403
        );
    }
}
