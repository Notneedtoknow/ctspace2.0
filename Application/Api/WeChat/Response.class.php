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

    public function __construct()
    {
        parent::__construct();
        $this->valid();
    }

    public function valid()
    {
        $echo_str = I('get.echostr');
        if($this->checkSignature()){
            \Log\File\SaveLogFile::write(var_export($echo_str,true)."验证签名成功！",'','','',true);
            echo $echo_str;
//            exit ;
        }else{
            \Log\File\SaveLogFile::write(var_export(parent::$Token,true)."验证签名失败！",'','','',true);
        }
    }

    /**
     * 验证签名
     * @return bool
     */
    private function checkSignature()
    {
        $signature = I('get.signature');
        $timestamp = I('get.timestamp');
        $nonce = I('get.nonce');
        if (empty($signature) || empty($timestamp) || empty($nonce))
            return false;

        $tmpArr = array(parent::$Token, $timestamp, $nonce);
        sort($tmpArr , SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature)
            return true;
        else
            return false;
    }

    /**
     * 获取用户发送信息
     */
    public function getMessage()
    {
        parent::$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if(empty(parent::$postStr)){
            echo "";
            \Log\File\SaveLogFile::write("输出到微信终端数据！(无post)！",'','','',true);
            exit ;
        }
        libxml_disable_entity_loader(true);
        parent::$postObj = simplexml_load_string(parent::$postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        parent::saveUserMessage();
        if(!empty($result)){
            parent::responseTextMessage();
        }else{
            parent::responseDefaultMessage();
            \Log\File\SaveLogFile::write("保存用户发送信息失败！".var_export(parent::$postStr,true),'','','',true);
        }

    }


}