<?php

/**
 * 太空船运算符，按符号从左至右比较两个表达式,依次满足运算符的返回值是-1 0 1。
 *
 * @author farwish <farwish@foxmail.com>
 */

echo 1 <=> 1; // 0
echo 1 <=> 2; // -1
echo 2 <=> 1; // 1
