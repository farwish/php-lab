<?php

$it = new \ArrayIterator([
    [
        'Name' => 'A',
        'Value' => 'AV',
    ],
    [
        'Name' => 'B',
        'Value' => 'BV',
    ],
]);

for ($it; $it->valid(); $it->next()) {
    print_r($it->current());
}
