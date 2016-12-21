<?php
/**
 * 后台模块 抽象类
 * Created by PhpStorm.
 * User: kangxin
 * Date: 2016-12-17
 * Time: 14:56
 */
namespace Common\Controller;
use Think\Controller;
class AbstractController extends Controller{

    protected $login_status,$user_id,$user_name,$user_cname;

    protected $need_login = array();

    public function __construct()
    {
        parent::__construct();

        if(in_array(ACTION_NAME,$this->need_login)){
            $this->checkLogin();
        }
    }

    /**
     * 验证登录
     */
    public function checkLogin(){
        $user = session('user');
        if(empty($user)){
            $this->redirect('Admin/Index/login',array(),3);
        }else{
            $this->login_status = true;
            $this->user_id = $user['id'];
            $this->user_name = $user['name'];
            $this->user_cname = $user['cname'];
        }
    }

    /**
     * 断点格式化输出
     * @param array $data
     */
    public function formatPrint($data = array()){
        echo "<pre>";
        print_r($data);
        echo "</pre>";die;
    }


    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @param array $message 传递给错误信息的变量数组
     * @return void
     */
    protected function ajaxReturn($data,$message=array(),$type='',$json_option=0) {
        if(empty($type)) $type  =   C('DEFAULT_AJAX_RETURN');
        if($data['statusCode']){
            $code = C('CODE');
            $data['message'] = isset($code[$data['statusCode']])? $code[$data['statusCode']]:'提示信息未设置';
            if(is_array($message)&&!empty($message)){
                foreach($message as $k => $v){
                    $k = '{'.$k.'}';
                    $data['message'] = str_replace($k,$v,$data['message']);
                }
            }
        }
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data,$json_option));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler.'('.json_encode($data,$json_option).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            default     :
                // 用于扩展其他返回格式数据
                Hook::listen('ajax_return',$data);
        }
    }

}