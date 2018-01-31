<?php

/**
 * php Connect.php
 *
 * @see https://symfony.com/doc/2.7/components/event_dispatcher.html
 * @author farwish <farwish@foxmail.com>
 */

include __DIR__ . '/../../../vendor/autoload.php';
include 'CartListener.php';
include 'ComplainListener.php';
include 'Events.php';

use Symfony\Component\EventDispatcher\EventDispatcher;

$stock = 10;
$dispatcher = new EventDispatcher;

// 1. Listener.
$cart_listener = new CartListener($stock);

// 2. addListener has three arguments: event name; php callable; optional priority integer.
// 同一事件名，按数字从大到小优先级执行
$dispatcher->addListener(Events::PAY,    [$cart_listener, 'payAction'], 10);
$dispatcher->addListener(Events::PAY,    [$cart_listener, 'smsAction'], 15);

// 3. dispatch.
$dispatcher->dispatch(Events::PAY, $cart_listener);

echo "Stock after pay: " . ($stock = $cart_listener->getStock()) . PHP_EOL;


// 1.
$complain_listener = new ComplainListener($stock);
// 2.
$dispatcher->addListener(Events::REFUND, [$complain_listener, 'RefundAction']);
// 3.
$dispatcher->dispatch(Events::REFUND, $complain_listener);

echo "Stock after refund: " . ($stock = $complain_listener->getStock()) . PHP_EOL;

/*
Sms action.
Pay action.
Stock after pay:9
Refund action.
Stock after refund:10
 */
