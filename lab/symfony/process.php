<?php

include __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

$process = new Process('ls -lsa');
$process->run();

// executes after the command finishes
if (!$process->isSuccessful()) {
    throw new ProcessFailedException($process);
}

echo $process->getOutput();
