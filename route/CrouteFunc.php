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
use Workerman\Util\Helper;
use Workerman\Util\MyLog;
use Workerman\Util\Verify;

class CrouteFunc
{

    /**
     * @var Router $router
     */
    private static $router;

    private static function getRouter()
    {
        if (!self::$router instanceof Router) {
            self::$router = new RouterImpl();
        }
        return self::$router;
    }

    public static function deal(&$in, &$out)
    {
        try {
            //设置out的cmd
            $out['cmd'] = Helper::getIncomeParam($in, 'cmd');
            $out['user_id'] = Helper::getIncomeParam($in, 'user_id');
            $out['body'] = '';
            $out['return_code'] = Errors::SUCCESS;

            //获取cmd映射的函数
            $func = self::getFunc($in['cmd']);

            //验证协议是否合法
            Verify::validProtocol($in);

            //调用router 处理协议
            $body = call_user_func(array(self::getRouter() , $func), $in);

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

}