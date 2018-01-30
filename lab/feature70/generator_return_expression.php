<?php

/**
 * 支持生成器最后用 return 返回一个值，这个值可以通过 getReturn 获取.
 *
 * @author farwish <farwish@foxmail.com>
 */

$gen= (function() {
    yield 1;
    yield 2;

    return 3;
})();

foreach ($gen as $val) {
    echo $val , PHP_EOL;
}

echo $gen->getReturn(), PHP_EOL;
