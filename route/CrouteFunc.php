<?php
/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/15
 * Time: 上午9:25
 */

namespace Workerman\route;

use Workerman\config\Errors;
use Workerman\Exception\UserException;
use Workerman\Model\UserModel;
use Workerman\Util\Helper;
use Workerman\Util\MyLog;
use Workerman\Util\Verify;

class CrouteFunc
{

    public static function deal(&$in, &$out)
    {
        try {
            //设置out的cmd
            $out['cmd'] = Helper::getIncomeParam($in, 'cmd');
            $out['user_id'] = Helper::getIncomeParam($in, 'user_id');
            $out['body'] = '';

            //获取cmd映射的函数
            $func = self::getFunc($in['cmd']);

            Verify::validProtocol($in);

            $body = call_user_func(array(__NAMESPACE__ . "\\CrouteFunc", $func), $in);
            //未出错 return_code 为 SUCCESS 设置body
            $out['return_code'] = Errors::SUCCESS;
            $out['body'] = $body;
        } catch (UserException $ue) {
            MyLog::warn("Exception: Line %s Message ", $ue->getLine(), $ue->getMessage());
            //出错了,获取异常的code
            $out['return_code'] = $ue->getCode();
        }
    }

    private static function getFunc($cmd)
    {
        $func = isset(CmdMap::$bind[$cmd]) ? CmdMap::$bind[$cmd] : '';
        if ($func != '') {
            return $func;
        }
        throw new UserException(Errors::INVALID_CMD);
    }

    //cmd 0x00001001
    private static function getUserByName(&$in)
    {
        $body = $in['body'];

        $userModel = new UserModel();
        return $userModel->findUserByName($body['name']);
    }




}