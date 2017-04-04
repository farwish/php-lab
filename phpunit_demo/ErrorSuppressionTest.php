<?php

use PHPUnit\Framework\TestCase;

/**
 * 用 @ 抑制错误，对返回值进行检查。
 *
 * @farwish
 */
class ErrorSuppressionTest extends TestCase
{
    public function testFileWriting()
    {
        $writer = new FileWriter;
        $this->assertFalse(@$writer->write('/is-not-writable/file', 'stuff'));
    }
}

class FileWriter
{
    public function write($file, $content)
    {
        $file = fopen($file, 'w');
        if ($file == false) {
            return false;
        }
    }
}
