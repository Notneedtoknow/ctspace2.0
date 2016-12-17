<?php
/**
 * 用户模型 全局唯一入口
 * Created by PhpStorm.
 * User: kangxin
 * Date: 2016-12-17
 * Time: 15:40
 */
namespace Common\Model;
use Think\Model;
/**
 * 用户类
 * Class MemberModel
 * @package Common\Model
 */
class MemberModel extends Model{

    public $type;

    public function __construct()
    {
        parent::__construct();

        $this->type = \Common\Model\RoleModel::$type;
    }

    /**
     * 获得用户信息
     * @param int $id
     * @param string $name
     * @param string $mobile
     * @return array|mixed
     */
    public function getMember($id = 0,$name = '',$mobile = ''){
        if(!empty($id)){
            return $result = $this->find($id);
        }
        if(!empty($name)){
            return $result = $this->where(array('name'=>$name))->find();
        }
        if(!empty($mobile)){
            return $result = $this->where(array('mobile'=>$mobile))->find();
        }
        return $result = array();
    }

    /**
     * 用户注册
     * @param $info
     * @return array|int|string
     */
    public function register($info){
        $time = time();

        $base = $this->checkInfo($info);
        if(!is_array($base)){
            return $base;
        }
        $flag = $this->checkPassword($info['pwd1'],$info['pwd2']);
        if($flag != 10000){
            return $flag;
        }
        $data = $base;
        $data['encrypt']        = random();
        $data['password']       = $this->buildPwd($info['pwd1'],$data['encrypt']);
        $data['create_time']    = $time;
        if(!empty(session('member'))){
            $invite = session('member');
            $data['invite_id']      = $invite['id'];
            $data['invite_name']    = $invite['name'];
            $data['invite_cname']   = $invite['cname'];
            $data['invite_time']    = $time;
        }

        if(!$data=$this->create($data)){
            return $this->getError() ;
        }
        $this->add($data);
        return 10000;
    }

    /**
     * 验证基础数据
     * @param $info
     * @return array|int
     */
    public function checkInfo($info){
        //判断用户类型是否存在
        if(!isset($this->type[$info['type']])){
            return 10016;
        }
        //判断用户名是否为空
        if(empty($info['name'])){
            return 10010;
        }
        //判断手机号是否为空
        if(empty($info['mobile'])){
            return 10011;
        }
        //判断用户名是否存在
        $arr = $this->getMember(0,$info['name']);
        //判断手机号是否存在
        $arr2 = $this->getMember(0,'',$info['mobile']);
        if(empty($info['id'])){
            if(!empty($arr)){
                return 10014;
            }
            if(!empty($arr2)){
                return 10015;
            }
        }else{
            if(!empty($arr) && $info['id'] != $arr['id']){
                return 10014;
            }
            if(!empty($arr2) && $info['id'] != $arr2['id']){
                return 10015;
            }
        }

        $data = array(
            'type' => $info['type'],
            'name' => $info['name'],
            'cname' => $info['cname'],
            'mobile' => $info['mobile'],
        );

        return $data;

    }

    /**
     * 验证密码
     * @param $pwd1
     * @param $pwd2
     * @param bool $check_old
     * @param string $encrypt
     * @param string $old_pwd
     * @return int
     */
    public function checkPassword($pwd1,$pwd2,$check_old = false,$encrypt = '',$old_pwd = ''){
        //判断密码是否为空
        if(empty($pwd1) || empty($pwd2)){
            return 10012;
        }
        //判断密码是否一致
        if($pwd1 != $pwd2){
            return 10013;
        }
        if(!empty($check_old)){
            if($old_pwd != $this->buildPwd($pwd1,$encrypt)){
                return 10017;
            }
        }
        return 10000;
    }

    /**
     * 生成密码
     * @param $pwd
     * @param $encrypt
     * @return string
     */
    public function buildPwd($pwd,$encrypt){
        return md5(md5($pwd).$encrypt);
    }











}