<?php

/**
 * Open Internet or Unix domain socket connection.
 *
 * @author farwish
 */

// The stream will by default be opened in blocking mode.
// You can switch it to non-blocking mode by using stream_set_blocking().
$fp = stream_socket_client('tcp://127.0.0.1:9502', $errno, $errstr);

if (! $fp) {
    echo "$errno : $errstr" . PHP_EOL;
} else {
    fwrite($fp, "123");
    while (! feof($fp)) {
        echo fgets($fp, 1024);
    }
    fclose($fp);
}



