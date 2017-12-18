<?php
/**
 * PDO基于键值的查询预处理
 *
 * @license Apache
 * @author farwish <farwish@foxmail.com>
 */

$pdo = new \PDO('mysql:host=127.0.0.1;dbname=xxx;port=3306', 'root', 'xxx');

// LIKE 查询预处理
$param1 = "上海";
$sql1 = "select * from sys_city where city_name like ?";
$stmt1 = $pdo->prepare($sql1);
if ($stmt1->execute([
    "%$param1%",
]) ) {
    $res1 = $stmt1->fetchAll(\PDO::FETCH_ASSOC);
    print_r($res1);
}

// IN 查询预处理
$param2 = [1,2,3];
$prepare = rtrim( str_pad('?', 2 * count($param2), ',?') , ',');
$sql2 = "select * from sys_city where city_id in($prepare)";
$stmt2 = $pdo->prepare($sql2);
if ($stmt2->execute($param2)) {
    $res2 = $stmt2->fetchAll(\PDO::FETCH_ASSOC);
    print_r($res2);
}

// 普通条件查询预处理
$param3 = "上海市";
$sql3 = "select * from sys_city where city_name = ?";
$stmt3 = $pdo->prepare($sql3);
if ($stmt3->execute([
    $param3,
])) {
    $res3 = $stmt3->fetchAll(\PDO::FETCH_ASSOC);
    print_r($res3);
}
