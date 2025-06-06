<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            if ($request->is('e-ppdb/*')) {
                // Check if user has PPDB session authentication
                if (session()->has('santri_id')) {
                    return null;
                }
                return route('login-ppdb-santri');
            }
            return route('login');
        }
        return null;
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        if (in_array('santri', $guards)) {
            // Check if user has PPDB session authentication
            if (session()->has('santri_id')) {
                return;
            }
            throw new \Illuminate\Auth\AuthenticationException(
                'Unauthenticated.', $guards, route('login-ppdb-santri')
            );
        }

        throw new \Illuminate\Auth\AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }
}
