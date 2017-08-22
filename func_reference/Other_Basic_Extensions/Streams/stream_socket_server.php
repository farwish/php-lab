<?php

/**
 * Create an Internet or Unix domain server socket.
 * 
 * Execute system socket(), bind(), listen() calls.
 *
 * @author farwish
 */

$sock = stream_socket_server("tcp://0.0.0.0:9502", $errno, $errstr);

if (! $sock) {
    echo "$errno : $errstr" . PHP_EOL;
} else {
    echo 'Remote socket name : ' . stream_socket_get_name($sock, true) . PHP_EOL;
    echo 'Local socket name : ' . stream_socket_get_name($sock, false) . PHP_EOL;

    // Accept a connection on a socket.
    //
    // stream_socket_accept Should not be used with UDP server sockets.
    // UDP use stream_socket_recvfrom and stream_socket_sendto instead.
    while ( $conn = stream_socket_accept($sock) ) {
        fwrite($conn, date('Y-m-d H:i:s'));
        fclose($conn);
    }
    fclose($sock);
}
