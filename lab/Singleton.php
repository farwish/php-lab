<?php

/**
 * Singleton Design.
 *
 * @farwish
 */

trait Singleton
{
    /**
     * Current object.
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Get instance.
     *
     * @return object
     */
    public static function getInstance()
    {
        if ( is_null(static::$instance) ) {
            static::$instance = new self();
        }

        return static::$instance;
    }
}

class Demo
{
    use Singleton;

    public function doit()
    {
    
    }
}

$contain = [];

for ($i = 0; $i < 100; $i++)
{
    //$contain[] = Demo::getInstance();
    $contain[] = new Demo();
}

print_r($contain);

echo (memory_get_usage() / 1024) . "KB\n";
