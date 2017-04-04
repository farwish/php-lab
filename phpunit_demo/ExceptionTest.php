<?php

use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    /**
     * expectedException InvalidArgumentException
     *
     */
    public function testException()
    {
        // 为被测代码所抛出的异常建立预期.
        $this->expectException(InvalidArgumentException::class);
    }
}
