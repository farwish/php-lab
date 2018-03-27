#!/usr/bin/env php
<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Symfony\Component\Console\Application;

spl_autoload_register(function($class) {
    $file = __DIR__ . "/{$class}.php";
    if (file_exists($file)) {
        include $file;
    }
}, true, true);

/**
 * @doc http://symfony.com/doc/current/components/console/changing_default_command.html
 *
 */
$app = new Application();

$cmd = new HelloCommand();

// `php app.php app:hello`
$app->add($cmd)
    // `php app.php`
    ->getApplication()
    ->setDefaultCommand($cmd->getName())
    ->run();