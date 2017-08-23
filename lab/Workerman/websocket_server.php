<?php

require __DIR__ . '/../../vendor/autoload.php';

use Workerman\Worker;

$ws_worker = new Worker("websocket://0.0.0.0:8080");

$ws_worker->count = 4;

$ws_worker->onConnect = function($connection) {
    echo "New connection\n";
};

$ws_worker->onMessage = function($connection, $data) {
    $connection->send($data);
};

$ws_worker->onClose = function($connection) {
    echo "Connection closed\n";
};

Worker::runAll();
