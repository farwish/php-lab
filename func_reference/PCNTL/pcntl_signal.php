<?php
/**
 * 信号控制.
 *
 * 为指定的信号(signo)安装处理器（callable函数/方法 或 SIG_IGN 或 SIG_DFL）.
 *
 * @link http://cn2.php.net/manual/en/function.pcntl-signal.php
 * @lincense Apache
 * @author farwish <farwish@foxmail.com>
 */

// pcntl_signal_dispatch() 比 declare(ticks = 1) 省内存

#declare(ticks = 1);

// 1. 安装信号处理器
$bool1 = pcntl_signal(SIGTERM, 'my_handler');
$bool2 = pcntl_signal(SIGHUP, 'my_handler');
$bool3 = pcntl_signal(SIGUSR1, 'my_handler');

if ($bool1 && $bool2 && $bool3) {
    echo "Installing signal handler ..." . PHP_EOL;

    // 2. Send a signal to a process.
    posix_kill( posix_getpid(), SIGUSR1 );

    // 3. 为待处理信号调用处理器
    pcntl_signal_dispatch();

    echo "Done." . PHP_EOL;

    echo "Memory use : " . memory_get_usage() . " bytes" . PHP_EOL;
} else {
    echo "Set signal failed." . PHP_EOL;
}

function my_handler($signo)
{
    switch ($signo) {
        case SIGTERM:

            echo "Caught SIGTERM" . PHP_EOL;
            exit;
            break;

        case SIGHUP:

            echo "Caught SIGHUP" . PHP_EOL;
            break;

        case SIGUSR1:

            echo "Caught SIGUSR1 ..." . PHP_EOL;
            break;
        
        default:
            break;
    }
}
