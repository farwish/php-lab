<?php

$data = [
    ['id' => 1, 'name' => 'Tom'],
    ['id' => 2, 'name' => 'Fred'],
];

$id = $name = '';

while (['id' => $id, 'name' => $name] = $data) {
    echo "{$id} {$name}\n";
}
