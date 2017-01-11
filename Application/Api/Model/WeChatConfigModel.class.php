<?php
/**
 * Created by PhpStorm.
 * User: kangxin
 * Date: 2017-01-11
 * Time: 09:37
 */
namespace Api\Model;
use Think\Model;
/**
 * 微信配置表 数据类
 * Class WeChatConfigModel
 * @package Api\Model
 */
class WeChatConfigModel extends Model
{
    /**
     * 插入access_token于表中
     * @param $access_token
     * @param $expires_in
     * @return mixed
     */
    public function insert($access_token, $expires_in)
    {
        $data = array(
            'access_token' => $access_token,
            'expires_in' => time() + intval($expires_in) - 2756,
        );
        $return = $this->add($data);
        return $return;
    }
}