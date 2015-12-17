<?php
/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/17
 * Time: 上午9:53
 */

namespace Workerman\route;

interface Router
{
    //cmd 0x00001001
    public function getUserByName(&$in);

}