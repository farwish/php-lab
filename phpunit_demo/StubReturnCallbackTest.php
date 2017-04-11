<?php

use PHPUnit\Framework\TestCase;

include "SomeClass.php";

class StubReturnCallbackTest extends TestCase
{
    public function testReturnCallbackStub()
    {
        $stub = $this->createMock(SomeClass::class);

        $stub->method('doSomething')->will($this->returnCallback('md5'));

        $this->assertEquals('e10adc3949ba59abbe56e057f20f883e', $stub->doSomething('123456'));
    }
}
