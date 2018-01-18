    <?php
    /**
     * Created by PhpStorm.
     * User: thinkpad
     * Date: 2016/8/22
     * Time: 17:28
     */

    /**
     * 打印参数
     *
     * @param	arr|str		$arr
     *
     */
    if (! function_exists('p')) {
        //打印数组
        function p($arr)
        {
            echo '<pre>' . print_r($arr,true) . '</pre>';
            die();
        }
    };

    /**
     * 返回json结果
     *
     * @param	arr		$arr
     * @return	json
     *
     */
        function ajaxJsonRes($arr)
        {
            echo json_encode($arr);
            exit();
        }

    /**
     * 返回json提示信息
     *
     * @param	int		$status
     * @param   str     $msg
     * @param   array   $data
     * @return	json
     *
     */
    function echoAjaxJson($status = '0', $msg = '', $data= '')
    {
        //1-正常，0-提示，-1-错误
        $result['status'] = $status;
        $result['msg'] = $msg;
        $result['data'] = $data;
        echo json_encode($result);
        exit();
    }

    /**
     * 跳转页面提示信息
     *
     * @param	int		$status
     * @param   str     $msg
     * @param   str     $url
     * @return	json
     *
     */
    function redirectPageMsg($status = '1', $msg = '', $url = '', $btnMsg = '返回')
    {
        //1-正常，0-提示，-1-错误
        $result['status'] = $status;
        $result['msg'] = $msg;
        $result['url'] = $url;
        $result['btnMsg'] = $btnMsg;

        return view('layouts.pageMsg', $result);
        exit();
    }

    /**
     * 树形排序
     * @param	array		$array
     * @param	string		$pid
     * @return	array
     */
    function sortTree($array, $pid = '0', $level = 0)
    {
        $arr = array();
        foreach ($array as $v) {
            if ($v['pid'] == $pid) {
                $v['level'] = $level;
                $arr[] = $v;
                $v['level'] = $level + 1;
                $arr = array_merge($arr, sortTree($array, $v['id'], $v['level']));
            }
        }

        return $arr;
    }

    /**
     * 转成树形结构
     *
     * @param	array		$data
     * @param	string		$pid
     * @param	bool		$listPid
     * @return	array
     */
    function getTree($data, $pid = '0', $listPid = '0')
    {
        $typeId = $listPid == '1' ? 'id' : 'pid';
        $tree = array();
        foreach($data as $k => $v)
        {
            if($v[$typeId] == $pid)
            {
                $v['children'] = getTree($data, $v['id']);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    /**
     * 2个日期之间差数
     *
     * @param	date		$startDate
     * @param	date		$endDate
     * @param	srting	    $type
     * @return	array
     */
    function getDateToDiff($startDate, $endDate, $type){
        switch ($type)
        {
            case 'day':
                $startDate = strtotime($startDate);
                $endDate = strtotime($endDate);
                $res = abs($endDate - $startDate)/86400;
            break;
            case 'month':
                $startDate = strtotime($startDate);
                $endDate = strtotime($endDate);
                list($date_1['y'],$date_1['m'])=explode("-",date('Y-m',$startDate));
                list($date_2['y'],$date_2['m'])=explode("-",date('Y-m',$endDate));
                $res = abs(($date_2['y']-$date_1['y'])*12 +$date_2['m']-$date_1['m']);
            break;
            case 'year':
                $res = abs($endDate - $startDate);
                break;
        }

        return $res;
    }

    /**
     * 指定日期下一天、月、年
     *
     * @param	date		$date
     * @param	srting 		$type
     * @return	array
     */
    function getNextDate($date, $type){
        switch ($type)
        {
            case 'day':
                $res = date("Y-m-d",strtotime("$date +1 day"));
                break;
            case 'month':
                $res = date("Y-m",strtotime("$date +1 month"));
                break;
            case 'year':
                $res = $date + 1;
                break;
        }

        return $res;
    }


    /**
     * 随机数
     *
     * @param	int			$length
     * @return	array
     */
    function randomKeys($length)
    {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        for($i=0;$i<$length;$i++)
        {
            $key .= $pattern{mt_rand(0,35)};
        }
        return $key;
    }

    /**
     * 获取随机ID
     *
     * @return	string
     */
    function getId(){
        $id = strtoupper(md5(uniqid(mt_rand(), true)));
        return $id;
    }

    /**
     * map查找
     *
     * @param    array		arr
     * @param    string		key
     * @param    string     isSub
     * @return    string
     *
     */
    function mapKey($arr = array(), $key = '', $isSub = '0')
	{
        $str = '';
        if(!array_key_exists($key, $arr)){
            return '';
        }
        if($isSub){
            $str = $arr[$key]['sub_name'].' - ';
        }else{
            $str = $arr[$key];
        }

        return $str;
    }

    /**
     * 保留x小数点0补齐
     *
     * @param    float        num
     * @param    int            x
     * @return   string
     *
     */
    function toDecimal($num = 0, $x = 2){
        $num = sprintf("%.".$x."f",$num);
        return $num;
    }

    /**
     * 发票长度补齐8位
     *
     * @param	string			$num
     * @return	array
     */
    function formatInvoice($num){
        $x = '';
        $a = 8;
        $strlen = strlen($num);
        $strlen = 8 - $strlen;
        for($i = 0; $i < $strlen; $i++){
            $x .= 0;
        }
        $x .= $num;
        return $x;
    }

    /**
     * 邮件发送
     *
     * @param	$string			$template
     * @param   $array          $data
     * @return	array
     */
    function postMail($template, $data, $subject)
    {
        Mail::send($template, $data, function($m) use($data)
        {
            $m->to($data['mail'])->subject('e收贷-现金贷邮箱验证');
        });
    }

    /**
     * 获取邮箱地址
     *
     * @param	string			$str
     * @return	array
     */
    function exMailUrl($str){
        $url = '';
        $exMail = explode('@', $str);
        $exMail = strtolower($exMail[1]);
        switch ($exMail) {
            case 'qq.com':
                $url = 'http://mail.qq.com';
                break;
            case '126.com':
                $url = 'http://mail.126.com';
                break;
            case '163.com':
                $url = 'http://mail.163.com';
                break;
            case 'sina.com':
                $url = 'http://mail.sina.com.cn';
                break;
            case 'sina.cn':
                $url = 'http://mail.sina.com.cn';
                break;
            default :
                $url = route('member.login');
        }

        return $url;
    }

    function imgBase64($img_file){
        $img_base64 = '';
        $app_img_file = $img_file;                 //组合出真实的绝对路径
        $img_info = getimagesize($app_img_file);            //取得图片的大小，类型等
        $fp = fopen($app_img_file,"r");                     //图片是否可读权限
        if($fp){
            $file_content = chunk_split(base64_encode(fread($fp,filesize($app_img_file))));//base64编码
            switch($img_info[2]){           //判读图片类型
                case 1:$img_type="gif";break;
                case 2:$img_type="jpg";break;
                case 3:$img_type="png";break;
            }
            $img_base64 = 'data:image/'.$img_type.';base64,'.$file_content;//合成图片的base64编码
            fclose($fp);
        }
        return $img_base64;         //返回图片的base64
    }

    //