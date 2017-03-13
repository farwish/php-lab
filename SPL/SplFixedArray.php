<?php

/**
 * 同数据量情况:
 * 
 * 数据量很小的时候（循环10次）：
 *  用户调用和系统调用基本一致.
 *  内存占用一致.
 *  Array 稍快.
 *
 * 数据量中等的时候（循环100次）：
 *  用户调用和系统调用基本一致.
 *  内存占用基本一致，SplFixedArray 稍少.
 *  Array 稍快.
 *
 * 数据量较大的时候（循环>1000次）:
 *  用户调用和系统调用基本一致.
 *  SplFixedArray 内存占用少.
 *  SplFixedArray 更快
 *
 * @farwish
 */

$start = microtime();

$arr = [];

/*
for ($i = 0; $i < 10000; $i++)
{
    $arr[] = 10;
}
 */

$arr = new SplFixedArray(10000);

for ($i = 0; $i < 10000; $i++)
{
    $arr[$i] = 10;
}

$end = microtime();

echo "Memory use: " . memory_get_usage()/1024 . " KB\n";

echo "Time spend: " . ($end - $start) . "\n";

