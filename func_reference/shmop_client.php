<?php
$shmkey = 0x123456;

$size = 4096;

$shmid = shmop_open($shmkey, 'w', 0644, $size);

if ($shmid == -1) {
	die("create share memory failure\n");
}

$ret = shmop_read($shmid, 0, 256);

if (! $ret) {
	die("read from share memory failure\n");
}

shmop_close($shmid);

echo $ret;
