<?php

namespace App\Http\Controllers;

use App\Enums\BabSkripsi;
use App\Enums\ProgressSkripsiStatus;
use App\Models\ProgressSkripsi;
use App\Services\SkripsiProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProgressSkripsiController extends Controller
{
    public function __construct(
        private readonly SkripsiProgressService $progressService,
    ) {}

    public function index(Request $request): View
    {
        $this->authorizeAccess();

        $user = $request->user();

        $progressList = ProgressSkripsi::query()
            ->with('mahasiswa')
            ->when($user->isMahasiswa(), fn ($q) => $q->where('mahasiswa_id', $user->id))
            ->latest('updated_at')
            ->get();

        return view('progress-skripsi.index', compact('progressList'));
    }

    public function create(Request $request): View
    {
        $this->authorizeAccess();

        abort_unless($request->user()->isMahasiswa(), 403);

        $existingProgress = $this->progressService->getLatestPerBab($request->user());
        $user = $request->user()->load('mahasiswaProfile');

        return view('progress-skripsi.create', [
            'babOptions' => BabSkripsi::cases(),
            'existingProgress' => $existingProgress,
            'profile' => $user->mahasiswaProfile,
            'approvedCount' => $existingProgress->filter(fn ($p) => $p->status === ProgressSkripsiStatus::Disetujui)->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAccess();

        abort_unless($request->user()->isMahasiswa(), 403);

        $validated = $request->validate([
            'bab' => ['required', 'in:'.implode(',', array_column(BabSkripsi::cases(), 'value'))],
            'catatan_revisi' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $existing = ProgressSkripsi::query()
                ->where('mahasiswa_id', $request->user()->id)
                ->where('bab', $validated['bab'])
                ->first();

            if ($existing?->file_path) {
                Storage::disk('public')->delete($existing->file_path);
            }

            $filePath = $request->file('file')->store('skripsi', 'public');
        }

        $this->progressService->upsertProgress($request->user(), [
            'bab' => $validated['bab'],
            'catatan_revisi' => $validated['catatan_revisi'] ?? null,
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Progress skripsi berhasil dikirim.');
    }

    private function authorizeAccess(): void
    {
        abort_unless(
            auth()->user()->isMahasiswa() || auth()->user()->isAdmin(),
            403
        );
    }
}
