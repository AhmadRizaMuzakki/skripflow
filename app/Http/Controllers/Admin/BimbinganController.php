<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BimbinganController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        $mahasiswa = MahasiswaProfile::query()
            ->with(['user', 'dosenPembimbing'])
            ->latest()
            ->get();

        $dosens = User::role('dosen')->orderBy('name')->get();

        return view('admin.bimbingan.index', compact('mahasiswa', 'dosens'));
    }

    public function update(Request $request, MahasiswaProfile $bimbingan): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'dosen_pembimbing_id' => ['nullable', 'exists:users,id'],
        ]);

        $dosenId = $validated['dosen_pembimbing_id'] ?? null;

        if ($dosenId !== null) {
            $dosen = User::findOrFail($dosenId);
            abort_unless($dosen->isDosen(), 422);
        }

        $bimbingan->update(['dosen_pembimbing_id' => $dosenId]);

        return back()->with('success', 'Dosen pembimbing berhasil diperbarui.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
