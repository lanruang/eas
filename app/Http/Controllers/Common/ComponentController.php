<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use App\Http\Models\User\UserModel AS UserDb;
use App\Http\Models\Department\DepartmentModel AS DepartmentDb;
use App\Http\Models\Positions\PositionsModel AS PositionsDb;
use Illuminate\Support\Facades\Input;
use Validator;

class ComponentController extends CommonController
{
    public function ctGetUser(Request $request)
    {
        //验证传输方式
        if (!$request->ajax()) {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //搜索参数
        $searchSql[] = array('users.supper_admin', '=', '0');
        $searchSql[] = array('users.recycle', '=', 0);
        if (array_key_exists('s_u_name', $input)) {
            $searchSql[] = array('users.user_name', 'like', '%' . $input['s_u_name'] . '%');
        }
        $data['searchSql'] = $searchSql;

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = UserDb::where($searchSql)->count();
        //获取数据
        $result = UserDb::leftJoin('users_base AS ub', 'users.user_id', '=', 'ub.user_id')
            ->leftJoin('department AS dep', 'ub.department', '=', 'dep.dep_id')
            ->leftJoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->select('users.user_id AS id', 'users.user_name AS name', 'users.user_email AS email', 'users.status',
                'dep.dep_name', 'pos.pos_name')
            ->where($searchSql)
            ->skip($skip)
            ->take($take)
            ->get()
            ->toArray();

        //创建结果数据
        $data['draw'] = isset($input['draw']) ? intval($input['draw']) : 1;
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //获取部门数据
    public function ctGetDep(Request $request)
    {
        //验证传输方式
        if (!$request->ajax()) {
            echoAjaxJson('-1', '非法请求');
        }

        $result = DepartmentDb::select('dep_id AS id', 'dep_name AS text', 'dep_pid AS pid')
            ->where('status', 1)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        //获取下拉菜单最小pid
        $selectPid = DepartmentDb::where('status', 1)
            ->min('dep_pid');
        $result = !getTreeT($result, $selectPid) ? $result = array() : getTreeT($result, $selectPid);

        //返回结果
        ajaxJsonRes($result);
    }

    //获取岗位数据
    public function ctGetPos(Request $request)
    {
        //验证传输方式
        if (!$request->ajax()) {
            echoAjaxJson('-1', '非法请求');
        }

        $result = PositionsDb::select('pos_id AS id', 'pos_name AS text', 'pos_pid AS pid', 'status')
            ->orderBy('sort', 'asc')
            ->where('status', 1)
            ->get()
            ->toArray();
        //获取下拉菜单最小pid
        $selectPid = PositionsDb::where('status', 1)
            ->min('pos_pid');
        $result = !getTreeT($result, $selectPid) ? $result = array() : getTreeT($result, $selectPid);

        //返回结果
        ajaxJsonRes($result);
    }
}
