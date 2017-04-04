<?php

use PHPUnit\Framework\TestCase;

/**
 * phpunit -v StackTest.php
 *
 * -v : Output more verbose information.
 *
 * @farwish
 */
class StackTest extends TestCase
{
    public function testEmpty()
    {
        $stack = [];

        $this->assertEmpty($stack);

        return $stack;
    }

    /**
     * @depends testEmpty
     *
     */
    public function testPush(array $stack)
    {
        array_push($stack, 'foo');

        $this->assertEquals('foo', $stack[count($stack) - 1]);

        $this->assertNotEmpty($stack);

        return $stack;
    }

    /**
     * @depends testPush
     *
     */
    public function testPop($stack)
    {
        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }
}
