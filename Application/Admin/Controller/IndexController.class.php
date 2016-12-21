<?php
namespace Admin\Controller;
class IndexController extends \Common\Controller\AbstractController {

    /**
     * @var \Common\Model\MemberModel
     */
    public $member;
    public function __construct()
    {
        $this->need_login = array('index');
        parent::__construct();
        $this->member = D('Member');
    }

    public function index(){
        echo "hello,world";
    }

    public function login(){
        $this->display();
    }

    public function loginIn(){
        $info['name'] = I('post.name','','htmlspecialchars');
        $info['pwd']  = I('post.pwd','','htmlspecialchars');
        $statusCode = $this->member->loginSubmit($info);
        $this->ajaxReturn(compact('statusCode'));
    }

    public function loginOut(){

    }

}