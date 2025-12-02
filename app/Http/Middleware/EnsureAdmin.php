<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->roles !== 'admin') {
            // If it's an AJAX request, return 403 JSON, otherwise redirect back with error.
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Admin only.'], 403);
            }

            return redirect()->route('travels.index')->with('error', 'Akses ditolak. Hanya admin yang dapat menambahkan data travel.');
        }

        return $next($request);
    }
}
