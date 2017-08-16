<?php
/**
 * 为信号设置闹钟(定时)信号.
 *
 * 设置一个定时器(timer)给进程发送 SIGALRM 信号.
 * 调用 pcntl_alarm() 会清除之前设置的闹钟.
 *
 * @link http://cn2.php.net/manual/en/function.pcntl-alarm.php
 * @license Apache
 * @author farwish <farwish@foxmail.com>
 */

// 1.
if (! pcntl_signal(SIGALRM, 'my_handler') ) {
    echo "Set signal handler failure" . PHP_EOL;
    die;
}

// 2.
// 设置定时发送SIGALRM信号
// 相当于3秒后执行 posix_kill( posix_getpid(), SIGALRM )
// 返回值：在此之前用 pcntl_alarm 设置的时间，没有则为0
pcntl_alarm(3);

sleep(3);

// 3.
pcntl_signal_dispatch();

function my_handler($signo)
{
    switch ($signo) {
        case SIGALRM:

            echo "Caught SIGALRM ..." . PHP_EOL;
            break;

        default:
            break;
    }
}
