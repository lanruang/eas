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
    $status = base64_encode($status);
    $msg = base64_encode($msg);
    $url = base64_encode($url);

    header('Location: '.route('sysMessage')."/".$status."/".$msg."/".$url);
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
function sort_tree($array, $pid = 0, $level = 0)
{
    $arr = array();

    foreach($array as $v){
        if($v['pid']==$pid){
            $v['level'] = $level;
            $arr[] = $v;
            $v['level'] = $level + 1;
            $arr = array_merge($arr,sort_tree($array,$v['id'], $v['level']));
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