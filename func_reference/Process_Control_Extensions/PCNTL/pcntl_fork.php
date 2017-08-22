<?php
/*
|--------------------------------
| only used in cli.
| @farwish
|--------------------------------
*/

function each_echo($prefix) {
	for ($i = 0; $i < 5; $i++) {
		echo "{$prefix} : {$i}\n";
		sleep(1);
	}
}

$pid = pcntl_fork();

if ($pid == -1) {
	// 创建失败
	die("pcntl_fork fail.\n");	
} else if ($pid) {
	// 父进程执行线程内返回子进程的PID
	// parent process
	each_echo("parent");
} else {
	// 子进程执行线程内返回0
	// child process
	each_echo("child");
    die;
}

