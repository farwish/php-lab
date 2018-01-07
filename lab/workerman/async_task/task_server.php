<?php

/**
 * Task Server
 *
 * @license Apache-2.0
 */

include __DIR__ . '/../../../vendor/autoload.php';

use Workerman\Worker;

$task_worker = new Worker('Text://0.0.0.0:12345');

$task_worker->count = 15;
$task_worker->name = 'TaskWorker';
$task_worker->onMessage = function($connection, $task_data) {
    $task_data = json_decode($task_data, true);
    sleep(10);
    $task_result = [
        'msg' => 'success',
        'code' => 0,
    ];
    $connection->send( json_encode($task_result)  );
};

Worker::runAll();
