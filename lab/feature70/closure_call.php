<?php

/**
 * 简写法 临时绑定对象到一个closure并调用这个closure
 *
 * @author farwish <farwish@foxmail.com>
 */

class A
{
    private $x = 1;
}

$getX = function() {
    return $this->x;
};

/* Pre PHP7
$getXCB = $getX->bindTo(new A, 'A');
echo $getXCB();
 */

// PHP7+
echo $getX->call(new A);
