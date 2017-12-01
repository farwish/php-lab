<?php

$microtime = explode(' ', microtime());

// 28位：14 + 微秒后半部分一般为8位，补足为10位 + 4位随机
$no = date('YmdHis') . str_pad(ltrim($microtime[0], '0.'), 10, 6) . mt_rand(1000, 9999);

echo $no . "\n";

