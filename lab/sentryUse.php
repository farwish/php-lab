<?php

/**
 * sentry-php Client test. 
 *
 * @farwish
 */

require __DIR__ . "/../vendor/autoload.php";

class Base_Base
{
    use Alcon\Traits\SentryClientTrait;

    public function __construct($dsn = '')
    {
        $this->test = 'test';

        //( new \Alcon\Traits\SentryClientTrait() )->sentryInit();
        //$this->sentryInit();
        static::sentryRun($dsn);
    }
}

new Base_Base();
