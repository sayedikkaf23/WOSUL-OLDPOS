<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
 		if($request->lang == '')
        {
            App::setLocale('ar');
        }
        else
        {
            App::setLocale($request->lang);
        }

        return $next($request);
    }
}
