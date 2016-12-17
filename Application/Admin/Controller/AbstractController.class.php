<?php
/**
 * 后台模块 抽象类
 * Created by PhpStorm.
 * User: kangxin
 * Date: 2016-12-17
 * Time: 14:56
 */
namespace Admin\Controller;
use Think\Controller;
class AbstractController extends Controller{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 断点格式化输出
     * @param array $data
     */
    public function format_print($data = array()){
        echo "<pre>";
        print_r($data);
        echo "</pre>";die;
    }

}