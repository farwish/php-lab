<?php

$mutex = new SyncMutex("UniqueName");

if (! $mutex->lock(3000)) {
    echo "Unable to lock mutex.\n";

    exit();
}

$mutex->unlock();
