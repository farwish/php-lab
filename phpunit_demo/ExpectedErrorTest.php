<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error;

class ExpectedErrorTest extends TestCase
{
    /**
     * @expectedException 
     *
     */
    public function testFailingInclude()
    {
        include 'not_existing_file.php';
    }
}
