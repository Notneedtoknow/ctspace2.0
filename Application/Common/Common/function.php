<?php
/**
 * Created by PhpStorm.
 * User: kangxin
 * Date: 2016-12-17
 * Time: 16:33
 */

/**
 * 产生随机字符串
 * @param int $length 输出长度 默认为6
 * @param string $chars 字符串范围 默认为 "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz"
 * @return string 字符串
 */
function random($length = 6, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz')
{
    $hash = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}