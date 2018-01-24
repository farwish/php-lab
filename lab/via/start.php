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

$con->setCount(3)
    ->setSocket($socket)
    ->setTitle('Via')
    ->setSelectTimeout(10)
    ->setAcceptTimeout(30)
    ->start();

