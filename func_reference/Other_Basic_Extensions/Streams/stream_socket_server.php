<?php

$protocal = 'tcp';

$local_socket = $protocal . '://127.0.0.1:9502';

// @see http://php.net/manual/en/function.stream-socket-server.php
$flags = ($protocal == 'udp') ? STREAM_SERVER_BIND : STREAM_SERVER_BIND | STREAM_SERVER_LISTEN;

$resource = stream_socket_server($local_socket, $errno, $errstr, $flags);

if (! $resource) {
    echo "$error: $errstr";
} else {
    while ($conn = stream_socket_accept($resource)) {
        fwrite($conn, "The local time is " . date('Y-m-d H:i:s') . "\n");
        fclose($conn);
    }
    fclose($resource);
}


