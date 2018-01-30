<?php

// similer to call pcntl_signal_dispatch() at the end or declare(ticks=1) at
// the top.
pcntl_async_signals(true);

pcntl_signal(SIGHUP, function($sig) {
    echo "SIGHUP\n";
});

posix_kill(posix_getpid(), SIGHUP);

//pcntl_signal_dispatch();
