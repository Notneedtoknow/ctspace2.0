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

    protected static $MsgType = array('text','image','voice','video','location','link','event');

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
}