<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('user') || !Session::get('user.logged_in')) {
            return redirect()->route('login')
                ->with('error', 'Anda harus login terlebih dahulu');
        }

        return $next($request);
    }
}