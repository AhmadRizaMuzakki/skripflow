<?php

namespace App\Http\Controllers;

use App\Enums\JenisMilestone;
use App\Enums\MilestoneStatus;
use App\Models\Milestone;
use App\Models\ProgressSkripsi;
use App\Services\TimelineMilestoneService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MilestoneController extends Controller
{
    public function index(Request $request, TimelineMilestoneService $timelineService): View
    {
        $this->authorizeAccess();

        $user = $request->user();

        if ($user->isMahasiswa()) {
            $user->load('mahasiswaProfile');

            $milestones = Milestone::query()
                ->where('mahasiswa_id', $user->id)
                ->orderBy('tanggal_pelaksanaan')
                ->get();

            $progressList = ProgressSkripsi::query()
                ->where('mahasiswa_id', $user->id)
                ->orderByDesc('updated_at')
                ->get();

            $user->setRelation('milestones', $milestones);
            $user->setRelation('progressSkripsi', $progressList);

            $timelineSteps = $timelineService->buildForUser($user);
            $summary = $timelineService->summary($timelineSteps, $milestones->count());

            return view('milestones.timeline', compact('timelineSteps', 'milestones', 'summary'));
        }

        $milestones = Milestone::query()
            ->with('mahasiswa')
            ->latest('tanggal_pelaksanaan')
            ->get();

        return view('milestones.index', compact('milestones'));
    }

    public function create(Request $request): View
    {
        $this->authorizeAccess();

        abort_unless($request->user()->isMahasiswa(), 403);

        return view('milestones.create', [
            'jenisOptions' => JenisMilestone::cases(),
            'statusOptions' => MilestoneStatus::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAccess();

        abort_unless($request->user()->isMahasiswa(), 403);

        $validated = $request->validate([
            'jenis_milestone' => ['required', 'in:'.implode(',', array_column(JenisMilestone::cases(), 'value'))],
            'tanggal_pelaksanaan' => ['nullable', 'date'],
            'status' => ['required', 'in:'.implode(',', array_column(MilestoneStatus::cases(), 'value'))],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        Milestone::create([
            ...$validated,
            'mahasiswa_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('milestones.index')
            ->with('success', 'Milestone berhasil ditambahkan.');
    }

    public function edit(Request $request, Milestone $milestone): View
    {
        $this->authorizeMilestoneUpdate($request, $milestone);

        $milestone->load('mahasiswa.mahasiswaProfile');

        return view('milestones.edit', [
            'milestone' => $milestone,
            'statusOptions' => MilestoneStatus::cases(),
        ]);
    }

    public function update(Request $request, Milestone $milestone): RedirectResponse
    {
        $this->authorizeMilestoneUpdate($request, $milestone);

        $validated = $request->validate([
            'tanggal_pelaksanaan' => ['nullable', 'date'],
            'status' => ['required', 'in:'.implode(',', array_column(MilestoneStatus::cases(), 'value'))],
        ]);

        $milestone->update($validated);

        $profile = $milestone->mahasiswa->mahasiswaProfile;

        return redirect()
            ->route('mahasiswa-bimbingan.show', $profile)
            ->with('success', 'Status milestone berhasil diperbarui.');
    }

    private function authorizeAccess(): void
    {
        abort_unless(
            auth()->user()->isMahasiswa() || auth()->user()->isAdmin(),
            403
        );
    }

    private function authorizeMilestoneUpdate(Request $request, Milestone $milestone): void
    {
        $user = $request->user();

        abort_unless($user->isDosen() || $user->isAdmin(), 403);

        if ($user->isAdmin() && ! $user->isDosen()) {
            return;
        }

        $milestone->loadMissing('mahasiswa.mahasiswaProfile');

        abort_unless(
            $milestone->mahasiswa->mahasiswaProfile
                && $user->supervises($milestone->mahasiswa->mahasiswaProfile),
            403
        );
    }
}
