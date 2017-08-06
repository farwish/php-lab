<?php
/**
 * Socket Client
 *
 * create
 * write / read
 * connect
 *
 * @author farwish <farwish@foxmail.com>
 */

$sockfd = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

$conn_bool = socket_connect($sockfd, '127.0.0.1', 9501);

if ( $error_code = socket_last_error() ) {
    $msg = socket_strerror( $error_code );
    socket_clear_error();
    echo $msg;die;
}

$buf = "Hello";

if ( socket_write($sockfd, $buf, 100 ) ) {
    $context = socket_read($sockfd, 100);   

    echo $context . PHP_EOL;
}


