<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->query('locale', 'ar') == 'ar') {
            App::setLocale('ar');
            Auth::user()?->update(['locale' => 'ar']);
        } else {
            App::setLocale('en');
            Auth::user()?->update(['locale' => 'en']);
        }
        return $next($request);
    }
}
