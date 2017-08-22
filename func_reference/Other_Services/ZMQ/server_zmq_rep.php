<?php

/**
 * ZMQ Server.
 *
 * @farwish
 */

// 初始化 ZMQSocket 对象
// $responder = new ZMQSocket(new ZMQContext(), ZMQ::SOCKET_REP);
$responder = (new ZMQContext())->getSocket(ZMQ::SOCKET_REP, 1);

// 绑定 socket 地址
$responder->bind("tcp://0.0.0.0:5555");

while(true)
{
    // 接收消息
    $request = $responder->recv();

    printf("Received request: [%s]\n", $request);

    // 发送回复消息
    $responder->send( mt_rand() );
}
