<?php
/**
 * Multiple dependencies test.
 *
 * 消费者方法按顺序依赖其它方法提供的数据作为参数.
 *
 * @farwish
 */
class MultipleDependenciesTest extends \PHPUnit\Framework\TestCase
{
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
     * @depends testProducerFirst
     * @depends testProducerSecond
     *
     */
    public function testConsumer()
    {
        $this->assertEquals(
            ['first', 'second'],
            func_get_args()
        );
    }
}
