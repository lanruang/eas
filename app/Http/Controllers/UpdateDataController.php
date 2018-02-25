<?php

namespace App\Http\Controllers;

use App\Http\Models\Fullback\ContDetailsModel;
use App\Http\Models\Invoice\InvoiceDetailsModel;
use App\Http\Models\Invoice\InvoiceModel;
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
use App\Http\Models\Supplier\SupplierModel AS SupplierDb;
use App\Http\Models\Fullback\BudgetModel AS BudDb;
use App\Http\Models\Fullback\BudgetSubjectModel AS BudSubDb;
use App\Http\Models\Fullback\BudgetSubjectDateModel AS BudSubDateDb;
use App\Http\Models\Budget\BudgetModel AS BudgetDb;
use App\Http\Models\Budget\BudgetSubjectModel AS BudgetSubjectDb;
use App\Http\Models\Budget\BudgetSubjectDateModel AS BudgetSubjectDateDb;
use App\Http\Models\Fullback\ContractModel AS ContDb;
use App\Http\Models\Fullback\ContDetailsModel AS ContDetailsDb;
use App\Http\Models\Contract\ContractModel AS ContractDb;
use App\Http\Models\Contract\ContractDetailsModel AS ContractDetailsDb;
use App\Http\Models\Fullback\InvoiceModel AS InvoDb;
use App\Http\Models\Fullback\InvoiceDetailsModel AS InvoDetailsDb;
use App\Http\Models\Invoice\InvoiceModel AS InvoiceDb;
use App\Http\Models\Invoice\InvoiceDetailsModel AS InvoiceDDb;
use App\Http\Models\Fullback\RchsModel AS RchsDb;

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
        /*---------------------------------预算
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
        echo('导入预算数据'.$result.'条<br>');*/
        /*---------------------------------发票
        $invo = InvoDb::get()->toArray();
        echo('导出发票数据'.count($invo).'条<br>');
        $result = DB::transaction(function () use($invo) {
            $x = 0;
            $result = '';
            $invoData = array();
            foreach($invo as $k => $v){
                $id = getId();
                $invo_type = SysAssDb::where('ass_type','invoice_type')
                    ->where('old_id', $v['fpzl'])->first();
                $invoData['invo_id'] = $id;
                $invoData['invo_pid'] = 0;
                $invoData['invo_start_num'] = $v['fpqsh'];
                $invoData['invo_end_num'] = $v['fpzzh'];
                $invoData['invo_buy_date'] = date('Y-m-d', $v['gmrq']);
                $invoData['invo_text'] = '';
                $invoData['invo_type'] = $invo_type->ass_id;
                $invoData['old_id'] = $v['id'];
                $invoData['old_company'] = $v['gs'];
                $invoData['created_user'] = 'A4049242B8B6B3323A8E2269261761E0';
                $invoData['created_at'] = date('Y-m-d H:i:s', time());
                $invoData['updated_at'] = date('Y-m-d H:i:s', time());
                $result = InvoiceDb::insert($invoData);
                $x = $result ? $x + 1 : $x;
            }
            return $x;
        });
        echo('导入发票数据'.$result.'条');*/
        /*---------------------------------发票明细
        $invo = InvoDetailsDb::get()->toArray();
        echo('导出发票明细数据'.count($invo).'条<br>');
        $result = DB::transaction(function () use($invo) {
            $x = 0;
            $result = '';
            $invoData = array();
            foreach($invo as $k => $v){
                $id = getId();
                $invo_id = InvoiceDb::where('old_id', $v['fpgr_id'])->first();
                $invoData['invo_details_id'] = $id;
                $invoData['invo_id'] = $invo_id->invo_id;
                $invoData['invo_num'] = formatInvoice($v['fphm']);
                $invoData['invo_status'] = $v['fpzt'] == '1' ? '401' : '400';
                $invoData['invo_write_user'] = formatInvoice($v['fphm']);
                $invoData['invo_write_date'] = formatInvoice($v['fphm']);
                $invoData['invo_class'] = 'inside';
                $invoData['created_at'] = date('Y-m-d H:i:s', time());
                $invoData['updated_at'] = date('Y-m-d H:i:s', time());
                $invoData['old_id'] = $v['id'];
                $result = InvoiceDDb::insert($invoData);
                $x = $result ? $x + 1 : $x;
            }
            return $x;
        });
        echo('导入发票数据明细'.$result.'条');*/
        /*---------------------------------获取合同
        $cont = ContDb::from('ht AS ht')
            ->leftjoin('ht_name AS hn', 'hn.id', '=', 'ht.htname')
            ->where('ht.review', 'yes')
            ->where('ht.htstart', '>=', strtotime('2017-01-01'))
            ->select('ht.*', 'hn.name')
            ->get()->toArray();
        echo('导出数据合同'.count($cont).'条<br>');
        $contData = array();
        $result = DB::transaction(function () use($cont) {
            $x = 0;
            $result = '';
            foreach($cont as $k => $v){
                $id = getId();
                $cont_type = SysAssDb::where('ass_type', 'contract_type')
                    ->where('old_id', $v['type'])
                    ->first();
                $cont_budget = BudgetDb::where('old_id', $v['ys'])->first();
                $cont_subject = SubjectDb::where('old_id', $v['km'])->first();
                if($v['sz_type'] == 'sr'){
                    $cont_parties = CustomerDb::where('old_id', $v['htf'])->first();
                    $cont_parties = $cont_parties->cust_id;
                }else{
                    $cont_parties = SupplierDb::where('old_id', $v['htf'])->first();
                    $cont_parties = $cont_parties->supp_id;
                }
                $contData['old_id'] = $v['id'];
                $contData['cont_id'] = $id;
                $contData['cont_type'] = $cont_type->ass_id;
                $contData['cont_class'] = $v['sz_type'] == 'sr' ? '88400D89A9DE71258069C6262DEFC34C' : '1ED7BB45826EE056124DF47A93CB9072';
                $contData['cont_budget'] = $cont_budget ? $cont_budget->budget_id : '';
                $contData['cont_subject'] = $cont_subject->sub_id;
                $contData['cont_num'] = $v['htid'];
                $contData['cont_name'] = $v['name'];
                $contData['cont_name'] = $v['name'];
                $contData['cont_name'] = $v['name'];
                $contData['cont_name'] = $v['name'];
                $contData['cont_name'] = $v['name'];
                $contData['cont_parties'] = $cont_parties;
                $contData['cont_start'] = date('Y-m-d', $v['htstart']);
                $contData['cont_end'] = date('Y-m-d', $v['htfinish']);
                $contData['cont_status'] = '301';
                $contData['cont_sum_amount'] = '0';
                $contData['cont_remark'] = '';
                $contData['cont_auto'] = '0';
                $contData['created_user'] = 'A4049242B8B6B3323A8E2269261761E0';
                $contData['created_at'] = date('Y-m-d H:i:s', time());
                $contData['updated_at'] = date('Y-m-d H:i:s', time());
                $result = ContractDb::insert($contData);
                $x = $result ? $x + 1 : $x;
            }
            return $x;
        });
        echo('导入数据'.$result.'条');*/
        /*---------------------------------获取合同明细*/
        $cont = ContDetailsDb::where('review', 'yes')
            ->whereBetween('htrq', array(strtotime('2017-01-01'),strtotime('2018-12-31')))
            ->get()->toArray();
        echo('导出数据合同明细'.count($cont).'条<br>');
        $contData = array();
        $result = DB::transaction(function () use($cont) {
            $x = 0;
            $result = '';
            foreach($cont as $k => $v){
                $id = getId();
                $cont_handle_status = 000;
                $cont_id = ContractDb::where('old_id', $v['ht'])->first();
                if($cont_id){
                    if($v['check_ys'] == '1' && $v['check_kp'] == '0' && $v['check'] == '0'){
                        $cont_handle_status = '100';
                    }
                    if($v['check_ys'] == '1' && $v['check_kp'] == '1' && $v['check'] == '0'){
                        $cont_handle_status = '110';
                    }
                    if($v['check_ys'] == '1' && $v['check_kp'] == '1' && $v['check'] == '1'){
                        $cont_handle_status = '111';
                    }
                    $contData['details_id'] = $id;
                    $contData['cont_id'] = $cont_id->cont_id;
                    $contData['cont_details_date'] = date('Y-m-d', $v['htrq']);
                    $contData['cont_amount'] = $v['htje'];
                    $contData['cont_status'] = '301';
                    $contData['cont_handle_status'] = $cont_handle_status;
                    $contData['created_at'] = date('Y-m-d H:i:s', time());
                    $contData['updated_at'] = date('Y-m-d H:i:s', time());
                    //$result = ContractDb::insert($contData);
                    //$x = $result ? $x + 1 : $x;
                    //合同核销数据
                    /*
                    if($v['check_ys'] == '1'){
                        $htType = $v['sz_type'] == 'sr' ? '应收款生成' : '应付款生成' ;
                        $ht = RchsDb::where('ht_hx', $v['id'])
                            ->where('title', 'like', '%'.$htType.'%')
                            ->get()->toArray();
                        if(count($ht) == '2'){
                            $debit = SubjectDb::where('old_id', $ht[1]['km'])->first();
                            $subject_id_debit = $debit->sub_id;
                            $credit = SubjectDb::where('old_id', $ht[0]['km'])->first();
                            $subject_id_credit = $credit->sub_id;
                            $receivable = $cont_id->cont_class == '88400D89A9DE71258069C6262DEFC34C' ? 'receivable' : 'payable' ;
                            $data['cont_main_id'] = getId();
                            $data['cont_main_type'] = $receivable;
                            $data['cont_id'] = $cont_id->cont_id;
                            $data['details_id'] = $id;
                            $data['cont_amount'] = $v['htje'];
                            $data['budget_id'] = $cont_id->cont_budget;
                            $data['subject_id_debit'] = $subject_id_debit;
                            $data['subject_id_credit'] = $subject_id_credit;
                            $data['created_user'] = 'A4049242B8B6B3323A8E2269261761E0';
                            $data['created_at'] = date('Y-m-d H:i:s', time());
                            $data['updated_at'] = date('Y-m-d H:i:s', time());
                            //
                        }else{
                            echo('hthx_ys'.$v['id']."<br>");
                        }
                    }
                    */
                    if($v['check_kp'] == '1'){
                        $htType = $v['sz_type'] == 'sr' ? '开票生成' : '收票生成' ;
                        $ht = RchsDb::where('ht_hx', $v['id'])
                            ->where('title', 'like', '%'.$htType.'%')
                            ->get()->toArray();
                        if(count($ht) == '2'){
                            $debit = SubjectDb::where('old_id', $ht[1]['km'])->first();
                            $subject_id_debit = $debit->sub_id;
                            $credit = SubjectDb::where('old_id', $ht[0]['km'])->first();
                            $subject_id_credit = $credit->sub_id;
                            $receivable = $cont_id->cont_class == '88400D89A9DE71258069C6262DEFC34C' ? 'invoOpen' : 'invoCollect' ;
                            $data['cont_main_id'] = getId();
                            $data['cont_main_type'] = $receivable;
                            $data['cont_id'] = $cont_id->cont_id;
                            $data['details_id'] = $id;
                            $data['cont_amount'] = $v['htje'];
                            $data['budget_id'] = $cont_id->cont_budget;
                            $data['subject_id_debit'] = $subject_id_debit;
                            $data['subject_id_credit'] = $subject_id_credit;
                            $data['created_user'] = 'A4049242B8B6B3323A8E2269261761E0';
                            $data['created_at'] = date('Y-m-d H:i:s', time());
                            $data['updated_at'] = date('Y-m-d H:i:s', time());
                            if($v['sz_type']){
                                
                            }else{
                                
                            }
                        }else{
                            echo('hthx_kp'.$v['id']."<br>");
                        }
                    }
                    /*
                    if($v['check'] == '1'){
                        $htType = $v['sz_type'] == 'sr' ? '收款确认' : '付款确认' ;
                        $ht = RchsDb::where('ht_hx', $v['id'])
                            ->where('title', 'like', '%'.$htType.'%')
                            ->get()->toArray();
                        if(count($ht) == '2'){
                            $debit = SubjectDb::where('old_id', $ht[1]['km'])->first();
                            $subject_id_debit = $debit->sub_id;
                            $credit = SubjectDb::where('old_id', $ht[0]['km'])->first();
                            $subject_id_credit = $credit->sub_id;
                            $receivable = $cont_id->cont_class == '88400D89A9DE71258069C6262DEFC34C' ? 'income' : 'payment' ;
                            $data['cont_main_id'] = getId();
                            $data['cont_main_type'] = $receivable;
                            $data['cont_id'] = $cont_id->cont_id;
                            $data['details_id'] = $id;
                            $data['cont_amount'] = $v['htje'];
                            $data['budget_id'] = $cont_id->cont_budget;
                            $data['subject_id_debit'] = $subject_id_debit;
                            $data['subject_id_credit'] = $subject_id_credit;
                            $data['created_user'] = 'A4049242B8B6B3323A8E2269261761E0';
                            $data['created_at'] = date('Y-m-d H:i:s', time());
                            $data['updated_at'] = date('Y-m-d H:i:s', time());
                            //
                        }else{
                            echo('hthx_js'.$v['id']."<br>");
                        }
                    }

                    if($v['check'] == '1'){
                        $ht = RchsDb::where('ht_hx', $v['id'])
                            ->where('title', 'like', '%自动结转%')
                            ->get()->toArray();
                        if(count($ht) == '2'){
                            $debit = SubjectDb::where('old_id', $ht[1]['km'])->first();
                            $subject_id_debit = $debit->sub_id;
                            $credit = SubjectDb::where('old_id', $ht[0]['km'])->first();
                            $subject_id_credit = $credit->sub_id;
                            $receivable = $cont_id->cont_class == '88400D89A9DE71258069C6262DEFC34C' ? 'incomeAuto' : 'paymentAuto' ;
                            $data['cont_main_id'] = getId();
                            $data['cont_main_type'] = $receivable;
                            $data['cont_id'] = $cont_id->cont_id;
                            $data['details_id'] = $id;
                            $data['cont_amount'] = $v['htje'];
                            $data['budget_id'] = $cont_id->cont_budget;
                            $data['subject_id_debit'] = $subject_id_debit;
                            $data['subject_id_credit'] = $subject_id_credit;
                            $data['created_user'] = 'A4049242B8B6B3323A8E2269261761E0';
                            $data['created_at'] = date('Y-m-d H:i:s', time());
                            $data['updated_at'] = date('Y-m-d H:i:s', time());
                            //
                        }else{
                            echo('hthx_zd'.$v['id']."<br>");
                        }
                    }
                    */
                }else{
                    echo('htid='.$v['ht']."<br>");
                }
            }
            return $x;
        });
        echo('导入数据合同明细'.$result.'条');

    }

}
