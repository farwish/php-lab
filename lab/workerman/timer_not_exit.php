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
$worker = new Worker();

$worker->count = 4;

$worker->onWorkerStart = function($worker) {
    Timer::add(10, function() {
        echo "this is task.\n";
        exit;
    });

    try {
        while(true) {

        }
        throw new \Exception('异常了');
    } catch (\Exception $e) {
        print_r('aaaaaaaaaaa');
    }
};

Worker::runAll();
