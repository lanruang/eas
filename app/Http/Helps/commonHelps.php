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
        die(1);
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
 * @return	json
 *
 */
function echoAjaxJson($status = '0', $msg = '')
{
    //1-正常，0-提示，-1-错误
    $result['status'] = $status;
    $result['msg'] = $msg;
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
function redirectPageMsg($status = '1', $msg = '', $url = '')
{
    //1-正常，0-提示，-1-错误
    $result['status'] = $status;
    $result['msg'] = $msg;
    $result['url'] = $url;

    return view('layouts.pageMsg', $result);
    exit();
}

/**
 * 验证参数
 *
 * @param	str		$str
 * @param   str     $param
 * @param   str     $type
 * @return	bool
 *
 */
function validateParam ($str = '', $param = '', $type = '')
{
    switch ($param){
        case "nullInt"://整数并且可以为空
            $res = ctype_digit($str);
        break;
    }

    return $res;
}

/**
 * 树形排序
 * @param	array		$array
 * @param	int			$pid
 * @return	array
 */
function sortTree($array, $pid = 0, $level = 0)
{
    $arr = array();

    foreach($array as $v){
        if($v['pid']==$pid){
            $v['level'] = $level;
            $arr[] = $v;
            $v['level'] = $level + 1;
            $arr = array_merge($arr,sortTree($array,$v['id'], $v['level']));
        }
    }
    return $arr;
}

/**
 * 转成树形结构
 *
 * @param	array		$data
 * @param	int			$pid
 * @return	array
 */
function getTree($data, $pid = 0)
{
    $tree = '';
    foreach($data as $k => $v)
    {
        if($v['pid'] == $pid)
        {
            $v['children'] = getTree($data, $v['id']);
            $tree[] = $v;
        }
    }
    return $tree;
}

/**
 * 转成树形结构(特殊)
 *
 * @param	array		$data
 * @param	int			$pid
 * @return	array
 */
function getTreeT($data, $pid = 0)
{
    $tree = '';
    foreach($data as $k => $v)
    {
        if($v['pid'] == $pid)
        {
            $type = 'item';
            $rel = getTreeT($data, $v['id']);
            if($rel){
                $type = 'folder';
                $v['additionalParameters']['children'] = $rel;
            }
            $v['type'] = $type;
            $tree[] = $v;
        }
    }
    return $tree;
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