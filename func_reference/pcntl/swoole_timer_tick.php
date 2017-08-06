<?php

swoole_timer_tick(5000, function() {
    $date_time = date('Y-m-d');
});
