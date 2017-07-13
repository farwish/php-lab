<?php

class BaseState
{
    protected static $reflect;

    public static function getReflect()
    {
        if (! static::$reflect) {
            static::$reflect = new ReflectionClass(static::class);
        }

        return static::$reflect;
    }

    public static function value($name)
    {
        return static::getReflect()->getConstant($name);
    }

    public static function values()
    {
        $keys = static::getReflect()->getConstants();
        unset($keys['_LIST']);
        return $keys;
    }

    public static function name($value)
    {
        return static::names()[$value];
    }

    public static function names()
    {
        return array_flip(static::values());
    }

    public static function info($name)
    {
        return static::getReflect()->getConstants()['_LIST'][ static::value($name) ];
    }

    public static function list()
    {
        return static::getReflect()->getConstants()['_LIST'];
    }
}

/**
 * Class State.
 *
 * @farwish
 */
class State extends BaseState
{
    /**
     * status
     */
    const SUCCESS = 0;

    const INFO    = 1;

    const NOTICE  = 2;

    const WARNING = 3;

    const FAILURE = 4;

    const _LIST = [
        self::SUCCESS => '成功',
        self::INFO    => '信息',
        self::NOTICE  => '注意',
        self::WARNING => '警告',
        self::FAILURE => '失败',
    ];
}


echo State::SUCCESS . PHP_EOL;

echo State::INFO . PHP_EOL;

//echo State::$list[State::SUCCESS] . PHP_EOL;

//echo State::$list[State::INFO] . PHP_EOL;

// 反射 State 类
$reflect = new ReflectionClass('State');

// 获取一个常量值
echo $reflect->getConstant('SUCCESS') . PHP_EOL;

// 常量名对应值组成的数组
/*
Array
(
    [SUCCESS] => 0
    [INFO] => 1
    [NOTICE] => 2
    [WARNING] => 3
    [FAILURE] => 4
)
*/
print_r( $reflect->getConstants() );

// 类的头部注释
echo $reflect->getDocComment() . PHP_EOL;

// 类有多少行, 30
echo $reflect->getEndLine() . PHP_EOL;

echo $reflect->getExtensionName() . PHP_EOL;

$reflect2 = new ReflectionClass('Datetime');

// 获取定义的类所在扩展的名称：date
echo $reflect2->getExtensionName() . PHP_EOL;

// /home/www/php-lab/lab/ReflectClass.php
echo $reflect->getFileName() . PHP_EOL;

// []
print_r( $reflect->getInterfaceNames() );

// []
print_r( $reflect->getInterfaces() );

/*
Array
(
    [0] => DateTimeInterface
)
*/
print_r( $reflect2->getInterfaceNames() );

/*
Array
(
    [DateTimeInterface] => ReflectionClass Object
    (
        [name] => DateTimeInterface
    )
)
*/
print_r($reflect2->getInterfaces() );

print_r( $reflect->getMethods() );

// State
echo $reflect->getName() . PHP_EOL;

// 空
echo $reflect->getNamespaceName() . PHP_EOL;

/*
Array
(
    [0] => ReflectionProperty Object
    (
        [name] => list
        [class] => State
    )
)
*/
print_r( $reflect->getProperties() );

// State
echo $reflect->getShortName() . PHP_EOL;

/*
Array
(
    [list] => Array
    (
        [0] => 成功
        [1] => 信息
        [2] => 注意
        [3] => 警告
        [4] => 失败
    )
)
*/
print_r( $reflect->getStaticProperties() );

/*
Array
(
    [0] => 成功
    [1] => 信息
    [2] => 注意
    [3] => 警告
    [4] => 失败
)
*/
//print_r( $reflect->getStaticPropertyValue('list') );

echo $reflect->hasConstant('SUCCESS') . PHP_EOL;

echo $reflect->isUserDefined() . PHP_EOL;

// __toString
echo $reflect;

echo "Value SUCCESS = " . State::SUCCESS . PHP_EOL;

echo "Value SUCCESS = " . State::value('SUCCESS') . PHP_EOL;

echo "Key 0 name = " . State::name(0) . PHP_EOL;

echo "Info SUCCESS = " . State::info('SUCCESS') . PHP_EOL;

echo "=== Values === " . PHP_EOL;

print_r( State::values() );

echo "=== Names === " . PHP_EOL;

print_r( State::names() );

echo "=== List === " . PHP_EOL;
print_r( State::list() );