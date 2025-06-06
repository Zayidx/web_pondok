<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PSB\PendaftaranSantri;

class CheckSantriAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated via santri guard
        if (Auth::guard('santri')->check()) {
            return $next($request);
        }

        // Check if user has valid PPDB session
        if (session()->has('santri_id')) {
            $santri = PendaftaranSantri::find(session('santri_id'));
            if ($santri && $santri->status_santri === 'sedang_ujian') {
                // Auto-login the user with santri guard
                Auth::guard('santri')->login($santri);
                return $next($request);
            }
        }

        // If neither authentication is valid, redirect to PPDB login
        return redirect()->route('login-ppdb-santri');
    }
} 