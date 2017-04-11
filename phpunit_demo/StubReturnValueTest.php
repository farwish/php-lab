<?php

use PHPUnit\Framework\TestCase;

include_once "SomeClass.php";

class StubReturnValueTest extends TestCase
{
    public function testStub()
    {
        // create
        $stub = $this->createMock(SomeClass::class);

        // configure
        //$stub->method('doSomething')->willReturn('foo');
        $stub->method('doSomething')->will($this->returnValue('foo'));

        // call
        $this->assertEquals('foo', $stub->doSomething());
    }
}
