<?php

include __DIR__ . '/../../vendor/autoload.php';

use Workerman\Worker;

$http = new Worker('http://0.0.0.0:2345');

$http->count = 4;

$http->onMessage = function($connection, $data) {
    $connection->send("hello\n");
};

Worker::runAll();
