<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitcher
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->cookie('locale', 'id');
        
        if (in_array($locale, ['id', 'en'])) {
            App::setLocale($locale);
        } else {
            App::setLocale('id');
        }

        return $next($request);
    }
}