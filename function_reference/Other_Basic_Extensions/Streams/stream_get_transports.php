<?php

/**
 * Retrieve list of registered socket transports.
 *
 * Array
   (
    [0] => tcp
    [1] => udp
    [2] => unix
    [3] => udg
    [4] => ssl
    [5] => sslv3
    [6] => tls
    [7] => tlsv1.0
    [8] => tlsv1.1
    [9] => tlsv1.2
   )
 *
 * Test available transports for stream_socket_server() first param.
 *
 * @author farwish
 */

$transports = stream_get_transports();

print_r($transports);
