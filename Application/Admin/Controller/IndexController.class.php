<?php
namespace Admin\Controller;
class IndexController extends \Common\Controller\AbstractController {

    /**
     * @var \Common\Model\MemberModel
     */
    public $member;
    public function __construct()
    {
        parent::__construct();
        $this->member = D('Member');
    }

    public function index(){

    }

    public function register(){
        $info = array(
            'name'      => I('post.name'),
            'cname'     => I('post.cname'),
            'mobile'    => I('post.mobile'),
            'pwd1'      => I('post.pwd1'),
            'pwd2'      => I('post.pwd1'),
        );
        $info['type'] = 1;
        $statusCode = $this->member->register($info);
        $this->ajaxReturn(compact('statusCode'));
    }

}