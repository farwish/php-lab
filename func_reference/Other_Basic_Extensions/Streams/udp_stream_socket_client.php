<?php

$socket = stream_socket_client('udp://127.0.0.1:1113', $errno, $errstr, 30);

if (! $socket) {
    echo "$errstr ($errno)\n";die;
}

fwrite($socket, "Normal data transmit.\n");

stream_socket_sendto($socket, "Out of Band data.", 0);

$data = stream_socket_recvfrom($socket, 1024, 0);

echo "Receive data: {$data}";

fclose($socket);
