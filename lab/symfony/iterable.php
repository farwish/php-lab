<?php
/**
 * iterable used as a parameter type
 *
 * @farwish
 */

// Traversable 无法被单独实现的基本抽象接口。相反它必须由 IteratorAggregate 或 Iterator 接口实现。
// Traversable 是一个无法在 PHP 脚本中实现的内部引擎接口。

class Test implements IteratorAggregate
{
    // https://github.com/farwish/symfony/blob/master/src/Symfony/Component/HttpFoundation/ParameterBag.php#L222
    public function getIterator()
    {
        return new \ArrayIterator([
            'a',
            'b',
        ]);
    }
}

/**
 * As parameter type
 *
 * Parameters declared as iterable may use null or an array as a default value. 
 *
 * @require php7.1
 *
 * @link https://wiki.php.net/rfc/iterable
 */
function foo(iterable $iterable)
{
    foreach ($iterable as $v) {
        echo $v;
    }
} 

/**
 * As return type
 *
 */
function bar(): iterable 
{
    return ['a', 'b'];    
}

foo( (new Test)->getIterator() );

echo PHP_EOL;

print_r(bar());
