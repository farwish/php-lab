#!/bin/env php
<?php
/**
 * PHP解释器终端
 *
 * $ yum install readline-devel
 * $ cd php-7.1.4/
 * $ ./configure --with-readline
 *
 * @link http://www.laruence.com/2009/06/11/930.html
 */

$pid = posix_getpid();

$user = posix_getlogin();

echo <<<EOF
USAGE: [command | expression]
input php code to execute by fork a new process
input quit to exit
    Shell Executor.\n
EOF;

while (true) {
    $prompt = "[{$user}]$ ";
    $input = readline($prompt);

    readline_add_history($input);

    if ($input == 'quit') {
        break;
    }

    if ($input == '') {
        continue; 
    } else {
        process_execute($input);
    }
}

exit(0);

function process_execute($input)
{
    $pid = pcntl_fork();

    if ($pid == 0) {
        $pid = posix_getpid();

        echo "* Process {$pid} was created, and executed : \n";

        eval($input);

        die;
    } else {
        $pid = pcntl_wait($status, WUNTRACED);

        // 检测子进程状态码是否是正常退出(TRUE).
        if (pcntl_wifexited($status)) {
            echo "\n* Sub process {$pid} exited with status {$status}\n";
        }
    }
}

