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
        parent::__construct();
    }

    public function test(){
        $we_chat = new \Api\WeChat\Response();
        $result = $we_chat->getMessage();
        $this->formatPrint($result);
    }

}