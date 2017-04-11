<?php

use PHPUnit\Framework\TestCase;

include "SomeClass.php";

class StubThrowExceptionTest extends TestCase
{
    public function testThrowExceptionStub()
    {
        $stub = $this->createMock(SomeClass::class);

        $stub->method('doSomething')->will($this->throwException(new \Exception));

        $stub->doSomething();
    }
}
