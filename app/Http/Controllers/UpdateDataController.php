<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Models\Fullback\ContractTypeModel AS ContractTypeDb;
use App\Http\Models\System\SysAssemblyModel AS SysAssDb;
use App\Http\Models\Fullback\DepartmentModel AS DepDb;
use App\Http\Models\Department\DepartmentModel AS DepartmentDb;
use App\Http\Models\Positions\PositionsModel AS PositionsDb;
use App\Http\Models\Fullback\PositionsModel AS PosDb;
use App\Http\Models\Fullback\CustomerTypeModel AS CustTypeDb;
use App\Http\Models\Subjects\SubjectsModel AS SubjectDb;
use App\Http\Models\Fullback\SubjectModel AS SubDb;
use App\Http\Models\User\UserModel AS UserDb;
use App\Http\Models\User\UsersBaseModel AS UsersBaseDb;
use App\Http\Models\User\UsersInfoModel AS UsersInfoDb;
use App\Http\Models\Fullback\UserModel AS UsDb;
use App\Http\Models\Customer\CustomerModel AS CustomerDb;
use App\Http\Models\Fullback\BudgetModel AS BudDb;
use App\Http\Models\Fullback\BudgetSubjectModel AS BudSubDb;
use App\Http\Models\Fullback\BudgetSubjectDateModel AS BudSubDateDb;
use App\Http\Models\Budget\BudgetModel AS BudgetDb;
use App\Http\Models\Budget\BudgetSubjectModel AS BudgetSubjectDb;
use App\Http\Models\Budget\BudgetSubjectDateModel AS BudgetSubjectDateDb;

class UpdateDataController extends Common\CommonController
{
    //用户列表
    public function index()
    {
        set_time_limit(0);
        /*---------------------------------合同类型
        $cont_type = ContractTypeDb::get()->toArray();
        echo('导出数据'.count($cont_type).'条<br>');
        //格式化合同类型数据
        $contType = array();
        $x = 0;
        $result = '';
        foreach($cont_type as $k => $v){
            $id = getId();
            $contType['old_id'] = $v['id'];
            $contType['ass_id'] = $id;
            $contType['ass_type'] = 'contract_type';
            $contType['ass_text'] = $v['name'];
            $contType['ass_value'] = $id;
            $contType['ass_sort'] = $k+1;
            $contType['created_at'] = date('Y-m-d H:i:s', time());
            $contType['updated_at'] = date('Y-m-d H:i:s', time());
            $result = SysAssDb::insert($contType);
            $x = $result ? $x+1 : $x;
        }
        echo('导入数据'.$x.'条');*/
        /*---------------------------------部门
        $dep = DepDb::get()->toArray();
        echo('导出数据'.count($dep).'条<br>');
        $depData = array();
        $x = 0;
        $result = '';
        foreach($dep as $k => $v){
            $id = getId();
            $depData['dep_id'] = $id;
            $depData['dep_name'] = $v['name'];
            $depData['dep_leader'] = 0;
            $depData['dep_pid'] = 0;
            $depData['status'] = 1;
            $depData['sort'] = $k+1;
            $depData['old_id'] = $v['id'];
            $result = DepartmentDb::insert($depData);
            $x = $result ? $x+1 : $x;
        }
        echo('导入数据'.$x.'条');*/
        /*---------------------------------岗位
        $pos = PosDb::get()->toArray();
        echo('导出数据'.count($pos).'条<br>');
        $posData = array();
        $x = 0;
        $result = '';
        foreach($pos as $k => $v){
            $id = getId();
            $posData['pos_id'] = $id;
            $posData['pos_name'] = $v['name'];
            $posData['pos_pid'] = 0;
            $posData['status'] = 1;
            $posData['sort'] = $k+1;
            $posData['old_id'] = $v['id'];
            $result = PositionsDb::insert($posData);
            $x = $result ? $x+1 : $x;
        }
        echo('导入数据'.$x.'条');*/
        /*---------------------------------客户类型
                $custType = CustTypeDb::get()->toArray();
                echo('导出数据'.count($custType).'条<br>');
                $custTypeData = array();
                $x = 0;
                $result = '';
                foreach($custType as $k => $v){
                    $id = getId();
                    $custTypeData['old_id'] = $v['id'];
                    $custTypeData['ass_id'] = $id;
                    $custTypeData['ass_type'] = 'customer_type';
                    $custTypeData['ass_text'] = $v['name'];
                    $custTypeData['ass_value'] = $id;
                    $custTypeData['ass_sort'] = $k+1;
                    $custTypeData['created_at'] = date('Y-m-d H:i:s', time());
                    $custTypeData['updated_at'] = date('Y-m-d H:i:s', time());
                    $result = SysAssDb::insert($custTypeData);
                    $x = $result ? $x+1 : $x;
                }
                echo('导入数据'.$x.'条');*/
        /*---------------------------------科目
        $sub = SubDb::get()->toArray();
        echo('导出数据'.count($sub).'条<br>');
        $subData = array();
        $keyS = array();
        foreach($sub as $k => $v){
            $id = getId();
            $keyS[$v['id']] = $id;
            $subData[$k]['old_id'] = $v['id'];
            $subData[$k]['old_pid'] = $v['pid'];
            $subData[$k]['sub_id'] = $id;
            $subData[$k]['sub_type'] = $v['type'];
            $subData[$k]['sub_ip'] = $v['kmip'];
            $subData[$k]['sub_name'] = $v['title'];
            $subData[$k]['sub_pid'] = '';
            $subData[$k]['status'] = $v['status'];
            $subData[$k]['sort'] = 0;
            $subData[$k]['created_at'] = date('Y-m-d H:i:s', time());
            $subData[$k]['updated_at'] = date('Y-m-d H:i:s', time());
        }
        foreach($subData as $k => $v){
            $subData[$k]['sub_pid'] = $v['old_pid'] != 0 ? $keyS[$v['old_pid']] : 0;
        }
        $x = 0;
        $result = '';
        foreach($subData as $k => $v){
            $result = SubjectDb::insert($v);
            $x = $result ? $x+1 : $x;
        }
        echo('导入数据'.$x.'条');*/
        /*---------------------------------用户
        $user = UsDb::where('id', '<>', '1')->get()->toArray();
        echo('导出数据'.count($user).'条<br>');
        $userData = array();
        $x = 0;
        $result = '';
        foreach($user as $k => $v){
            $id = getId();
            $userData['old_id'] = $v['id'];
            $userData['user_id'] = $id;
            $userData['user_name'] = $v['usernick'];
            $userData['user_email'] = $v['username'].'@'.'fullback.com';
            $userData['user_img'] = 'resources/views/template/assets/avatars/user.jpg';
            $userData['password'] = $v['password'] == '' ? md5('123456') : $v['password'];
            $userData['role_id'] = '';
            $userData['supper_admin'] = 0;
            $userData['status'] = $v['status'];
            $userData['recycle'] = 0;
            $userData['created_at'] = date('Y-m-d H:i:s', time());
            $userData['updated_at'] = date('Y-m-d H:i:s', time());

            //获取部门信息
            $userDep['user_id'] = $id;
            $userDep['department'] = '';
            $userDep['positions'] = '';
            $userDep['created_at'] = date('Y-m-d H:i:s', time());
            $userDep['updated_at'] = date('Y-m-d H:i:s', time());
            $dep = DepartmentDb::where('old_id', $v['bm'])->first();
            if($dep){
                $userDep['department'] = $dep['dep_id'];
            }
            $pos = PositionsDb::where('old_id', $v['gw'])->first();
            if($dep){
                $userDep['positions'] = $pos['pos_id'];
            }
            //获取其他信息
            $userOther['user_id'] = $id;
            $userOther['created_at'] = date('Y-m-d H:i:s', time());
            $userOther['updated_at'] = date('Y-m-d H:i:s', time());

            $result = DB::transaction(function () use($userData, $userDep, $userOther) {
                UserDb::insert($userData);
                UsersBaseDb::insert($userDep);
                UsersInfoDb::insert($userOther);
                return 1;
            });
            $x = $result ? $x+1 : $x;
        }
        echo('导入数据'.$x.'条');*/
        /*---------------------------------客户
        $cust = CustDb::where('pid_back', 0)->get()->toArray();
        echo('导出数据'.count($cust).'条<br>');
        $custData = array();
        $result = DB::transaction(function () use($cust) {
            $x = 0;
            $result = '';
            foreach($cust as $k => $v){
                $id = getId();
                $custData['old_id'] = $v['id'];
                $custData['old_type'] = $v['type'];
                $custData['old_dep'] = $v['bm'];
                $custData['cust_id'] = $id;
                $custData['cust_num'] = $v['khid'];
                $custData['cust_name'] = $v['name'];
                $custData['cust_status'] = $v['status'] == 0 ? 1 : 0;
                $custData['cust_phone'] = $v['kh_dh'];
                $custData['cust_address'] = $v['kh_dz'];
                $custData['cust_website'] = $v['kh_wz'];
                $custData['cust_tax_num'] = '';
                $custData['cust_join_time'] = $v['kh_ksfwrq'] != 0 ? date('Y-m-d H:i:s', $v['kh_ksfwrq']) : '';
                $custData['cust_end_time'] = $v['kh_zzfwrq'] != 0 ? date('Y-m-d H:i:s', $v['kh_zzfwrq']) : '';
                $custData['cust_remark'] = $v['kh_remark'];
                $custData['cust_fax'] = $v['kh_cz'];
                $custData['cust_type'] = '';
                $custData['created_at'] = date('Y-m-d H:i:s', time());
                $custData['updated_at'] = date('Y-m-d H:i:s', time());
                $result = CustomerDb::insert($custData);
                $x = $result ? $x+1 : $x;
            }
            return $x;
        });
        echo('导入数据'.$result.'条');*/
        /*---------------------------------供应商
        $supp = SuppDb::where('pid_back', 0)->get()->toArray();
        echo('导出数据'.count($supp).'条<br>');
        $suppData = array();
        $result = DB::transaction(function () use($supp) {
            $x = 0;
            $result = '';
            foreach($supp as $k => $v){
                $id = getId();
                $suppData['old_id'] = $v['id'];
                $suppData['old_type'] = $v['type'];
                $suppData['old_dep'] = $v['bm'];
                $suppData['supp_id'] = $id;
                $suppData['supp_num'] = $v['gysid'];
                $suppData['supp_name'] = $v['name'];
                $suppData['supp_status'] = $v['status'] == 0 ? 1 : 0;
                $suppData['supp_phone'] = $v['phone'];
                $suppData['supp_fax'] = '';
                $suppData['supp_address'] = $v['dz'];
                $suppData['supp_website'] = '';
                $suppData['supp_tax_num'] = '';
                $suppData['supp_join_time'] = '';
                $suppData['supp_end_time'] = '';
                $suppData['supp_remark'] = '';
                $suppData['supp_type'] = '';
                $suppData['created_at'] = date('Y-m-d H:i:s', time());
                $suppData['updated_at'] = date('Y-m-d H:i:s', time());
                $result = SupplierDb::insert($suppData);
                $x = $result ? $x+1 : $x;
            }
            return $x;
        });
        echo('导入数据'.$result.'条');*/
        /*---------------------------------供应商*/
        $Bud = BudDb::whereIn('id', ['11', '12', '13', '14', '15'])->get()->toArray();
        echo('导出预算数据'.count($Bud).'条<br>');
        $BudData = array();
        $BudSubData = array();
        $BudSubDateData = array();
        $result = DB::transaction(function () use($Bud) {
                $x = 0;
                $resultD = '';
                foreach($Bud as $bk => $bv){
                    $dep = DepartmentDb::where('old_id', $bv['bm'])->first();
                    $id = getId();
                    $BudData['old_id'] = $bv['id'];
                    $BudData['old_dep'] = $bv['bm'];
                    $BudData['budget_id'] = $id;
                    $BudData['department'] = $dep['dep_id'];
                    $BudData['budget_ids'] = '';
                    $BudData['budget_period'] = 'month';
                    $BudData['budget_sum'] = 0;
                    $BudData['create_user'] = 0;
                    $BudData['budget_num'] = $bv['ysid'];
                    $BudData['budget_name'] = $bv['name'];
                    $BudData['budget_start'] = 0;
                    $BudData['budget_end'] = 0;
                    $BudData['status'] = 1;
                    $BudData['created_at'] = date('Y-m-d H:i:s', time());
                    $BudData['updated_at'] = date('Y-m-d H:i:s', time());
                    //获取预算科目数据
                    $BudSub = BudSubDb::where('ys', $bv['id'])->where('review', 'yes')->get()->toArray();
                    echo('导出预算科目数据'.$bv['name']."-".count($BudSub).'条<br>');
                    $sbX = 0;
                    $result = '';
                    foreach($BudSub as $sbk => $sbv){
                        $sub = SubjectDb::where('old_id', $sbv['km'])->first();
                        $BudSubData['old_id'] = $sbv['id'];
                        $BudSubData['old_sub_id'] = $sbv['km'];
                        $BudSubData['budget_id'] = $id;
                        $BudSubData['subject_id'] = $sub['sub_id'];
                        $BudSubData['sum_amount'] = 0;
                        $BudSubData['status'] = 1;
                        $BudSubData['created_at'] = date('Y-m-d H:i:s', time());
                        $BudSubData['updated_at'] = date('Y-m-d H:i:s', time());
                        $result = BudgetSubjectDb::insert($BudSubData);
                        $sbX = $result ? $sbX+1 : $sbX;
                    }
                    echo('导入预算科目数据'.$bv['name']."-".$sbX.'条<br>');
                    //获取预算科目数据
                    $BudSubDate = BudSubDateDb::where('ys', $bv['id'])->where('review', 'yes')->get()->toArray();
                    echo('导出预算科目金额数据'.$bv['name']."-".count($BudSubDate).'条<br>');
                    $sbdX = 0;
                    $result = '';
                    foreach($BudSubDate as $sbdk => $sbdv){
                        $sub_id = BudSubDb::where('id', $sbdv['yskm'])->first();
                        $sub = SubjectDb::where('old_id', $sub_id['km'])->first();
                        $BudSubDataData['old_id'] = $sbdv['ys'];
                        $BudSubDataData['budget_id'] = $id;
                        $BudSubDataData['subject_id'] = $sub['sub_id'];
                        $BudSubDataData['budget_date'] = date('Y-m', $sbdv['sjfw']);
                        $BudSubDataData['budget_date_str'] = strtotime($BudSubDataData['budget_date']);
                        $BudSubDataData['budget_amount'] = $sbdv['ysje'];
                        $BudSubDataData['status'] = 1;
                        $BudSubDataData['created_at'] = date('Y-m-d H:i:s', time());
                        $BudSubDataData['updated_at'] = date('Y-m-d H:i:s', time());
                        $result = BudgetSubjectDateDb::insert($BudSubDataData);
                        BudgetSubjectDb::where('old_id', $sbdv['yskm'])
                            ->increment('sum_amount', $sbdv['ysje']);
                        $sbdX = $result ? $sbdX+1 : $sbdX;
                    }
                    echo('导入预算科目金额数据'.$bv['name']."-".$sbdX.'条<br>');
                    $resultD = BudgetDb::insert($BudData);
                    $x = $resultD ? $x + 1 : $x;
                }
                return $x;
        });
        echo('导入预算数据'.$result.'条<br>');
    }

}
