<?php

use PHPUnit\Framework\TestCase;

class IncompleteTest extends TestCase
{
    public function testSomething()
    {
        $this->assertTrue(true);

        $this->markTestIncomplete('待实现');
    }
}
