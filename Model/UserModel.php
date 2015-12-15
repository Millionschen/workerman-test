<?php
/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/15
 * Time: 上午10:23
 */

namespace Workerman\Model;


use Workerman\Lib\Db;
use workerman\Lib\DbConnection;

class UserModel
{
    /**
     * @var DbConnection $db
     */
    private $db;

    public function __construct()
    {
        $this->db = Db::instance('db');
    }

    public function findUserByName($name)
    {
        $user = $this->db->select('*')
            ->from('user')
            ->where('name = :name')
            ->bindValues(array('name'=>$name))
            ->row();
        return $user;
    }

}