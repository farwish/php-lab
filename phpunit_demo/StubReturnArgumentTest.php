<?php

use PHPUnit\Framework\TestCase;

include_once "SomeClass.php";

/**
 * 为指定类创建mock.
 *
 * @farwish
 */
class StubReturnArgumentTest extends TestCase
{
    public function testReturnArgumentStub()
    {
        // create a stub for the SomeClass class.
        $stub = $this->createMock(SomeClass::class);

        // configure the stub.
        $stub->method('doSomething')->will($this->returnArgument(0));

        // method returns 'foo'.
        $this->assertEquals('foo', $stub->doSomething('foo'));

        // method returns 'bar'.
        $this->assertEquals('bar', $stub->doSomething('bar'));
    }
}
