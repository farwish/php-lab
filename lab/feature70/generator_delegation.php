<?php

/**
 * 生成器可以用 yield from 结构使用其他生成器。
 *
 * @author farwish <farwish@foxmail.com>
 */

function gen()
{
    yield 1;
    yield 2;
    yield from gen2();
}

function gen2()
{
    yield 3;
    yield 4;
}

foreach (gen() as $val)
{
    echo $val , PHP_EOL;
}
