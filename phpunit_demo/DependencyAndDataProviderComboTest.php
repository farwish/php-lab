<?php

use PHPUnit\Framework\TestCase;

/**
 * use depends and provider.
 * 综合使用依赖方法和数据提供者.
 *
 *  provider提供者的数据中"任何一组数组"都必须符合消费者要求。
 *
 * @farwish
 */
class DependencyAndDataProviderComboTest extends TestCase
{
    public $data = [
        ['provider1', 'provider2'],
    ];

    public function provider()
    {
        return $this->data;
    }

    public function testProducerFirst()
    {
        $this->assertTrue(true);
        return 'first';
    }

    public function testProducerSecond()
    {
        $this->assertTrue(true);
        return 'second';
    }

    /**
     * @dataProvider provider
     * @depends testProducerFirst
     * @depends testProducerSecond
     */
    public function testConsumer()
    {
        $this->assertEquals(
            ['provider1', 'provider2', 'first', 'second'],
            func_get_args()
        );
    }
}
