<?php

/**
 * websocket 服务执行异步任务：
 *  websocket server 收到数据后，异步连接 task server，异步取得结果，不阻塞进程。
 *  不阻塞进程指的是，当前进程依旧可以处理其它客户端连接;
 *  单个进程内执行依旧是阻塞的，要等异步任务处理完后才能返回结果。
 *
 * @license Apache-2.0
 * @author farwish <farwish@foxmail.com>
 */

include __DIR__ . '/../../../vendor/autoload.php';

use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

$worker = new Worker('websocket://0.0.0.0:8080');
$worker->reusePort = true;

$worker->onMessage = function($ws_connection, $message) {

    // connect to task server.
    $task_connection = new AsyncTcpConnection('Text://127.0.0.1:12345');

    // data
    $task_data = [
        'function' => 'send_mail',
        'args'     => ['from' => '', 'to' => '', 'content' => ''],
    ];

    // 发送
    $task_connection->send( json_encode($task_data)  );

    // 监听返回消息事件
    $task_connection->onMessage = function($task_connection, $task_result) use ($ws_connection) {

        // task server return result
        print_r($task_result);

        // close async connection
        $task_connection->close();

        // notice websocket client.
        $ws_connection->send('task complete');
    };

    // 执行连接
    $task_connection->connect();

    /*
    sleep(15);

    $ws_connection->send('task complete');
     */
};

Worker::runAll();
