# EventDispatcher

@see https://symfony.com/doc/4.1/components/event_dispatcher.html

实现了中介者模式, 使多个子类之间可以共享/修改变量, 项目更易于扩展。

1.建一个 listener 类，监听名如 kernel.response 的事件。

2.程序中调用 dispatcher 对象的 dispatch 方法，第一个参数为 kernel.response 事件，第二个参数把 listener 对象传给 dispatch。

3.dispatcher 得到所有监听 kernel.response 的 listener，执行它们。





