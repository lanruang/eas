<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\User\UserModel AS UserDb;
use App\Http\Models\Role\RoleModel AS RoleDb;
use App\Http\Models\Department\DepartmentModel AS DepartmentDb;
use App\Http\Models\Positions\PositionsModel AS PositionsDb;
use App\Http\Models\User\UsersBaseModel AS UsersBaseDb;
use App\Http\Models\User\UsersInfoModel AS UsersInfoDb;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Common\CommonController
{
    //员工列表
    public function index()
    {
        return view('user.index');
    }

    //员工列表
    public function getUser(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //搜索参数
        //$searchSql[] = array('supper_admin', '=', '0');
        $searchSql[] = array('recycle', '=', 0);
        if(array_key_exists('s_u_name', $input)){
            $searchSql[] = array('user_name', 'like', '%'.$input['s_u_name'].'%');
        }

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = UserDb::where($searchSql)->count();
        //获取数据
        $result = UserDb::select('user_id AS id', 'user_name AS name', 'user_email AS email', 'status')
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

    //员工详情
    public function userInfo()
    {
        $isSession = 0;
        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'id' => 'between:32,32',
        ];
        $message = [
            'id.integer' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return redirectPageMsg('-1', $validator->errors()->first(), route('user.index'));
        }
        $id = isset($input['id']) ? $input['id'] : '';
        if(!$id){
            $id = session('userInfo.user_id');
            $isSession = '1';
        }

        //获取员工信息
        $data['userInfo'] = UserDb::join('users_base AS ub', 'users.user_id', '=', 'ub.user_id')
            ->join('users_info AS ui', 'users.user_id', '=', 'ui.user_id')
            ->leftjoin('department AS dep', 'ub.department', '=', 'dep.dep_id')
            ->leftjoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->leftJoin('role AS r', 'users.role_id', '=', 'r.id')
            ->select('users.*', 'ub.*', 'ui.*', 'dep.dep_name', 'pos.pos_name', 'r.name AS role_name')
            ->where('users.user_id', $id)
            ->first();

        //获取部门负责人
        $dep_leader = DepartmentDb::leftjoin('users AS u', 'u.user_id', '=', 'department.dep_leader')
            ->select('u.user_name AS dep_leader')
            ->where('department.dep_id', $data['userInfo']->department)
            ->get()
            ->first();
        if($dep_leader){
            $data['userInfo']->dep_leader = $dep_leader->dep_leader;
        }
        /*
        if(!$data['userInfo'] || ($data['userInfo']['supper_admin'] == '1' && session('userInfo.user_id') != $data['userInfo']['user_id'])){
            return redirectPageMsg('-1', "员工不存在", route('user.index'));
        }
        */
        if(!$data['userInfo']){
            return redirectPageMsg('-1', "员工不存在", route('user.index'));
        }
        $data['isSession'] = $isSession;
        $data['userInfo']['status'] = $data['userInfo']['status'] == 1 ? "使用中" : "已禁用";
        return view('user.userInfo', $data);
    }
    
    //修改密码
    public function editPwd(Request $request)
    {
        //验证传输方式
       if(!$request->ajax())
        {
            echoAjaxJson(0, '非法请求');
        }
        //验证表单
        $input = Input::all();
        $rules = [
            'oldPassword' => 'required',
            'password' => 'required|confirmed'
        ];
        $message = [
            'oldPassword.required' => '密码未填写',
            'password.required' => '新密码未填写',
            'password.confirmed' => '2次密码不相同',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson(0, $validator->errors()->first());
        }
        //验证原密码
        $userInfo = UserDb::where('user_id', session('userInfo.user_id'))
                            ->where('password', md5($input['oldPassword']))
                            ->first();
        if(empty($userInfo)){
            echoAjaxJson(0, '原密码错误');
        }
        //更新密码
        $result = UserDb::where('user_id', session('userInfo.user_id'))
                            ->update(['password' => md5($input['password'])]);
        if($result)
        {
            echoAjaxJson(1, '修改成功，请重新登录。');
        }
        echoAjaxJson(0, '修改失败');
    }

    //添加用户视图
    public function addUser()
    {
        //获取角色
        $result = roleDb::select('id', 'name')
            ->where('status', '1')
            ->orderBy('sort', 'asc')
            ->get();
        $data['role'] = $result;

        return view('user.addUser', $data);
    }

    //添加角色
    public function createUser()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'user_name' => 'required|between:1,255',
            'user_email' => 'required|between:1,255|email',
            'department' => 'between:32,32',
            'positions' => 'between:32,32',
        ];
        $message = [
            'user_name.required' => '姓名未填写',
            'user_name.between' => '姓名字符数过多',
            'user_email.required' => '邮箱未填写',
            'user_email.between' => '邮箱字符数过多',
            'user_email.email' => '邮箱格式不正确',
            'department.between' => '部门参数错误',
            'positions.between' => '岗位参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('user.addUser'));
        }

        //姓名是否存在
        $result = UserDb::where('user_email', $input['user_email'])->first();
        if($result){
            return redirectPageMsg('-1', "添加失败，邮箱已存在", route('user.addUser'));
        }

        //格式化数据
        $input['department'] = !$input['department'] ? 0 : $input['department'];
        $input['positions'] = !$input['positions'] ? 0 : $input['positions'];

        //创建员工---事务处理
        $result = DB::transaction(function () use($input) {
            //创建用户登录表数据
            $user_id = getId();
            $userDb = new UserDb();
            $userDb->user_id = $user_id;
            $userDb->user_name = $input['user_name'];
            $userDb->user_email = $input['user_email'];
            $userDb->user_img = "resources/views/template/assets/avatars/user.jpg";
            $userDb->password = md5('123456');
            $userDb->role_id = $input['role_id'];
            //$userDb->supper_admin = 0;
            $userDb->supper_admin = 1;
            $userDb->status = array_key_exists('status', $input) ? 1 : 0;
            $userDb->save();
            //创建用户基础信息表数据
            $userBsDb = new UsersBaseDb();
            $userBsDb->user_id = $user_id;
            $userBsDb->department = $input['department'];
            $userBsDb->positions = $input['positions'];
            $userBsDb->save();
            //创建用户详情表数据
            $userIoDb = new UsersInfoDb();
            $userIoDb->user_id = $user_id;
            $userIoDb->save();
            return true;
        });

        if($result){
            return redirectPageMsg('1', "添加成功", route('user.addUser'));
        }else{
            return redirectPageMsg('-1', "添加失败", route('user.addUser'));
        }
    }

    //编辑员工视图
    public function editUser()
    {
        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'id' => 'required|between:32,32',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.integer' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return redirectPageMsg('-1', $validator->errors()->first(), route('user.index'));
        }
        $id = $input['id'];

        //获取员工信息
        $user = UserDb::join('users_base AS ub', 'users.user_id', '=', 'ub.user_id')
            ->join('users_info AS ui', 'users.user_id', '=', 'ui.user_id')
            ->leftjoin('department AS dep', 'ub.department', '=', 'dep.dep_id')
            ->leftjoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->select('users.*', 'ub.*', 'ui.*', 'dep.dep_name', 'pos.pos_name')
            ->where('users.user_id', $id)
            ->first();

        if(!$user){
            return redirectPageMsg('-1', "参数错误", route('user.index'));
        }

        //获取角色
        $role = roleDb::select('id', 'name')
            ->where('status', '1')
            ->orderBy('sort', 'asc')
            ->get();
        $data['user'] = $user;
        $data['role'] = $role;

        return view('user.editUser', $data);
    }

    //更新员工
    public function updateUser()
    {
        //验证表单
        $input = Input::all();

        $rules = [
            'user_name' => 'required|between:1,255',
            'department' => 'between:32,32',
            'positions' => 'between:32,32',
            'user_id' => 'required|between:32,32',
        ];
        $message = [
            'user_name.required' => '姓名未填写',
            'user_name.between' => '姓名字符数过多',
            'department.between' => '部门参数错误',
            'positions.between' => '岗位参数错误',
            'user_id.required' => '参数不存在',
            'user_id.integer' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('user.editRole')."?id=".$input['user_id']);
        }

        //格式化数据
        $uid = $input['user_id'];
        //登录表
        $data['user_name'] = $input['user_name'];
        $data['role_id'] = $input['role_id'];
        $data['status'] = array_key_exists('status', $input) ? 1 : 0;;
        //基础信息表
        $dataB['department'] = !$input['department'] ? 0 : $input['department'];
        $dataB['positions'] = !$input['positions'] ? 0 : $input['positions'];

        //更新数据---事务处理
        $result = DB::transaction(function () use($uid, $data, $dataB) {
            //更新用户登录表数据
            UserDb::where('user_id', $uid)
                ->update($data);
            //更新用户基础信息表数据
            UsersBaseDb::where('user_id', $uid)
                ->update($dataB);
            //更新用户详情表数据
            //UsersInfoDb::where('user_id', $uid)
                //->update($data);
            return true;
        });

        if($result){
            return redirectPageMsg('1', "编辑成功", route('user.index'));
        }else{
            return redirectPageMsg('-1', "编辑失败", route('user.editUser')."?id=".$uid);
        }
    }

    //删除员工
    public function delUser(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson(0, '非法请求');
        }
        $input = Input::all();

        //过滤信息
        $rules = [
            'id' => 'required|between:32,32',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.between' => '参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }

        //删除员工
        $data['recycle'] = 1;
        $result = UserDb::where('user_id', $input['id'])
            ->update($data);
            //->delete();

        if ($result) {
            echoAjaxJson('1', '删除成功');
        } else {
            echoAjaxJson('-1', '删除失败');
        }
    }
    
    //重置密码
    public function resetPwd(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson(0, '非法请求');
        }
        $input = Input::all();

        //过滤信息
        $rules = [
            'id' => 'required|between:32,32',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.between' => '参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            echoAjaxJson('-1', $validator->errors()->first());
        }

        //格式化数据
        $data['password'] = md5('123456');

        if($input['id'] == '1'){

        }

        //获取员工信息
        $user = UserDb::select('user_id', 'supper_admin')
            ->where('user_id', $input['id'])
            ->first();
        /*
        if(!$user || ($user->supper_admin == '1' && session('userInfo.user_id') != $user->user_id)){
            echoAjaxJson('-1', '参数错误');
        }
        */
        if(!$user){
            echoAjaxJson('-1', '参数错误');
        }
        //更新数据
        $result = UserDb::where('user_id', $input['id'])
            ->update($data);

        if ($result) {
            echoAjaxJson('1', '操作成功, 密码已重置为123456');
        } else {
            echoAjaxJson('-1', '操作失败');
        }
    }
}
