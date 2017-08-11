<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use App\Http\Models\Notice\NoticeModel AS NoticeDb;
use App\Http\Models\Expense\ExpenseModel AS ExpenseDb;
use Illuminate\Support\Facades\DB;
use Validator;

class NoticeController extends Common\CommonController
{
    public function index()
    {
        return view('notice.index');
    }

    //获取报销列表
    public function getNotice(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //搜索参数
        $searchSql[] = array('notice_user', session('userInfo.user_id'));

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = NoticeDb::where($searchSql)
        ->whereIn('notice_type', array('0','1'))->count();

        //获取数据
        $result = NoticeDb::where($searchSql)
            ->whereIn('notice_type', array('0','1'))
            ->select('notice_id', 'notice_message', 'created_at AS add_time', 'is_see AS see',
                'is_check AS check', 'notice_type AS type')
            ->skip($skip)
            ->take($take)
            ->orderBy('is_check', 'asc')
            ->orderBy('is_see', 'asc')
            ->orderBy('notice_type', 'desc')
            ->orderBy('created_at', 'desc')
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
    
    //阅读通知
    public function noticeRead($id = 0)
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            return redirectPageMsg('-1', '参数错误', route('notice.index'));
        };

        //获取消息
        $result = NoticeDb::from('notice AS not')
            ->leftjoin('users AS u', 'u.user_id', '=', 'not.post_user')
            ->select('not.notice_id', 'not.notice_type', 'not.notice_message', 'not.is_check',
                'u.user_name', 'not.created_at', 'not.notice_class', 'not.notice_remark',
                'not.notice_value', 'not.post_user')
            ->where('not.notice_id', $id)
            ->where('not.notice_user', session('userInfo.user_id'))
            ->get()
            ->first();
        if(!$result){
            return redirectPageMsg('-1', '内容不存在', route('notice.index'));
        }
        //下拉菜单
        $switchData['notice_class'] = $result['notice_class'];
        $switchData['post_user'] = $result['post_user'];
        switch ($switchData){
            case ($switchData['notice_class'] == 'reimburse' && $result['post_user'] == 0):
                $select[] = array('value'=>1, 'text'=>'已付款');
                $select[] = array('value'=>0, 'text'=>'其他');
            break;
            case ($switchData['notice_class'] == 'reimburse' && $result['post_user'] != 0):
                $select[] = array('value'=>1, 'text'=>'确认收款');
            break;
        }

        if($result['notice_type'] == 1){
            $result['select'] = $select;
        }

        //更新查看点击
        NoticeDb::where('notice_id', $id)
            ->where('notice_user', session('userInfo.user_id'))
            ->update(array('is_see'=>1));

        return view('notice.noticeRead', $result);
    }

    //更新消息
    public function updateNotice()
    {
        //验证表单
        $input = Input::all();

        $rules = [
            'notice_value' => 'required',
            'notice_msg' => 'required_if:notice_value,0',
            'notice_id' => 'required|digits_between:0,11|numeric',
        ];
        $message = [
            'notice_value.required' => '请选择提交结果',
            'notice_msg.required_if' => '请填写备注',
            'notice_id.required' => '参数不存在',
            'notice_id.digits_between' => '参数错误',
            'notice_id.numeric' => '参数类型错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('notice.noticeRead')."/".$input['notice_id']);
        }

        $id = $input['notice_id'];
        $notice = NoticeDb::where('notice_id', $id)
                ->where('notice_user', session('userInfo.user_id'))
                ->get()
                ->first();
        if(!$notice){
            return redirectPageMsg('-1', '提交失败，消息通知不存在，请重试', route('notice.index'));
        }

        //事务处理
        $result = DB::transaction(function () use($input, $notice) {
            $result['rel'] = true;
            switch ($notice['notice_class'])
            {
                case "reimburse":
                    $result = $this->updateReimburse($notice, $input);
                    break;
            }
            if($result['rel']){
                $data['notice_value'] = $input['notice_value'];
                $data['notice_remark'] = $input['notice_msg'];
                $data['is_check'] = 1;
                //更新消息通知
                NoticeDb::where('notice_id', $input['notice_id'])
                    ->update($data);
            }
            return $result;
        });
        if($result['rel']){
            return redirectPageMsg('1', '提交成功', route('notice.noticeRead').'/'.$id);
        }else{
            return redirectPageMsg('-1', '提交失败，'.$result['msg'].'请刷新重试', route('notice.noticeRead').'/'.$id);
        }
    }

    //更新费用报销
    private function updateReimburse($notice, $input){
        $data = '';
        $id = $notice['notice_app'];
        //获取报销单信息
        $expense = ExpenseDb::where('expense_id', $id)
            ->select('expense_status', 'expense_num', 'expense_user')
            ->get()
            ->first();
        if(!$expense){
            return array('rel'=>false,'msg'=>'报销单据不存在');
        }
        switch ($expense['expense_status'])
        {
            case "203":
                $data['expense_status'] = '204';
                $notData['notice_class'] = 'reimburse';//分组
                $notData['notice_type'] = 1;//需要确认操作
                $notData['notice_app'] = $id;//需要确认操作
                $notData['notice_message'] = '报销单据：编号'.$expense['expense_num'].'。出纳已付款，请确认收款。';
                $notData['notice_user'] = $expense['expense_user'];
                $notData['post_user'] = session('userInfo.user_id');
            break;
            case "204":
                //发送用户通知
                $data['expense_status'] = '201';
                $notData['notice_class'] = 'reimburse';//分组
                $notData['notice_type'] = 0;//需要确认操作
                $notData['notice_app'] = $id;//需要确认操作
                $notData['notice_message'] = '报销单据：编号'.$expense['expense_num'].'。确认收款。';
                $notData['notice_user'] = session('userInfo.sysConfig.reimburse.userCashier');
                $notData['post_user'] = session('userInfo.user_id');
                break;
        }
        if(!$data){
            return array('rel'=>false,'msg'=>'报销单据状态错误');
        }

        if($input['notice_value'] == '0'){
            $data['expense_status'] = '205';
            //更新状态
            ExpenseDb::where('expense_id', $id)
                ->update($data);

            //发送用户通知
            $notData['notice_class'] = 'reimburse';//分组
            $notData['notice_type'] = 0;//需要确认操作
            $notData['notice_app'] = $id;//需要确认操作
            $notData['notice_message'] = '报销单据：编号'.$expense['expense_num'].'。';
            $notData['notice_remark'] = $input['notice_msg'];
            $notData['notice_user'] = $expense['expense_user'];
            $notData['post_user'] = session('userInfo.sysConfig.reimburse.userCashier');
            $this->createNotice($notData);
        }else{
            //更新状态
            ExpenseDb::where('expense_id', $id)
                ->update($data);
            $this->createNotice($notData);
        }

        return array('rel'=>true,'msg'=>'');
    }
}
