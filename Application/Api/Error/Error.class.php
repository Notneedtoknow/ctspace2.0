<?php
/**
 * Created by PhpStorm.
 * User: kangxin
 * Date: 2017-01-11
 * Time: 10:16
 */
namespace Api\Error;
class Error {

    /**
     * 抛出接口异常错误
     * @param $code
     * @param null $value
     * @throws \Exception
     */
    public static function throwException($code, $value = null)
    {
        $message = $value;
        if ($code) {
            $codeConfig = self::getCodeConfig();
            if (empty($codeConfig[$code])) {
                throw new \Exception('错误码' . $code . '的相应提示信息没有设置');
            }
            $message = $codeConfig[$code];
            if (is_array($value)) {
                $replace = array_keys($value);
                foreach ($replace as &$v) {
                    $v = '{' . $v . '}';
                }
                $message = str_replace($replace, $value, $message);
            }
        }
        throw new \Exception($message, $code);
    }

    /**
     * 获得接口模块错误码
     * @return mixed
     */
    public static function getCodeConfig()
    {
        $codeConfig = C('ERROR');
        return $codeConfig;
    }


}