<?php

/**
 * php websocket_server.php start
 *
 * @farwish
 */
require __DIR__ . '/../../vendor/autoload.php';

use Workerman\Worker;

$ws_worker = new Worker("websocket://0.0.0.0:8081");

$ws_worker->count = 3;

$ws_worker->onConnect = function($connection) {
    echo "New connection\n";
};

$ws_worker->onMessage = function($connection, $data) {
    echo $data . "\n";
    while (1) {
        sleep(1);
        $connection->send("Ws server say: " . rand());
    }
};

$ws_worker->onClose = function($connection) {
    echo "Connection closed\n";
};

Worker::runAll();
