<?php
/**
 * 双向链表
 *
 * SplDoublyLinkedList implements Iterator , ArrayAccess , Countable {}
 *
 * object(SplDoublyLinkedList)#1 (2) {
	  ["flags":"SplDoublyLinkedList":private]=>
	  int(0)
	  ["dllist":"SplDoublyLinkedList":private]=>
	  array(0) {
	  }
	}
 *
 * @farwish
 */

$dll = new SplDoublyLinkedList();

// $index 不能跳越赋值
$dll->add(0, 'hello');
$dll->add(1, 'hello2');
$dll->add(2, 'hello3');

$dll->push('A');

var_dump($dll);

echo "the value of the first node : " . $dll->bottom() . "\n";

echo "the number of elements in the doubly linked list : " . $dll->count() . "\n";

echo "the current node value : " . $dll->current() . "\n";

echo "SplDoublyLinkedList default mode is : " . $dll->getIteratorMode() . "\n"; // 0

// 有两组正交的mode可以设置, 默认是SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_KEEP
// 默认是管道型, 迭代数据不会删除. 改用下面的模式, 迭代数据时同时进行删除.
//$dll->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO);
//$dll->setIteratorMode(SplDoublyLinkedList::IT_MODE_DELETE);

echo "SplDoublyLinkedList mode after setted : " . $dll->getIteratorMode() . "\n"; // 1

echo "SplDoublyLinkedList is empty : " . ($dll->isEmpty() ? 'true' : 'false') . "\n";

echo "SplDoublyLinkedList current node index : " . $dll->key() . "\n"; // 0

echo "SplDoublyLinkedList offset 1 exists : " . ($dll->offsetExists(1) ? 'true' : 'false') . "\n";

echo "SplDoublyLinkedList value at offset 1 : " . $dll->offsetGet(1) . "\n";

$dll->offsetSet(1, 'hello2_dummy');
echo "SplDoublyLinkedList value at offset 1 after offsetSet : " . $dll->offsetGet(1) . "\n";

echo "unsets the value at the specified \$index 1\n";
$dll->offsetUnset(1);

echo "pops a node from the end of the doubly linked list : " . $dll->pop() . "\n";

echo "{-------------------\n";
echo "SplDoublyLinkedList rewind..\n";
$dll->rewind();
while ($dll->valid()) {
	echo "SplDoublyLinkedList current node value : " . $dll->current()."\n";
	echo "SplDoublyLinkedList next..\n";
	$dll->next();
}
echo "--------------------}\n";

$dll->rewind();
	echo "SplDoublyLinkedList current node value : " . $dll->current()."\n";
$dll->next();
	echo "SplDoublyLinkedList current node value : " . $dll->current()."\n";
$dll->prev();
	echo "SplDoublyLinkedList current node value : " . $dll->current()."\n";

echo "the value of the last node : " . $dll->top() . "\n";

echo "shifts a node from the beginning of the doubly linked list.. \n";
$dll->shift();
print_r($dll);

echo "prepends the doubly linked list with an element..\n";
$dll->unshift('hello_dummy');
print_r($dll);
