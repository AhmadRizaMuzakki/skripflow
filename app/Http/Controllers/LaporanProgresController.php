<?php

namespace App\Http\Controllers;

use App\Enums\ProgressSkripsiStatus;
use App\Services\DosenDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanProgresController extends Controller
{
    public function __construct(
        private readonly DosenDashboardService $dosenService,
    ) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()->isDosen() || $request->user()->isAdmin(), 403);

        $filter = $request->query('status', 'semua');
        $submissions = $this->dosenService->getSubmissionsForDosen($request->user(), $filter === 'semua' ? null : $filter);
        $pendingCount = $this->dosenService->getPendingCount($request->user());

        $filterCounts = [
            'semua' => $this->dosenService->getSubmissionsForDosen($request->user(), null)->count(),
            'menunggu' => $this->dosenService->getPendingCount($request->user()),
            'selesai' => $this->dosenService->getSubmissionsForDosen($request->user(), 'selesai')->count(),
        ];

        return view('laporan-progres.index', [
            'submissions' => $submissions,
            'filter' => $filter,
            'pendingCount' => $pendingCount,
            'filterCounts' => $filterCounts,
        ]);
    }
}
