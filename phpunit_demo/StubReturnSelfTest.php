<?php

use PHPUnit\Framework\TestCase;

include_once "SomeClass.php";

class StubReturnSelfTest extends TestCase
{
    public function testReturnSelf()
    {
        // create a stub for the SomeClass class.
        $stub = $this->createMock(SomeClass::class);

        // configure the stub.
        $stub->method('doSomething')->will($this->returnSelf());

        // assertSame().
        $this->assertSame($stub, $stub->doSomething());
    }
}
