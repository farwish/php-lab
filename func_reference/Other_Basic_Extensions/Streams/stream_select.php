<?php

/**
 * stream_select
 *
 * @link php.net/manual/en/function.stream-select.php
 *
 * @farwish
 */

$stream1 = stream_socket_server('tcp://127.0.0.1:8000');
$stream2 = stream_socket_server('tcp://127.0.0.1:8001');

$read = [$stream1, $stream2];

$write = null;

$except = null;

/**
 * Note：
 * 前三个参数都是引用类型，由于ZendEngine限制，不能直接传null
 * 所以要用临时变量或表达式代替。
 *
 */
if ( false === ($num = stream_select($read, $write, $except, 0)) )
{
    echo "stream_select error.\n";
} else if ($num > 0) {
    var_dump($num);
}

