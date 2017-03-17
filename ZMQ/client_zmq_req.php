<?php

/**
 * ZMQ Client
 *
 * @farwish
 */
echo "Connecting to zmq_rep_server...\n";

const COUNT = 1000;

$con = new SplFixedArray(COUNT);

for ($i = 0; $i < COUNT; $i++) {
    $requester = (new ZMQContext())->getSocket(ZMQ::SOCKET_REQ, 1);
    $requester->connect("tcp://localhost:5555");
    $con[$i] = $requester;
}

for ($i = 0; $i < COUNT; $i++) {
    $reply = $con[$i]->send("Hello")->recv();
    echo " and received reply : {$reply}\n";
}

/*
// 初始化 ZMQSocket 对象
// $requester = new ZMQSocket(new ZMQContext(), ZMQ::SOCKET_REQ);
$requester = (new ZMQContext())->getSocket(ZMQ::SOCKET_REQ);

// 连接服务器

$requester->connect("tcp://localhost:5555");

for ($i = 0; $i < 10; $i++) {

    echo "Sending request {$i}\n";

    // 发送消息 & 接收消息
    $reply = $requester->send("Hello")->recv();

    echo " and received reply : {$reply}\n";

    sleep(1);
}
 */
