<?php

use PHPUnit\Framework\TestCase;

include "SomeClass.php";

class StubOnConsecutiveCallsTest extends TestCase
{
    public function testOnConsecutiveCallsStub()
    {
        $stub = $this->createMock(SomeClass::class);

        $stub->method('doSomething')->will($this->onConsecutiveCalls(2, 3, 5, 7));

        $this->assertEquals(2, $stub->doSomething());
        $this->assertEquals(3, $stub->doSomething());
        $this->assertEquals(5, $stub->doSomething());
        $this->assertEquals(7, $stub->doSomething());
    }
}
