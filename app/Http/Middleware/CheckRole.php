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
        // First check if user is logged in and has admin relationship
        if (!$request->user() || !$request->user()->admin) {
            abort(403, 'Unauthorized action. Please login as admin.');
        }

        // Get the user's role name
        $userRole = $request->user()->admin->role->nama;

        // Check if the user's role matches the required role
        // Using trim to remove any whitespace and case-insensitive comparison
        if (strtolower(trim($userRole)) !== strtolower(trim($role))) {
            abort(403, "Unauthorized action. You need {$role} role to access this page.");
        }

        return $next($request);
    }
} 
 
 
 
 
 