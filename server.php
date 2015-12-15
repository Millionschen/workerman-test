<?php
/**
 * Created by PhpStorm.
 * User: millions
 * Date: 15/12/14
 * Time: 上午10:13
 */
use Workerman\Connection\TcpConnection;
use workerman\Util\MyLog;
use Workerman\Worker;
require_once 'Autoloader.php';

//开发模式, 会记录trace级别的log
define("dev", 1);

//创建一个worker 使用应用层协议json
$worker = new Worker("json://0.0.0.0:9797");

// 启动4个进程对外提供服务
$worker->count = 4;

function onWorkerStart($worker)
{
    //设置日志的worker id
    MyLog::$workerId = $worker->id;
}

//开始连接 进行初始化工作
function handleConnection($connection)
{
}

//断开时 进行清理工作
function handleClose($connection)
{
}

// 当客户端发来数据时 处理数据
/**
 * @param TcpConnection $connection
 * @param $data
 */
function onMessage($connection, $data)
{
    //记录进入数据
    MyLog::debug("[IN] [LEN] %s [USER_ID] %s [CMD] %x [body] %s ",
        $data['pack_len'],
        $data['user_id'],
        $data['cmd'],
        json_encode($data['body']));

    //根据cmd进行路由
    \Workerman\route\CrouteFunc::deal($data, $response);

    //记录发出数据
    MyLog::debug("[OUT] [USER_ID] %s [CMD] 0x%08x [RET] %s [body] %s ",
        $response['user_id'],
        $response['cmd'],
        $response['return_code'],
        json_encode($response['body']));

    //返回数据
    $connection->send($response);
}


$worker->onWorkerStart = 'onWorkerStart';
$worker->onConnect = 'handleConnection';
$worker->onMessage = 'onMessage';
$worker->onClose = 'handleClose';

// 运行worker
Worker::runAll();