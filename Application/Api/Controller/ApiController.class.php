<?php
namespace Api\Controller;
use Common\Controller;
use Api\Logic\WeChatLogic;
/**
 * 接口控制器
 * Class ApiController
 * @package Api\Controller
 */
class ApiController extends Controller\AbstractController {

    public function __construct()
    {
        parent::__construct();
    }

    public function test(){
        $we_chat = new WeChatLogic();
        $result = $we_chat->getAccessToken();
        $this->formatPrint($result);
    }

}