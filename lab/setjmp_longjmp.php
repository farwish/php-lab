<?php
/**
 * setjmp longjmp is not implemented.
 *
 * @author farwish <farwish@foxmail.com>
 */
$i = setjmp();

print_r("before i is {$i}\n");

if ($i == 0) {
    f2();

    print_r("this will not be printed.\n");
}

// 3
print_r("after i is {$i}\n");

function f2()
{
    print_r("f2 called.\n");

    longjmp();
}
