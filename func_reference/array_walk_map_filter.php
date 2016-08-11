<?php

$test = [
	'a' => 'good',
	'b' => 'great',
	'c' => 'perfect',
];

$testing = [
	['a' => 'good'],
	['b' => 'great'],
	['c' => 'perfect'],
];

/**
 * array_walk 作用于一维.
 *
 * 回调函数作用到每个数组元素, 会更改原数组
 */

array_walk($test, function(&$v, $k) {
	$v .= " - suffix";
});

/**
 * array_walk 作用于二维.
 *
 */

$tmp = "wonerful";
array_walk($testing, function(&$v, $k) use ($tmp) {
	$v['d'] = $tmp;

	array_walk($v, function(&$vv, $kk) use ($tmp) {
		$vv .= " - {$tmp}";
	});
});

print_r($test);
print_r($testing);


/**
 * array_map
 * 
 */



/**
 * array_filter
 *
 */
