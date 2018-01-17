<?php
/**
 * Multi process program theory.
 *
 * @see https://github.com/farwish/alcon/blob/master/src/Supports/Helper.php#L477
 *
 * @license Apache-2.0
 * @author farwish <farwish@foxmail.com>
 */

$count = 4;
$pids  = [];

echo "Current posix_getpid is " . posix_getpid() . PHP_EOL;

for ($i = 0; $i < $count; $i++) {

    $pid = pcntl_fork();

    switch ($pid) {
        case -1:
            throw new \Exception("Fork failed.\n");
            break;
        case 0:
            // Child.
            sleep( rand(2,20) );
            echo "Child posix_getpid is " . posix_getpid() . PHP_EOL;
            die;
            break;
        default:
            // Parent.
            echo "Parent posix_getpid is " . posix_getpid() . PHP_EOL;
            echo "Parent pid is " . $pid . PHP_EOL;
            $pids[] = $pid;
            break;
    }
}

print_r($pids);

// Monitor
$num = 0;
foreach ($pids as $pid) {
    $exited_child_pid = pcntl_waitpid($pid, $status, WUNTRACED);
    if ($exited_child_pid) {
        $num++;
    }
}

echo $num . ' child exited.' . PHP_EOL;

/*
    fork 返回两次，0 时是子进程空间，大于0是父进程空间。

    从以下输出得出结论：
        Parent 分支其实是在主进程内执行的，这里面可以拿到子进程id。
        为了不阻塞主进程，所以任务都是在 Child 分支内执行，子进程可以通过 posix_getpid() 得到自己的进程id.
        Child 模拟不同的耗时任务，主(父)进程已经执行完毕，子进程还在执行；各个子进程执行完毕后，调用退出结束进程。
        在 Child 进程全部退出前，主进程执行完毕结束了，那么子进程就成了孤儿进程; 所以 waitpid 让主进程阻塞等待所有子进程退出后。
        如果一直运行不退出的服务器程序，那么就需要主进程对子进程状态进行监控，需要始终运行(无限循环监听)或者一个子进程满足一定条件后退出后重新拉起防止内存泄露。

    Current posix_getpid is 62353
    Parent posix_getpid is 62353
    Parent pid is 62354
    Parent posix_getpid is 62353
    Parent pid is 62355
    Parent posix_getpid is 62353
    Parent pid is 62356
    Parent posix_getpid is 62353
    Parent pid is 62357
    Array
    (
        [0] => 62354
        [1] => 62355
        [2] => 62356
        [3] => 62357
    )
    Child posix_getpid is 62354
    Child posix_getpid is 62355
    Child posix_getpid is 62356
    Child posix_getpid is 62357

*/
