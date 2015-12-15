<?php
/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/15
 * Time: 上午12:49
 */

namespace Workerman\Util;


class Response
{
    public static function makeResponse($cmd, $returnCode, &$body = '')
    {
        return array(
            'cmd' => $cmd,
            'return_code' => $returnCode,
            'body' => $body
        );
    }

}