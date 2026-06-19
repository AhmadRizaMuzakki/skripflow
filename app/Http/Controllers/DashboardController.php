<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Services\DosenDashboardService;
use App\Services\SkripsiProgressService;
use App\Services\TimelineMilestoneService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(
        Request $request,
        TimelineMilestoneService $timelineService,
        SkripsiProgressService $progressService,
        DosenDashboardService $dosenService,
    ): View {
        $user = $request->user();

        if ($user->isMahasiswa()) {
            return $this->mahasiswaDashboard($user, $timelineService, $progressService);
        }

        if ($user->isDosen() || $user->isAdmin()) {
            return $this->dosenDashboard($user, $dosenService);
        }

        return view('dashboard', compact('user'));
    }

    private function dosenDashboard($user, DosenDashboardService $dosenService): View
    {
        $profiles = $dosenService->getSupervisedProfiles($user);
        $stats = $dosenService->getStats($profiles);
        $attentionItems = $dosenService->getAttentionItems($profiles);
        $pendingCount = $dosenService->getPendingCount($user);

        return view('dashboard-dosen', [
            'user' => $user,
            'stats' => $stats,
            'attentionItems' => $attentionItems,
            'pendingCount' => $pendingCount,
        ]);
    }

    private function mahasiswaDashboard(
        $user,
        TimelineMilestoneService $timelineService,
        SkripsiProgressService $progressService,
    ): View {
        $user->load('mahasiswaProfile.dosenPembimbing');

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
