<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', 'en');
        app()->setLocale($locale);
        view()->share('lang', $locale);
        return $next($request);
    }
}
