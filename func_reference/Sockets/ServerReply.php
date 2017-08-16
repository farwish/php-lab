#!/bin/env php
<?php
/**
 * Socket Reply Server
 *
 * 回射服务器.
 * 
 * create
 * set_option
 * bind
 * listen
 * accept
 * read / write
 * close
 *
 * @license Apache
 * @author farwish <farwish@foxmail.com>
 */

$backlog = 50;

$optval = 2;

$ip = '127.0.0.1';

$port = '9501';

// Bytes
$sock_read_length = 255 * 3;


// 参数见：
// http://php.net/manual/en/function.socket-create.php
// SOL_TCP === getprotobyname('tcp')
//
// getprotobyname() returns the protocol number associated with the protocol number as per /etc/protocols 
$sockfd = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

// http://php.net/manual/en/function.socket-set-option.php
socket_set_option($sockfd, SOL_SOCKET, SO_REUSEADDR, $optval);

// 'port' is only used when binding an AF_INET socket.
$bind_bool = socket_bind($sockfd, $ip, $port);

// Be told to listen for incoming connections on socket.
// socket_listen() only to sockets of type SOCK_STREAM and SOCK_SEQPACKET
//
// Maximum of 'backlog' incomming connections will be quequed for processing.
// Maximum number of 'backlog' depend on Linux platform SOMAXCONN ( /proc/sys/net/core/somaxconn ).
// `man listen` for more detail.
$listen_bool = socket_listen($sockfd, $backlog);

while (true) {
    // Accept incoming connections on the socket.
    // http://php.net/manual/zh/function.socket-accept.php

    $new_sockfd = socket_accept($sockfd);

    if ($new_sockfd) {

        $buf = '';

        while ( $context = socket_read($new_sockfd, $sock_read_length) ) {
            echo "From client : " . $context . PHP_EOL;

            socket_send($new_sockfd, $context, mb_strlen($context), MSG_EOF);
        }

        //socket_write($new_sockfd, 'World', 100);
    }

    if ( $error_code = socket_last_error() ) {
        $msg = socket_strerror( $error_code );
        socket_clear_error();
        echo $msg;die;
    }
}

socket_close($sockfd);
