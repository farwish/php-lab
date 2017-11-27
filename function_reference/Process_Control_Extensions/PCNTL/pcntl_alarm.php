<?php
/**
 * 为信号设置闹钟(定时)信号.
 *
 * 设置一个定时器(timer)给进程发送 SIGALRM 信号.
 * 调用 pcntl_alarm() 会清除之前设置的闹钟，如 pcntl_alarm(0)
 *
 * @link http://cn2.php.net/manual/en/function.pcntl-alarm.php
 * @license Apache
 * @author farwish <farwish@foxmail.com>
 */

// 1.
// 安装一个信号处理器
if (! pcntl_signal(SIGALRM, ['\Test', 'handler']) ) {
    echo "Set signal handler failure" . PHP_EOL;
    die;
}

echo "installed signal\n";

// 2.
// 设置定时发送SIGALRM信号
// 相当于3秒后执行 posix_kill( posix_getpid(), SIGALRM )
// 如果脚本在3秒之前执行完了，那么信号处理器不会调用
// 返回值：在此之前用 pcntl_alarm 设置的时间，没有则为0
pcntl_alarm(3);

sleep(5);

echo "after sleep\n";

// pcntl_alarm(0);
// Will cancel any previously set alarm.
// If seconds is zero, no new alarm is created.
// 本行作用只是清除之前的alarm.

// 3.
// 为等待的信号调用信号处理器
// 由于调用了 dispatch 才能触发 pcntl_signal 安装的信号处理器
// 所以 Caught SIGALRM 在输出 after sleep 之后。
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

class Test
{
    public static function handler($signo)
    {
        switch ($signo) {
            case SIGALRM:

                echo "Caught SIGALRM ..." . PHP_EOL;
                break;

            default:
                break;
        }
    }
}
