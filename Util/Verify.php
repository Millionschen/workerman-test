<?php
/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/15
 * Time: 上午12:21
 */

namespace Workerman\Util;


use Workerman\config\Errors;
use Workerman\Exception\UserException;

class Verify
{
    const VERIFY_KEY = 'zuihuibao2015';
    const VALID_TIME_DIFF = 300;

    public static function validProtocol($data)
    {
        $valid = true;
        if (!self::isTimeValid($data['timestamp'])) {
            $valid = false;
        }

        $verify = self::GenHash($data['cmd'], $data['timestamp'], $data['body']);

        if (strcasecmp($data['verify'], $verify) !== 0) {
            $valid = false;
        }

        if (!$valid) {
            throw new UserException(Errors::NOT_VERIFIED);
        }
    }

    private static function isTimeValid($timestamp)
    {
        if (abs(intval($timestamp) - time()) < self::VALID_TIME_DIFF) {
            return true;
        }
        return false;
    }

    private static function GenHash($cmd, $timestamp, $body)
    {
        $body = json_encode($body);
        return self::GetHashVerifyCode(array($cmd, $timestamp, $body), self::VERIFY_KEY);
    }

    private static function GetHashVerifyCode($signatureArr, $key)
    {
        $signatureStr = implode('', $signatureArr);
        return hash_hmac('sha256', $signatureStr, $key);
    }

}

