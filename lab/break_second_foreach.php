<?php

$arr = [
    1,
    2,
    3,
    4,
    5,
];

$arr2 = [
    'a',
    'b',
    'c',
    'd',
];

foreach ($arr2 as $vv) {
    foreach ($arr as $v) {
        if ($v == 3) {
            break;
        }
        echo $v . PHP_EOL;
    }

    echo $vv . PHP_EOL;
}
