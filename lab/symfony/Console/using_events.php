<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;

$dispatcher = new EventDispatcher();

$app = new Application();

$app->setDispatcher($dispatcher);

$app->run();