<?php

use PHPUnit\Framework\TestCase;

class SkippedTest extends TestCase
{
    protected function setUp()
    {
        if (! extension_loaded('mysql'))
        {
            $this->markTestSkipped('MySQL 扩展不可以.');
        }
    }

    /**
     * @requires PHP 5.3
     *
     */
    public function testConnection()
    {
        $this->assertTrue(true);
    }
}
