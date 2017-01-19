<?php
/**
 * Created by PhpStorm.
 * User: kangxin
 * Date: 2017-01-12
 * Time: 14:40
 */
namespace Log\File;
use Think\Log\Driver;
/**
 * 日志文件存储类
 * Class SaveLogFile
 * @package Log\File
 */
class SaveLogFile
{

    // 日志级别 从上到下，由低到高
    const EMERG     = 'EMERG';  // 严重错误: 导致系统崩溃无法使用
    const ALERT     = 'ALERT';  // 警戒性错误: 必须被立即修改的错误
    const CRIT      = 'CRIT';  // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
    const ERR       = 'ERR';  // 一般错误: 一般性错误
    const WARN      = 'WARN';  // 警告性错误: 需要发出警告的错误
    const NOTICE    = 'NOTIC';  // 通知: 程序可以运行但是还不够完美的错误
    const INFO      = 'INFO';  // 信息: 程序输出信息
    const DEBUG     = 'DEBUG';  // 调试: 调试信息
    const SQL       = 'SQL';  // SQL：SQL语句 注意只在调试模式开启时有效

    // 日志信息
    static protected $log       =  array();

    // 日志存储
    static protected $storage   =   null;

    // 日志初始化
    static public function init($config=array()){
        $type   =   isset($config['type']) ? $config['type'] : 'File';
        $class  =   strpos($type,'\\')? $type: 'Think\\Log\\Driver\\'. ucwords(strtolower($type));
        unset($config['type']);
        $config['log_path'] = '/Log';
        self::$storage = new $class($config);
    }

    /**
     * 日志直接写入
     * @static
     * @access public
     * @param string $message 日志信息
     * @param string $level  日志级别
     * @param string $type 日志记录方式
     * @param string $destination  写入目标
     * @param bool $is_new 是否启用自定义日志目录
     * @return void
     */
    static function write($message,$level=self::ERR,$type='',$destination='',$is_new=false) {
        if(!self::$storage){
            $type 	= 	$type ? : C('LOG_TYPE');
            $class  =   'Think\\Log\\Driver\\'. ucwords($type);
            if($is_new){
                $config['log_path'] = './Log/'.MODULE_NAME.'/';
            }else{
                $config['log_path'] = C('LOG_PATH');
            }
            self::$storage = new $class($config);
        }
        if(empty($destination) && $is_new){
            $destination = './Log/'.MODULE_NAME.'/'.date('y_m_d').'.log';
        }else{
            $destination = C('LOG_PATH').date('y_m_d').'.log';
        }
        self::$storage->write("{$level}: {$message}", $destination);
    }
}