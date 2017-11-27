<?php

/**
 * UDP server.
 *
 * Note:
 * For UDP sockets, you must use STREAM_SERVER_BIND as the flags parameter.
 *
 * @farwish
 */

// 监听 ipv6 地址，写 [::]，如 udp://[::]:1113
$socket = stream_socket_server('udp://127.0.0.1:1113', $errno, $errstr, STREAM_SERVER_BIND);

if (! $socket) {
    echo "$errstr ($errno)\n";die;
}

do {
    // Receives data from a remote socket up to length bytes, connected or not
    $data = stream_socket_recvfrom($socket, 1024, 0, $address);

    echo "Remote address: {$address}" . PHP_EOL;

    echo "Receive Data: {$data}";

    // Sends a message to a socket, whether it is connected or not
    stream_socket_sendto($socket, date('Y-m-d'), 0, $address);

} while ($data !== false);

