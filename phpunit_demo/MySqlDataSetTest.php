<?php

include "dbunit.phar";

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

/**
 * 使用数据库扩展的测试要实现 getConnection 和 getDataSet.
 *
 * @farwish
 */
class MySqlDataSetTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        $database = 'alconseek';
        $user = 'root';
        $password = '123456';
        $pdo = new PDO('mysql:dbname=alconseek;host=localhost', $user, $password);
        return $this->createDefaultDBConnection($pdo, $database);
    }

    public function getDataSet()
    {
        return $this->getConnection()->createDataSet();
    }

    public function testFilteredGuestbook()
    {
        $tableNames = ['article'];
        $dataSet = $this->getConnection()->createDataSet($tableNames);
        // ...
    }

    /**
     * getRowCount() 取得表中行数.
     *
     */
    public function testGetRowCount()
    {
        $this->assertEquals(3, $this->getConnection()->getRowCount('article'));
    }
}
