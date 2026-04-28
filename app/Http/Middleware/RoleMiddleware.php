<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $role = session('role');

        // belum login
        if (!$role) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        // role tidak sesuai
        if (!in_array($role, $roles)) {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}