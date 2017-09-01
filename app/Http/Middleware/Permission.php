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

        if(!in_array($request->route()->getName(), session('userInfo.permission')) && session('userInfo.supper_admin') == '0' && !in_array($request->route()->getName(), session('userInfo.not_permission'))){
            if ($request->ajax() || $request->wantsJson()) {
                echoAjaxJson('-1', '没有权限');
            } else {
                $rel['status'] = base64_encode('-1');
                $rel['msg'] = base64_encode('没有权限');
                $rel['url'] = base64_encode(route('main.index'));
                return redirect(route('component.ctRedirectMsg', $rel));
            }
        }

        switch ($request->route()->getPrefix()) {
            case '/budget':
                if(!session('userInfo.sysConfig.budget.subBudget')){
                    $rel['status'] = base64_encode('-1');
                    $rel['msg'] = base64_encode('预算科目未设置，无法使用该功能');
                    $rel['url'] = base64_encode(route('main.index'));
                    return redirect(route('component.ctRedirectMsg', $rel));
                }
            break;
            case '/reimburse':
                if(!session('userInfo.sysConfig.reimburse.userCashier')){
                    $rel['status'] = base64_encode('-1');
                    $rel['msg'] = base64_encode('出纳岗位未设置，无法使用该功能');
                    $rel['url'] = base64_encode(route('main.index'));
                    return redirect(route('component.ctRedirectMsg', $rel));
                }
            break;
            case '/reimbursePay':
                if(!session('userInfo.sysConfig.reimbursePay.subPay')){
                    $rel['status'] = base64_encode('-1');
                    $rel['msg'] = base64_encode('费用报销付款方式未设置，无法使用该功能');
                    $rel['url'] = base64_encode(route('main.index'));
                    return redirect(route('component.ctRedirectMsg', $rel));
                }
            break;
        }

        return $next($request);
    }

}
