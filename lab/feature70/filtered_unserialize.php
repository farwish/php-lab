<?php

/**
 * 对 unserialize 设置白名单类名，不反序列化不受信任的数据。
 *
 * @author farwish <farwish@foxmail.com>
 */

class Foo
{
    private $foo;
}

$foo = new Foo;

$str = serialize($foo);

// default behaviour
// unserialize($str, ["allowed_classes" => true]);

// convert objects into __PHP_Incomplete_Class object.
// unserialize($str, ["allowed_classes" => false]);

// convert objects into __PHP_Incomplete_Class object except those of MyClass.
$data = unserialize($str, ['allowed_classes' => ['MyClass']]);

print_r($data);
