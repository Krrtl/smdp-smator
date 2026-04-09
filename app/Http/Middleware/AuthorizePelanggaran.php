<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthorizePelanggaran
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $role = $request->session()->get('user_role');
        $jabatan = $request->session()->get('user_jabatan');

        // Normalize jabatan for comparison (e.g. "Tata Tertib" -> "tatatertib")
        $jabatanNorm = $jabatan ? strtolower(str_replace(' ', '', $jabatan)) : null;

        // Allow if admin or role == tatatertib or jabatan normalized == tatatertib
        if ($role === 'admin' || $role === 'tatatertib' || $jabatanNorm === 'tatatertib') {
            return $next($request);
        }

        // Otherwise forbid access
        abort(403, 'Akses ditolak.');
    }
}