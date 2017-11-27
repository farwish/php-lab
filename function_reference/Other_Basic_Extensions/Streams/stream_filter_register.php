<?php

/**
 *
 * stream_filter_register() allows you to implement your own filter
 * on any registered stream used with all the other filesystem functions.
 * 
 * stream_filter_register()
 * stream_filter_append()
 * stream_filter_prepend()
 * stream_filter_remove()
 *
 * @farwish
 */

/**
 * Define our filter class
 *
 * @link php.net/manual/en/class.php-user-filter.php
 */
class strtoupper_filter extends php_user_filter
{
    public function filter($in, $out, &$consumed, $closing)
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = strtoupper($bucket->data);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}

/**
 * Register our filter with php
 *
 * @link php.net/manual/en/function.stream-filter-register.php
 */
stream_filter_register('str_toupper', 'strtoupper_filter');

$fp = fopen('foo-bar.txt', 'w');

stream_filter_append($fp, 'str_toupper');

fwrite($fp, "line1\n");
fwrite($fp, "word2\n");
fwrite($fp, "aaaa");

fclose($fp);

readfile('foo-bar.txt');
