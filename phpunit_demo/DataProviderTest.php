<?php
/**
 * Specify Data provider.
 *
 * 为消费者方法 指定 数据提供者.
 *
 * @farwish
 */
class DataProviderTest extends \PHPUnit\Framework\TestCase
{
    public $data = [
        'zero plus zero' => [0, 0, 0],
        'zero plus one' => [0, 1, 1],
        'one plus zero' => [1, 0, 1],
        'one plus one' => [1, 1, 2],
    ];

    /**
     * @dataProvider additionProvider
     *
     */
    public function testAdd($a, $b, $expected)
    {
        $this->assertEquals($expected, $a + $b);
    }

    public function additionProvider()
    {
        return $this->data;
    }
}
