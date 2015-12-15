<?php
/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/14
 * Time: 下午11:39
 */

namespace  Workerman\config;


class Errors
{
    //正确
    const SUCCESS = 0;

    //系统错误
    const LOG_DIR_IS_NOT_WRITABLE = 1000;
    const INVALID_CMD = 1001;
    const INVALID_PROTOCOL = 1002;

    //用户错误
    const NOT_VERIFIED = 2000;
}