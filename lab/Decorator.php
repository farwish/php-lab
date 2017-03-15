<?php

/**
 * Decorator Design Pattern: base on object combine.
 *  instead of extends.
 *
 * @farwish
 */

interface DecoratorBase
{
}

/**
 * 现有功能类.
 *
 */
class Artist
{
    private $init;

    public function __construct($init = 'Artist')
    {
        $this->init = $init;
    }

    public function doit()
    {
        echo "here is {$this->init}\n";
    }
}

/**
 * 拓展功能类.
 *
 */
class Writer implements DecoratorBase
{
    private $collect;

    public function __construct($obj)
    {
        $this->collect = $obj;
    }

    public function doit()
    {
        $this->collect->doit(); 

        self::write();
    }

    public function write()
    {
        echo "im writer\n";
    }
}

class Singer implements DecoratorBase
{
    private $collect;

    public function __construct($obj)
    {
        $this->collect = $obj;
    }

    public function doit()
    {
        $this->collect->doit();

        self::sing();
    }

    public function sing()
    {
        echo "im singer\n";
    }
}

$artist = new Artist();
$artist->doit();

echo "----------------\n";

// inject
$writer = new Writer( $artist );
$writer->doit();

$singer = new Singer( $artist );
$singer->doit();


