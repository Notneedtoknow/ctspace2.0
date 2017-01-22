<?php
/**
 * Created by PhpStorm.
 * User: kangxin
 * Date: 2017-01-11
 * Time: 11:29
 */
namespace Api\WeChat;
/**
 * 微信接口抽象类
 * Class WeChat
 * @package Api\WeChat
 */
abstract class WeChat
{

    protected static $AppID, $AppSecret, $Token, $AccessToken;

    protected static $AccessTokenUrl,$GetIpListUrl;

    protected static $MsgTypeEnum = array('text','image','voice','video','location','link','event');

    protected static $postStr,$ToUserName,$FromUserName,$CreateTime,$MsgType,$Content,$MsgId;

    protected static $respondStr;

    public function __construct()
    {
        self::setBasicConfig();
        self::setUrl();
    }

    /**
     * 配置基础设置
     */
    private static function setBasicConfig()
    {
        $basic_config = C('WE_CHAT_CONFIG');
        self::$AppID = isset($basic_config['AppID']) ? $basic_config['AppID'] : '';
        self::$AppSecret = isset($basic_config['AppSecret']) ? $basic_config['AppSecret'] : '';
        self::$Token = isset($basic_config['Token']) ? $basic_config['Token'] : '';
    }

    /**
     * 配置url设置
     */
    private static function setUrl()
    {
        $url_config = C('WE_CHAT_URL');
        self::$AccessTokenUrl = isset($url_config['AccessTokenUrl']) ? $url_config['AccessTokenUrl'] : '';
        self::$GetIpListUrl = isset($url_config['GetIpListUrl']) ? $url_config['GetIpListUrl'] : '';
    }

    /**
     * 保存用户记录
     * @return mixed
     */
    protected static function saveUserMessage()
    {
        $we_chat_text_record = M('WeChatTextRecord');
        $info = array(
            'msg_id'            =>  self::getMsgId(),
            'to_user_name'      =>  self::getToUserName(),
            'open_id'           =>  self::getFromUserName(),
            'content'           =>  self::getContent(),
            'respond'           =>  self::getRespondStr(),
            'create_time'       =>  self::getCreateTime(),
        );
        $result = $we_chat_text_record->add($info);
        return $result;
    }

    /**
     * 获得小鑫回复
     * @return mixed
     */
    protected static function getRespondStr()
    {
        $respond = array(
            '是的，我是这个世界上最帅的人！',
            '哈哈，我完全不懂你在说神马！',
            'Can you speak English?',
            'What are you fucking talking about?',
        );
        return self::$respondStr = $respond[array_rand($respond,1)];
    }

    /**
     * 获得开发者微信号
     * @return mixed
     */
    protected static function getToUserName()
    {
        return self::$postStr->ToUserName;
    }
    /**
     * 获得发送方帐号（一个OpenID）
     * @return mixed
     */
    protected static function getFromUserName()
    {
        return self::$postStr->FromUserName;
    }
    /**
     * 获得消息创建时间 （整型）
     * @return mixed
     */
    protected static function getCreateTime()
    {
        return self::$postStr->CreateTime;
    }
    /**
     * 获得消息类型
     * @return mixed
     */
    protected static function getMsgType()
    {
        return self::$postStr->MsgType;
    }
    /**
     * 获得文本消息内容
     * @return mixed
     */
    protected static function getContent()
    {
        return self::$postStr->Content;
    }
    /**
     * 获得消息id，64位整型
     * @return mixed
     */
    protected static function getMsgId()
    {
        return self::$postStr->MsgId;
    }

    /**
     * 向微信服务器ECHO内容
     * @param $content
     */
    private static function response($content)
    {
        echo $content;
    }

    /**
     * 回复文本信息
     */
    public function responseTextMessage()
    {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";
        $resultStr = sprintf($textTpl, self::$ToUserName, self::$FromUserName, time(), 'text', self::$respondStr);
        if (!headers_sent())
            header('Content-Type: application/xml; charset=utf-8');
        self::response($resultStr);
    }
}