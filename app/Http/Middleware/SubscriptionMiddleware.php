<?php

namespace App\Http\Middleware;

use Closure;
class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->session()->has('logged_merchant_id')>0)
        {
            if($request->session()->has('uid') || strpos($request->path(),"pricing")!=false)
            {
              return $next($request);
            }
            else
            {
                return redirect()->route('pricing', ['lang' => request()->lang]);
            }   
        }
        else
        {
            if(strpos($request->path(),"login")==false)
            {
                return redirect()->route('login', ['lang' => request()->lang]);
            }
            else
            {
                return $next($request);
            }
        }

        
    }
}