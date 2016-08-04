<?php
/*
|--------------------------------------------
| cat the manual for detail
| ( http://php.net/manual/zh/ref.posix.php )
| @farwish
|--------------------------------------------
*/

// determine accessibility of a file
if ( posix_access('README.md', POSIX_F_OK) ) {
	echo "file exists.\n";
} else {
	echo "file not exists.\n";
}

if ( posix_access('README.md', POSIX_R_OK) ) {
	echo "file has read permission.\n";
} else {
	echo "file has no read permission.\n";
}

if ( posix_access('README.md', POSIX_W_OK) ) {
	echo "file has write permission.\n";
} else {
	echo "file has no write permission.\n";
}

if ( posix_access('README.md', POSIX_X_OK) ) {
	echo "file has execute permission.\n";
} else {
	echo "file has no execute permission.\n";
}

echo posix_ctermid() . "\n";  // pathname of controlling terminal

echo posix_errno() . "\n";  // alias of posix_get_last_error

echo posix_getcwd() . "\n";  // pathname of current dir

echo "effective group ID of current process: " . posix_getegid() . "\n"; 

echo "effective user ID of current process: " . posix_geteuid() . "\n"; 

echo "real group ID of current process: " . posix_getgid() . "\n"; 

print_r(posix_getgrgid(posix_getgid())) . "\n"; // info about a group by group id

print_r(posix_getgrnam('weichen')) . "\n"; // info about a group by name

print_r(posix_getgroups()) . "\n"; // the group set of current process

echo "login name: " . posix_getlogin() . "\n";

echo "process group id for job control: " . posix_getpgid(posix_getgid()) . "\n";

echo "current process group identifier: " . posix_getpgrp() . "\n";

echo "the current process identifier: " . posix_getpid() . "\n";

echo "the parent process identifier: " . posix_getppid() . "\n"; 

print_r(posix_getpwnam('weichen')) . "\n"; // info about a user by username

print_r(posix_getpwuid(posix_geteuid())) . "\n"; // info about a user by user id

print_r(posix_getrlimit()) . "\n"; // info about system resource limits

echo "the current sid of the process: " . posix_getsid(posix_getpid()) . "\n"; 

echo "the numeric real user ID of current process: " . posix_getuid() . "\n";

print_r( posix_initgroups('weichen', 1000) ) . "\n";

echo posix_isatty(STDOUT) . "\n"; // 确定一个文件描述符是否是一个交互终端

// 发送信号给当前进程, 中断进程
//posix_kill(posix_getpid(), WNOHANG); 

// 创建管道文件a.php, 已存在时fail
if (posix_mkfifo('a.php', 0644)) {
	echo "succ\n";
} else {
	echo "fail\n";
}

// 创建一个特殊或普通文件
if (posix_mknod('b.php', POSIX_S_IFREG)) {
	echo "posix_mknod succ\n";
} else {
	echo "posix_mknod fail\n";
}

// set the effective GID of the current process
// bool posix_setegid( int $gid ); // $gid: the group id.

// set the effective UID of the current process
// bool posix_seteuid( int $uid ); // $uid: the user id.

// set the real GID of the current process
// bool posix_setgid( int $gid ); // $gid: the group id.

// set process group id for job control
// bool posix_setpgid( int $pid, int $pgid ); // $pid: the process id. $pgid: the process group id

// set system resource limits
// bool posix_setrlimit( int $resource, int $softlimit, int $hardlimit );

// make the current process a session leader
// int posix_setsid( )

// set the UID of the current process
echo "posix_getuid : " . posix_getuid() . "\n";
posix_setuid(10001);
echo "posix_getuid after posix_setuid : " . posix_getuid() . "\n";

// retrieve the system error message associated with the given errno
echo "error message with given errno : " . posix_strerror(posix_get_last_error()) . "\n";

// get info about the current CPU usage.
print_r(posix_times());

// determine terminal device name
echo "terminal device name : " . posix_ttyname(STDOUT) . "\n";

// get system name
print_r(posix_uname());
