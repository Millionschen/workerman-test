<?php
/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/17
 * Time: 上午9:54
 */

namespace Workerman\route;


use Workerman\Model\UserModel;

class RouterImpl implements Router
{

    //0x00001001
    public function getUserByName(&$in)
    {
        $body = $in['body'];
        $userModel = new UserModel();
        return $userModel->findUserByName($body['name']);
    }

}