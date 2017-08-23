<?php

/**
 * 创建资源流上下文
 *
 * @link http://php.net/manual/zh/function.stream-context-create.php
 * @link http://php.net/manual/zh/context.php  上下文(context)参数和选项
 * @link http://php.net/manual/zh/context.socket.php    套接字上下文选项
 *
 * @author farwish
 */

// Socket context 选项
$opts = [
    'socket' => [
        'bindto' => '127.0.0.1:8080',
    ],
];

// Create the context
// options 必须是一个二维关联数组，默认空数组
// 之后用 stream_context_set_option 依然能在此上下文上补充设置参数 
$context = stream_context_create($opts);


// HTTP context 选项
/*
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: foo=bar\r\n"
  )
);

$context = stream_context_create($opts);

// Sends an http request to www.example.com with additional headers shown above
$fp = fopen('http://www.example.com', 'r', false, $context);
fpassthru($fp);
fclose($fp);
*/


