<?php
/**
 * Created by PhpStorm.
 * User: kangxin
 * Date: 2017-01-11
 * Time: 11:26
 */
namespace Api\WeChat;
class Response extends WeChat
{

    private $postStr;

    public function __construct()
    {
        parent::__construct();
//        if(!$this->checkSignature()){
//            //验证签名失败 记录日志
//            \Log\File\SaveLogFile::write('微信验证签名失败!'.json_encode(I('get.')),'','','',true);
//            exit ;
//        }
    }

    /**
     * 验证签名
     * @return bool
     */
    public function checkSignature()
    {
        $signature = I('get.signature');
        $timestamp = I('get.timestamp');
        $nonce = I('get.nonce');
        if (empty($signature) || empty($timestamp) || empty($nonce))
            return false;

        $tmpArr = array(self::$Token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature)
            return true;
        else
            return false;
    }

    public function getMessage()
    {
        $echo_str = I('get.echostr');
        if(!empty($echo_str)){
            \Log\File\SaveLogFile::write($echo_str,'','','',true);
        }
        $this->postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    }

}