<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || !$request->user()->admin || $request->user()->admin->role->nama !== 'Super Admin') {
            abort(403, 'Unauthorized action. Only Super Admin can access this page.');
        }

        return $next($request);
    }
} 
 
 
 
 
 