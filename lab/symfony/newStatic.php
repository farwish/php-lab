<?php

class A {
  public static function get_self() {
    // 类自己
    return new self();
  }
 
  public static function get_static() {
    // 静态绑定：static 不再被解析成定义当前方法所在的类，而是在实际运行时计算的。
    return new static();
  }

  public static function who()
  {
    echo __CLASS__;
  }

  public function test()
  {
      self::who();

      // 绑定的是运行时类中的方法
      static::who();
  }
}
 
class B extends A {
    public static function who()
    {
        echo __CLASS__;
    }
}
 
echo get_class(B::get_self()); // A
echo get_class(B::get_static()); // B
echo get_class(A::get_static()); // A

B::test(); // AB
