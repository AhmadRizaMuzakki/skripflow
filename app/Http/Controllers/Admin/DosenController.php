<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DosenRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class DosenController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        $dosens = User::role('dosen')
            ->withCount('mahasiswaProfilesDibimbing')
            ->latest()
            ->get();

        return view('admin.dosen.index', compact('dosens'));
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        return view('admin.dosen.create');
    }

    public function store(DosenRequest $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $dosen = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'nomor_induk' => $request->validated('nomor_induk'),
            'password' => Hash::make($request->validated('password')),
            'email_verified_at' => now(),
        ]);

        $dosenRole = Role::firstOrCreate(['name' => 'dosen', 'guard_name' => 'web']);
        $dosen->assignRole($dosenRole);

        return redirect()
            ->route('admin.dosen.index')
            ->with('success', 'Dosen pembimbing berhasil ditambahkan.');
    }

    public function edit(User $dosen): View
    {
        $this->authorizeAdmin();
        $this->ensureDosen($dosen);

        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(DosenRequest $request, User $dosen): RedirectResponse
    {
        $this->authorizeAdmin();
        $this->ensureDosen($dosen);

        $data = [
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'nomor_induk' => $request->validated('nomor_induk'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->validated('password'));
        }

        $dosen->update($data);

        return redirect()
            ->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(User $dosen): RedirectResponse
    {
        $this->authorizeAdmin();
        $this->ensureDosen($dosen);

        if ($dosen->id === auth()->id()) {
            return back()->withErrors(['dosen' => 'Anda tidak dapat menghapus akun sendiri.']);
        }

        if ($dosen->mahasiswaProfilesDibimbing()->exists()) {
            return back()->withErrors([
                'dosen' => 'Dosen masih memiliki mahasiswa bimbingan. Pindahkan mahasiswa terlebih dahulu.',
            ]);
        }

        $dosen->delete();

        return redirect()
            ->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil dihapus.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    private function ensureDosen(User $user): void
    {
        abort_unless($user->isDosen(), 404);
    }
}
