<?php

/**
 * 静态初始化 -> 调用.
 *
 * 测试调用50W次.
 *
 * @farwish
 */
class Tester
{
    public static $a;

    public static function init($a)
    {
        static::$a = $a;
    }

    public static function ab()
    {
        return static::$a;
    }
}

Tester::init(10);

for ($i = 0; $i < 500000; $i++) {

    //Tester::init(10);
    Tester::ab();

}
