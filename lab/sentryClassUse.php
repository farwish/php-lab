<?php

/**
 * sentry-php Client test. 
 *
 * @farwish
 */

require __DIR__ . "/../vendor/autoload.php";

class Base_Base
{
    public function __construct()
    {
        $this->test = 'test';

        \Alcon\Traits\SentryClientClass::sentryRun('https://8dd7b553546745949820ddeb68044c96:4a1a9ce5de3e4a8fb839a75383565c62@sentry.io/160169');
    }
}

new Base_Base();

throw new Exception('SectryClientClass');
