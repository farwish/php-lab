<?php

/**
 * 测试继承 \JsonSerializeable 的作用.
 *
 * @farwish
 */
class Money implements \JsonSerializable
{
    /**
     * 用json_encode序列化本对象时, 对返回值序列化.
     *
     * 不实现抽象类, 直接序列化一个类的对象, 是一个空的json字串: {}
     *
     */
    public function jsonSerialize()
    {
        return [
            'money' => 10,
        ];
    }
}

$json = json_encode(new Money());

print_r($json);
