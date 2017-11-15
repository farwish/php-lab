<?php

try {
    throw new \Exception('An exception.');

    Normal:

    echo "-----------\n";
} catch (\Exception $e) {

    goto Normal;

    echo "===========\n";
}
