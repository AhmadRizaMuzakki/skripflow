<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Services\AdminDashboardService;
use App\Services\DosenDashboardService;
use App\Services\SkripsiProgressService;
use App\Services\TimelineMilestoneService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(
        Request $request,
        TimelineMilestoneService $timelineService,
        SkripsiProgressService $progressService,
        DosenDashboardService $dosenService,
        AdminDashboardService $adminService,
    ): View|RedirectResponse {
        $user = $request->user();

        if ($user->isMahasiswa()) {
            return $this->mahasiswaDashboard($user, $timelineService, $progressService);
        }

        if ($user->isAdmin() && ! $user->isDosen()) {
            return $this->adminDashboard($adminService);
        }

        if ($user->isDosen()) {
            return $this->dosenDashboard($request, $user, $dosenService);
        }

        return view('dashboard', compact('user'));
    }

    private function adminDashboard(AdminDashboardService $adminService): View
    {
        return view('dashboard-admin', [
            'stats' => $adminService->getStats(),
            'recentDosen' => $adminService->getRecentDosen(),
        ]);
    }

    private function dosenDashboard(Request $request, $user, DosenDashboardService $dosenService): View
    {
        $filters = [
            'mahasiswa_id' => $request->query('mahasiswa_id'),
            'jenis' => $request->query('jenis'),
            'status' => $request->query('status'),
        ];

        $profiles = $dosenService->getSupervisedProfiles($user);
        $filteredProfiles = $dosenService->filterProfiles($profiles, $filters['mahasiswa_id']);
        $stats = $dosenService->getStats($filteredProfiles);
        $attentionItems = $dosenService->getAttentionItems($filteredProfiles);
        $pendingCount = $dosenService->getPendingCount($user);
        $milestones = $dosenService->getMilestones($user, $filters);

        return view('dashboard-dosen', [
            'user' => $user,
            'stats' => $stats,
            'attentionItems' => $attentionItems,
            'pendingCount' => $pendingCount,
            'milestones' => $milestones,
            'profiles' => $profiles,
            'filters' => $filters,
        ]);
    }

    private function mahasiswaDashboard(
        $user,
        TimelineMilestoneService $timelineService,
        SkripsiProgressService $progressService,
    ): View|RedirectResponse {
        $user->load('mahasiswaProfile.dosenPembimbing');

        if ($user->needsDosenPembimbing()) {
            return redirect()->route('dosen-pembimbing.show');
        }

        $profile = $user->mahasiswaProfile;
        $latestPerBab = $progressService->getLatestPerBab($user);

        $progressService->syncMahasiswaProfile($user);
        $profile?->refresh();

        $totalProgress = $progressService->calculateTotalProgress($latestPerBab);
        $currentBab = $progressService->getCurrentBab($latestPerBab);
        $upcomingDeadline = $progressService->getUpcomingDeadline($latestPerBab);
        $deadlineInfo = $progressService->getDeadlineInfo($upcomingDeadline);
        $activities = $progressService->getActivityRows($user);

        $milestones = Milestone::query()
            ->where('mahasiswa_id', $user->id)
            ->orderBy('tanggal_pelaksanaan')
            ->get();

        $user->setRelation('milestones', $milestones);
        $user->setRelation('progressSkripsi', $latestPerBab->values());

        $babStatuses = collect(\App\Enums\BabSkripsi::cases())->map(fn ($bab) => [
            'label' => $bab->label(),
            'status_label' => $latestPerBab->get($bab->value)?->status->label() ?? 'Belum ada',
            'status_class' => $progressService->babStatusClass($latestPerBab->get($bab->value)),
        ]);

        return view('dashboard-mahasiswa', [
            'user' => $user,
            'profile' => $profile,
            'totalProgress' => $totalProgress,
            'currentBab' => $currentBab,
            'upcomingDeadline' => $upcomingDeadline,
            'deadlineInfo' => $deadlineInfo,
            'activities' => $activities,
            'babStatuses' => $babStatuses,
        ]);
    }
}
