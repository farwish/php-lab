<?php

include __DIR__ . '/../../vendor/autoload.php';

use Workerman\WebServer;
use Workerman\Worker;

$web = new WebServer('http://0.0.0.0:80');

$web->count = 4;

$web->addRoot('www.demo.com', '/www');

Worker::runAll();
