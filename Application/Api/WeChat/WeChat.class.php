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

    protected static $postStr,$postObj,$ToUserName,$FromUserName,$CreateTime,$MsgType,$Content,$MsgId;

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
        self::$Token = isset($basic_config['Token']) ? $basic_config['Token'] : 'togatherandforever';
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
            'msg_id'            =>  trim(self::getMsgId()),
            'to_user_name'      =>  trim(self::getToUserName()),
            'open_id'           =>  trim(self::getFromUserName()),
            'content'           =>  trim(self::getContent()),
            'respond'           =>  self::getRespondStr(),
            'create_time'       =>  trim(self::getCreateTime()),
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
        return self::$ToUserName = self::$postObj->ToUserName;
    }
    /**
     * 获得发送方帐号（一个OpenID）
     * @return mixed
     */
    protected static function getFromUserName()
    {
        return self::$FromUserName = self::$postObj->FromUserName;
    }
    /**
     * 获得消息创建时间 （整型）
     * @return mixed
     */
    protected static function getCreateTime()
    {
        return self::$CreateTime = self::$postObj->CreateTime;
    }
    /**
     * 获得消息类型
     * @return mixed
     */
    protected static function getMsgType()
    {
        return self::$MsgType = self::$postObj->MsgType;
    }
    /**
     * 获得文本消息内容
     * @return mixed
     */
    protected static function getContent()
    {
        return self::$Content = self::$postObj->Content;
    }
    /**
     * 获得消息id，64位整型
     * @return mixed
     */
    protected static function getMsgId()
    {
        return self::$MsgId = self::$postObj->MsgId;
    }

    /**
     * 向微信服务器ECHO内容
     * @param $content
     */
    private static function response($content)
    {
//        \Log\File\SaveLogFile::write("回复消息：".var_export($content,true),'','','',true);
        echo $content;
    }

    /**
     * 回复默认文本消息
     */
    public function responseDefaultMessage()
    {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, self::$FromUserName, self::$ToUserName, time(), 'text', "对不起，我不知道你在说什么。");
        self::response($resultStr);
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
                    <FuncFlag>0</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, self::$FromUserName, self::$ToUserName, time(), 'text', self::$respondStr);
        self::response($resultStr);
    }
}