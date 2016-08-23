<?php
/**
 * Created by PhpStorm.
 * User: thinkpad
 * Date: 2016/8/22
 * Time: 17:28
 */

if (! function_exists('p')) {
    //打印数组
    function p($arr)
    {
        echo '<pre>' . print_r($arr,true) . '</pre>';
        die(1);
    }
}