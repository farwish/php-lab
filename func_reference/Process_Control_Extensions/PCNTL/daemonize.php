<?php
/**
 * Run as daemon mode.
 * 
 * @farwish MIT-license
 */

umask(0);

$pid = pcntl_fork();

switch ($pid) 
{
    case -1:
        throw new \Exception("Fork fail.\n");
        break;
    case 0:
        // child, because it can always get pid by posix_getpid();

        $pid_file = __FILE__ . '.status';

            // pid file is exists ?
        if ( file_exists($pid_file) && ($master_pid = file_get_contents($pid_file))
            // is valid process pid ?
            && posix_kill($master_pid, 0)
            // current pid is not running pid.
            && $master_pid != posix_getpid()
        ) {
            exit("$argv[0] already run, you can kill it using `kill $master_pid`.\n");
        }

        // make the current process a session leader.
        $sid = posix_setsid();

        if ($sid === -1) {
            throw new \Exception("setsid failed.\n");
        }

        // belows for compatible with ...
        // 再次 fork 的目的是确保本守护进程将来即使打开了一个终端设备，也不会自动获得控制终端。
        // 当没有控制终端的一个会话头进程打开一个终端设备时，该终端自动成为这个会话头进程的控制终端。
        // 然而再次调用 fork 之后，我们确保新的子进程不再是一个会话头进程，从而不能自动获得一个控制终端。

        /*
        $pid = pcntl_fork();

        if (-1 === $pid) {
            throw new \Exception("Form fail2.\n");
        } else if ($pid > 0) {
            // parent, terminate the current process.
            exit(0);
        }
         */

        echo "$argv[0] run succeed.\n";

        file_put_contents($pid_file, posix_getpid());

        // accept request.
        while (1) {
        }

        break;
    default:
        // parent, terminate the current process.
        exit(0);
        break;

}
