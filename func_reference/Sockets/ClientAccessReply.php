#!/bin/env php
<?php
/**
 * Socket Request Reply Client
 *
 * 请求应答客户端.
 *
 * create
 * write / read
 * connect
 *
 * @license Apache
 * @author farwish <farwish@foxmail.com>
 */

$ip = '127.0.0.1';

$port = '9501';

$sock_read_length = 255 * 3;

$sockfd = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

$conn_bool = socket_connect($sockfd, $ip, $port);

if ( $error_code = socket_last_error() ) {
    $msg = socket_strerror( $error_code );
    socket_clear_error();
    echo $msg;die;
}

while ( $line = trim( fgets(STDIN) ) ) {

    if ( socket_write($sockfd, $line, mb_strlen($line) ) ) {
        $context = socket_read($sockfd, $sock_read_length);
        echo "From server : " . $context . PHP_EOL;
    }
}


