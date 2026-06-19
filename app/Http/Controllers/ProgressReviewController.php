<?php

namespace App\Http\Controllers;

use App\Models\ProgressSkripsi;
use App\Services\DosenDashboardService;
use App\Services\SkripsiProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProgressReviewController extends Controller
{
    public function __construct(
        private readonly SkripsiProgressService $progressService,
        private readonly DosenDashboardService $dosenService,
    ) {}

    public function approve(Request $request, ProgressSkripsi $progress): RedirectResponse
    {
        $this->authorizeReview($request, $progress);

        $this->progressService->approveByDosen($progress);

        return back()->with('success', 'Progress berhasil disetujui (ACC).');
    }

    public function revise(Request $request, ProgressSkripsi $progress): RedirectResponse
    {
        $this->authorizeReview($request, $progress);

        $validated = $request->validate([
            'catatan_revisi' => ['required', 'string', 'max:2000'],
            'deadline_revisi' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $this->progressService->reviseByDosen(
            $progress,
            $validated['catatan_revisi'],
            $validated['deadline_revisi'],
        );

        return back()->with('success', 'Progress dikembalikan untuk revisi.');
    }

    private function authorizeReview(Request $request, ProgressSkripsi $progress): void
    {
        $user = $request->user();

        abort_unless($user->isDosen() || $user->isAdmin(), 403);

        $progress->load('mahasiswa.mahasiswaProfile');

        if ($user->isAdmin()) {
            return;
        }

        abort_unless(
            $progress->mahasiswa->mahasiswaProfile?->dosen_pembimbing_id === $user->id,
            403
        );
    }
}
