<?php

namespace App\Http\Controllers;

use App\Enums\BabSkripsi;
use App\Enums\ProgressSkripsiStatus;
use App\Models\MahasiswaProfile;
use App\Models\ProgressSkripsi;
use App\Notifications\ProgressSubmittedNotification;
use App\Services\SkripsiProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
            ->when($user->isDosen(), function ($query) use ($user) {
                $studentIds = MahasiswaProfile::query()
                    ->supervisedBy($user)
                    ->pluck('user_id');

                $query->whereIn('mahasiswa_id', $studentIds);
            })
            ->latest('updated_at')
            ->get();

        return view('progress-skripsi.index', [
            'progressList' => $progressList,
            'tableView' => $user->isDosen() || $user->isAdmin(),
        ]);
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
            'catatan_revisi' => ['required', 'string', 'max:2000'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
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

            $file = $request->file('file');
            $nim = $request->user()->nomor_induk ?: (string) $request->user()->id;
            $directory = "skripsi/{$nim}/{$validated['bab']}";
            $fileName = $this->sanitizeUploadedFileName($file->getClientOriginalName());

            $filePath = $file->storeAs($directory, $fileName, 'public');
        }

        $progress = $this->progressService->upsertProgress($request->user(), [
            'bab' => $validated['bab'],
            'catatan_revisi' => $validated['catatan_revisi'] ?? null,
            'file_path' => $filePath,
        ]);

        $request->user()->loadMissing('mahasiswaProfile.dosenPembimbing');
        $dosen = $request->user()->mahasiswaProfile?->dosenPembimbing;

        if ($dosen) {
            $dosen->notify(new ProgressSubmittedNotification($progress));
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Progress skripsi berhasil dikirim.');
    }

    public function file(Request $request, ProgressSkripsi $progress): StreamedResponse
    {
        abort_unless($progress->file_path, 404);

        $user = $request->user();
        $progress->loadMissing('mahasiswa.mahasiswaProfile');

        $canAccess = $user->isAdmin()
            || $progress->mahasiswa_id === $user->id
            || (
                $user->isDosen()
                && $progress->mahasiswa->mahasiswaProfile
                && $user->supervises($progress->mahasiswa->mahasiswaProfile)
            );

        abort_unless($canAccess, 403);
        abort_unless(Storage::disk('public')->exists($progress->file_path), 404);

        return Storage::disk('public')->response(
            $progress->file_path,
            basename($progress->file_path),
        );
    }

    private function sanitizeUploadedFileName(string $filename): string
    {
        $filename = basename(str_replace(['\\', '/', "\0"], '', $filename));
        $filename = trim($filename);

        return $filename !== '' ? $filename : 'dokumen.pdf';
    }

    private function authorizeAccess(): void
    {
        abort_unless(
            auth()->user()->isMahasiswa()
            || auth()->user()->isAdmin()
            || auth()->user()->isDosen(),
            403
        );
    }
}
