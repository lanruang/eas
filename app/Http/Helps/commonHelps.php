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
 * @return	json
 *
 */
function redirectPageMsg($status = '0', $msg = '')
{
    $result['status'] = $status;
    $result['msg'] = $msg;
    echo json_encode($result);
    exit();
}

/**
 * 验证参数
 *
 * @param	str		$str
 * @param   str     $param
 * @param   str     $type
 * @return	str
 *
 */
function validateParam ($str, $param, $type)
{
    switch ($param){
        case "nullInt"://整数并且可以为空
            ctype_digit($str);
        break;
    }


    exit();
}