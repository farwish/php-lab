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
        'bindto'  => '127.0.0.1:8080',
        'backlog' => '10240' // `man listen`, 默认是 SOMAXCONN，为 128; 超过 /proc/sys/net/core/somaxconn 按此值
        // 通过 /etc/sysctl.conf 加入 net.core.somaxconn = 10240 设置，sysctl -p 使生效
        // 这里牵涉到了一些linux内核参数调优，这不可避免, 高性能需要各部分软件的一起提升。
    ],
];

// Create the context
// options 必须是一个二维关联数组，默认空数组
// 之后用 stream_context_set_option 依然能在此上下文上补充设置参数 
$context = stream_context_create($opts);

var_dump($context);


//=====================================================

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


