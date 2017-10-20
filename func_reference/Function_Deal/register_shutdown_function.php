<?php

function shutdown()
{
    echo "Script executed with success", PHP_EOL;
}

register_shutdown_function('shutdown');
