<?php
/**
 * 后台执行命令, stdout 写入文件, stderr 不保存.
 *
 * nohup: 运行不受挂断影响的命令, 不在 stdout 输出.
 * ps aux | grep sleep 查看后台运行进程 sleep.php
 * 
 * 与 php ./sleep.php > ./tmp.log 均不能在Web环境(php-fpm)下执行.
 *
 * @farwish
 */

$cmd = 'nohup php ./sleep.php > ./tmp.log 2>/dev/null &';

exec($cmd);
