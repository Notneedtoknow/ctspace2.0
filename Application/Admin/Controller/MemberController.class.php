<?php
/**
 * Created by PhpStorm.
 * User: kangxin
 * Date: 2016-12-21
 * Time: 14:15
 */
namespace Admin\Controller;
class MemberController extends \Common\Controller\AbstractController{


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