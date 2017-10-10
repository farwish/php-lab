<?php
/**
 * Regular Expression demo.
 *
 * @license MIT
 * @author farwish
 */
class Regexp
{
    private static $vars = [
        'fawisHH',
        'farwisHH',
        'farrwisHH',
        'farrrrwisHH',
    ];

    // 正则表达是由普通字符（非元字符的所有字符）以及特殊字符（元字符）组成的文字模式。
    // 描述在搜索文本时要匹配的一个或多个字符串。
    
    // 普通字符：非打印字符，字母/数字/标点/其它符号

    // 特殊字符：
    // $    =>  匹配字符结尾
    // ()   =>  子表达式的开始和结束，子表达式可以获取供以后使用，第一个子表达式就是 $matches[1]
    // +    =>  1，多
    // *    =>  0，1，多
    // ?    =>  0，1
    // .    =>  除换行符 \n 之外的任何字符
    // [    =>  简单字符组[123], 范围字符组[1-3], 组合字符组[1-3a-c], 排除字符组[^1-3]
    //          字符组运算[[1-3]&&[^2]], 
    //          预定义字符组 (digit) \d == [0-9], (word) \w == [a-zA-Z0-9_], (space) \s == [ \f\n\r\t\v]
    //                               \D == [^0-9],       \W == [^a-zA-Z0-9_],        \S == [^ \f\n\r\t\v]
    // \    =>  下一个字符标记为 或特殊字符，或原义字符，或向后引用，或八进制转义符
    // ^    =>  匹配字符开始，在方括号中表示排除字符集合
    // {    =>  限定符
    // |    =>  两项之间选择
    // 匹配特殊字符需要转义

    // 限定符：
    // +    =>  1个，多个
    // *    =>  0个，1个，多个
    // ?    =>  0个，1个
    // {n}  =>  n个
    // {n,} =>  至少n个
    // {n,m}=>  至少n个，至多m个

    // 定位符：
    // ^
    // $
    // \b   =>  匹配一个字符的边界，
    // \B   =>  非字边界

    // 选择
    // ?:   非捕获元，匹配 pattern 但不获取匹配结果， 如 (?:pattern)(pattern)  第一个匹配不被缓存
    // ?=   非捕获元，正向预查，在任何匹配 pattern 的字符串开始处匹配查找字符串
    // ?!   非捕获元，负向预查，在任何不匹配 pattern 的字符串开始处匹配查找字符串

    public static function print()
    {
        $p1 = "#far+wish#i";
        echo "pattern: {$p1}  1次，多次" . PHP_EOL;

        foreach (static::$vars as $v) {
            if (preg_match($p1, $v, $matches) ) {
                echo $matches[0] . PHP_EOL;
            }
        }

        echo "----------------" . PHP_EOL;

        $p2 = "#far*wish#i";
        echo "pattern: {$p2}  0次，1次，多次" . PHP_EOL;

        foreach (static::$vars as $v) {
            if (preg_match($p2, $v, $matches) ) {
                echo $matches[0] . PHP_EOL;
            }
        }

        echo "----------------" . PHP_EOL;

        $p3 = "#far?wish#i";
        echo "pattern: {$p3}  0次，1次" . PHP_EOL;

        foreach (static::$vars as $v) {
            if (preg_match($p3, $v, $matches) ) {
                echo $matches[0] . PHP_EOL;
            }
        }

        echo "----------------" . PHP_EOL;

        $p4 = "#f.*h#i";
        echo "pattern: {$p4}  贪婪模式，匹配尽可能多的字符" . PHP_EOL;

        foreach (static::$vars as $v) {
            if (preg_match($p4, $v, $matches) ) {
                echo $matches[0] . PHP_EOL;
            }
        }

        echo "----------------" . PHP_EOL;

        $p5 = "#f.*?h#i";
        echo "pattern: {$p5}  非贪婪模式，尽可能少的匹配" . PHP_EOL;

        foreach (static::$vars as $v) {
            if (preg_match($p5, $v, $matches) ) {
                echo $matches[0] . PHP_EOL;
            }
        }

        echo "----------------" . PHP_EOL;

        $p6 = "#f.+?h#i";
        echo "pattern: {$p6}  非贪婪模式" . PHP_EOL;

        foreach (static::$vars as $v) {
            if (preg_match($p6, $v, $matches) ) {
                echo $matches[0] . PHP_EOL;
            }
        }

        echo "----------------" . PHP_EOL;
        echo "通过在 + * ? 之后放置 ? , 表达式从‘贪婪’转换为‘非贪婪’的最小匹配" . PHP_EOL;
        echo "----------------" . PHP_EOL;

        $str = "Is it going up up";
        $pattern = '#\b([a-z]+) \1\b#';
        echo "pattern: {$pattern}  查找重复的单词" . PHP_EOL;

        if (preg_match($pattern, $str, $matches)) {
            echo $matches[1] . PHP_EOL;
        }
    }
}

Regexp::print();
