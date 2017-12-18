<?php
/**
 * array_unshift() the second param can be array.
 *
 * @author farwish
 */

$a = [];

$b = [
    [
        'id' => 1,
        'name' => 'tom',
    ],
];

array_unshift($a, [
    'id' => 2,
    'name' => 'jack',
]);

array_unshift($b, [
    'id' => 2,
    'name' => 'jack',
]);

print_r($a);
print_r($b);
