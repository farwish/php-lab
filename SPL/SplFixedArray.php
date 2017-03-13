<?php

/**
 * 同数据量:
 *  SplFixedArray 更省内存.
 *  用户调用和系统调用两者基本一样.
 *
 * @farwish
 */

/*
$arr = [];

for ($i = 0; $i < 100; $i++)
{
    $arr[] = 10;
}
 */

$arr = new SplFixedArray(100);

for ($i = 0; $i < 100; $i++)
{
    $arr[$i] = 10;
}

echo memory_get_usage();

