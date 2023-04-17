<?php

namespace App\Http\Middleware;

use App\Models\WebsiteService;
use App\Models\WebsiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class WebMenuMiddleware
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

        $website_settings = WebsiteSetting::all();
        foreach ($website_settings as $setting) {
            View::share(strtolower($setting->key), $setting->value);
        }

        return $next($request);
    }
}
