<?php

/**
 * 实例初始化 -> 调用.
 *
 * 测试调用50W次.
 *
 * @farwish
 */
class Test
{
    public $a;
    
    public function __construct($a)
    {
        $this->a = $a;
    }

    public function ab()
    {
        return $this->a;
    }
}

$obj = new Test(10);

for ($i = 0; $i < 500000; $i++) {

    //$obj = new Test(10);
    $obj->ab();

}
