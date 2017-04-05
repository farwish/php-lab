<?php

use PHPUnit\Framework\TestCase;

/**
 * 通过setUp()建立基境，不再需要返回/传递参数.
 *
 * @farwish
 */
class StackFixtureTest extends TestCase
{
    protected $stack;

    /**
     *  每个测试方法都会运行一次setUp().
     *
     */
    protected function setUp()
    {
        $this->stack = [];
    }

    public function testEmpty()
    {
        $this->assertTrue( empty($this->stack) );
    }

    public function testPush()
    {
        array_push( $this->stack, 'foo' );
        $this->assertEquals('foo', $this->stack[count($this->stack) - 1]);
        $this->assertFalse( empty($this->stack) );
    }

    public function testPop()
    {
        array_push( $this->stack, 'foo' );
        $this->assertEquals('foo', array_pop($this->stack));
        $this->assertTrue( empty($this->stack) );
    }
}
