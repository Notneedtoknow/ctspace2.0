<?php
namespace Api\Controller;
use Common\Controller;
/**
 * 接口控制器
 * Class ApiController
 * @package Api\Controller
 */
class WeChatController extends Controller\AbstractController {

    public function __construct()
    {
        $this->need_login = array('index');
        parent::__construct();
    }

    public function test(){
        $we_chat_response = new \Api\WeChat\Response();
        $we_chat_response->getMessage();
//        $this->formatPrint($result);
    }

}