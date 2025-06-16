<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectToCheckStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('santri')->check()) {
            $santri = Auth::guard('santri')->user();
            
            // Jika belum pernah mengakses check-status, arahkan ke sana
            if (!session()->has('has_checked_status')) {
                session(['has_checked_status' => true]);
                return redirect()->route('e-ppdb.check-status');
            }
        }

        return $next($request);
    }
} 