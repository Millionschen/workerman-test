<?php
/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/14
 * Time: 下午8:17
 */

namespace Workerman\Util;

use Workerman\config\Errors;
use Workerman\Exception\UserException;

class MyLog
{
    const LOG_PATH = '/app/web/log/';

    static $workerId = "main";

    private static $logSize = 5242880; // 1024 * 1024 * 5 = 5M

    public static function trace()
    {
        $args = func_get_args();
        $format = $args[0];
        array_shift($args);
        $desc = vsprintf($format, $args);
        if (defined("dev")) {
            self::write($desc, 'TRACE');
        }
    }

    public static function debug()
    {
        $args = func_get_args();
        $format = $args[0];
        array_shift($args);
        $desc = vsprintf($format, $args);
        self::write($desc, 'DEBUG');
    }

    public static function warn()
    {
        $args = func_get_args();
        $format = $args[0];
        array_shift($args);
        $desc = vsprintf($format, $args);
        self::write($desc, 'WARN');
    }

    public static function notice()
    {
        $args = func_get_args();
        $format = $args[0];
        array_shift($args);
        $desc = vsprintf($format, $args);
        self::write($desc, 'NOTICE');
    }

    public static function error()
    {
        $args = func_get_args();
        $format = $args[0];
        array_shift($args);
        $desc = vsprintf($format, $args);
        self::write($desc, 'ERROR');
    }

    private static function write($desc, $level)
    {
        $nowTime = date('[y-m-d H:i:s]');
        $today = date('Y_m_d');
        // 检查日志目录是否可写
        if (!is_writable(self::LOG_PATH)) {
            throw new UserException(Errors::LOG_DIR_IS_NOT_WRITABLE);
        }

//        $target = self::LOG_PATH . $level . "_" . self::$workerId . '_' . $today;
        $target = self::LOG_PATH . $level . '_' . $today;

        if (file_exists($target) && self::$logSize <= filesize($target)) {
            $fileName = basename($target) . '_' . time() . '_log';
            rename($target, dirname($target) . "/" . $fileName);
        }
        clearstatcache();
        return error_log("$nowTime $desc\n", 3, $target);
    }
}
