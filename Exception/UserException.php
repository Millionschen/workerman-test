<?php

namespace  Workerman\Exception;
use Exception;
use Workerman\config\Errors;

/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/14
 * Time: 下午11:33
 */
class UserException extends \Exception
{
    public function __construct($code)
    {
        parent::__construct('', $code);
    }
}