<?php

include __DIR__ . '/../../vendor/autoload.php';

use Workerman\Worker;

$tcp = new Worker('tcp://0.0.0.0:2346');

$tcp->count = 4;
$tcp->reusePort = true;

$tcp->onConnect = function($connection) {
    echo "New connection\n";
};

$tcp->onMessage = function($connection, $data) {
    $connection->send("hello\n");
};

$tcp->onClose = function($connection) {
    echo "Connection closed\n";
};

Worker::runAll();
