<?php
/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/15
 * Time: 上午11:03
 */

namespace Workerman\Util;


use Workerman\config\Errors;
use Workerman\Exception\UserException;

class Helper
{
    public static function getIncomeParam(&$in, $key, $optional = false, $default = '')
    {
        if (isset($in[$key]))
            return $in[$key];
        if ($optional) {
            return $default;
        }
        throw new UserException(Errors::INVALID_PROTOCOL);
    }
}