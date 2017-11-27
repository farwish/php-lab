<?php
$shmkey = 0x123456;

$size = 4096;

$shmid = shmop_open($shmkey, 'c', 0644, $size);

if ($shmid == -1) {
	die("create share memory failure\n");
}

$data = "datas";

$ret = shmop_write($shmid, $data, 0);

if (! $ret) {
	die("write in share memory failure\n");
}

shmop_close($shmid);
