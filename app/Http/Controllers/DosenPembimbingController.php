<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelectDosenPembimbingRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DosenPembimbingController extends Controller
{
    public function show(): View|RedirectResponse
    {
        $user = auth()->user();

        abort_unless($user->isMahasiswa(), 403);

        $profile = $user->mahasiswaProfile;

        abort_unless($profile, 403);

        if ($profile->dosen_pembimbing_id !== null) {
            return redirect()
                ->route('dashboard')
                ->with('info', 'Dosen pembimbing sudah ditetapkan dan tidak dapat diubah.');
        }

        return view('mahasiswa.pilih-dosen-pembimbing', [
            'dosens' => self::availableDosens(),
        ]);
    }

    public function store(SelectDosenPembimbingRequest $request): RedirectResponse
    {
        $user = $request->user();
        $profile = $user->mahasiswaProfile;

        $dosen = User::query()->findOrFail($request->validated('dosen_pembimbing_id'));
        abort_unless($dosen->isDosen(), 422);

        if ($profile->dosen_pembimbing_id !== null) {
            return redirect()
                ->route('dashboard')
                ->withErrors(['dosen_pembimbing_id' => 'Dosen pembimbing sudah ditetapkan dan tidak dapat diubah.']);
        }

        $profile->update([
            'dosen_pembimbing_id' => $dosen->id,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Dosen pembimbing berhasil dipilih. Pilihan ini tidak dapat diubah.');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public static function availableDosens()
    {
        return User::role('dosen')
            ->withCount('mahasiswaProfilesDibimbing')
            ->orderBy('name')
            ->get();
    }
}
