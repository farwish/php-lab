<?php
/**
 * @access http://ip/php-lab/lab/alcon/HelperTest.php
 *
 * @farwish
 */

include __DIR__ . '/../../vendor/autoload.php';

echo '<pre>';

print_r($_SERVER);

echo \Alcon\Supports\Helper::full_server_name() . PHP_EOL;

echo \Alcon\Supports\Helper::full_url() . PHP_EOL;
