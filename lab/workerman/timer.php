<?php

/**
 * php socket_no_Listen.php start
 *
 * @farwish
 */
require __DIR__ . '/../../vendor/autoload.php';

use Workerman\Worker;
use Workerman\Lib\Timer;

// 不监听任何 socket 地址 ( 不创建套接字上下文 )
// Worker.php:1568
$worker = new Worker('text://127.0.0.1:8091');

$worker->count = 4;

$worker->reusePort = true;

$worker->onWorkerStart = function($worker) {
    $time_interval = 2.5;

    Timer::add($time_interval, function() {
        echo "task run\n";
    }, [], false);
};

Worker::runAll();
