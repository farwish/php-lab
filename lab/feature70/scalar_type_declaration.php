<?php
/**
 * @doc http://php.net/manual/en/migration70.new-features.php
 *
 * @author farwish <farwish@foxmail.com>
 */

// Strict mode in action.
// 1. 严格模式：如果参数声明和返回类型声明和实际不匹配报Fatal error, 不会应用自动类型转换.
// @doc http://php.net/manual/en/control-structures.declare.php
declare(strict_types = 1);

// 2. Scalar type declaration: 参数声明类型支持更多类型，bool/float/int/string/iterable(7.1) 。
//     use ... access argument list in PHP5.6+，func_get_args() in earlier.
// 3. Return type declaration: 返回类型声明。
function sumOfInts(float ...$ints): float
{
    return array_sum($ints);
}

// 9.1
print_r(sumOfInts(2, 3, 4.1));

