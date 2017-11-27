<?php

$stream = stream_socket_server('tcp://127.0.0.1:8000');

var_dump( stream_supports_lock($stream) ); // false
