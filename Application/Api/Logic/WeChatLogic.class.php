<?php
/**
 * Created by PhpStorm.
 * User: kangxin
 * Date: 2017-01-09
 * Time: 14:39
 */
namespace Api\Logic;
use Think\Model;
/**
 * 微信服务
 * Class WeChatLogic
 * @package Api\Service
 */
class WeChatLogic {

    /**
     * @var \Api\Model\WeChatConfigModel
     */
    public $we_chat_config_model;

    public $access_token;

    public function __construct()
    {
        $this->we_chat_config_model = D('WeChatConfig');
        //构建类时检测access_token是否存在
        if(empty($this->access_token)){
            $this->getAccessToken();
        }
    }

    /**
     * 获得微信公众平台access_token
     * @return mixed
     */
    public function getAccessToken(){
        $data = $this->we_chat_config_model->order('id desc')->find();
        if(empty($data) || !isset($data['expires_in']) || $data['expires_in'] < time()){
            //若未找到有效的access_token 则重新请求
            $config = C('WE_CHAT_CONFIG');
            $url = $config['TokenUrl'].'?grant_type=client_credential&appid='.$config['AppID'].'&secret='.$config['AppSecret'];
            $result = json_decode($this->curl($url));
            if(!isset($result->access_token) || !isset($result->expires_in)){
                \Api\Error\Error::throwException($result->errcode);
            }
            $this->we_chat_config_model->insert($access_token = $result->access_token,$result->expires_in);
        }else{
            $access_token = $data['access_token'];
        }
        return $this->access_token = $access_token;
    }

    /**
     * @param $url
     * @param string $method
     * @param null $postFields
     * @param null $header
     * @return mixed
     * @throws \Exception
     */
    public function curl($url, $method = 'GET', $postFields = null, $header = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if (!empty($postFields)) {
                    if (is_array($postFields) || is_object($postFields)) {
                        if (is_object($postFields))
                            $postFields = json_decode(json_encode($postFields), true);
                        $postBodyString = "";
                        $postMultipart = false;
                        foreach ($postFields as $k => $v) {
                            if ("@" != substr($v, 0, 1)) { //判断是不是文件上传
                                $postBodyString .= "$k=" . urlencode($v) . "&";
                            } else { //文件上传用multipart/form-data，否则用www-form-urlencoded
                                $postMultipart = true;
                            }
                        }
                        unset($k, $v);
                        if ($postMultipart) {
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                        } else {
                            curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
                        }
                    } else {
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                    }
                }
                break;
            default:
                if (!empty($postFields) && is_array($postFields))
                    $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($postFields);
                break;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        if (!empty($header) && is_array($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch), 0);
        }
        curl_close($ch);
        return $response;
    }

}