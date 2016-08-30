<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;

class Permission
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
        $userInfo = $request->session()->has('userInfo');
        if(!$userInfo){
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect('login');
            }
        }

        return $next($request);
    }
}
