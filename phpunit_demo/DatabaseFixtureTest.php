<?php

use PHPUnit\Framework\TestCase;

/**
 * fixtures: 共享基境.
 *
 */
class DatabaseFixtureTest extends TestCase
{
    protected static $dbh;

    public static function setUpBeforeClass()
    {
        self::$dbh = new PDO('sqlite::memory:');
    }

    public static function tearDownAfterClass()
    {
        self::$dbh = null;
    }

    public function testDemo()
    {
        $this->assertTrue(true);
    }
}
