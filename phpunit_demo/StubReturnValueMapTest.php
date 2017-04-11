<?php

use PHPUnit\Framework\TestCase;

include_once "SomeClass.php";

class StubReturnValueMapTest extends TestCase
{
    public function testReturnValueMapStub()
    {
        // create a stub for the SomeClass class.
        $stub = $this->createMock(SomeClass::class);        

        // create a map of arguments to return values.
        $map = [
            ['a', 'b', 'c', 'd'],
            ['e', 'f', 'g', 'h'],
        ];

        // configure the stub.
        $stub->method('doSomething')->will($this->returnValueMap($map));

        $this->assertEquals('d', $stub->doSomething('a', 'b', 'c'));
        $this->assertEquals('h', $stub->doSomething('e', 'f', 'g'));
    }
}
