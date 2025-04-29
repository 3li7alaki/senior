<?php

namespace App\Http\Middleware;

use App\Helpers\Util;
use Closure;
use Illuminate\Http\Request;

class ConvertNullStringsToNull
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        $input = Util::convertNullStringsToNull($input);

        $request->replace($input);

        return $next($request);
    }
}
