<?php

/**
 * Socket Server
 * 
 * create
 * set_option
 * bind
 * listen
 * accept
 * read / write
 * close
 *
 * @author farwish <farwish@foxmail.com>
 */

$sockfd = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

socket_set_option($sockfd, SOL_SOCKET, SO_REUSEADDR, 2);

$bind_bool = socket_bind($sockfd, '127.0.0.1', 9501);

$listen_bool = socket_listen($sockfd, 50);

while (true) {
    // Accept incoming connections on the socket.
    // http://php.net/manual/zh/function.socket-accept.php

    $new_sockfd = socket_accept($sockfd);

    if ($new_sockfd) {

        $buf = '';

        $context = socket_read($new_sockfd, 100);
        echo $context . PHP_EOL;

        //socket_write($new_sockfd, 'World', 100);

        socket_send($new_sockfd, 'World', 100, MSG_EOF);
    }

    if ( $error_code = socket_last_error() ) {
        $msg = socket_strerror( $error_code );
        socket_clear_error();
        echo $msg;die;
    }
}

socket_close($sockfd);
