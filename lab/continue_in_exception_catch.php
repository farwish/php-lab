<?php

for ($i = 0; $i < 10; $i++) {
    try {
        throw new \Exception('test');
    } catch (\Exception $e) {
        if ($i == 8) {
            continue;
        }
        echo $i . PHP_EOL;
    }
}
