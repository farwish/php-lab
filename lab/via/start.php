<?php
/**
 * Via package.
 *
 * @license Apache-2.0
 * @author farwish <farwish@foxmail.com>
 */

include 'Container.php';

use Via\Container;

$socket = 'tcp://0.0.0.0:8080';

$con = new Container();

$con
    // Parameter.
    //
    // option, default is 1
    ->setCount(3)
    // option, can also be in constructor
    ->setSocket($socket)
    // option, default is Via
    ->setTitle('Via')
    // option, default is 100
    ->setBacklog(100)
    // option, default is 30
    ->setSelectTimeout(30)
    // option, default is 60
    ->setAcceptTimeout(60)

    // Event callback.
    //
    // option, when client connected with server, callback trigger.
    ->onConnection(function($connection) {
        echo "New client connected.\n";
    })
    // option, when client send message to server, callback trigger.
    ->onMessage(function($connection, $message) {
        fwrite($connection, "server say:{$message}");
    })

    // Start server.
    //
    ->start();

