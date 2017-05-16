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
                echoAjaxJson('-1', '登录超时，请重新登录');
            } else {
                return redirect(route('login.index'));
            }
        }

        if(!in_array($request->route()->getName(), session('userInfo.permission')) && session('userInfo.supper_admin') == '0'){
            if ($request->ajax() || $request->wantsJson()) {
                echoAjaxJson('-1', '没有权限');
            } else {
                redirectPageMsg('-1', "没有权限", route('main.index'));
            }
        }

        return $next($request);
    }

}
