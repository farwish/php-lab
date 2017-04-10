<?php

include "dbunit.phar";

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

/**
 * 自己定义测试用例的通用数据库抽象层.
 *
 * phpunit --configuration developer.xml -v ./
 *
 * 不使用configuration选项，默认使用 phpunit.xml 作为配置.
 *
 * 有几类数据集：XML DataSet(MySQL XML DataSet), 
 *              YAML DataSet, 
 *              CSV DataSet,
 *              Array DataSet.
 *
 * @farwish
 */
abstract class MyDatabaseTestCase extends TestCase
{
    use TestCaseTrait;

    static private $pdo = null;

    private $conn = null;

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo == new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }
        return $this->conn;
    }
}
