<?php
/**
 * Echo Exception object will have many stack trace.
 *
 * @farwish
 */

require __DIR__ . '/../../vendor/autoload.php';

use Workerman\Worker;

Worker::$logFile = __DIR__ . '/workerman.log';

try {
    throw new Exception('Test exception info.');
} catch (\Exception $e) {
    // Notice it.
    Worker::log($e);
}

