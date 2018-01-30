<?php

$data = [
    [1, 'Tom'],
    [2, 'Fred'],
];


// list() style.
list($id1, $name1) = $data[0];
echo $id1 . ' ' . $name1 . PHP_EOL;

// [] style.
[$id2, $name2] = $data[1];
echo $id2 . ' ' . $name2 . PHP_EOL;

echo "---\n";

// list() style.
foreach ($data as list($id, $name)) {
    echo $id . ' ' . $name . PHP_EOL;
}

// [] style.
foreach ($data as [$id, $name]) {
    echo $id . ' ' . $name . PHP_EOL;
}
