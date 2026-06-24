<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMahasiswaHasDosen
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->isMahasiswa()) {
            return $next($request);
        }

        if ($user->mahasiswaProfile?->dosen_pembimbing_id !== null) {
            return $next($request);
        }

        if ($request->routeIs(
            'dashboard',
            'dosen-pembimbing.*',
            'profile.*',
            'logout',
        )) {
            return $next($request);
        }

        return redirect()
            ->route('dosen-pembimbing.show')
            ->with('warning', 'Pilih dosen pembimbing terlebih dahulu untuk melanjutkan.');
    }
}
